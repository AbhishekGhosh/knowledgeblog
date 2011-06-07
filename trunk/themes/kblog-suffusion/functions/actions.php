<?php
/**
 * Contains a list of all custom action hooks and corresponding functions defined for Suffusion.
 * This file is included in functions.php
 *
 * @package Suffusion
 * @subpackage Functions
 */


// First we will get all options from the database, then we will individually invoke the options within each function as required.
global $suffusion_unified_options;
foreach ($suffusion_unified_options as $id => $value) {
	$$id = $value;
}

include(TEMPLATEPATH . "/functions/knowledgeblog.php");

// Fiddle with some standard hooks
remove_action('wp_head', 'wp_generator');
add_action('wp_head', 'suffusion_include_custom_styles', 11);
add_action('init', 'suffusion_register_jquery');
add_action('init', 'suffusion_register_custom_types');
add_action('init', 'suffusion_register_menus'); // Weird, but apparently required for approval of the theme.
add_action('init', 'suffusion_check_custom_css');

// Required for WP-MS, 3.0+
add_action('before_signup_form', 'suffusion_pad_signup_form_start');
add_action('after_signup_form', 'suffusion_pad_signup_form_end');

//
// The following section defines different hooks with actions

add_action('wp_print_styles', 'suffusion_disable_plugin_styles');

add_action('suffusion_document_header', 'suffusion_set_title');
add_action('suffusion_document_header', 'suffusion_include_meta');
add_action('suffusion_document_header', 'suffusion_include_favicon');
add_action('suffusion_document_header', 'suffusion_include_default_feed');
add_action('suffusion_document_header', 'suffusion_include_skin');
add_action('suffusion_document_header', 'suffusion_include_bp_admin_css');
add_action('suffusion_document_header', 'suffusion_include_ie_fixes');
add_action('suffusion_document_header', 'suffusion_include_dbx');
add_action('suffusion_document_header', 'suffusion_include_js');
add_action('suffusion_document_header', 'suffusion_include_custom_header_js');

add_action('suffusion_before_page', 'suffusion_js_initializer');

add_action('suffusion_before_begin_wrapper', 'suffusion_display_open_header');

add_action('suffusion_after_begin_wrapper', 'suffusion_display_closed_header');
add_action('suffusion_after_begin_wrapper', 'suffusion_print_widget_area_below_header');

add_action('suffusion_page_header', 'suffusion_display_header');
add_action('suffusion_page_header', 'suffusion_display_main_navigation');

add_action('suffusion_page_navigation', 'suffusion_display_hierarchical_navigation');
add_action('suffusion_page_navigation', 'suffusion_create_navigation_breadcrumb');

add_action('suffusion_before_begin_content', 'suffusion_featured_posts');
add_action('suffusion_after_begin_content', 'suffusion_template_specific_header');

add_action('suffusion_content', 'suffusion_excerpt_or_content');

add_action('suffusion_after_begin_post', 'suffusion_print_post_page_title');

add_action('suffusion_after_content', 'suffusion_meta_pullout');

add_action('suffusion_before_end_post', 'suffusion_author_information');
add_action('suffusion_before_end_post', 'suffusion_post_footer');

add_action('suffusion_before_end_content', 'suffusion_pagination');

// CSS Holy Grail changes...
add_action('suffusion_before_end_container', 'suffusion_print_left_sidebars');
add_action('suffusion_before_end_container', 'suffusion_print_right_sidebars');

add_action('suffusion_after_end_container', 'suffusion_print_widget_area_above_footer');

add_action('suffusion_page_footer', 'suffusion_display_footer');

add_action('suffusion_document_footer', 'suffusion_include_custom_footer_js');

/*
 * The following section says what to do for each action
 */
function suffusion_document_header() {
	do_action('suffusion_document_header');
}

function suffusion_before_page() {
	do_action('suffusion_before_page');
}

function suffusion_before_begin_wrapper() {
	do_action('suffusion_before_begin_wrapper');
}

function suffusion_after_begin_wrapper() {
	do_action('suffusion_after_begin_wrapper');
}

function suffusion_page_header() {
	do_action('suffusion_page_header');
}

function suffusion_after_begin_container() {
	do_action('suffusion_after_begin_container');
}

function suffusion_before_begin_content() {
	do_action('suffusion_before_begin_content');
}

function suffusion_after_begin_content() {
	do_action('suffusion_after_begin_content');
}

function suffusion_content() {
	do_action('suffusion_content');
}

function suffusion_after_begin_post() {
	do_action('suffusion_after_begin_post');
}

function suffusion_after_content() {
	do_action('suffusion_after_content');
}

function suffusion_before_end_post() {
	do_action('suffusion_before_end_post');
}

function suffusion_before_end_content() {
	do_action('suffusion_before_end_content');
}

function suffusion_before_end_container() {
	do_action('suffusion_before_end_container');
}

function suffusion_after_end_container() {
	do_action('suffusion_after_end_container');
}

function suffusion_page_footer() {
	do_action('suffusion_page_footer');
}

function suffusion_document_footer() {
	do_action('suffusion_document_footer');
}

function suffusion_page_navigation() {
	do_action('suffusion_page_navigation');
}

function suffusion_query_posts() {
	do_action('suffusion_query_posts');
}

function suffusion_before_first_sidebar() {
	do_action('suffusion_before_first_sidebar');
}

function suffusion_between_first_sidebars() {
	do_action('suffusion_between_first_sidebars');
}

function suffusion_after_first_sidebar() {
	do_action('suffusion_after_first_sidebar');
}

function suffusion_before_second_sidebar() {
	do_action('suffusion_before_second_sidebar');
}

function suffusion_between_second_sidebars() {
	do_action('suffusion_between_second_sidebars');
}

function suffusion_after_second_sidebar() {
	do_action('suffusion_after_second_sidebar');
}

function suffusion_before_comment() {
	do_action('suffusion_before_comment');
}

function suffusion_after_comment() {
	do_action('suffusion_after_comment');
}

//
// This section defines the individual callback functions
//

function suffusion_include_skin() {
	global $suf_color_scheme, $suf_css_gzip_enabled, $suf_style_inheritance, $suffusion_theme_hierarchy, $suf_show_rounded_corners, $suf_autogen_css, $suffusion_autogen_included;
	$suffusion_autogen_included = false;
	$skin = "dark-green.css";
	if (substr($suf_color_scheme, 0, 10) == "dark-theme") {
		$skin = "dark-".substr($suf_color_scheme, 11).".css";
	}
	else {
		$skin = substr($suf_color_scheme, 12).".css";
	}

	$sheets = $suffusion_theme_hierarchy[$suf_color_scheme];
	if ($suf_css_gzip_enabled != 'disabled') {
		$child = "";
		if (STYLESHEETPATH != TEMPLATEPATH) {
			$child = "&amp;sdir=".STYLESHEETPATH;
		}
		$theme = get_current_theme(); // Need this because a child theme might be getting used.
		$theme_data = get_theme($theme);
		$theme_version = "&amp;ver=".$theme_data['Version'];
		if ($suf_style_inheritance == 'nothing' && STYLESHEETPATH != TEMPLATEPATH) {
			$stylesheets = "style.css";
		}
		else {
			$stylesheets = implode(',',$sheets);
		}
		if ($suf_autogen_css == 'autogen') {
			$autogen_path = suffusion_get_custom_css_relative_location();
			$autogen_exists = suffusion_create_or_update_custom_css();
			if ($autogen_path != false && $autogen_exists) {
				$stylesheets .= ",".$autogen_path;
				$suffusion_autogen_included = true;
			}
		}
?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.php?skin=<?php echo $stylesheets;?>&amp;comp=<?php echo $suf_css_gzip_enabled;?><?php echo $child;?><?php echo $theme_version;?>" type="text/css" media="all"/>
<?php
		if ($suf_show_rounded_corners == 'show') {
?>
	<!--[if !IE]>--><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/rounded-corners.php?comp=<?php echo $suf_css_gzip_enabled;?><?php echo $theme_version;?>" type="text/css" media="all"/><!--<![endif]-->
<?php
		}
	}
	else {
		if ($suf_style_inheritance == 'nothing' && STYLESHEETPATH != TEMPLATEPATH) {
		?>
			<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" type="text/css" media="all"/>
		<?php
		}
		else {
			foreach ($sheets as $sheet) {
				if (file_exists(TEMPLATEPATH."/$sheet")) {
				?>
					<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/<?php echo $sheet; ?>" type="text/css" media="all"/>
				<?php
				}
			}
			if (STYLESHEETPATH != TEMPLATEPATH) {
		?>
				<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" type="text/css" media="all"/>
		<?php
			}
		}
		if ($suf_show_rounded_corners == 'show') {
?>
	<!--[if !IE]>--><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/rounded-corners.css" type="text/css" media="all"/><!--<![endif]-->
<?php
		}
	}
}

function suffusion_include_dbx() {
	global $suf_sidebar_1_dnd, $suf_sidebar_2_dnd, $suf_sidebar_1_expcoll, $suf_sidebar_2_expcoll, $suf_sidebar_count, $suf_wa_sb2_style;
	if (suffusion_should_include_dbx()) {
		$expcoll_1 = $suf_sidebar_1_expcoll == "enabled" ? "yes" : "no";
		$expcoll_2 = $suf_sidebar_2_expcoll == "enabled" ? "yes" : "no";
?>
	<!-- Sidebar docking boxes (dbx) by Brothercake - http://www.brothercake.com/ -->
	<script type="text/javascript">
	/* <![CDATA[ */
	window.onload = function() {
		//initialise the docking boxes manager
		var manager = new dbxManager('main'); 	//session ID [/-_a-zA-Z0-9/]

<?php
		if ($suf_sidebar_1_dnd == "enabled") {?>
		//create new docking boxes group
		var sidebar = new dbxGroup(
			'sidebar', 		// container ID [/-_a-zA-Z0-9/]
			'vertical', 		// orientation ['vertical'|'horizontal']
			'7', 			// drag threshold ['n' pixels]
			'no',			// restrict drag movement to container axis ['yes'|'no']
			'10', 			// animate re-ordering [frames per transition, or '0' for no effect]
			'<?php echo $expcoll_1; ?>', 			// include open/close toggle buttons ['yes'|'no']
			'open', 		// default state ['open'|'closed']
			'open', 		// word for "open", as in "open this box"
			'close', 		// word for "close", as in "close this box"
			'click-down and drag to move this box', // sentence for "move this box" by mouse
			'click to %toggle% this box', // pattern-match sentence for "(open|close) this box" by mouse
			'use the arrow keys to move this box', // sentence for "move this box" by keyboard
			', or press the enter key to %toggle% it',  // pattern-match sentence-fragment for "(open|close) this box" by keyboard
			'%mytitle%  [%dbxtitle%]' // pattern-match syntax for title-attribute conflicts
		);
<?php
		}
		if (($suf_sidebar_count > 1 && $suf_sidebar_2_dnd == "enabled" && $suf_wa_sb2_style == "boxed" && !(is_page_template('1l-sidebar.php') || is_page_template('1r-sidebar.php'))) ||
				($suf_sidebar_2_dnd == "enabled" && $suf_wa_sb2_style == "boxed" && (is_page_template('1l1r-sidebar.php') || is_page_template('2l-sidebars.php') || is_page_template('2r-sidebars.php')))) {
?>
		var sidebar_2 = new dbxGroup(
			'sidebar-2', 		// container ID [/-_a-zA-Z0-9/]
			'vertical', 		// orientation ['vertical'|'horizontal']
			'7', 			// drag threshold ['n' pixels]
			'no',			// restrict drag movement to container axis ['yes'|'no']
			'10', 			// animate re-ordering [frames per transition, or '0' for no effect]
			'<?php echo $expcoll_2; ?>', 			// include open/close toggle buttons ['yes'|'no']
			'open', 		// default state ['open'|'closed']
			'open', 		// word for "open", as in "open this box"
			'close', 		// word for "close", as in "close this box"
			'click-down and drag to move this box', // sentence for "move this box" by mouse
			'click to %toggle% this box', // pattern-match sentence for "(open|close) this box" by mouse
			'use the arrow keys to move this box', // sentence for "move this box" by keyboard
			', or press the enter key to %toggle% it',  // pattern-match sentence-fragment for "(open|close) this box" by keyboard
			'%mytitle%  [%dbxtitle%]' // pattern-match syntax for title-attribute conflicts
		);
<?php
		}
		?>
	};
	/* ]]> */
	</script>

<?php
	}
}

