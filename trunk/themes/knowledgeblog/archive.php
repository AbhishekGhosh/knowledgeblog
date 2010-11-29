<?php
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'gd_short_excerpt');
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<div id="content" class="archive">
			<h1>
				<?php if ( is_category() || is_day() || is_month() || is_year() || is_paged() || (function_exists(is_tag) && is_tag()) ) { ?>
					<?php if (is_category()) { ?>
						Posts In Category <?php single_cat_title(''); ?>
					<?php } elseif ( function_exists(is_tag) && is_tag()) { ?>
						Posts Tagged <?php single_tag_title(''); ?>
					<?php } elseif (is_day()) { ?>
						Archive For <?php the_time(); ?>.
					<?php } elseif (is_month()) { ?>
						Archive For  <?php the_time('F Y'); ?>
					<?php } elseif (is_year()) { ?>
						Archive For  <?php the_time('Y'); ?>
					<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
						Archive
					<?php } ?>
				<?php }?>
			</h1>
			<?php while (have_posts()) : the_post(); ?>
				<div class="post list" id="post-<?php the_ID(); ?>">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<p class="details_small">
						on <?php the_date(); ?> 
						by <?php the_author(); ?>
						in <?php the_category(', '); ?>,
						<?php comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')); ?>
					</p>
					<?php the_excerpt(); ?>
					<p><a href="<?php the_permalink() ?>" rel="bookmark"><?php _e('Read more...') ;?></a></p>
				</div>
			<?php endwhile; ?>
			<div class="navigation">
				<div class="prev"><?php next_posts_link('&laquo; previous posts') ?></div>
				<div class="next"><?php previous_posts_link('next posts &raquo;') ?></div>
			</div>
		</div>
	<?php else : ?>
		<?php include (TEMPLATEPATH . '/not_found.php'); ?>
	<?php endif; ?>
<?php get_footer(); ?>
