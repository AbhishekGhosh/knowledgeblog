<?php
  /*
   Plugin Name: Knowledgeblog Post Metadata Plugin
   Plugin URI: http://knowledgeblog.org/metadata-plugin
   Description: Add metadata to blogposts
   Version: 0.1
   Author: Simon Cockell
   Author URI: http://knowledgeblog.org
   
   Copyright 2010. Simon Cockell (s.j.cockell@newcastle.ac.uk)
   Newcastle University. 
   
  */

new PostMetadata;

class PostMetadata {
  public $post_metadata;
  function PostMetadata() {
    $this->__construct();
  }
  function __construct() {
    $this->post_metadata = new MetaData();
    //add metadata API endpoint
    add_action('init', array(&$this, 'metadata_rewrite'));
    //intialise metadata for this post
    //this is late enough that we're in the loop,
    //but not so late we've already tried to emit the metadata...
    add_action('wp', array(&$this, 'init_metadata'),9);
    //inject metadata into post head
    add_action('wp_head', array(&$this, 'add_header'));
    //add query_var so metadata endpoint can be responded to
    add_filter('query_vars', array(&$this, 'metadata_query_vars'));
    //deal with the metadata endpoint, emit the required format
    add_action('template_redirect', array(&$this, 'metadata_endpoint'));
  }
  function init_metadata() {
    global $post;
    $this->post_metadata->add_data($post);
  }
  /*
  * Adds the rewrite_endpoint for the metadata RESTful API
  */
  function metadata_rewrite() {
    global $wp_rewrite;
    add_rewrite_endpoint( 'kblog', EP_ALL );
    $wp_rewrite->flush_rules();
  }
  /*
  * Adds query_var for the metadata endpoint. 
  * Needed so the template_redirect can respond appropriately
  */
  function metadata_query_vars($vars) {
     $vars[] = 'kblog';
     return $vars;
  }
  /*
  * Uses template_redirect to emit the metadata endpoints
  * Tests for the requested type and responds accordingly
  * Unsupported requests should just result in the parent post
  */
  function metadata_endpoint() {
    global $wp_query;
     if ($wp_query->query_vars['kblog'] == 'metadata.html') {
        $this->get_html_meta();
        exit();
     }
     if ($wp_query->query_vars['kblog'] == 'metadata.json') {
        $this->get_json_meta();
        exit();
     }
  }
  /*
  * Emits html metadata
  */
  function get_html_meta() {
    echo "<html>
    <head>
";
    $this->add_header();
    echo "</head>
    <body>
    </body>
    </html>
";
  }
  /*
  * Emits JSON metadata
  */
  function get_json_meta() {
  }
  /*
  * Injects Google Scholar required metadata into the post header.
  * Can be reused for the metadata RESTy service.
  */
  function add_header() {
    if (is_single() || is_page()) {
        echo '<meta name="resource_type" content="knowledgeblog">
  ';
        //avoid doing anything on summary pages
        echo '<meta name="citation_title" content="'.$this->post_metadata->title.'">
  ';
        foreach ($this->post_metadata->authors as $author) {
                echo '<meta name="citation_author" content="'.$author.'">
  ';
        }
        echo '<meta name="citation_date" content="'.$this->post_metadata->date.'">
  ';
        echo '<meta name="citation_journal_title" content="'.$this->post_metadata->site_name.'">
  ';
    }
  }

  function debug(){
    echo "Simon's debug statement";
  }

}

class MetaData {
    public $postid;
    public $title;
    public $authors;
    public $date;
    public $site_name;
    function MetaData() {
        $this->__construct();
    }
    function __construct() {
    }
    public function add_data($thispost) {
        $this->postid = $thispost->ID;
        $this->title = $thispost->post_title;
        $this->authors = self::get_authors($this->postid);
        $this->date = date('Y/m/d', strtotime($thispost->post_date));
        $this->site_name = get_bloginfo('name');
    }
    /*
    * Gets a list of post authors, plays nicely with default Wordpress and Coauthors Plus
    */
    function get_authors($id) { 
        $authors = array();
        if (!function_exists('coauthors')) {
            $author = get_author($id);
            //$author = get_userdata($post->post_author);
            $author_realname = $author->last_name.", ".$author->first_name;
            if ($author_realname != ', ') {
                $authors[] = $author_realname;
                //echo '<meta name="citation_author" content="'.$author_realname.'">';
            }
            else {
                $authors[] = $author->user_login;
            }
        }
        else {
            $coauthors = get_coauthors($id);
            foreach ($coauthors as $author) {
                $author_realname = $author->last_name.", ".$author->first_name;
                if ($author_realname != ', ') {
                    $authors[] = $author_realname;
                }
                else {
                    $authors[] = $author->user_login;
                }
            }
        }
        return $authors;
    }

}
        /*
        SAMPLE GOOGLE SCHOLAR METADATA
        <meta name="citation_title" content="The testis isoform of the phosphorylase kinase catalytic subunit (PhK-T) plays a critical role in regulation of glycogen mobilization in developing lung">
        <meta name="citation_author" content="Liu, Li">
        <meta name="citation_author" content="Rannels, Stephen R.">
        <meta name="citation_author" content="Falconieri, Mary">
        <meta name="citation_author" content="Phillips, Karen S.">
        <meta name="citation_author" content="Wolpert, Ellen B.">
        <meta name="citation_author" content="Weaver, Timothy E.">
        <meta name="citation_date" content="1996/05/17">
        <meta name="citation_journal_title" content="Journal of Biological Chemistry">
        <meta name="citation_volume" content="271">
        <meta name="citation_issue" content="20">
        <meta name="citation_firstpage" content="11761">
        <meta name="citation_pdf_url" content="http://www.example.com/content/271/20/11761.full.pdf">
        */

?>
