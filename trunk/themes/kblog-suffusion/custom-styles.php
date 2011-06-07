<?php
/**
 * Dynamically generated styles
 *
 * @package Suffusion
 * @subpackage Templates
 */

global $suffusion_unified_options, $theme_name, $content_width, $suffusion_reevaluate_styles;
if ($suffusion_reevaluate_styles) {
	$suffusion_unified_options = suffusion_get_unified_options(true, true);
}
foreach ($suffusion_unified_options as $id => $value) {
	global $$id;
	$$id = $value;
}
$css_generator = new Suffusion_CSS_Generator();
if ($suf_body_style_setting == 'custom') {
?>

body {
	background-color: #<?php echo $suf_body_background_color;?>;
<?php
	if ($suf_body_background_image != "") {
		$body_bg_url = " url($suf_body_background_image) ";
?>
	background-image: <?php echo $body_bg_url;?>;
	background-repeat: <?php echo $suf_body_background_repeat;?>;
	background-attachment: <?php echo $suf_body_background_attachment;?>;
	background-position: <?php echo $suf_body_background_position;?>;
<?php
	}
?>
}
<?php
}
if ($suf_wrapper_settings_def_cust == 'custom') {
?>
#wrapper {
	<?php echo $css_generator->get_bg_information('suf_wrapper_bg_settings'); ?>
<?php
	if ($suf_show_shadows == "show") {
?>
	/* Shadows - CSS3 for browsers that support it */
	box-shadow: 10px 10px 5px #888;
	-moz-box-shadow: 10px 10px 5px #888;
	-khtml-box-shadow: 10px 10px 5px #888;
	-webkit-box-shadow: 10px 10px 5px #888;
<?php
	}
?>
}

<?php
}
if ($suf_post_bg_settings_def_cust == 'custom') {
?>
.post, div.page {
	<?php echo $css_generator->get_bg_information('suf_post_bg_settings'); ?>
}

<?php
}
if ($suf_body_font_style_setting == 'custom') {
?>
body {
	color: #<?php echo $suf_font_color;?>;
	font-family: <?php echo stripslashes($suf_body_font_family);?>;
}

a {
	color: #<?php echo $suf_link_color;?>;
	text-decoration: <?php echo $suf_link_style;?>;
}

a:visited {
	color: #<?php echo $suf_visited_link_color;?>;
	text-decoration: <?php echo $suf_visited_link_style;?>;
}

a:hover {
	color: #<?php echo $suf_link_hover_color;?>;
	text-decoration: <?php echo $suf_link_hover_style;?>;
}

<?php
}

$prefix = false;
$template_prefixes = suffusion_get_template_prefixes();
$template_sb = suffusion_get_template_sidebars();
foreach ($template_prefixes as $template => $pref) {
	$prefix = $pref;
	$sb_count = $template_sb[$template];
	$widths = $css_generator->get_widths_for_template($prefix, $sb_count, $template);
	$template_class = '.page-template-'.str_replace('.', '-', $template);
	$css_generator->print_template_specific_classes($template_class, $widths);
}

if ($suf_size_options == "custom") {
	if (isset($suf_wrapper_margin)) {
		$wrapper_margin = "50px";
		$wrapper_margin = get_size_from_field($suf_wrapper_margin, "50px");
?>
#wrapper {
	margin: <?php echo $wrapper_margin;?> auto;
}
<?php
	}

	if (isset($suf_header_height)) {
		$header_height = get_size_from_field($suf_header_height, "55px");
?>
#header {
	height: <?php echo $header_height;?>;
}
<?php
	}

	$widths = $css_generator->get_widths_for_template(false, $suf_sidebar_count);
}
else {
	// We still need to get the array of widths for the sidebars.
	$widths = $css_generator->get_automatic_widths(1000, $suf_sidebar_count, false);
}

// The default settings:
$css_generator->print_template_specific_classes('', $widths);

// For the no-sidebars.php template (uses the same widths as computed for the default settings):
?>

.page-template-no-sidebars-php .entry img {
	max-width: 99%;
}
* html .page-template-no-sidebars-php .entry img {
	w\idth: expression(this.width > (parseInt(document.getElementById('wrapper').offsetWidth) - 60) ? '96%' : true);
}
.page-template-no-sidebars-php .wp-caption {
	max-width: 99%;
}
* html .page-template-no-sidebars-php .wp-caption {
	w\idth: expression(this.offsetWidth > (parseInt(document.getElementById('wrapper').offsetWidth) - 60) ? '96%' : true);
}
.page-template-no-sidebars-php .entry .wp-caption img {
	max-width: 100%;
}
* html .page-template-no-sidebars-php .entry .wp-caption img {
	w\idth: expression(this.width > (parseInt(document.getElementById('wrapper').offsetWidth) - 60) ? '100%' : true);
}
.page-template-no-sidebars-php #container { padding-left: 0; padding-right: 0; }
<?php
// The magazine template uses the default widths, hence this is not in the CSS generator.
?>
.suf-mag-headlines {
<?php if (isset($widths['mag-headlines'])) {?>
	width: <?php echo check_integer($widths['mag-headlines']) ? $widths['mag-headlines'].'px' : $widths['mag-headlines'];?>;
<?php } ?>
<?php //if (!isset($widths['mag-headlines'])) {?>
	padding-left: <?php echo check_integer($widths['mag-headline-photos']) ? ($widths['mag-headline-photos']).'px' : $widths['mag-headline-photos'];?>;
<?php //} ?>
}

