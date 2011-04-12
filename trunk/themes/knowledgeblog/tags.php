<?php
/*
Template Name: Tags
*/
?>
<?php get_header(); ?>
		<div id="content" class="single">
			<div class="post">
				<h2><?php _e('Tags'); ?></h2>
				
				<p class="tag-cloud">
				<?php wp_tag_cloud('smallest=1&largest=4&unit=em'); ?>
				</p>

			</div>
		</div>
<?php get_footer(); ?>
