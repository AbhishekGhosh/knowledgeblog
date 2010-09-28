<?php
  /**
   * @package knowledgeblog
   */
  /*
   Plugin Name: References
   Plugin URI: http://knowledgeblog.org/documents/reference
   Description: Support for referencing within a blog
  */

$doi_tags = array();

function reference_doi_handler($atts,$content=null){
  global $doi_tags;
  $doi_tags[] = $content;
  
  return "<a href=\"http://dx.doi.org/" . $content . "\">[*]</a>";
}

add_shortcode("doi", "reference_doi_handler");


function reference_list(){
  
  global $doi_tags;
  
  $retn = "";
  foreach($doi_tags as $doi){
    $retn.=$doi . "<br/>";
  } 

  echo $retn;
}



?>