* html .suf-mag-headlines {
<?php if (isset($widths['mag-headlines'])) {?>
	w\idth: <?php echo check_integer($widths['mag-headlines']) ? ($widths['mag-headlines'] - 15).'px' : '96%';?>;
<?php } ?>
}

* html table.suf-mag-excerpts,
* html table.suf-mag-categories {
<?php if (isset($widths['mag-headlines'])) {?>
	w\idth: <?php echo check_integer($widths['mag-headlines']) ? ($widths['mag-headlines'] - 25).'px' : '96%';?>;
<?php } ?>
}

* html table.suf-tiles {
	w\idth: <?php echo check_integer($widths['main-col']) ? ($widths['main-col'] - 25).'px' : '96%';?>;
}

.suf-mag-headline-photo-box {
	width: <?php echo $widths['mag-headline-photos']; ?>px;
	right: <?php echo $widths['mag-headline-photos']; ?>px;
}

.suf-mag-headline-block {
	width: <?php echo check_integer($widths['mag-headline-block']) ? $widths['mag-headline-block'].'px' : $widths['mag-headline-block'];?>;
}

* html .suf-mag-headline-block {
	w\idth: <?php echo check_integer($widths['mag-headline-block']) ? ($widths['mag-headline-block'] - 20).'px' : $widths['mag-headline-block'];?>;
}

<?php
if ($suf_date_box_show == 'hide' || $suf_date_box_show == 'hide-search' || $suf_post_meta_position != 'corners') {
	if ($suf_date_box_show == 'hide-search' && $suf_post_meta_position == 'corners') {
		$template_class = '.search-results';
	}
	else {
		$template_class = '';
	}
?>
<?php echo $template_class;?> .post .date {
	display: none;
}

<?php echo $template_class;?> .title-container {
	padding-left: 0;
	padding-right: 0;
}

<?php echo $template_class;?> .post .title {
	padding-left: 0;
}
<?php
}
/*else if ($suf_date_box_style == 'line') {
?>
.post .title {
	padding-left: 0px;
}

.post .date {
	font-size: 10px;
	width: auto;
	height: auto;
	background-image: none;
	color: #aaaaaa;
	margin-left: 0;
	right: 0px;
	text-align: left;
	float: left;
	line-height: normal;
	display: inline-block;
}

.post .date span {
	display: inline-block;
	color: #aaaaaa;
	text-align: left;
	padding-top: 0px;
	height: auto;
}
.post .date span.year {
	display: inline-block;
	color: #aaaaaa;
	text-align: left;
	height: auto;
	padding-left: 3px;
}

.title-container {
	padding-left: 0px;
}

.post .date span.year, .post .date span.month, .post .date span.day {
	font-size: 10px;
	display: inline;
	color: #aaaaaa;
}
<?php
}*/
else if ($suf_date_box_settings_def_cust == 'custom') {
?>
.post .date {
	<?php echo $css_generator->get_bg_information('suf_date_box_settings'); ?>
}

.post .date span.day {
	<?php echo $css_generator->get_font_information('suf_date_box_dfont'); ?>
}

.post .date span.month {
	<?php echo $css_generator->get_font_information('suf_date_box_mfont'); ?>
}

.post .date span.year {
	<?php echo $css_generator->get_font_information('suf_date_box_yfont'); ?>
}
<?php
}
?>

.post-footer .category,
.postdata .category{
<?php
	if ($suf_post_show_cats == 'show-tright' || $suf_post_show_cats == 'show-bright' ) {
?>
	float: right;
<?php
	}
?>
}

.post .post-footer .comments, .post .postdata .comments {
<?php
if ($suf_post_show_comment == 'show-tleft' || $suf_post_show_comment == 'show-bleft') {
?>
	float: left;
<?php
}
else {
?>
	float: right;
<?php
}
?>
}

div.page .post-footer .comments, div.page .postdata .comments {
<?php
if ($suf_page_show_comment == 'show-tleft' || $suf_page_show_comment == 'show-bleft') {
?>
	float: left;
<?php
}
else {
?>
	float: right;
<?php
}
?>
}

