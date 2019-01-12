<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Src extends MY_Controller {

	public function css() {
		$this->_renderCSS(array('assets/css/app'));
	}

	public function js() {
		$this->_renderJS(array('assets/js/app'));
	}

}
