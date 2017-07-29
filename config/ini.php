<?php
defined('INDEX_UNLOCK') or die('ACCESS DENIED');
require('config.default.php');
require('../functions.php');
require('../config.php');

ini_set('display_errors', $display_errors);
error_reporting($error_reporting);

$params = array();

require('global.params.php');
require('global.constants.php');

require($params['folders']['engine'].SP.'utils.class.php');
require($params['folders']['engine'].SP.'dispatcher.class.php');
require($params['folders']['engine'].SP.'reports.class.php');

$dispatcher = new dispatcher($params);

require($params['folders']['engine'].SP.'params.class.php');


if(DB_LIBS) {
	require('conn.php');
	require($params['folders']['engine'].SP.'db'.SP.'dbInt.class.php');
}
require(CORE_PATH.SP.'singleton.class.php');
require(CORE_PATH.SP.'cookie.class.php');
require(CORE_PATH.SP.'session.class.php');
if(is_file($params['folders']['application'].SP.'user.class.php')) {
	require($params['folders']['application'].SP.'user.class.php');
} else {
	require(CORE_PATH.SP.'user.class.php');
}
require(CORE_PATH.SP.'page.class.php');
require(CORE_PATH.SP.'template.class.php');
require(CORE_PATH.SP.'tools.class.php');
require(CORE_PATH.SP.'elements.class.php');
require(CORE_PATH.SP.'core.class.php');

$dispatcher->get_classes();

$params = $dispatcher->get_params();
unset($dispatcher);
$params['main_class'] = str_replace('-', '_', $params['main_class']);

$params = params::get_instance($params);
$core = new $params->main_class;
//oggetti per il template
$session = $core->get_object('session');
$user = $core->get_object('user');
$page = $core->get_object('page');
$tools = $core->get_object('tools');
$template = $core->get_object('template');
$elements = $core->get_object('elements');
$contents = $tools->get_tool('contents');
//echo $tools['contents']->get_response();
//utils::trace($obj);