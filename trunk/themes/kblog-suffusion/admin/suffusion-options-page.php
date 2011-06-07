<?php
/**
 * Contains the layout functions for Suffusion's options.
 * This file is included in functions.php
 *
 * @package Suffusion
 * @subpackage Admin
 */

$suffusion_options_file = basename(__FILE__);

$suffusion_options_intro_page = 'theme-options-intro.php';
$suffusion_options_visual_effects_page = 'theme-options-visual-effects.php';
$suffusion_options_sidebars_and_widgets_page = 'theme-options-sidebars-and-widgets.php';
$suffusion_options_blog_features_page = 'theme-options-blog-features.php';
$suffusion_options_templates_page = 'theme-options-templates.php';

add_action('admin_menu', 'suffusion_add_admin');

function suffusion_render_options() {
	global $themename, $suffusion_options_intro_page, $suffusion_options_visual_effects_page, $suffusion_options_sidebars_and_widgets_page, $suffusion_options_blog_features_page, $suffusion_options_templates_page;
	if (isset($_REQUEST['saved'])) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved for this page.</strong></p></div>';
	if (isset($_REQUEST['reset'])) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset for this page.</strong></p></div>';
	if (isset($_REQUEST['reset_all'])) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset for all pages.</strong></p></div>';
	if (isset($_REQUEST['migrated'])) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' options migrated successfully.</strong></p></div>';
	if (isset($_REQUEST['imported'])) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' options imported successfully.</strong></p></div>';
?>
<div class="wrap">
<div class="suf-tabbed-options">
<h2 class='suf-header-1'>Settings for Suffusion</h2>
<?php
	global $intro_options, $visual_effects_options, $sidebars_and_widgets_options, $blog_features_options, $templates_options;
	//$option_page_options = array();
	if (isset($_REQUEST['page'])) {
		$options_page = $_REQUEST['page'];
		switch ($options_page) {
			case $suffusion_options_visual_effects_page:
				$option_page_options = $visual_effects_options;
		        break;
			case $suffusion_options_sidebars_and_widgets_page:
				$option_page_options = $sidebars_and_widgets_options;
		        break;
			case $suffusion_options_blog_features_page:
				$option_page_options = $blog_features_options;
		        break;
			case $suffusion_options_templates_page:
				$option_page_options = $templates_options;
		        break;
			case $suffusion_options_intro_page:
			default:
				$option_page_options = $intro_options;
		        break;
		}
	}
	$option_structure = get_option_structure($option_page_options);
	get_options_html($option_structure);
?>
</div><!-- suf-options -->
</div><!-- wrap -->
<?php
}

function suffusion_add_admin() {
	global $options, $spawned_options, $suffusion_options, $suffusion_options_intro_page, $suffusion_options_visual_effects_page, $suffusion_options_sidebars_and_widgets_page, $suffusion_options_blog_features_page, $suffusion_options_templates_page, $suffusion_reevaluate_styles, $suffusion_unified_options;
	$options_files = array($suffusion_options_intro_page, $suffusion_options_visual_effects_page, $suffusion_options_sidebars_and_widgets_page, $suffusion_options_blog_features_page, $suffusion_options_templates_page);
	if ($suffusion_options === FALSE || $suffusion_options == null || $suffusion_options == '') {
		$suffusion_options = array();
	}

	if (isset($_GET['page']) && in_array($_GET['page'], $options_files)) {
		$options_page = $_GET['page'];
		if (isset($_REQUEST['formaction']) && 'save' == $_REQUEST['formaction']) {
			$form_category = $_REQUEST['formcategory'];
			check_admin_referer($form_category."-suffusion", $form_category.'-wpnonce');
			$filtered_options = get_options_for_category($options, $form_category);

			foreach ($filtered_options as $value) {
				if(isset($value['id']) && isset($_REQUEST[$value['id']])) {
					$suffusion_options[$value['id']] = $_REQUEST[$value['id']];
				}
				else {
					if (isset($value['id'])) {
						unset($suffusion_options[$value['id']]);
					}
				}
			}
			$filtered_spawned_options = get_spawned_options_for_category($options, $spawned_options, $form_category);
			$parents = array();
			foreach ($filtered_spawned_options as $value) {
				$parent_children = array();
				if (isset($parents[$value['parent']])) {
					$parent_children = $parents[$value['parent']];
				}
				if(isset($_REQUEST[$value['id']])) {
					$parent_children[count($parent_children)] = substr($value['id'], strlen($value['parent']) + 1, strlen($value['id']) - strlen($value['parent']));
				}
				$parents[$value['parent']] = $parent_children;
			}
			foreach ($parents as $parent => $children) {
				if (is_array($children)) {
					$children_str = implode(',', $children);
					$suffusion_options[$parent] = $children_str;
				}
				else {
					unset($suffusion_options[$parent]);
				}
			}
			update_option('suffusion_options', $suffusion_options);
			$suffusion_reevaluate_styles = true;
            $suffusion_unified_options = suffusion_get_unified_options(false, false);
			suffusion_set_options_version($suffusion_unified_options);
			update_option('suffusion_unified_options', $suffusion_unified_options);
			if (isset($suffusion_unified_options['suf_autogen_css']) && $suffusion_unified_options['suf_autogen_css'] == 'autogen') {
				suffusion_create_or_update_custom_css(true);
			}
			header("Location: admin.php?page=$options_page&saved=true&category=$form_category");
			die;
		}
		else if(isset($_REQUEST['formaction']) && ('reset' == $_REQUEST['formaction'] || 'reset_all' == $_REQUEST['formaction'])) {
			$form_category = $_REQUEST['formcategory'];
			check_admin_referer($form_category."-suffusion", $form_category.'-wpnonce');
			if ('reset_all' == $_REQUEST['formaction']) {
				$category = 'all';
			}
			else {
				$category = $form_category;
			}
			$filtered_options = get_options_for_category($options, $category);
			foreach ($filtered_options as $value) {
				if (isset($value['id'])) {
					unset($suffusion_options[$value['id']]);
				}
			}
			$filtered_spawned_options = get_spawned_options_for_category($options, $spawned_options, $category);
			foreach ($filtered_spawned_options as $value) {
				if (isset($value['id'])) {
					unset($suffusion_options[$value['id']]);
				}
			}
			update_option('suffusion_options', $suffusion_options);
			$suffusion_reevaluate_styles = true;
			$suffusion_unified_options = suffusion_get_unified_options(false, false);
			suffusion_set_options_version($suffusion_unified_options);
			update_option('suffusion_unified_options', $suffusion_unified_options);
			if (isset($suffusion_unified_options['suf_autogen_css']) && $suffusion_unified_options['suf_autogen_css'] == 'autogen') {
				suffusion_create_or_update_custom_css(true);
			}
			header("Location: admin.php?page=$options_page&".$_REQUEST['formaction']."=true&category=$form_category");
			die;
		}
		else if (isset($_REQUEST['formaction']) && 'suf_up_migrate_302' == $_REQUEST['formaction']) {
			suffusion_migrate_from_v302();
		}
		else if (isset($_REQUEST['formaction']) && 'suf_up_migrate_343' == $_REQUEST['formaction']) {
			suffusion_migrate_from_v343();
		}
		else if (isset($_REQUEST['formaction']) && 'suf_export_options' == $_REQUEST['formaction']) {
			suffusion_export_settings();
		}
		else if (isset($_REQUEST['formaction']) && 'suf_import_options' == $_REQUEST['formaction']) {
			suffusion_import_settings();
		}
	}

	add_menu_page("Suffusion", "Suffusion", 'manage_options', $suffusion_options_intro_page, 'suffusion_render_options', get_bloginfo('template_url').'/admin/images/aquoid.png');

	$suffusion_theme_options_intro = add_submenu_page($suffusion_options_intro_page, 'Introduction', "Introduction", 'manage_options', $suffusion_options_intro_page, 'suffusion_render_options');
	add_action("admin_head-$suffusion_theme_options_intro", 'suf_admin_header_style');
	add_action("admin_print_scripts-$suffusion_theme_options_intro", 'suffusion_admin_script_loader');
	add_action("admin_print_styles-$suffusion_theme_options_intro", 'suffusion_admin_style_loader');

	$suffusion_theme_options_visual = add_submenu_page($suffusion_options_intro_page, 'Visual Effects', "Visual Effects", 'manage_options', $suffusion_options_visual_effects_page, 'suffusion_render_options');
	add_action("admin_head-$suffusion_theme_options_visual", 'suf_admin_header_style');
	add_action("admin_print_scripts-$suffusion_theme_options_visual", 'suffusion_admin_script_loader');
	add_action("admin_print_styles-$suffusion_theme_options_visual", 'suffusion_admin_style_loader');

	$suffusion_theme_options_sidebars = add_submenu_page($suffusion_options_intro_page, 'Sidebars and Widgets', "Sidebars and Widgets", 'manage_options', $suffusion_options_sidebars_and_widgets_page, 'suffusion_render_options');
	add_action("admin_head-$suffusion_theme_options_sidebars", 'suf_admin_header_style');
	add_action("admin_print_scripts-$suffusion_theme_options_sidebars", 'suffusion_admin_script_loader');
	add_action("admin_print_styles-$suffusion_theme_options_sidebars", 'suffusion_admin_style_loader');

	$suffusion_theme_options_blog = add_submenu_page($suffusion_options_intro_page, 'Blog Features', "Blog Features", 'manage_options', $suffusion_options_blog_features_page, 'suffusion_render_options');
	add_action("admin_head-$suffusion_theme_options_blog", 'suf_admin_header_style');
	add_action("admin_print_scripts-$suffusion_theme_options_blog", 'suffusion_admin_script_loader');
	add_action("admin_print_styles-$suffusion_theme_options_blog", 'suffusion_admin_style_loader');

	$suffusion_theme_options_templates = add_submenu_page($suffusion_options_intro_page, 'Templates', "Templates", 'manage_options', $suffusion_options_templates_page, 'suffusion_render_options');
	add_action("admin_head-$suffusion_theme_options_templates", 'suf_admin_header_style');
	add_action("admin_print_scripts-$suffusion_theme_options_templates", 'suffusion_admin_script_loader');
	add_action("admin_print_styles-$suffusion_theme_options_templates", 'suffusion_admin_style_loader');
}

