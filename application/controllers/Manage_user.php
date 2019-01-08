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
		$config['full_tag_open'] = '<div class="row justify-content-sm-center align-items-center"><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div>';
		$config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close'] = '</span></li>';
		$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close'] = '</span></li>';
		$config['first_link'] = '<i class="material-icons">&#xe5dc;</i>';
		$config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['next_link'] = '<i class="material-icons">&#xe5cc;</i>';
		$config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close'] = '</span></li>';
		$config['prev_link'] = '<i class="material-icons">&#xe5cb;</i>';
		$config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['prev_tag_close'] = '</span></li>';
		$config['last_link'] = '<i class="material-icons">&#xe5dd;</i>';
		$config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close'] = '</span></li>';

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