function suffusion_include_ie_fixes() {?>
<!--[if lt IE 8]>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/ie-fix.css" type="text/css" media="all" />
<![endif]-->

<!--[if lt IE 7]>
<script src="<?php echo get_template_directory_uri(); ?>/belatedpng.js"></script>
<script>
	//Drew Diller's Belated PNG: http://dillerdesign.wordpress.com/2009/07/02/belatedpng-img-nodes-javascript-event-handling/
  	DD_belatedPNG.fix('img, .suf-widget ul li, #sidebar ul li, #sidebar-2 ul li, .sidebar-tab-content ul li, li.suf-mag-catblock-post, input, .searchform .searchsubmit, #right-header-widgets .searchsubmit, #left-header-widgets .searchsubmit, #top-bar-left-widgets .searchsubmit,  #top-bar-right-widgets .searchsubmit, submit, .searchsubmit, .postdata .category, .postdata .comments, .postdata .edit, .previous-entries a, .next-entries a, .post-nav .next a, .post-nav .previous a, .post .date, h3#comments, h3.comments, #h3#respond, h3.respond, blockquote, blockquote div');
 </script>
<![endif]-->
<?php
}

function suffusion_include_custom_styles() {
	global $suf_custom_css_link_1, $suf_custom_css_link_2, $suf_custom_css_link_3, $suf_custom_css_code, $suffusion_rtl_layout, $suf_autogen_css, $suffusion_autogen_included;
	if (($rtl = get_locale_stylesheet_uri()) != '') {
		$suffusion_rtl_layout = true;
?>
	<!-- Hack for navigation bar issues in RTL layout in IE6/IE7 -->
	<!--[if lt IE 8]>
	<style type="text/css">
		#nav ul {
			float: left;
		}
	</style>
	<![endif]-->
<?php
	}
	$autogen_css = false;
	if ($suf_autogen_css == 'autogen') {
		$autogen_css = suffusion_get_custom_css_url();
	}
	if ($autogen_css != false && !$suffusion_autogen_included) {
?>
<link rel="stylesheet" href="<?php echo $autogen_css; ?>" type="text/css" media="all" />
<?php
	}
	else if (!$suffusion_autogen_included || $suf_autogen_css == 'nogen') {
?>

	<!-- CSS styles constructed using option definitions -->
	<style type="text/css">
	<!--/*--><![CDATA[/*><!--*/
<?php
	include_once (TEMPLATEPATH . '/custom-styles.php');
?>
	/*]]>*/-->
	</style>
	<!-- /CSS styles constructed using option definitions -->
<?php
	}
	if (is_attachment()) {
?>
	<!-- Attachment page options, only displayed in an attachment page -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri()."/attachment-styles.css"; ?>" type="text/css" media="all" />
	<!-- /Attachment page options -->
<?php
	}
?>
	<!-- Custom CSS stylesheets linked through options -->
<?php
	if (isset($suf_custom_css_link_1) && trim($suf_custom_css_link_1) != "") { ?>
	<link rel="stylesheet" href="<?php echo $suf_custom_css_link_1; ?>" type="text/css" media="all" />
<?php
	}
	if (isset($suf_custom_css_link_2) && trim($suf_custom_css_link_2) != "") {?>
	<link rel="stylesheet" href="<?php echo $suf_custom_css_link_2; ?>" type="text/css" media="all" />
<?php
	}
	if (isset($suf_custom_css_link_3) && trim($suf_custom_css_link_3) != "") {?>
	<link rel="stylesheet" href="<?php echo $suf_custom_css_link_3; ?>" type="text/css" media="all" />
<?php }?>
	<!-- /Custom CSS stylesheets -->

<?php
if (isset($suf_custom_css_code) && trim($suf_custom_css_code) != "") { ?>
	<!-- Custom CSS styles defined in options -->
	<style type="text/css">
<?php echo stripslashes($suf_custom_css_code); ?>
	</style>
	<!-- /Custom CSS styles defined in options -->
<?php }
}

function suffusion_include_custom_header_js() {
	global $suf_custom_header_js;
	if (isset($suf_custom_header_js) && trim($suf_custom_header_js) != "") {?>
<!-- Custom JavaScript for header defined in options -->
<script type="text/javascript">
/* <![CDATA[ */
<?php echo stripslashes($suf_custom_header_js)."\n"; ?>
/* ]]> */
</script>
<!-- /Custom JavaScript for header defined in options -->
<?php
	}
}

function suffusion_display_header() {
	global $suf_sub_header_vertical_alignment, $suf_header_fg_image_type, $suf_header_fg_image, $suf_header_alignment;
	if ($suf_header_alignment == 'right') {
		suffusion_display_widgets_in_header();
	}
?>
    <div id="header" class="fix">
	<?php
	$header = ($suf_header_fg_image_type == 'image' && trim($suf_header_fg_image) != '') ? "<img src='$suf_header_fg_image' alt='".esc_attr(get_bloginfo('name'))."'/>" : get_bloginfo('name', 'display');
	$home_link = get_option("home");
	if (function_exists('icl_get_home_url')) {
		$home_link = icl_get_home_url();
	}
	if ($suf_sub_header_vertical_alignment == "above") {
		?>
   		<div class="description"><?php bloginfo('description');?></div>
   		<div class="blogtitle"><a href="<?php echo $home_link;?>"><?php echo $header;?></a></div>
	<?php
	}
	else {
		?>
   		<div class="blogtitle"><a href="<?php echo $home_link;?>"><?php echo $header;?></a></div>
   		<div class="description"><?php bloginfo('description');?></div>
<?php
	}
	?>
    </div><!-- /header -->
<?php
	if ($suf_header_alignment != 'right') {
		suffusion_display_widgets_in_header();
	}
}

function suffusion_display_main_navigation() {
	global $suf_nav_contents, $suf_nav_item_type;
	$display = apply_filters('suffusion_can_display_main_navigation', true);
	if ($display) {
?>
 	<div id="nav" class="<?php echo $suf_nav_item_type; ?> fix">
		<div class='col-control'>
<?php
	suffusion_display_left_header_widgets();
	//Two options using native WP functionality:
	//1. wp_list_pages('title_li=&sort_column=menu_order&depth=3'); // This will need you to add the starting and ending <ul> tags
	//2. wp_page_menu('show_home=Home&menu_class=nav'); // This needs nothing and even creates the div. Works only for WP 2.7+
	//I am using a custom function here because I want to show the ">" for items with a dropdown. Also, page exclusions don't work as desired with standard functionality.
	if ($suf_nav_contents == "pages") {
		create_nav(true, $suf_nav_contents, 'main', 'suf_nav_pages', 'suf_nav_cats', 'suf_nav_links', 'suf_nav_menus');
	}
	suffusion_display_right_header_widgets();
?>
		</div><!-- /col-control -->
	</div><!-- /nav -->
<?php
	}
}

function suffusion_display_top_navigation() {
	global $suf_navt_contents, $suf_wa_tbrh_style, $suf_wa_tbrh_open_text, $suf_wa_tbrh_close_text, $suf_navt_item_type;
	if ($suf_navt_contents == "pages" || (function_exists('has_nav_menu') && has_nav_menu('top'))) {
		if (!suffusion_is_sidebar_empty(7)) {
			if ($suf_wa_tbrh_style == 'sliding-panel' || $suf_wa_tbrh_style == 'spanel-flat' || $suf_wa_tbrh_style == 'spanel-boxed') {
				$display = apply_filters('suffusion_can_display_sliding_panel', true);
				if ($display) {
?>
	<!-- #top-bar-right-spanel -->
	<div id="top-bar-right-spanel" class='warea fix'>
		<div class='col-control'>
			<div class='spanel'>
				<div class='spanel-content fix'>
<?php
					dynamic_sidebar('Top Bar Right Widgets');
?>
				</div>
			</div>
		</div>
	</div>
	<!-- #top-bar-right-spanel -->
<?php
				}
			}
		}
		$display = apply_filters('suffusion_can_display_top_navigation', true);
		if ($display) {
?>
	<div id='nav-top' class='<?php echo $suf_navt_item_type; ?> fix'>
		<div class='col-control'>
<?php
		if (!suffusion_is_sidebar_empty(6)) {
?>
			<!-- #top-bar-left-widgets -->
			<div id="top-bar-left-widgets" class="warea">
<?php
			dynamic_sidebar('Top Bar Left Widgets');
?>
			</div>
			<!-- /#top-bar-left-widgets -->
<?php
		}
		if (!suffusion_is_sidebar_empty(7)) {
			if ($suf_wa_tbrh_style == 'sliding-panel' || $suf_wa_tbrh_style == 'spanel-flat' || $suf_wa_tbrh_style == 'spanel-boxed') {
?>
		<div id="top-bar-right-spanel-tab">
			<div class="toggle">
				<a class="open" href="#"><?php echo $suf_wa_tbrh_open_text; ?></a>
				<a style="display: none;" class="close" href="#"><?php echo $suf_wa_tbrh_close_text; ?></a>
			</div>
		</div> <!-- /#top-bar-right-spanel-tab -->
<?php
			}
			else {
?>
		<!-- #top-bar-right-widgets -->
		<div id="top-bar-right-widgets" class="warea">
<?php
				dynamic_sidebar('Top Bar Right Widgets');
?>
		</div>
		<!-- /#top-bar-right-widgets -->
<?php
			}
		}
		create_nav(true, "pages", 'top', 'suf_navt_pages', 'suf_navt_cats', 'suf_navt_links', 'suf_navt_menus');
?>
		</div><!-- /.col-control -->
	</div><!-- /#nav-top -->
<?php
		}
	}
}

function suffusion_display_left_header_widgets() {
	if (!suffusion_is_sidebar_empty('8')) {?>
		<!-- left-header-widgets -->
		<div id="left-header-widgets" class='warea fix'>
		<?php
			dynamic_sidebar('Left Header Widgets');
		?>
		</div>
		<!-- /left-header-widgets -->
<?php
	}
}

function suffusion_display_right_header_widgets() {
	global $suf_show_search;
	if ($suf_show_search == "show" || !suffusion_is_sidebar_empty(3)) {?>
		<!-- right-header-widgets -->
		<div id="right-header-widgets" class="warea">
		<?php
			if (!dynamic_sidebar('Right Header Widgets')) {
				if ($suf_show_search == "show") {
					get_search_form();
				}
			}
		?>
		</div>
		<!-- /right-header-widgets -->
<?php
	}
}

/*
 * Displays the widget area below the header, if it is enabled.
 */
function suffusion_print_widget_area_below_header() {
	global $suf_widget_area_below_header_enabled, $suf_ns_wabh_enabled, $suf_wa_wabh_style;
	$display = apply_filters('suffusion_can_display_widget_area_below_header', true);
	if (!$display) {
		return;
	}
	if ($suf_widget_area_below_header_enabled == "enabled") {
		if ((is_page_template('no-sidebars.php') && $suf_ns_wabh_enabled == 'not-enabled')) {
		}
		else if (!suffusion_is_sidebar_empty(4)) { ?>
	<!-- horizontal-outer-widgets-1 Widget Area -->
	<div id="horizontal-outer-widgets-1" class="dbx-group <?php echo $suf_wa_wabh_style;?> warea fix">
		<?php
			dynamic_sidebar('Widget Area Below Header');
		?>
	</div>
	<!-- /horizontal-outer-widgets-1 --><?php
		}
	}
}