function suffusion_migrate_from_v302() {
	global $spawned_options, $suffusion_options, $suffusion_options_intro_page, $suffusion_reevaluate_styles, $suffusion_unified_options;
	if ($suffusion_options === FALSE || $suffusion_options == null || $suffusion_options == '') {
		$suffusion_options = array();
	}

	$form_category = $_REQUEST['formcategory'];
	check_admin_referer($form_category."-suffusion", $form_category.'-wpnonce');
	$parent_spawn_groups = array();
	foreach ($spawned_options as $spawn) {
		$parent = $spawn['parent'];
		if (!$parent_spawn_groups[$parent]) {
			$parent_spawn_groups[$parent] = array();
		}
		$parent_spawn = $parent_spawn_groups[$parent];
		$parent_spawn[count($parent_spawn)] = $spawn['id'];
		$parent_spawn_groups[$parent] = $parent_spawn;
	}

	$new_values = array();
	foreach ($parent_spawn_groups as $parent => $spawn_group) {
		$new_values[$parent] = array();
	}

	foreach ($parent_spawn_groups as $parent => $spawn_group) {
		$selected_children = $new_values[$parent];
		foreach ($spawn_group as $idx => $spawn) {
			if (get_option($spawn)) {
				$selected_children[count($selected_children)] = substr($spawn, strlen($parent) + 1, strlen($spawn) - strlen($parent));
			}
		}
		$new_values[$parent] = $selected_children;
	}

	foreach ($new_values as $parent => $spawn_group) {
		$consolidated = implode(',', $spawn_group);
		$suffusion_options[$parent] = $consolidated;
	}
	update_option('suffusion_options', $suffusion_options);
	$suffusion_reevaluate_styles = true;
	$suffusion_unified_options = suffusion_get_unified_options(false, false);
	suffusion_set_options_version($suffusion_unified_options);
	update_option('suffusion_unified_options', $suffusion_unified_options);
	if (isset($suffusion_unified_options['suf_autogen_css']) && $suffusion_unified_options['suf_autogen_css'] == 'autogen') {
		suffusion_create_or_update_custom_css(true);
	}

	$meta_fields = array('suf_alt_page_title' => 'text');
	foreach ($meta_fields as $meta_field => $type) {
		$pages = get_pages();
		if ($pages && is_array($pages)) {
			foreach ($pages as $page) {
				$page_id = $page->ID;
				if ($page != null) {
					if ($type == 'checkbox') {
						$data = 'on';
					}
					else if ($type == 'text') {
						$data = get_option($meta_field.'_'.$page_id);
					}
					if (get_post_meta($page_id, $meta_field) == '') {
						add_post_meta($page_id, $meta_field, $data, true);
					}
					else if ($data != get_post_meta($page_id, $meta_field, true)) {
						update_post_meta($page_id, $meta_field, $data);
					}
					else if ($data == '') {
						delete_post_meta($page_id, $meta_field, get_post_meta( $page_id, $meta_field, true));
					}
				}
			}
		}
	}
	header("Location: admin.php?page=$suffusion_options_intro_page&migrated=true&category=$form_category"."&data=");
}

function suffusion_import_settings() {
	global $suffusion_options, $suffusion_options_intro_page, $suffusion_reevaluate_styles, $suffusion_unified_options;
	if ($suffusion_options === FALSE || $suffusion_options == null || $suffusion_options == '') {
		$suffusion_options = array();
	}

	$form_category = $_REQUEST['formcategory'];
	check_admin_referer($form_category."-suffusion", $form_category.'-wpnonce');
	if (file_exists(TEMPLATEPATH."/admin/import/suffusion-options.php")) {
		include (TEMPLATEPATH."/admin/import/suffusion-options.php");
		foreach ($suffusion_exported_options as $option => $option_value) {
			$suffusion_options[$option] = $option_value;
		}
		update_option('suffusion_options', $suffusion_options);
		$suffusion_reevaluate_styles = true;
		$suffusion_unified_options = suffusion_get_unified_options(false, false);
		suffusion_set_options_version($suffusion_unified_options);
		update_option('suffusion_unified_options', $suffusion_unified_options);
		if (isset($suffusion_unified_options['suf_autogen_css']) && $suffusion_unified_options['suf_autogen_css'] == 'autogen') {
			suffusion_create_or_update_custom_css(true);
		}
		header("Location: admin.php?page=$suffusion_options_intro_page&imported=true&category=$form_category"."&data=");
	}
	else {
		header("Location: admin.php?page=$suffusion_options_intro_page&imported=false&category=$form_category"."&data=");
	}
}

function suffusion_migrate_from_v343() {
	global $options, $suffusion_options_intro_page, $suffusion_reevaluate_styles, $suffusion_unified_options;
	$suffusion_options = array();

	$form_category = $_REQUEST['formcategory'];
	check_admin_referer($form_category."-suffusion", $form_category.'-wpnonce');
	foreach ($options as $value) {
		if (isset($value['id'])) {
			if (get_option($value['id']) === FALSE) {
				unset($suffusion_options[$value['id']]);
			}
			else {
				$suffusion_options[$value['id']] = get_option($value['id']);
			}
		}
	}
	update_option('suffusion_options', $suffusion_options);
	$suffusion_reevaluate_styles = true;
	$suffusion_unified_options = suffusion_get_unified_options(false, false);
	suffusion_set_options_version($suffusion_unified_options);
	update_option('suffusion_unified_options', $suffusion_unified_options);
	if (isset($suffusion_unified_options['suf_autogen_css']) && $suffusion_unified_options['suf_autogen_css'] == 'autogen') {
		suffusion_create_or_update_custom_css(true);
	}
	header("Location: admin.php?page=$suffusion_options_intro_page&migrated=true&category=$form_category"."&data=");
}

