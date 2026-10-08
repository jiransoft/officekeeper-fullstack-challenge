<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'      => '',
    'hostname' => getenv('DB_HOST') ?: 'mysql',
    'username' => getenv('DB_USERNAME') ?: 'dlp_user',
    'password' => getenv('DB_PASSWORD') ?: 'dlp_password',
    'database' => getenv('DB_DATABASE') ?: 'dlp_console',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress'  => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE,
);
