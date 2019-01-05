<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index() {
		$this->data['title'] = 'Codeigniter | '.lang('H_HOMEPAGE');
		$this->data['page_name'] = lang('H_HOMEPAGE');
		$templates[] = 'welcome_message';
		$this->_render($templates);
	}

}
