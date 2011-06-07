<?php
/**
 * Search form
 *
 * @package Suffusion
 * @subpackage Templates
 */
?>

<form method="get" class="searchform" action="<?php bloginfo('url'); ?>/">
	<input type="text" value="<?php _e('Search','suf_theme');?>" name="s" class="searchfield" onfocus="if (this.value == '<?php _e("Search","suf_theme");?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e("Search","suf_theme");?>';}" />
	<input type="submit" class="searchsubmit" value="" name="searchsubmit" />
<?php
	if (function_exists('icl_object_id')) {
?>
	<input type="hidden" name="lang" value="<?php echo(ICL_LANGUAGE_CODE); ?>"/>
<?php
	}
?>
</form>
