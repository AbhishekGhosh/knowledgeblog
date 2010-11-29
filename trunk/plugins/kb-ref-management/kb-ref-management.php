<?php
  /*
   Plugin Name: Knowledgeblog Reference Manager
   Plugin URI: http://knowledgeblog.org/
   Description: Manage references in blogposts
   Version: 0.1
   Author: Simon Cockell
   Author URI: http://knowledgeblog.org
   
   Copyright 2010. Simon Cockell (s.j.cockell@newcastle.ac.uk)
   Newcastle University. 
   
  */


class RefManager{

  function init(){
    register_activation_hook(__FILE__, array(__CLASS__, 'refman_install'));
    
    /*add_shortcode('doi',
                  array(__CLASS__, 'doi_shortcode' ));
    
    add_shortcode('pmid', 
                  array(__CLASS__, 'pmid_shortcode' ));
    */
    add_filter('the_content', array(__CLASS__, 'process_doi'));
    //shortcodes only allow in-place replacement, so not addition of biblio to foot of post.
    //could deal with this by having seperate shortcode for bibliography, or just using plain filters. 
    //A further point is that shortcode processors don't know about the post_ID, so can I add metadata? Need to do this so we don't need to look up every time the post is loaded. Could they know, without having it passed as a ref?
    /*add_action('wp_footer', 
               array(__CLASS__, 'add_script'));
    
    add_action('wp_footer', 
               array(__CLASS__, 'unconditional'));

    if (get_option('wp_latex_enabled')) {
        add_filter( 'the_content', array(__CLASS__, 'inline_to_shortcode' ) );
    }
    */
    add_action('admin_menu', array(__CLASS__, 'refman_menu'));

    add_filter('plugin_action_links', array(__CLASS__, 'refman_settings_link'), 9, 2 );
  }

  function refman_install() {
    /*
    //registers default options
    add_option('force_load', FALSE);
    add_option('latex_syntax', 'inline');
    //test for wp-latex here
    if (method_exists('WP_LaTeX', 'init')) {
        add_option('wp_latex_enabled', FALSE);
    }
    else {
        add_option('wp_latex_enabled', TRUE);
    }
    */
  }
  
  function debug(){
    echo "Simon's debug statement";
  }

  /*
  function doi_shortcode($atts,$content){
    $post_id = get_the_id();
    //retrieve the references from the database - if the post has been loaded before, they should be stored, then we don't have to fetch it back.
    //get_post_meta($post_id, $key, $single);
    //self::check_doi($content);
    //use to add references to WP database, so we don't have to retrieve every time.
    //add_post_meta($post_id, $meta_key, $meta_value, $unique);
    //eg
    //add_post_meta($post_id, 'paperxml', $xml, False);
    return get_the_id()." DOI!<br/>";
  }
    */
  function process_doi($content) {
    //find dois in the_content
    //doi regex assumes dois start with '10.' - seems reasonable?
    //also - should we optionally allow ^doi:?
    $preg = "#\[doi\](10\..*?)\[\/doi\]#"; //make sure this is non-greedy
    preg_match_all($preg, $content, $dois);
    echo "<pre>";
    print_r($dois[0]);
    print_r($dois[1]);
    echo "</pre>";
    //need to make sure we deal with duplicate DOIs here
    $replacees = array_unique($dois[0]);
    $uniq_doi = array_unique($dois[1]);
    $i = 0;
    while ($i < count($replacees)) {
        $doi = $uniq_doi[$i];
        $article = self::get_pub_info($doi);
        echo "<pre>";
        echo $article;
        echo "</pre>";
        $replacer = "<sup>".strval($i+1)."</sup>";
        $content = str_replace($replacees[$i], $replacer, $content);
        $i++;
    }
    //call CrossRef to process them
    //http://www.crossref.org/openurl/?id=doi:10.3998/3336451.0009.101&noredirect=true&pid=s.j.cockell@newcastle.ac.uk&format=unixref
    //make array of xmls
    //add bibliography and in place pointers
    return $content;
  }

  private function get_pub_info($pub_doi) {
    $url = "http://www.crossref.org/openurl/?noredirect=true&pid=s.j.cockell@newcastle.ac.uk&format=unixref&id=doi:".$pub_doi;
    echo $url;
    return file_get_contents($url, 0);
    $handle = fopen($url, "r");
    if ($xml = fread($handle)) {
        fclose($handle);
        return $xml;
    }
    else {
        return "ERROR";
    }
  }

