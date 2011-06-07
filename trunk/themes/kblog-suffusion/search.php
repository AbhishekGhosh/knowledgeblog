<?php
/**
 * Search results, can be set up to show either excerpts or full contents
 *
 * @package Suffusion
 * @subpackage Templates
 */

global $suffusion_unified_options;
foreach ($suffusion_unified_options as $id => $value) {
	$$id = $value;
}

get_header();
suffusion_query_posts();
?>
    <div id="main-col">
<?php suffusion_before_begin_content(); ?>
      <div id="content" class="hfeed">
<?php
if ($suf_search_excerpt == 'list') {
	if (!function_exists('get_template_part')) {
		include_once(TEMPLATEPATH . '/layouts/layout-list.php');
	}
	else {
		get_template_part('layouts/layout-list');
	}
}
else if ($suf_search_excerpt == 'tiles') {
	suffusion_after_begin_content();
	if (!function_exists('get_template_part')) {
		include_once(TEMPLATEPATH . '/layouts/layout-tiles.php');
	}
	else {
		get_template_part('layouts/layout-tiles');
	}
}
else {
	suffusion_after_begin_content();
	if (!function_exists('get_template_part')) {
		include_once(TEMPLATEPATH . '/layouts/layout-blog.php');
	}
	else {
		get_template_part('layouts/layout-blog');
	}
}
?>
      </div><!-- content -->
    </div><!-- main col -->
<?php get_footer(); ?>