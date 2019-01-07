<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	// TRUE[must logged-in]
	// FALSE[must not logged-in]
	// NULL[allow all]
	// public $GLOBAL_AUTH = FALSE; // override *__AUTH rule
	// public $index__AUTH = FALSE; // specific to [method_name]__AUTH
	public $index__AUTH = NULL; // if not defined OR value is NULL will allow all access

	public function index() {
		$this->data['title'] = APP_NAME.' | '.lang('H_HOMEPAGE');
		$this->data['page_name'] = lang('H_HOMEPAGE');
		$templates[] = 'welcome_message';
		$this->_render($templates);
	}

}
