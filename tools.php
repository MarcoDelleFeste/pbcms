<?php
/**
 * Questo file permette l'assegnazione dei tools che sarranno dispibili per le pagine dell sito
 * 
 * Ci sono due modi per assegnare un tool ad una pagina.
 * * definirlo come tool di default
 * * definirlo come tool di una pagina specifica
 *
 * @author  Marco Delle Feste <marco.delle.feste@gmail.com>
 *
 * @since 1.1
 *
 * @package pittbullcms
 */
/**
 * L'array $default raccoglie tutti i tools disponibili in ogni pagina
*/
$default = array(
	'prototype'=>array('autoload'=>false, 'action'=>'read'),
);
/**
 * L'array $pages raccoglie i tools che saranno resi disponibili in base alla coincidenza con l'indice della pagina
*/
$pages = array(
	'user/*' =>array('user-account'=>array('autoload'=>false, 'action'=>'%02$s')),
);
 