.tags {
<?php
	if ($suf_post_show_tags == 'show-tleft' || $suf_post_show_tags == 'show-bleft') {
?>
	float: left;
	text-align: left;
<?php
	}
	else {
?>
	float: right;
<?php
	}
?>
}

.post span.author {
<?php
if ($suf_post_show_posted_by == 'show-tright' || $suf_post_show_posted_by == 'show-bright') {
?>
	float: right;
	padding-left: 10px;
<?php
}
else {
?>
	float: left;
	padding-right: 10px;
<?php
}
?>
}

div.page span.author {
<?php
if ($suf_page_show_posted_by == 'show-tright' || $suf_page_show_posted_by == 'show-bright') {
?>
	float: right;
	padding-left: 10px;
<?php
}
else {
?>
	float: left;
	padding-right: 10px;
<?php
}
?>
}

#commentform textarea {
	width: 90%;
}

<?php
if ($suf_header_style_setting == "custom") {
	if (($suf_header_image_type == "image" && isset($suf_header_background_image) && trim($suf_header_background_image) != '') ||
			($suf_header_image_type == "rot-image" && isset($suf_header_background_rot_folder) && trim($suf_header_background_rot_folder) != '')) {
		if ($suf_header_image_type == "image") {
			$header_bg_url = " url($suf_header_background_image) ";
		}
		else {
			$header_bg_url = " url(".suffusion_get_rotating_image($suf_header_background_rot_folder).") ";
		}
?>
#header-container {
	background-image: <?php echo $header_bg_url;?>;
	background-repeat: <?php echo $suf_header_background_repeat;?>;
	background-position: <?php echo $suf_header_background_position;?>;
	height: <?php echo $suf_header_section_height;?>;
}
<?php
	}
	else if ($suf_header_image_type == "gradient") {
        if ($suf_size_options == "custom" && isset($suf_header_height)) {
            $header_bg_url = " url(".get_template_directory_uri()."/gradient.php?start=$suf_header_gradient_start_color&finish=$suf_header_gradient_end_color&direction=$suf_header_gradient_style&height=$suf_header_height)";
        }
        else {
            $header_bg_url = " url(".get_template_directory_uri()."/gradient.php?start=$suf_header_gradient_start_color&finish=$suf_header_gradient_end_color&direction=$suf_header_gradient_style&height=121)";
        }
		if ($suf_header_gradient_style == "top-down" || $suf_header_gradient_style == "down-top") {
			$header_bg_repeat = "repeat-x";
		}
		else if ($suf_header_gradient_style == "left-right" || $suf_header_gradient_style == "right-left") {
			$header_bg_repeat = "repeat-y";
		}
		if ($suf_header_gradient_style == "top-down" || $suf_header_gradient_style == "left-right") {
			$header_bg_color = $suf_header_gradient_end_color;
		}
		else if ($suf_header_gradient_style == "down-top" || $suf_header_gradient_style == "right-left") {
			$header_bg_color = $suf_header_gradient_start_color;
		}
?>
#header-container {
	background-image: <?php echo $header_bg_url;?>;
	background-repeat: <?php echo $header_bg_repeat;?>;
	background-color: #<?php echo $header_bg_color; ?>;
}
<?php
	}
?>
div.blogtitle a {
	color: #<?php echo $suf_blog_title_color;?>;
	text-decoration: <?php echo $suf_blog_title_style;?>;
}

.blogtitle a:hover {
	color: #<?php echo $suf_blog_title_hover_color;?>;
	text-decoration: <?php echo $suf_blog_title_hover_style;?>;
}

.description {
	color: #<?php echo $suf_blog_description_color;?>;
}

<?php
	if ($suf_sub_header_vertical_alignment == "above" || $suf_sub_header_vertical_alignment == "below") {
?>
.description {
	display: block;
	width: 100%;
	margin-top: 0px;
	margin-left: 0px;
	margin-right: 0px;
}

.blogtitle {
	width: 100%;
}
<?php
	}
?>

.blogtitle {
<?php
	if ($suf_header_alignment == "right") {
?>
	float: right;
	text-align: right;
<?php
	}
	else if ($suf_header_alignment == "left") {
?>
	float: left;
	text-align: left;
<?php
	}
	else if ($suf_header_alignment == "center") {
?>
	float: none;
	margin-left: auto;
	margin-right: auto;
<?php
	}
	else if ($suf_header_alignment == "hidden") {
?>
	display: none;
	visibility: hidden;
<?php
	}
?>
}

#header {
<?php
	if ($suf_header_alignment == "center") {
?>
	text-align: center;
<?php
	}
?>
}

