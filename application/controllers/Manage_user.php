<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user extends MY_Controller {

	// Rule valid if user exist in session
	// more lowest more power
	// public $MIN_ROLE = 0; //[global] override *_MIN_ROLE
	// public $MIN_ACCESS_LEVEL = 0; //[global] override *_MIN_ACCESS_LEVEL
	public $ui_user_list__MIN_ROLE = 0;
	public $ui_user_list__MIN_ACCESS_LEVEL = 0;

	public function ui_user_list() {
		$this->data['title'] = APP_NAME.' | '.lang('H_MANAGE_USERS');
		$this->data['page_name'] = lang('H_MANAGE_USERS');
		$templates[] = 'welcome_message';
		$this->_render($templates);
	}
}