  private function check_doi() {
  }

  function pmid_shortcode($atts,$content){
    return "PMID!<br/>";
  }
  
  function latex_shortcode($atts,$content)
  {
    //this gives us an optional "syntax" attribute, which defaults to "inline", but can also be "display"
    extract(shortcode_atts(array(
                'syntax' => get_option('latex_syntax'),
            ), $atts));
  }

  function add_script(){
    if( !self::$add_script )
      return;
    
    if( self::$block_script )
      return;

    wp_register_script( 'mathjax', 
                        plugins_url('MathJax/MathJax.js',__FILE__),
                        false, null, true );

    wp_print_scripts( 'mathjax' );
  }
  
  //add a link to settings on the plugin management page
  function refman_settings_link( $links, $file ) {
    if ($file == 'kb-ref-management/kb-ref-management.php' && function_exists('admin_url')) {
        $settings_link = '<a href="' .admin_url('options-general.php?page=kb-ref-management.php').'">'. __('Settings') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
  }

  function refman_menu() {
    add_options_page('Reference Management Plugin Options', 'Knowledgeblgo Reference Management Plugin', 'manage_options', 'kb-ref-management', array(__CLASS__, 'refman_plugin_options'));
  }

  function refman_plugin_options() {
      if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
      }
      echo '<div class="wrap" id="refman-options">
<h2>Knowledgeblog Reference Management Plugin Options</h2>
';
    if ($_POST['refman_hidden'] == 'Y') {
        //process form
        /*if ($_POST['force_load']) {
            update_option('force_load', TRUE);
        }
        else {
            update_option('force_load', FALSE);
        }
        if ($_POST['wp_latex_enabled']) {
            update_option('wp_latex_enabled', TRUE);
        }
        else {
            update_option('wp_latex_enabled', FALSE);
        }
        if ($_POST['latex_syntax'] != get_option('latex_syntax')) {
            update_option('latex_syntax', $_POST['latex_syntax']);
        }*/
        echo '<p><i>Options updated</i></p>';   
    }
?>   
      <form id="refman" name="refman" action="" method='POST'>
      <input type="hidden" name="refman_hidden" value="Y">
      <table class="form-table">
      <!--tr valign="middle">
      <th scope="row">Force Load<br/><font size="-2">Force MathJax javascript to be loaded on every post (Removes the need to use the &#91;mathjax&#93; shortcode).</font></th>
      <td><input type="checkbox" name="force_load" value="1"<?php 
      if (get_option('force_load')) {
        echo 'CHECKED';
      }
      ?>/></td>
      </tr>
      <tr valign="middle">
      <th scope="row">Default &#91;latex&#93; syntax attribute.<br/><font size='-2'>By default, the &#91;latex&#93; shortcode renders equations using the MathJax '<?php get_option('latex_syntax') ?>' syntax.</font></th>
      <td><select name='latex_syntax'>
            <option value='inline' <?php if (get_option('latex_syntax') == 'inline') echo 'SELECTED'; ?>>Inline</option>
            <option value='display' <?php if (get_option('latex_syntax') == 'display') echo 'SELECTED'; ?>>Display</option>
          </select>
      </td>
      </tr>
      <tr valign="middle">
      <th scope="row">Use wp-latex syntax?<br/><font size="-2">Allows use of the $latex$ wp-latex syntax. Conflicts with wp-latex.</font></th>
      <td><input type="checkbox" name="wp_latex_enabled" value="1"<?php 
      if (method_exists('WP_LaTeX', 'init')) {
        update_option('wp_latex_enabled', FALSE);
        echo 'DISABLED';
      }
      if (get_option('wp_latex_enabled')) {
        echo 'CHECKED';
      }
      //test for wp-latex
      ?>/>
      <?php
        if (method_exists('WP_LaTeX', 'init')) {
            echo '<br/>
<font size="-2">Uninstall wp-latex to be able to use this syntax</font>
';
        }
      ?>
      </td>
      </tr-->
      </table>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
      </form>
      </div>
<?php
  }

}

RefManager::init();

?>
