<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	// TRUE[must logged-in]
	// FALSE[must not logged-in]
	// NULL[allow all]
	// public $global_auth_rule = FALSE; // override *__auth_rule rule
	// public $index__auth_rule = FALSE; // specific to [method_name]__auth_rule
	public $index__auth_rule = NULL; // if not defined OR value is NULL will allow all access

	public function __construct() {
		parent::__construct();
		// $this->index__auth_rule = TRUE; // Set here if controller need dynamic rules
	}

	public function index() {
		$this->data['title'] = APP_NAME.' | '.lang('H_HOMEPAGE');
		$this->data['page_name'] = lang('H_HOMEPAGE');
		$templates[] = 'welcome_message';
		$this->_render($templates);
	}

	public function css() {
		$this->_renderCSS(['assets/css/app']);
	}

	public function js() {
		$this->_renderJS(['assets/js/app']);
	}

}
