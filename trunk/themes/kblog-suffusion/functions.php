<?php
/**
 * Core functions file for the theme. Includes other key files.
 *
 * @package Suffusion
 * @subpackage Functions
 */

$locale = get_locale();
load_textdomain('suf_theme', locate_template(array("translation/{$locale}.mo", "{$locale}.mo")));

if (!isset($content_width)) $content_width = 725;
$themename = "Suffusion";
$shortname = "suf";
$suffusion_reevaluate_styles = false;
// Option names
$color_scheme = $shortname."_color_scheme";
$sidebar_header = $shortname."_sidebar_header";
$show_shadows = $shortname."_show_shadows";
$body_style_setting = $shortname."_body_style_setting";
$body_font_style_setting = $shortname."_body_font_style_setting";
$header_style_setting = $shortname."_header_style_setting";

$font_faces = array (
	"Arial, Helvetica, sans-serif" => "<font face=\"Arial, Helvetica, sans-serif\">Arial, <span class='mac'>Arial, Helvetica,</span> <i>sans-serif</i></font>",
	"'Arial Black', Gadget, sans-serif" => "<font face=\"'Arial Black', Gadget, sans-serif\">Arial Black, <span class='mac'>Arial Black, Gadget,</span> <i>sans-serif</i></font>",
	"'Comic Sans MS', cursive" => "<font face=\"'Comic Sans MS', cursive\">Comic Sans MS, <span class='mac'>Comic Sans MS,</span> <i>cursive</i></font>",
	"'Courier New', Courier, monospace " => "<font face=\"'Courier New', Courier, monospace\">Courier New, <span class='mac'>Courier New, Courier,</span> <i>monospace</i></font>",
	"Georgia, serif" => "<font face=\"Georgia, serif\">Georgia, <span class='mac'>Georgia,</span> <i>serif</i></font>",
	"Impact, Charcoal, sans-serif" => "<font face=\"Impact, Charcoal, sans-serif\">Impact, <span class='mac'>Impact, Charcoal,</span> <i>sans-serif</i></font>",
	"'Lucida Console', Monaco, monospace" => "<font face=\"'Lucida Console', Monaco, monospace\">Lucida Console, <span class='mac'>Monaco,</span> <i>monospace</i></font>",
	"'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "<font face=\"'Lucida Sans Unicode', 'Lucida Grande', sans-serif\">Lucida Sans Unicode, <span class='mac'>Lucida Grande,</span> <i>sans-serif</i></font>",
	"'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "<font face=\"'Palatino Linotype', 'Book Antiqua', Palatino, serif\">Palatino Linotype, Book Antiqua, <span class='mac'>Palatino,</span> <i>serif</i></font>",
	"Tahoma, Geneva, sans-serif" => "<font face=\"Tahoma, Geneva, sans-serif\">Tahoma, <span class='mac'>Geneva,</span> <i>sans-serif</i></font>",
	"'Times New Roman', Times, serif" => "<font face=\"'Times New Roman', Times, serif\">Times New Roman, <span class='mac'>Times,</span> <i>serif</i></font>",
	"'Trebuchet MS', Helvetica, sans-serif" => "<font face=\"'Trebuchet MS', Helvetica, sans-serif\">Trebuchet MS, <span class='mac'>Helvetica,</span> <i>sans-serif</i></font>",
	"Verdana, Geneva, sans-serif" => "<font face=\"Verdana, Geneva, sans-serif\">Verdana, <span class='mac'>Verdana, Geneva,</span> <i>sans-serif</i></font>",
	"Symbol" => "<font face=\"Symbol\">Symbol, <span class='mac'>Symbol</span></font> (\"Symbol\" works in IE and Chrome on Windows and in Safari on Mac)",
	"Webdings" => "<font face=\"Webdings\">Webdings, <span class='mac'>Webdings</span></font> (\"Webdings\" works in IE and Chrome on Windows and in Safari on Mac)",
	"Wingdings, 'Zapf Dingbats'" => "<font face=\"Wingdings, 'Zapf Dingbats'\">Wingdings, <span class='mac'>Zapf Dingbats</span></font> (\"Wingdings\" works in IE and Chrome on Windows and in Safari on Mac)",
	"'MS Sans Serif', Geneva, sans-serif" => "<font face=\"'MS Sans Serif', Geneva, sans-serif\">MS Sans Serif, <span class='mac'>Geneva,</span> <i>sans-serif</i></font>",
	"'MS Serif', 'New York', serif" => "<font face=\"'MS Serif', 'New York', serif\">MS Serif, <span class='mac'>New York,</span> <i>serif</i></font>",
);

$suffusion_options = get_option('suffusion_options');
$theme_name = suffusion_get_theme_name();
$default_theme_name = "dark-theme-green";
$suffusion_rtl_layout = false;

$all_page_ids = get_all_page_ids();
$all_page_ids = implode(',',$all_page_ids);
$all_category_ids = get_all_category_ids();
$all_category_ids = implode(',',$all_category_ids);

//Essential for multi-selects. Do not delete!!! And do not move it below the next statement!!!
$spawned_options = array();
$page_array = null;
$category_array = null;

// Global variables
$SUFFUSION_COMMENT_TYPES = array('comment' => __('Comments', 'suf_theme'), 'trackback' => __('Trackbacks', 'suf_theme'), 'pingback' => __('Pingbacks', 'suf_theme'));

$sidebar_tabs = array(
	'archives' => array('title' => __('Archives', 'suf_theme')),
	'categories' => array('title' => __('Categories', 'suf_theme')),
	'links' => array('title' => __('Links', 'suf_theme')),
	'meta' => array('title' => __('Meta', 'suf_theme')),
	'pages' => array('title' => __('Pages', 'suf_theme')),
	'recent_comments' => array('title' => __('Recent Comments', 'suf_theme')),
	'recent_posts' => array('title' => __('Recent Posts', 'suf_theme')),
	'search' => array('title' => __('Search', 'suf_theme')),
	'tag_cloud' => array('title' => __('Tag Cloud', 'suf_theme')),
	'custom_tab_1' => array('title' => __('Custom Tab 1', 'suf_theme')),
	'custom_tab_2' => array('title' => __('Custom Tab 2', 'suf_theme')),
	'custom_tab_3' => array('title' => __('Custom Tab 3', 'suf_theme')),
	'custom_tab_4' => array('title' => __('Custom Tab 4', 'suf_theme')),
	'custom_tab_5' => array('title' => __('Custom Tab 5', 'suf_theme')),
	'custom_tab_6' => array('title' => __('Custom Tab 6', 'suf_theme')),
	'custom_tab_7' => array('title' => __('Custom Tab 7', 'suf_theme')),
	'custom_tab_8' => array('title' => __('Custom Tab 8', 'suf_theme')),
	'custom_tab_9' => array('title' => __('Custom Tab 9', 'suf_theme')),
	'custom_tab_10' => array('title' => __('Custom Tab 10', 'suf_theme')),
);

$suffusion_404_title =  __("Error 404 - Not Found", "suf_theme");
$suffusion_404_content = __("Sorry, the page that you are looking for does not exist.", "suf_theme");

$suffusion_comment_label_name = __('Name', "suf_theme");
$suffusion_comment_label_req = __('(required)', "suf_theme");
$suffusion_comment_label_email = __('E-mail', "suf_theme");
$suffusion_comment_label_uri = __('URI', "suf_theme");
$suffusion_comment_label_your_comment = __('Your Comment', "suf_theme");

$social_networks = array('twitter' => 'Twitter',
                'facebook' => 'Facebook',
                'technorati' => 'Technorati',
                'linkedin' => "LinkedIn",
                'flickr' => 'Flickr',
                'delicious' => 'Delicious',
                'digg' => 'Digg',
                'stumbleupon' => 'StumbleUpon',
                'reddit' => "Reddit");

$suffusion_sitemap_entities = array(
	'pages' => array('title' => 'Pages', 'opt' => '_pages'),
	'categories' => array('title' => 'Categories', 'opt' => '_categories'),
	'authors' => array('title' => 'Authors', 'opt' => '_authors'),
	'years' => array('title' => 'Yearly Archives', 'opt' => '_yarchives'),
	'months' => array('title' => 'Monthly Archives', 'opt' => '_marchives'),
	'weeks' => array('title' => 'Weekly Archives', 'opt' => '_warchives'),
	'days' => array('title' => 'Daily Archives', 'opt' => '_darchives'),
	'tag-cloud' => array('title' => 'Tags', 'opt' => '_tags'),
	'posts' => array('title' => 'Blog Posts', 'opt' => '_posts'),
);

