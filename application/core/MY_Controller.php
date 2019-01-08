<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'core/Container.php');

class MY_Controller extends CI_Controller {

	public $container;
	protected $data = [];

	public function __construct() {
		parent::__construct();
		$this->container = new Container();
		$debug = isset($_GET['debug']) ? TRUE : FALSE;
		$this->output->enable_profiler(ENVIRONMENT == 'development' ? $debug : FALSE);
	}

	protected function _render($template = []) {
		$this->load->view('widgets/header', $this->data);
		foreach ($template as $view) {
			$this->load->view($view, $this->data);
		}
		$this->load->view('widgets/footer', $this->data);
	}

	protected function _renderJSON($status = 200, $data) {
		if ($data !== NULL) {
			$this->data = array_merge($this->data, $data);
		}
		$this->output->set_content_type('application/json');
		$this->output->set_status_header($status);
		$this->output->set_output(json_encode($this->data));
		$this->output->_display();
		die;
	}

	protected function _renderJS($template) {
		$this->output->set_content_type('application/javascript');
		foreach ($template as $view) {
			$this->load->view($view, $this->data);
		}
		$this->output->_display();
		die;
	}

	protected function _renderCSS($template) {
		$this->output->set_content_type('text/css');
		foreach ($template as $view) {
			$this->load->view($view, $this->data);
		}
		$this->output->_display();
		die;
	}
}
