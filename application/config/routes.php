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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Asset routes
$route['assets'] = 'assets/index';
$route['assets/table/(:any)'] = 'assets/table/$1';

// Auth routes
$route['login'] = 'login/index';
$route['login/authenticate'] = 'login/authenticate';
$route['logout'] = 'login/logout';

// Pages routes (profile / sign-up)
$route['pages/profile'] = 'pages/profile';
$route['pages/sign-up'] = 'pages/sign_up';
$route['pages/sign_up'] = 'pages/sign_up';

// Import routes (Phase 1: GI)
$route['import'] = 'import/index';
$route['import/(:any)'] = 'import/index/$1';
$route['import/preview'] = 'import/preview';
$route['import/preview/(:any)'] = 'import/preview/$1';
$route['import/process'] = 'import/process';
$route['import/process/(:any)'] = 'import/process/$1';
$route['import/status/(:num)'] = 'import/status/$1';
$route['import/download-error/(:num)'] = 'import/download_error/$1';
$route['import/commit/(:num)'] = 'import/commit/$1';

// Rekomposisi routes
$route['entry_kontrak'] = 'entry_kontrak/index';
$route['entry_kontrak/tambah'] = 'entry_kontrak/tambah';
$route['entry_kontrak/store'] = 'entry_kontrak/store';

$route['entry_kontrak/prk_by_jenis'] = 'entry_kontrak/prk_by_jenis';
$route['entry_kontrak/drp_by_prk'] = 'entry_kontrak/drp_by_prk';
$route['entry_kontrak/detail_prk_drp'] = 'entry_kontrak/detail_prk_drp';
$route['prognosa'] = 'prognosa/index';
$route['prognosa/detail'] = 'prognosa/detail';
$route['prognosa/export_csv'] = 'prognosa/export_csv';
$route['entry_kontrak/export_gsheet'] = 'entry_kontrak/export_gsheet';

// E-Transport routes
$route['transport'] = 'Transport_request/index';
$route['transport/ajukan'] = 'Transport_request/create';
$route['transport/simpan'] = 'Transport_request/store';
$route['transport/daftar_saya'] = 'Transport_request/my_requests';
$route['transport/semua_daftar'] = 'Transport_request/all_requests';
$route['transport/detail/(:num)'] = 'Transport_request/detail/$1';
$route['transport/export_pdf'] = 'Transport_request/export_pdf';
$route['transport/export_pdf/(:num)'] = 'Transport_request/export_pdf/$1';

$route['transport/approval'] = 'Transport_approval/index';
$route['transport/approve/(:num)'] = 'Transport_approval/approve/$1';
$route['transport/reject/(:num)'] = 'Transport_approval/reject/$1';
$route['transport/approval/edit/(:num)'] = 'Transport_approval/edit/$1';
$route['transport/approval/update/(:num)'] = 'Transport_approval/update/$1';

$route['transport/fleet'] = 'Transport_fleet/index';
$route['transport/fleet_process/(:num)'] = 'Transport_fleet/process/$1';

$route['transport/security'] = 'Transport_security/index';
$route['transport/security_checkin/(:num)'] = 'Transport_security/checkin/$1';
$route['transport/security_checkout/(:num)'] = 'Transport_security/checkout/$1';