$all_sitemap_entities = array_keys($suffusion_sitemap_entities);
$all_sitemap_entities = implode(',', $all_sitemap_entities);

include_once (TEMPLATEPATH . "/admin/theme-definitions.php");
if (is_admin()) {
	include_once (TEMPLATEPATH . "/admin/theme-options.php");
}
$suffusion_unified_options = suffusion_get_unified_options(true);

$post_type_options = array(
	array('name' => 'post_type', 'type' => 'text', 'desc' => 'Post Type (e.g. book)', 'std' => '', 'reqd' => true),
	array('name' => 'style_inherit', 'type' => 'select', 'desc' => 'Inherit styles from:', 'std' => 'post',
		'options' => array('post' => 'Post - will get styles for Posts', 'page' => 'Page - will get styles for Pages', 'custom' => 'Custom - define your own styles')),
);

$post_type_labels = array(
	array('name' => 'name', 'type' => 'text', 'desc' => 'Name (e.g. Books)', 'std' => '', 'reqd' => true),
	array('name' => 'singular_name', 'type' => 'text', 'desc' => 'Singular Name (e.g. Book)', 'std' => '', 'reqd' => true),
	array('name' => 'add_new', 'type' => 'text', 'desc' => 'Text for "Add New" (e.g. Add New)', 'std' => ''),
	array('name' => 'add_new_item', 'type' => 'text', 'desc' => 'Text for "Add New Item" (e.g. Add New Book)', 'std' => ''),
	array('name' => 'edit_item', 'type' => 'text', 'desc' => 'Text for "Edit Item" (e.g. Edit Book)', 'std' => ''),
	array('name' => 'new_item', 'type' => 'text', 'desc' => 'Text for "New Item" (e.g. New Book)', 'std' => ''),
	array('name' => 'view_item', 'type' => 'text', 'desc' => 'Text for "View Item" (e.g. View Book)', 'std' => ''),
	array('name' => 'search_items', 'type' => 'text', 'desc' => 'Text for "Search Items" (e.g. Search Books)', 'std' => ''),
	array('name' => 'not_found', 'type' => 'text', 'desc' => 'Text for "Not found" (e.g. No Books Found)', 'std' => ''),
	array('name' => 'not_found_in_trash', 'type' => 'text', 'desc' => 'Text for "Not found in Trash" (e.g. No Books Found in Trash)', 'std' => ''),
	array('name' => 'parent_item_colon', 'type' => 'text', 'desc' => 'Parent Text with a colon (e.g. Book Series:)', 'std' => ''),
);

$post_type_args = array(
	array('name' => 'public', 'desc' => 'Public', 'type' => 'checkbox', 'default' => true),
	array('name' => 'publicly_queryable', 'desc' => 'Publicly Queriable', 'type' => 'checkbox', 'default' => true),
	array('name' => 'show_ui', 'desc' => 'Show UI', 'type' => 'checkbox', 'default' => true),
	array('name' => 'query_var', 'desc' => 'Query Variable', 'type' => 'checkbox', 'default' => true),
	array('name' => 'rewrite', 'desc' => 'Rewrite', 'type' => 'checkbox', 'default' => true),
	array('name' => 'hierarchical', 'desc' => 'Hierarchical', 'type' => 'checkbox', 'default' => true),
	array('name' => 'exclude_from_search', 'desc' => 'Exclude from Search', 'type' => 'checkbox', 'default' => true),
	array('name' => 'show_in_nav_menus', 'desc' => 'Show in Navigation menus', 'type' => 'checkbox', 'default' => true),
	array('name' => 'menu_position', 'desc' => 'Menu Position', 'type' => 'text', 'default' => null),
);

$post_type_supports = array(
	array('name' => 'title', 'desc' => 'Title', 'type' => 'checkbox', 'default' => false),
	array('name' => 'editor', 'desc' => 'Editor', 'type' => 'checkbox', 'default' => false),
	array('name' => 'author', 'desc' => 'Author', 'type' => 'checkbox', 'default' => false),
	array('name' => 'thumbnail', 'desc' => 'Thumbnail', 'type' => 'checkbox', 'default' => false),
	array('name' => 'excerpt', 'desc' => 'Excerpt', 'type' => 'checkbox', 'default' => false),
	array('name' => 'trackbacks', 'desc' => 'Trackbacks', 'type' => 'checkbox', 'default' => false),
	array('name' => 'custom-fields', 'desc' => 'Custom Fields', 'type' => 'checkbox', 'default' => false),
	array('name' => 'comments', 'desc' => 'Comments', 'type' => 'checkbox', 'default' => false),
	array('name' => 'revisions', 'desc' => 'Revisions', 'type' => 'checkbox', 'default' => false),
	array('name' => 'page-attributes', 'desc' => 'Page Attributes', 'type' => 'checkbox', 'default' => false),
);

$taxonomy_options = array(
	array('name' => 'taxonomy', 'type' => 'text', 'desc' => 'Taxonomy (e.g. genres)', 'std' => '', 'reqd' => true),
	array('name' => 'object_type', 'type' => 'text', 'desc' => 'Applicable to post types (comma-separated list e.g. book, movie)', 'std' => '', 'reqd' => true),
);

$taxonomy_labels = array(
	array('name' => 'name', 'type' => 'text', 'desc' => 'Name (e.g. Genres)', 'std' => '', 'reqd' => true),
	array('name' => 'singular_name', 'type' => 'text', 'desc' => 'Singular Name (e.g. Genre)', 'std' => '', 'reqd' => true),
	array('name' => 'search_items', 'type' => 'text', 'desc' => 'Text for "Search Items" (e.g. Search Genres)', 'std' => ''),
	array('name' => 'popular_items', 'type' => 'text', 'desc' => 'Text for "Popular Items" (e.g. Popular Genres)', 'std' => ''),
	array('name' => 'all_items', 'type' => 'text', 'desc' => 'Text for "All Items" (e.g. All Genres)', 'std' => ''),
	array('name' => 'parent_item', 'type' => 'text', 'desc' => 'Parent Item (e.g. Parent Genre)', 'std' => ''),
	array('name' => 'parent_item_colon', 'type' => 'text', 'desc' => 'Parent Item Colon (e.g. Parent Genre:)', 'std' => ''),
	array('name' => 'edit_item', 'type' => 'text', 'desc' => 'Text for "Edit Item" (e.g. Edit Genre)', 'std' => ''),
	array('name' => 'update_item', 'type' => 'text', 'desc' => 'Text for "Update Item" (e.g. Update Genre)', 'std' => ''),
	array('name' => 'add_new_item', 'type' => 'text', 'desc' => 'Text for "Add New Item" (e.g. Add New Genre)', 'std' => ''),
	array('name' => 'new_item_name', 'type' => 'text', 'desc' => 'Text for "New Item Name" (e.g. New Genre Name)', 'std' => ''),
);

$taxonomy_args = array(
	array('name' => 'public', 'desc' => 'Public', 'type' => 'checkbox', 'default' => true),
	array('name' => 'show_ui', 'desc' => 'Show UI', 'type' => 'checkbox', 'default' => true),
	array('name' => 'show_tagcloud', 'desc' => 'Show in Tagcloud widget', 'type' => 'checkbox', 'default' => true),
	array('name' => 'hierarchical', 'desc' => 'Hierarchical', 'type' => 'checkbox', 'default' => true),
	array('name' => 'rewrite', 'desc' => 'Rewrite', 'type' => 'checkbox', 'default' => true),
);

if (is_admin()) { // The following don't need to be loaded for non-admin screens
	include_once (TEMPLATEPATH . "/admin/suffusion-options-page.php");
	require_once(TEMPLATEPATH.'/admin/theme-options-renderer.php');
}

function suf_get_formatted_page_array($prefix) {
	global $spawned_options, $page_array;
	$ret = array();
	$pages = get_pages('sort_column=menu_order');
    if ($pages != null) {
        foreach ($pages as $page) {
            if (is_null($page_array)) {
                $ret[$page->ID] = array ("title" => $page->post_title, "depth" => count(get_post_ancestors($page)));
            }
            $spawned_options[count($spawned_options)] = array(  "id" => $prefix."_".$page->ID,
                "type" => "checkbox",
                "parent" => $prefix, "std" => "false");
        }
    }
	if ($page_array == null) {
		$page_array = $ret;
		return $ret;
	}
	else {
		return $page_array;
	}
}