function suf_admin_header_style() {
    global $options, $suffusion_options_intro_page, $suffusion_options_visual_effects_page, $suffusion_options_sidebars_and_widgets_page, $suffusion_options_blog_features_page, $suffusion_options_templates_page;
	$options_files = array($suffusion_options_intro_page, $suffusion_options_visual_effects_page, $suffusion_options_sidebars_and_widgets_page, $suffusion_options_blog_features_page, $suffusion_options_templates_page);
    $landing_tabs = array($suffusion_options_intro_page => 'intro-pages', $suffusion_options_visual_effects_page => 'visual-effects', $suffusion_options_sidebars_and_widgets_page => 'sidebar-setup', $suffusion_options_blog_features_page => 'blog-features', $suffusion_options_templates_page => 'templates');
    $landing_tab_sections = array($suffusion_options_intro_page => 'welcome', $suffusion_options_visual_effects_page => 'theme-selection', $suffusion_options_sidebars_and_widgets_page => 'sidebar-layout', $suffusion_options_blog_features_page => 'nav-setup', $suffusion_options_templates_page => 'magazine-template');

    $option_structure = get_option_structure($options);
    $custom_type_structure = array('post-types' => array('custom-post-types', 'add-edit-post-type'),
	    'taxonomies' => array('custom-taxonomy-types', 'add-edit-taxonomy-type'));
	if (isset($_REQUEST['category']) && in_array($_REQUEST['page'], $options_files)) {
		$category = $_REQUEST['category'];
        foreach ($option_structure as $l1) {
            if (!isset($l1['parent']) || $l1['parent'] == null) {
                foreach ($l1['children'] as $l2slug => $l2name) {
                    foreach ($option_structure[$l2slug]['children'] as $l3slug => $l3name) {
                        if ($category == $l3slug) {
                            $group = $l2slug;
                        }
                    }
                }
            }
        }
	}
	else if (!isset($_REQUEST['category']) && in_array($_REQUEST['page'], $options_files)) {
		$group = $landing_tabs[$_REQUEST['page']];
		$category = $landing_tab_sections[$_REQUEST['page']];
	}
	else if (isset($_REQUEST['category']) && $_REQUEST['page'] == 'suffusion-custom-types') {
		$category = $_REQUEST['category'];
		foreach ($custom_type_structure as $custom_type => $custom_type_categories) {
			if (in_array($category, $custom_type_categories)) {
				$group = $custom_type;
				break;
			}
		}
	}
	else if (in_array($_REQUEST['page'], $options_files)){
		$category = 'welcome';
        $group = 'intro-pages';
	}
    else {
	    $category = 'custom-post-types';
        $group = 'post-types';
    }
?>
	<script type="text/javascript">
		/* <![CDATA[ */
		$j = jQuery.noConflict();

		$j(document).ready(function() {
			var selected_category = '<?php echo $category; ?>';
			var selected_group = '<?php echo $group; ?>';
			var current_group = selected_group;
			$j('div.suf-options').hide();
			$j('div.main-content').hide();
			$j('div.suf-options-' + selected_group).show();
			$j('.htab-' + selected_group).addClass('current-tab');
			$j('#' + selected_category).show();
			$j('div.suf-options ul.tabs li.' + selected_category + ' a').addClass('current-tab');
			$j('ul.htabs li').click(function(){
				var thisClass = this.className.substring(5, this.className.indexOf(" "));
				$j('div.suf-options').hide();
				$j('div.suf-options-' + thisClass).show();
				$j('ul.htabs li').removeClass('current-tab');
				$j(this).addClass('current-tab');
				if (current_group != thisClass) {
    				$j('div.suf-options ul.tabs li a').removeClass('current-tab');
        			$j('ul.tabs').find('li:first a').addClass('current-tab');
    	    		$j('div.suf-options-' + thisClass + ' div.main-content').hide();
			    	$j('div.suf-options-' + thisClass).find('.main-content:first').show();
			    	current_group = thisClass;
				}
			});

			$j('ul.tabs li a').click(function(){
				var thisClass = this.className.substring(0, this.className.indexOf(" "));
				$j('div.suf-options div.main-content').hide();
				$j('#' + thisClass).show();
				$j('div.suf-options ul.tabs li a').removeClass('current-tab');
				$j(this).addClass('current-tab');
			});

			$j('input.suf-multi-select-button').click(function() {
			    var thisAction = this.className.substring(0, this.className.indexOf(" "));
			    var thisName = this.name.substring(0, this.name.indexOf("-"));
			    if (thisAction == 'button-all') {
			        $j('input[type=checkbox].suf-options-checkbox-' + thisName).attr('checked', true);
			    }
			    else if (thisAction == 'button-none') {
			        $j('input[type=checkbox].suf-options-checkbox-' + thisName).attr('checked', false);
			    }
			});

			$j('.suf-grouping').each(function() {
				var thisName = this.className.substring(0, this.className.indexOf(" "));
				thisName = thisName.substring(13);
				$j('.' + thisName + '-grouping .' + thisName + '-rhs').append(this);
				//$j(this).prependTo(this.parentNode.parentNode);
			});

			$j('.suf-background-options input[type=text]').change(function(event) {
				var thisName = event.currentTarget.id;
				thisName = thisName.substring(0, thisName.indexOf('-'));
				$j("#" + thisName).val('color=' + $j("#" + thisName + "-bgcolor").val() + ';' +
									   'colortype=' + $j("input[name=" + thisName + "-colortype]:checked").val() + ';' +
									   'image=' + $j("#" + thisName + "-bgimg").val() + ';' +
									   'position=' + $j("#" + thisName + "-position").val() + ';' +
									   'repeat=' + $j("#" + thisName + "-repeat").val() + ';' +
									   'trans=' + $j("#" + thisName + "-trans").val() + ';'
						);
			});

			$j('.suf-background-options input[type=radio]').change(function(event) {
				var thisName = event.currentTarget.name;
				thisName = thisName.substring(0, thisName.indexOf('-'));
				$j("#" + thisName).val('color=' + $j("#" + thisName + "-bgcolor").val() + ';' +
									   'colortype=' + $j("input[name=" + thisName + "-colortype]:checked").val() + ';' +
									   'image=' + $j("#" + thisName + "-bgimg").val() + ';' +
									   'position=' + $j("#" + thisName + "-position").val() + ';' +
									   'repeat=' + $j("#" + thisName + "-repeat").val() + ';' +
									   'trans=' + $j("#" + thisName + "-trans").val() + ';'
						);
			});

			$j('.suf-background-options select').change(function(event) {
				var thisName = event.currentTarget.id;
				thisName = thisName.substring(0, thisName.indexOf('-'));
				$j("#" + thisName).val('color=' + $j("#" + thisName + "-bgcolor").val() + ';' +
									   'colortype=' + $j("input[name=" + thisName + "-colortype]:checked").val() + ';' +
									   'image=' + $j("#" + thisName + "-bgimg").val() + ';' +
									   'position=' + $j("#" + thisName + "-position").val() + ';' +
									   'repeat=' + $j("#" + thisName + "-repeat").val() + ';' +
									   'trans=' + $j("#" + thisName + "-trans").val() + ';'
						);
			});

			$j('.suf-font-options input[type=text]').change(function(event) {
				var thisName = event.currentTarget.id;
				thisName = thisName.substring(0, thisName.indexOf('-'));
				$j("#" + thisName).val('color=' + $j("#" + thisName + "-color").val() + ';' +
									   'font-face=' + $j("#" + thisName + "-font-face").val() + ';' +
									   'font-weight=' + $j("#" + thisName + "-font-weight").val() + ';' +
									   'font-style=' + $j("#" + thisName + "-font-style").val() + ';' +
									   'font-variant=' + $j("#" + thisName + "-font-variant").val() + ';' +
									   'font-size=' + $j("#" + thisName + "-font-size").val() + ';' +
									   'font-size-type=' + $j("#" + thisName + "-font-size-type").val() + ';'
						);
			});

			$j('.suf-font-options select').change(function(event) {
				var thisName = event.currentTarget.id;
				thisName = thisName.substring(0, thisName.indexOf('-'));
				$j("#" + thisName).val('color=' + $j("#" + thisName + "-color").val() + ';' +
									   'font-face=' + $j("#" + thisName + "-font-face").val() + ';' +
									   'font-weight=' + $j("#" + thisName + "-font-weight").val() + ';' +
									   'font-style=' + $j("#" + thisName + "-font-style").val() + ';' +
									   'font-variant=' + $j("#" + thisName + "-font-variant").val() + ';' +
									   'font-size=' + $j("#" + thisName + "-font-size").val() + ';' +
									   'font-size-type=' + $j("#" + thisName + "-font-size-type").val() + ';'
						);
			});

			$j('.suf-border-options input[type=text]').change(function(event) {
				var thisId = event.currentTarget.id;
				thisId = thisId.substring(0, thisId.indexOf('-'));
				var edges = new Array('top', 'right', 'bottom', 'left');
				var border = '';
				for (var x in edges) {
					var edge = edges[x];
					var thisName = thisId + '-' + edge;
					border += edge + '::';
					border += 'color=' + $j("#" + thisName + "-color").val() + ';' +
							'colortype=' + $j("input[name=" + thisName + "-colortype]:checked").val() + ';' +
							'style=' + $j("#" + thisName + "-style").val() + ';' +
							'border-width=' + $j("#" + thisName + "-border-width").val() + ';' +
							'border-width-type=' + $j("#" + thisName + "-border-width-type").val() + ';';
					border += '||';
				}
				$j('#' + thisId).val(border);
			});

			$j('.suf-border-options input[type=radio]').change(function(event) {
				var thisId = event.currentTarget.name;
				thisId = thisId.substring(0, thisId.indexOf('-'));
				var edges = new Array('top', 'right', 'bottom', 'left');
				var border = '';
				for (var x in edges) {
					var edge = edges[x];
					var thisName = thisId + '-' + edge;
					border += edge + '::';
					border += 'color=' + $j("#" + thisName + "-color").val() + ';' +
							'colortype=' + $j("input[name=" + thisName + "-colortype]:checked").val() + ';' +
							'style=' + $j("#" + thisName + "-style").val() + ';' +
							'border-width=' + $j("#" + thisName + "-border-width").val() + ';' +
							'border-width-type=' + $j("#" + thisName + "-border-width-type").val() + ';';
					border += '||';
				}
				$j('#' + thisId).val(border);
			});

			$j('.suf-border-options select').change(function(event) {
				var thisId = event.currentTarget.id;
				thisId = thisId.substring(0, thisId.indexOf('-'));
				var edges = new Array('top', 'right', 'bottom', 'left');
				var border = '';
				for (var x in edges) {
					var edge = edges[x];
					var thisName = thisId + '-' + edge;
					border += edge + '::';
					border += 'color=' + $j("#" + thisName + "-color").val() + ';' +
							'colortype=' + $j("input[name=" + thisName + "-colortype]:checked").val() + ';' +
							'style=' + $j("#" + thisName + "-style").val() + ';' +
							'border-width=' + $j("#" + thisName + "-border-width").val() + ';' +
							'border-width-type=' + $j("#" + thisName + "-border-width-type").val() + ';';
					border += '||';
				}
				$j('#' + thisId).val(border);
			});

			$j('div.suf-loader').hide();
			$j('a.edit-post-type').live("click", function(){
				var thisId = this.id;
				var add_edit_form = $j('form#form-add-edit-post-type');
				$j('div.suf-loader').show();
				$j.post($j(this).attr("href"), {
					action: "suffusion_display_custom_post_type",
					post_type_index: parseInt(thisId.substr(15))
				}, function(data) {
					add_edit_form.html($j(data));
					$j('div.suf-loader').hide();
				  }
				);

				$j('div.suf-options div.main-content').hide();
				$j('#add-edit-post-type').show();
				$j('div.suf-options ul.tabs li a').removeClass('current-tab');
				$j('ul.tabs li a.add-edit-post-type').addClass('current-tab');

				return false;
			});

			$j('a.delete-post-type').live("click", function(){
				var thisId = this.id;
				var list_types_form = $j('form#form-custom-post-types');
				var nonce = $j('#custom_post_types_wpnonce').val();
				var add_edit_type_form = $j('form#form-add-edit-post-type');
				$j('div.suf-loader').show();
				$j.post($j(this).attr("href"), {
					action: "suffusion_display_all_custom_post_types",
					post_type_index: parseInt(thisId.substr(17)),
					processing_function: "delete",
					custom_post_types_wpnonce: nonce
				}, function(data) {
					list_types_form.html($j(data).filter('.suf-custom-post-types-section'));
					add_edit_type_form.html($j(data).filter('.suf-post-type-edit-section'));
					$j('div.suf-loader').hide();
				  }
				);

				$j('div.suf-options div.main-content').hide();
				$j('#custom-post-types').show();
				$j('div.suf-options ul.tabs li a').removeClass('current-tab');
				$j('ul.tabs li a.custom-post-types').addClass('current-tab');

				return false;
			});

			$j('a.edit-taxonomy').live("click", function(){
				var thisId = this.id;
				var add_edit_form = $j('form#form-add-edit-taxonomy');
				$j('div.suf-loader').show();
				$j.post($j(this).attr("href"), {
					action: "suffusion_display_custom_taxonomy",
					taxonomy_index: parseInt(thisId.substr(14))
				}, function(data) {
					add_edit_form.html($j(data));
					$j('div.suf-loader').hide();
				  }
				);

				$j('div.suf-options div.main-content').hide();
				$j('#add-edit-taxonomy').show();
				$j('div.suf-options ul.tabs li a').removeClass('current-tab');
				$j('ul.tabs li a.add-edit-taxonomy').addClass('current-tab');

				return false;
			});

			$j('a.delete-taxonomy').live("click", function(){
				var thisId = this.id;
				var list_types_form = $j('form#form-custom-taxonomies');
				var add_edit_type_form = $j('form#form-add-edit-taxonomy');
				$j('div.suf-loader').show();
				$j.post($j(this).attr("href"), {
					action: "suffusion_display_all_custom_taxonomies",
					taxonomy_index: parseInt(thisId.substr(16)),
					processing_function: "delete"
				}, function(data) {
					list_types_form.html($j(data).filter('.suf-custom-taxonomies-section'));
					add_edit_type_form.html($j(data).filter('.suf-taxonomy-edit-section'));
					$j('div.suf-loader').hide();
				  }
				);

				$j('div.suf-options div.main-content').hide();
				$j('#custom-taxonomies').show();
				$j('div.suf-options ul.tabs li a').removeClass('current-tab');
				$j('ul.tabs li a.custom-taxonomies').addClass('current-tab');

				return false;
			});

			$j('.suf-button-bar').draggable({handle: 'h2'});

			$j('.suf-custom-type-settings input.button').live("click", function() {
				var thisName = this.name;
				var add_edit_post_type_form = $j('form#form-add-edit-post-type');
				var list_post_types_form = $j('form#form-custom-post-types');
				var add_edit_taxonomy_form = $j('form#form-add-edit-taxonomy');
				var list_taxonomies_form = $j('form#form-custom-taxonomies');
				var form_values;
				if (thisName == 'save-post-type-edit') {
					form_values = add_edit_post_type_form.serialize().replace(/%5B/g, '[').replace(/%5D/g, ']');

					$j('div.suf-loader').show();
					$j.post(ajaxurl, 'action=suffusion_save_custom_post_type&'+form_values, function(data) {
						add_edit_post_type_form.html($j(data).filter('.suf-post-type-edit-section'));
						list_post_types_form.html($j(data).filter('.suf-custom-post-types-section'));
						$j('div.suf-loader').hide();
					});

					$j('div.suf-options div.main-content').hide();
					$j('#add-edit-post-type').show();
					$j('div.suf-options ul.tabs li a').removeClass('current-tab');
					$j('ul.tabs li a.add-edit-post-type').addClass('current-tab');
				}
				else if (thisName == 'delete-all-custom-post-types') {
					var nonce = $j('#custom_post_types_wpnonce').val();
					$j('div.suf-loader').show();
					$j.post(ajaxurl, {
						action: "suffusion_display_all_custom_post_types",
						processing_function: "delete_all",
						custom_post_types_wpnonce: nonce
					}, function(data) {
						add_edit_post_type_form.html($j(data).filter('.suf-post-type-edit-section'));
						list_post_types_form.html($j(data).filter('.suf-custom-post-types-section'));
						$j('div.suf-loader').hide();
					});
				}
				else if (thisName == 'reset-post-type-edit') {
					$j(':input','form#form-add-edit-post-type')
							.not(':button, :submit, :reset, :hidden')
							.val('')
							.removeAttr('checked')
							.removeAttr('selected');

					//add_edit_form[0].reset();
					$j("#post_type_index").val("");
				}
				else if (thisName == 'save-taxonomy-edit') {
					form_values = add_edit_taxonomy_form.serialize().replace(/%5B/g, '[').replace(/%5D/g, ']');

					$j('div.suf-loader').show();
					$j.post(ajaxurl, 'action=suffusion_save_custom_taxonomy&'+form_values, function(data) {
						add_edit_taxonomy_form.html($j(data).filter('.suf-taxonomy-edit-section'));
						list_taxonomies_form.html($j(data).filter('.suf-custom-taxonomies-section'));
						$j('div.suf-loader').hide();
					});

					$j('div.suf-options div.main-content').hide();
					$j('#add-edit-taxonomy').show();
					$j('div.suf-options ul.tabs li a').removeClass('current-tab');
					$j('ul.tabs li a.add-edit-taxonomy').addClass('current-tab');
				}
				else if (thisName == 'delete-all-custom-taxonomies') {
					$j('div.suf-loader').show();
					$j.post(ajaxurl, {
						action: "suffusion_display_all_custom_taxonomies",
						processing_function: "delete_all"
					}, function(data) {
						add_edit_taxonomy_form.html($j(data).filter('.suf-taxonomy-edit-section'));
						list_taxonomies_form.html($j(data).filter('.suf-custom-taxonomies-section'));
						$j('div.suf-loader').hide();
					});
				}
				else if (thisName == 'reset-taxonomy-edit') {
					$j(':input','form#form-add-edit-taxonomy')
							.not(':button, :submit, :reset, :hidden')
							.val('')
							.removeAttr('checked')
							.removeAttr('selected');

					//add_edit_form[0].reset();
					$j("#taxonomy_index").val("");
				}

				return false;
			});

		});
		function submit_form(field, form) {
			form.elements['formaction'].value = field.name;
			if (field.name == 'reset_all') {
				if (confirm("This will reset ALL your configurations to the original values!!! Are you sure you want to continue?")) {
					form.submit();
				}
			}
			else if (field.name == 'reset') {
				if (confirm("This will reset all options on this page to the original values!!! Are you sure you want to continue?")) {
					form.submit();
				}
			}
			else {
				form.submit();
			}
		}
		/* ]]> */
	</script>
<?php
}

