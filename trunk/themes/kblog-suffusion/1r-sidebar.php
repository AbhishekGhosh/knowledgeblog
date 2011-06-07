<?php
/**
 * Template Name: Single Right Sidebar
 *
 * @package Suffusion
 * @subpackage Templates
 */

get_header();
?>

<div id="main-col">
<?php
suffusion_page_navigation();
suffusion_before_begin_content();
?>
	<div id="content">
<?php
if (have_posts()) {
	while (have_posts()) {
		the_post();
?>
		<div <?php post_class('fix'); ?> id="post-<?php the_ID(); ?>">
			<?php suffusion_after_begin_post(); ?>
			<div class="entry-container fix">
				<div class="entry fix">
					<?php suffusion_content(); ?>
				</div><!--/entry -->
			<?php
					suffusion_after_content();
			?>
			</div><!-- .entry-container -->
			<?php suffusion_before_end_post(); ?>

			<?php comments_template(); ?>
		</div><!--/post -->
<?php
	}
}
?>
	</div><!-- #content -->
</div><!-- #main-col -->
<?php get_footer(); ?>
