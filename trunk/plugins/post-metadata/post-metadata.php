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


class PostMetadata{

  function init(){
    //inject metadata into post head
    add_action('wp_head', array(__CLASS__, 'add_header'));
    //add metadata API endpoint
    add_action('init', array(__CLASS__, 'metadata_rewrite'));
    //add query_var so metadata endpoint can be responded to
    add_filter('query_vars', array(__CLASS__, 'metadata_query_vars'));
    //deal with the metadata endpoint, emit the required format
    add_action('template_redirect', array(__CLASS__, 'metadata_endpoint'));
  }
 

  /*
  * Adds the rewrite_endpoint for the metadata RESTful API
  */

  function metadata_rewrite() {
    global $wp_rewrite;
    add_rewrite_endpoint( 'metadata', EP_ALL );
    $wp_rewrite->flush_rules();
    //echo "<pre>";print_r($wp_rewrite);echo "</pre>";
  }

  /*
  * Adds query_var for the metadata endpoint. 
  * Needed so the template_redirect can respond appropriately
  */
  function metadata_query_vars($vars) {
     $vars[] = 'metadata';
     return $vars;
  }
  
  /*
  * Uses template_redirect to emit the metadata endpoints
  * Tests for the requested type and responds accordingly
  * Unsupported requests should just result in the parent post
  */
  function metadata_endpoint() {
    global $wp_query;
     if ($wp_query->query_vars['metadata'] == '1') {
        self::add_header();
        exit();
     }
  }

  /*
  * Injects Google Scholar required metadata into the post header.
  * Can be reused for the metadata RESTy service.
  */
  function add_header() {

    echo "<!-- KNOWLEDGEBLOG METADATA -->
";
    if (is_single() || is_page()) {
        global $post;
        echo '<meta name="resource_type" content="knowledgeblog">
  ';
        //avoid doing anything on summary pages
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
        $postid = $post->ID;
        $title = $post->post_title;
        //print_r($post);
        echo '<meta name="citation_title" content="'.$title.'">
  ';
        $authors = self::get_authors($postid);
        foreach ($authors as $author) {
                echo '<meta name="citation_author" content="'.$author.'">
  ';
        }
        $date = date('Y/m/d', strtotime($post->post_date));
        echo '<meta name="citation_date" content="'.$date.'">
  ';
        $site_name = get_bloginfo('name');
        echo '<meta name="citation_journal_title" content="'.$site_name.'">
  ';
    }

    echo "<!-- END KNOWLEDGEBLOG METADATA -->
";
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

  function debug(){
    echo "Simon's debug statement";
  }

}

PostMetadata::init();
?>
