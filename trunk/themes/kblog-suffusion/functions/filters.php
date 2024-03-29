<?php
/**
 * Defines a list of filter hooks and the functions tied to those hooks
 *
 * @package Suffusion
 * @subpackage Functions
 */

// First we will get all options from the database, then we will individually invoke the options within each function as required.
global $suffusion_unified_options;
foreach ($suffusion_unified_options as $id => $value) {
	$$id = $value;
}

add_filter('get_pages', 'suffusion_replace_page_with_alt_title');
add_filter('page_link', 'suffusion_unlink_page', 10, 2);
add_filter('wp_list_pages', 'suffusion_js_for_unlinked_pages');
add_filter('wp_list_pages', 'suffusion_remove_a_title_attribute');
add_filter('wp_list_categories', 'suffusion_remove_a_title_attribute');
add_filter('wp_list_bookmarks', 'suffusion_remove_a_title_attribute');

add_filter('the_content_more_link', 'suffusion_set_more_link');
add_filter('the_content', 'suffusion_pages_link', 8);

add_filter('comment_reply_link', 'suffusion_hide_reply_link_for_pings', 10, 4);
add_filter('get_comments_number', 'suffusion_filter_trk_ping_from_count');
add_filter('get_comments_pagenum_link', 'suffusion_append_comment_type');

add_filter('user_contactmethods', 'suffusion_add_user_contact_methods');

add_filter('excerpt_length', 'suffusion_excerpt_length');
add_filter('excerpt_more', 'suffusion_excerpt_more');

add_filter('widget_text', 'do_shortcode');

add_filter('suffusion_can_display_attachment', 'suffusion_filter_attachment_display', 10, 4);

// Some filters to make sure the theme works with BP without hiccups
add_filter('bp_field_css_classes', 'suffusion_add_bp_specific_classes');

function suffusion_replace_page_with_alt_title($pages, $args = array()) {
	if ($pages && is_array($pages)) {
		foreach ($pages as $page) {
			$alt_title = get_post_meta($page->ID, 'suf_alt_page_title', true);
			if ($alt_title === FALSE || trim($alt_title) == "") {
			}
			else {
				$page->post_title = $alt_title;
			}
		}
	}
	return $pages;
}

function suffusion_set_more_link($more_link_text) {
	return "<span class='more-link fix'>".$more_link_text."</span>";
}

function suffusion_pages_link($content) {
	$args = array(
		'before' => '<div class="page-links"><strong>'.__('Pages:', 'suf_theme').'</strong>',
		'after' => '</div>',
		'link_before' => '<span class="page-num">',
		'link_after' => '</span>',
		'next_or_number' => 'number',
		'echo' => 0
	);
	$content .= wp_link_pages($args);
	return $content;
}

function suffusion_hide_reply_link_for_pings($link, $custom_options = array(), $current_comment, $current_post) {
	global $suf_show_hide_reply_link_for_pings;
	if ($suf_show_hide_reply_link_for_pings != "allow") {
		if (($current_comment->comment_type != "") && ($current_comment->comment_type != "comment")) {
			$link = "";
		}
	}
	return $link;
}

function suffusion_filter_trk_ping_from_count($output) {
	global $post, $suf_show_track_ping;

	if (!is_admin()) {
		$all_comments = get_comments('status=approve&post_id=' . $post->ID);
		$comments_by_type = separate_comments($all_comments);
		$comments_number = count($comments_by_type['comment']);
		if ($suf_show_track_ping == "show" || $suf_show_track_ping == "separate") {
			return $output;
		}
		else {
			return $comments_number;
		}
	}
	else {
		return $output;
	}
}

function suffusion_get_comment_type_from_request() {
	global $post, $suf_show_track_ping, $SUFFUSION_COMMENT_TYPES;
	$comment_type = $_REQUEST['comment_type'];

	if ($comment_type == null || $comment_type == "" || $SUFFUSION_COMMENT_TYPES[$comment_type] == null) {
		$all_comments = get_comments('status=approve&post_id=' . $post->ID);
		$comments_by_type = separate_comments($all_comments);

		if (count($comments_by_type['comment']) > 0) {
			$comment_type = 'comment';
		}
		else if (count($comments_by_type['trackback']) > 0){
			$comment_type = 'trackback';
		}
		else if (count($comments_by_type['pingback']) > 0){
			$comment_type = 'pingback';
		}
	}
	return $comment_type;
}