function suf_get_formatted_category_array($prefix, $spawn = true) {
	global $spawned_options, $category_array;
	$ret = array();
	$args = array("type" => "post",
		"orderby" => "name",
		"hide_empty" => false,
	);
	$categories = get_categories($args);
	if ($categories == null) { $categories = array(); }
	foreach ($categories as $category) {
		if ($category_array == null) {
			$ret[$category->cat_ID] = array("title" => $category->cat_name);
		}
		if ($spawn) {
			$spawned_options[count($spawned_options)] = array(  "id" => $prefix."_".$category->cat_ID,
														        "type" => "checkbox",
														        "parent" => $prefix,
																"std" => "false");
		}
	}
	if ($category_array == null) {
		$category_array = $ret;
		return $category_array;
	}
	else {
		return $category_array;
	}
}

function suf_get_allowed_categories($prefix) {
	global $suffusion_options;
	$allowed = array();
	if (isset($suffusion_options[$prefix])) {
		$selected = $suffusion_options[$prefix];
		if ($selected && trim($selected) != '') { $selected_categories = explode(',', $selected); } else { $selected_categories = array(); }
		if ($selected_categories && is_array($selected_categories)) {
			foreach ($selected_categories as $category) {
				$allowed[count($allowed)] = get_category($category);
			}
		}
	}
	return $allowed;
}

if (class_exists("WP_Widget")) {
	include_once (TEMPLATEPATH . '/suffusion-widgets.php');
}
include_once (TEMPLATEPATH . '/suffusion-classes.php');

add_action('admin_menu', 'suffusion_add_page_fields');

function suffusion_add_page_fields() {
	add_meta_box('suffusion-page-box', 'Additional Options for Suffusion', 'suffusion_page_extras', 'page', 'normal', 'high');
	add_meta_box('suffusion-post-box', 'Additional Options for Suffusion', 'suffusion_post_extras', 'post', 'normal', 'high');
}

function suffusion_page_extras() {
	global $post;
?>
	<p>
		<label for="suf_alt_page_title"><?php _e("Page Title in Dropdown Menu", "suf_theme"); ?></label><br/>
		<input type="text" id="suf_alt_page_title" name="suf_alt_page_title"
		value="<?php echo get_post_meta($post->ID, "suf_alt_page_title", true); ?>" /> <?php _e("This text will be shown in the drop-down menus in the navigation bar. If left blank, the title of the page is used.", 'suf_theme'); ?>
	</p>
	<p>
		<label for="suf_nav_unlinked"><?php _e("Do not link to this page in the navigation bars", "suf_theme"); ?></label><br/>
		<input type="checkbox" id="suf_nav_unlinked" name="suf_nav_unlinked"
			<?php if (get_post_meta($post->ID, 'suf_nav_unlinked', true)) { echo " checked='checked' ";} ?> />
			<?php _e('If this box is checked, clicking on this page in the navigation bar will not take you anywhere.', 'suf_theme'); ?>
	</p>
	<p>
		<label for="thumbnail"><?php _e("Thumbnail", "suf_theme"); ?></label><br/>
		<input type="text" id="thumbnail" name="thumbnail"
			value="<?php echo get_post_meta($post->ID, "thumbnail", true); ?>" /> <?php _e("Enter the full URL of the thumbnail image that you would like to use, including http://", "suf_theme"); ?>
	</p>
	<p>
		<label for="featured_image"><?php _e("Featured Image", "suf_theme"); ?></label><br/>
		<input type="text" id="featured_image" name="featured_image"
			value="<?php echo get_post_meta($post->ID, "featured_image", true); ?>" /> <?php _e("Enter the full URL of the featured image that you would like to use, including http://", "suf_theme"); ?>
	</p>
	<p>
		<label for="meta_description"><?php _e("Meta Description", "suf_theme"); ?></label><br/>
		<textarea id="meta_description" name="meta_description" cols='80' rows='5'><?php echo get_post_meta($post->ID, "meta_description", true); ?></textarea>
	</p>
	<p>
		<label for="meta_keywords"><?php _e("Meta Keywords", "suf_theme"); ?></label><br/>
		<input type="text" id="meta_keywords" name="meta_keywords" style='width: 500px;'
			value="<?php echo get_post_meta($post->ID, "meta_keywords", true); ?>" /> <?php _e("Enter a comma-separated list of keywords for this post. This list will be included in the meta tags for this post.", "suf_theme"); ?>
	</p>
	<input type='hidden' id='suffusion_post_meta' name='suffusion_post_meta' value='suffusion_post_meta'/>
<?php
}

function suffusion_post_extras() {
	global $post;
?>
	<p>
		<label for="thumbnail"><?php _e("Thumbnail", "suf_theme"); ?></label><br/>
		<input type="text" id="thumbnail" name="thumbnail"
			value="<?php echo get_post_meta($post->ID, "thumbnail", true); ?>" /> <?php _e("Enter the full URL of the thumbnail image that you would like to use, including http://", "suf_theme"); ?>
	</p>
	<p>
		<label for="featured_image"><?php _e("Featured Image", "suf_theme"); ?></label><br/>
		<input type="text" id="featured_image" name="featured_image"
			value="<?php echo get_post_meta($post->ID, "featured_image", true); ?>" /> <?php _e("Enter the full URL of the featured image that you would like to use, including http://", "suf_theme"); ?>
	</p>
	<p>
		<label for="suf_magazine_headline"><?php _e("Make this post a headline", "suf_theme"); ?></label><br/>
		<input type="checkbox" id="suf_magazine_headline" name="suf_magazine_headline"
			<?php if (get_post_meta($post->ID, 'suf_magazine_headline', true)) { echo " checked='checked' ";} ?> />
			<?php _e('If this box is checked, this post will show up as a headline in the magazine template.', 'suf_theme'); ?>
	</p>
	<p>
		<label for="suf_magazine_excerpt"><?php _e("Make this post an excerpt in the magazine layout", "suf_theme"); ?></label><br/>
		<input type="checkbox" id="suf_magazine_excerpt" name="suf_magazine_excerpt"
			<?php if (get_post_meta($post->ID, 'suf_magazine_excerpt', true)) { echo " checked='checked' ";} ?> />
			<?php _e('If this box is checked, this post will show up as an excerpt in the magazine template.', 'suf_theme'); ?>
	</p>
	<p>
		<label for="meta_description"><?php _e("Meta Description", "suf_theme"); ?></label><br/>
		<textarea id="meta_description" name="meta_description" cols='80' rows='5'><?php echo get_post_meta($post->ID, "meta_description", true); ?></textarea>
	</p>
	<p>
		<label for="meta_keywords"><?php _e("Meta Keywords", "suf_theme"); ?></label><br/>
		<input type="text" id="meta_keywords" name="meta_keywords" style='width: 500px;'
			value="<?php echo get_post_meta($post->ID, "meta_keywords", true); ?>" /> <?php _e("Enter a comma-separated list of keywords for this post. This list will be included in the meta tags for this post.", "suf_theme"); ?>
	</p>
	<input type='hidden' id='suffusion_post_meta' name='suffusion_post_meta' value='suffusion_post_meta'/>
<?php
}

function suffusion_save_post_fields($post_id) {
	$suffusion_post_fields = array('thumbnail', 'featured_image', 'suf_magazine_headline', 'suf_magazine_excerpt', 'suf_alt_page_title', 'meta_description', 'meta_keywords', 'suf_nav_unlinked');
    if (isset($_POST['suffusion_post_meta'])) {
        foreach ($suffusion_post_fields as $post_field) {
            $data = stripslashes($_POST[$post_field]);
            if (get_post_meta($post_id, $post_field) == '') {
                add_post_meta($post_id, $post_field, $data, true);
            }
            else if ($data != get_post_meta($post_id, $post_field, true)) {
                update_post_meta($post_id, $post_field, $data);
            }
            else if ($data == '') {
                delete_post_meta($post_id, $post_field, get_post_meta($post_id, $post_field, true));
            }
        }
    }
}

add_action("save_post", "suffusion_save_post_fields");
add_action("publish_post", "suffusion_save_post_fields");

function suffusion_export_settings() {
	global $options, $suffusion_options;
	$export = array();
	foreach ($options as $value) {
		if ((isset($value['export']) && $value['export'] == 'ne') || !isset($value['id']) || $value['type'] == 'button') {
			continue;
		}
		if (!isset($suffusion_options[$value['id']]) && isset($value['std'])) {
			$export[$value['id']] = $value['std'];
		}
		else {
			$export[$value['id']] = $suffusion_options[$value['id']];
		}
	}
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename="suffusion-options.php"');
	echo "<?php \n";
	echo "/* Suffusion settings exported on ".date('Y-m-d H:i')." */ \n";
	echo '$suffusion_exported_options = ';
	var_export($export);
	echo ";\n ?>";
	die;
}