/*
 * Prints the left sidebars, if the sidebars are enabled and are positioned on the left
 */
function suffusion_print_left_sidebars() {
	global $suf_sidebar_count, $suf_sidebar_alignment, $suf_sidebar_2_alignment, $suf_sbtab_alignment, $tabs_alignment, $suf_sbtab_enabled, $suf_wa_sb1b_style, $suf_wa_sb2b_style, $suf_wa_wst_style, $suf_wa_wsb_style;
	if (is_page_template("no-sidebars.php") || is_page_template("no-widgets.php") || is_page_template('1r-sidebar.php') || is_page_template('2r-sidebars.php')) {
		return;
	}

	$display = apply_filters('suffusion_can_display_left_sidebars', true); // Custom templates can use this hook to avoid sidebars
	if (!$display) {
		return;
	}

	if ($suf_sbtab_enabled == 'enabled' && (($suf_sidebar_count == 1 && $suf_sidebar_alignment == 'left')
		|| ($suf_sidebar_count == 2 && ($suf_sidebar_alignment == 'left' || $suf_sidebar_2_alignment == 'left') && $suf_sbtab_alignment == 'left')
		|| ($suf_sidebar_count == 2 && $suf_sidebar_alignment == 'left' && $suf_sidebar_2_alignment == 'left' && !is_page_template('1l1r-sidebar.php'))
		|| is_page_template('1l-sidebar.php') || is_page_template('2l-sidebars.php')
		|| (is_page_template('1l1r-sidebar.php') && $suf_sbtab_alignment == 'left'))) {
		echo "<div id='sidebar-container' class='sidebar-container-left fix'>";
		$tabs_alignment = 'left';
		if (!function_exists('get_template_part')) {
			include_once (TEMPLATEPATH . '/sidebar-tabs.php');
		}
		else {
			get_template_part('sidebar-tabs');
		}
	}

	if (is_page_template('2l-sidebars.php') ||
			($suf_sidebar_count == 2 && $suf_sidebar_alignment == 'left' && $suf_sidebar_2_alignment == 'left' && !is_page_template('2r-sidebars.php')
					&& !is_page_template('1l1r-sidebar.php') && !is_page_template('1l-sidebar.php') && !is_page_template('1r-sidebar.php'))) {
		echo "<div id='sidebar-wrap' class='sidebar-wrap sidebar-wrap-left fix'>";
		if (!suffusion_is_sidebar_empty(18)) {
			suffusion_print_sidebar(18, 'wsidebar-top', 'Wide Sidebar (Top)', $suf_wa_wst_style, 'left');
		}
	}

	if (($suf_sidebar_count > 0 && $suf_sidebar_alignment == "left") || is_page_template('1l-sidebar.php') || is_page_template('2l-sidebars.php') || (is_page_template('1l1r-sidebar.php') && $suf_sidebar_alignment == 'left')) {
		echo "<div id='sidebar-shell-1' class='sidebar-shell sidebar-shell-left'>\n";
		suffusion_before_first_sidebar();
		get_sidebar();
		suffusion_between_first_sidebars();
		suffusion_print_sidebar(9, 'sidebar-b', 'Sidebar 1 (Bottom)', $suf_wa_sb1b_style, "left");
		suffusion_after_first_sidebar();
		echo "</div>\n";
	}

	if (($suf_sidebar_count > 1 && $suf_sidebar_2_alignment == "left" && !is_page_template('1l-sidebar.php') && !(is_page_template('1l1r-sidebar.php') && $suf_sidebar_alignment == 'left'))
			|| is_page_template('2l-sidebars.php')
			|| (is_page_template('1l1r-sidebar.php') && $suf_sidebar_alignment == 'right')) {
		echo "<div id='sidebar-shell-2' class='sidebar-shell sidebar-shell-left'>\n";
		suffusion_before_second_sidebar();
		get_sidebar(2);
		suffusion_between_second_sidebars();
		suffusion_print_sidebar(10, 'sidebar-2-b', 'Sidebar 2 (Bottom)', $suf_wa_sb2b_style, "left");
		suffusion_after_second_sidebar();
		echo "</div>\n";
	}

	if (is_page_template('2l-sidebars.php') ||
			($suf_sidebar_count == 2 && $suf_sidebar_alignment == 'left' && $suf_sidebar_2_alignment == 'left' && !is_page_template('2r-sidebars.php')
					&& !is_page_template('1l1r-sidebar.php') && !is_page_template('1l-sidebar.php') && !is_page_template('1r-sidebar.php'))) {
		if (!suffusion_is_sidebar_empty(19)) {
			suffusion_print_sidebar(19, 'wsidebar-bottom', 'Wide Sidebar (Bottom)', $suf_wa_wsb_style, 'left');
		}
		echo "</div>";
	}

	if ($suf_sbtab_enabled == 'enabled' && (($suf_sidebar_count == 1 && $suf_sidebar_alignment == 'left')
		|| ($suf_sidebar_count == 2 && ($suf_sidebar_alignment == 'left' || $suf_sidebar_2_alignment == 'left') && $suf_sbtab_alignment == 'left')
		|| ($suf_sidebar_count == 2 && $suf_sidebar_alignment == 'left' && $suf_sidebar_2_alignment == 'left' && !is_page_template('1l1r-sidebar.php'))
		|| is_page_template('1l-sidebar.php') || is_page_template('2l-sidebars.php')
		|| (is_page_template('1l1r-sidebar.php') && $suf_sbtab_alignment == 'left'))) {
		echo "</div> <!-- /#sidebar-container -->";
	}
}

/*
 * Displays the right sidebars, if the sidebars are enabled and are positioned on the right
 */
function suffusion_print_right_sidebars() {
	global $suf_sidebar_count, $suf_sidebar_alignment, $suf_sidebar_2_alignment, $suf_sbtab_alignment, $tabs_alignment, $suf_sbtab_enabled, $suf_wa_sb1b_style, $suf_wa_sb2b_style, $suf_wa_wst_style, $suf_wa_wsb_style;
	if (is_page_template("no-sidebars.php") || is_page_template("no-widgets.php") || is_page_template('1l-sidebar.php') || is_page_template('2l-sidebars.php')) {
		return;
	}

	$display = apply_filters('suffusion_can_display_right_sidebars', true); // Custom templates can use this hook to avoid sidebars
	if (!$display) {
		return;
	}

	if ($suf_sbtab_enabled == 'enabled' && (($suf_sidebar_count == 1 && $suf_sidebar_alignment == 'right')
		|| ($suf_sidebar_count == 2 && ($suf_sidebar_alignment == 'right' || $suf_sidebar_2_alignment == 'right') && $suf_sbtab_alignment == 'right')
		|| ($suf_sidebar_count == 2 && $suf_sidebar_alignment == 'right' && $suf_sidebar_2_alignment == 'right' && !is_page_template('1l1r-sidebar.php'))
		|| is_page_template('1r-sidebar.php') || is_page_template('2r-sidebars.php')
		|| (is_page_template('1l1r-sidebar.php') && $suf_sbtab_alignment == 'right'))) {
		echo "<div id='sidebar-container' class='sidebar-container-right fix'>";
		$tabs_alignment = 'right';
		if (!function_exists('get_template_part')) {
			include_once (TEMPLATEPATH . '/sidebar-tabs.php');
		}
		else {
			get_template_part('sidebar-tabs');
		}
	}

	if (is_page_template('2r-sidebars.php') ||
			($suf_sidebar_count == 2 && $suf_sidebar_alignment == 'right' && $suf_sidebar_2_alignment == 'right' && !is_page_template('2l-sidebars.php')
					&& !is_page_template('1l1r-sidebar.php') && !is_page_template('1l-sidebar.php') && !is_page_template('1r-sidebar.php'))) {
		echo "<div id='sidebar-wrap' class='sidebar-wrap sidebar-wrap-right fix'>";
		if (!suffusion_is_sidebar_empty(18)) {
			suffusion_print_sidebar(18, 'wsidebar-top', 'Wide Sidebar (Top)', $suf_wa_wst_style, 'right');
		}
	}

	if (($suf_sidebar_count > 0 && $suf_sidebar_alignment == "right")
			|| is_page_template('1r-sidebar.php')
			|| is_page_template('2r-sidebars.php')
			|| (is_page_template('1l1r-sidebar.php') && $suf_sidebar_alignment == 'right')) {
		echo "<div id='sidebar-shell-1' class='sidebar-shell sidebar-shell-right'>\n";
		suffusion_before_first_sidebar();
		get_sidebar();
		suffusion_between_first_sidebars();
		suffusion_print_sidebar(9, 'sidebar-b', 'Sidebar 1 (Bottom)', $suf_wa_sb1b_style, "right");
		suffusion_after_first_sidebar();
		echo "</div>\n";
	}

	if (($suf_sidebar_count > 1 && $suf_sidebar_2_alignment == "right" && !is_page_template('1r-sidebar.php') && !(is_page_template('1l1r-sidebar.php') && $suf_sidebar_alignment == 'right'))
			|| is_page_template('2r-sidebars.php')
			|| (is_page_template('1l1r-sidebar.php') && $suf_sidebar_alignment == 'left')) {
		echo "<div id='sidebar-shell-2' class='sidebar-shell sidebar-shell-right'>\n";
		suffusion_before_second_sidebar();
		get_sidebar(2);
		suffusion_between_second_sidebars();
		suffusion_print_sidebar(10, 'sidebar-2-b', 'Sidebar 2 (Bottom)', $suf_wa_sb2b_style, "right");
		suffusion_after_second_sidebar();
		echo "</div>\n";
	}

	if (is_page_template('2r-sidebars.php') ||
			($suf_sidebar_count == 2 && $suf_sidebar_alignment == 'right' && $suf_sidebar_2_alignment == 'right' && !is_page_template('2l-sidebars.php')
					&& !is_page_template('1l1r-sidebar.php') && !is_page_template('1l-sidebar.php') && !is_page_template('1r-sidebar.php'))) {
		if (!suffusion_is_sidebar_empty(19)) {
			suffusion_print_sidebar(19, 'wsidebar-bottom', 'Wide Sidebar (Bottom)', $suf_wa_wsb_style, 'right');
		}
		echo "</div>";
	}

	if ($suf_sbtab_enabled == 'enabled' && (($suf_sidebar_count == 1 && $suf_sidebar_alignment == 'right')
		|| ($suf_sidebar_count == 2 && ($suf_sidebar_alignment == 'right' || $suf_sidebar_2_alignment == 'right') && $suf_sbtab_alignment == 'right')
		|| ($suf_sidebar_count == 2 && $suf_sidebar_alignment == 'right' && $suf_sidebar_2_alignment == 'right' && !is_page_template('1l1r-sidebar.php'))
		|| is_page_template('1r-sidebar.php') || is_page_template('2r-sidebars.php')
		|| (is_page_template('1l1r-sidebar.php') && $suf_sbtab_alignment == 'right'))) {
		echo "</div> <!-- /#sidebar-container -->";
	}
}

/*
 * Displays the widget area above the footer, if it is enabled.
 */
function suffusion_print_widget_area_above_footer() {
	global $suf_widget_area_above_footer_enabled, $suf_ns_waaf_enabled,  $suf_wa_waaf_style;
	$display = apply_filters('suffusion_can_display_widget_area_above_footer', true);
	if (!$display) {
		return;
	}
	if ($suf_widget_area_above_footer_enabled == "enabled") {
		if (is_page_template('no-sidebars.php') && ($suf_ns_waaf_enabled == 'not-enabled')) {
		}
		else if (!suffusion_is_sidebar_empty(5)) { ?>
	<!-- horizontal-outer-widgets-2 Widget Area -->
	<div id="horizontal-outer-widgets-2" class="<?php echo $suf_wa_waaf_style; ?> warea fix">
		<?php
			dynamic_sidebar('Widget Area Above Footer');
		?>
	</div>
	<!-- /horizontal-outer-widgets-2 -->
<?php
		}
	}
}

