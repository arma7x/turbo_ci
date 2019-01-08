<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opcache extends MY_Controller {

	// public $global_auth_rule = TRUE; 
	// -> Not required IF $global_role_rule is NOT NULL OR $global_access_level_rule is NOT NULL
	// -> Not required IF $*__role_rule is NOT NULL OR $*__access_level_rule is NOT NULL
	// more lowest more power
	public $global_role_rule = 0; // override *__role_rule
	public $global_access_level_rule = 0; // override *__access_level_rule
	// public $*__role_rule = 0; // specific to [method_name]__role_rule
	// public $*__access_level_rule = 0; // specific to [method_name]__access_level_rule

	public function index() {
		require_once(APPPATH.'libraries/Opcache.php');
	}
}
