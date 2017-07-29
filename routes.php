<?php
/**
 * In questo file vanno indicate tutte le pagine dell sito
 *
 * quelle già presenti sono considerate come pagine di default e se ne sconsiglia l'eliminazione
 * per definire una singola pagina bisogna definirne il nome
 * ad esempio 'azienda'
 * per avere una pagina che funga da indice di sezione basterà definirla come nel seguente esempio
 * 'azienda/*'
 * l'asterisco alla fine del nome indicherà al framework di accettare qualsiasi indirizzo che abbia come radice la stringa azienda
 *
 * @author  Marco Delle Feste <marco.delle.feste@gmail.com>
 *
 * @since 1.0
 *
 * @package pittbullcms
 */
$routes = array(
	'getAll'	=> array(
		'id'=>'getAll',
		'css_class'=> '',
		'template_name'=>'default',
		'body'=>'body/getAll.php',
		'access_level'=>10,
		'title'=>'Elenco di tutte le pagine',
	),
	'coming-soon'=> array(
		'id'=>'coming-soon',
		'css_class'=> '',
		'template_name'=>'coming-soon',
		'body'=>'body/coming-soon.php',
		'access_level'=>10,
		'title'=>'Saremo presto online!',
	),
	'home'	=> array(
		'id'=>'home',
		'css_class'=> '',
		'template_name'=>'default',
		'body'=>'body/home.php',
		'access_level'=>10,
		'title'=>'Home',
		'custom'=>'%01$s',
	),
	'user/*' =>array(
		'id'=>'user-%02$s',
		'css_class'=> '',
		'template_name'=>'default',
		'body'=>'body/user-%02$s.php',
		'access_level'=>10,
		'title'=>'User %02$s',
		'custom'=>'%02$s',
	),
	'login' =>array(
		'id'=>'login',
		'css_class'=> '',
		'template_name'=>'default',
		'body'=>'body/login.php',
		'access_level'=>10,
		'title'=>'Login',
	),
	'logout' =>array(
		'id'=>'logout',
		'css_class'=> '',
		'template_name'=>'default',
		'body'=>'body/logout.php',
		'access_level'=>10,
		'title'=>'Logout',
	),
	'administration' =>array(
		'id'=>'adminitration',
		'css_class'=> '',
		'template_name'=>'default',
		'body'=>'dinamic',
		'access_level'=>30,
		'title'=>'Amministrazione',
	),
);