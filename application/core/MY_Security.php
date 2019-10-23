<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries'.DIRECTORY_SEPARATOR.'JWT.php');

class MY_Security extends CI_Security {

	public function __construct() {
		parent::__construct();
		if (config_item('csrf_protection') && $this->get_request_header(JWT::$JWT_NAME) === NULL) {
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

	public function csrf_verify()
	{
		// If it's not a POST request we will set the CSRF cookie
		if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST' || $this->get_request_header(JWT::$JWT_NAME) !== NULL)
		{
			return $this->csrf_set_cookie();
		}

		// Check if URI has been whitelisted from CSRF checks
		if ($exclude_uris = config_item('csrf_exclude_uris'))
		{
			$uri = load_class('URI', 'core');
			foreach ($exclude_uris as $excluded)
			{
				if (preg_match('#^'.$excluded.'$#i'.(UTF8_ENABLED ? 'u' : ''), $uri->uri_string()))
				{
					return $this;
				}
			}
		}

		// Check CSRF token validity, but don't error on mismatch just yet - we'll want to regenerate
		$valid = isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name])
			&& is_string($_POST[$this->_csrf_token_name]) && is_string($_COOKIE[$this->_csrf_cookie_name])
			&& hash_equals($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name]);

		// We kill this since we're done and we don't want to pollute the _POST array
		unset($_POST[$this->_csrf_token_name]);

		// Regenerate on every submission?
		if (config_item('csrf_regenerate'))
		{
			// Nothing should last forever
			unset($_COOKIE[$this->_csrf_cookie_name]);
			$this->_csrf_hash = NULL;
		}

		$this->_csrf_set_hash();
		$this->csrf_set_cookie();

		if ($valid !== TRUE)
		{
			$this->csrf_show_error();
		}

		log_message('info', 'CSRF token verified');
		return $this;
	}

	private function request_headers() {
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

	private function get_request_header($index, $xss_clean = FALSE) {
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
