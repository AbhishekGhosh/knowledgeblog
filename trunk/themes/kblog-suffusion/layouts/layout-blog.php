<?php
/**
 * This file creates a blog-style layout of posts, useful if you are creating a generic blog.
 * This file is not to be loaded directly, but is instead loaded from different templates.
 *
 * @package Suffusion
 * @subpackage Templates
 */

global $query_string, $wp_query, $full_content_post_counter, $full_post_count, $suffusion_blog_layout, $suffusion_duplicate_posts;
$suffusion_blog_layout = true;
if (!isset($suffusion_duplicate_posts)) $suffusion_duplicate_posts = array();

if (have_posts()) {
	$full_content_post_counter = 0;
	$full_post_count = suffusion_get_full_content_count();
	while (have_posts()) {
		the_post();
		if (in_array($post->ID, $suffusion_duplicate_posts)) {
			continue;
		}
		$full_content_post_counter++;
?>
	<div <?php post_class();?> id="post-<?php the_ID(); ?>">
<?php
		suffusion_after_begin_post();
?>
	<div class="entry-container fix">
		<div class="entry entry-content fix">
<?php
		suffusion_content();
?>
		</div><!--entry -->
<?php
		suffusion_after_content();
?>
	</div><!-- .entry-container -->
<?php
		suffusion_before_end_post();
?>
	</div><!--post -->
<?php
	}
	suffusion_before_end_content();
}
else {
	if (!function_exists('get_template_part')) {
		include_once(TEMPLATEPATH . '/layouts/template-missing.php');
	}
	else {
		get_template_part('layouts/template-missing');
	}
}
?>