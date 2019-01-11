<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user extends MY_Controller {

	// public $global_auth_rule = TRUE; 
	// -> Not required IF $global_role_rule is NOT NULL OR $global_access_level_rule is NOT NULL
	// -> Not required IF $*__role_rule is NOT NULL OR $*__access_level_rule is NOT NULL
	// more lowest more power
	// public $global_role_rule = 0; // override *__role_rule
	// public $global_access_level_rule = 0; // override *__access_level_rule
	public $ui_user_list__role_rule = 1; // specific to [method_name]__role_rule
	public $ui_user_list__access_level_rule = 1; // specific to [method_name]__access_level_rule
	public $update_user_role__role_rule = 0;
	public $update_user_role__access_level_rule = 0;
	public $update_user_access_level__role_rule = 0;
	public $update_user_access_level__access_level_rule = 0;
	public $update_user_status__role_rule = 0;
	public $update_user_status__access_level_rule = 0;
	public $delete_user__role_rule = 0;
	public $delete_user__access_level_rule = 0;

	public function ui_user_list() {
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