.description {
<?php
	if ($suf_sub_header_alignment == "right") {
?>
	float: right;
	text-align: right;
<?php
	}
	else if ($suf_sub_header_alignment == "left") {
?>
	float: left;
	text-align: left;
<?php
	}
	else if ($suf_sub_header_alignment == "center") {
?>
	float: none;
	margin-left: auto;
	margin-right: auto;
	margin-top: 0px;
<?php
	}
	else if ($suf_sub_header_alignment == "hidden") {
?>
	display: none;
	visibility: hidden;
<?php
	}
?>
}

<?php
}

// If there are header widgets then the width of the header needs to be balanced
if (!suffusion_is_sidebar_empty(12)) {
	$wih_width = get_size_from_field($suf_wih_width, "300px");
	if ($suf_header_alignment != 'right') {
?>
#header {
	float: left;
	width: auto;
}
.blogtitle, .description {
	float: none;
}
#header-widgets {
	float: right;
	width: <?php echo $wih_width; ?>;
}
<?php
	}
	else {
?>
#header {
	float: right;
	width: auto;
}
.blogtitle, .description {
	float: none;
}
#header-widgets {
	float: left;
	width: <?php echo $wih_width; ?>;
}
<?php
	}
}

?>
#nav ul {
	text-transform: <?php echo $suf_nav_text_transform; ?>;
}
#nav-top ul {
	text-transform: <?php echo $suf_navt_text_transform; ?>;
	float: <?php echo $suf_navt_dd_pos; ?>;
}
<?php

if ($suf_sidebar_header == "plain-borderless") {
?>
#sidebar .dbx-handle,
#sidebar-2 .dbx-handle {
	border-bottom: none;
}

<?php
}

if ($suf_sb_font_style_setting == "custom") {
?>
#sidebar,
#sidebar-2,
#sidebar-container {
	color:  #<?php echo $suf_sb_font_color;?>;
}

#sidebar a,
#sidebar-2 a,
#sidebar-container a {
	color:  #<?php echo $suf_sb_link_color;?>;
	text-decoration: <?php echo $suf_sb_link_style;?>;
}


#sidebar a:visited,
#sidebar-2 a:visited,
#sidebar-container a:visited {
	color:  #<?php echo $suf_sb_visited_link_color;?>;
	text-decoration: <?php echo $suf_sb_visited_link_style;?>;
}

#sidebar a:hover,
#sidebar-2 a:hover,
#sidebar-container a:hover {
	color:  #<?php echo $suf_sb_link_hover_color;?>;
	text-decoration: <?php echo $suf_sb_link_hover_style;?>;
}

<?php
}

if ($suf_wa_tbrh_style != 'tiny') {
	$tbrh_columns = intval($suf_wa_tbrh_columns);
	$tbrh_width = $css_generator->get_column_width($tbrh_columns);
	$tbrh_margin = $css_generator->get_margin($tbrh_columns);
	$tbrh_ie_margin = $css_generator->get_ie6_margin($tbrh_columns);
?>
#top-bar-right-spanel .suf-panel-widget,
#top-bar-right-spanel .suf-flat-widget,
#top-bar-right-spanel .suf-widget {
	width: <?php echo $tbrh_width;?>;
	display: inline-block;
	margin: <?php echo $tbrh_margin;?>;
}

* html #top-bar-right-spanel .suf-panel-widget,
#top-bar-right-spanel .suf-flat-widget,
#top-bar-right-spanel .suf-widget {
	ma\rgin: <?php echo $tbrh_ie_margin;?>;
}

#top-bar-right-spanel {
	background-color: #<?php echo $css_generator->strip_color_hash($suf_wa_tbrh_panel_color);?>;
	border-color: #<?php echo $css_generator->strip_color_hash($suf_wa_tbrh_panel_border_color);?>;
	color: #<?php echo $css_generator->strip_color_hash($suf_wa_tbrh_panel_font_color);?>;
}

<?php
}

if (substr($suf_wa_tbrh_panel_color, 0, 1) == '#') {
	$spanel_bg_color = substr($suf_wa_tbrh_panel_color, 1, strlen($suf_wa_tbrh_panel_color) - 1);
}
else {
	$spanel_bg_color = $suf_wa_tbrh_panel_color;
}


if ($suf_widget_area_below_header_enabled == "enabled") {
	$bw1_columns = intval($suf_widget_area_below_header_columns);
	$bw1_width = $css_generator->get_column_width($bw1_columns);
	$bw1_margin = $css_generator->get_margin($bw1_columns);
	$bw1_ie_margin = $css_generator->get_ie6_margin($bw1_columns);

    if (!($suf_wa_wabh_style == 'flattened' && $bw1_columns == 1)) {
?>
#horizontal-outer-widgets-1 .suf-horizontal-widget {
	width: <?php echo $bw1_width;?>;
	display: inline-block;
	margin: <?php echo $bw1_margin;?>;
}

* html #horizontal-outer-widgets-1 .suf-horizontal-widget {
	ma\rgin: <?php echo $bw1_ie_margin;?>;
}
<?php
    }
    else {
?>
#horizontal-outer-widgets-1 .suf-horizontal-widget {
    display: block;
    float: none;
}
<?php
    }
    if ($suf_header_for_widgets_below_header == "plain-borderless") {
?>

#horizontal-outer-widgets-1 .dbx-handle {
	border-bottom: none;
}

