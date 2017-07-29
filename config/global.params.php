<?php
defined('INDEX_UNLOCK') or die();
//echo '<pre>'.print_r($_SERVER, true).'</pre>';die();
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
$realpath = str_replace('structure/index.php', '', str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']));
$params['document_root'] = $realpath;
$subfolder = str_replace($realpath, '', str_replace('structure/index.php', '', $_SERVER['PHP_SELF']));
$host = (substr($_SERVER['HTTP_HOST'], -1, 1) != '/' && substr($subfolder, 0, 1) != '/') ? $_SERVER['HTTP_HOST'].'/' : $_SERVER['HTTP_HOST'];

$params['site_url'] = $protocol.'://'.$host.$subfolder;
$params['base_url'] = $params['site_url'];

$params['wsdl_url'] = $wsdl_url;

$params['site_name'] = $site_name;
$params['compact_site_name'] = str_replace(' ', '', strtolower($site_name));

$params['languages'] = $languages;
$params['services'] = $services;
$params['directives'] = $directives;

$params['db_libs'] = $db_libs;
$params['use_db'] = $use_db;

$params['email_system']	= $email_system;
$params['admin'] = $admin;
$params['email_admin']	= $email_admin;

$params['folders']['engine'] = $params['document_root'].$folder_engine;
$params['folders']['application'] = $params['document_root'].$folder_application;
$params['folders']['structure']	= $params['document_root'].$folder_structure;
$params['folders']['upload'] = $params['folders']['structure'].DIRECTORY_SEPARATOR.$folder_upload;
$params['folders']['cache'] = $params['document_root'].$folder_cache;

$params['adresses']['upload'] = $params['site_url'].$folder_upload;
$params['adresses']['engine'] = $params['site_url'].$folder_engine;
$params['adresses']['application'] = $params['site_url'].$folder_application;
$params['adresses']['structure']	= $params['site_url'].$folder_structure;
$params['adresses']['cache'] = $params['site_url'].$folder_cache;

$params['template'] = $default_template;

$params['maintenance'] = $maintenance;

$params['devel'] = $devel;

$params['debug'] = $debug;