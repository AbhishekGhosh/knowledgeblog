<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
<input type="text" value="<?php echo attribute_escape($s); ?>" name="s" id="s" />
<input type="submit" id="searchsubmit" value="<?php _e("Search"); ?>" />
</form>
