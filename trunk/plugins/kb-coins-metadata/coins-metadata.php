<?php
/*
Plugin Name: COinS Metadata Exposer
Plugin URI: http://www.zotero.org
Description: Makes your blog readable by Zotero and other COinS interpreters.
Version: 0.5
Author: Sean Takats - Center for History and New Media
Author URI: http://chnm.gmu.edu

Copyright 2007 Sean Takats (email: stakats@gmu.edu)

NB: Adapted by Simon Cockell (s.j.cockell@newcastle.ac.uk) to support the Co-Authors Plus plugin (http://www.wordpress.org/extend/plugins/co-authors-plus).

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_filter('the_content', 'coinsify_the_content');

function coinsify_the_content($content)
{
	foreach((get_the_category()) as $cat) {
		$cats = $cats . "&amp;rft.subject=" . urlencode($cat->cat_name);
	} 
    $authors = get_authors();
    $i = 0;
    if (sizeof($authors) == 1) {	
        $coins_span  = '<span class="Z3988" title="ctx_ver=Z39.88-2004&amp;rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Adc&amp;rfr_id=info%3Asid%2Focoins.info%3Agenerator&amp;rft.title='.urlencode(get_the_title()).'&amp;rft.aulast='.urlencode(get_the_author_lastname()).'&amp;rft.aufirst='.urlencode(get_the_author_firstname()).$cats.'&amp;rft.source='.urlencode(get_bloginfo('name')).'&amp;rft.date='.the_time('Y-m-d').'&amp;rft.type=blogPost&amp;rft.format=text&amp;rft.identifier='.the_permalink().'&amp;rft.language=English"></span>';
    }
    else {
        //deal with coauthors here
	    //rft.aulast and rft.aufirst are for first author rft.au otherwise
        foreach ($authors as $author) {
            if ($i == 0) {
                $author_string .= "&amp;rft.aulast=".urlencode($author['lastname'])."&amp;rft.aufirst=".urlencode($author['firstname']);
            }
            else {
                $authorname = $author['firstname']." ".$author['lastname'];
                $author_string .= "&amp;rft.au=".urlencode($authorname);
            }
            $i++;
        }
    $coins_span = '<span class="Z3988" title="ctx_ver=Z39.88-2004&amp;rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Adc&amp;rfr_id=info%3Asid%2Focoins.info%3Agenerator&amp;rft.title='.urlencode(get_the_title()).$author_string.$cats.'&amp;rft.source='.urlencode(get_bloginfo('name')).'&amp;rft.date='.the_time('Y-m-d').'&amp;rft.type=blogPost&amp;rft.format=text&amp;rft.identifier='.the_permalink().'&amp;rft.language=English"></span>';
    }
	return $content.$coins_span;
}

    function get_authors() { 
        $authors = array();
        if (!function_exists('coauthors')) {
            $author = array('firstname'=>get_the_author_firstname(),'lastname'=>get_the_author_lastname());
            $authors[] = $author;
        }
        else {
            $coauthors = get_coauthors();
            foreach ($coauthors as $author) {
                $a = array('firstname'=>$author->first_name, 'lastname'=>$author->last_name);
                $authors[] = $a;
            }
        }
        return $authors;
    }

?>
