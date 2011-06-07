<?php

function knowledgeblog_authors($class='default') {

    if (! function_exists(get_coauthors)) {
        if ($class == 'pullout') {
        echo "<li><span class='pullout-author'>".sprintf(__('Posted by %1$s at %2$s', 'suf_theme'),
            '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'">'.get_the_author_meta('display_name').'</a>',
        sprintf(get_the_time(get_option('time_format'))))."</span></li>\n";
        }
        elseif ($class == 'list') {
        echo "<li>".sprintf(__('Posted by %1$s at %2$s', 'suf_theme'),
			'<a href="'.get_author_posts_url(get_the_author_meta('ID')).'">'.get_the_author_meta('display_name').'</a>',
		sprintf(get_the_time(get_option('time_format'))))."</li>\n";
        }
        else {
            echo "<span class='author'>";
            printf(__('Posted by %1$s at %2$s', 'suf_theme'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'">'.get_the_author_meta('display_name').'</a>', sprintf(get_the_time(get_option('time_format'))));
        }
            echo "</span>";
    }
    else {
        $authors = get_coauthors();
        $size = sizeof($authors);
        $i = 1;
        $author_string = '';
        foreach ($authors as $author) {
            $author_string .= '<a href="'.get_author_posts_url($author->ID).'" title="View all posts by '.$author->display_name.'">'.$author->display_name.'</a>';
            if ($i < $size-1) {
                $author_string .= ', ';
            }
            elseif ($i == $size-1) {
                $author_string .= ' and ';
            }
            $i++;
        }
        if ($class == 'pullout') {
		    echo "<li><span class='pullout-author'>".sprintf(__('Posted by %1$s at %2$s', 'suf_theme'),
		    $author_string,
            sprintf(get_the_time(get_option('time_format'))))."</span></li>\n";
        }
        elseif ($class == 'list') {
            echo "<li>".sprintf(__('Posted by %1$s at %2$s', 'suf_theme'),
		    $author_string,
            sprintf(get_the_time(get_option('time_format'))))."</li>\n";
        }
        else {
            echo "<span class='author'>";
            printf(__('Posted by %1$s at %2$s', 'suf_theme'), $author_string, sprintf(get_the_time(get_option('time_format'))));
            echo "</span>";
        }
        
    }

}
