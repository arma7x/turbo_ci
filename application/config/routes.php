<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['offline']['get'] = 'welcome/offline';
$route['authentication/ui_login']['get'] = 'authentication/ui_login';
$route['authentication/ui_register']['get'] = 'authentication/ui_register';
$route['authentication/ui_forgot_password']['get'] = 'authentication/ui_forgot_password';
$route['authentication/ui_activate_account']['get'] = 'authentication/ui_activate_account';
$route['authentication/ui_reset_password']['get'] = 'authentication/ui_reset_password';
$route['authentication/ui_update_password']['get'] = 'authentication/ui_update_password';
$route['authentication/manage_token']['get'] = 'authentication/manage_token';
$route['manage_user/user_list']['get'] = 'manage_user/user_list';
$route['src/app.css']['get'] = 'src/css';
$route['src/app.js']['get'] = 'src/js';
$route['sw.js']['get'] = 'src/sw';

$route['authentication/login']['post'] = 'authentication/login';
$route['authentication/register']['post'] = 'authentication/register';
$route['authentication/forgot_password']['post'] = 'authentication/forgot_password';
$route['authentication/activate_account']['post'] = 'authentication/activate_account';
$route['authentication/reset_password']['post'] = 'authentication/reset_password';
$route['authentication/update_password']['post'] = 'authentication/update_password';
$route['authentication/delete_token']['post'] = 'authentication/delete_token';
$route['authentication/upload_avatar']['post'] = 'authentication/upload_avatar';
$route['manage_user/update_user_role']['post'] = 'manage_user/update_user_role';
$route['manage_user/update_user_access_level']['post'] = 'manage_user/update_user_access_level';
$route['manage_user/update_user_status']['post'] = 'manage_user/update_user_status';
$route['manage_user/delete_user']['post'] = 'manage_user/delete_user';
$route['authentication/log_out']['post'] = 'authentication/log_out';