add_action('wp_head', 'suf_add_header_contents');
function suf_add_header_contents() {
	suf_create_openid_links();
	suf_create_additional_feeds();
}

add_action('wp_footer', 'suf_add_footer_contents');
function suf_add_footer_contents() {
	suf_create_analytics_contents();
}

// OpenID stuff...
function suf_create_openid_links() {
	global $suf_openid_enabled, $suf_openid_server, $suf_openid_delegate;
	if ($suf_openid_enabled == "enabled") {
		echo "<!-- Start OpenID settings -->\n";
		echo "<link rel=\"openid.server\" href=\"".$suf_openid_server."\" />\n";
		echo "<link rel=\"openid.delegate\" href=\"".$suf_openid_delegate."\" />\n";
		echo "<!-- End OpenID settings -->\n";
	}
}
// ... End OpenID stuff

// Analytics ...
function suf_create_analytics_contents() {
	global $suf_analytics_enabled, $suf_custom_analytics_code;
	if ($suf_analytics_enabled == "enabled") {
		if (trim($suf_custom_analytics_code) != "") {
			echo "<!-- Start Google Analytics -->\n";
			echo stripslashes($suf_custom_analytics_code)."\n";
			echo "<!-- End Google Analytics -->\n";
		}
	}
}
// ... End Analytics

// Additional Feeds ...
function suf_create_additional_feeds() {
	global $suffusion_options;
	echo "<!-- Start Additional Feeds -->\n";
	if (isset($suffusion_options['suf_custom_rss_feed_1']) && trim($suffusion_options['suf_custom_rss_feed_1']) != "") {
		echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".esc_attr($suffusion_options['suf_custom_rss_title_1'])."\" href=\"".$suffusion_options['suf_custom_rss_feed_1']."\" />\n";
	}
	if (isset($suffusion_options['suf_custom_rss_feed_2']) && trim($suffusion_options['suf_custom_rss_feed_2']) != "") {
		echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".esc_attr($suffusion_options['suf_custom_rss_title_2'])."\" href=\"".$suffusion_options['suf_custom_rss_feed_2']."\" />\n";
	}
	if (isset($suffusion_options['suf_custom_rss_feed_3']) && trim($suffusion_options['suf_custom_rss_feed_3']) != "") {
		echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".esc_attr($suffusion_options['suf_custom_rss_title_3'])."\" href=\"".$suffusion_options['suf_custom_rss_feed_3']."\" />\n";
	}
	if (isset($suffusion_options['suf_custom_atom_feed_1']) && trim($suffusion_options['suf_custom_atom_feed_1']) != "") {
		echo "<link rel=\"alternate\" type=\"application/atom+xml\" title=\"".esc_attr($suffusion_options['suf_custom_atom_title_1'])."\" href=\"".$suffusion_options['suf_custom_atom_feed_1']."\" />\n";
	}
	if (isset($suffusion_options['suf_custom_atom_feed_2']) && trim($suffusion_options['suf_custom_atom_feed_2']) != "") {
		echo "<link rel=\"alternate\" type=\"application/atom+xml\" title=\"".esc_attr($suffusion_options['suf_custom_atom_title_2'])."\" href=\"".$suffusion_options['suf_custom_atom_feed_2']."\" />\n";
	}
	if (isset($suffusion_options['suf_custom_atom_feed_3']) && trim($suffusion_options['suf_custom_atom_feed_3']) != "") {
		echo "<link rel=\"alternate\" type=\"application/atom+xml\" title=\"".esc_attr($suffusion_options['suf_custom_atom_title_3'])."\" href=\"".$suffusion_options['suf_custom_atom_feed_3']."\" />\n";
	}
	echo "<!-- End Additional Feeds -->\n";
}
// ... End Additional Feeds

function get_excluded_pages($prefix) {
	global $$prefix;
	$inclusions = $$prefix;

	$all_pages = get_all_page_ids();//get_pages('sort_column=menu_order');
	if ($all_pages == null) {
		$all_pages = array();
	}

    if ($inclusions && trim($inclusions) != '') {
        $include = explode(',', $inclusions);
        $translations = wpml_lang_object_ids($include, 'post');
        foreach ($translations as $translation) {
            $include[count($include)] = $translation;
        }
    }
    else {
        $include = array();
    }

	// First we figure out which pages have to be excluded
	$exclude = array();
	foreach ($all_pages as $page) {
		if (!in_array($page, $include)) {
            $exclude[count($exclude)] = $page;
        }
	}
	// Now we need to figure out if these excluded pages are ancestors of any pages on the list. If so, we remove the descendants
	foreach ($all_pages as $page) {
		$ancestors = get_post_ancestors($page);
		foreach ($ancestors as $ancestor) {
			if (in_array($ancestor, $exclude)) {
				$exclude[count($exclude)] = $page;
			}
		}
	}

	$exclusion_list = implode(",", $exclude);
	return $exclusion_list;
}

function suffusion_get_home_link_html($position) {
	global $suffusion_options;
	$retStr = "";
	$suf_show_home = "";
	$option_name = $position == 'top' ? 'suf_navt_show_home' : 'suf_show_home';
	if (!isset($suffusion_options[$option_name])) {
		$suf_show_home = "none";
	}
	else {
		$suf_show_home = $suffusion_options[$option_name];
	}

    $show_on_front = get_option('show_on_front');
	$class = "";
    if (is_front_page()) {
        $class = " class='current_page_item' ";
    }
    else if (is_home() && $show_on_front == 'posts') {
        $class = " class='current_page_item' ";
    }

	$option_name = $position == 'top' ? 'suf_navt_home_text' : 'suf_home_text';
	$home_link = function_exists('home_url') ? home_url() : get_option("home");
	if (function_exists('icl_get_home_url')) {
		$home_link = icl_get_home_url();
	}
	if ($suf_show_home == "text") {
		if ($suffusion_options[$option_name] === FALSE || $suffusion_options[$option_name] == null) {
			$suf_home_text = "Home";
		}
		else if (trim($suffusion_options[$option_name]) == "") {
			$suf_home_text = "Home";
		}
		else {
			$suf_home_text = trim($suffusion_options[$option_name]);
		}
		$retStr .= "\n\t\t\t\t\t"."<li $class><a href='".$home_link."'>".$suf_home_text."</a></li>";
	}
	else if ($suf_show_home == "icon") {
		$retStr .= "\n\t\t\t\t\t"."<li $class><a href='".$home_link."'><img src='".get_template_directory_uri()."/images/home-light.png' alt='Home' class='home-icon'/></a></li>";
	}
	return $retStr;
}

function create_nav($echo = true, $type = "pages", $position = 'main', $page_option = 'suf_nav_pages', $cats_option = 'suf_nav_cats', $links_option = 'suf_nav_links', $menus_option = 'suf_nav_menus') {
	$retStr = "";
	if ($type == "pages") {
		$retStr = create_nav_for_entities($position, $page_option, $cats_option, $links_option, $menus_option);
	}
	if ($echo) {
		echo $retStr;
	}
	return $retStr;
}