<?php
	}
	if ($suf_wabh_font_style_setting == "custom") {
?>
#horizontal-outer-widgets-1 {
	color:  #<?php echo $suf_wabh_font_color;?>;
}

#horizontal-outer-widgets-1 a {
	color:  #<?php echo $suf_wabh_link_color;?>;
	text-decoration: <?php echo $suf_wabh_link_style;?>;
}

#horizontal-outer-widgets-1 a:visited {
	color:  #<?php echo $suf_wabh_visited_link_color;?>;
	text-decoration: <?php echo $suf_wabh_visited_link_style;?>;
}

#horizontal-outer-widgets-1 a:hover {
	color:  #<?php echo $suf_wabh_link_hover_color;?>;
	text-decoration: <?php echo $suf_wabh_link_hover_style;?>;
}
<?php
	}
}

if ($suf_widget_area_above_footer_enabled == "enabled") {
	$bw2_columns = intval($suf_widget_area_above_footer_columns);
	$bw2_width = $css_generator->get_column_width($bw2_columns);
	$bw2_margin = $css_generator->get_margin($bw2_columns);
	$bw2_ie_margin = $css_generator->get_ie6_margin($bw2_columns);

    if (!($suf_wa_waaf_style == 'flattened' && $bw2_columns == 1)) {
?>
#horizontal-outer-widgets-2 .suf-horizontal-widget {
	width: <?php echo $bw2_width;?>;
	display: inline-block;
	margin: <?php echo $bw2_margin;?>;
}

* html #horizontal-outer-widgets-2 .suf-horizontal-widget {
	ma\rgin: <?php echo $bw2_ie_margin;?>;
}
<?php
    }
    else {
?>
#horizontal-outer-widgets-2 .suf-horizontal-widget {
    display: block;
    float: none;
}
<?php
    }
	if ($suf_header_for_widgets_above_footer == "plain-borderless") {
?>

#horizontal-outer-widgets-2 .dbx-handle {
	border-bottom: none;
}

<?php
	}
	if ($suf_waaf_font_style_setting == "custom") {
?>
#horizontal-outer-widgets-2 {
	color:  #<?php echo $suf_waaf_font_color;?>;
}

#horizontal-outer-widgets-2 a {
	color:  #<?php echo $suf_waaf_link_color;?>;
	text-decoration: <?php echo $suf_waaf_link_style;?>;
}

#horizontal-outer-widgets-2 a:visited {
	color:  #<?php echo $suf_waaf_visited_link_color;?>;
	text-decoration: <?php echo $suf_waaf_visited_link_style;?>;
}

#horizontal-outer-widgets-2 a:hover {
	color:  #<?php echo $suf_waaf_link_hover_color;?>;
	text-decoration: <?php echo $suf_waaf_link_hover_style;?>;
}
<?php
	}
}

$featured_height = get_size_from_field($suf_featured_height, "250px");
$featured_excerpt_width = get_size_from_field($suf_featured_excerpt_width, "250px");
?>
#slider, #sliderContent {
	height: <?php echo $featured_height; ?>; /* important to be same as image height */
}

#featured-posts .left, #featured-posts .right {
	height: <?php echo $featured_height; ?>;
}

.sliderImage {
	height: <?php echo $featured_height; ?>;
}

#featured-posts .left {
	width: <?php echo $featured_excerpt_width; ?> !important;
}

#featured-posts .right {
	width: <?php echo $featured_excerpt_width; ?> !important;
}

.sliderImage div {
	background-color: <?php if (substr($suf_featured_excerpt_bg_color, 0, 1) != '#') echo '#'; echo $suf_featured_excerpt_bg_color;?>;
	color: <?php if (substr($suf_featured_excerpt_font_color, 0, 1) != '#') echo '#'; echo $suf_featured_excerpt_font_color;?>;
}

.sliderImage div a {
	color: <?php if (substr($suf_featured_excerpt_link_color, 0, 1) != '#') echo '#'; echo $suf_featured_excerpt_link_color;?>;
}

<?php
if ($suf_featured_show_border == "show") {
?>
#featured-posts {
	border-width: 1px;
	border-style: solid;
}
<?php
}

