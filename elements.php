<?php
/**
 * Questo file permette l'assegnazione degli elements che sarranno dispibili per le pagine dell sito
 *
 * Ci sono due modi per assegnare un elemento ad una pagina.
 * * definirlo come elmento di default
 * * definirlo come elemento di una pagina specifica
 *
 * @author  Marco Delle Feste <marco.delle.feste@gmail.com>
 *
 * @since 1.0
 *
 * @package pittbullcms
 */
/**
 * L'array $default raccoglie tutti gli elements disponibili in ogni pagina che si basa sul template tpl.default.php
 * per creare un gruppo di elements da assegnare ad un altro template, basterà creare una variabile che abbia lo stesso nome assegnato all'indice
 * template_name nell'array pages
 */
$default = array(
	'logo' => array('file'=>'logo.php',	'access_level'=>10,	'callback'=>false,),
	
	'menu_global' => array('file'=>'menu-global.php','access_level'=>10,'callback'=>false,),
	
	'reports' => array('file'=>'reports.php','access_level'=>10,'callback'=>false,),
	
	'user_panel' => array('file'=>(!$this->user->is_logged) ? 'user-login.php' : 'user-welcome.php','access_level'=>10,'callback'=>false,),
	
	'body' => array('file'=>$this->page->body,'access_level'=>10,'callback'=>false,),
	
	'menu_footer' => array('file'=>'menu-footer.php','access_level'=>10,'callback'=>false,),
	
	'site_info' => array('file'=>'site-info.php','access_level'=>1,'callback'=>false,),
	
	'test_element' => array('file'=>'test.php','access_level'=>30,'callback'=>false,),

);
/**
 * L'array $pages raccoglie gli elements che saranno resi disponibili in base alla coincidenza con l'indice della pagina
 */
$pages = array();

