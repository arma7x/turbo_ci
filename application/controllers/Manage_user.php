<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user extends MY_Controller {

	// public $GLOBAL_AUTH = TRUE; 
	// -> Not required IF $GLOBAL_ROLE is NOT NULL OR $GLOBAL_ACCESS_LEVEL is NOT NULL
	// -> Not required IF $*_MIN_ROLE is NOT NULL OR $*__MIN_ACCESS_LEVEL is NOT NULL
	// more lowest more power
	// public $GLOBAL_ROLE = 0; // override *_MIN_ROLE
	// public $GLOBAL_ACCESS_LEVEL = 0; // override *_MIN_ACCESS_LEVEL
	public $ui_user_list__MIN_ROLE = 0; // specific to [method_name]__MIN_ROLE
	public $ui_user_list__MIN_ACCESS_LEVEL = 0; // specific to [method_name]__MIN_ACCESS_LEVEL

	public function ui_user_list() {
		$this->data['title'] = APP_NAME.' | '.lang('H_MANAGE_USERS');
		$this->data['page_name'] = lang('H_MANAGE_USERS');
		$templates[] = 'welcome_message';
		$this->_render($templates);
	}
}