function suffusion_admin_script_loader() {
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-custom', get_template_directory_uri().'/admin/jquery-ui/jquery-ui-custom.js', array('jquery-ui-core'));
	wp_enqueue_script('jquery-jscolor', get_template_directory_uri().'/admin/jscolor/jscolor.js', array('jquery'));
}

function suffusion_admin_style_loader() {
	wp_enqueue_style('suffusion-admin', get_template_directory_uri().'/admin/admin.css', array(), '3.6.7');
	wp_enqueue_style('suffusion-admin-jq', get_template_directory_uri().'/admin/jquery-ui/css/jquery-ui-1.7.2.custom.css', array('suffusion-admin'), '3.6.4');
}

add_action('admin_init', 'suffusion_admin_init');
function suffusion_admin_init() {
	register_setting('suffusion_post_type_options', 'suffusion_post_types');
	register_setting('suffusion_taxonomy_options', 'suffusion_taxonomies');
}

add_action('wp_ajax_suffusion_save_custom_post_type', 'suffusion_save_custom_post_type');
function suffusion_save_custom_post_type() {
	global $post_type_options, $post_type_labels, $post_type_args, $post_type_supports;
	$post_type_index = $_POST['post_type_index'];
	$suffusion_post_type = $_POST['suffusion_post_type'];

	check_ajax_referer('add-edit-post-type-suffusion', 'add-edit-post-type-wpnonce');
	$suffusion_post_types = get_option('suffusion_post_types');
	$valid = suffusion_validate_custom_type_form($suffusion_post_type, array('options' => $post_type_options, 'labels' => $post_type_labels, 'args' => $post_type_args, 'supports' => $post_type_supports));
	if ($valid) {
		if ($suffusion_post_types == null || !is_array($suffusion_post_types)) {
				$suffusion_post_types = array();
		}
		if (isset($post_type_index) && $post_type_index != '' && $post_type_index != -5) {
			$suffusion_post_types[$post_type_index] = $suffusion_post_type;
			$index = $post_type_index;
		}
		else {
			$suffusion_post_types[] = $suffusion_post_type;
			$index = max(array_keys($suffusion_post_types));
		}

		update_option('suffusion_post_types', $suffusion_post_types);
		suffusion_display_custom_post_type($index, "Post Type saved successfully");
	}
	else {
		suffusion_display_custom_post_type(-1, "NOT SAVED: Please populate all required fields");
	}
	suffusion_display_all_custom_post_types();
}

