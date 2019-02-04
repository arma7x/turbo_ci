<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

	public $index = array('role' => 1, 'access_level' => 1);

	public function __construct() {
		parent::__construct();
		$this->header_template = 'widgets/dashboard/header';
		$this->footer_template = 'widgets/dashboard/footer';
	}

	public function index() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container->app_name.' | '.lang('H_DASHBOARD');
		$this->data['page_name'] = str_replace('%s', $this->container->app_name, lang('H_DASHBOARD'));
		$templates[] = 'dashboard/welcome';
		$this->_render($templates);
	}
}