function suffusion_list_comments() {
	global $suf_show_track_ping, $SUFFUSION_COMMENT_TYPES;
	if ($suf_show_track_ping == "show") {
		$commentargs = array(
			"avatar_size" => 48,
			"callback" => "suffusion_comments_callback"
		);
	}
	else if ($suf_show_track_ping == "separate") {
		$comment_type = suffusion_get_comment_type_from_request();
		$commentargs = array(
			"avatar_size" => 48,
			"type" => "$comment_type",
			"callback" => "suffusion_comments_callback"
		);
	}
	else if ($suf_show_track_ping == "hide") {
		$commentargs = array(
			"avatar_size" => 48,
			"type" => "comment",
			"callback" => "suffusion_comments_callback"
		);
	}

	echo "<ol class=\"commentlist\">\n";
	wp_list_comments($commentargs);
	echo "</ol>\n";
}

function suffusion_split_comments() {
	global $post, $suf_show_track_ping, $SUFFUSION_COMMENT_TYPES;

	if ($suf_show_track_ping != "separate") {
		return;
	}

	$all_comments = get_comments('status=approve&post_id=' . $post->ID);
	$comments_by_type = separate_comments($all_comments);

	echo "<div class=\"comment-response-types fix\">\n";
	foreach ($comments_by_type as $comment_type => $comment_type_list) {
		if ($comment_type == 'pings') {
			continue;
		}
		$type_number = count($comment_type_list);
		if ($type_number > 0) {
			$page_link = get_page_link($post->ID);
			$pretty_comment_type = $SUFFUSION_COMMENT_TYPES[$comment_type] == null ? $SUFFUSION_COMMENT_TYPES['comment'] : $SUFFUSION_COMMENT_TYPES[$comment_type];
			$request_comment_type = suffusion_get_comment_type_from_request();
			if ($request_comment_type != $comment_type) {
				$page_link = add_query_arg("comment_type", $comment_type, $page_link);
				echo "<a href=\"$page_link#comments\" class=\"comment-response-types\">".$pretty_comment_type." ($type_number)</a> ";
			}
			else {
				echo "<span class=\"comment-response-types\">".$pretty_comment_type." ($type_number)</span> ";
			}
		}
	}
	echo "</div>\n";
}

function suffusion_append_comment_type($link) {
	global $post, $suf_show_track_ping, $SUFFUSION_COMMENT_TYPES;

	if ($suf_show_track_ping != "separate") {
		return $link;
	}

	$comment_type = suffusion_get_comment_type_from_request();
	$link = add_query_arg("comment_type", $comment_type, $link);
	return $link;
}

function suffusion_comment_navigation() {
	global $suf_cpagination_type, $suf_cpagination_index, $suf_cpagination_prev_next, $suf_cpagination_show_all, $suf_show_track_ping, $SUFFUSION_COMMENT_TYPES;
	if ($suf_show_track_ping == 'show' || $suf_show_track_ping == 'hide') {
		$older = __("Older Comments", "suf_theme");
		$newer = __("Newer Comments", "suf_theme");
	}
	else {
		$comment_type = suffusion_get_comment_type_from_request();
		$older = sprintf(__('Older %1$s', 'suf_theme'), $SUFFUSION_COMMENT_TYPES[$comment_type]);
		$newer = sprintf(__('Newer %1$s', 'suf_theme'), $SUFFUSION_COMMENT_TYPES[$comment_type]);
	}
	if ($suf_cpagination_type == 'old-new') {
?>
	<div class="navigation fix">
		<div class="alignleft"><?php previous_comments_link("&laquo; $older"); ?></div>
		<div class="alignright"><?php next_comments_link("$newer &raquo;"); ?></div>
	</div>
<?php
	}
	else {
		// The user wants pagination
		global $wp_query, $cpage;
		$max_page = get_comment_pages_count();
		$prev_next = $suf_cpagination_prev_next == "show";
		$show_all = $suf_cpagination_show_all == "all";
		if (!$cpage && $max_page >= 1) {
			$current_page = $max_page;
		}
		else {
			$current_page = $cpage;
		}
		if ($max_page > 1) {
?>
		<div class="navigation fix">
			<div class="suf-page-nav fix">
<?php
			if ($suf_cpagination_index == "show") {
?>
				<span class="page-index"><?php printf(__('Page %1$s of %2$s', 'suf_theme'), $current_page, $max_page); ?></span>
<?php
			}
			$comment_order = get_option('comment_order');
			if ($comment_order == 'asc') {
				$next_text = $newer." &raquo;";
				$prev_text = "&laquo; ".$older;
			}
			else {
				$prev_text = "&laquo; ".$newer;
				$next_text = $older." &raquo;";
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
				"next_text" => $next_text,
				"prev_text" => $prev_text,
			));
?>
			</div><!-- suf page nav -->
		</div><!-- page nav -->
<?php
		}
	}
}

