<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends MY_Controller {

	public $ui_login = array('auth' => FALSE);
	public $login = array('auth' => FALSE);
	public $ui_register = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $register = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $ui_activate_account = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $activate_account = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $ui_forgot_password = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $forgot_password = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $ui_reset_password = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $reset_password = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $ui_update_password = array('auth' => TRUE);
	public $update_password = array('auth' => TRUE);
	public $upload_avatar = array('auth' => TRUE);
	public $manage_token = array('auth' => TRUE);
	public $remove_remember_token = array('auth' => TRUE);
	public $whoami = array('auth' => TRUE);
	public $log_out = array('auth' => TRUE);

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function whoami() {
		$this->_renderJSON(200, $this->container['user']);
	}

	public function ui_login() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_LOGIN');
		$this->data['page_name'] = lang('H_LOGIN');
		$this->widgets['content'] = 'auth/login';
		$this->_renderLayout();
	}

	public function login() {
		$this->BlockGetRequest();
		$data = array(
			'email' => strtolower($this->input->post_get('email', TRUE)),
			'password' => $this->input->post_get('password'),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('email', lang('L_EMAIL'), 'required|valid_email');
		$this->form_validation->set_rules('password', lang('L_PASSWORD'), 'required|min_length[10]');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON(400, $data);
		} else {
			$remember_me = $this->input->post_get('remember_me') === 'true' ? TRUE : FALSE;
			$validate_credential = $this->authenticator->validate_credential(array('email' => strtolower($this->input->post_get('email', TRUE))), $this->input->post_get('password'), $remember_me);
			if ($validate_credential === TRUE) {
				$data = array(
					'message' => lang('M_SUCCESS_LOGIN')
				);
				if ($this->input->post_get('redirect') === 'true') {
					$data['redirect'] = $this->config->item('base_url');
				}
				//$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_LOGIN')));
				$this->_renderJSON(200, $data);
			} else if ($validate_credential === 0) {
				$data = array(
					'message' => lang('M_FAIL_LOGIN_INACTIVE')
				);
				$this->_renderJSON(400, $data);
			} else if ($validate_credential === -1) {
				$data = array(
					'message' => lang('M_FAIL_LOGIN_BANNED')
				);
				$this->_renderJSON(400, $data);
			} else {
				$data = array(
					'message' => lang('M_FAIL_LOGIN')
				);
				$this->_renderJSON(400, $data);
			}
		}
	}

	public function ui_register() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_REGISTER');
		$this->data['page_name'] = lang('H_REGISTER');
		$this->widgets['content'] = 'auth/register';
		$this->_renderLayout();
	}

	public function register() {
		$this->BlockGetRequest();
		$data = array(
			'username' => strtolower($this->input->post_get('username', TRUE)),
			'email' => strtolower($this->input->post_get('email', TRUE)),
			'password' => $this->input->post_get('password'),
			'confirm_password' => $this->input->post_get('confirm_password'),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username', lang('L_USERNAME'), 'required|alpha_dash|is_unique[users.username]');
		$this->form_validation->set_rules('email', lang('L_EMAIL'), 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', lang('L_PASSWORD'), 'required|min_length[10]|matches[confirm_password]');
		$this->form_validation->set_rules('confirm_password', lang('L_CONFIRM_PASSWORD'), 'required|min_length[10]|matches[password]');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON(400, $data);
		} else {
			unset($data['confirm_password']);
			$result = $this->authenticator->store_credential($data);
			if ($result) {
				$data = array(
					'message' => lang('M_SUCCESS_REGISTER'),
					'redirect' => $this->config->item('base_url')
				);
				//$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_REGISTER')));
				$this->_renderJSON(200, $data);
			} else {
				$data = array(
					'message' => lang('M_FAIL_REGISTER'),
				);
				//$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FAIL_REGISTER')));
				$this->_renderJSON(400, $data);
			}
		}
	}

	public function ui_activate_account() {
		$this->AllowGetRequest();
		if ($this->input->post_get('token', TRUE) !== NULL) {
			$this->load->helper('url');
			$result = $this->authenticator->validate_activation_token($this->input->post_get('token', TRUE));
			if ($result) {
				//$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_ACTIVE_ACCOUNT')));
				redirect($this->config->item('base_url').'authentication/ui_login');
			}
			//$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FAIL_ACTIVE_ACCOUNT')));
			redirect($this->config->item('base_url'));
		}
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_ACTIVATE_ACCOUNT');
		$this->data['page_name'] = lang('H_ACTIVATE_ACCOUNT');
		$this->widgets['content'] = 'auth/activate_account';
		$this->_renderLayout();
	}

	public function activate_account() {
		$this->BlockGetRequest();
		$data = array(
			'email' => strtolower($this->input->post_get('email', TRUE)),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('email', lang('L_EMAIL'), 'required|valid_email');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON(400, $data);
		} else {
			$result = $this->authenticator->issue_activation_token($data);
			if ($result) {
				$data = array(
					'message' => lang('M_ACTIVE_ACCOUNT_LINK'),
					'redirect' => $this->config->item('base_url')
				);
				//$this->session->set_flashdata('__notification', array('type' => 'info', 'message'=>lang('M_ACTIVE_ACCOUNT_LINK')));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => lang('M_ACTIVE_ACCOUNT_LINK_INVALID')
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function ui_forgot_password() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_FORGOT_PASSWORD');
		$this->data['page_name'] = lang('H_FORGOT_PASSWORD');
		$this->widgets['content'] = 'auth/forgot_password';
		$this->_renderLayout();
	}

	public function forgot_password() {
		$this->BlockGetRequest();
		$data = array(
			'email' => strtolower($this->input->post_get('email', TRUE)),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('email', lang('L_EMAIL'), 'required|valid_email');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON(400, $data);
		} else {
			$result = $this->authenticator->issue_reset_token($data);
			if ($result !== FALSE) {
				$data = array(
					'token' => $result,
					'message' => lang('M_FORGOT_PASSWORD_LINK'),
					'redirect' => $this->config->item('base_url')
				);
				//$this->session->set_flashdata('__notification', array('type' => 'info', 'message'=>lang('M_FORGOT_PASSWORD_LINK')));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => lang('M_FORGOT_PASSWORD_LINK_INVALID')
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function ui_reset_password() {
		$this->AllowGetRequest();
		$this->load->helper('url');
		if ($this->input->post_get('token', TRUE) !== NULL) {
			$result = $this->authenticator->verify_reset_token($this->input->post_get('token', TRUE));
			if ($result === FALSE) {
				//$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FORGOT_PASSWORD_LINK_INVALID_TOKEN')));
				redirect($this->config->item('base_url'));
			}
			$this->data['title'] = $this->container['app_name'].' | '.lang('H_RESET_PASSWORD');
			$this->data['page_name'] = lang('H_RESET_PASSWORD');
			$this->data['user'] = $result;
			$this->widgets['content'] = 'auth/reset_password';
			$this->_renderLayout();
		} else {
			//$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FORGOT_PASSWORD_LINK_INVALID_TOKEN')));
			redirect($this->config->item('base_url'));
		}
	}

	public function reset_password() {
		$this->BlockGetRequest();
		$data = array(
			'token' => $this->input->post_get('token', TRUE),
			'new_password' => $this->input->post_get('new_password'),
			'confirm_password' => $this->input->post_get('confirm_password'),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('token', lang('L_TOKEN'), 'required');
		$this->form_validation->set_rules('new_password', lang('L_NEW_PASSWORD'), 'required|min_length[10]|matches[confirm_password]');
		$this->form_validation->set_rules('confirm_password', lang('L_CONFIRM_PASSWORD'), 'required|min_length[10]|matches[new_password]');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON(400, $data);
		} else {
			$result = $this->authenticator->validate_reset_token($this->input->post_get('token', TRUE), $this->input->post_get('new_password'));
			if ($result) {
				$data = array(
					'message' => lang('M_SUCCESS_UPDATE_PASSWORD'),
					'redirect' => $this->config->item('base_url').'authentication/ui_login',
				);
				//$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_UPDATE_PASSWORD')));
				$this->_renderJSON(200, $data);
			} else {
				$data = array(
					'message' => lang('M_FAIL_UPDATE_PASSWORD'),
				);
				$this->_renderJSON(400, $data);
			}
		}
	}

	public function ui_update_password() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_UPDATE_PASSWORD');
		$this->data['page_name'] = lang('H_UPDATE_PASSWORD');
		$this->widgets['content'] = 'auth/update_password';
		$this->_renderLayout();
	}

	public function update_password() {
		$this->BlockGetRequest();
		$data = array(
			'old_password' => $this->input->post_get('old_password'),
			'new_password' => $this->input->post_get('new_password'),
			'confirm_password' => $this->input->post_get('confirm_password'),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('old_password', lang('L_OLD_PASSWORD'), 'required|min_length[10]');
		$this->form_validation->set_rules('new_password', lang('L_NEW_PASSWORD'), 'required|min_length[10]|matches[confirm_password]');
		$this->form_validation->set_rules('confirm_password', lang('L_CONFIRM_PASSWORD'), 'required|min_length[10]|matches[new_password]');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON(400, $data);
		} else {
			$index = array('id' => $this->container['user']['id']);
			$result = $this->authenticator->update_credential($index, $this->input->post_get('old_password'), $this->input->post_get('new_password'));
			if ($result) {
				$data = array(
					'message' => lang('M_SUCCESS_UPDATE_PASSWORD'),
					'redirect' => $this->config->item('base_url')
				);
				//$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_UPDATE_PASSWORD')));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => lang('M_FAIL_UPDATE_PASSWORD')
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function upload_avatar() {
		$this->BlockGetRequest();
		$data = array(
			'avatar' => $this->input->post_get('avatar'),
			'updated_at' => time(),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('avatar', lang('L_AVATAR'), 'required|max_length[10000]');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'message' => $this->form_validation->error_array()['avatar']
			);
			$this->_renderJSON(400, $data);
		} else {
			$result = $this->authenticator->update_user_by_index(array('id' => $this->container['user']['id']), $data);
			if ($result) {
				$data = array(
					'message' => lang('M_SUCCESS_UPLOAD_AVATAR'),
					'redirect' => $this->config->item('base_url')
				);
				//$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_UPLOAD_AVATAR')));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => lang('M_FAIL_UPLOAD_AVATAR')
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function manage_token() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_LOG_IN_DEVICES');
		$this->data['page_name'] = lang('H_LOG_IN_DEVICES');
		$this->data['token_list'] = $this->authenticator->get_remember_token(array('user' => $this->container['user']['id']));
		$this->data['current_token'] = $this->authenticator->get_current_remember_token();
		$this->widgets['content'] = 'auth/manage_token';
		$this->_renderLayout();
	}

	public function delete_token() {
		$this->BlockGetRequest();
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('id', lang('L_ID'), 'required');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'message' => $this->form_validation->error_array()['id']
			);
			$this->_renderJSON(400, $data);
		} else {
			$data['user'] = $this->container['user']['id'];
			$result = $this->authenticator->remove_remember_token($data);
			if ($result) {
				$data = array(
					'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_REMOVE')),
					'redirect' => $this->config->item('base_url').'authentication/manage_token'
				);
				//$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_REMOVE'))));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_FAIL_REMOVE'))
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function log_out() {
		$this->BlockGetRequest();
		$this->authenticator->clear_credential();
		$data = array(
			'message' => lang('M_SUCCESS_LOGOUT'),
			'redirect' => $this->config->item('base_url')
		);
		//$this->session->set_flashdata('__notification', array('type' => 'info', 'message'=>lang('M_SUCCESS_LOGOUT')));
		$this->_renderJSON(200, $data);
	}
}
