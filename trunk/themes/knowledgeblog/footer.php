	<div id="sidebarFrame">	

	<?php
		include (TEMPLATEPATH . '/sidebar_top.php');
		include (TEMPLATEPATH . '/sidebar_left.php');
		include (TEMPLATEPATH . '/sidebar_right.php');
	?>
	</div>

	<div id="footer">
		<p>
		<?php echo sprintf(__("Powered by <a href='http://wordpress.org/' title='%s'><strong>WordPress</strong></a>"), __("Powered by WordPress, state-of-the-art semantic personal publishing platform.")); ?>.
		<a href="http://greatgonzo.net/projects/gonzodaily">GonzoDaily</a> theme by <a href="http://greatgonzo.net">Gonzo</a>.
		</p>
		<p>
		<a href="<?php echo get_bloginfo('rss2_url'); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a>, <a href="<?php echo get_bloginfo('comments_rss2_url'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a>
		<!-- <br /><?php printf('%d queries. %s seconds.', get_num_queries(), timer_stop(0, 3)); ?> -->
		</p>
<?php wp_footer(); ?>

	</div>
	
</body>
</html>