function create_nav_for_entities($position = 'main', $page_option = 'suf_nav_pages', $cats_option = 'suf_nav_cats', $links_option = 'suf_nav_links', $menus_option = 'suf_nav_menus') {
	global $post, $suf_nav_pages_style, $suf_nav_page_tab_title, $suf_nav_page_tab_link, $suf_navt_pages_style, $suf_navt_page_tab_title, $suf_navt_page_tab_link;
	global $suf_nav_cat_style, $suf_nav_cat_tab_title, $suf_nav_cat_tab_link, $suf_navt_cat_style, $suf_navt_cat_tab_title, $suf_navt_cat_tab_link;
	global $suf_nav_links_style, $suf_nav_links_tab_title, $suf_nav_links_tab_link, $suf_navt_links_style, $suf_navt_links_tab_title, $suf_navt_links_tab_link;
	global $suf_nav_entity_order, $suf_navt_entity_order, $suf_nav_pages_all_sel, $suf_nav_cats_all_sel, $suf_nav_links_all_sel, $suf_navt_pages_all_sel, $suf_navt_cats_all_sel, $suf_navt_links_all_sel;
	global $suf_nav_menus_all_sel, $suf_navt_menus_all_sel;

	switch ($position) {
		case 'top':
			$pages_style = $suf_navt_pages_style;
			$page_tab_title = stripslashes($suf_navt_page_tab_title);
			$page_tab_link = $suf_navt_page_tab_link;
			$page_all_sel = $suf_navt_pages_all_sel;
			$cat_style = $suf_navt_cat_style;
			$cat_tab_title = stripslashes($suf_navt_cat_tab_title);
			$cat_tab_link = $suf_navt_cat_tab_link;
			$cat_all_sel = $suf_navt_cats_all_sel;
			$links_style = $suf_navt_links_style;
			$links_tab_title = stripslashes($suf_navt_links_tab_title);
			$links_tab_link = $suf_navt_links_tab_link;
			$link_all_sel = $suf_navt_links_all_sel;
			$menus_all_sel = $suf_navt_menus_all_sel;
			$entity_order = $suf_navt_entity_order;
			break;
		default:
			$pages_style = $suf_nav_pages_style;
			$page_tab_title = stripslashes($suf_nav_page_tab_title);
			$page_tab_link = $suf_nav_page_tab_link;
			$page_all_sel = $suf_nav_pages_all_sel;
			$cat_style = $suf_nav_cat_style;
			$cat_tab_title = stripslashes($suf_nav_cat_tab_title);
			$cat_tab_link = $suf_nav_cat_tab_link;
			$cat_all_sel = $suf_nav_cats_all_sel;
			$links_style = $suf_nav_links_style;
			$links_tab_title = stripslashes($suf_nav_links_tab_title);
			$links_tab_link = $suf_nav_links_tab_link;
			$link_all_sel = $suf_nav_links_all_sel;
			$menus_all_sel = $suf_nav_menus_all_sel;
			$entity_order = $suf_nav_entity_order;
			break;
	}

	global $$page_option, $$cats_option, $$links_option, $$menus_option;

	if (function_exists('wp_nav_menu')) {
		$selected_menus = $$menus_option;
		$menu_args = array(
			'sort_column' => 'menu_order'
		);

		if ($menus_all_sel == 'selected') {
			if (trim($selected_menus) == '') {
				$menus_included = array();
			}
			else {
				$menus_included = explode(',', $selected_menus);
			}
		}

		$menu_locations = array();
		if (function_exists('get_nav_menu_locations')) {
			$menu_locations = get_nav_menu_locations();
		}

		if (isset($menus_included) && isset($menu_locations[$position])) {
			$menu_in_location = $menu_locations[$position];
			if (!in_array($menu_in_location, $menus_included)) {
				$menus_included[] = $menu_in_location;
			}
		}

		if (isset($menus_included)) {
			if (count($menus_included) == 0) {
				$menus_to_show = array();
			}
			else {
				$menu_args['include'] = $menus_included;
				$menus_to_show = wp_get_nav_menus($menu_args);
			}
		}
		else {
			$menus_to_show = wp_get_nav_menus($menu_args);
		}
	}

	$entity_order = suf_get_entity_order($entity_order, 'nav');
	$entity_order = explode(',', $entity_order);
	$processed_menus = array();
	$home_link = suffusion_get_home_link_html($position);
	$ret_str = $home_link;
	foreach ($entity_order as $entity) {
		if ($entity == 'pages') {
			$selected_pages = $$page_option;
			$page_args = array(
				'sort_column' => 'menu_order,post_title',
				'child_of' => 0,
				'echo' => 0,
				'suffusion_nav_display' => $pages_style,
			);

			if ($page_all_sel == 'selected') {
				if (trim($selected_pages) == '') {
					$page_args = array();
				}
				else {
					$page_args['include'] = $selected_pages;
				}
			}
			else if ($page_all_sel == 'exclude-selected') {
				$page_args['exclude'] = $selected_pages;
			}
			else if ($page_all_sel == 'exclude-all') {
				$page_args = array();
			}

			if ($pages_style == 'rolled-up' && count($page_args) != 0) {
				$page_args['title_li'] = "<a href='".($page_tab_link == '' ? '#' : $page_tab_link)."'>".$page_tab_title."</a>";
			}
			else if ($pages_style == 'flattened' && count($page_args) != 0) {
				$page_args['title_li'] = '';
			}
			if (count($page_args) == 0) {
				$page_str = '';
			}
			else {
				$page_str = wp_list_pages($page_args);
			}
			$ret_str .= $page_str;
		}
		else if ($entity == 'categories') {
			$cat_args = array(
				'orderby'            => 'name',
				'order'              => 'ASC',
				'child_of'           => 0,
				'echo'               => 0,
				'current_category'   => 0,
			);

			if (function_exists('mycategoryorder')) {
			    $cat_args['orderby'] = 'order';
			}

			$selected_cats = $$cats_option;
			if ($cat_all_sel == 'selected') {
				if (trim($selected_cats) == '') {
					$cat_args = array();
				}
				else {
					$cat_args['include'] = $selected_cats;
				}
			}
			else if ($cat_all_sel == 'exclude-selected') {
				$cat_args['exclude'] = $selected_cats;
			}
			else if ($cat_all_sel == 'exclude-all') {
				$cat_args = array();
			}

			if ($cat_style == 'rolled-up' && count($cat_args) != 0) {
				$cat_args['title_li'] = "<a href='".($cat_tab_link == '' ? '#' : $cat_tab_link)."'>".$cat_tab_title."</a>";
			}
			else if ($cat_style == 'flattened' && count($cat_args) != 0) {
				$cat_args['title_li'] = '';
			}

			if (count($cat_args) == 0) {
				$cat_str = '';
			}
			else {
				$cat_str = wp_list_categories($cat_args);
			}
			$ret_str .= $cat_str;
		}
		else if ($entity == 'links') {
			$link_args = array(
				'orderby'          => 'name',
				'order'            => 'ASC',
				'limit'            => -1,
				'echo'             => 0,
				'categorize'       => 0,
				'title_before' => '',
				'title_after' => '',
			);
			if (function_exists('mylinkorder')) {
			    $link_args['orderby'] = 'order';
			}

			$selected_links = $$links_option;
			if ($link_all_sel == 'selected') {
				if (trim($selected_links) == '') {
					$link_args = array();
				}
				else {
					$link_args['include'] = $selected_links;
				}
			}
			else if ($link_all_sel == 'exclude-selected') {
				$link_args['exclude'] = $selected_links;
			}
			else if ($link_all_sel == 'exclude-all') {
				$link_args = array();
			}

            if ($links_style == 'rolled-up' && count($link_args) != 0) {
	            $link_args['title_li'] = "<a href='".($links_tab_link == '' ? '#' : $links_tab_link)."'>".$links_tab_title."</a>";
            }
            else if ($links_style == 'flattened' && count($link_args) != 0) {
	            $link_args['title_li'] = '';
            }

			if (count($link_args) == 0) {
				$link_str = '';
			}
			else {
				$link_str = wp_list_bookmarks($link_args);
			}
			$ret_str .= $link_str;
        }
		else if ((strlen($entity) >= 5 && substr($entity, 0, 5) == 'menu-')) {
			if (function_exists('wp_nav_menu') && count($menus_to_show) != 0) {
				$menu_print_args = array(
					'container' => '',
					'menu_class' => 'menu',
					'echo' => false,
					'depth' => 0,
				);
				$menu_str = '';
				$menu_entity_id = (int)substr($entity, 5);
				foreach ($menus_to_show as $menu) {
					if ($menu->term_id != $menu_entity_id) continue;
					$menu_print_args['menu'] = $menu->term_id;
					$this_menu_str = wp_nav_menu($menu_print_args);
					$this_menu_str = preg_replace('/^<ul id="[-a-zA-Z0-9]*" class="menu">/', '', $this_menu_str);
					$this_menu_str = preg_replace('/<\/ul>$/', '', $this_menu_str);
					$menu_str .= $this_menu_str;
				}
				$ret_str .= $menu_str;
			}
		}
	}
	if (trim($ret_str) != '') {
		$ret_str = "<ul class='sf-menu'>\n".$ret_str."\n</ul>\n";
	}
	return $ret_str;
}

/**
 * If more than one page exists, return TRUE.
 */
function show_page_nav() {
	global $wp_query;
	return ($wp_query->max_num_pages > 1);
}
/**
 * If more than one post exists, return TRUE.
 */
function suffusion_post_count() {
	$post_type = get_query_var('post_type');
	if (!isset($post_type) || $post_type == null || $post_type == '') {
		$post_type = 'post';
	}
	$post_count = wp_count_posts($post_type);
	return $post_count->publish;
}

function check_integer($val) {
	if (substr($val, 0, 1) == '-') {
		$val = substr($val, 1);
	}
	return (preg_match('/^\d*$/'  , $val) == 1);
}

