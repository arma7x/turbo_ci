<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user extends MY_Controller {

	public $index = array('role' => 1, 'access_level' => 1);
	public $update_user_role = array('role' => 0, 'access_level' => 0);
	public $update_user_access_level = array('role' => 0, 'access_level' => 0);
	public $update_user_status = array('role' => 0, 'access_level' => 0);
	public $delete_user = array('role' => 0, 'access_level' => 0);
	public $ui_register = array('role' => 0, 'access_level' => 0);
	public $register = array('role' => 0, 'access_level' => 0);

	public function __construct() {
		parent::__construct();
		$this->template = 'widgets/dashboard/template';
		$this->widgets['nav'] = 'widgets/dashboard/nav';
		$this->widgets['dashboard_menu'] = 'widgets/dashboard/dashboard_menu';
	}

	public function index() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_MANAGE_USERS');
		$this->data['page_name'] = lang('H_MANAGE_USERS');
		$this->load->helper('url');
		$this->load->model('User_Model', 'user_model');
		$filter = array(
			'keyword' => $this->input->get('keyword') !== '' && $this->input->get('keyword') !== null ? $this->input->get('keyword', TRUE) : NULL,
			'role' => $this->input->get('role') !== '' && $this->input->get('role') !== null ? (int) $this->input->get('role', TRUE) : NULL,
			'access_level' => $this->input->get('access_level') !== '' && $this->input->get('access_level') !== null ? (int) $this->input->get('access_level', TRUE) : NULL,
			'status' => $this->input->get('status') !== '' && $this->input->get('status') !== null ? (int) $this->input->get('status', TRUE) : NULL,
		);
		$this->data['filter'] = $filter;
		$this->data['user_list'] = $this->user_model->get_user_list($filter, current_url(), 10, (int) $this->input->get('page'), TRUE);
		$this->data['content'] = $this->load->view('dashboard/manage_user/index', $this->data, TRUE);
		$this->_renderLayout();
	}

	public function update_user_role() {
		$this->BlockGetRequest();
		$this->load->library('form_validation');
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
			'user' => $this->container['user']['id'],
			'role' => $this->input->post_get('role', TRUE),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('id', lang('L_ID'), 'required|differs[user]');
		$this->form_validation->set_rules('user', lang('L_USER'), 'required');
		$this->form_validation->set_rules('role', lang('L_ROLE'), 'required|is_natural|less_than_equal_to[127]');
		if ($this->form_validation->run() === FALSE) {
			$error = '';
			if (isset($this->form_validation->error_array()['id'])) {
				$error = $this->form_validation->error_array()['id'];
			} else if (isset($this->form_validation->error_array()['role'])) {
				$error = $this->form_validation->error_array()['role'];
			}
			$data = array(
				'message' => $error
			);
			$this->_renderJSON(400, $data);
		} else {
			$result = $this->authenticator->update_user_by_index(array('id' => $this->input->post_get('id')), array('role' => (int) $this->input->post_get('role'), 'updated_at' => time()));
			if ($result) {
				$data = array(
					'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_UPDATE_ROLE')),
					'redirect' => $this->config->item('base_url').'dashboard/manage_user/index'
				);
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_UPDATE_ROLE'))));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_FAIL_UPDATE_ROLE'))
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function update_user_access_level() {
		$this->BlockGetRequest();
		$this->load->library('form_validation');
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
			'user' => $this->container['user']['id'],
			'access_level' => $this->input->post_get('access_level', TRUE),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('id', lang('L_ID'), 'required|differs[user]');
		$this->form_validation->set_rules('user', lang('L_USER'), 'required');
		$this->form_validation->set_rules('access_level', lang('L_ACCESS_LEVEL'), 'required|is_natural|less_than_equal_to[127]');
		if ($this->form_validation->run() === FALSE) {
			$error = '';
			if (isset($this->form_validation->error_array()['id'])) {
				$error = $this->form_validation->error_array()['id'];
			} else if (isset($this->form_validation->error_array()['access_level'])) {
				$error = $this->form_validation->error_array()['access_level'];
			}
			$data = array(
				'message' => $error
			);
			$this->_renderJSON(400, $data);
		} else {
			$result = $this->authenticator->update_user_by_index(array('id' => $this->input->post_get('id')), array('access_level' => (int) $this->input->post_get('access_level'), 'updated_at' => time()));
			if ($result) {
				$data = array(
					'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_UPDATE_ACCESS_LEVEL')),
					'redirect' => $this->config->item('base_url').'dashboard/manage_user/index'
				);
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_UPDATE_ACCESS_LEVEL'))));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_FAIL_UPDATE_ACCESS_LEVEL'))
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function update_user_status() {
		$this->BlockGetRequest();
		$this->load->library('form_validation');
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
			'user' => $this->container['user']['id'],
			'status' => $this->input->post_get('status', TRUE),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('id', lang('L_ID'), 'required|differs[user]');
		$this->form_validation->set_rules('user', lang('L_USER'), 'required');
		$this->form_validation->set_rules('status', lang('L_STATUS'), 'required|in_list[-1,0,1]');
		if ($this->form_validation->run() === FALSE) {
			$error = '';
			if (isset($this->form_validation->error_array()['id'])) {
				$error = $this->form_validation->error_array()['id'];
			} else if (isset($this->form_validation->error_array()['status'])) {
				$error = $this->form_validation->error_array()['status'];
			}
			$data = array(
				'message' => $error
			);
			$this->_renderJSON(400, $data);
		} else {
			$result = $this->authenticator->update_user_by_index(array('id' => $this->input->post_get('id')), array('status' => (int) $this->input->post_get('status'), 'updated_at' => time()));
			if ($result) {
				$data = array(
					'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_UPDATE_STATUS')),
					'redirect' => $this->config->item('base_url').'dashboard/manage_user/index'
				);
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_UPDATE_STATUS'))));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_FAIL_UPDATE_STATUS'))
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function delete_user() {
		$this->BlockGetRequest();
		$this->load->library('form_validation');
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
			'user' => $this->container['user']['id'],
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('id', lang('L_ID'), 'required|differs[user]');
		$this->form_validation->set_rules('user', lang('L_USER'), 'required');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'message' => $error = $this->form_validation->error_array()['id']
			);
			$this->_renderJSON(400, $data);
		} else {
			$result = $this->authenticator->destroy_user_data($this->input->post_get('id'));
			if ($result) {
				$data = array(
					'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_REMOVE')),
					'redirect' => $this->config->item('base_url').'dashboard/manage_user/index'
				);
				$this->session->set_flashdata('__notification', array('type' => 'success', 'message'=>str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_REMOVE'))));
				$this->_renderJSON(200, $data);
			}
			$data = array(
				'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_FAIL_REMOVE'))
			);
			$this->_renderJSON(400, $data);
		}
	}

	public function ui_register() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_ADD_USER');
		$this->data['page_name'] = lang('H_ADD_USER');
		$this->data['content'] = $this->load->view('dashboard/manage_user/register', $this->data, TRUE);
		$this->_renderLayout();
	}

	public function register() {
		$this->BlockGetRequest();
		$this->load->library('form_validation');
		$data = array(
			'username' => strtolower($this->input->post_get('username', TRUE)),
			'email' => strtolower($this->input->post_get('email', TRUE)),
			'password' => $this->input->post_get('password'),
			'confirm_password' => $this->input->post_get('confirm_password'),
			'role' => $this->input->post_get('role'),
			'access_level' => $this->input->post_get('access_level'),
			'status' => $this->input->post_get('status'),
		);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('username', lang('L_USERNAME'), 'required|alpha_dash|is_unique[users.username]');
		$this->form_validation->set_rules('email', lang('L_EMAIL'), 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', lang('L_PASSWORD'), 'required|min_length[10]|matches[confirm_password]');
		$this->form_validation->set_rules('confirm_password', lang('L_CONFIRM_PASSWORD'), 'required|min_length[10]|matches[password]');
		$this->form_validation->set_rules('access_level', lang('L_ACCESS_LEVEL'), 'required|is_natural|less_than_equal_to[127]');
		$this->form_validation->set_rules('role', lang('L_ROLE'), 'required|is_natural|less_than_equal_to[127]');
		$this->form_validation->set_rules('status', lang('L_STATUS'), 'required|in_list[-1,0,1]');
		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'errors' => $this->form_validation->error_array()
			);
			$this->_renderJSON(400, $data);
		} else {
			$this->load->library('encryption');
			$data['id'] = bin2hex($this->security->get_random_bytes(5));
			$data['role'] = (int) $this->input->post_get('role');
			$data['access_level'] = (int) $this->input->post_get('access_level');
			$data['status'] = (int) $this->input->post_get('status');
			$data['password'] = $this->encryption->encrypt(password_hash($this->authenticator->generate_password_safe_length($data['confirm_password'], TRUE), PASSWORD_DEFAULT));
			$data['avatar'] = $this->authenticator->default_avatar;
			$data['created_at'] = time();
			$data['updated_at'] = time();
			$data['last_logged_in'] = time();
			unset($data['confirm_password']);
			$result = $this->authenticator->save_user($data);
			if ($result) {
				$data = array(
					'message' => lang('M_SUCCESS_REGISTER'),
					'redirect' => $this->config->item('base_url').'dashboard/manage_user/ui_register'
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
}
