<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	protected $data = [];

	public function __construct() {
		parent::__construct();
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

	protected function _renderJSON($data, $status = 200) {
		$this->output->set_content_type('application/json');
		$this->output->set_status_header($status);
		$this->output->set_output(json_encode($data));
		$this->output->_display();
		die;
	}
}
