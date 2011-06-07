<?php
/**
 * Defines a Search widget that overrides the default WP Search Widget.
 *
 * @package Suffusion
 * @subpackage Widgets
 *
 */
class Suffusion_Search extends WP_Widget {
	function Suffusion_Search() {
		$widget_options = array(
			"classname" => "search",
			"description" => __("A search form for your blog", "suf_theme"),
		);

		$control_options = array(
			"id_base" => "search"
		);

		$this->WP_Widget("search", __("Search", "suf_theme"), $widget_options, $control_options);
	}

	function widget($args, $instance) {
		extract($args);

		$title = apply_filters("widget_title", $instance["title"]);
		echo $before_widget;
		if (trim($title) != "") {
			echo $before_title.$title.$after_title;
		}
		get_search_form();
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance["title"] = strip_tags($new_instance["title"]);

		return $instance;
	}

	function form($instance) {
		$defaults = array("title" => __("Search", "suf_theme"));
		$instance = wp_parse_args((array)$instance, $defaults);
?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'suf_theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

<?php
	}
}
?>