<?php if( reports::count_report() > 0 ) : ?>
<div class="reports">
<?php
	$reports = reports::get_report();
	foreach($reports as $case=>$msg_list)
	{
		$file = $case.'.php';
		switch($case)
		{
			case 'notice':
			case 'errors':
			case 'success':
			default:
				$html_params = array('id'=>'reports-'.$case, 'css_class'=>$case);
			break;
		}
		require('reports'.SP.$file);
	}
?>
<br /><a href="#close">chiudi</a></div>
<?php endif; ?>