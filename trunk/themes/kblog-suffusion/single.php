<?php
get_header();
?>
    <div id="main-col">
  	<div id="content">
<?php
if (have_posts()) {
	while (have_posts()) {
		the_post();
		if (suffusion_post_count() > 1) {
?>
	<div class='post-nav'>
	<table>
		<tr>
			<td class='previous'><?php previous_post_link('%link', '%title') ?></td>
			<td class='next'><?php next_post_link('%link', '%title') ?></td>
		</tr>
	</table>
	</div>
<?php
		}
		$custom_class = "";
		if ($post->post_type != 'post' && $post->post_type != 'page') {
			// Custom post type. See if there is style inheritance
			$suffusion_post_types = get_option('suffusion_post_types');
			if (is_array($suffusion_post_types)) {
				foreach ($suffusion_post_types as $suffusion_post_type) {
					if ($suffusion_post_type['style_inherit'] != 'custom') {
						$custom_class = $suffusion_post_type['style_inherit'];
					}
				}
			}
			if ($custom_class == "") {
				$custom_class = "post";
			}
		}
?>
	<div <?php post_class($custom_class);?> id="post-<?php the_ID(); ?>">
<?php suffusion_after_begin_post(); ?>
		<div class="entry-container fix">
			<div class="entry fix">
<?php
	suffusion_content();
?>
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
