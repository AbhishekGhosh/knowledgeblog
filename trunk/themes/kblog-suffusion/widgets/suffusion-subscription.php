<?php
/**
 * This widget helps you place subscription links on your blog. Visitors can either subscribe through email or by clicking on a feed URL.
 * A FeedBurner account is needed. FeedBurner stats can be displayed, optionally. You can additionally set up links for popular social
 * media services: Del.icio.us, Digg, Facebook, Flickr, LinkedIn, Reddit, StumbleUpon, Technorati and Twitter
 *
 * @package Suffusion
 * @subpackage Widgets
 *
 */

class Suffusion_Subscription extends WP_Widget {
	function Suffusion_Subscription() {
		$widget_ops = array(
            'classname' => 'widget-suf-subscription',
            'description' => __("This widget lets visitors of your blog subscribe to it and follow you on popular social networks like Digg, FaceBook etc. Additionally they can subscribe to your blog by email, for which you need a FeedBurner account.", "suf_theme"));
		$control_ops = array('width' => 840);

		$this->WP_Widget("suf-subscription", __("Follow Me", "suf_theme"), $widget_ops, $control_ops);
        $this->icon_suffixes = array();
        $image_path = opendir(TEMPLATEPATH . "/images/follow");
        while (false !== ($icon = readdir($image_path))) {
			if (!($icon == "." || $icon == "..")) {
	            if (!is_dir($icon)) {
    	            $icon_type = substr($icon, 0, strpos($icon, '-'));
	                if (isset($this->icon_suffixes[$icon_type])) {
		                $icon_type_suffixes = $this->icon_suffixes[$icon_type];
	                }
	                else {
		                $icon_type_suffixes = array();
	                }
    	            $icon_type_suffixes[] = substr($icon, strpos($icon, '-') + 1, strpos($icon, '.') - strpos($icon, '-') - 1);
        	        $this->icon_suffixes[$icon_type] = $icon_type_suffixes;
            	}
			}
        }
        $this->follow_urls = array(
            'Delicious' => array('account' => 'http://delicious.com/%account%'),
            'Digg' => array('account' => 'http://www.digg.com/users/%account%'),
            'Facebook' => array('url' => '%account%'),
            'Flickr' => array('account' => 'http://flickr.com/photos/%account%'),
            'LinkedIn' => array('url' => '%account%'),
            'Reddit' => array('account' => 'http://www.reddit.com/user/%account%'),
            'RSS' => array('account' => 'http://feeds.feedburner.com/%account%'),
            'StumbleUpon' => array('account' => 'http://%account%.stumbleupon.com'),
            'Technorati' => array('account' => 'http://technorati.com/people/technorati/%account%/'),
            'Twitter' => array('account' => 'http://twitter.com/%account%'));
	}

    function form($instance) {
        $defaults = array("title" => __("Follow Me", "suf_theme"),
            "feed" => __("your-feed-name", "suf_theme"),
            "show_email" => true,
            "default_text" => __("Enter email address", "suf_theme"),
            "button_text" => __("Subscribe", "suf_theme"),
            "show_stats" => false,
            "icon_size" => '48px',
        );
        foreach ($this->icon_suffixes as $icon_type => $icon_type_suffixes) {
            $defaults[$icon_type."_icon"] = $icon_type."-".$icon_type_suffixes[0];
            $defaults["show_".$icon_type] = false;
        }
        $instance = wp_parse_args((array)$instance, $defaults);
?>
<div style='display: inline-block; clear: both;'>
    <div style='float: left; width: 48%; margin-right: 20px;'>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'suf_theme'); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('feed'); ?>"><?php _e('FeedBurner Feed:', 'suf_theme'); ?></label>
            <input id="<?php echo $this->get_field_id('feed'); ?>" name="<?php echo $this->get_field_name('feed'); ?>" value="<?php echo $instance['feed']; ?>" class="widefat" />
            <i><?php printf(__('Enter your %1$s feed name. If your feed address is http://feeds.feedburner.com/my_feed_name, your feed name is my_feed_name', 'suf_theme'), '<a href="http://feedburner.google.com">FeedBurner</a>'); ?></i>
        </p>