function get_size_from_field($val, $default, $allow_blank = true) {
	$ret = $default;
	if (substr(trim($val), -2) == "px") {
		$test_str = trim(substr(trim($val), 0, strlen(trim($val)) - 2));
		if (check_integer($test_str)) {
			$ret = $test_str."px";
		}
	}
	else if (check_integer(trim($val))) {
		if (!$allow_blank) {
			if (trim($val) != '') {
				$ret = trim($val)."px";
			}
		}
		else {
			$ret = trim($val)."px";
		}
	}
	return $ret;
}

function get_numeric_size_from_field($val, $default) {
	$ret = $default;
	if (substr(trim($val), -2) == "px") {
		$test_str = trim(substr(trim($val), 0, strlen(trim($val)) - 2));
		if (check_integer($test_str)) {
			$ret = (int)$test_str;
		}
	}
	else if (check_integer(trim($val))) {
		$ret = (int)(trim($val));
	}
	return $ret;
}

function suffusion_get_formatted_sbtab_array($prefix, $spawn = true) {
	global $sidebar_tabs, $spawned_options;

	foreach ($sidebar_tabs as $tab => $tab_options) {
		$spawned_options[count($spawned_options)] = array('id' => $prefix.'_'.$tab, 'type' => 'checkbox', 'parent' => $prefix, 'std' => 'false');
	}
	return $sidebar_tabs;
}

function suf_get_allowed_pages($prefix) {
	global $suffusion_options;
	$allowed = array();
	if (isset($suffusion_options[$prefix])) {
		$selected = $suffusion_options[$prefix];
		if (!empty($selected)) {
			$selected_pages = explode(',', $selected);
			if (is_array($selected_pages) && count($selected_pages) > 0) {
				foreach ($selected_pages as $page_id) {
					$page = get_page($page_id);
					$allowed[count($allowed)] = $page;
				}
			}
		}
	}
	return $allowed;
}

function suf_get_formatted_link_array($prefix, $spawn = true) {
	global $spawned_options, $link_array;
	$ret = array();
	$args = array(
		"order" => "ASC",
		"orderby" => 'name',
	);
	$links = get_bookmarks($args);
	if ($links == null) {
		$links = array();
	}
	foreach ($links as $link) {
		if ($link_array == null) {
			$ret[$link->link_id] = array("title" => $link->link_name);
		}
		if ($spawn) {
			$spawned_options[count($spawned_options)] = array(  "id" => $prefix."_".$link->link_id,
														        "type" => "checkbox",
														        "parent" => $prefix,
																"std" => "false");
		}
	}
	if ($link_array == null) {
		$link_array = $ret;
		return $link_array;
	}
	else {
		return $link_array;
	}
}

function suf_get_formatted_wp_menu_array($prefix, $spawn = true) {
	global $spawned_options, $menu_array;
	$ret = array();
	if (!function_exists('wp_nav_menu')) {
		return $ret;
	}

	$menus = wp_get_nav_menus();
	if ($menus == null) {
		$menus = array();
	}

	foreach ($menus as $menu) {
		if ($menu_array == null) {
			$ret[$menu->term_id] = array("title" => $menu->name);
		}
		if ($spawn) {
			$spawned_options[count($spawned_options)] = array(  "id" => $prefix."_".$menu->term_id,
														        "type" => "checkbox",
														        "parent" => $prefix,
																"std" => "false");
		}
	}

	if ($menu_array == null) {
		$menu_array = $ret;
		return $menu_array;
	}
	else {
		return $menu_array;
	}
}

/* Functions for WPML compatibility */
function wpml_lang_object_id($id, $type){
    if(function_exists('icl_object_id')) {
        return icl_object_id($id, $type, true);
    } else {
        return $id;
    }
}

function wpml_lang_object_ids($ids_array, $type) {
    if(function_exists('icl_object_id')) {
        $res = array();
        foreach ($ids_array as $id) {
            $xlat = icl_object_id($id, $type, false);
            if (!is_null($xlat) && !in_array($xlat, $res)) {
                $res[] = $xlat;
            }
        }
        return $res;
    }
    else {
        return $ids_array;
    }
}
/* End functions for WPML compatibility */

function suffusion_tab_array_prepositions() {
    global $sidebar_tabs;
    $ret = array();
    foreach ($sidebar_tabs as $key => $value) {
        $ret[count($ret)] = array('key' => $key, 'value' => $value['title']);
    }
    return $ret;
}

function suffusion_nav_entity_prepositions() {
    $ret = array(array('key' => 'pages', 'value' => 'Pages'), array('key' => 'categories', 'value' => 'Categories'), array('key' => 'links', 'value' => 'Links'));
    return $ret;
}

function suf_get_formatted_options_array($prefix, $options_array) {
	global $spawned_options;
	$ret = array();
    foreach ($options_array as $option_key => $option_value) {
        $ret[$option_key] = array('title' => $option_value, 'depth' => 1);
        $spawned_options[] = array('id' => $prefix.'_'.$option_key, 'type' => 'checkbox', 'parent' => $prefix, 'std' => 'false');
    }
    return $ret;
}

function suf_get_entity_order($entity_order, $entity_type='nav') {
    $ret = array();
    if (is_array($entity_order)) {
        foreach ($entity_order as $element) {
            $ret[] = $element['key'];
        }
        $ret = implode(',',$ret);
    }
    else {
        $defaults = suffusion_entity_prepositions($entity_type);
        $ret = explode(',', $entity_order);
        $default_array = array();
        foreach ($defaults as $default) {
            $default_array[] = $default['key'];
            if (!in_array($default['key'], $ret)) {
                $ret[] = $default['key'];
            }
        }
        $ret_array = array();
        foreach ($ret as $ret_entry) {
            if (in_array($ret_entry, $default_array)) {
                $ret_array[] = $ret_entry;
            }
        }
        $ret = implode(',', $ret_array);
    }
    return $ret;
}

function suffusion_update_available($theme) {
    static $themes_update;
    if ( !isset($themes_update) )
        $themes_update = get_transient('update_themes');

    if ( is_object($theme) && isset($theme->stylesheet) )
        $stylesheet = $theme->stylesheet;
    elseif ( is_array($theme) && isset($theme['Stylesheet']) )
        $stylesheet = $theme['Stylesheet'];
    else
        return false; //No valid info passed.

    if (isset($themes_update->response[ $stylesheet ])) {
        return true;
    }
    return false;
}

function suffusion_get_full_content_count() {
	global $suffusion, $suf_category_fc_number, $suf_author_fc_number, $suf_tag_fc_number, $suf_search_fc_number, $suf_archive_fc_number, $suf_index_fc_number, $suf_pop_fc_number;
	$context = $suffusion->get_context();
	$full_post_count = 0;
	if (in_array('category', $context)) {
		$full_post_count = (int)$suf_category_fc_number;
	}
	else if (in_array('author', $context)) {
		$full_post_count = (int)$suf_author_fc_number;
	}
	else if (in_array('tag', $context)) {
		$full_post_count = (int)$suf_tag_fc_number;
	}
	else if (in_array('search', $context)) {
		$full_post_count = (int)$suf_search_fc_number;
	}
	else if (in_array('date', $context)) {
		$full_post_count = (int)$suf_archive_fc_number;
	}
	else if (in_array('home', $context) || in_array('blog', $context)) {
		$full_post_count = (int)$suf_index_fc_number;
	}
	else if (in_array('page', $context)) {
		if (in_array('posts.php', $context)) {
			$full_post_count = (int)$suf_pop_fc_number;
		}
	}
	return $full_post_count;
}

function suffusion_nr_entity_prepositions() {
    $ret = array(array('key' => 'current', 'value' => 'Currently Reading'), array('key' => 'unread', 'value' => 'Not Yet Read'), array('key' => 'completed', 'value' => 'Completed'));
    return $ret;
}

function suffusion_entity_prepositions($entity_type = 'nav') {
	if ($entity_type == 'nav') {
		$ret = array(array('key' => 'pages', 'value' => 'Pages'), array('key' => 'categories', 'value' => 'Categories'), array('key' => 'links', 'value' => 'Links'));
		if (function_exists('wp_get_nav_menus')) {
			$menus = wp_get_nav_menus();
			if ($menus == null) {
				$menus = array();
			}

			foreach ($menus as $menu) {
				$ret[] = array('key' => "menu-".$menu->term_id, "value" => $menu->name);
			}
		}
	}
	else if ($entity_type == 'nr') {
		$ret = array(array('key' => 'current', 'value' => 'Currently Reading'), array('key' => 'unread', 'value' => 'Not Yet Read'), array('key' => 'completed', 'value' => 'Completed'));
	}
	else if ($entity_type == 'mag-layout') {
		$ret = array(array('key' => 'headlines', 'value' => 'Headlines'), array('key' => 'excerpts', 'value' => 'Excerpts'), array('key' => 'categories', 'value' => 'Categories'));
	}
	else if ($entity_type == 'sitemap') {
		global $suffusion_sitemap_entities;
		$ret = array();
		foreach ($suffusion_sitemap_entities as $entity => $entity_options) {
			$ret[] = array('key' => $entity, 'value' => $entity_options['title']);
		}
	}
    return $ret;
}

