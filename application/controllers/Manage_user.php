<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user extends MY_Controller {

	// public $global_auth_rule = TRUE; 
	// -> Not required IF $global_role_rule is NOT NULL OR $global_access_level_rule is NOT NULL
	// -> Not required IF $*__role_rule is NOT NULL OR $*__access_level_rule is NOT NULL
	// more lowest more power
	// public $global_role_rule = 0; // override *__role_rule
	// public $global_access_level_rule = 0; // override *__access_level_rule
	public $ui_user_list__role_rule = 0; // specific to [method_name]__role_rule
	public $ui_user_list__access_level_rule = 0; // specific to [method_name]__access_level_rule

	public function ui_user_list() {
		$this->data['title'] = APP_NAME.' | '.lang('H_MANAGE_USERS');
		$this->data['page_name'] = lang('H_MANAGE_USERS');
		$this->load->helper('url');
		$this->load->model('User_Model', 'user_model');
		$this->data['user_list'] = $this->user_model->get_user_list(current_url(), 1, TRUE, (int) $this->input->get('page'));
		$templates[] = 'admin/manage_user';
		$this->_render($templates);
	}
}
