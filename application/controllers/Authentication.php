<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends MY_Controller {

	//public $global_auth_rule = FALSE; // OR NULL ALSO ACCEPTED
	public $ui_login__auth_rule = FALSE;
	public $login__auth_rule = FALSE;
	public $ui_register__auth_rule = FALSE;
	public $register__auth_rule = FALSE;
	public $ui_activate_account__auth_rule = FALSE;
	public $activate_account__auth_rule = FALSE;
	public $ui_forgot_password__auth_rule = FALSE;
	public $forgot_password__auth_rule = FALSE;
	public $ui_reset_password__auth_rule = FALSE;
	public $reset_password__auth_rule = FALSE;
	public $ui_update_password__auth_rule = TRUE;
	public $update_password__auth_rule = TRUE;
	public $log_out__auth_rule = NULL;

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function ui_login() {
		$this->data['title'] = 'Codeigniter | '.lang('H_LOGIN');
		$this->data['page_name'] = lang('H_LOGIN');
		$templates[] = 'auth/login';
		$this->_render($templates);
	}

	public function login() {
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
					'message' => lang('M_SUCCESS_LOGIN'),
					'redirect' => $this->config->item('base_url')
				);
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_LOGIN')));
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
		$this->data['title'] = 'Codeigniter | '.lang('H_REGISTER');
		$this->data['page_name'] = lang('H_REGISTER');
		$templates[] = 'auth/register';
		$this->_render($templates);
	}

	public function register() {
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
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_REGISTER')));
				$this->_renderJSON(200, $data);
			} else {
				$data = array(
					'message' => lang('M_FAIL_REGISTER'),
				);
				$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FAIL_REGISTER')));
				$this->_renderJSON(400, $data);
			}
		}
	}

	public function ui_activate_account() {
		if ($this->input->post_get('token', TRUE) !== NULL) {
			$this->load->helper('url');
			$result = $this->authenticator->validate_activation_token($this->input->post_get('token', TRUE));
			if ($result) {
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_ACTIVE_ACCOUNT')));
				redirect($this->config->item('base_url').'authentication/ui_login');
			}
			$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FAIL_ACTIVE_ACCOUNT')));
			redirect($this->config->item('base_url'));
		}
		$this->data['title'] = 'Codeigniter | '.lang('H_ACTIVATE_ACCOUNT');
		$this->data['page_name'] = lang('H_ACTIVATE_ACCOUNT');
		$templates[] = 'auth/activate_account';
		$this->_render($templates);
	}

	public function activate_account() {
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
				$this->session->set_flashdata('__notification', array('type' => 'info', 'message'=>lang('M_ACTIVE_ACCOUNT_LINK')));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => lang('M_ACTIVE_ACCOUNT_LINK_INVALID')
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function ui_forgot_password() {
		$this->data['title'] = 'Codeigniter | '.lang('H_FORGOT_PASSWORD');
		$this->data['page_name'] = lang('H_FORGOT_PASSWORD');
		$templates[] = 'auth/forgot_password';
		$this->_render($templates);
	}

	public function forgot_password() {
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
				$this->session->set_flashdata('__notification', array('type' => 'info', 'message'=>lang('M_FORGOT_PASSWORD_LINK')));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => lang('M_FORGOT_PASSWORD_LINK_INVALID')
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function ui_reset_password() {
		$this->load->helper('url');
		if ($this->input->post_get('token', TRUE) !== NULL) {
			$result = $this->authenticator->verify_reset_token($this->input->post_get('token', TRUE));
			if ($result === FALSE) {
				$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FORGOT_PASSWORD_LINK_INVALID_TOKEN')));
				redirect($this->config->item('base_url'));
			}
			$this->data['title'] = 'Codeigniter | '.lang('H_RESET_PASSWORD');
			$this->data['page_name'] = lang('H_RESET_PASSWORD');
			$this->data['user'] = $result;
			$templates[] = 'auth/reset_password';
			$this->_render($templates);
		} else {
			$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FORGOT_PASSWORD_LINK_INVALID_TOKEN')));
			redirect($this->config->item('base_url'));
		}
	}

	public function reset_password() {
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
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_UPDATE_PASSWORD')));
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
		$this->data['title'] = 'Codeigniter | '.lang('H_UPDATE_PASSWORD');
		$this->data['page_name'] = lang('H_UPDATE_PASSWORD');
		$templates[] = 'auth/update_password';
		$this->_render($templates);
	}

	public function update_password() {
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
			$index = array('id' => $this->session->user['id']);
			$result = $this->authenticator->update_credential($index, $this->input->post_get('old_password'), $this->input->post_get('new_password'));
			if ($result) {
				$data = array(
					'message' => lang('M_SUCCESS_UPDATE_PASSWORD'),
					'redirect' => $this->config->item('base_url')
				);
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_UPDATE_PASSWORD')));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => lang('M_FAIL_UPDATE_PASSWORD')
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function log_out() {
		$this->authenticator->clear_credential();
		$data = array(
			'message' => lang('M_SUCCESS_LOGOUT'),
			'redirect' => $this->config->item('base_url')
		);
		$this->session->set_flashdata('__notification', array('type' => 'info', 'message'=>lang('M_SUCCESS_LOGOUT')));
		$this->_renderJSON(200, $data);
	}
}
