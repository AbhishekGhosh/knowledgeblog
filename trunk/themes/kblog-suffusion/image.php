<?php
get_header();
?>
    <div id="main-col">
  	<div id="content">
<?php
if (have_posts()) {
	while (have_posts()) {
		the_post();
?>
	<div <?php post_class(array('post', 'fix'));?> id="post-<?php the_ID(); ?>">
<?php suffusion_after_begin_post(); ?>
		<div class="entry-container fix">
			<div class="entry fix">
<?php
	suffusion_attachment();
	suffusion_content();
?>
	<p class="navigation-attachment">
		<span class="alignleft"><?php previous_image_link('thumbnail', __('Previous Image', 'suf_theme')); ?></span>
		<span class="alignright"><?php next_image_link('thumbnail', __('Next Image', 'suf_theme')); ?></span>
	</p><!-- .navigation-attachment -->
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
else {
?>
        <div class="post fix">
		<p><?php _e('Sorry, no posts matched your criteria.', 'suf_theme'); ?></p>
        </div><!--post -->

<?php
}
?>
      </div><!-- content -->
    </div><!-- main col -->
<?php
get_footer();
?>