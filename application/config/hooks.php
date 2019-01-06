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
    // /auth && /internal-api/auth must login
    // /guest && /internal-api/guest must not login
    $CI = &get_instance();
    if ($CI->uri->segment(1) === 'auth' && $CI->session->status === NULL) {
        show_error('Unauthorized', 401, '401 - Unauthorized');
    }
    if ($CI->uri->segment(1) === 'guest' && $CI->session->status != NULL) {
        show_error('Forbidden Access', 403, '403 - Forbidden Access');
    }
    if ($CI->uri->segment(1) === 'internal-api' && $CI->uri->segment(2) === 'auth' && $CI->session->status === NULL) {
        show_error('Unauthorized', 401, '401 - Unauthorized');
    }
    if ($CI->uri->segment(1) === 'internal-api' && $CI->uri->segment(2) === 'guest' && $CI->session->status != NULL) {
        show_error('Forbidden Access', 403, '403 - Forbidden Access');
    }
};

$hook['post_controller_constructor'][] = function() {
    $CI = &get_instance();
    $method = $CI->router->method;
    
    $require_role = NULL;
    $min_role_method = $method.'__MIN_ROLE';
    if((isset($CI->MIN_ROLE) ? $CI->MIN_ROLE : NULL) !== NULL) {
        $require_role = $CI->MIN_ROLE;
    } else if ((isset($CI->$min_role_method) ? $CI->$min_role_method : NULL) !== NULL) {
        $require_role = $CI->$min_role_method;
    }
    
    $require_access_level = NULL;
    $min_access_level_method = $method.'__MIN_ACCESS_LEVEL';
    if ((isset($CI->MIN_ACCESS_LEVEL) ? $CI->MIN_ACCESS_LEVEL : NULL) !== NULL){
        $require_access_level = $CI->MIN_ACCESS_LEVEL;
    } else if ((isset($CI->$min_access_level_method) ? $CI->$min_access_level_method : NULL) !== NULL) {
        $require_access_level = $CI->$min_access_level_method;
    }

    if ($require_role !== NULL || $require_access_level !== NULL) {
        if ($CI->container->user !== NULL) {
            if (((int) $CI->container->user['role'] <= $require_role) === FALSE) {
                show_error('Forbidden Access', 403, '403 - Forbidden Access');
            }
            if (((int) $CI->container->user['access_level'] <= $require_access_level) === FALSE) {
                show_error('Forbidden Access', 403, '403 - Forbidden Access');
            }
        }
    }
};
