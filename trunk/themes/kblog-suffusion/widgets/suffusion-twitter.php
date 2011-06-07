<?php
/**
 * This creates a widget to let people follow you on Twitter. You can pick your Twitter icon and display your Tweets too.
 *
 * @package Suffusion
 * @subpackage Widgets
 *
 */

class Suffusion_Follow_Twitter extends WP_Widget {
	function Suffusion_Follow_Twitter() {
		$widget_ops = array('classname' => 'widget-suf-follow-twitter', 'description' => __("A widget to enable people to follow you on Twitter", "suf_theme"));
		$control_ops = array('width' => 840, 'height' => 350);

		$this->WP_Widget("suf-follow-twitter", __("Twitter", "suf_theme"), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$user = $instance['user'];
		$show_icon = $instance['show_icon'];
		$show_tagline = $instance['show_tagline'];
		$show_tweets = $instance['show_tweets'];
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		$tagline = $instance['tagline'];
		$icon = $instance['icon'];
		$icon_height = $instance['icon_height'];
		$num_tweets = $instance['num_tweets'];

		echo $before_widget;

		if (!empty($title)) {
			echo $before_title;
			echo $title;
			echo $after_title;
		}

		if ($show_icon || $show_tagline) {
?>

<div style='text-align: center;'>
	<a href="http://twitter.com/<?php echo $user; ?>" class="twitter-icon-and-tag" title="<?php echo esc_attr($tagline);?>">
<?php
			if ($show_icon) {
?>
		<img src="<?php echo get_template_directory_uri(); ?>/images/twitter/<?php echo $icon;?>-big.png" alt="Twitter" height="<?php echo $icon_height;?>" width="<?php echo $icon_height;?>"/>
<?php
			}

			if ($show_tagline) {
				echo $tagline;
			}
?>
	</a>
</div>

<?php
		}

		if ($show_tweets) {
			$feed_url = "http://search.twitter.com/search.atom?q=from:" . $user . "&rpp=" . $num_tweets;

			//Using cURL because of problems with some web hosts like DreamHost
			$ch = curl_init();
			$timeout = 5; // set to zero for no timeout
			curl_setopt ($ch, CURLOPT_URL, $feed_url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$feed = curl_exec($ch);
			curl_close($ch);

			$feed = str_replace("&lt;", "<", $feed);
			$feed = str_replace("&gt;", ">", $feed);
			$feed = str_replace("&apos;", "'", $feed);
			$feed = str_replace("&quot;", '"', $feed);
			$feed = str_replace("&amp;", '&', $feed);
			$clean = explode("<content type=\"html\">", $feed);

			$amount = count($clean) - 1;

			echo "<ul>";

			for ($i = 1; $i <= $amount; $i++) {
				$cleaner = explode("</content>", $clean[$i]);
				echo "<li>".$cleaner[0]."</li>";
			}
			echo "</ul>";
		}
        echo $after_widget; /*
?>
		</div>
	</div>
<?php */
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance["user"] = strip_tags($new_instance["user"]);
		$instance["show_icon"] = $new_instance["show_icon"];
		$instance["show_tagline"] = $new_instance["show_tagline"];
		$instance["show_tweets"] = $new_instance["show_tweets"];
		$instance["title"] = strip_tags($new_instance["title"]);
		$instance["tagline"] = strip_tags($new_instance["tagline"]);
		$instance["icon"] = $new_instance["icon"];
		$instance["icon_height"] = $new_instance["icon_height"];
		$instance["num_tweets"] = $new_instance["num_tweets"];

		return $instance;
	}

	function form($instance) {
		$defaults = array("user" => __("your-user-name", "suf_theme"),
			"title" => __("My Tweets", "suf_theme"),
			"tagline" => __("Follow me on Twitter", "suf_theme"),
			"show_icon" => true,
			"show_tagline" => true,
			"icon_height" => "32px",
			"num_tweets" => 5,
		);
		$instance = wp_parse_args((array)$instance, $defaults);
		if (!isset($instance['icon'])) {
			$icon = "twitter-00";
		}
		else {
			$icon = $instance['icon'];
		}
?>
<div style='display: inline-block; clear: both;'>
	<div style='float: left; width: 32%; margin-right: 10px;'>
<?php
		_e("<p>This widget lets you set up a link to allow people to follow you on Twitter. You can additionally show your latest feeds.</p>", "suf_theme");
		printf("<p>%s</p>", __("Recommended settings:","suf_theme"));
		echo "<ul class='twitter-desc'>\n";
		printf("<li>%s\n", __("If you are placing this widget in the \"Right Header Widgets\":", "suf_theme"));
		echo "<ul>\n";
		printf("<li>%s</li>\n", __("Show icon", "suf_theme"));
		printf("<li>%s</li>\n", __("Don't show tagline", "suf_theme"));
		printf("<li>%s</li>\n", __("Don't show feeds", "suf_theme"));
		echo "</ul>\n";
		echo "</li>\n";

		printf("<li>%s\n", __("If you are placing this widget in the sidebars or in \"Widget Area below Header\" or \"Widget Area below Footer\":", "suf_theme"));
		echo "<ul>\n";
		printf("<li>%s</li>\n", __("Show icon", "suf_theme"));
		printf("<li>%s</li>\n", __("Show tagline", "suf_theme"));
		printf("<li>%s</li>\n", __("Show feeds", "suf_theme"));
		echo "</ul>\n";
		echo "</li>\n";

		echo "</ul>\n"; ?>
	</div>
	<div style='float: left; width: 32%; margin-right: 10px;'>
		<p>
			<label for="<?php echo $this->get_field_id( 'user' ); ?>"><?php _e('User:', 'suf_theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'user' ); ?>" name="<?php echo $this->get_field_name( 'user' ); ?>" value="<?php if (isset($instance['user'])) echo $instance['user']; ?>" class="widefat" />
			<i><?php _e("This is the user who will be followed", "suf_theme"); ?></i>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_icon' ); ?>" name="<?php echo $this->get_field_name( 'show_icon' ); ?>" type="checkbox" <?php if (isset($instance['show_icon'])) checked( $instance['show_icon'], 'on' ); ?>  class="checkbox" />
			<label for="<?php echo $this->get_field_id( 'show_icon' ); ?>"><?php _e('Show Twitter Icon', 'suf_theme'); ?></label>
			<i><?php _e("Will show the selected Twitter icon", "suf_theme"); ?></i>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_tagline' ); ?>" name="<?php echo $this->get_field_name( 'show_tagline' ); ?>" type="checkbox" <?php if (isset($instance['show_tagline'])) checked( $instance['show_tagline'], 'on' ); ?>  class="checkbox" />
			<label for="<?php echo $this->get_field_id( 'show_tagline' ); ?>"><?php _e('Show a Tagline', 'suf_theme'); ?></label>
			<i><?php _e("Will show up near the Twitter icon", "suf_theme"); ?></i>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_tweets' ); ?>" name="<?php echo $this->get_field_name( 'show_tweets' ); ?>" type="checkbox" <?php if (isset($instance['show_tweets'])) checked( $instance['show_tweets'], 'on' ); ?>  class="checkbox" />
			<label for="<?php echo $this->get_field_id( 'show_tweets' ); ?>"><?php _e('Show my Tweets', 'suf_theme'); ?></label>
			<i><?php _e("Will show your latest tweets", "suf_theme"); ?></i>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'suf_theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if (isset($instance['title'])) echo $instance['title']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tagline' ); ?>"><?php _e('Tagline:', 'suf_theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'tagline' ); ?>" name="<?php echo $this->get_field_name( 'tagline' ); ?>" value="<?php if (isset($instance['tagline'])) echo $instance['tagline']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_tweets' ); ?>"><?php _e('Number of Tweets to display:', 'suf_theme'); ?></label>
			<select id="<?php echo $this->get_field_id( 'num_tweets' ); ?>" name="<?php echo $this->get_field_name( 'num_tweets' ); ?>">
<?php
		for ($i = 1; $i <= 20; $i++) {
?>
				<option <?php if (isset($instance['num_tweets']) && $i == $instance['num_tweets'] ) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
<?php
		}
?>
			</select>
		</p>
	</div>

	<div style='float: left; width: 32%;'>
		<p class="twitter-icons">
			<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e('Select your Twitter icon:', 'suf_theme'); ?></label><br/>
<?php
		for ($i = 0; $i < 10; $i++) {
?>
			<span><input type="radio" name="<?php echo $this->get_field_name('icon'); ?>" value="twitter-0<?php echo $i; ?>" <?php if ("twitter-0$i" == $icon) { echo  ' checked="checked" '; } ?>/><img src="<?php echo get_template_directory_uri(); ?>/images/twitter/twitter-0<?php echo $i; ?>.png" alt="Twitter 0<?php echo $i; ?>"/></span>
<?php
		}
?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'icon_height' ); ?>"><?php _e('Set the height for the Twitter icon:', 'suf_theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'icon_height' ); ?>" name="<?php echo $this->get_field_name( 'icon_height' ); ?>"
				value="<?php echo $instance['icon_height']; ?>"/>
			<br/>
			<i><?php _e("Recommended sizes: 32px if the widget is being added to the \"Right Header Widgets\" area, whatever you like otherwise. Note that making the image too large will cause loss of picture quality.", "suf_theme"); ?></i>
		</p>
	</div>
</div>
<?php
	}
}
?>