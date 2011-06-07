<?php
/**
 * Template Name: All Categories
 *
 * Displays all the categories used within the blog.
 *
 * @package Suffusion
 * @subpackage Templates
 */

global $suffusion_unified_options;
foreach ($suffusion_unified_options as $id => $value) {
	$$id = $value;
}

get_header();
?>
    <div id="main-col">
<?php suffusion_before_begin_content(); ?>
	  <div id="content">
<?php
if (have_posts()) {
	while (have_posts()) {
		the_post();
?>
    <div class="post fix" id="post-<?php the_ID(); ?>">
<?php suffusion_after_begin_post(); ?>

        <div class="entry fix">
			<?php suffusion_content(); ?>
		</div><!--/entry -->

		<ul class="category-archives">
<?php
		$show_hierarchical = $suf_temp_cats_hierarchical == "hierarchical" ? true : false;
		$show_post_count = $suf_temp_cats_post_count == "show" ? true : false;
		if ($suf_temp_cats_rss == "show") {
			wp_list_categories(array('feed' => __('RSS', 'suf_theme'), 'show_count' => $show_post_count, 'hierarchical' => $show_hierarchical,
				'use_desc_for_title' => false, 'title_li' => false ));
		}
		else {
			wp_list_categories(array('show_count' => $show_post_count, 'hierarchical' => $show_hierarchical, 'use_desc_for_title' => false, 'title_li' => false ));
		}
?>
		</ul><!-- /.category-archives -->
<?php
		suffusion_before_end_post();
		comments_template();
?>
		</div><!-- post -->
<?php
	}
}
?>
      </div><!-- content -->
	</div><!-- main col -->
<?php get_footer(); ?>
