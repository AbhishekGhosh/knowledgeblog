<?php get_header(); ?>

<?php add_filter('the_content', 'fix_content'); ?>

<?php if (have_posts()) : ?>
	<?php rewind_posts(); ?>

	<?php if (!is_paged()) : the_post(); ?>
	<div class="latest" id="post-<?php the_ID(); ?>">
		<p class="details_small">
			on <?php the_date(); ?> 
						<!-- SJC -->
						<!--by <?php the_author(); ?>-->
						by <?php if( function_exists( "coauthors" ) ){coauthors();} ?>
			in <?php the_category(', '); ?>, 
			<?php comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')); ?>
		</p>
		<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<div class="post_content">
		<?php the_content('Read more...'); ?>
		</div>
	</div>
	<?php endif; ?>
	
	<?php
	remove_filter('get_the_excerpt', 'wp_trim_excerpt');
	add_filter('get_the_excerpt', 'gd_short_excerpt');
	?>


	<div id="content"<?php if (is_home() && !is_paged()) { ?> class="home"<?php } ?>>


		<?php while (have_posts()) : the_post(); ?>

		<div class="post list" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permalink: <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<p class="details_small">
				on <?php the_date(); ?> 
						<!-- SJC -->
						<!--by <?php the_author(); ?>-->
						by <?php if( function_exists( "coauthors" ) ){coauthors();} ?>
	<div class="latest" id="post-<?php the_ID(); ?>">
		<p class="details_small">
			on <?php the_date(); ?> 
						<!-- SJC -->
						<!--by <?php the_author(); ?>-->
						by <?php if( function_exists( "coauthors" ) ){coauthors();} ?>
				in <?php the_category(', '); ?>, 
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
		
	</div>
	<?php else : ?>
	<div id="content"<?php if (is_home() && !is_paged()) { ?> class="home"<?php } ?>>
		<?php include (TEMPLATEPATH . '/not_found.php'); ?>
	</div>
	<?php endif; ?>

<?php get_footer(); ?>
