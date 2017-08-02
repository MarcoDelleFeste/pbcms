<?php
defined('INDEX_UNLOCK') or die();

$display_errors = true;
$error_reporting = E_ALL & ~E_STRICT;

$languages = array('default'=>'it', 'en');
$services = array();
$directives = array('account', 'admin');

$db_libs = true; //indica al sistema se caricare le librerie per l'uso del database
$use_db = false; //serve a definire il modo in cui il sistema ricerca pagine, tool, elementi e contenuti.

$email_system	= 'your.email.address@google.com';
$admin = 'Your name';
$author = 'Your name'; 
$description = 'a description'; 
$email_admin	= 'First name Last name <your.email.address@google.com>';
$site_name	= 'Site name - ';

$folder_structure = 'structure';
$folder_upload = 'uploads';
$folder_engine = 'engine';
$folder_application = 'application';
$folder_cache = 'cache';

$default_template = 'tpl.default.php';
/**
* Indirizzi di accesso ad eventuali web service.
**/
$wsdl_url = '';

$devel = true;

$debug = false;

$maintenance = false;