function suffusion_display_footer() {
	global $suf_footer_left, $suf_footer_center, $suf_footer_layout_style;
	$display = apply_filters('suffusion_can_display_site_footer', true);
	if (!$display) {
		return;
	}
	if ($suf_footer_layout_style != 'in-align') {
	?>
	<div id='page-footer'>
		<div class='col-control'>
	<?php
	}
	?>
	<div id="cred">
		<table>
			<tr>
				<td class="cred-left"><?php $strip = stripslashes($suf_footer_left); echo do_shortcode($strip); ?></td>
				<td class="cred-center"><?php $strip = stripslashes($suf_footer_center); echo do_shortcode($strip); ?></td>
				<td class="cred-right"><a href="http://www.aquoid.com/news/themes/suffusion/">Suffusion theme by Sayontan Sinha</a></td>
			</tr>
		</table>
	</div>
	<?php
	if ($suf_footer_layout_style != 'in-align') {
	?>
		</div>
	</div>
	<?php
	}
	?>
	<!-- <?php echo get_num_queries(); ?> queries, <?php suffusion_get_memory_usage(); ?> in <?php timer_stop(1); ?> seconds. -->
	<?php
 }

function suffusion_include_custom_footer_js() {
	global $suf_custom_footer_js;
	if (isset($suf_custom_footer_js) && trim($suf_custom_footer_js) != "") {
?>
<!-- Custom JavaScript for footer defined in options -->
<script type="text/javascript">
/* <![CDATA[ */
	<?php echo stripslashes($suf_custom_footer_js)."\n"; ?>
/* ]]> */
</script>
<!-- /Custom JavaScript for footer defined in options -->
<?php }
}

function suffusion_get_siblings_in_nav($ancestors, $index, $exclusion_list, $exclude, $echo = 1) {
	if (count($ancestors) <= $index || $index < 0) {
		return;
	}
	$exclusion_query = $exclude == "hide" ? "&exclude=".$exclusion_list : "";
	$children = wp_list_pages("title_li=&child_of=".$ancestors[$index]."&echo=".$echo.$exclusion_query);
	return $children;
}

function suffusion_display_hierarchical_navigation() {
	$display = apply_filters('suffusion_can_display_hierarchical_navigation', true);
	if (!$display) {
		return;
	}
	global $post, $suf_nav_breadcrumb, $suf_nav_exclude_in_breadcrumb;
	$ancestors = get_post_ancestors($post);
	$exclusion_list = get_excluded_pages("suf_nav_pages");
	$num_ancestors = count($ancestors);

	if ($suf_nav_breadcrumb == "all") {
		for ($anc_index = 1; $num_ancestors - $anc_index >= 0; $anc_index++) {
			$style = ($anc_index == 1) ? "subnav" : "l".($anc_index + 1)."nav";
			$class = ($anc_index == 1) ? "" : "hier-nav";
?>
	<div id="<?php echo $style;?>" class="<?php echo $class; ?> fix">
		<ul>
			<?php suffusion_get_siblings_in_nav($ancestors, $num_ancestors - $anc_index, $exclusion_list, $suf_nav_exclude_in_breadcrumb); ?>
		</ul>
	</div><?php echo "<!-- /".$style."-->"; ?>
<?php
		}
		$exclusion_query = $suf_nav_exclude_in_breadcrumb == "hide" ? "&exclude_tree=".$exclusion_list : "";
		$style = ($num_ancestors == 0) ? "subnav" : "l".($num_ancestors + 2)."nav";
		$class = ($num_ancestors == 0) ? "" : "hier-nav";
		$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0".$exclusion_query);
		if ($children) {
	?>
	<div id="<?php echo $style;?>" class="<?php echo $class; ?> fix">
		<ul>
			<?php echo $children; ?>
		</ul>
	</div><!-- /sub nav -->
<?php
		}
	}
}

function suffusion_create_navigation_breadcrumb() {
	$display = apply_filters('suffusion_can_display_breadcrumb_navigation', true);
	if (!$display) {
		return;
	}
	global $suf_nav_breadcrumb, $suf_breadcrumb_separator, $post;

	$ancestors = get_post_ancestors($post);
	$num_ancestors = count($ancestors);
	if ($suf_nav_breadcrumb == "breadcrumb") {
		if ($num_ancestors > 0) {
	?>
	<div id="subnav" class="fix">
		<div class="breadcrumb">
	<?php
			for ($i = $num_ancestors-1; $i>=0; $i--) {
				$anc_page = get_page($ancestors[$i]);
				echo "<a href='".get_permalink($ancestors[$i])."'>".$anc_page->post_title."</a> ".$suf_breadcrumb_separator." ";
			}
			echo $post->post_title;
	?>
		</div>
	</div><!-- /sub nav -->
	<?php
		}
	}
}

function suffusion_excerpt_or_content() {
	global $post, $suf_category_excerpt, $suf_tag_excerpt, $suf_archive_excerpt, $suf_index_excerpt, $suf_search_excerpt, $suf_author_excerpt, $suf_show_excerpt_thumbnail, $suf_show_content_thumbnail, $full_content_post_counter, $full_post_count;
	if (($full_content_post_counter > $full_post_count) && ((is_category() && $suf_category_excerpt == "excerpt") ||
		(is_tag() && $suf_tag_excerpt == "excerpt") ||
		(is_search() && $suf_search_excerpt == "excerpt") ||
		(is_author() && $suf_author_excerpt == "excerpt") ||
		((is_date() || is_year() || is_month() || is_day() || is_time())&& $suf_archive_excerpt == "excerpt") ||
		(!(is_singular() || is_category() || is_tag() || is_search() || is_author() || is_date() || is_year() || is_month() || is_day() || is_time()) && $suf_index_excerpt == "excerpt"))) {
		$show_image = $suf_show_excerpt_thumbnail == "show" ? true : false;
		suffusion_excerpt($show_image);
	}
	else {
		if (function_exists('add_theme_support') && has_post_thumbnail($post->ID) && $suf_show_content_thumbnail == 'show')
			echo suffusion_get_post_thumbnail('content');
		the_content(__('Continue reading', 'suf_theme').' &raquo;');
	}
}

function suffusion_excerpt($show_image = false) {
	global $post;
	if ($show_image) {
		if (function_exists('add_theme_support') && has_post_thumbnail($post->ID)) {
			echo suffusion_get_post_thumbnail('excerpt');
		}
		else {
			echo suffusion_get_image(array());
		}
	}
	the_excerpt();
}

function suffusion_get_image($options = array()) {
	global $post, $suf_excerpt_thumbnail_alignment, $suf_excerpt_thumbnail_size, $suf_excerpt_thumbnail_custom_width, $suf_excerpt_thumbnail_custom_height;
	global $suf_featured_image_size, $suf_featured_image_custom_width, $suf_featured_image_custom_height, $suf_img_use_resizing_script;
	global $suf_mag_headline_image_size, $suf_mag_headline_image_custom_width, $suf_mag_headline_image_custom_height, $suf_mag_headline_image_alignment;
	global $suf_mag_excerpt_image_size, $suf_mag_excerpt_image_custom_width, $suf_mag_excerpt_image_custom_height, $suf_excerpt_tt_zc, $suf_excerpt_tt_quality;
	$img = "";
	$full_size = false;
	if ((isset($options['featured']) && $options['featured']) || (isset($options['featured-widget']) && $options['featured-widget'])) { // First try to retrieve a featured image, if "featured" is true
		$img = get_post_meta($post->ID, "featured_image", true);
        $featured_width = isset($options['featured-width']) ? $options['featured-width'] : get_size_from_field($suf_featured_image_custom_width, '200px');
        $featured_width = (int)(substr($featured_width, 0, strlen($featured_width) - 2));
		$featured_height = isset($options['featured-height']) ? $options['featured-height'] : get_size_from_field($suf_featured_image_custom_height, '200px');
	   	$featured_height = (int)(substr($featured_height, 0, strlen($featured_height) - 2));
	}
	if ((isset($options['featured']) && $options['featured'] && $suf_featured_image_size == 'custom') || (isset($options['featured-widget']) && $options['featured-widget'] && $options['featured-image-custom-size'])) {
		$width = $featured_width;
		$height = $featured_height;
		$size = array($width, $height);
	}
	else if (isset($options['mag-headline']) && $options['mag-headline'] && $suf_mag_headline_image_size == 'custom') {
		$width = get_size_from_field($suf_mag_headline_image_custom_width, '200px');
		$width = (int)(substr($width, 0, strlen($width) - 2));
		$height = get_size_from_field($suf_mag_headline_image_custom_height, '200px');
		$height = (int)(substr($height, 0, strlen($height) - 2));
		$size = array($width, $height);
	}
	else if (isset($options['mag-excerpt']) && $options['mag-excerpt'] && $suf_mag_excerpt_image_size == 'custom') {
		$width = get_size_from_field($suf_mag_excerpt_image_custom_width, '200px');
		$width = (int)(substr($width, 0, strlen($width) - 2));
		$height = get_size_from_field($suf_mag_excerpt_image_custom_height, '200px');
		$height = (int)(substr($height, 0, strlen($height) - 2));
		$size = array($width, $height);
	}
	else if (((!isset($options['featured']) || !$options['featured']) && (!isset($options['featured-widget']) || !$options['featured-widget']) && (!isset($options['mag-headline']) || !$options['mag-headline']) && (!isset($options['mag-excerpt']) || !$options['mag-excerpt'])) ||
			(isset($options['featured']) && $options['featured'] && $suf_featured_image_size == 'excerpt') || (isset($options['featured-widget']) && $options['featured-widget'] && !$options['featured-image-custom-size']) ||
			(isset($options['mag-headline']) && $options['mag-headline'] && $suf_mag_headline_image_size == 'excerpt') || (isset($options['mag-excerpt']) && $options['mag-excerpt'] && $suf_mag_excerpt_image_size == 'excerpt')) {
		if ($suf_excerpt_thumbnail_size == 'custom') {
			$width = get_size_from_field($suf_excerpt_thumbnail_custom_width, '200px');
			$width = (int)(substr($width, 0, strlen($width) - 2));
			$height = get_size_from_field($suf_excerpt_thumbnail_custom_height, '200px');
			$height = (int)(substr($height, 0, strlen($height) - 2));
			$size = array($width, $height);
		}
		else if ($suf_excerpt_thumbnail_size == 'thumbnail' || $suf_excerpt_thumbnail_size == 'medium' || $suf_excerpt_thumbnail_size == 'large') {
			$width = get_option($suf_excerpt_thumbnail_size.'_size_w');
			$height = get_option($suf_excerpt_thumbnail_size.'_size_h');
			$size = $suf_excerpt_thumbnail_size;
		}
		else {
			$full_size = true;
			$size = 'full';
		}
	}
	else {
		$full_size = true;
		$size = 'full';
	}
	if (trim($img) == "") { // Retrieve image from thumbnail
		$img = get_post_meta($post->ID, "thumbnail", true);
	}
	if (trim($img) == "") { // No thumbnail. Try getting the images from the gallery.
		$attachments = get_children(array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment',
			'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order'));
		if (is_array($attachments)) {
			foreach ($attachments as $id => $attachment) {
				$img = wp_get_attachment_image_src($id, $size);
				$img = $img[0];
				break;
			}
		}
	}
	if (trim($img) == "") { // No gallery. Try embedded images.
		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post->post_content, $images);
		if (isset($images) && isset($images[1]) && isset($images[1][0]) && $images[1][0])
			$img = $images[1][0];
	}
	if (trim($img) != "") {
		if (!$full_size && $suf_img_use_resizing_script == 'timthumb' && function_exists('imagecreatetruecolor')) {
			global $blog_id;
			if (isset($blog_id) && $blog_id > 0) {
				$imageParts = explode('/files/', $img);
				if (isset($imageParts[1])) {
					$img = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
				}
				$install_type = "&amp;type=m";
			}
			else {
				$install_type = "&amp;type=s";
			}
			$img = urlencode($img);
			$img = get_bloginfo('template_directory')."/timthumb.php?src=$img&amp;w=$width&amp;h=$height&amp;zc=$suf_excerpt_tt_zc&amp;quality=$suf_excerpt_tt_quality$install_type";
		}
		if (isset($options["featured"]) || isset($options['featured-widget'])) {
			return "<a href=\"".get_permalink($post->ID)."\"><img src=\"".$img."\" alt=\"".$post->post_title."\" class=\"featured-excerpt-".$options["excerpt_position"]."\"/></a>";
		}
		else if (isset($options['mag-headline']) || isset($options['mag-excerpt'])) {
			return "<a href=\"".get_permalink($post->ID)."\"><img src=\"".$img."\" alt=\"".$post->post_title."\" /></a>";
		}
		else {
			return "<a href=\"".get_permalink($post->ID)."\"><img src=\"".$img."\" alt=\"".$post->post_title."\" class=\"$suf_excerpt_thumbnail_alignment-thumbnail\"/></a>";
		}
	}
	else {
		return "";
	}
}

