<?php
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'gd_short_excerpt');
?>
<?php get_header(); ?>
		<div id="content" class="archive">
			<h1><?php _e("Search"); ?> - "<?php the_search_query(); ?>"</h1>
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<div class="post list" id="post-<?php the_ID(); ?>">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<p class="details_small">
						on <?php the_date() ?> 
						<!-- SJC -->
						<!--by <?php the_author(); ?>-->
						by <?php coauthors(); ?>
						in <?php the_category(', ') ?>, 
						<?php comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')); ?>
					</p>
					<?php the_excerpt(); ?>
					<p><a href="<?php the_permalink() ?>" rel="bookmark"><?php _e('Read more...') ;?></a></p>
				</div>
			<?php endwhile; ?>
			<div class="navigation">
				<div class="prev"><?php next_posts_link('&laquo; Previous posts') ?></div>
				<div class="next"><?php previous_posts_link('Next posts &raquo;') ?></div>
			</div>
		<?php else : ?>
			<div class="post">
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
			</div>
		<?php endif; ?>

		</div>
		
<?php get_footer(); ?>
