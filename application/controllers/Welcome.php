<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/* 
	| # RULES AND GUIDE
	|
	| [USELESS]array('auth' = NULL, role' => 0, 'access_level' => 0);
	| [USELESS]array('auth' = FALSE, role' => 0, 'access_level' => 0);
	| 
	| [VALID]array('auth' = NULL);
	| [VALID]array('auth' = TRUE);
	| [VALID]array('auth' = FALSE);
	| [VALID]array('role' => 0, 'access_level' => 0);
	| [VALID]array('auth' = TRUE, 'role' => 0, 'access_level' => 0);
	|
	| ----------------------------------------------------------------
	|
	| # AUTHENTICATION FLOW
	|
	| (1) auth
	| 	-> NULL[allow all]
	| 	-> TRUE[must logged-in]
	| 	-> FALSE[must not logged-in]
	| (2) role(auth must TRUE to define this value)
	| 	-> lowest value has more power, 0 is lowest value, maximum value  is 127
	| 	-> USER['role'] value must lower than or equal to defined value to PASS
	| (3) access_level(auth must TRUE to define this value)
	| 	-> lowest value has more power, 0 is lowest value, maximum value is 127
	| 	-> USER['access_level'] value must lower than or equal to defined value to PASS
	|
	| ----------------------------------------------------------------
	|
	| # Use class name(lowercase) for global rule, this will overrride on all method rules
	|
	| public $welcome = array('auth' => NULL);
	| public $welcome = array('role' => 0, 'access_level' => 0);
	|
	| ----------------------------------------------------------------
	| 
	| # Use method name(lowercase) for specific method only
	|
	| public $index = array('auth' => NULL);
	| public $index = array('role' => 0, 'access_level' => 0);
	|
	| ----------------------------------------------------------------
	| 
	| # EXAMPLE
	|
	| public $index = array('role' => 1, 'access_level' => 1);
	|
	| USER['role'] is 0 and USER['access_level'] is 0 = ALLOW
	| USER['role'] is 0 and USER['access_level'] is 1 = ALLOW
	| USER['role'] is 1 and USER['access_level'] is 1 = ALLOW
	| USER['role'] is 1 and USER['access_level'] is 0 = ALLOW
	|
	| USER['role'] is 0 and USER['access_level'] is 127 = BLOCK
	| USER['role'] is 1 and USER['access_level'] is 127 = BLOCK
	| USER['role'] is 127 and USER['access_level'] is 127 = BLOCK
	*/

	public function __construct() {
		parent::__construct();
		// Set rule here if controller need dynamic rules
	}

	public function index() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container->app_name.' | '.lang('H_HOMEPAGE');
		$this->data['page_name'] = str_replace('%s', $this->container->app_name, lang('H_WELCOME'));
		$this->data['content'] = $this->load->view('welcome', $this->data, TRUE);
		$this->_renderLayout();
	}

	public function offline() {
		$this->AllowGetRequest();
		$this->container->user = NULL;
		$this->data['title'] = $this->container->app_name.' | '.lang('H_Offline');
		$this->data['page_name'] = lang('H_Offline');
		$this->data['message'] = lang('M_OFFLINE');
		$this->data['content'] = $this->load->view('offline', $this->data, TRUE);
		$this->_renderLayout();
	}

	public function language() {
		$this->BlockGetRequest();
		$this->load->helper('cookie');
		$expire = time() + (60 * 60 * 24 * 365);
		$lang = 'english';
		$new_lang = $this->input->post_get('lang', TRUE);
		if ($new_lang === 'malay') {
			$lang = $new_lang;
		}
		setcookie('lang', $lang, $expire, $this->config->item('cookie_path'), $this->config->item('cookie_domain'), $this->config->item('cookie_secure'), $this->config->item('cookie_httponly'));
		$data = array('message' => $lang);
		$this->_renderJSON(200, $data);
	}

}