function suffusion_get_unified_options($update = false, $fetch_from_cache = true) {
	global $options, $suffusion_options, $skin_settings, $theme_name, $default_theme_name, $suf_cache_unified;
	if (!isset($suf_cache_unified) || $suf_cache_unified == 'cache') {
		$unified_options_from_db = get_option('suffusion_unified_options');
		if (isset($unified_options_from_db) && isset($unified_options_from_db['suffusion_options_version']) && $fetch_from_cache) {
			$suffusion_theme = get_current_theme(); // Need this because a child theme might be getting used.
			$suffusion_theme_data = get_theme($suffusion_theme);
			if (isset($suffusion_theme_data['Version'])) {
				$suffusion_theme_version = $suffusion_theme_data['Version'];
			}
			else {
				$suffusion_theme_version = "1.0";
			}
			$options_version = $unified_options_from_db['suffusion_options_version'];
			if ($options_version == $suffusion_theme_version) {
				return $unified_options_from_db;
			}
		}
	}
	include_once (TEMPLATEPATH . "/admin/theme-options.php");
	$theme_name = suffusion_get_theme_name();
	$suffusion_options = get_option('suffusion_options');
	if ($theme_name == 'root') {
		$skin = $default_theme_name;
	}
	else {
		$skin = $theme_name;
	}

	if (file_exists(STYLESHEETPATH."/skins/$skin/settings.php")) {
		include_once(STYLESHEETPATH."/skins/$skin/settings.php");
	}
	else if (file_exists(TEMPLATEPATH."/skins/$skin/settings.php")) {
		include_once(TEMPLATEPATH."/skins/$skin/settings.php");
	}
	$unified_options_array = array();
	foreach ($options as $value) {
		if (isset($value['id'])) {
			if (!isset($suffusion_options[$value['id']])) {
				if (is_array($skin_settings) && isset($skin_settings[$value['id']])) {
					$unified_options_array[$value['id']] = $skin_settings[$value['id']];
				}
				else if (isset($value['std'])) {
					$unified_options_array[$value['id']] = $value['std'];
				}
		    }
			else if (isset($value['std']) && $suffusion_options[$value['id']] == $value['std']) {
				if (is_array($skin_settings) && isset($skin_settings[$value['id']])) {
					$unified_options_array[$value['id']] = $skin_settings[$value['id']];
				}
				else {
					$unified_options_array[$value['id']] = $suffusion_options[$value['id']];
				}
		    }
		    else {
			    $unified_options_array[$value['id']] = $suffusion_options[$value['id']];
		    }
		}
	}
	if ($update && current_user_can('manage_options')) {
		suffusion_set_options_version($unified_options_array);
		update_option('suffusion_unified_options', $unified_options_array);
	}
	return $unified_options_array;
}

/**
 * Attempts to return the location of a CSS file where the customization options of the theme are/will be stored.
 * If the installation is a WP-MS site, a folder called "suffusion" is created in the user-specific files directory (if missing).
 * For regular installations the folder is created in the uploads directory (if missing).
 * The path to autogen-options.css is returned if the file exists or was successfully created. Otherwise "false" is returned.
 *
 * @return bool|string
 */
function suffusion_get_custom_css_location() {
	global $blog_id;
	$folder_queue = array();
	if (defined('MULTISITE') && MULTISITE && isset($blog_id) && $blog_id > 0) {
		$folder_queue = array('blogs.dir', $blog_id, 'files', 'suffusion');
		$suffusion_content_folder = WP_CONTENT_DIR.'/blogs.dir/'.$blog_id.'/files/suffusion/';
	}
	else {
		$folder_queue = array('uploads', 'suffusion');
		$suffusion_content_folder = WP_CONTENT_DIR.'/uploads/suffusion/';
	}

	if (!file_exists($suffusion_content_folder)) {
		$temp_folder = WP_CONTENT_DIR;
		foreach ($folder_queue as $sub_folder) {
			$temp_folder .= '/'.$sub_folder;
			if (!file_exists($temp_folder)) {
				$created = mkdir($temp_folder, 0777); // 777 needed because you want both write and execute permissions on this folder. O/w you get an access error.
				if (!$created) {
					return false;
				}
			}
			else {
				//Set the right permissions
				$perms = fileperms($temp_folder);
				$perms = substr(sprintf('%o', $perms), -4);
				if ($perms != '0777') {
					chmod($temp_folder, 0777);
				}
			}
		}
	}

	$autogen_file_path = $suffusion_content_folder.'autogen-options.css';
	return $autogen_file_path;
}

/**
 * Attempts to return the relative location of a CSS file where the customization options of the theme are/will be stored.
 * The relative path to autogen-options.css is returned if the file exists or was successfully created. Otherwise "false" is returned.
 *
 * @return bool|string
 */
function suffusion_get_custom_css_relative_location() {
	$path = suffusion_get_custom_css_location();
	if ($path == false) return false;
	$file_path = explode(WP_CONTENT_DIR, $path);
	if (is_array($file_path) && count($file_path) > 0) {
		return str_replace(WP_CONTENT_DIR, '../..', $path);
	}
	return false;
}

/**
 * Returns the URL of the custom CSS file. This function is sensitive to an installation being a WP-MS installation or a regular WP installation.
 * The URL to autogen-options.css is returned if the file exists or false is returned.
 *
 * @return bool|string
 */
function suffusion_get_custom_css_url() {
	global $blog_id;
	$path = suffusion_get_custom_css_location();
	if ($path == false) return false;
	if (defined('MULTISITE') && MULTISITE && isset($blog_id) && $blog_id > 0) {
		$path = WP_CONTENT_URL . '/files/suffusion/autogen-options.css';
	}
	else {
		$path = WP_CONTENT_URL . '/uploads/suffusion/autogen-options.css';
	}
	return $path;
}

/**
 * Returns the total memory usage for the script at any point.
 *
 * @param bool $echo Echoes the value if set to true
 * @return string
 */
function suffusion_get_memory_usage($echo = true) {
	$ret = "";
	if (function_exists('memory_get_usage')) {
		$mem = memory_get_usage();
		$unit = "B";
		if ($mem > 1024) {
			$mem = round($mem / 1024);
			$unit = "KB";
			if ($mem > 1024) {
				$mem = round($mem / 1024);
				$unit = "MB";
			}
		}
		$ret = $mem . $unit;
		if ($echo) {
			echo $ret;
		}
	}
	return $ret;
}

/**
 * Returns the name of the skin being used.
 *
 * @return string
 */
function suffusion_get_theme_name() {
    $suffusion_options = get_option('suffusion_options');
    if (!isset($suffusion_options['suf_color_scheme']) || $suffusion_options['suf_color_scheme'] === FALSE || $suffusion_options['suf_color_scheme'] == null || !isset($suffusion_options['suf_color_scheme'])) {
        $theme_name = 'root';
    }
    else {
        $theme_name = $suffusion_options['suf_color_scheme'];
    }
    return $theme_name;
}

/**
 * Sets the version of the theme in the suffusion_unified_options option. Starting from version 3.6.4 of the theme the unified options are stored
 * as a separate option in the database to avoid loading the huge $options array into memory. However, to account for cases where some default values
 * of options are changed or some new options with new default values are introduced, this workaround is required.
 * It stores the version number as a part of the options, so that if the current version is newer than the options version, the options are recalculated
 *
 * @param  $suffusion_unified_options
 * @return void
 * @since 3.6.4
 */
function suffusion_set_options_version(&$suffusion_unified_options) {
	if (is_array($suffusion_unified_options)) {
		$suffusion_theme = get_current_theme(); // Need this because a child theme might be getting used.
		$suffusion_theme_data = get_theme($suffusion_theme);
		$suffusion_theme_version = $suffusion_theme_data['Version'];
		$suffusion_unified_options['suffusion_options_version'] = $suffusion_theme_version;
	}
}

function suffusion_get_formatted_sitemap_contents_array($prefix) {
	global $suffusion_sitemap_entities, $spawned_options;

	foreach ($suffusion_sitemap_entities as $tab => $tab_options) {
		$spawned_options[count($spawned_options)] = array('id' => $prefix.'_'.$tab, 'type' => 'checkbox', 'parent' => $prefix, 'std' => 'false');
	}
	return $suffusion_sitemap_entities;
}

