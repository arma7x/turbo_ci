<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Src extends MY_Controller {

	public function css() {
		$this->_renderCSS(array('src/css/app.css'));
	}

	public function js() {
		$this->_renderJS(array('src/js/app.js'));
	}

}