function suffusion_post_footer() {
	global $suf_post_show_posted_by, $suf_page_show_posted_by, $suf_post_show_tags, $suf_post_show_cats, $suf_post_show_comment, $suf_page_show_comment, $post, $suf_post_meta_position, $suf_page_meta_position;
?>
		<div class="post-footer fix">
<?php
	if ((!is_page() && $suf_post_meta_position == 'corners' && ($suf_post_show_posted_by == 'show' || $suf_post_show_posted_by == 'show-bright')) ||
		(is_page() && $suf_page_meta_position == 'corners' && ($suf_page_show_posted_by == 'show' || $suf_page_show_posted_by == 'show-bright'))) {  ?>
            <?php knowledgeblog_authors(); ?>
<?php
	}
	if (!is_page() && $suf_post_meta_position == 'corners' && ($suf_post_show_cats == 'show-bleft' || $suf_post_show_cats == 'show-bright')) {
?>
			<span class="category"><?php the_category(', ') ?></span>
<?php
	}
	if (!is_page()) {
		if ($suf_post_meta_position == 'corners') {
			if (is_singular()) {
				if ('open' == $post->comment_status && ($suf_post_show_comment == 'show-bleft' || $suf_post_show_comment == 'show-bright')) {
?>
			<span class="comments"><a href="#respond"><?php _e('Add comments', 'suf_theme'); ?></a></span>
<?php
				}
			}
			else if ($suf_post_show_comment == 'show-bleft' || $suf_post_show_comment == 'show-bright') { ?>
			<span class="comments"><?php comments_popup_link(__('No Responses', 'suf_theme').' &#187;', __('1 Response', 'suf_theme').' &#187;', __('% Responses', 'suf_theme').' &#187;'); ?></span>
<?php
			}
		}
	}
	else {
		if ('open' == $post->comment_status && $suf_page_meta_position == 'corners' && ($suf_page_show_comment == 'show-bleft' || $suf_page_show_comment == 'show-bright')) {
?>
			<span class="comments"><a href="#respond"><?php _e('Add comments', 'suf_theme'); ?></a></span>
<?php
		}
	}
	if (!is_page() && $suf_post_meta_position == 'corners' && ($suf_post_show_tags == 'show' ||  $suf_post_show_tags == 'show-bleft')) { ?>
			<span class="tags"><?php the_tags(__('Tagged with: ', 'suf_theme'),', ','<br />'); ?></span>
<?php
	}
?>
		</div>
<?php
}

function suffusion_disable_plugin_styles() {
	wp_deregister_style('wp-pagenavi');
}

function suffusion_pagination() {
	global $suf_pagination_type, $suf_pagination_index, $suf_pagination_prev_next, $suf_pagination_show_all;
	if (is_singular()) {
		return;
	}
    if (show_page_nav()) {
        if (function_exists("wp_pagenavi")) {
			// If the user has wp_pagenavi installed, we will use that for pagination
?>
		<div class="page-nav fix">
<?php
			wp_pagenavi();
?>
		</div><!-- page nav -->
<?php
		}
		else if ($suf_pagination_type == "numbered") {
			// The user doesn't have WP-PageNavi, but still wants pagination
			global $wp_query, $paged;
			$max_page = $wp_query->max_num_pages;
			$prev_next = $suf_pagination_prev_next == "show";
			$show_all = $suf_pagination_show_all == "all";
			if (!$paged && $max_page >= 1) {
				$current_page = 1;
			}
			else {
				$current_page = $paged;
			}
?>
		<div class="page-nav fix">
			<div class="suf-page-nav fix">
<?php
			if ($suf_pagination_index == "show") {
?>
				<span class="page-index"><?php printf(__('Page %1$s of %2$s', 'suf_theme'), $current_page, $max_page); ?></span>
<?php
			}
			echo paginate_links(array(
				"base" => add_query_arg("paged", "%#%"),
				"format" => '',
				"type" => "plain",
				"total" => $max_page,
				"current" => $current_page,
				"show_all" => $show_all,
				"end_size" => 2,
				"mid_size" => 2,
				"prev_next" => $prev_next,
				"next_text" => __('Older Entries', 'suf_theme'),
				"prev_text" => __('Newer Entries', 'suf_theme'),
			));
?>
			</div><!-- suf page nav -->
		</div><!-- page nav -->
<?php
		}
		else {
?>
		<div class="page-nav fix">
			<span class="previous-entries"><?php next_posts_link(__('Older Entries', 'suf_theme')); ?></span>
			<span class="next-entries"><?php previous_posts_link(__('Newer Entries', 'suf_theme')); ?></span>
		</div><!-- page nav -->
<?php
		}
    }
}

function suffusion_comment_pagination() {
	global $suf_cpagination_type, $suf_cpagination_index, $suf_cpagination_prev_next, $suf_cpagination_show_all;
	if ($suf_cpagination_type == "numbered") {
		// The user wants pagination
		global $wp_query, $paged;
		$max_page = $wp_query->max_num_pages;
		$prev_next = $suf_cpagination_prev_next == "show";
		$show_all = $suf_cpagination_show_all == "all";
		if (!$paged && $max_page >= 1) {
			$current_page = 1;
		}
		else {
			$current_page = $paged;
		}
?>
		<div class="page-nav fix">
			<div class="suf-page-nav fix">
<?php
		if ($suf_cpagination_index == "show") {
?>
				<span class="page-index"><?php printf(__('Page %1$s of %2$s', 'suf_theme'), $current_page, $max_page); ?></span>
<?php
		}
		echo paginate_comments_links(array(
			"base" => add_query_arg("cpage", "%#%"),
			"format" => '',
			"type" => "plain",
			"total" => $max_page,
			"current" => $current_page,
			"show_all" => $show_all,
			"end_size" => 2,
			"mid_size" => 2,
			"prev_next" => $prev_next,
			"next_text" => __('Older Entries', 'suf_theme'),
			"prev_text" => __('Newer Entries', 'suf_theme'),
		));
?>
			</div><!-- suf page nav -->
		</div><!-- page nav -->
<?php
	}
	else {
?>
		<div class="page-nav fix">
			<span class="previous-entries"><?php next_posts_link(__('Older Entries', 'suf_theme')); ?></span>
			<span class="next-entries"><?php previous_posts_link(__('Newer Entries', 'suf_theme')); ?></span>
		</div><!-- page nav -->
<?php
	}
}

function suffusion_featured_posts() {
	global $suf_featured_category_view, $suf_featured_tag_view, $suf_featured_search_view, $suf_featured_author_view, $suf_featured_time_view, $suf_featured_index_view;
	global $suf_mag_featured_enabled, $suf_featured_pages_with_fc, $post;
    $pages_with_fc = explode(',', $suf_featured_pages_with_fc);
	if ((is_category() && $suf_featured_category_view == "enabled") || (is_tag() && $suf_featured_tag_view == "enabled") ||
		(is_search() && $suf_featured_search_view == "enabled") || (is_author() && $suf_featured_author_view == "enabled") ||
		(is_page_template('magazine.php') && $suf_mag_featured_enabled == 'enabled') ||
        (is_page() && isset($pages_with_fc) && is_array($pages_with_fc) && isset($post) && isset($post->ID) && in_array($post->ID, $pages_with_fc)) ||
		((is_date() || is_year() || is_month() || is_day() || is_time()) && $suf_featured_time_view == "enabled") ||
		(!(is_category() || is_tag() || is_search() || is_author() || is_date() || is_year() || is_month() || is_day() || is_time() || is_page_template('magazine.php') || is_page())
			&& $suf_featured_index_view == "enabled")) {
		locate_template(array("featured-posts.php"), true);
		suffusion_display_featured_posts();
	}
}

function suffusion_include_featured_js() {
	global $suf_featured_category_view, $suf_featured_tag_view, $suf_featured_search_view, $suf_js_in_footer;
	global $suf_featured_author_view, $suf_featured_time_view, $suf_featured_index_view, $suf_mag_featured_enabled, $suf_featured_pages_with_fc;
	$footer = $suf_js_in_footer == 'footer' ? true : false;
	if ((is_category() && $suf_featured_category_view == "enabled") || (is_tag() && $suf_featured_tag_view == "enabled") ||
		(is_search() && $suf_featured_search_view == "enabled") || (is_author() && $suf_featured_author_view == "enabled") ||
		(is_page_template('magazine.php') && $suf_mag_featured_enabled == 'enabled') ||
        (is_page() && $suf_featured_pages_with_fc != '') ||
		((is_date() || is_year() || is_month() || is_day() || is_time()) && $suf_featured_time_view == "enabled") ||
		(!(is_category() || is_tag() || is_search() || is_author() || is_date() || is_year() || is_month() || is_day() || is_time() || is_page_template('magazine.php') || is_page()) && $suf_featured_index_view == "enabled")) {
		wp_enqueue_script('jquery-cycle');
        wp_enqueue_script('slider-init', get_template_directory_uri() . '/scripts/slider-init.js', array('jquery-cycle'), null, $footer);
	}
    if (!is_admin() && is_active_widget('Suffusion_Featured_Posts', false, 'suf-featured-posts', true)) {
	    wp_enqueue_script('jquery-cycle');
    }
}

function suffusion_template_specific_header() {
	global $suf_cat_info_enabled, $suf_author_info_enabled, $suf_tag_info_enabled, $suf_search_info_enabled;
	if (is_category() && ($suf_cat_info_enabled == 'enabled')) { ?>
		<div class="category-info fix">
			<h2 class="category-title"><?php single_cat_title(); ?></h2>
<?php echo suffusion_get_category_information(); ?>
		</div><!-- .category-info -->
<?php
	}
	else if (is_author() && ($suf_author_info_enabled == 'enabled')) {
		$id = get_query_var('author'); ?>
		<div id="author-profile-<?php the_author_meta('user_nicename', $id); ?>" class="author-profile author-even fix">
			<h2 class="author-title"><?php the_author_meta('display_name', $id); ?></h2>
			<?php echo suffusion_get_author_information();?>
		</div><!-- /.author-profile -->
<?php
	}
	else if (is_tag() && ($suf_tag_info_enabled == 'enabled')) { ?>
		<div class="tag-info fix">
			<h2 class="tag-title"><?php single_tag_title(); ?></h2>
		<?php echo tag_description(get_query_var('tag_id')); ?>
		</div><!-- .tag-info -->
<?php
	}
	else if (is_search() && $suf_search_info_enabled == 'enabled') {
		if (have_posts()) {	?>
		<div class='post fix'>
			<h2 class='posttitle'><?php $title = wp_title(':', false); $title = trim($title); if (substr($title, 0, 1) == ':') { $title = substr($title, 1);} echo $title; ?></h2>
			<form method="get" action="<?php bloginfo('url'); ?>/" class='search-info' id='search-info'>
				<input class="search-hl checkbox" name="search-hl" id="search-hl" type="checkbox"/>
				<label class='search-hl' for='search-hl'><?php _e('Highlight matching results below', 'suf_theme');?></label>
				<input type='hidden' name='search-term' id='search-term' value="<?php $search_term = get_search_query(); echo esc_attr($search_term);?>"/>
			</form>
			<?php get_search_form(); ?>
		</div>
<?php
		}
	}
}

