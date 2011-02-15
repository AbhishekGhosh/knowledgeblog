<?php 

if ( function_exists('register_sidebars') ){
	register_sidebar(array('name' => 'top'));
	register_sidebar(array('name' => 'left'));
	register_sidebar(array('name' => 'right'));
}


function gd_short_excerpt($text) { // Fakes an excerpt if needed
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
		$excerpt_length = 30;
		$words = preg_split('/[\s\n\r]+/', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[…]');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

function fix_content($text) {
	global $post;
	
	$text = str_replace('<h2>','<h3>',$text);
	$text = str_replace('</h2>','</h3>',$text);
	
	return $text;
}

?>