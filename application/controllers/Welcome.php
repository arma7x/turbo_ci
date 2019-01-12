<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	// use lowercase class name for global, will overrride all method rules
	// public $welcome = array('auth' => NULL);
	// public $welcome = array('role' => 0, 'access_level' => 0); //auth is automatically TRUE
	// use lowercase method name for specific method
	// public $index = array('auth' => NULL);
	// public $index = array('role' => 0, 'access_level' => 0); //auth is automatically TRUE
	//  -> TRUE[must logged-in]
	//  -> FALSE[must not logged-in]
	//  -> NULL[allow all]

	public function __construct() {
		parent::__construct();
		// Set here if controller need dynamic rules
	}

	public function index() {
		$this->data['title'] = APP_NAME.' | '.lang('H_HOMEPAGE');
		$this->data['page_name'] = lang('H_HOMEPAGE');
		$templates[] = 'welcome_message';
		$this->_render($templates);
	}

}
