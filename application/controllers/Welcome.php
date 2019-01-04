<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index() {
		$this->data['title'] = 'Codeigniter | '.lang('H_HOMEPAGE');
		$this->data['page_name'] = lang('H_HOMEPAGE');
		$templates[] = 'welcome_message';
		$this->_render($templates);
	}

	public function registration() {
		$this->data['user'] = array(
			'username' => 'arma7x',
			'email' => 'arma7x@live.com',
		);
		$this->data['url'] = $this->config->item('base_url');
		$this->load->view('email_templates/registration.php', $this->data, FALSE);
	}

	public function confirm() {
		$this->data['user'] = array(
			'username' => 'arma7x',
			'email' => 'arma7x@live.com',
		);
		$this->data['url'] = $this->config->item('base_url').'guest/activate-account?token=1';
		$this->load->view('email_templates/confirm.php', $this->data, FALSE);
	}

	public function reset() {
		$this->data['user'] = array(
			'username' => 'arma7x',
			'email' => 'arma7x@live.com',
		);
		$this->data['url'] = $this->config->item('base_url').'guest/reset-password?token=1';
		$this->load->view('email_templates/reset.php', $this->data, FALSE);
	}

}
