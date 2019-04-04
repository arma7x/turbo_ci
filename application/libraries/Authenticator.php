<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticator {

	// https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence

	protected $CI;
	protected $user_table = 'users';
	protected $remember_token_table = 'remember_tokens';
	protected $remember_token_name = 'remember_me';
	protected $activation_token_table = 'activation_tokens';
	protected $reset_token_table = 'reset_tokens';
	protected $default_role = 127; // lowest value has more power, 0 is lowest value
	protected $default_access_level = 127; // lowest value has more power, 0 is lowest value
	protected $default_status = 1; // -1:BAN, 1:ACTIVE, 0:INACTIVE
	protected $default_avatar = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAAAAAA7VNdtAAAACXBIWXMAAAA4AAAAOABi1B8JAAABTklEQVRIx+3Tv0vDUBDA8f6/p8FBLSQgaCtNoThEoXWoDgYEK4joIlRcRMTBH1DrIFXrL1rEKjGS5FwcFOzLu9w9R8Hv/D7Lvbvcx6/L/ZO/Q7CzVXNdb70VGxI8mYXv7GZsQt7rMFS5L5PABSX7TiK4AKmmXgTSBK0lZMnbhE6gw5I9QsAySzyKTMYMScYpAjcMCUgBpwwZ0OSYISFNzhiCeZI8chOrUcJOOHJIkVX2XyJHF9Y9v2NHOmlIm+ynRSmS7iVaVEXxWb5K3LSGNz8wOGS8Kv2I/DnK5Dp1lpU28iTesLSJ+SFHBnPUVxZ62eRpml5Lu5tFXmcgI6dHk2QeMiuEJNkGphUkyO0YR6ClE/RYAYVYI20Q2tdIVSJFTJH+iETgMkV2RAF+ilRk4iQKCUdlAg8KuTAQcKCQXRPSUMiaCakqpG5Cyl9vPwHZXW4PhaKQ+wAAAABJRU5ErkJggg==';

	public function __construct() {
		$this->CI = &get_instance();
		$this->validate_remember_token();
	}

	public function __get($key) {
		return isset($this->$key) ? $this->$key : NULL;
	}

	public function get_remember_token($index) {
		return $this->CI->db->select('id, user_agent, last_used')
			->get_where($this->remember_token_table, $index)
			->result_array();
	}

	public function remove_remember_token($index) {
		if ($index['id'] === $this->get_current_remember_token()) {
			return FALSE;
		}
		$this->CI->db->delete($this->remember_token_table, $index);
		return $this->CI->db->select('id')->get_where($this->remember_token_table, $index, 1)->row_array() === NULL;
	}

	public function get_current_remember_token() {
		$value = $this->CI->input->cookie($this->remember_token_name, TRUE);
		if ($value !== NULL) {
			return explode('__', $value)[0];
		}
		return '';
	}

	public function get_user_by_index($index, $select) {
		if ($select === NULL) {
			return $this->CI->db->get_where($this->user_table, $index, 1)->row_array();
		} else {
			return $this->CI->db->select($select)->get_where($this->user_table, $index, 1)->row_array();
		}
	}

	public function save_user($data) {
		if ($this->get_user_by_index(array('id' => $data['id']), 'id') !== NULL) {
			return FALSE;
		}
		return $this->CI->db->insert($this->user_table, $data);
	}

	public function update_user_by_index($index, $data) {
		if ($this->get_user_by_index($index, 'id') === NULL) {
			return FALSE;
		}
		return $this->CI->db->update($this->user_table, $data, $index);
	}

	public function generate_password_safe_length($string) {
		return base64_encode(hash('sha384', $string, TRUE));
	}

	public function validate_credential($index, $password, $remember_me) {
		$user = $this->get_user_by_index($index, NULL);
		if ($user === NULL) {
			return FALSE;
		}
		if ((int) $user['status'] < 1) {
			return (int) $user['status'];
		}
		$this->CI->load->library('encryption');
		$current_password = $this->CI->encryption->decrypt($user['password']);
		$match = password_verify($this->generate_password_safe_length($password), $current_password);
		if ($match) {
			$this->CI->db->delete($this->activation_token_table, array('user' => $user['id']));
			$this->CI->db->delete($this->reset_token_table, array('user' => $user['id']));
			$this->update_user_by_index($index, array('last_logged_in' => time()));
			$jti = NULL;
			if ($remember_me) {
				$jti = $this->generate_remember_token($user['id']);
			}
			$this->CI->jwt->generate($jti, array('uid' => $user['id']));
			return TRUE;
		}
		return FALSE;
	}

	public function store_credential($data) {
		$this->CI->load->library('encryption');
		$password = $this->CI->encryption->encrypt(password_hash($this->generate_password_safe_length($data['password'], TRUE), PASSWORD_DEFAULT));
		$data['id'] = bin2hex($this->CI->security->get_random_bytes(5));
		$data['password'] = $password;
		$data['role'] = ($data['email'] === APP_ADMIN_EMAIL) ? 0 : $this->default_role;
		$data['access_level'] = ($data['email'] === APP_ADMIN_EMAIL) ? 0 : $this->default_access_level;
		$data['status'] = $this->default_status;
		$data['avatar'] = $this->default_avatar;
		$data['created_at'] = time();
		$data['updated_at'] = time();
		$data['last_logged_in'] = time();
		$mail = array(
			'user' => $data,
			'url' => $this->CI->config->item('base_url'),
		);
		$result = $this->save_user($data);
		if ($result) {
			$this->send_email($mail, 'email_templates/registration.php', 'Welcome Email');
			if ($data['status'] === 0) {
				$this->issue_activation_token(array('id' => $data['id']));
			}
		}
		return $result;
	}

	public function update_credential($index, $old_password, $new_password) {
		$success = $this->validate_credential($index, $old_password, FALSE);
		if ($success === TRUE) {
			$this->CI->load->library('encryption');
			$password = $this->CI->encryption->encrypt(password_hash($this->generate_password_safe_length($new_password), PASSWORD_DEFAULT));
			$data = array(
				'password' => $password,
				'updated_at' => time(),
			);
			return $this->update_user_by_index($index, $data);
		}
		return FALSE;
	}

	public function set_remember_cookie($value) {
		$expired = (60 * 60 * 24 * 365);
		$secure_cookie = (bool) $this->CI->config->item('cookie_secure');
		if (is_https()) {
			$secure_cookie = TRUE;
		}
		$this->CI->input->set_cookie(array(
				'name'   => $this->remember_token_name,
				'value'  => $value,
				'expire' => $expired,
				'domain' => $this->CI->config->item('cookie_domain'),
				'path'   => $this->CI->config->item('cookie_path'),
				'secure' => $secure_cookie,
				'httponly' => TRUE
			)
		);
	}

	public function generate_remember_token($user_id) {
		$id = bin2hex($this->CI->security->get_random_bytes(8));
		$validator = bin2hex($this->CI->security->get_random_bytes(10));
		$hash_validator = hash('sha384', $validator);
		$data = array(
			'id' => $id,
			'validator_hash' => $hash_validator,
			'user' => $user_id,
			'user_agent' => $this->CI->input->user_agent(TRUE),
			'last_used' => time()
		);
		$this->CI->db->insert($this->remember_token_table, $data);
		$this->set_remember_cookie($id.'__'.$validator);
		return $id;
	}

	public function validate_remember_token() {
		if ($this->CI->jwt->token->hasClaim('uid') === FALSE) {
			$value = $this->CI->input->cookie($this->remember_token_name, TRUE);
			if ($value !== NULL) {
				$id__validator = explode('__', $value);
				if (count($id__validator) > 1) {
					$token = $this->CI->db->get_where($this->remember_token_table, array('id' => $id__validator[0]), 1)->row_array();
					if ($token !== NULL) {
						if (hash_equals(hash('sha384', $id__validator[1]), $token['validator_hash'])) {
							$user = $this->get_user_by_index(array('id' => $token['user']), NULL);
							if ($user !== NULL) {
								if ((int) $user['status'] === 1) {
									$this->CI->jwt->generate($id__validator[0], array('uid' => $user['id']));
									$this->update_user_by_index(array('id' => $user['id']), array('last_logged_in' => time()));
									$this->CI->db->update($this->remember_token_table, array('last_used' => time()), array('id' => $id__validator[0]));
									$this->set_remember_cookie($value);
								}
							}
						}
					} else {
						$this->CI->load->helper('cookie');
						delete_cookie($this->remember_token_name);
					}
				}
			}
		} else if ($this->CI->jwt->token->hasClaim('jti')) {
			$value = $this->CI->input->cookie($this->remember_token_name, TRUE);
			if ($value !== NULL) {
				$id__validator = explode('__', $value);
				if (count($id__validator) > 1) {
					if ($this->CI->jwt->token->getClaim('jti') === $id__validator[0]) {
						$token = $this->CI->db->select('id')->get_where($this->remember_token_table, array('id' => $id__validator[0]), 1)->row_array();
						if ($token !== NULL) {
							$this->set_remember_cookie($value);
						}
					} else {
						$this->clear_credential();
					}
				}
			}
		}
	}

	public function clear_credential() {
		$value = $this->CI->input->cookie($this->remember_token_name, TRUE);
		if ($value !== NULL) {
			$id__validator = explode('__', $value);
			if (count($id__validator) > 1) {
				$this->CI->db->delete($this->remember_token_table, array('id' => $id__validator[0]));
				$this->CI->load->helper('cookie');
				delete_cookie($this->remember_token_name);
			}
		}
		$this->CI->jwt->generate(NULL, array());
	}

	public function issue_reset_token($index) {
		$user = $this->get_user_by_index($index, 'id, username, email, status');
		if ($user === NULL) {
			return FALSE;
		}
		if ((int) $user['status'] === 1) {
			$this->CI->db->delete($this->reset_token_table, array('user' => $user['id']));
			$id = bin2hex($this->CI->security->get_random_bytes(8));
			$validator = bin2hex($this->CI->security->get_random_bytes(10));
			$hash_validator = hash('sha384', $validator);
			$data = array(
				'id' => $id,
				'validator_hash' => $hash_validator,
				'user' => $user['id'],
			);
			$this->CI->db->insert($this->reset_token_table, $data);
			$mail = array(
				'user' => $user,
				'url' => $this->CI->config->item('base_url').'authentication/ui_reset_password?token='.$id.'__'.$validator,
			);
			$this->send_email($mail, 'email_templates/reset.php', 'Password Reset');
			return $id.'__'.$validator;
		}
		return FALSE;
	}

	public function verify_reset_token($token) {
		$id__validator = explode('__', $token);
		if (count($id__validator) > 1) {
			$token = $this->CI->db->get_where($this->reset_token_table, array('id' => $id__validator[0]), 1)->row_array();
			if ($token !== NULL) {
				if (hash_equals(hash('sha384', $id__validator[1]), $token['validator_hash'])) {
					$user = $this->get_user_by_index(array('id' => $token['user']), 'id, email, status');
					if ($user !== NULL) {
						if ((int) $user['status'] === 1) {
							return $user;
						}
					}
				}
			}
		}
		return FALSE;
	}

	public function validate_reset_token($token, $new_password) {
		$user = $this->verify_reset_token($token);
		if ($user == FALSE) {
			return FALSE;
		}
		$this->CI->load->library('encryption');
		$password = $this->CI->encryption->encrypt(password_hash($this->generate_password_safe_length($new_password), PASSWORD_DEFAULT));
		$data = array(
			'password' => $password,
			'updated_at' => time(),
		);
		$this->CI->db->delete($this->reset_token_table, array('user' => $user['id']));
		return $this->update_user_by_index(array('id' => $user['id']), $data);
	}

	public function issue_activation_token($index) {
		$user = $this->get_user_by_index($index, 'id, username, email, status');
		if ($user === NULL) {
			return FALSE;
		}
		if ((int) $user['status'] === 0) {
			$this->CI->db->delete($this->activation_token_table, array('user' => $user['id']));
			$id = bin2hex($this->CI->security->get_random_bytes(25));
			$data = array(
				'id' => $id,
				'user' => $user['id'],
			);
			$this->CI->db->insert($this->activation_token_table, $data);
			$mail = array(
				'user' => $user,
				'url' => $this->CI->config->item('base_url').'authentication/ui_activate_account?token='.$id,
			);
			$this->send_email($mail, 'email_templates/confirm.php', 'Account Activation');
			return TRUE;
		}
		return FALSE;
	}

	public function validate_activation_token($token) {
		$exist = $this->CI->db->get_where($this->activation_token_table, array('id' => $token), 1)->row_array();
		if ($exist === NULL) {
			return FALSE;
		}
		$success = $this->update_user_by_index(array('id' => $exist['user']), array('status' => 1, 'updated_at' => time()));
		if ($success) {
			$this->CI->db->delete($this->activation_token_table, array('user' => $exist['user']));
			RETURN TRUE;
		}
		return FALSE;
	}

	public function destroy_user_data($id) {
		$this->CI->db->delete($this->remember_token_table, array('user' => $id));
		$this->CI->db->delete($this->activation_token_table, array('user' => $id));
		$this->CI->db->delete($this->reset_token_table, array('user' => $id));
		return $this->CI->db->delete($this->user_table, array('id' => $id));
	}

	public function send_email($data, $template, $subject) {
		$this->CI->load->library('email');
		$config = array();
		$config['mailtype'] = "html";
		$config['protocol'] = APP_EMAIL_PROTOCOL;
		$config['smtp_host'] = APP_EMAIL_SMTP_HOST;
		$config['smtp_user'] = APP_EMAIL_SMTP_USER;
		$config['smtp_pass'] = APP_EMAIL_SMTP_PASS;
		$config['smtp_port'] = APP_EMAIL_SMTP_PORT;
		$config['smtp_crypto'] = APP_EMAIL_SMTP_CRYPTO;
		$config['smtp_timeout'] = 600;
		$config['priority'] = 1;
		$config['newline'] = "\r\n";
		$this->CI->email->initialize($config);
		$this->CI->email->from(APP_EMAIL_SMTP_USER, APP_NAME);
		$this->CI->email->to($data['user']['email']);
		$this->CI->email->subject($subject);
		$this->CI->email->message($this->CI->load->view($template, $data, TRUE));
		if (!$this->CI->email->send(FALSE)) {
			// echo $this->CI->email->print_debugger();
			log_message('error', $subject.'::'.$data['user']['email']);
		}
	}
}
