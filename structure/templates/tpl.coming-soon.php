<?php $page->send_header(); ?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo $params->site_name.' - '.$page->title; ?></title>
<meta name="author" content="<?php echo $params->author; ?>">
<meta name="description" content="<?php echo $params->description; ?>">
<!-- CSS -->
<link href="<?php echo $params->base_url; ?>css/coming-soon.css" media="all" rel="stylesheet" />
<!-- CSS -->
</head>
<body id="<?php echo $page->id; ?>" class="<?php echo $page->css_class; ?>">
<div id="container">
<header>
    <nav>
      <?php $template->get_element('logo', array()); ?>
    </nav>
  </header>
  <div id="main" role="main">
    <article class="content">
      <?php $template->get_element('body'); ?>
    </article>
  </div>
  <footer>
  <?php $template->get_element('site_info'); ?>
  </footer>
</div>
<!-- JAVASCRIPT -->
<!-- JAVASCRIPT -->
</body>
</html>