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
    if ($CI->session->status !== NULL) {
        $user = $CI->authenticator->get_user_by_index(array('id' => $CI->session->user['id']), 'status');
        if ((int) $user['status'] < 1) {
            $CI->authenticator->clear_credential();
            show_error('Unauthorized', 401, '401 - Unauthorized');
        }
    }
};

$hook['post_controller_constructor'][] = function() {
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
    //$method = $CI->router->method;  //get method to access
    //var_dump($method);
    //var_dump(isset($CI->$method) ? $CI->$method : NULL); // get access level, if NULL ->access to all
    //die;
};