if ($suf_emphasis_customization == 'custom') {
?>

.download {
	color: #<?php echo $suf_download_font_color;?>;
	background-color: #<?php echo $suf_download_background_color;?>;
	border-color: #<?php echo $suf_download_border_color;?>;
}

.announcement {
	color: #<?php echo $suf_announcement_font_color;?>;
	background-color: #<?php echo $suf_announcement_background_color;?>;
	border-color: #<?php echo $suf_announcement_border_color;?>;
}

.note {
	color: #<?php echo $suf_note_font_color;?>;
	background-color: #<?php echo $suf_note_background_color;?>;
	border-color: #<?php echo $suf_note_border_color;?>;
}

.warning {
	color: #<?php echo $suf_warning_font_color;?>;
	background-color: #<?php echo $suf_warning_background_color;?>;
	border-color: #<?php echo $suf_warning_border_color;?>;
}

<?php
}
?>
.suf-mag-headlines {
	height: <?php echo get_size_from_field($suf_mag_headlines_height, "250px"); ?>;
}

col.suf-mag-excerpt {
<?php
$mag_excerpt_td_width = floor(100/(int)$suf_mag_excerpts_per_row);
?>
	width: <?php echo $mag_excerpt_td_width; ?>%;
}

.suf-mag-excerpt-image {
<?php
if (check_integer($widths['main-col'])) {
	$mag_excerpt_td_img_width = floor($widths['main-col']/(int)$suf_mag_excerpts_per_row) - 20;
}
else {
	$mag_excerpt_td_img_width = '98%';
}
?>
	width: <?php echo check_integer($mag_excerpt_td_img_width) ? $mag_excerpt_td_img_width.'px' : $mag_excerpt_td_img_width;?>;
	height: <?php echo get_size_from_field($suf_mag_excerpts_image_box_height, "100px"); ?>;
}

* html .suf-mag-excerpt-image {
<?php
if (check_integer($widths['main-col'])) {
	$mag_excerpt_td_img_width = floor($widths['main-col']/(int)$suf_mag_excerpts_per_row) - 20 - (int)$suf_mag_excerpts_per_row;
}
else {
	$mag_excerpt_td_img_width = '95%';
}
?>
	w\idth: <?php echo check_integer($mag_excerpt_td_img_width) ? $mag_excerpt_td_img_width.'px' : $mag_excerpt_td_img_width;?>;
}

col.suf-mag-category {
<?php
$mag_category_td_width = floor(100/(int)$suf_mag_catblocks_per_row);
?>
	width: <?php echo $mag_category_td_width; ?>%;
}

.suf-mag-category-image {
<?php
if (check_integer($widths['main-col'])) {
	$mag_category_td_img_width = floor($widths['main-col']/(int)$suf_mag_catblocks_per_row) - 20;
}
else {
	$mag_category_td_img_width = '98%';
}
?>
	width: <?php echo $mag_category_td_img_width; ?>px;
	height: <?php echo get_size_from_field($suf_mag_catblocks_image_box_height, "100px"); ?>;
}

* html .suf-mag-category-image {
<?php
if (check_integer($widths['main-col'])) {
	$mag_category_td_img_width = floor($widths['main-col']/(int)$suf_mag_catblocks_per_row) - 20 - (int)$suf_mag_catblocks_per_row;
}
else {
	$mag_category_td_img_width = '95%';
}
?>
	w\idth: <?php echo $mag_category_td_img_width; ?>px;
}

h2.suf-mag-category-title {
	text-align: <?php echo $suf_mag_catblocks_title_alignment; ?>;
}

.suf-mag-categories th {
	text-align: <?php echo $suf_mag_catblocks_main_title_alignment; ?>;
}

.suf-mag-excerpts th {
	text-align: <?php echo $suf_mag_excerpts_main_title_alignment; ?>;
}

h2.suf-mag-excerpt-title {
	text-align: <?php echo $suf_mag_excerpt_title_alignment; ?>;
}

h2.suf-mag-headlines-title {
	text-align: <?php echo $suf_mag_headline_main_title_alignment; ?>;
}

.author-info img.avatar {
    float: <?php echo $suf_uprof_post_info_gravatar_alignment;?>;
    padding: 5px;
}

<?php
if ($suf_navt_bar_style == 'full-full') {
?>
#nav-top .col-control,
#top-bar-right-spanel .col-control {
	width: auto;
}
#nav-top {
	border-radius: 0;
	-moz-border-radius: 0;
	-webkit-border-radius: 0;
	-khtml-border-radius: 0;
}
<?php
}
else if ($suf_navt_bar_style == 'full-align') {
?>
#nav-top,
#top-bar-right-spanel {
	width: auto;
}

#nav-top {
	border-radius: 0;
	-moz-border-radius: 0;
	-webkit-border-radius: 0;
	-khtml-border-radius: 0;
}
<?php
}
else if ($suf_navt_bar_style == 'align') {
?>
#nav-top,
#top-bar-right-spanel {
	margin: 0 auto;
}

