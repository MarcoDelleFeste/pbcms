<div id="<?php echo $html_params['id']; ?>" class="<?php echo $html_params['css_class']; ?>">
<?php 
foreach($msg_list as $msg)
{
	echo $msg.'<br />';
}
?>
</div>