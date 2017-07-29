<?php $page->send_header(); ?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title><?php echo $params->site_name.' - '.$page->title; ?></title>
	<meta name="author" content="<?php echo $params->author; ?>">
	<meta name="description" content="<?php echo $params->description; ?>">
	<!-- CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<link href="<?php echo $params->base_url; ?>css/style.css" media="all" rel="stylesheet" />
	<link href="<?php echo $params->base_url; ?>css/edit.css" media="all" rel="stylesheet" />
	<?php echo $template->get_files('css'); ?>
	<!-- CSS -->
</head>
<body id="<?php echo $page->id; ?>" class="<?php echo $page->css_class; ?>">
<div id="container">
	<header>
		
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<?php $template->get_element('logo', array()); ?>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<?php $template->get_element('menu_global'); ?>
				</div>
			</div>
		</nav>
		
		
		</nav>
	</header>
	<div id="main" role="main" class="container">
		<div class="starter-template">
		<?php $template->get_element('reports'); ?>
		<?php $template->get_element('body'); ?>
		</div>
	</div>
	<footer>
		<?php $template->get_element('menu_footer'); ?>
		<?php $template->get_element('site_info'); ?>
	</footer>
</div>
<!-- JAVASCRIPT -->
<script type="text/javascript"><?php //echo $template->get_js_obj(); ?></script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php echo $params->base_url; ?>js/jquery.ext.js"></script>
<script type="text/javascript" src="<?php echo $params->base_url; ?>js/global.functions.js"></script>
<?php echo $template->get_files('js'); ?>
<!-- JAVASCRIPT -->
</body>
</html>