function suffusion_get_category_information() {
	$ret = "<div class=\"category-description\">\n";
	if (function_exists('get_cat_icon')) {
		$ret .= get_cat_icon('echo=false');
	}
	$ret .= category_description()."\n";
	$ret .= "</div><!-- .category-description -->\n";
	return $ret;
}

function suffusion_get_author_information() {
	$id = get_query_var('author');
	$ret = "<div class=\"author-description\">\n";
	$ret .= get_avatar(get_the_author_meta('user_email', $id), '96')."\n";
	$ret .= "<p class=\"author-bio\">\n";
	$ret .= get_the_author_meta('description', $id)."\n";
	$ret .= "</p><!-- /.author-bio -->\n";
	$ret .= "</div><!-- /.author-description -->\n";
	return $ret;
}

function suffusion_print_post_page_title() {
	global $post, $suf_post_show_cats, $suf_post_show_comment, $suf_page_show_comment, $suf_post_show_posted_by, $suf_page_show_posted_by, $suf_post_show_tags, $suf_post_meta_position, $suf_page_meta_position;
	if (is_singular()) {
		$header_tag = "h1";
	}
	else {
		$header_tag = "h2";
	}

	if ($post->post_type == 'post') {
?>
		<div class='title-container fix'>
			<div class="title">
				<<?php echo $header_tag;?>  class="posttitle"><?php echo suffusion_get_post_title_and_link(); ?></<?php echo $header_tag;?>>
<?php
		if ($suf_post_meta_position == 'corners') {
?>
				<div class="postdata fix">
<?php
		if (($suf_post_show_posted_by == 'show-tleft' || $suf_post_show_posted_by == 'show-tright') && $suf_post_meta_position == 'corners') {  ?>
			<?php knowledgeblog_authors(); ?>
<?php
		}
		if ($suf_post_show_cats == 'show' || $suf_post_show_cats == 'show-tright') {
?>
			<span class="category"><?php the_category(', ') ?></span>
<?php
		}
		if (is_singular()) {
			if ('open' == $post->comment_status && ($suf_post_show_comment == 'show' || $suf_post_show_comment == 'show-tleft')) {
?>
			<span class="comments"><a href="#respond"><?php _e('Add comments', 'suf_theme'); ?></a></span>
<?php
			}
		}
		else if ($suf_post_show_comment == 'show' || $suf_post_show_comment == 'show-tleft') { ?>
			<span class="comments"><?php comments_popup_link(__('No Responses', 'suf_theme').' &#187;', __('1 Response', 'suf_theme').' &#187;', __('% Responses', 'suf_theme').' &#187;'); ?></span>
<?php	}
		if (is_singular() && get_edit_post_link() != '') { ?>
   			<span class="edit"><?php edit_post_link(__('Edit', 'suf_theme'), '', ''); ?></span>
<?php
		}
		if ($suf_post_show_tags == 'show-tleft' ||  $suf_post_show_tags == 'show-tright') { ?>
			<span class="tags"><?php the_tags(__('Tagged with: ', 'suf_theme'),', ','<br />'); ?></span>
<?php
		}
?>
				</div><!-- /.postdata -->
<?php
	}
?>
			</div><!-- /.title -->
<?php
		if ("post" == $post->post_type) {
?>
			<div class="date"><span class="month"><?php the_time('M'); ?></span> <span class="day"><?php the_time('d'); ?></span><span class="year"><?php the_time('Y'); ?></span></div>
<?php
		}
?>
		</div><!-- /.title-container -->
<?php
	}
	else {
		if (!is_singular()) {
?>
		<<?php echo $header_tag;?> class="posttitle"><?php echo suffusion_get_post_title_and_link(); ?></<?php echo $header_tag;?>>
<?php
		}
		else {
?>
        <<?php echo $header_tag;?> class="posttitle"><?php the_title(); ?></<?php echo $header_tag;?>>
<?php
		}

		if ($suf_page_meta_position == 'corners') {
?>
        <div class="postdata fix">
<?php
		if ($suf_page_show_posted_by == 'show-tleft' || $suf_page_show_posted_by == 'show-tright') {  ?>
			<?php knowledgeblog_authors(); ?>
<?php
		}
		if ('open' == $post->comment_status && ($suf_page_show_comment == 'show' || $suf_page_show_comment == 'show-tleft')) {
?>
			<span class="comments"><a href="#respond"><?php _e('Add comments', 'suf_theme'); ?></a></span>
<?php
		}
		if (get_edit_post_link() != '') {
?>
			<span class="edit"><?php edit_post_link(__('Edit', 'suf_theme'), '', ''); ?></span>
<?php
		}
?>
        </div>
<?php
		}
	}
}

function suffusion_include_custom_js_files() {
	global $suf_custom_js_file_1, $suf_custom_js_file_2, $suf_custom_js_file_3, $suf_js_in_footer;
	$footer = $suf_js_in_footer == 'footer' ? true : false;
	if ($suf_custom_js_file_1) {
		wp_enqueue_script('suffusion-js-1', $suf_custom_js_file_1, array(), null, $footer);
	}
	if ($suf_custom_js_file_2) {
		wp_enqueue_script('suffusion-js-2', $suf_custom_js_file_2, array(), null, $footer);
	}
	if ($suf_custom_js_file_3) {
		wp_enqueue_script('suffusion-js-3', $suf_custom_js_file_3, array(), null, $footer);
	}
}

function suffusion_include_jqfix_js() {
	global $suf_js_in_footer;
	$footer = $suf_js_in_footer == 'footer' ? true : false;
    wp_enqueue_script('suffusion', get_template_directory_uri() . '/scripts/suffusion.js', array('jquery'), '3.6.9', $footer);
}

function suffusion_set_title() {
    global $suf_seo_enabled, $suf_seo_title_style, $suf_seo_title_separator, $suf_seo_show_subtitle, $suf_seo_show_page_num, $paged, $page;
	if ($suf_seo_enabled != 'enabled') {
		if (is_home() || is_front_page()) {
			echo "\t<title>".get_bloginfo('name')."</title>\n";
		}
		else {
			echo "\t<title>".wp_title('&raquo;', false)."</title>\n";
		}
	}

    $page_title = wp_title('', false);
    $blog_title = get_bloginfo('name');
    if (is_home() || is_front_page()) {
        $title = $blog_title;
        if ($suf_seo_show_subtitle == 'show') {
    		$blog_subtitle = get_bloginfo('description');
        	$title .= " ".$suf_seo_title_separator." ".$blog_subtitle;
        }
    }
    else {
        if ($suf_seo_title_style == 'page-blog') {
            $title = $page_title." ".$suf_seo_title_separator." ".$blog_title;
        }
        else if ($suf_seo_title_style == 'blog-page') {
            $title = $blog_title." ".$suf_seo_title_separator." ".$page_title;
        }
        else {
            $title = $page_title;
        }
    }
	if ($suf_seo_show_page_num == 'show' && ($paged >= 2 || $page >= 2)) {
		$title .= ' '.$suf_seo_title_separator.' '.sprintf(__('Page %s', 'suf_theme'), max($paged, $page));
	}
    $title = apply_filters('suffusion_set_title', $title);
    echo "\t<title>{$title}</title>\n";
}

function suffusion_include_meta() {
    global $suf_seo_enabled, $suf_seo_all_settings;
    if ($suf_seo_enabled == 'enabled') {
        $seo_settings = explode(',', $suf_seo_all_settings);
        suffusion_include_meta_generator($seo_settings);
        suffusion_include_meta_theme($seo_settings);
        suffusion_include_meta_robots($seo_settings);
        suffusion_include_meta_author($seo_settings);
        suffusion_include_meta_copyright($seo_settings);
        suffusion_include_meta_revised($seo_settings);

        suffusion_include_meta_description();
        suffusion_include_meta_keywords();
    }
}

function suffusion_include_meta_generator($seo_settings) {
    if ($seo_settings && in_array('generator', $seo_settings)) {
        wp_generator();
    }
}

function suffusion_include_meta_theme($seo_settings) {
    if ($seo_settings && in_array('theme', $seo_settings)) {
        $theme_data = get_theme_data(TEMPLATEPATH.'/style.css');
        echo "\t".'<meta name="template" content="'.esc_attr("{$theme_data['Title']} {$theme_data['Version']}").'" />'."\n";
    }
}

function suffusion_include_meta_robots($seo_settings) {
    if ($seo_settings && in_array('robots', $seo_settings) && get_option('blog_public')) {
        echo "\t".'<meta name="robots" content="noindex,nofollow" />' . "\n";
    }
}

function suffusion_include_meta_author($seo_settings) {
    global $wp_query;
    if ($seo_settings && in_array('author', $seo_settings)) {
        if (is_singular()) {
            $author = get_the_author_meta('display_name', $wp_query->post->post_author);
        }
        else {
            $posts_on_page = $wp_query->posts;
            $author_array = array();
            foreach ($posts_on_page as $single_post) {
                $single_author = get_the_author_meta('display_name', $single_post->post_author);
                if (!in_array($single_author, $author_array)) {
                    $author_array[] = get_the_author_meta('display_name', $single_post->post_author);
                }
            }
            $author = implode(',',$author_array);
        }

        if ($author) {
            echo "\t".'<meta name="author" content="'.esc_attr($author).'" />' . "\n";
        }
    }
}

function suffusion_include_meta_copyright($seo_settings) {
    if ($seo_settings && in_array('copyright', $seo_settings)) {
        if (is_singular()) {
            $copy_date = sprintf(get_the_time(get_option('date_format')));
        }
        else {
            $copy_date = date('Y');
        }
        echo "\t".'<meta name="copyright" content="'.sprintf(esc_attr__('Copyright (c) %1$s', 'suf_theme'), $copy_date).'" />'."\n";
    }
}

function suffusion_include_meta_revised($seo_settings) {
    if ($seo_settings && in_array('revised', $seo_settings)) {
        if (is_singular()) {
            $mod_time = sprintf(get_the_modified_time(get_option('date_format')." ".get_option('time_format')));
            echo "\t".'<meta name="revised" content="'.$mod_time.'" />'."\n";
        }
    }
}

function suffusion_include_default_feed() {
	global $suf_custom_default_rss_enabled, $wp_version;
	if ($suf_custom_default_rss_enabled == 'enabled') {
		if ($wp_version < 3) {
			// In version 3.7.0 Removing the call to automatic_feed_links() because the theme uploader will fail the check.
			// Instead if users are on 2.9 or older, the link will be printed directly.
//			if (function_exists('automatic_feed_links')) {
//				echo automatic_feed_links();
//			}
//			else {
				echo "\t".'<link rel="alternate" type="application/rss+xml" title="'.esc_attr(get_bloginfo('name')).' RSS Feed" href="'.get_feed_link('rss2').'" />'."\n";
//			}
		}
	}
}

