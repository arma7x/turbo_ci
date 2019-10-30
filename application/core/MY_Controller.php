<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Pimple\Container;

class MY_Controller extends CI_Controller {

	public $container;
	protected $template = 'widgets/frontend/template';
	protected $widgets = array();
	protected $data = [];

	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('X-XSS-Protection: 1; mode=block');
		$this->output->set_header('X-Frame-Options: DENY');
		$this->output->set_header('X-Content-Type-Options: nosniff');
		$this->container = new Container();
		$debug = isset($_GET['debug']) ? TRUE : FALSE;
		$this->output->enable_profiler(ENVIRONMENT == 'development' ? $debug : FALSE);
	}

	final protected function AllowGetRequest() {
		if ($this->input->method(FALSE) !== 'get') {
			log_message('error', $this->input->method(FALSE).'::'.$this->uri->uri_string());
			show_error("Method Not Allowed", 405);
		}
	}

	final protected function BlockGetRequest() {
		if ($this->input->method(FALSE) === 'get') {
			log_message('error', $this->input->method(FALSE).'::'.$this->uri->uri_string());
			show_error("Method Not Allowed", 405);
		}
	}

	final protected function _renderLayout() {
		foreach ($this->widgets as $index => $widget) {
			$this->data[$index] = $this->load->view($widget, $this->data, TRUE);
		}
		$this->load->view($this->template, $this->data);
	}

	final protected function _renderJSON($status = 200, $data = array()) {
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
