<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="bg">
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml;charset=<?php bloginfo('charset'); ?>" />
	<title><?php if( is_search() ) { $ser = $_GET['s']; ?><?php _e('Search'); ?><?php echo " &quot;" . wp_specialchars($ser, 1) . "&quot;"; } ?><?php wp_title(''); ?><?php if( ! is_home() ) { ?> | <?php } ?><?php echo strip_tags(html_entity_decode(get_option('blogname'),ENT_NOQUOTES,'UTF-8')); ?></title>
	<meta http-equiv="imagetoolbar" content="no" />
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
	
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/ie7-style.css" />
	<![endif]-->

	<!--[if IE]>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/ie-style.css" />
	<![endif]-->

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/mobile.css" type="text/css" media="handheld" />

	<style type="text/css">
		@import url(<?php bloginfo('stylesheet_directory'); ?>/mobile.css) screen and (max-width:801px);
	</style>
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<?php wp_head(); ?>
	
</head>
<body>

	<div id="header">
		<?php if (is_home() || is_page_template('archives.php') ){ ?>
		<h1><a href="<?php echo get_option('home'); ?>/"><?php echo html_entity_decode(get_option('blogname'),ENT_NOQUOTES,'UTF-8'); ?></a></h1>
		<?php } else { ?>
		<p class="title"><a href="<?php echo get_option('home'); ?>/"><?php echo html_entity_decode(get_option('blogname'),ENT_NOQUOTES,'UTF-8'); ?></a></p>
		<?php } ?>
		<div class="description"><?php bloginfo('description'); ?></div>
		<?php /*
		<ul id="navigation">
			<li><a href="/"><?php _e('Home'); ?></a></li>
			<?php wp_list_pages('title_li=&depth=1&sort_column=menu_order'); ?>
		</ul>
		*/ ?>
	</div>
