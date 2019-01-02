<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticator {

	/*
	 * https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
	*/

	protected $CI;
	protected $USER_TABLE = 'users';
	protected $REMEMBER_TOKEN_TABLE = 'remember_tokens';
	protected $REMEMBER_TOKEN_NAME = 'remember_me';
	protected $ACTIVATION_TOKEN_TABLE = 'activation_tokens';
	protected $DEFAULT_ROLE = 2; // 0:ADMIN, 1:STAFF, 2:MEMBER
	protected $DEFAULT_ACCESS_LEVEL = 999; // 0:SUDO
	protected $DEFAULT_STATUS = 1; // 1:ACTIVE, 0:INACTIVE, -1:BAN
	protected $DEFAULT_AVATAR = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAAAAAA7VNdtAAAACXBIWXMAAAA4AAAAOABi1B8JAAABTklEQVRIx+3Tv0vDUBDA8f6/p8FBLSQgaCtNoThEoXWoDgYEK4joIlRcRMTBH1DrIFXrL1rEKjGS5FwcFOzLu9w9R8Hv/D7Lvbvcx6/L/ZO/Q7CzVXNdb70VGxI8mYXv7GZsQt7rMFS5L5PABSX7TiK4AKmmXgTSBK0lZMnbhE6gw5I9QsAySzyKTMYMScYpAjcMCUgBpwwZ0OSYISFNzhiCeZI8chOrUcJOOHJIkVX2XyJHF9Y9v2NHOmlIm+ynRSmS7iVaVEXxWb5K3LSGNz8wOGS8Kv2I/DnK5Dp1lpU28iTesLSJ+SFHBnPUVxZ62eRpml5Lu5tFXmcgI6dHk2QeMiuEJNkGphUkyO0YR6ClE/RYAYVYI20Q2tdIVSJFTJH+iETgMkV2RAF+ilRk4iQKCUdlAg8KuTAQcKCQXRPSUMiaCakqpG5Cyl9vPwHZXW4PhaKQ+wAAAABJRU5ErkJggg==';

	public function __construct($params) {
		$this->CI = &get_instance();
		$this->USER_TABLE = $params['user_table'];
		$this->REMEMBER_TOKEN_TABLE = $params['remember_token_table'];
		$this->validate_remember_token();
	}

	private function get_user_by_index($index) {
		return $this->CI->db->get_where($this->USER_TABLE, $index, 1)->row_array();
	}

	private function save_user($data) {
		if ($this->get_user_by_index(array('id' => $data['id'])) != NULL) {
			return FALSE;
		}
		$this->CI->db->insert($this->USER_TABLE, $data);
		return TRUE;
	}

	private function update_user_by_index($index, $data) {
		if ($this->get_user_by_index($index) === NULL) {
			return FALSE;
		}
		$this->CI->db->update($this->USER_TABLE, $data, $index);
		return TRUE;
	}

	private function set_token_cookie($value) {
		$expire = time() + (60 * 60 * 24 * 365);
		$secure_cookie = (bool) $this->CI->config->item('cookie_secure');
		if ($secure_cookie && ! is_https()) {
			return FALSE;
		}
		setcookie(
			$this->REMEMBER_TOKEN_NAME,
			$value,
			$expire,
			$this->CI->config->item('cookie_path'),
			$this->CI->config->item('cookie_domain'),
			$secure_cookie,
			TRUE
		);
	}

	public function generate_password_safe_length($string) {
		return base64_encode(hash('sha384', $string, TRUE));
	}

	public function validate_credential($index, $password, $remember_me) {
		$user = $this->get_user_by_index($index);
		if ($user === NULL) {
			return FALSE;
		}
		if ($user['status'] < $this->DEFAULT_STATUS) {
			return (int) $user['status'];
		}
		$this->CI->load->library('encryption');
		$current_password = $this->CI->encryption->decrypt($user['password']);
		$match = password_verify($this->generate_password_safe_length($password), $current_password);
		if ($match) {
			$this->CI->db->delete($this->ACTIVATION_TOKEN_TABLE, array('user' => $user['id']));
			$this->CI->session->set_userdata(array('status' => TRUE, 'user' => $user));
			if ($remember_me) {
				$this->generate_remember_token($this->CI->session->user['id']);
			}
			return TRUE;
		}
		return FALSE;
	}

	public function store_credential($data) {
		$this->CI->load->library('encryption');
		$password = $this->CI->encryption->encrypt(password_hash($this->generate_password_safe_length($data['password'], TRUE), PASSWORD_DEFAULT));
		$data['id'] = bin2hex($this->CI->security->get_random_bytes(5));
		$data['password'] = $password;
		$data['role'] = ($data['email'] === APP_ADMIN_EMAIL) ? 0 : $this->DEFAULT_ROLE;
		$data['access_level'] = $this->DEFAULT_ACCESS_LEVEL;
		$data['status'] = $this->DEFAULT_STATUS;
		$data['avatar'] = $this->DEFAULT_AVATAR;
		$data['inserted_at'] = time();
		$data['updated_at'] = time();
		return $this->save_user($data);
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

	public function generate_remember_token($user_id) {
		$id = bin2hex($this->CI->security->get_random_bytes(8));
		$validator = bin2hex($this->CI->security->get_random_bytes(10));
		$hash_validator = hash('sha384', $validator);
		$data = array(
			'id' => $id,
			'validator_hash' => $hash_validator,
			'user' => $user_id,
		);
		$this->CI->db->insert($this->REMEMBER_TOKEN_TABLE, $data);
		$this->set_token_cookie($id.'__'.$validator);
	}

	public function validate_remember_token() {
		if ($this->CI->session->status === NULL) {
			$this->CI->load->helper('cookie');
			$value = get_cookie($this->REMEMBER_TOKEN_NAME);
			if ($value != NULL) {
				$id__validator = explode('__', $value);
				if (count($id__validator) > 1) {
					$token = $this->CI->db->get_where($this->REMEMBER_TOKEN_TABLE, array('id' => $id__validator[0]), 1)->row_array();
					if ($token != NULL) {
						if (hash_equals(hash('sha384', $id__validator[1]), $token['validator_hash'])) {
							$user = $this->get_user_by_index(array('id' => $token['user']));
							if ($user != NULL) {
								$this->CI->session->set_userdata(array('status' => TRUE, 'user' => $user));
								$this->set_token_cookie($value);
							}
						}
					}
				}
			}
		} else if ($this->CI->session->status === TRUE) {
			$this->CI->load->helper('cookie');
			$value = get_cookie($this->REMEMBER_TOKEN_NAME);
			if ($value != NULL) {
				$id__validator = explode('__', $value);
				if (count($id__validator) > 1) {
					$this->set_token_cookie($value);
				}
			}
		}
	}

	public function clear_credential() {
		$this->CI->load->helper('cookie');
		$value = get_cookie($this->REMEMBER_TOKEN_NAME);
		if ($value != NULL) {
			$id__validator = explode('__', $value);
			if (count($id__validator) > 1) {
				$this->CI->db->delete($this->REMEMBER_TOKEN_TABLE, array('id' => $id__validator[0]));
				delete_cookie($this->REMEMBER_TOKEN_NAME);
			}
		}
		$this->CI->session->unset_userdata('status');
		$this->CI->session->unset_userdata('user');
	}

	public function issue_reset_token() {}

	public function validate_reset_token() {}

	public function issue_activation_token($index) {
		$user = $this->get_user_by_index($index);
		if ($user === NULL) {
			return FALSE;
		}
		if ((int) $user['status'] === 0) {
			$this->CI->db->delete($this->ACTIVATION_TOKEN_TABLE, array('user' => $user['id']));
			$id = bin2hex($this->CI->security->get_random_bytes(25));
			$data = array(
				'id' => $id,
				'user' => $user['id'],
			);
			$this->CI->db->insert($this->ACTIVATION_TOKEN_TABLE, $data);
			return TRUE;
		}
		return FALSE;
	}

	public function validate_activation_token($token) {
		$exist = $this->CI->db->get_where($this->ACTIVATION_TOKEN_TABLE, array('id' => $token), 1)->row_array();
		if ($exist === NULL) {
			return FALSE;
		}
		$success = $this->update_user_by_index(array('id' => $exist['user']), array('status' => 1, 'updated_at' => time()));
		if ($success) {
			$this->CI->db->delete($this->ACTIVATION_TOKEN_TABLE, array('user' => $exist['user']));
			RETURN TRUE;
		}
		return FALSE;
	}

}