add_action('wp_ajax_suffusion_display_all_custom_post_types', 'suffusion_display_all_custom_post_types');
function suffusion_display_all_custom_post_types() {
	$delete = "";
	if (isset($_POST['processing_function'])) {
		$processing_function = $_POST['processing_function'];
	}
	else {
		$processing_function = "";
	}
	if ($processing_function == 'delete') {
		$delete = delete_post_type();
		$delete = $delete == "" ? null : "<div id='message' class='updated fade'><p><strong>$delete</strong></p></div>";
	}
	else if ($processing_function == 'delete_all') {
		$delete = suffusion_delete_all_custom_post_types();
		$delete = $delete == "" ? null : "<div id='message' class='updated fade'><p><strong>$delete</strong></p></div>";
	}
	$suffusion_post_types = get_option('suffusion_post_types');
?>
	<div class='suf-custom-post-types-section suf-section'>
		<?php
		echo $delete;
		echo wp_nonce_field('custom_post_types_suffusion', 'custom_post_types_wpnonce', true, false);
		?>
		<p>The following post types exist. You can edit / delete any of these. Note that if you edit / delete the name of any of these, it will not delete associated posts. You can recreate these post types again and everything will be back to normal:</p>
		<input type="hidden" name="post_type_index" value="" />
		<input type="hidden" name="formaction" value="default" />

		<table class='custom-type-list'>
			<tr>
				<th>Post Type</th>
				<th>Name</th>
				<th>Singular Name</th>
				<th>Action</th>
			</tr>
<?php
	if (is_array($suffusion_post_types)) {
		foreach ($suffusion_post_types as $id => $custom_post_type) {
?>
			<tr>
				<td><?php echo $custom_post_type['post_type']; ?></td>
				<td><?php echo $custom_post_type['labels']['name']; ?></td>
				<td><?php echo $custom_post_type['labels']['singular_name']; ?></td>
				<td><a class='edit-post-type' id='edit-post-type-<?php echo $id; ?>' href='<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php'>Edit</a> | <a class='delete-post-type' id='delete-post-type-<?php echo $id; ?>' href='<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php'>Delete</a></td>
			</tr>
<?php
		}
	}
?>
		</table>

		<div class="suf-button-bar">
			<h2>Custom Type Actions</h2>
			<label><input name="delete-all-custom-post-types" type="button" value="Delete All Custom Post Types" class="button delete-all-custom-post-types" /></label>
		</div><!-- suf-button-bar -->
	</div><!-- .suf-custom-post-types-section -->
<?php
	if ($processing_function == 'delete' || $processing_function == 'delete_all') {
		suffusion_display_custom_post_type(-1);
	}
}

