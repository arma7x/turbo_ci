<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends MY_Controller {

	public $login = array('auth' => FALSE);
	public $register = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $activate_account = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $forgot_password = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $reset_password = array('auth' => FALSE, 'enable' => APP_REGISTRATION);
	public $update_password = array('auth' => TRUE);
	public $manage_token = array('auth' => TRUE);

	public function __construct() {
		parent::__construct();
	}

	public function login() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_LOGIN');
		$this->data['page_name'] = lang('H_LOGIN');
		$this->widgets['content'] = 'auth/login';
		$this->_renderLayout();
	}

	public function register() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_REGISTER');
		$this->data['page_name'] = lang('H_REGISTER');
		$this->widgets['content'] = 'auth/register';
		$this->_renderLayout();
	}

	public function activate_account() {
		$this->AllowGetRequest();
		if ($this->input->post_get('token', TRUE) !== NULL) {
			$this->load->helper('url');
			$result = $this->authenticator->validate_activation_token($this->input->post_get('token', TRUE));
			if ($result) {
				//$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>lang('M_SUCCESS_ACTIVE_ACCOUNT')));
				redirect($this->config->item('base_url').'authentication/login');
			}
			//$this->session->set_flashdata('__notification', array('type' => 'warning', 'message'=>lang('M_FAIL_ACTIVE_ACCOUNT')));
			redirect($this->config->item('base_url'));
		}
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_ACTIVATE_ACCOUNT');
		$this->data['page_name'] = lang('H_ACTIVATE_ACCOUNT');
		$this->widgets['content'] = 'auth/activate_account';
		$this->_renderLayout();
	}

	public function forgot_password() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_FORGOT_PASSWORD');
		$this->data['page_name'] = lang('H_FORGOT_PASSWORD');
		$this->widgets['content'] = 'auth/forgot_password';
		$this->_renderLayout();
	}

	public function reset_password() {
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

	public function update_password() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_UPDATE_PASSWORD');
		$this->data['page_name'] = lang('H_UPDATE_PASSWORD');
		$this->widgets['content'] = 'auth/update_password';
		$this->_renderLayout();
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
}
