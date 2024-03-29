<?php
/**
 * This replaces the default Meta widget of WordPress
 *
 * @package Suffusion
 * @subpackage Widgets
 * 
 */
class Suffusion_Meta extends WP_Widget_Meta {
	function Suffusion_Meta() {
		$widget_ops = array('classname' => 'widget_meta', 'description' => __( "Log in/out, admin, feed and WordPress links", "suf_theme") );
		$this->WP_Widget('meta', __('Meta', 'suf_theme'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Meta', 'suf_theme') : $instance['title']);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
?>
			<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<li class="rss"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo esc_attr(__('Syndicate this site using RSS 2.0', 'suf_theme')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>', 'suf_theme'); ?></a></li>
			<li class="rss"><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(__('The latest comments to all posts in RSS', 'suf_theme')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>', 'suf_theme'); ?></a></li>
			<?php wp_meta(); ?>
			</ul>
<?php
		echo $after_widget;
	}
}
?>
