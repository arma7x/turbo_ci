<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class JWT {

	const JWT_NAME = 'Authorization';
	const JWT_TOKEN_EXPIRED = 31536000;
	const JWT_COOKIE_EXPIRED = 3600;

	protected $CI;
	public $token;

	public function __construct() {
		$this->CI = &get_instance();
		$this->validate();
	}

	private function validate() {
		$token = '';
		if ($this->CI->input->get_request_header(SELF::JWT_NAME, TRUE) !== NULL) {
			$parts = explode(' ', $this->CI->input->cookie(SELF::JWT_NAME, TRUE));
			$token = (COUNT($parts) >= 1) ? $parts[1] : '';
		} else if ($this->CI->input->cookie(strtolower(SELF::JWT_NAME), TRUE) !== NULL) {
			$token = $this->CI->input->cookie(strtolower(SELF::JWT_NAME), TRUE);
		}
		
		try {
			$token = (new Parser())->parse((string) $token);
			$signer = new Sha256();
			$validator = new ValidationData();
			if ($token->verify($signer, $this->CI->config->item('encryption_key')) !== TRUE || $token->validate($validator) === FALSE) {
				$this->generate(NULL, array());
			} else {
				$this->token = $token;
			}
		} catch(Exception $error) {
			$this->generate(NULL, array());
		}
	}

	public function generate($jti, $claims) {
		$signer = new Sha256();
		$time = time();
		$expired = 3600;
		$secure_cookie = (bool) $this->CI->config->item('cookie_secure');
		if (is_https()) {
			$secure_cookie = TRUE;
		}
		$token = (new Builder());
		$token
			//->setIssuer($this->CI->config->item('base_url'))
			//->setAudience($this->CI->config->item('base_url'))
			->setIssuedAt($time)
			->setNotBefore($time)
			->setExpiration(time() + SELF::JWT_TOKEN_EXPIRED);
		if (is_array($claims)) {
			foreach($claims as $name => $value) {
				$token->set($name, $value);
			}
		}
		if ($jti !== NULL) {
			$token->setId($jti, true);
		}
		$token->sign($signer, $this->CI->config->item('encryption_key'));
		$this->token = $token->getToken();
		$this->CI->output->set_header(SELF::JWT_NAME.': Bearer '.$token->getToken());
		$this->CI->input->set_cookie(array(
				'name'   => strtolower(SELF::JWT_NAME),
				'value'  => $token->getToken(),
				'expire' => SELF::JWT_COOKIE_EXPIRED,
				'domain' => $this->CI->config->item('cookie_domain'),
				'path'   => $this->CI->config->item('cookie_path'),
				'secure' => $secure_cookie,
				'httponly' => TRUE
			)
		);
	}

}
