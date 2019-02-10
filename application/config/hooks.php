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
	$CI = &get_instance();
	$CI->load->helper('cookie');
	$expire = time() + (60 * 60 * 24 * 365);
	$lang = get_cookie('lang');
	if ($lang === NULL) {
		$lang = $CI->config->item('language');
	}
	setcookie('lang', $lang, $expire, $CI->config->item('cookie_path'), $CI->config->item('cookie_domain'), $CI->config->item('cookie_secure'), $CI->config->item('cookie_httponly'));
	if ($lang === 'malay') {
		$CI->lang->load('app', 'malay');
	} else {
		$CI->lang->load('app', 'english');
	}
};

$hook['post_controller_constructor'][] = function() {
	$CI = &get_instance();
	$CI->container['app_name'] = APP_NAME;
	if ($CI->session->status !== NULL) {
		$user = $CI->authenticator->get_user_by_index(array('id' => $CI->session->user['id']), 'id, username, email, role, access_level, status, avatar');
		if ($user !== NULL) {
			if ((int) $user['role'] === 0) {
				$user['role_alias'] = lang('L_ADMIN');
			} else if ((int) $user['role'] === 1) {
				$user['role_alias'] = lang('L_MODERATOR');
			} else {
				$user['role_alias'] = lang('L_MEMBER');
			}
			$CI->container['user'] = $user;
			if ((int) $user['status'] < 1) {
				$CI->authenticator->clear_credential();
				show_error('Unauthorized', 401, '401 - Unauthorized');
			}
		}
	} else {
		$CI->container['user'] = NULL;
	}
	$CI->container['sw_offline_cache'] = $CI->input->get_request_header('Sw-Offline-Cache', TRUE);
	if ($CI->container['sw_offline_cache'] !== NULL) {
		$CI->container['user'] = NULL;
		$CI->lang->load('app', $CI->config->item('language'));
		//log_message('error', 'Cache::'.$CI->container['sw_offline_cache']);
	}
};

$hook['post_controller_constructor'][] = function() {
	$CI = &get_instance();
	$class = strtolower($CI->router->class);
	$method = strtolower($CI->router->method);
	$require_auth = NULL;

	if (isset($CI->$class)) {
		if((isset($CI->$class['enable']) ? $CI->$class['enable'] : TRUE) === FALSE) {
			show_404();
		}
		if((isset($CI->$class['auth']) ? $CI->$class['auth'] : NULL) !== NULL) {
			$require_auth = $CI->$class['auth'];
		}
	} else if (isset($CI->$method)) {
		if((isset($CI->$method['enable']) ? $CI->$method['enable'] : TRUE) === FALSE) {
			show_404();
		}
		if ((isset($CI->$method['auth']) ? $CI->$method['auth'] : NULL) !== NULL) {
			$require_auth = $CI->$method['auth'];
		}
	}

	if ($require_auth === TRUE && $CI->container['user'] === NULL) {
		show_error('Unauthorized', 401, '401 - Unauthorized');
	} else if ($require_auth === FALSE && $CI->container['user'] !== NULL) {
		show_error('Forbidden Access', 403, '403 - Forbidden Access');
	}
};

$hook['post_controller_constructor'][] = function() {
	$CI = &get_instance();
	$class = strtolower($CI->router->class);
	$method = strtolower($CI->router->method);
	$require_role = NULL;
	$require_access_level = NULL;

	if (isset($CI->$class)) {
		if((isset($CI->$class['role']) ? $CI->$class['role'] : NULL) !== NULL) {
			$require_role = $CI->$class['role'];
		}
	} else if (isset($CI->$method)) {
		if ((isset($CI->$method['role']) ? $CI->$method['role'] : NULL) !== NULL) {
			$require_role = $CI->$method['role'];
		}
	}

	if (isset($CI->$class)) {
		if((isset($CI->$class['access_level']) ? $CI->$class['access_level'] : NULL) !== NULL) {
			$require_access_level = $CI->$class['access_level'];
		}
	} else if (isset($CI->$method)) {
		if ((isset($CI->$method['access_level']) ? $CI->$method['access_level'] : NULL) !== NULL) {
			$require_access_level = $CI->$method['access_level'];
		}
	}

	if ($require_role !== NULL || $require_access_level !== NULL) {
		if ($CI->container['user'] === NULL) {
			show_error('Unauthorized', 401, '401 - Unauthorized');
		} elseif ($CI->container['user'] !== NULL) {
			if (((int) $CI->container['user']['role'] <= $require_role) === FALSE) {
				show_error('Forbidden Access', 403, '403 - Forbidden Access');
			}
			if (((int) $CI->container['user']['access_level'] <= $require_access_level) === FALSE) {
				show_error('Forbidden Access', 403, '403 - Forbidden Access');
			}
		}
	}
};
