<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

	public $index = array('role' => 1, 'access_level' => 1);

	public function __construct() {
		parent::__construct();
		$this->template = 'widgets/dashboard/template';
		$this->widgets['nav'] = 'widgets/dashboard/nav';
		$this->widgets['dashboard_menu'] = 'widgets/dashboard/dashboard_menu';
	}

	public function index() {
		$this->AllowGetRequest();
		$this->data['title'] = $this->container['app_name'].' | '.lang('H_DASHBOARD');
		$this->data['page_name'] = str_replace('%s', $this->container['app_name'], lang('H_DASHBOARD'));
		$this->data['content'] = $this->load->view('dashboard/welcome', $this->data, TRUE);
		$this->_renderLayout();
	}
}