add_action('wp_ajax_suffusion_modify_post_type_layout', 'suffusion_modify_post_type_layout');
function suffusion_modify_post_type_layout() {
	$layout_positions = array('hide' => 'Do not show', 'tleft' => 'Below title, left', 'tright' => 'Below title, right',
		'bleft' => 'Below content, left', 'bright' => 'Below content, right');
	$delete = "";
	$processing_function = $_POST['processing_function'];
	if (isset($processing_function) && $processing_function == 'delete') {
		$delete = delete_post_type();
		$delete = $delete == "" ? null : "<div id='message' class='updated fade'><p><strong>$delete</strong></p></div>";
	}
	else if (isset($processing_function) && $processing_function == 'delete_all') {
		$delete = suffusion_delete_all_custom_post_types();
		$delete = $delete == "" ? null : "<div id='message' class='updated fade'><p><strong>$delete</strong></p></div>";
	}
	$suffusion_post_types = get_option('suffusion_post_types');
?>
	<div class='suf-modify-post-type-layout-section suf-section'>
		<?php echo $delete; ?>
		<p>The following post types exist. You can edit / delete any of these. Note that if you edit / delete the name of any of these, it will not delete associated posts. You can recreate these post types again and everything will be back to normal:</p>
		<input type="hidden" name="post_type_index" value="" />
		<input type="hidden" name="formaction" value="default" />

		<table class='custom-type-list'>
			<tr>
				<th>Post Type</th>
				<th>Position of Elements</th>
			</tr>
<?php
	if (is_array($suffusion_post_types)) {
		foreach ($suffusion_post_types as $id => $custom_post_type) {
?>
			<tr>
				<td><?php echo $custom_post_type['post_type']; ?></td>
				<td>
					<?php
						$custom_post_type_supports = $custom_post_type['options'];
						if (in_array('author', $custom_post_type_supports)) {
							echo "Author Position: ";
						}
					?>
				</td>
<!--				<td><?php echo $custom_post_type['labels']['name']; ?></td>
				<td><?php echo $custom_post_type['labels']['singular_name']; ?></td> -->
<!--				<td><a class='edit-post-type' id='edit-post-type-<?php echo $id; ?>' href='<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php'>Edit</a> | <a class='delete-post-type' id='delete-post-type-<?php echo $id; ?>' href='<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php'>Delete</a></td> -->
			</tr>
<?php
		}
	}
?>
		</table>

		<div class="suf-button-bar">
			<h2>Custom Type Actions</h2>
			<label><input name="save-post-type-layouts" type="button" value="Save Post Type Layouts" class="button delete-all-custom-post-types" /></label>
		</div><!-- suf-button-bar -->
	</div><!-- .suf-modify-post-type-layout-section -->
<?php
	if ($processing_function == 'delete' || $processing_function == 'delete_all') {
		suffusion_display_custom_post_type(-1);
	}
}

function delete_post_type() {
	// For some reason a blank nonce is being fetched here even if I do $_POST['custom_post_types_wpnonce']. Weird
	check_ajax_referer('custom_post_types_suffusion', 'custom_post_types_wpnonce');
	$post_type_index = $_POST['post_type_index'];
	$ret = "";
	if (isset($post_type_index)) {
		$suffusion_post_types = get_option('suffusion_post_types');
		if (is_array($suffusion_post_types)) {
			unset($suffusion_post_types[$post_type_index]);
			update_option('suffusion_post_types', $suffusion_post_types);
			$ret = "Post type deleted.";
		}
		else {
			$ret = "Failed to delete post type. Post types are not stored as an array in the database.";
		}
	}
	return $ret;
}

function suffusion_delete_all_custom_post_types() {
	check_ajax_referer('custom_post_types_suffusion', 'custom_post_types_wpnonce');
	$option = get_option('suffusion_post_types');
	if (isset($option) && is_array($option)) {
		$ret = delete_option('suffusion_post_types');
		if ($ret) {
			$ret = "All post types deleted.";
		}
		else {
			$ret = "Failed to delete post types.";
		}
	}
	else {
		$ret = "No post types exist!";
	}
	return $ret;
}

add_action('wp_ajax_suffusion_display_custom_post_type', 'suffusion_display_custom_post_type');
function suffusion_display_custom_post_type($index = null, $msg = null) {
	global $post_type_labels, $post_type_args, $post_type_supports, $post_type_options;
	if (isset($_POST['post_type_index'])) {
		$post_type_index = $_POST['post_type_index'];
	}
	else {
		$post_type_index = -5;
	}
	$suffusion_post_types = get_option('suffusion_post_types');
	if (is_array($suffusion_post_types) && $post_type_index != '' && $post_type_index != -5) {
		$suffusion_post_type = $suffusion_post_types[$post_type_index];
	}
	else if (is_array($suffusion_post_types) && ($post_type_index =='' || $post_type_index == -5) && ($index > -1)) {
		$suffusion_post_type = $suffusion_post_types[$index];
	}
	else if (isset($_POST['suffusion_post_type']) && ($post_type_index =='' || $post_type_index == -5) && $index == -1) {
		$suffusion_post_type = $_POST['suffusion_post_type'];
	}
	else {
		$suffusion_post_type = array('labels' => $post_type_labels, 'args' => $post_type_args, 'supports' => $post_type_supports);
		foreach ($suffusion_post_type as $parameter_type => $parameters) {
			foreach ($parameters as $parameter => $parameter_value) {
				$suffusion_post_type[$parameter_type][$parameter] = FALSE;
			}
		}
	}

	$msg = $msg == null ? null : "<div id='message' class='updated fade'><p><strong>$msg</strong></p></div>";
?>
<div class='suf-post-type-edit-section suf-section'>
	<?php
	echo $msg;
	echo wp_nonce_field('add-edit-post-type-suffusion', 'add-edit-post-type-wpnonce', true, false);
	?>
	<input type='hidden' name='post_type_index' id='post_type_index' value="<?php echo $post_type_index; ?>"/>
	<table>
		<?php
			foreach ($post_type_options as $option) {
		?>
		<tr>
			<?php process_custom_type_option($option, null, $suffusion_post_type, 'suffusion_post_type'); ?>
		</tr>
		<?php
			}
		?>
	</table>

	<table class="custom-type-table">
		<tr>
			<col class='half-width' />
			<col/>
		</tr>
		<tr valign='top'>
			<th scope='row'>Display information</th>
			<th scope='row'>Arguments</th>
		</tr>
		<tr>
			<td rowspan='2'>
				<table>
					<?php foreach ($post_type_labels as $label) { ?>
					<tr>
						<?php process_custom_type_option($label, 'labels', $suffusion_post_type, 'suffusion_post_type'); ?>
					</tr>
					<?php } ?>
				</table>
			</td>

			<td>
				<table>
					<?php foreach ($post_type_args as $arg) { ?>
					<tr>
						<?php process_custom_type_option($arg, 'args', $suffusion_post_type, 'suffusion_post_type'); ?>
					</tr>
					<?php } ?>
				</table>
			</td>
		</tr>

		<tr>
			<td>
				<table width='100%'>
					<tr>
						<th>Supports</th>
					</tr>

					<tr>
						<td>
							<table>
								<?php foreach ($post_type_supports as $support) { ?>
								<tr>
									<?php process_custom_type_option($support, 'supports', $suffusion_post_type, 'suffusion_post_type'); ?>
								</tr>
								<?php } ?>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div class="suf-button-bar">
		<h2>Custom Type Actions</h2>
		<label><input name="save-post-type-edit" type="button" value="Save changes" class="button save-post-type-edit" /></label>
		<label><input name="reset-post-type-edit" type="button" value="Clear all fields" class="button reset-post-type-edit" /></label>
		<input type="hidden" name="formaction" value="default" />
		<input type="hidden" name="formcategory" value="add-edit-post-type" />
	</div><!-- suf-button-bar -->
</div><!-- suf-post-type-edit-section -->
<?php
}