<?php
}

if ($suf_wah_layout_style == 'full-full') {
?>
#widgets-above-header .col-control {
	width: auto;
}
<?php
}
else if ($suf_wah_layout_style == 'full-align') {
?>
#widgets-above-header {
	width: auto;
}
<?php
}
else if ($suf_wah_layout_style == 'align') {
?>
#widgets-above-header {
	margin: 0 auto;
}

<?php
}

$wah_columns = intval($suf_wah_columns);
$wah_width = $css_generator->get_column_width($wah_columns);
$wah_margin = $css_generator->get_margin($wah_columns);
$wah_ie_margin = $css_generator->get_ie6_margin($wah_columns);
?>
#widgets-above-header .suf-widget {
	width: <?php echo $wah_width;?>;
	display: inline-block;
	margin: <?php echo $wah_margin;?>;
}

* html #widgets-above-header .suf-widget {
	ma\rgin: <?php echo $wah_ie_margin;?>;
}

<?php
for ($i=1; $i<=5; $i++) {
	$adhoc_column_option = 'suf_adhoc'.$i.'_columns';
	$adhoc_columns = intval($$adhoc_column_option);
	$adhoc_width = $css_generator->get_column_width($adhoc_columns);
	$adhoc_margin = $css_generator->get_margin($adhoc_columns);
	$adhoc_ie_margin = $css_generator->get_ie6_margin($adhoc_columns);
?>
#ad-hoc-<?php echo $i; ?> .suf-widget {
	width: <?php echo $adhoc_width;?>;
	display: inline-block;
	margin: <?php echo $adhoc_margin;?>;
}

* html #ad-hoc-<?php echo $i; ?> .suf-widget {
	ma\rgin: <?php echo $adhoc_ie_margin;?>;
}
<?php
}

if ($suf_footer_layout_style != 'in-align') {
	if ($suf_footer_layout_style == 'out-hcfull') {
?>
#page-footer .col-control {
	width: auto;
}
<?php
	}
	else if ($suf_footer_layout_style == 'out-cfull-halign') {
		?>
#page-footer {
	width: auto;
}
		<?php
	}
	else if ($suf_footer_layout_style == 'out-hcalign') {
		?>
#page-footer {
	margin: 0 auto;
	padding: 0 10px;
}
		<?php
	}
}

if ($suf_header_layout_style != 'in-align') {
	if ($suf_header_layout_style == 'out-hcfull') {
?>
#header-container .col-control {
	width: auto;
}
<?php
	}
	else if ($suf_header_layout_style == 'out-cfull-halign') {
?>
#header-container {
	width: auto;
}
<?php
	}
	else if ($suf_header_layout_style == 'out-hcalign') {
?>
#header-container {
	margin: 0 auto;
	padding: 0 10px;
}

<?php
	}

	if ($suf_nav_bar_style == 'full-full') {
?>
#nav .col-control {
	width: auto;
	}
<?php
	}
	else if ($suf_nav_bar_style == 'full-align') {
?>
#nav {
	width: auto;
}
<?php
	}
	else if ($suf_nav_bar_style == 'align') {
?>
#nav {
	margin: 0 auto;
}
<?php
	}
}
else {
?>
#nav {
	margin: 0 auto;
	width: 100%;
<?php
	if (isset($suffusion_rtl_layout) && $suffusion_rtl_layout) {
?>
	float: right;
<?php
	}
?>
}
<?php
}
?>

col.suf-tile {
<?php
global $wp_query;
$number_of_cols = count($wp_query->posts) - suffusion_get_full_content_count();
if ($number_of_cols > (int)$suf_tile_excerpts_per_row || $number_of_cols <= 0) {
	$number_of_cols = (int)$suf_tile_excerpts_per_row;
}
//$tile_td_width = floor(100/(int)$suf_tile_excerpts_per_row);
$tile_td_width = floor(100/$number_of_cols);
?>
	width: <?php echo $tile_td_width; ?>%;
}

.suf-tile-image {
<?php
if (check_integer($widths['main-col'])) {
	$tile_td_img_width = floor($widths['main-col']/(int)$number_of_cols) - 20;
}
else {
	$tile_td_img_width = '95%';
}
//$tile_td_img_width = floor($widths['main-col']/$number_of_cols) - 20;
?>
	width: <?php if (check_integer($tile_td_img_width)) { echo $tile_td_img_width.'px'; } else { echo $tile_td_img_width; } ?>;
	height: <?php echo get_size_from_field($suf_tile_image_box_height, "100px"); ?>;
}