function suffusion_add_user_contact_methods($contact_methods) {
    global $suf_uprof_networks, $social_networks;
    if (trim($suf_uprof_networks) != '') {
        $networks = explode(',', $suf_uprof_networks);
        foreach ($networks as $network) {
            $display = $social_networks[$network];
            $contact_methods[$network] = $display;
        }
    }
    return $contact_methods;
}

function suffusion_excerpt_length($length) {
	global $suf_excerpt_custom_length;
	if (check_integer($suf_excerpt_custom_length)) {
		return $suf_excerpt_custom_length;
	}
	else {
		return $length;
	}
}

function suffusion_excerpt_more($more) {
	global $post, $suf_excerpt_custom_more_text;
	$stripped = stripslashes($suf_excerpt_custom_more_text);
	return " <a href='".get_permalink($post->ID)."'>".$stripped."</a>";
}

function suffusion_post_class($class) {
	global $post;
	$sticky = " ";
	if (is_sticky()) {
		$sticky = " sticky-post ";
	}
	return $class.$sticky."fix";
}

function suffusion_get_post_title_and_link() {
	global $post;
	$ret = "<a href='".get_permalink($post->ID)."' class='entry-title' rel='bookmark' title='".esc_attr($post->post_title)."' >".$post->post_title."</a>";
	return apply_filters('suffusion_get_post_title_and_link', $ret);
	//return $ret;
}

function suffusion_unlink_page($link, $id) {
	$link_disabled = get_post_meta($id, 'suf_nav_unlinked', true);
	if ($link_disabled) {
		return "#";
	}
	else {
		return $link;
	}
}

function suffusion_js_for_unlinked_pages($listing) {
	if (trim($listing) != '') {
		$listing = str_replace(array("href='#'", 'href="#"'), "href='#' onclick='return false;'", $listing);
	}
	return $listing;
}

function suffusion_remove_a_title_attribute($listing) {
	global $suf_nav_strip_a_title;
	if (isset($suf_nav_strip_a_title) && $suf_nav_strip_a_title == 'hide') {
		if (trim($listing) != '') {
			$listing = preg_replace('/title=\"(.*?)\"/','',$listing);
		}
	}
	return $listing;
}

function suffusion_comments_callback($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;

	$comment_type = get_comment_type($comment->comment_ID);

	$cache = wp_cache_get('comment_template');

	if (!is_array($cache)) $cache = array();

	if ( !isset( $cache[$comment_type] ) ) {
		$template = locate_template(array("comment-{$comment_type}.php", 'comment.php'));

		$cache[$comment_type] = $template;
		wp_cache_set('comment_template', $cache);
	}

	require($cache[$comment_type]);
}

function suffusion_add_bp_specific_classes($css_classes) {
	if (is_array($css_classes)) {
		if (in_array('editfield', $css_classes)) {
			$css_classes[] = 'fix';
		}
	}
	return $css_classes;
}

function suffusion_filter_attachment_display($type, $mime) {
	$option_name = "suf_{$mime}_att_type";
	global $$option_name;
	if (isset($$option_name)) {
		return $$option_name;
	}
	return $type;
}
?>