function suffusion_include_meta_description() {
    global $suf_seo_meta_description, $wp_query;
    if (is_home()) {
        $description = $suf_seo_meta_description;
    }
    else if (is_singular()) {
        $description = get_post_meta($wp_query->post->ID, "meta_description", true);
        if (empty($description) && is_front_page()) {
            $description = $suf_seo_meta_description;
        }
    }
    else if (is_category() || is_tag() || is_tax()) {
        $description = term_description('', get_query_var('taxonomy'));
    }
    else if (is_author()) {
        $description = get_the_author_meta('description', get_query_var('author'));
    }
    if (!empty($description)) {
        $description = stripslashes($description);
        $description = strip_tags($description);
        $description = str_replace(array("\r", "\n", "\t"), '', $description);
        $description = "\t".'<meta name="description" content="' . $description . '" />' . "\n";
        echo $description;
    }
}

function suffusion_include_meta_keywords() {
    global $suf_seo_meta_keywords, $wp_query;
    if (is_home() || is_category() || is_tag() || is_tax() || is_author()) {
        $keywords = $suf_seo_meta_keywords;
    }
    else if (is_singular()) {
        $keywords = get_post_meta($wp_query->post->ID, "meta_keywords", true);
        if (empty($keywords)) {
            $keywords = $suf_seo_meta_keywords;
        }
    }

    if (!empty($keywords)) {
        $keywords = stripslashes($keywords);
        $keywords = strip_tags($keywords);
        $keywords = str_replace(array("\r", "\n", "\t"), '', $keywords);
        $keywords = str_replace(array(", ", " ,"), ',', $keywords);
        $keywords = "\t".'<meta name="keywords" content="' . $keywords . '" />' . "\n";
        echo $keywords;
    }
}

function suffusion_js_initializer() {
    global $suf_nav_delay, $suf_nav_effect, $suf_navt_delay, $suf_navt_effect, $suf_featured_interval, $suf_featured_fx, $suf_featured_transition_speed, $suf_featured_sync, $suf_jq_masonry_enabled;
    global $suf_featured_category_view, $suf_featured_tag_view, $suf_featured_search_view;
    global $suf_featured_author_view, $suf_featured_time_view, $suf_featured_index_view, $suf_featured_pages_with_fc, $suf_mag_featured_enabled;

    if ($suf_nav_delay == '') {
        $delay = "0";
    }
    else {
        $delay = $suf_nav_delay;
    }

	if ($suf_navt_delay == '') {
		$delay_top = "0";
	}
	else {
		$delay_top = $suf_navt_delay;
	}
?>
    <script type='text/javascript'>
        //Menu effects
        var suf_nav_delay = <?php echo $delay; ?>;
        var suf_nav_effect = "<?php echo $suf_nav_effect;?>";
        var suf_navt_delay = <?php echo $delay_top; ?>;
        var suf_navt_effect = "<?php echo $suf_navt_effect;?>";
        var suf_jq_masonry_enabled = "<?php echo $suf_jq_masonry_enabled; ?>";
<?php
	if ((is_category() && $suf_featured_category_view == "enabled") || (is_tag() && $suf_featured_tag_view == "enabled") ||
		(is_search() && $suf_featured_search_view == "enabled") || (is_author() && $suf_featured_author_view == "enabled") ||
		(is_page_template('magazine.php') && $suf_mag_featured_enabled == 'enabled') ||
        (is_page() && $suf_featured_pages_with_fc != '') ||
		((is_date() || is_year() || is_month() || is_day() || is_time()) && $suf_featured_time_view == "enabled") ||
		(!(is_category() || is_tag() || is_search() || is_author() || is_date() || is_year() || is_month() || is_day() || is_time() || is_page_template('magazine.php') || is_page()) && $suf_featured_index_view == "enabled")) {
        $pause = __('Pause', 'suf_theme');
        $resume = __('Resume', 'suf_theme');
?>
        //Featured content
        var suf_featured_interval = <?php echo $suf_featured_interval; ?>;
        var suf_featured_transition_speed = <?php echo $suf_featured_transition_speed; ?>;
        var suf_featured_fx = '<?php echo $suf_featured_fx; ?>';
        var suf_featured_pause = '<?php echo $pause; ?>';
        var suf_featured_resume = '<?php echo $resume; ?>';
		var suf_featured_sync = <?php echo $suf_featured_sync; ?>;
<?php
    }
?>
    </script>
<?php
}

function suffusion_author_information() {
    global $suf_uprof_post_info_enabled, $suf_uprof_post_info_header, $suf_uprof_post_info_content, $suf_uprof_post_info_gravatar;
    global $suf_uprof_post_info_gravatar_size;
	if (is_singular()) {
		$ret = "";
		if ($suf_uprof_post_info_enabled == 'bottom' || (is_page() && $suf_uprof_post_info_enabled == 'pages') || (!is_page() && $suf_uprof_post_info_enabled == 'posts')) {
			$ret = "<div class='author-info fix'>\n";
			if (trim($suf_uprof_post_info_header) != '') {
				$header = stripslashes($suf_uprof_post_info_header);
				$header = do_shortcode($header);
				$ret .= "<h4>".$header."</h4>\n";
			}
			if ($suf_uprof_post_info_gravatar == 'show') {
				$ret .= get_avatar(get_the_author_meta('user_email'), "$suf_uprof_post_info_gravatar_size");
			}
			if (trim($suf_uprof_post_info_content) != '') {
				$body = stripslashes($suf_uprof_post_info_content);
				$body = do_shortcode($body);
				$ret .= $body;
			}
			$ret .= "</div>\n";
		}
		echo $ret;
	}
}

function suffusion_get_post_thumbnail($excerpt_or_content = 'excerpt') {
	global $post, $suf_excerpt_thumbnail_alignment, $suf_excerpt_thumbnail_size, $suf_excerpt_thumbnail_custom_width, $suf_excerpt_thumbnail_custom_height;

	if ($suf_excerpt_thumbnail_size == 'custom') {
		$width = get_size_from_field($suf_excerpt_thumbnail_custom_width, '200px');
		$width = (int)(substr($width, 0, strlen($width) - 2));
		$height = get_size_from_field($suf_excerpt_thumbnail_custom_height, '200px');
		$height = (int)(substr($height, 0, strlen($height) - 2));
		$size = array($width, $height);
	}
	else {
		$size = $suf_excerpt_thumbnail_size;
	}

	if (function_exists('get_the_post_thumbnail') && has_post_thumbnail()) {
		$img = get_the_post_thumbnail($post->ID, $size);
		if ($excerpt_or_content != 'content') {
			$img = "<a href=\"".get_permalink($post->ID)."\">".$img."</a>";
		}
		return "<div class='$suf_excerpt_thumbnail_alignment-thumbnail'>".$img."</div>";
	}
	else {
		return "";
	}
}

function suffusion_include_favicon() {
	global $suf_favicon_path;
	if (trim($suf_favicon_path) != '') {
		echo "<link rel='shortcut icon' href='$suf_favicon_path' />\n";
	}
}

function suffusion_is_sidebar_empty($index) {
	$sidebars = wp_get_sidebars_widgets();
	if (!isset($sidebars['sidebar-'.$index]) || $sidebars['sidebar-'.$index] == null || (is_array($sidebars['sidebar-'.$index]) && count($sidebars['sidebar-'.$index]) == 0)) {
		return true;
	}
	return false;
}

function suffusion_sidebar_widget_count($index) {
	$sidebars = wp_get_sidebars_widgets();
	if (!isset($sidebars['sidebar-'.$index]) || $sidebars['sidebar-'.$index] == null || (is_array($sidebars['sidebar-'.$index]) && count($sidebars['sidebar-'.$index]) == 0)) {
		return 0;
	}
	return count($sidebars['sidebar-'.$index]);
}

function suffusion_display_open_header() {
	global $suf_header_layout_style;
	$display = apply_filters('suffusion_can_display_open_header', true);
	if (!$display) {
		return;
	}
	if ($suf_header_layout_style != 'in-align') {
		if ($suf_header_layout_style  == 'out-hcalign' || $suf_header_layout_style  == 'out-cfull-halign') {
			suffusion_display_top_navigation();
			suffusion_display_widgets_above_header();
		}
?>
		<div id="header-container" class="fix">
			<div class='col-control fix'>
<?php
		if ($suf_header_layout_style  == 'out-hcfull') {
		//if ($suf_header_layout_style  != 'out-hcalign' && $suf_header_layout_style  != 'out-cfull-halign') {
			suffusion_display_top_navigation();
			suffusion_display_widgets_above_header();
		}
		suffusion_display_header();
		if ($suf_header_layout_style  == 'out-hcfull') {
		//if ($suf_header_layout_style  != 'out-hcalign' && $suf_header_layout_style  != 'out-cfull-halign') {
			suffusion_display_main_navigation();
		}
	?>
			</div>
		</div><!-- //#header-container -->
<?php
		if ($suf_header_layout_style  == 'out-hcalign' || $suf_header_layout_style  == 'out-cfull-halign') {
			suffusion_display_main_navigation();
		}
	}
	else {
		suffusion_display_top_navigation();
		suffusion_display_widgets_above_header();
	}
}

function suffusion_display_closed_header() {
	global $suf_header_layout_style;
	$display = apply_filters('suffusion_can_display_closed_header', true);
	if (!$display) {
		return;
	}
	if ($suf_header_layout_style == 'in-align') {
?>
			<div id="header-container" class="fix">
				<?php
					suffusion_page_header();
				?>
			</div><!-- //#header-container -->
<?php
	}
}

/**
 * Based on the Image Rotator script by Matt Mullenweg > http://photomatt.net
 * Inspired by Dan Benjamin > http://hiveware.com/imagerotator.php
 * Latest version always at: http://photomatt.net/scripts/randomimage
 *
 * Make the folder the relative path to the images, like "../img" or "random/images/".
 *
 * Modifications by Sayontan Sinha, to dynamically pass the folder for images.
 * This cannot exist as a standalone file, because it loads outside the context of WP, so variables such as folder names cannot be fetched by the file automatically.
 */
function suffusion_get_rotating_image($folder) {
	// Space seperated list of extensions, you probably won't have to change this.
	$exts = 'jpg jpeg png gif';

	$files = array(); $i = -1; // Initialize some variables
//	if ('' == $folder) $folder = './';
	$content_folder = WP_CONTENT_DIR."/".$folder;

	$handle = opendir($content_folder);
	$exts = explode(' ', $exts);
	while (false !== ($file = readdir($handle))) {
		foreach($exts as $ext) { // for each extension check the extension
			if (preg_match('/\.'.$ext.'$/i', $file, $test)) { // faster than ereg, case insensitive
				$files[] = $file; // it's good
				++$i;
			}
		}
	}
	closedir($handle); // We're not using it anymore
	mt_srand((double)microtime()*1000000); // seed for PHP < 4.2
	$rand = mt_rand(0, $i); // $i was incremented as we went along
	return WP_CONTENT_URL."/".$folder."/".$files[$rand];
}

function suffusion_register_jquery() {
	global $suf_cdn_jquery_enabled, $suf_featured_use_lite, $suf_js_in_footer;
	$footer = $suf_js_in_footer == 'footer' ? true : false;
	if ($suf_cdn_jquery_enabled == 'cdn' && !is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js', array(), null, $footer);
	}

	if ($suf_featured_use_lite == 'lite') {
		wp_register_script('jquery-cycle', get_template_directory_uri() . '/scripts/jquery.cycle.lite.min.js', array('jquery'), null, $footer);
	}
	else {
		wp_register_script('jquery-cycle', get_template_directory_uri() . '/scripts/jquery.cycle.all.min.js', array('jquery'), null, $footer);
	}
}

function suffusion_include_bp_admin_css() {
	if (function_exists('bp_is_group')) {
?>
		<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/buddypress/bp-themes/bp-default/_inc/css/adminbar.css" type="text/css" media="all"/>
<?php
	}
}

function suffusion_pad_signup_form_start() {
?>
<div id="main-col">
<?php
}

function suffusion_pad_signup_form_end() {
?>
</div><!-- #main-col -->
<?php
}