* html .suf-tile-image {
<?php
if (check_integer($widths['main-col'])) {
	$tile_td_img_width = floor($widths['main-col']/(int)$number_of_cols) - 20 - (int)$number_of_cols;
}
else {
	$tile_td_img_width = '95%';
}
//$tile_td_img_width = floor($widths['main-col']/$number_of_cols) - 20 - $number_of_cols;
?>
	w\idth: <?php if (check_integer($tile_td_img_width)) { echo $tile_td_img_width.'px'; } else { echo $tile_td_img_width; } ?>;
}

h2.suf-tile-title {
	text-align: <?php echo $suf_tile_title_alignment; ?>;
}

div.booklisting img,
div.bookentry img {
	width: <?php echo get_size_from_field($suf_nr_main_cover_w, "108px"); ?>;
	height: <?php echo get_size_from_field($suf_nr_main_cover_h, "160px"); ?>;
}

div.bookentry .stats {
	width: <?php echo (get_numeric_size_from_field($suf_nr_main_cover_w, 108) + 34); ?>px;
}

div.bookentry .review {
	width: <?php echo ($widths['main-col'] - get_numeric_size_from_field($suf_nr_main_cover_w, 108) - 80); ?>px;
}

* html div.bookentry .review {
	w\idth: <?php echo ($widths['main-col'] - get_numeric_size_from_field($suf_nr_main_cover_w, 108) - 100); ?>px;
}

col.nr-shelf-slot {
<?php
$books_per_row = (int)$suf_nr_books_per_row;
$slot_width = floor(100/$suf_nr_books_per_row);
?>
	width: <?php echo $slot_width; ?>%;
}

<?php
if ($suf_nav_skin_def_cust == 'custom') {
	$css_generator->print_navigation_bar('nav');
}

if ($suf_navt_skin_def_cust == 'custom') {
	$css_generator->print_navigation_bar('nav-top');
}

if ($suf_post_meta_position != 'corners') {
	if ($suf_post_meta_position == 'left-pullout') {
?>
div.post .entry-container {
	padding-left: 150px;
}
<?php
	}
	else {
?>
div.post .entry-container {
	padding-right: 150px;
}
<?php
	}
?>
div.post .entry {
	width: 100%;
	float: left;
}
<?php
}
if ($suf_page_meta_position != 'corners') {
	if ($suf_page_meta_position == 'left-pullout') {
?>
div.page .entry-container {
	padding-left: 150px;
}
<?php
	}
	else {
?>
div.page .entry-container {
	padding-right: 150px;
}
<?php
	}
?>
div.page .entry {
	width: 100%;
	float: left;
}
<?php
}
?>
.attachment object.audio { width: <?php echo $suf_audio_att_player_width; ?>px; height: <?php echo $suf_audio_att_player_height; ?>px; }
.attachment object.application { width: <?php echo $suf_application_att_player_width; ?>px; }
.attachment object.text { width: <?php echo $suf_text_att_player_width; ?>px; }
.attachment object.video { width: <?php echo $suf_video_att_player_width; ?>px; height: <?php echo $suf_video_att_player_height; ?>px; }
.sidebar-container-left #sidebar-shell-1 { float: left; margin-left: 0; margin-right: 15px; right: auto; }
.sidebar-container-left #sidebar-shell-2 { float: left; margin-left: 0; margin-right: 15px; right: auto;}
.sidebar-container-right #sidebar-shell-1 { float: right; margin-right: 0; margin-left: 15px; right: auto; left: auto; }
.sidebar-container-right #sidebar-shell-2 { float: right; margin-right: 0; margin-left: 15px; right: auto; left: auto;}
.sidebar-wrap-right #sidebar-shell-1 { float: right; margin-left: 0; margin-right: 0;}
.sidebar-wrap-right #sidebar-shell-2 { float: right; margin-right: 15px; margin-left: 0;}
.sidebar-wrap-left #sidebar-shell-1 { float: left; margin-left: 0; margin-right: 0;}
.sidebar-wrap-left #sidebar-shell-2 { float: left; margin-left: 15px; margin-right: 0;}
.sidebar-container-left #sidebar-wrap { margin-left: 0; margin-right: 0; left: auto; right: auto; }
.sidebar-container-right #sidebar-wrap { margin-left: 0; margin-right: 0; left: auto; right: auto; }
#sidebar-container .tab-box { margin-left: 0; margin-right: 0; }
#sidebar-container.sidebar-container-left { margin-left: -100%; }
.sidebar-container-left .tab-box { float: left; }
.sidebar-container-right .tab-box { float: right; }
* html #sidebar-container #sidebar-shell-1, * html #sidebar-container #sidebar-shell-2 { lef\t: auto; r\ight: auto; }
* html .sidebar-container-left #sidebar-wrap, * html .sidebar-container-right #sidebar-wrap { lef\t: auto; r\ight: auto; }
