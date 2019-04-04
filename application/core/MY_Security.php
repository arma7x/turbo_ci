<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Security extends CI_Security {

	public function __construct() {
		parent::__construct();
		if (config_item('csrf_protection') && $this->get_request_header('Authorization') === NULL) {
			foreach (array('csrf_expire', 'csrf_token_name', 'csrf_cookie_name') as $key) {
				if (NULL !== ($val = config_item($key))) {
					$this->{'_'.$key} = $val;
				}
			}
			if ($cookie_prefix = config_item('cookie_prefix')) {
				$this->_csrf_cookie_name = $cookie_prefix.$this->_csrf_cookie_name;
			}
			$this->_csrf_set_hash();
		}
		$this->charset = strtoupper(config_item('charset'));
		log_message('info', 'Security Class Initialized');
	}

	public function request_headers() {
		if (function_exists('apache_request_headers')) {
			return apache_request_headers();
		} else {
			$headers = array();
			isset($_SERVER['CONTENT_TYPE']) && $headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
			foreach ($_SERVER as $key => $val) {
				if (sscanf($key, 'HTTP_%s', $header) === 1) {
					$header = str_replace('_', ' ', strtolower($header));
					$header = str_replace(' ', '-', ucwords($header));
					$headers[$header] = $_SERVER[$key];
				}
			}
			return $headers;
		}
	}

	public function get_request_header($index, $xss_clean = FALSE) {
		$headers = $this->request_headers();
		foreach ($headers as $key => $value) {
			$headers[strtolower($key)] = $value;
		}
		$index = strtolower($index);
		if (!isset($headers[$index])) {
			return NULL;
		}
		return ($xss_clean === TRUE) ? $this->xss_clean($headers[$index]) : $headers[$index];
	}
}