if (!function_exists('suffusion_bp_content_class')) {
	/**
	 * Similar to the post_class() function, but for BP. This is NOT used by core Suffusion, but is useful for child themes using BP.
	 * This might be defined by the Suffusion BuddyPress Pack for BP users of Suffusion, but is included conditionally here so
	 * that the theme and the plugin can be used independently of each other and so that one version of Suffusion can work with an older
	 * version of the BP pack.
	 *
	 * @since 3.6.7
	 * @param bool $custom
	 * @param bool $echo
	 * @return bool|string
	 */
	function suffusion_bp_content_class($custom = false, $echo = true) {
		if (!function_exists('bp_is_group')) return false;

		$css = array();
		$css[] = 'post';
		if (bp_is_profile_component()) $css[] = "profile-component";
		if (bp_is_activity_component()) $css[] = "activity-component";
		if (bp_is_blogs_component()) $css[] = "blogs-component";
		if (bp_is_messages_component()) $css[] = "messages-component";
		if (bp_is_friends_component()) $css[] = "friends-component";
		if (bp_is_groups_component()) $css[] = "groups-component";
		if (bp_is_settings_component()) $css[] = "settings-component";
		if (bp_is_member()) $css[] = "member";
		if (bp_is_user_activity()) $css[] = "user-activity";
		if (bp_is_user_friends_activity()) $css[] = "user-friends-activity";
		if (bp_is_activity_permalink()) $css[] = "activity-permalink";
		if (bp_is_user_profile()) $css[] = "user-profile";
		if (bp_is_profile_edit()) $css[] = "profile-edit";
		if (bp_is_change_avatar()) $css[] = "change-avatar";
		if (bp_is_user_groups()) $css[] = "user-groups";
		if (bp_is_group()) $css[] = "group";
		if (bp_is_group_home()) $css[] = "group-home";
		if (bp_is_group_create()) $css[] = "group-create";
		if (bp_is_group_admin_page()) $css[] = "group-admin-page";
		if (bp_is_group_forum()) $css[] = "group-forum";
		if (bp_is_group_activity()) $css[] = "group-activity";
		if (bp_is_group_forum_topic()) $css[] = "group-forum-topic";
		if (bp_is_group_forum_topic_edit()) $css[] = "group-forum-topic-edit";
		if (bp_is_group_members()) $css[] = "group-members";
		if (bp_is_group_invites()) $css[] = "group-invites";
		if (bp_is_group_membership_request()) $css[] = "group-membership-request";
		if (bp_is_group_leave()) $css[] = "group-leave";
		if (bp_is_group_single()) $css[] = "group-single";
		if (bp_is_user_blogs()) $css[] = "user-blogs";
		if (bp_is_user_recent_posts()) $css[] = "user-recent-posts";
		if (bp_is_user_recent_commments()) $css[] = "user-recent-commments";
		if (bp_is_create_blog()) $css[] = "create-blog";
		if (bp_is_user_friends()) $css[] = "user-friends";
		if (bp_is_friend_requests()) $css[] = "friend-requests";
		if (bp_is_user_messages()) $css[] = "user-messages";
		if (bp_is_messages_inbox()) $css[] = "messages-inbox";
		if (bp_is_messages_sentbox()) $css[] = "messages-sentbox";
		if (bp_is_notices()) $css[] = "notices";
		if (bp_is_messages_compose_screen()) $css[] = "messages-compose-screen";
		if (bp_is_single_item()) $css[] = "single-item";
		if (bp_is_activation_page()) $css[] = "activation-page";
		if (bp_is_register_page()) $css[] = "register-page";
		$css[] = 'fix';

		if (is_array($custom)) {
			foreach($custom as $class) {
				if (!in_array($class, $css)) $css[] = esc_attr($class);
			}
		}
		else if ($custom != false) {
			$css[] = $custom;
		}
		$css_class = implode(' ', $css);
		if ($echo) echo ' class="'.$css_class.'" ';
		return ' class="'.$css_class.'" ';
	}
}

function suffusion_get_template_prefixes() {
	$template_prefixes = array('1l-sidebar.php' => '_1l', '1r-sidebar.php' => '_1r', '1l1r-sidebar.php' => '_1l1r', '2l-sidebars.php' => '_2l', '2r-sidebars.php' => '_2r');
	$template_prefixes = apply_filters('suffusion_filter_template_prefixes', $template_prefixes);
	return $template_prefixes;
}

function suffusion_get_template_sidebars() {
	$template_sb = array('1l-sidebar.php' => 1, '1r-sidebar.php' => 1, '1l1r-sidebar.php' => 2, '2l-sidebars.php' => 2, '2r-sidebars.php' => 2);
	$template_sb = apply_filters('suffusion_filter_template_sidebars', $template_sb);
	return $template_sb;
}

if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');
	add_theme_support('nav-menus'); // For Beta and RC versions of WP 3.0
	add_theme_support('menus'); // For the final release version of WP 3.0
	add_theme_support('automatic-feed-links');
}
require_once (TEMPLATEPATH . "/suffusion.php");
include_once (TEMPLATEPATH . "/widget-areas.php");
include_once (TEMPLATEPATH . "/functions/actions.php");
include_once (TEMPLATEPATH . "/functions/filters.php");
include_once (TEMPLATEPATH . "/functions/media.php");
include_once (TEMPLATEPATH . "/functions/shortcodes.php");

// This is not a BP child theme, but in case it is used with the Suffusion BP support pack, this inclusion is needed.
if (!is_admin() && function_exists('bp_is_group') && file_exists(PLUGINDIR . "/buddypress/bp-themes/bp-default/_inc/ajax.php")) {
	include_once (PLUGINDIR . "/buddypress/bp-themes/bp-default/_inc/ajax.php");
}

if (isset($suffusion_options['suf_custom_php_file'])) {
	$custom_php_file = $suffusion_options['suf_custom_php_file'];
	$custom_php_file = stripslashes($custom_php_file);
	if (substr($custom_php_file, 0, 1) == "/") {
		$custom_php_file = substr($custom_php_file, 1);
	}
	if (trim($custom_php_file) != "" && file_exists(WP_CONTENT_DIR."/".$custom_php_file)) {
		include_once(WP_CONTENT_DIR."/".$custom_php_file);
	}
}

$suffusion_theme_hierarchy = array(
	'light-theme-gray-1' => array('style.css', 'skins/light-theme-gray-1/style.css'),
	'light-theme-gray-2' => array('style.css', 'skins/light-theme-gray-2/style.css'),
	'light-theme-green' => array('style.css', 'skins/light-theme-green/style.css'),
	'light-theme-orange' => array('style.css', 'skins/light-theme-orange/style.css'),
	'light-theme-pale-blue' => array('style.css', 'skins/light-theme-pale-blue/style.css'),
	'light-theme-purple' => array('style.css', 'skins/light-theme-purple/style.css'),
	'light-theme-red' => array('style.css', 'skins/light-theme-red/style.css'),
	'light-theme-royal-blue' => array('style.css', 'skins/light-theme-royal-blue/style.css'),
	'dark-theme-gray-1' => array('style.css', 'skins/light-theme-gray-1/style.css', 'dark-style.css', 'skins/dark-theme-gray-1/style.css'),
	'dark-theme-gray-2' => array('style.css', 'skins/light-theme-gray-2/style.css', 'dark-style.css', 'skins/dark-theme-gray-2/style.css'),
	'dark-theme-green' => array('style.css', 'skins/light-theme-green/style.css', 'dark-style.css', 'skins/dark-theme-green/style.css'),
	'dark-theme-orange' => array('style.css', 'skins/light-theme-orange/style.css', 'dark-style.css', 'skins/dark-theme-orange/style.css'),
	'dark-theme-pale-blue' => array('style.css', 'skins/light-theme-pale-blue/style.css', 'dark-style.css', 'skins/dark-theme-pale-blue/style.css'),
	'dark-theme-purple' => array('style.css', 'skins/light-theme-purple/style.css', 'dark-style.css', 'skins/dark-theme-purple/style.css'),
	'dark-theme-red' => array('style.css', 'skins/light-theme-red/style.css', 'dark-style.css', 'skins/dark-theme-red/style.css'),
	'dark-theme-royal-blue' => array('style.css', 'skins/light-theme-royal-blue/style.css', 'dark-style.css', 'skins/dark-theme-royal-blue/style.css'),
	'minima' => array('style.css', 'skins/minima/style.css'),
);

$suffusion = new Suffusion();
$suffusion->init();

?>
