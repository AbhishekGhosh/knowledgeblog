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
    add_action('template_redirect', array(__CLASS__, 'bibliography_output'));

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

  function process_doi($content) {
    //find dois in the_content
    //doi regex assumes dois start with '10.' - seems reasonable?
    //also - should we optionally allow ^doi:?
    $dois = self::get_dois($content);
    $replacees = $dois[0];
    $uniq_doi = $dois[1];
    if ($uniq_doi) {
        //echo "<pre>";print_r($uniq_doi);echo "</pre>";}
        $metadata_arrays = self::get_arrays($uniq_doi);
        $i = 0;
        while ($i < count($replacees)) {
            //echo "<pre>";
            //echo $article;
            //print_r($article_arrays[$i]);
            //echo "</pre>";
            $replacer = '<span id="cad'.strval($i+1).'" name="citation-cad">['.strval($i+1).']</span>';
            $content = str_replace($replacees[$i], $replacer, $content);
            $i++;
        }
        //call CrossRef to process them
        //http://www.crossref.org/openurl/?id=doi:10.3998/3336451.0009.101&noredirect=true&pid=s.j.cockell@newcastle.ac.uk&format=unixref
        //make array of xmls
        //add bibliography and in place pointers
        $permalink = get_permalink();
        //echo "<a href='".$permalink."/bib.json'>JSON</a>"; 
    
        $json = self::metadata_to_json($metadata_arrays);
        $json_a = json_decode($json, true);
        $bibliography = self::build_bibliography($json_a);
        $content .= $bibliography;
    }
    return $content;
  }

  private function build_bibliography($pub_array) {
    $i = 1;
    $bib_string = "<h2>References</h2>
    <ol>
    ";
    foreach ($pub_array as $pub) {
        //echo "<pre>";print_r($pub);echo "</pre>";
        $bib_string .= "<li>
";
        $author_count = 1;
        $author_total = count($pub['author']);
        foreach ($pub['author'] as $author) {
            //get author initials
            $firsts = $author['given'];
            $words = explode(' ', $firsts);
            $initials = "";
            foreach ($words as $word) {
                $initials .= strtoupper(substr($word,0,1)).".";
            }
            $initials;
            $bib_string .= $initials." ".$author['family'].", ";
            if ($author_count == ($author_total - 1)) {
                $bib_string .= "and ";
            }
            $author_count++;
        }
        if ($pub['title']) {
            $bib_string .= '"'.$pub['title'].'"';
        }
        if ($pub['container-title']) {
            $bib_string .= ', <i>'.$pub['container-title'].'</i>';
        }
        if ($pub['volume']) {
            $bib_string .= ', vol. '.$pub['volume'];
        }
        if ($pub['issued']['date-parts'][0][0]) {
            $bib_string .= ', '.$pub['issued']['date-parts'][0][0];
        }
        if ($pub['page']) {
            $bib_string .= ', pp. '.$pub['page'];
        }
        if ($pub['DOI']) {
            $bib_string .= '. <a href="http://dx.doi.org/'.$pub['DOI'].'" target="_blank" title="'.$pub['title'].'">DOI</a>';
        }
        $bib_string .= ".
</li>
";
    }
    $bib_string .= "</ol>
";
    return $bib_string;
  }

  private function get_arrays($dois) {
    $metadata_arrays = array();
    foreach ($dois as $doi) {
        //echo $doi."<br/>";
        $metadata = array();
        $article = self::crossref_doi_lookup($doi);
        if ($article == null) {
            $article = self::pubmed_doi_lookup($doi);
            $article_array = self::array_from_xml($article);
            $metadata = self::get_pubmed_metadata($article_array);
        }
        else {
            $article_array = self::array_from_xml($article);
            $metadata = self::get_crossref_metadata($article_array);
            //echo "<pre>";print_r($article_array);echo "</pre>";
        }
        $metadata_arrays[] = $metadata;
    }
    return $metadata_arrays;
  }
  
  private function get_dois($content) {
    $preg = "#\[doi\](10\..*?)\[\/doi\]#"; //make sure this is non-greedy
    preg_match_all($preg, $content, $dois);
    //echo "<pre>";
    //print_r($dois[0]);
    //print_r($dois[1]);
    //echo "</pre>";
    //need to make sure we deal with duplicate DOIs here
    //array_values() needed to keep array indicies sequential
    $replacees = array_values(array_unique($dois[0]));
    $uniq_doi = array_values(array_unique($dois[1]));
    $returnval = array($replacees, $uniq_doi);
    return $returnval;
  }

  private function crossref_doi_lookup($pub_doi) {
    $url = "http://www.crossref.org/openurl/?noredirect=true&pid=s.j.cockell@newcastle.ac.uk&format=unixref&id=doi:".$pub_doi;
    //echo $url."<br/>";
    $xml = file_get_contents($url, 0);
    if (preg_match('/not found in CrossRef/', $xml)) {
        return null;
    }
    else {
        return $xml;
    }
  }

  private function pubmed_doi_lookup($pub_doi) {
    $search = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmax=1&term=".$pub_doi;
    $search_xml = file_get_contents($search, 0);
    //TODO - id can be retrieved from pubmed_xml
    $search_obj = self::array_from_xml($search_xml);
    $idlist = $search_obj->IdList;
    $id = $idlist->Id;
    $fetch_xml = self::pubmed_id_lookup($id);
    return $fetch_xml;
  }
  private function pubmed_id_lookup($pub_id) {
    $fetch = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&retmode=xml&id=".$pub_id;
    $xml = file_get_contents($fetch, 0);
    //need to handle failures here too
    return $xml;
  }

  private function array_from_xml($xml) {
    $xmlarray = array();
    $x = new SimpleXMLElement($xml);
    //$x = simplexml_load_string($xml);
    return $x;
  }

  private function check_doi() {
  }

  function pmid_shortcode($atts,$content){
    return "PMID!<br/>";
  }

  function bibliography_output() {
    global $post;
    $uri = self::get_requested_uri();
    if ($uri[0] == 'json') {
        //render the json here
        $this_post = get_post($post->ID, ARRAY_A);
        $post_content = $this_post['post_content'];
        $dois = self::get_dois($post_content);
        $metadata = array();
        $metadata = self::get_arrays($dois[1]);
        $json = self::metadata_to_json($metadata);
        echo $json;
        exit;
    }
    elseif ($uri[0] == 'bib') {
        //render bibtex here
        exit;
    }
    elseif ($uri[0] == 'ris') {
        //render ris here
        exit; //prevents rest of page rendering
    }
  }

  private function metadata_to_json($md) {
    $json_string = "{\n";
    $item_number = 1;
    $md_number = count($md);
    foreach ($md as $m) {
        $item_string = "ITEM-".$item_number;
        $json_string .= '"'.$item_string.'": {
    "id": "'.$item_string.'",
    "title": "'.$m[6].'",
    "author": [
    ';
        $author_length = count($m[0]);
        $track = 1;
        foreach ($m[0] as $author) {
            $json_string .= '{
        "family": "'.$author['surname'].'",
        "given": "'.$author['given_name'].'"
    ';
    if ($track != $author_length) {
    $json_string .= '},
    ';
    }
    else {
        $json_string .= '}
    ';
        }
        $track++;
        }
        $json_string .= '],
    "container-title": "'.$m[1].'",
    "issued":{
        "date-parts":[
            [';
        $date_string = $m[3]['year'];
        if ($m[3]['month']) {
            $date_string .= ", ".(int)$m[3]['month'];
        }
        if ($m[3]['day']) {
            $date_string .= ", ".(int)$m[3]['day'];
        }
        $json_string .= $date_string.']
        ]
    },
    ';
        if ($m[7]) {
            $json_string .= '"page": "'.$m[7].'-'.$m[8].'",
    ';
        }
        //volume
        if ($m[4]) {
            $json_string .= '"volume": "'.$m[4].'",
    ';
        }
        //issue
        if ($m[5]) {
            $json_string .= '"issue": "'.$m[5].'",
    ';
        }
        //doi
        if ($m[9]) {
            $json_string .= '"DOI": "'.$m[9].'",
    ';
        }
        //url
        //type
        $json_string .= '"type": "article-journal"
';
        if ($item_number != $md_number) {
            $json_string .= '},
';
        }
        else {
            $json_string .= '}
';
        }

        $item_number++;
    }
    $json_string .= '}';
    return $json_string;
  }
  
  private function get_crossref_metadata($article) {
    $authors = array();
    $journal_title = "";
    $abbrv_title = "";
    $pub_date = array();
    $volume = "";
    $title = "";
    $first_page = "";
    $last_page = "";
    $reported_doi = "";
    $resource = "";
    $issue = "";
    
    $journal = $article->children()->children()->children();
    foreach ($journal->children() as $child) {
        if ($child->getName() == 'journal_metadata') {
            $journal_title = $child->full_title;
            $abbrv_title = $child->abbrev_title;
        }
        elseif ($child->getName() == 'journal_issue') {
            $issue = $child->issue;
            foreach ($child->children() as $issue_info) {
                if ($issue_info->getName() == 'publication_date') {
                    $pub_date['month'] = $issue_info->month;
                    $pub_date['day'] = $issue_info->day;
                    $pub_date['year'] = $issue_info->year;

                }
                elseif ($issue_info->getName() == 'journal_volume') {
                    $volume = $issue_info->volume;
                }
            }
        }
        elseif ($child->getName() == 'journal_article') {
            foreach ($child->children() as $details) {
                if ($details->getName() == 'titles') {
                    $title = $details->children();
                }
                elseif ($details->getName() == 'contributors') {
                    $people = $details->children();
                    $author_count = 0;
                    foreach ($people as $person) {
                        $authors[$author_count] = array();
                        $authors[$author_count]['given_name'] = $person->given_name;
                        $authors[$author_count]['surname'] = $person->surname;
                        $author_count++;
                    }
                }
                elseif ($details->getName() == 'pages') {
                    $first_page = $details->first_page;
                    $last_page = $details->last_page;
                }
                elseif ($details->getName() == 'doi_data') {
                    $reported_doi = $details->doi;
                    $resource = $details->resource;
                }
            }
        }
    }
    return array($authors,$journal_title,$abbrv_title,$pub_date,$volume,$issue,$title,$first_page,$last_page,$reported_doi,$resource);
  }

  private function get_pubmed_metadata($article) {
    $authors = array();
    $journal_title = "";
    $abbrv_title = "";
    $pub_date = array();
    $volume = "";
    $title = "";
    $first_page = "";
    $last_page = "";
    $reported_doi = "";
    $resource = "";
    $issue = "";
    $meta = $article->children()->children()->children();
    foreach ($meta as $child) {
        if ($child->getName() == 'Article') {
            //echo "<pre>";print_r($child);echo "</pre>";
            foreach ($child->children() as $subchild) {
                //Journal -> JournalIssue -> Volume, Issue, PubDate
                //Journal -> Title
                //Journal -> ISOAbbreviation
                if ($subchild->getName() == 'Journal') {
                    $jissue = $subchild->JournalIssue;
                    $volume = $jissue->Volume;
                    $issue = $jissue->Issue;
                    $journal_title = $subchild->Title;
                    $abbrv_title = $subchild->ISOAbbreviation;
                }
                //ArticleTitle
                elseif ($subchild->getName() == 'ArticleTitle') {
                    $title = $subchild;
                }
                //AuthorList -> Author[]
                elseif ($subchild->getName() == 'AuthorList') {
                    $author_count = 0;
                    foreach ($subchild->Author as $author) {
                        $authors[$author_count] = array();
                        $authors[$author_count]['given_name'] = $author->ForeName;
                        $authors[$author_count]['surname'] = $author->LastName;
                        $author_count++;
                    }
                }
                //ArticleDate
                elseif ($subchild->getName() == 'ArticleDate') {
                    //echo "<pre>";print_r($subchild);echo "</pre>";
                    $pub_date['month'] = $subchild->Month;
                    $pub_date['day'] = $subchild->Day;
                    $pub_date['year'] = $subchild->Year;
                }
                //ELocationID (DOI)
                elseif ($subchild->getName() == 'ELocationID') {
                    $reported_doi = $subchild;
                }
            }
        }
    }
    return array($authors,$journal_title,$abbrv_title,$pub_date,$volume,$issue,$title,$first_page,$last_page,$reported_doi,$resource);
  }
  
  private function get_requested_uri() {
    $requesturi = $_SERVER['REQUEST_URI'];
    //echo "<pre>$requesturi</pre>";
    preg_match('#\/(.*)\/bib\.(bib|ris|json)$#', $requesturi, $matches);
    //echo "<pre>";
    //print_r($matches);
    //echo "</pre>";
    //matches[1] is post (with extraneous paths maybe)
    $uri = null;
    if ($matches) { 
        $uri = array();
        $uri[0] = $matches[2];
    }
    return $uri;
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
