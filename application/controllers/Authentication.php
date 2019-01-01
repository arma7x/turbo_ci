<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends MY_Controller {

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
		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON($data, 400);
		} else {
			$remember_me = $this->input->post_get('remember_me') == 'true' ? TRUE : FALSE;
			$validate_credential = $this->authenticator->validate_credential(array('email' => strtolower($this->input->post_get('email', TRUE))), $this->input->post_get('password'), $remember_me);
			if ($validate_credential == NULL) {
				$data = array(
					'message' => lang('M_FAIL_LOGIN')
				);
				$this->_renderJSON($data, 400);
			}
			$data = array(
				'message' => lang('M_SUCCESS_LOGIN'),
				'redirect' => $this->config->item('base_url')
			);
			$this->_renderJSON($data, 200);
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
		$this->form_validation->set_rules('username', lang('L_USERNAME'), 'required|alpha_numeric|is_unique[users.username]');
		$this->form_validation->set_rules('email', lang('L_EMAIL'), 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', lang('L_PASSWORD'), 'required|min_length[10]|matches[confirm_password]');
		$this->form_validation->set_rules('confirm_password', lang('L_CONFIRM_PASSWORD'), 'required|min_length[10]|matches[password]');
		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON($data, 400);
		} else {
			unset($data['confirm_password']);
			$result = $this->authenticator->store_credential($data);
			if ($result) {
				$data = array(
					'message' => lang('M_SUCCESS_REGISTER'),
					'redirect' => $this->config->item('base_url')
				);
				$this->_renderJSON($data, 200);
			} else {
				$data = array(
					'message' => lang('M_FAIL_REGISTER'),
				);
				$this->_renderJSON($data, 400);
			}
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
		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON($data, 400);
		} else {
			$data = array(
				'message' => lang('M_FORGOT_PASSWORD_LINK'),
				'redirect' => $this->config->item('base_url')
			);
			$this->_renderJSON($data, 200);
		}
	}

	public function ui_activate_account() {
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
		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON($data, 400);
		} else {
			$data = array(
				'message' => lang('M_ACTIVE_ACCOUNT_LINK'),
				'redirect' => $this->config->item('base_url')
			);
			$this->_renderJSON($data, 200);
		}
	}

	public function ui_reset_password() {
		$this->data['title'] = 'Codeigniter | '.lang('H_RESET_PASSWORD');
		$this->data['page_name'] = lang('H_RESET_PASSWORD');
		$templates[] = 'auth/reset_password';
		$this->_render($templates);
	}

	public function reset_password() {
		$data = array(
			'new_password' => $this->input->post_get('new_password'),
			'confirm_password' => $this->input->post_get('confirm_password'),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('new_password', lang('L_NEW_PASSWORD'), 'required|min_length[10]|matches[confirm_password]');
		$this->form_validation->set_rules('confirm_password', lang('L_CONFIRM_PASSWORD'), 'required|min_length[10]|matches[new_password]');
		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON($data, 400);
		} else {
			$data = array(
				'message' => lang('M_SUCCESS_UPDATE_PASSWORD'),
				'redirect' => $this->config->item('base_url').'guest/login',
			);
			$this->_renderJSON($data, 200);
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
		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON($data, 400);
		} else {
			$index = array('id' => $this->session->user['id']);
			$result = $this->authenticator->update_credential($index, $this->input->post_get('old_password'), $this->input->post_get('new_password'));
			if ($result) {
				$data = array(
					'message' => lang('M_SUCCESS_UPDATE_PASSWORD'),
					'redirect' => $this->config->item('base_url')
				);
				$this->_renderJSON($data, 200);
			}
			$data = array(
				'message' => lang('M_FAIL_UPDATE_PASSWORD')
			);
			$this->_renderJSON($data, 400);
		}
	}

	public function log_out() {
		$this->authenticator->clear_credential();
		$data = array(
			'message' => lang('M_SUCCESS_LOGOUT'),
			'redirect' => $this->config->item('base_url')
		);
		$this->_renderJSON($data, 200);
	}
}