add_action('wp_ajax_suffusion_save_custom_taxonomy', 'suffusion_save_custom_taxonomy');
function suffusion_save_custom_taxonomy() {
	global $taxonomy_options, $taxonomy_labels, $taxonomy_args;
	$taxonomy_index = $_POST['taxonomy_index'];
	$suffusion_taxonomy = $_POST['suffusion_taxonomy'];
	$valid = suffusion_validate_custom_type_form($suffusion_taxonomy, array('options' => $taxonomy_options, 'labels' => $taxonomy_labels, 'args' => $taxonomy_args));
	if ($valid) {
		$suffusion_taxonomies = get_option('suffusion_taxonomies');
		if ($suffusion_taxonomies == null || !is_array($suffusion_taxonomies)) {
			$suffusion_taxonomies = array();
		}
		if (isset($taxonomy_index) && $taxonomy_index != '' && $taxonomy_index != -5) {
			$suffusion_taxonomies[$taxonomy_index] = $suffusion_taxonomy;
			$index = $taxonomy_index;
		}
		else {
			$suffusion_taxonomies[] = $suffusion_taxonomy;
			$index = max(array_keys($suffusion_taxonomies));
		}

		update_option('suffusion_taxonomies', $suffusion_taxonomies);
		suffusion_display_custom_taxonomy($index, "Taxonomy saved successfully");
	}
	else {
		suffusion_display_custom_taxonomy(-1, "NOT SAVED: Please populate all required fields");
	}
	suffusion_display_all_custom_taxonomies();
}

add_action('wp_ajax_suffusion_display_all_custom_taxonomies', 'suffusion_display_all_custom_taxonomies');
function suffusion_display_all_custom_taxonomies() {
	$delete = "";
	if (isset($_POST['processing_function'])) {
		$processing_function = $_POST['processing_function'];
	}
	else {
		$processing_function = "";
	}
	if ($processing_function == 'delete') {
		$delete = delete_taxonomy();
		$delete = $delete == "" ? null : "<div id='message' class='updated fade'><p><strong>$delete</strong></p></div>";
	}
	else if ($processing_function == 'delete_all') {
		$delete = suffusion_delete_all_custom_taxonomies();
		$delete = $delete == "" ? null : "<div id='message' class='updated fade'><p><strong>$delete</strong></p></div>";
	}
	$suffusion_taxonomies = get_option('suffusion_taxonomies');
?>
	<div class='suf-custom-taxonomies-section suf-section'>
		<?php
		echo $delete;
		echo wp_nonce_field('custom-taxonomies-suffusion', 'custom-taxonomies-wpnonce', true, false);
		?>
		<p>The following taxonomies exist. You can edit / delete any of these. Note that if you edit / delete the name of any of these, it will not delete associated posts. You can recreate these taxonomies again and everything will be back to normal:</p>
		<input type="hidden" name="taxonomy_index" value="" />
		<input type="hidden" name="formaction" value="default" />

		<table class='custom-type-list'>
			<tr>
				<th>Taxonomy</th>
				<th>Object Type</th>
				<th>Name</th>
				<th>Singular Name</th>
				<th>Action</th>
			</tr>
<?php
	if (is_array($suffusion_taxonomies)) {
		foreach ($suffusion_taxonomies as $id => $custom_taxonomy) {
?>
			<tr>
				<td><?php echo $custom_taxonomy['taxonomy']; ?></td>
				<td><?php echo $custom_taxonomy['object_type']; ?></td>
				<td><?php echo $custom_taxonomy['labels']['name']; ?></td>
				<td><?php echo $custom_taxonomy['labels']['singular_name']; ?></td>
				<td><a class='edit-taxonomy' id='edit-taxonomy-<?php echo $id; ?>' href='<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php'>Edit</a> | <a class='delete-taxonomy' id='delete-taxonomy-<?php echo $id; ?>' href='<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php'>Delete</a></td>
			</tr>
<?php
		}
	}
?>
		</table>

		<div class="suf-button-bar">
			<h2>Custom Type Actions</h2>
			<label><input name="delete-all-custom-taxonomies" type="button" value="Delete All Custom Taxonomies" class="button delete-all-custom-taxonomies" /></label>
		</div><!-- suf-button-bar -->
	</div><!-- .suf-custom-taxonomies-section -->
<?php
	if ($processing_function == 'delete' || $processing_function == 'delete_all') {
		suffusion_display_custom_taxonomy(-1);
	}
}

add_action('wp_ajax_suffusion_display_custom_taxonomy', 'suffusion_display_custom_taxonomy');
function suffusion_display_custom_taxonomy($index = null, $msg = null) {
	global $taxonomy_labels, $taxonomy_args, $taxonomy_options;
	if (isset($_POST['taxonomy_index'])) {
		$taxonomy_index = $_POST['taxonomy_index'];
	}
	else {
		$taxonomy_index = -5;
	}
	$suffusion_taxonomies = get_option('suffusion_taxonomies');
	if (is_array($suffusion_taxonomies) && $taxonomy_index != '' && $taxonomy_index != -5) {
		$suffusion_taxonomy = $suffusion_taxonomies[$taxonomy_index];
	}
	else if (is_array($suffusion_taxonomies) && ($taxonomy_index =='' || $taxonomy_index == -5) && ($index > -1)) {
		$suffusion_taxonomy = $suffusion_taxonomies[$index];
	}
	else if (isset($_POST['suffusion_taxonomy']) && ($taxonomy_index =='' || $taxonomy_index == -5) && $index == -1) {
		$suffusion_taxonomy = $_POST['suffusion_taxonomy'];
	}
	else {
		$suffusion_taxonomy = array('labels' => $taxonomy_labels, 'args' => $taxonomy_args);
		foreach ($suffusion_taxonomy as $parameter_type => $parameters) {
			foreach ($parameters as $parameter => $parameter_value) {
				$suffusion_taxonomy[$parameter_type][$parameter] = FALSE;
			}
		}
	}

	$msg = $msg == null ? null : "<div id='message' class='updated fade'><p><strong>$msg</strong></p></div>";
?>
<div class='suf-taxonomy-edit-section suf-section'>
	<?php
	echo $msg;
	echo wp_nonce_field('add-edit-taxonomy-suffusion', 'add-edit-taxonomy-wpnonce', true, false);
	?>
	<input type='hidden' name='taxonomy_index' id='taxonomy_index' value="<?php echo $taxonomy_index; ?>"/>
	<table>
	<?php
		foreach ($taxonomy_options as $option) {
	?>
	<tr>
		<?php process_custom_type_option($option, null, $suffusion_taxonomy, 'suffusion_taxonomy'); ?>
	</tr>
	<?php
		}
	?>
	</table>

	<table class="custom-type-table">
		<tr>
			<col class='half-width' />
			<col/>
		</tr>
		<tr valign='top'>
			<th scope='row'>Display information</th>
			<th scope='row'>Arguments</th>
		</tr>
		<tr>
			<td>
				<table>
					<?php foreach ($taxonomy_labels as $label) { ?>
					<tr>
						<?php process_custom_type_option($label, 'labels', $suffusion_taxonomy, 'suffusion_taxonomy'); ?>
					</tr>
					<?php } ?>
				</table>
			</td>

			<td>
				<table>
					<?php foreach ($taxonomy_args as $arg) { ?>
					<tr>
						<?php process_custom_type_option($arg, 'args', $suffusion_taxonomy, 'suffusion_taxonomy'); ?>
					</tr>
					<?php } ?>
				</table>
			</td>
		</tr>
	</table>

	<div class="suf-button-bar">
		<h2>Custom Type Actions</h2>
		<label><input name="save-taxonomy-edit" type="button" value="Save changes" class="button save-taxonomy-edit" /></label>
		<label><input name="reset-taxonomy-edit" type="button" value="Clear all fields" class="button reset-taxonomy-edit" /></label>
		<input type="hidden" name="formaction" value="default" />
		<input type="hidden" name="formcategory" value="add-edit-taxonomy" />
	</div><!-- suf-button-bar -->
</div><!-- suf-taxonomy-edit-section -->
<?php
}

