<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'core/Container.php');

class MY_Controller extends CI_Controller {

	public $container;
	protected $header_template = 'widgets/header';
	protected $footer_template = 'widgets/footer';
	protected $data = [];

	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->container = new Container();
		$debug = isset($_GET['debug']) ? TRUE : FALSE;
		$this->output->enable_profiler(ENVIRONMENT == 'development' ? $debug : FALSE);
	}

	final protected function _render($template = []) {
		$this->load->view($this->header_template, $this->data);
		foreach ($template as $view) {
			$this->load->view($view, $this->data);
		}
		$this->load->view($this->footer_template, $this->data);
	}

	final protected function _renderJSON($status = 200, $data) {
		if ($data !== NULL) {
			$this->data = array_merge($this->data, $data);
		}
		$this->output->set_content_type('application/json');
		$this->output->set_status_header($status);
		$this->output->set_output(json_encode($this->data, JSON_PRETTY_PRINT));
		$this->output->_display();
		die;
	}

	final protected function _renderJS($template) {
		$this->output->set_content_type('application/javascript');
		foreach ($template as $view) {
			$this->load->view($view, $this->data);
		}
		$this->output->_display();
		die;
	}

	final protected function _renderCSS($template) {
		$this->output->set_content_type('text/css');
		foreach ($template as $view) {
			$this->load->view($view, $this->data);
		}
		$this->output->_display();
		die;
	}
}
