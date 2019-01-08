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
		$this->load->library('pagination');
		$this->load->helper('url');
		$config['base_url'] = current_url();
		$config['num_links'] = 100/10;
		$config['total_rows'] = 100/10;
		$config['per_page'] = 1;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$this->pagination->initialize($config);
		$templates[] = 'admin/manage_user';
		$this->_render($templates);
	}
}