        <p>
            <input id="<?php echo $this->get_field_id('show_email'); ?>" name="<?php echo $this->get_field_name('show_email'); ?>" type="checkbox" <?php checked( $instance['show_email'], 'on'); ?>  class="checkbox" />
            <label for="<?php echo $this->get_field_id('show_email'); ?>"><?php _e('Show Email Subscription', 'suf_theme'); ?></label>
            <i><?php _e("Will show a text box for visitors to enter an email address for subscription", "suf_theme"); ?></i>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('default_text'); ?>"><?php _e('Email Subscription Box Default Text:', 'suf_theme'); ?></label>
            <input id="<?php echo $this->get_field_id('default_text'); ?>" name="<?php echo $this->get_field_name('default_text'); ?>" value="<?php echo $instance['default_text']; ?>" class="widefat" />
            <i><?php _e("This text will be shown in the subscription box by default. When the visitor starts entering the email address the text will disappear.", "suf_theme"); ?></i>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Email Subscription Button Text:', 'suf_theme'); ?></label>
            <input id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" value="<?php echo $instance['button_text']; ?>" class="widefat" />
            <i><?php _e("This text will be shown in the subscription button near the email field", "suf_theme"); ?></i>
        </p>

        <p>
            <input id="<?php echo $this->get_field_id('show_stats'); ?>" name="<?php echo $this->get_field_name('show_stats'); ?>" type="checkbox" <?php checked( $instance['show_stats'], 'on'); ?>  class="checkbox" />
            <label for="<?php echo $this->get_field_id('show_stats'); ?>"><?php _e('Show FeedBurner reader count', 'suf_theme'); ?></label>
        </p>

<?php
        foreach($this->icon_suffixes as $icon_type => $icon_type_suffixes) {
?>
        <p>
            <input id="<?php echo $this->get_field_id('show_'.$icon_type); ?>" name="<?php echo $this->get_field_name('show_'.$icon_type); ?>" type="checkbox" <?php checked( $instance['show_'.$icon_type], 'on'); ?>  class="checkbox" />
            <label for="<?php echo $this->get_field_id('show_'.$icon_type); ?>">
                <?php printf(__('Show %1$s icon', 'suf_theme'), $icon_type); ?>
            </label>
        </p>
<?php
            if ($icon_type != 'RSS') {
                $url_or_account = $this->follow_urls[$icon_type];
?>
        <p>
            <label for="<?php echo $this->get_field_id($icon_type.'_account'); ?>">
<?php
                if (array_key_exists('account', $url_or_account)) {
                    printf(__('Enter %1$s account name:', 'suf_theme'), $icon_type);
                }
                else {
                    printf(__('Enter your full %1$s URL:', 'suf_theme'), $icon_type);
                }
?>
            </label>
            <input id="<?php echo $this->get_field_id($icon_type.'_account'); ?>" name="<?php echo $this->get_field_name($icon_type.'_account'); ?>" value="<?php if (isset($instance[$icon_type.'_account'])) echo $instance[$icon_type.'_account']; ?>" class="widefat" />
        </p>

<?php
            }
        }
?>
    </div>

