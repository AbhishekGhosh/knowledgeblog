<?php
  /*
   Plugin Name: Knowledgeblog DOI Management 
   Plugin URI: http://knowledgeblog.org/knowledgeblog-doi-plugin/
   Description: Manages DOIs on posts in Knowledgeblogs.
   Version: 0.1
   Author: Simon Cockell
   Author URI: http://knowledgeblog.org
   License: GPL2

   Copyright 2010. Simon Cockell (s.j.cockell@newcastle.ac.uk)
   Newcastle University. 
  
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License, version 2, as 
   published by the Free Software Foundation.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  */


class kb_doi{

  function init(){
    register_activation_hook(__FILE__, array(__CLASS__, 'kdoi_install'));
    
    //admin check
    add_action('add_meta_boxes', array(__CLASS__, 'kdoi_metabox'));
    add_action('save_post', array(__CLASS__, 'kdoi_save_postdata'));
    add_filter('plugin_action_links', array(__CLASS__, 'ktoc_settings_link'), 9, 2 );
  }

  function kdoi_install() {
    //registers default options
  }
  
  function debug(){
    echo "A debug statement";
  }

  function kdoi_metabox() {
    add_meta_box( 'doi_sectionid', __('DOI Data', 'kdoi_textdomain'), array(__CLASS__, 'kdoi_inner_custom_box'), 'post', 'side', 'default' );
  }
  function kdoi_inner_custom_box() {
      global $post;
      // Use nonce for verification
      wp_nonce_field( plugin_basename(__FILE__), 'kdoi_noncename' );
      $doi = get_post_meta($post->ID, 'doi');
      if ($doi[0] != null) {
        echo "This post has a DOI<br/>doi:".$doi[0];
      }
      else {
        $message = get_post_meta($post->ID, 'doi_message');
        echo $message[0]."<br/>";
        echo '<label for="kdoi_field">' . __("Enter post DOI here:", 'kdoi_textdomain' ) . '</label> ';
        echo '<input type="text" id= "kdoi_field" name="kdoi_field" value="10.4124/" size="25" />';
      }
  }

  function kdoi_save_postdata( $post_id ) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['kdoi_noncename'], plugin_basename(__FILE__) )) {
        return $post_id;
    }

    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;

  
    // Check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return $post_id;
    }

    // OK, we're authenticated: we need to find and save the data
    
    $my_doi = $_POST['kdoi_field'];

    //check doi against Datacite with a GET
    $datacite_url = "https://datacite.org.uk/api/dataset?doi=$my_doi";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $datacite_url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/xml'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $datacite_xml = curl_exec($ch);
    
    if ($datacite_xml == '') {
        //no xml, no matching DOI (so entry is wrong).
        //post message to this effect.
        update_post_meta($post_id, 'doi_message', 'ERROR: Not a Datacite DOI');
        update_post_meta($post_id, 'doi', null);
        return $post_id;
    }
    else {
        //check the <url> from the xml, to see if it matches the post permalink
        $perm = get_permalink($post_id);
        preg_match('#<url>(.+)</url>#', $datacite_xml, $matches);
        $doi_url = $matches[1];
        if ($doi_url == $perm) {
            //add doi to post metadata
            update_post_meta($post_id, 'doi_message', null);
            update_post_meta($post_id, 'doi', $my_doi);
        }
        else {
            //doi url and post url do not match, post a message to this effect.
            update_post_meta($post_id, 'doi_message', "ERROR: DOI does not match post permalink");
            update_post_meta($post_id, 'doi', null);
        }
    }
    return $my_doi;
  }

  //add a link to settings on the plugin management page
  function kdoi_settings_link( $links, $file ) {
    if ($file == 'knowledgeblog-table-of-contents/kblog-table-of-contents.php' && function_exists('admin_url')) {
        $settings_link = '<a href="' .admin_url('options-general.php?page=kblog-table-of-contents.php').'">'. __('Settings') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
  }

  function kdoi_menu() {
    add_options_page('Knowledgeblog-DOI Plugin Options', 'Knowledgeblog DOI Plugin', 'manage_options', 'kb_doi', array(__CLASS__, 'kdoi_plugin_options'));
  }

  function kdoi_plugin_options() {
      if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
      }
      echo '<div class="wrap" id="ktoc-options">
<h2>Kblog Table of Contents Plugin Options</h2>
';
    if ($_POST['kdoi_hidden'] == 'Y') {
        //process form
        //update_option('display_category', $_POST['display_category']);
        echo '<p><i>Options updated</i></p>';
    }
?>   
      <form id="kdoi" name="kdoi" action="" method='POST'>
      <input type="hidden" name="kdoi_hidden" value="Y">
      <!--
      <table class="form-table">
      <tr valign="middle">
      <th scope="row">Select Category to Display<br/>
	  <font size="-2">Which category do you want displayed in your Table of Contents?</font></th>
      <td><select name="display_category"><?php 
		$categories = get_categories();
		$selected = get_option('display_category');
		foreach ($categories as $cat) {
			$name = $cat->cat_name;
				echo "<option value='$name'";
			if ($name == $selected) {
				echo "SELECTED";
			}
				echo ">$name</option>\n";
		}
      ?>
	  </select>
	  </td>
      </tr>
      </table>
      -->
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
      </form>
      </div>
<?php
  }

}

kb_doi::init();

/*
 function mathjax_latex_hooks_footer()
 {
 $blogsurl = get_bloginfo('wpurl') . '/wp-content/plugins/' 
 . basename(dirname(__FILE__));
 echo '<script type="text/javascript" src="' . $blogsurl . '/MathJax/MathJax.js"></script>';

 }
*/




?>
