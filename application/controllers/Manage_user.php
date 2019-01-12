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
			'keyword' => $this->input->get('keyword') !== null ? $this->input->get('keyword', TRUE) : NULL,
			'role' => $this->input->get('role') !== null ? (int) $this->input->get('role', TRUE) : NULL,
			'access_level' => $this->input->get('access_level') !== null ? (int) $this->input->get('access_level', TRUE) : NULL,
			'status' => $this->input->get('status') !== null ? (int) $this->input->get('status', TRUE) : NULL,
		);
		$this->data['user_list'] = $this->user_model->get_user_list($filter, current_url(), 1, (int) $this->input->get('page'), TRUE);
		$templates[] = 'admin/manage_user';
		$this->_render($templates);
	}

	public function update_user_role() {}

	public function update_user_access_level() {}

	public function update_user_status() {}

	public function delete_user() {}
}
