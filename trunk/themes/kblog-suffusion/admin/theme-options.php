<?php
/**
 * Lists out all the options in "Suffusion Theme Options".
 * This file is included in functions.php
 *
 * @package Suffusion
 * @subpackage Admin
 */

global $category_array, $sidebar_tabs, $suffusion_404_title, $suffusion_404_content, $all_page_ids, $all_category_ids;

include_once(TEMPLATEPATH . "/admin/theme-options-intro.php");
include_once(TEMPLATEPATH . "/admin/theme-options-visual-effects.php");
include_once(TEMPLATEPATH . "/admin/theme-options-sidebars-and-widgets.php");
include_once(TEMPLATEPATH . "/admin/theme-options-blog-features.php");
include_once(TEMPLATEPATH . "/admin/theme-options-templates.php");

$options = array();
foreach ($intro_options as $option) {
	$options[] = $option;
}
foreach ($visual_effects_options as $option) {
	$options[] = $option;
}
foreach ($sidebars_and_widgets_options as $option) {
	$options[] = $option;
}
foreach ($blog_features_options as $option) {
	$options[] = $option;
}
foreach ($templates_options as $option) {
	$options[] = $option;
}

function suffusion_load_module($option_file, $option_array_name) {
	global $options;
	include_once(TEMPLATEPATH.$option_file);
	$option_array = $$option_array_name;
	foreach ($option_array as $option) {
		$options[] = $option;
	}
}
?>