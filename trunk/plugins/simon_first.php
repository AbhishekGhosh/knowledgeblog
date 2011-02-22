<?php
  /**
   * @package Peaches 
   */
  /*
   Plugin Name: Peaches
   Plugin URI: http://knowledgeblog.org/#
Description: Our first attempt at a Wordpress plugin. 
*/

   function peaches_get_lyric() {
     /** These are the lyrics to Hello Dolly */
     $lyrics = "Strolling along minding my own business
There goes a girl and a half
She's got me going up and down (x2)
Walking on the beaches looking at the peaches

Well I got the notion girl that
You got some sun tan lotion in that bottle of yours
Spread it all over my peeling skin baby
That feels real good
All this skirt
Lapping up the sun
Lap me up
(Why don't you come on and)
Lap me up
Walking on the beaches looking at the peaches

There goes another one
Just lying down on the sand dunes
Better go and take a swim
And see if I can cool down a little bit
'Cos you and me woman
We got a lotta things on our minds
(You know what I mean)
Walking on the beaches looking at the peaches

Will you take a look over there?
Where?
There
Is she trying to get out of that clitoris
Liberation for women
That's what I preach
Preacher man
Walking on the beaches looking at the peaches

Oh shit! There goes the charabang
Looks like I'm gonna be stuck here the whole summer
Well what a bummer
I can think of a lot worse places to be
Like down in the street
Or down in the sewer
Or even on the end of a skewer

Down on the beaches looking at the peaches";
     // Here we split it into lines
     $lyrics = explode("\n", $lyrics);

     // And then randomly choose a line
     return wptexturize( $lyrics[ mt_rand(0, count($lyrics) - 1) ] );
   }

// This just echoes the chosen line, we'll position it later
function peaches() {
  $chosen = peaches_get_lyric();
  echo "<p id='peaches'>$chosen</p>";
}

// Now we set that function up to execute when the admin_footer action is called
add_action('admin_footer', 'peaches');

// We need some CSS to position the paragraph
function peaches_css() {
  // This makes sure that the posinioning is also good for right-to-left languages
  $x = ( 'rtl' == get_bloginfo( 'text_direction' ) ) ? 'left' : 'right';

  echo "
	<style type='text/css'>
	#peaches {
		position: absolute;
		top: 4.5em;
		margin: 0;
		padding: 0;
		$x: 215px;
		font-size: 11px;
	}
	</style>
	";
}

add_action('admin_head', 'peaches_css');

?>
