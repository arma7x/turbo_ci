<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	// Flow checking auth -> role(auth must TRUE) -> access_level(auth must TRUE)

	// [USELESS]array('auth' = NULL, role' => 0, 'access_level' => 0); // caught on auth error
	// [USELESS]array('auth' = FALSE, role' => 0, 'access_level' => 0); // caught on auth error

	// [VALID]array('auth' = NULL);
	// [VALID]array('auth' = TRUE);
	// [VALID]array('auth' = FALSE);
	// [VALID]array('role' => 0, 'access_level' => 0);
	// [VALID]array('auth' = TRUE, 'role' => 0, 'access_level' => 0);

	// use lowercase class name for global, will overrride all method rules
	// public $welcome = array('auth' => NULL); // if need check auth only
	// public $welcome = array('role' => 0, 'access_level' => 0); //auth is consider TRUE, if defined NULL or FALSE will run it first before continue process flow

	// use lowercase method name for specific method
	// public $index = array('auth' => NULL); // if need check auth only
	// public $index = array('role' => 0, 'access_level' => 0); //auth is consider TRUE, if defined NULL or FALSE will run it first before continue process flow

	// -> NULL[allow all]
	// -> TRUE[must logged-in]
	// -> FALSE[must not logged-in]

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