function delete_taxonomy() {
	$taxonomy_index = $_POST['taxonomy_index'];
	$ret = "";
	if (isset($taxonomy_index)) {
		$suffusion_taxonomies = get_option('suffusion_taxonomies');
		if (is_array($suffusion_taxonomies)) {
			unset($suffusion_taxonomies[$taxonomy_index]);
			update_option('suffusion_taxonomies', $suffusion_taxonomies);
			$ret = "Taxonomy deleted.";
		}
		else {
			$ret = "Failed to delete taxonomy. Taxonomies are not stored as an array in the database.";
		}
	}
	return $ret;
}

function suffusion_delete_all_custom_taxonomies() {
	$option = get_option('suffusion_taxonomies');
	if (isset($option) && is_array($option)) {
		$ret = delete_option('suffusion_taxonomies');
		if ($ret) {
			$ret = "All taxonomies deleted.";
		}
		else {
			$ret = "Failed to delete taxonomies.";
		}
	}
	else {
		$ret = "No taxonomies exist!";
	}
	return $ret;
}

function suffusion_validate_custom_type_form($custom_type, $validation_options) {
	foreach ($validation_options as $option_type => $option_set) {
		if ($option_type == 'options') {
			$to_validate = $custom_type;
		}
		else {
			if (isset($custom_type[$option_type])) {
				$to_validate = $custom_type[$option_type];
			}
		}
		foreach ($option_set as $option) {
			if (isset($option['reqd'])) {
				if (isset($to_validate[$option['name']]) && trim($to_validate[$option['name']]) == '') {
					return false;
				}
			}
		}
	}
	return true;
}

// Draw the menu page itself
function suffusion_render_custom_types() {
?>
	<div class="wrap suf-custom-type-settings">
		<div class="suf-loader"><img src='<?php echo get_template_directory_uri(); ?>/admin/images/ajax-loader-large.gif' alt='Processing'></div>
		<div class="suf-tabbed-options">
			<h2 class='suf-header-1'>Custom Types for Suffusion</h2>
		<?php

	echo "<ul class='htabs fix'>\n";
		echo "<li class='htab-post-types level-2'>Custom Post Types</li>\n";
		echo "<li class='htab-taxonomies level-2'>Custom Taxonomies</li>\n";
	echo "</ul><!-- .htabs -->\n";

	echo "<div class='content-holder fix'>\n";
		echo "<div class='suf-options suf-options-post-types'>";
			echo "<h1>Custom Post Types</h1>\n";
			echo "<ul class='tabs'>\n";
				echo "<li class='custom-post-types'><a class=\"custom-post-types level-3\">Existing Post Types</a></li>\n";
				echo "<li class='add-edit-post-type'><a class=\"add-edit-post-type level-3\">Add / Edit Post Type</a></li>\n";
				//echo "<li class='modify-post-type-layout'><a class=\"modify-post-type-layout level-3\">Modify Post Type Layout</a></li>\n";
			echo "</ul>\n";

			echo "<div class='custom-post-types main-content' id='custom-post-types'>";
			echo "<h3 class='suf-header-2'>Existing Post Types</h3>\n";
			echo '<form method="post" name="form-custom-post-types" id="form-custom-post-types" action="options.php">';
			suffusion_display_all_custom_post_types();
			echo '</form>';
			echo "</div><!-- .custom-post-types -->\n";

			echo "<div class='add-edit-post-type main-content' id='add-edit-post-type'>";
			echo "<h3 class='suf-header-2'>Add / Edit Post Type</h3>\n";
		?>
			<form method="post" name="form-add-edit-post-type" id="form-add-edit-post-type" action="options.php">
			<?php
				suffusion_display_custom_post_type(-1);
			?>
			</form>
		<?php
			echo "</div><!-- .add-edit-post-type -->\n";

/*
			echo "<div class='modify-post-type-layout main-content' id='modify-post-type-layout'>";
			echo "<h3 class='suf-header-2'>Modify Post Type Layouts</h3>\n";
			echo '<form method="post" name="form-modify-post-type-layout" id="form-modify-post-type-layout" action="options.php">';
			suffusion_modify_post_type_layout();
			echo '</form>';
			echo "</div><!-- .modify-post-type-layout -->\n";
*/
		echo "</div><!-- .suf-options-post-types -->\n";

		echo "<div class='suf-options suf-options-taxonomies'>";
			echo "<h1>Custom Taxonomies</h1>\n";
			echo "<ul class='tabs'>\n";
				echo "<li class='custom-taxonomies'><a class=\"custom-taxonomies level-3\">Existing Taxonomies</a></li>\n";
				echo "<li class='add-edit-taxonomy'><a class=\"add-edit-taxonomy level-3\">Add / Edit Taxonomy</a></li>\n";
			echo "</ul>\n";

			echo "<div class='custom-taxonomies main-content' id='custom-taxonomies'>";
			echo "<h3 class='suf-header-2'>Existing Taxonomies</h3>\n";
			echo '<form method="post" name="form-custom-taxonomies" id="form-custom-taxonomies" action="options.php">';
			suffusion_display_all_custom_taxonomies();
			echo '</form>';
			echo "</div><!-- .custom-taxonomies -->\n";

			echo "<div class='add-edit-taxonomy main-content' id='add-edit-taxonomy'>";
			echo "<h3 class='suf-header-2'>Add / Edit Taxonomy</h3>\n";
		?>
			<form method="post" name="form-add-edit-taxonomy" id="form-add-edit-taxonomy" action="options.php">
			<?php
				suffusion_display_custom_taxonomy(-1);
			?>
			</form>
		<?php
			echo "</div><!-- .add-edit-taxonomies -->\n";
		echo "</div><!-- .suf-options-taxonomies -->\n";
	echo "</div><!-- .content-holder -->\n";
	echo "</div><!-- .tabbed-options -->\n";
	echo "</div><!-- .wrap -->\n";
}

add_action('admin_menu', 'suffusion_add_menu_pages');
function suffusion_add_menu_pages() {
//	$suffusion_post_types_page = add_submenu_page(basename(__FILE__), "Custom Types", "Custom Types", "manage_options", "suffusion-custom-types", 'suffusion_render_custom_types');
	$suffusion_post_types_page = add_submenu_page('theme-options-intro.php', "Custom Types", "Custom Types", "manage_options", "suffusion-custom-types", 'suffusion_render_custom_types');
	add_action("admin_head-$suffusion_post_types_page", 'suf_admin_header_style');
	add_action("admin_print_scripts-$suffusion_post_types_page", 'suffusion_admin_script_loader');
	add_action("admin_print_styles-$suffusion_post_types_page", 'suffusion_admin_style_loader');
}
?>