function suffusion_register_custom_types() {
	if (!function_exists('register_post_type') || !function_exists('register_taxonomy')) {
		return;
	}
	global $post_type_labels, $post_type_args, $post_type_supports, $taxonomy_labels, $taxonomy_args;
	$suffusion_post_types = get_option('suffusion_post_types');
	$suffusion_taxonomies = get_option('suffusion_taxonomies');
	if (is_array($suffusion_post_types)) {
		foreach ($suffusion_post_types as $id => $suffusion_post_type) {
			$args = array();
			$labels = array();
			$supports = array();
			foreach ($post_type_labels as $label) {
				if (isset($suffusion_post_type['labels'][$label['name']]) && $suffusion_post_type['labels'][$label['name']] != '') {
					$labels[$label['name']] = $suffusion_post_type['labels'][$label['name']];
				}
			}
			foreach ($post_type_supports as $support) {
				if (isset($suffusion_post_type['supports'][$support['name']])) {
					if ($suffusion_post_type['supports'][$support['name']] == '1') {
						$supports[] = $support['name'];
					}
				}
			}
			foreach ($post_type_args as $arg) {
				if (isset($suffusion_post_type['args'][$arg['name']])) {
					if ($arg['type'] == 'checkbox' && $suffusion_post_type['args'][$arg['name']] == '1') {
						$args[$arg['name']] = true;
					}
					else if ($arg['type'] != 'checkbox') {
						$args[$arg['name']] = $suffusion_post_type['args'][$arg['name']];
					}
				}
			}
			$args['labels'] = $labels;
			$args['supports'] = $supports;
			register_post_type($suffusion_post_type['post_type'], $args);
		}
	}

	if (is_array($suffusion_taxonomies)) {
		foreach ($suffusion_taxonomies as $id => $suffusion_taxonomy) {
			$labels = array();
			$args = array();
			foreach ($taxonomy_labels as $label) {
				if (isset($suffusion_taxonomy['labels'][$label['name']]) && $suffusion_taxonomy['labels'][$label['name']] != '') {
					$labels[$label['name']] = $suffusion_taxonomy['labels'][$label['name']];
				}
			}
			foreach ($taxonomy_args as $arg) {
				if (isset($suffusion_taxonomy['args'][$arg['name']])) {
					if ($arg['type'] == 'checkbox' && $suffusion_taxonomy['args'][$arg['name']] == '1') {
						$args[$arg['name']] = true;
					}
					else if ($arg['type'] != 'checkbox') {
						$args[$arg['name']] = $suffusion_taxonomy['args'][$arg['name']];
					}
				}
			}
			$args['labels'] = $labels;
			if (function_exists('post_type_exists')) {
				$object_type_str = $suffusion_taxonomy['object_type'];
				$object_type_array = explode(',',$object_type_str);
				$object_types = array();
				foreach ($object_type_array as $object_type) {
					if (post_type_exists(trim($object_type))) {
						$object_types[] = trim($object_type);
					}
				}
				register_taxonomy($suffusion_taxonomy['taxonomy'], $object_types, $args);
			}
		}
	}
}

function suffusion_include_js() {
	global $compress_scripts, $concatenate_scripts, $suf_js_gzip_enabled;
	if ($suf_js_gzip_enabled == 'gzip') {
		$compress_scripts = 1;
		$concatenate_scripts = 1;
		define('ENFORCE_GZIP', true);
	}

	suffusion_include_featured_js();
	suffusion_include_jqfix_js();
	suffusion_include_google_translate_js();
	suffusion_include_bp_js();
	suffusion_include_custom_js_files();
	if (suffusion_should_include_dbx()) {
		wp_enqueue_script('dbx', get_template_directory_uri() . '/dbx.js', array(), null, true);
	}
}

function suffusion_display_widgets_above_header() {
	$display = apply_filters('suffusion_can_display_widgets_above_header', true);
	if (!$display) {
		return;
	}
	if (!suffusion_is_sidebar_empty(11)) {
?>
		<!-- #widgets-above-header -->
		<div id="widgets-above-header" class='warea fix'>
			<div class='col-control'>
<?php
		dynamic_sidebar('Widgets Above Header');
?>
			</div>
		</div>
		<!-- /#widgets-above-header -->
<?php
	}
}

function suffusion_display_widgets_in_header() {
	if (!suffusion_is_sidebar_empty(12)) {
?>
		<!-- #header-widgets -->
		<div id="header-widgets" class="warea">
<?php
		dynamic_sidebar('Header Widgets');
?>
		</div>
		<!-- /#header-widgets -->
<?php
	}
}

/**
 * Function to support meus from the Menu dashboard.
 * Strictly speaking this is not required. You could select these same menus from the Main Navigation Bar Setup or Top Navigation Bar Setup.
 *
 * @return void
 */
function suffusion_register_menus() {
	if (function_exists('register_nav_menu')) {
		register_nav_menu('top', 'Top Navigation Bar');
		register_nav_menu('main', 'Main Navigation Bar');
	}
}

function suffusion_include_google_translate_js() {
    if (!is_admin() && is_active_widget('Suffusion_Google_Translator', false, 'suf-google-translator', true)) {
	    // For some reason the translation widget fails if we load the JS in the header. Hence we are overriding the header/footer JS setting
	    wp_register_script('google-translate', 'http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', array(), null, true);
	    wp_enqueue_script('google-translate');
    }
}

function suffusion_should_include_dbx() {
	global $suf_sidebar_count, $suf_sidebar_1_dnd, $suf_sidebar_2_dnd;
	if (is_page_template('no-sidebars.php') || ($suf_sidebar_count == 0 && !(is_page_template('1l-sidebar.php') || is_page_template('1r-sidebar.php') || is_page_template('1l1r-sidebar.php') || is_page_template('2l-sidebars.php') || is_page_template('2r-sidebars.php')))||
		($suf_sidebar_count == 1 && $suf_sidebar_1_dnd != "enabled"  && !(is_page_template('1l1r-sidebar.php') || is_page_template('2l-sidebars.php') || is_page_template('2r-sidebars.php'))) ||
		($suf_sidebar_1_dnd != "enabled" && $suf_sidebar_2_dnd != "enabled")) {
		return false;
	}
	else {
		return true;
	}
}

function suffusion_check_custom_css() {
	global $suf_autogen_css;
	if ($suf_autogen_css == 'autogen') {
		$options_from_db = get_option('suffusion_unified_options');
		if (isset($options_from_db)) {
			if (isset($options_from_db['suffusion_options_version'])) {
				$suffusion_options_version = $options_from_db['suffusion_options_version'];
				$suffusion_theme = get_current_theme(); // Need this because a child theme might be getting used.
				$suffusion_theme_data = get_theme($suffusion_theme);
				if (isset($suffusion_theme_data['Version'])) {
					$suffusion_theme_version = $suffusion_theme_data['Version'];
				}
				else {
					$suffusion_theme_version = "1.0";
				}
				if ($suffusion_options_version != $suffusion_theme_version) {
					suffusion_create_or_update_custom_css(true);
				}
				else {
					suffusion_create_or_update_custom_css(false);
				}
			}
			else {
				suffusion_create_or_update_custom_css(true);
			}
		}
		else {
			// This will effectively reset the options each time upon init. This will cause overhead.
			// However without this we run the risk of not seeing the autogen file updated upon activation of a new version of the theme.
			suffusion_create_or_update_custom_css(true);
		}
	}
}

function suffusion_create_or_update_custom_css($reset = false) {
	$autogen_file_path = suffusion_get_custom_css_location();
	if ($autogen_file_path != false) {
		if (!file_exists($autogen_file_path) || $reset) {
			$created = touch($autogen_file_path);
			if (!$created) {
				return false;
			}
			$created = chmod($autogen_file_path, 0777);
			if (!$created) {
				return false;
			}
		}
		else if (file_exists($autogen_file_path) && !$reset) {
			return true;
		}

		ob_start();
		include_once(TEMPLATEPATH."/custom-styles.php");
		$content = ob_get_contents();
		ob_end_clean();
		$css_file = fopen($autogen_file_path, 'w');
		fwrite($css_file, $content);
		fclose($css_file);
		return true;
	}
	return false;
}

function suffusion_include_bp_js() {
	global $suf_js_in_footer;
	$footer = $suf_js_in_footer == 'footer' ? true : false;

	if (!is_admin() && function_exists('bp_is_group')) {
		wp_enqueue_script('suffusion-bp-ajax-js', WP_PLUGIN_URL . '/buddypress/bp-themes/bp-default/_inc/global.js', array('jquery'), null, $footer);
	}
}

function suffusion_meta_pullout() {
	global $post, $suf_post_meta_position, $suf_page_meta_position;
	if ($suf_post_meta_position != 'corners' && ((!is_singular() && $post->post_type != 'page') || (is_singular() && !is_page()))) {
		suffusion_meta_pullout_for_post();
	}

	if ($suf_page_meta_position != 'corners' && (is_page() || (!is_singular() && $post->post_type == 'page'))) {
		suffusion_meta_pullout_for_page();
	}
}

function suffusion_meta_pullout_for_post() {
	global $post, $suf_post_meta_position, $suf_post_show_cats, $suf_post_show_posted_by, $suf_post_show_tags, $suf_date_box_show, $suf_post_show_comment;
	echo "<div class='meta-pullout meta-$suf_post_meta_position'>\n";
	echo "<ul>\n";

	if ($suf_date_box_show != 'hide' || ($suf_date_box_show == 'hide-search' && !is_search())) {
		echo "<li><span class='pullout-date'>".get_the_time(get_option('date_format'))."</span></li>\n";
	}

	if ($suf_post_show_posted_by != 'hide') {
			knowledgeblog_authors('pullout');
	}

	if ($suf_post_show_comment != 'hide') {
		if ('open' == $post->comment_status && is_singular()) {
			echo "<li><span class='comments'><a href='#respond'>".__('Add comments', 'suf_theme')."</a></span></li>\n";
		}
		else {
			echo "<li><span class='comments'>";
			comments_popup_link(__('No Responses', 'suf_theme'), __('1 Response', 'suf_theme'), __('% Responses', 'suf_theme'));
			echo "</span></li>\n";
		}
	}

	if ($suf_post_show_cats != 'hide') {
		$categories = get_the_category($post->ID);
		if ($categories) {
			echo "<li><span class='category'>";
			the_category(', ');
			echo "</span></li>\n";
		}
	}

	if ($suf_post_show_tags != 'hide') {
		$tags = get_the_tags($post->ID);
		if ($tags != '') {
			echo "<li><span class='tags'>";
			the_tags(__('Tagged with: ', 'suf_theme'),', ');
			echo "</span></li>\n";
		}
	}

	if (is_singular() && get_edit_post_link() != '') {
		echo "<li><span class='edit'>";
?>
		   <?php edit_post_link(__('Edit', 'suf_theme'), '', ''); ?>
<?php
		echo "</span></li>\n";
	}

	echo "</div>\n";
}

function suffusion_meta_pullout_for_page() {
	global $post, $suf_page_meta_position, $suf_page_show_posted_by, $suf_page_show_comment;
	echo "<div class='meta-pullout meta-$suf_page_meta_position'>\n";
	echo "<ul>\n";

	if ($suf_page_show_posted_by != 'hide') {
			knowledgeblog_authors('list');
		/*
        */
	}

	if ($suf_page_show_comment != 'hide') {
		if ('open' == $post->comment_status) {
			echo "<li><span class='comments'><a href='#respond'>".__('Add comments', 'suf_theme')."</a></span></li>\n";
		}
	}

	if (get_edit_post_link() != '') {
		echo "<li><span class='edit'>";
?>
		   <?php edit_post_link(__('Edit', 'suf_theme'), '', ''); ?>
<?php
		echo "</span></li>\n";
	}

	echo "</div>\n";
}
?>
