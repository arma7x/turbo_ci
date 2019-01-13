<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user extends MY_Controller {

	public $user_list = array('role' => 1, 'access_level' => 1);
	public $update_user_role = array('role' => 0, 'access_level' => 0);
	public $update_user_access_level = array('role' => 0, 'access_level' => 0);
	public $update_user_status = array('role' => 0, 'access_level' => 0);
	public $delete_user = array('role' => 0, 'access_level' => 0);

	public function user_list() {
		$this->data['title'] = APP_NAME.' | '.lang('H_MANAGE_USERS');
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
		$templates[] = 'admin/manage_user';
		$this->_render($templates);
	}

	public function update_user_role() {
		$this->load->library('form_validation');
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
			'user' => $this->container->user['id'],
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
					'redirect' => $this->config->item('base_url').'manage_user/user_list'
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
		$this->load->library('form_validation');
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
			'user' => $this->container->user['id'],
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
			$result = $this->authenticator->update_user_by_index(array('id' => $this->input->post_get('id')), array('role' => (int) $this->input->post_get('access_level'), 'updated_at' => time()));
			if ($result) {
				$data = array(
					'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_UPDATE_ACCESS_LEVEL')),
					'redirect' => $this->config->item('base_url').'manage_user/user_list'
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
		$this->load->library('form_validation');
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
			'user' => $this->container->user['id'],
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
			$result = $this->authenticator->update_user_by_index(array('id' => $this->input->post_get('id')), array('role' => (int) $this->input->post_get('status'), 'updated_at' => time()));
			if ($result) {
				$data = array(
					'message' => str_replace('%s', $this->input->post_get('id', TRUE), lang('M_SUCCESS_UPDATE_STATUS')),
					'redirect' => $this->config->item('base_url').'manage_user/user_list'
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
		$this->load->library('form_validation');
		$data = array(
			'id' => $this->input->post_get('id', TRUE),
			'user' => $this->container->user['id'],
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
					'redirect' => $this->config->item('base_url').'manage_user/user_list'
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
}
