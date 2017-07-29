<?php
$pages = $page->getAllPages();

foreach($pages as $pname=>$attrs)
{
	echo '<a href="'.$params->site_url.$pname.'">'.$attrs['title'].'</a> - file: '.$attrs['body'].'<br /><br />';
	//utils::trace($attrs);
}
?>