    <div style='float: left; width: 48%;'>
<?php
        foreach($this->icon_suffixes as $icon_type => $icon_type_suffixes) {
            $icon = $instance[$icon_type."_icon"];
?>
        <p class="follow-icons">
            <label for="<?php echo $this->get_field_id($icon_type.'_icon'); ?>"><?php printf(__('Select your %1$s icon', 'suf_theme'), $icon_type); ?></label><br/>
<?php
            foreach($icon_type_suffixes as $icon_type_suffix) {
?>
            <span><input type="radio" name="<?php echo $this->get_field_name($icon_type.'_icon'); ?>" value="<?php echo $icon_type."-".$icon_type_suffix; ?>" <?php if ($icon_type."-".$icon_type_suffix == $icon) { echo  ' checked="checked" '; } ?>/><img src="<?php echo get_template_directory_uri(); ?>/images/follow/<?php echo $icon_type."-".$icon_type_suffix; ?>.png" alt="<?php echo $icon_type."-".$icon_type_suffix; ?>"/></span>
<?php
            }
?>
        </p>
<?php
        }
?>
        <p>
            <label for="<?php echo $this->get_field_id('icon_size'); ?>"><?php _e('Select your icon size', 'suf_theme'); ?></label><br/>
            <select name="<?php echo $this->get_field_name('icon_size'); ?>" id="<?php echo $this->get_field_id('icon_size'); ?>" class='widefat'>
<?php
            $size_array = array ('16px', '24px', '32px', '40px', '48px', '64px');
            foreach($size_array as $size) {
?>
                <option value="<?php echo $size; ?>" <?php if ($instance['icon_size'] == $size) { echo " selected "; } ?>><?php echo $size; ?></option>
<?php
            }
?>
            </select>
        </p>
    </div>
</div>
<?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance["title"] = strip_tags($new_instance["title"]);
        $instance["feed"] = strip_tags($new_instance["feed"]);
        $instance["show_email"] = $new_instance["show_email"];
        $instance["default_text"] = $new_instance["default_text"];
        $instance["button_text"] = $new_instance["button_text"];
        $instance["show_stats"] = $new_instance["show_stats"];
        $instance["icon_size"] = $new_instance["icon_size"];

        foreach ($this->icon_suffixes as $icon_type => $icon_type_suffixes) {
            $instance['show_'.$icon_type] = $new_instance['show_'.$icon_type];
            $instance[$icon_type.'_icon'] = $new_instance[$icon_type.'_icon'];
            $instance[$icon_type.'_account'] = $new_instance[$icon_type.'_account'];
        }

        return $instance;
    }

	function widget( $args, $instance ) {
		extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		$feed = $instance['feed'];
		$show_email = $instance['show_email'];
        $default_text = $instance['default_text'];
		$button_text = $instance['button_text'];
        $show_stats = $instance['show_stats'];
        $icon_size = $instance['icon_size'];
        echo $before_widget;
        if (!empty($title)) {
            echo $before_title;
            echo $title;
            echo $after_title;
        }
        if ($show_email) {
?>
    <form style="text-align:center;"
        action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow"
        onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feed; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
            <p>
                <input type="text" style="width:140px" name="email" value='<?php echo $default_text; ?>' class='subscription-email'
                    onfocus="if (this.value == '<?php echo $default_text; ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo $default_text; ?>';}" />
                <input type="hidden" value="<?php echo $feed; ?>" name="uri"/>
                <input type="hidden" name="loc" value="en_US"/>
                <input type="submit" value="<?php echo $button_text; ?>" />
            </p>
    </form>
<?php
        }
        if ($show_stats) {
?>
    <p>
        <a href="http://feeds.feedburner.com/<?php echo $feed;?>">
            <img src="http://feeds.feedburner.com/~fc/<?php echo $feed;?>?bg=99CCFF&amp;fg=444444&amp;anim=0" height="26" width="88" style="border:0" alt="" />
        </a>
    </p>
<?php
        }
?>
    <div class='fix'>
<?php
        foreach ($this->icon_suffixes as $icon_type => $icon_type_suffixes) {
            $show = $instance['show_'.$icon_type];
            $icon = $instance[$icon_type.'_icon'];
            $account = $instance[$icon_type.'_account'];
            if ($icon_type == 'RSS') {
                $account = $feed;
            }
            if ($show && !empty($account)) {
                $url_or_account = $this->follow_urls[$icon_type];
                foreach ($url_or_account as $key => $value) {
                    $hyperlink = str_replace('%account%', $account, $value);
?>
        <a href="<?php echo $hyperlink; ?>" class="follow-icon-and-tag" title="<?php echo $icon_type;?>">
            <img src="<?php echo get_template_directory_uri(); ?>/images/follow/<?php echo $icon;?>.png" alt="<?php echo $icon_type;?>" style='width: <?php echo $icon_size;?>; height: <?php echo $icon_size;?>;' />
        </a>
<?php
                }
            }
        }
?>
    </div>
<?php
        echo $after_widget;
    }
}
?>