<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = function() {
	// Check if user status in session is not active
	$CI = &get_instance();
	if ($CI->session->status !== NULL) {
		$user = $CI->authenticator->get_user_by_index(array('id' => $CI->session->user['id']), 'role, access_level, status');
		if ($user !== NULL) {
			$CI->container['user'] = $user;
			if ((int) $user['status'] < 1) {
				$CI->authenticator->clear_credential();
				show_error('Unauthorized', 401, '401 - Unauthorized');
			}
		}
	}
};

$hook['post_controller_constructor'][] = function() {
	$CI = &get_instance();
	$method = $CI->router->method;

	$require_auth = NULL;
	$auth_method = $method.'__auth_rule';
	if((isset($CI->global_auth_rule) ? $CI->global_auth_rule : NULL) !== NULL) {
		$require_auth = $CI->global_auth_rule;
	} else if ((isset($CI->$auth_method) ? $CI->$auth_method : NULL) !== NULL) {
		$require_auth = $CI->$auth_method;
	}
	if ($require_auth === TRUE && $CI->session->status === NULL) {
		show_error('Unauthorized', 401, '401 - Unauthorized');
	} else if ($require_auth === FALSE && $CI->session->status !== NULL) {
		show_error('Forbidden Access', 403, '403 - Forbidden Access');
	}
};

$hook['post_controller_constructor'][] = function() {
	$CI = &get_instance();
	$method = $CI->router->method;

	$require_role = NULL;
	$role_rule_method = $method.'__role_rule';
	if((isset($CI->global_role_rule) ? $CI->global_role_rule : NULL) !== NULL) {
		$require_role = $CI->global_role_rule;
	} else if ((isset($CI->$role_rule_method) ? $CI->$role_rule_method : NULL) !== NULL) {
		$require_role = $CI->$role_rule_method;
	}

	$require_access_level = NULL;
	$access_level_rule_method = $method.'__access_level_rule';
	if ((isset($CI->global_access_level_rule) ? $CI->global_access_level_rule : NULL) !== NULL){
		$require_access_level = $CI->global_access_level_rule;
	} else if ((isset($CI->$access_level_rule_method) ? $CI->$access_level_rule_method : NULL) !== NULL) {
		$require_access_level = $CI->$access_level_rule_method;
	}

	if ($require_role !== NULL || $require_access_level !== NULL) {
		if ($CI->container->user === NULL) {
			show_error('Unauthorized', 401, '401 - Unauthorized');
		} elseif ($CI->container->user !== NULL) {
			if (((int) $CI->container->user['role'] <= $require_role) === FALSE) {
				show_error('Forbidden Access', 403, '403 - Forbidden Access');
			}
			if (((int) $CI->container->user['access_level'] <= $require_access_level) === FALSE) {
				show_error('Forbidden Access', 403, '403 - Forbidden Access');
			}
		}
	}
};
