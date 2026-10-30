<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'export_log';
$route['export_log'] = 'export_log/index';
$route['export_log/search'] = 'export_log/search';
$route['export_log/detail/(:num)'] = 'export_log/detail/$1';
$route['export_log/delete'] = 'export_log/delete';
$route['export_log/export_csv'] = 'export_log/export_csv';
$route['export_log/stats'] = 'export_log/stats';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
