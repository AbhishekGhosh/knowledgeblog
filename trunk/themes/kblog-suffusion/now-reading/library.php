<?php
/**
 * Library template for the Now Reading plugin.
 *
 * @package Suffusion
 * @subpackage NowReading
 */

get_header();
global $nr_book_query, $suffusion_unified_options;
foreach ($suffusion_unified_options as $id => $value) {
	$$id = $value;
}
$lib_order = suf_get_entity_order($suf_nr_lib_order, 'nr');
$lib_order = explode(',', $lib_order);
?>

<div id="main-col">
<?php suffusion_before_begin_content(); ?>
	<div id="content">
<?php suffusion_after_begin_content(); ?>
		<div class="post fix nr-post">
			<h1 class="posttitle"><?php echo stripslashes($suf_nr_lib_title); ?></h1>
			<div class="bookdata fix">
<?php
		if( can_now_reading_admin()) {
?>
				<div class="manage">
					<a href="<?php manage_library_url(); ?>"><?php _e('Manage Books', NRTD);?></a>
				</div>
<?php
		}
?>
			</div>
			<div class="entry">
<!--				There are <?php total_books() ?> books listed, of which <?php books_read_since('1 year') ?> have been read in the last year, <?php books_read_since('1 month') ?> read in the last month. That's a current average of <?php average_books('month'); ?>.-->
<?php
		foreach ($lib_order as $entity) {
			if ($entity == 'current' && $suf_nr_lib_curr_show == 'show') {
?>
				<h3><?php echo stripslashes($suf_nr_lib_curr_title);?><!-- (<?php echo total_books('reading', 0) ?>)--></h3>
<?php
				echo stripslashes($suf_nr_lib_curr_text);
?>
				<div class="booklisting">
<?php
				$nr_book_query = "status=reading&num=-1";
				include(TEMPLATEPATH . '/now-reading/nr-shelf.php');
?>
				</div>
<?php
			}
			else if ($entity == 'unread' && $suf_nr_lib_unread_show == 'show') {
?>
				<h3><?php echo stripslashes($suf_nr_lib_unread_title); ?><!--(<?php echo total_books('unread', 0) ?>)--></h3>
<?php
				echo stripslashes($suf_nr_lib_unread_text);
?>
				<div class="booklisting">
<?php
				$nr_book_query = "status=unread&num=-1";
				include(TEMPLATEPATH . '/now-reading/nr-shelf.php');
?>
				</div>
<?php
			}
			else if ($entity == 'completed' && $suf_nr_lib_completed_show == 'show') {
?>
				<h3><?php echo stripslashes($suf_nr_lib_completed_title); ?><!-- (<?php echo total_books('read', 0) ?>):--></h3>
<?php
				echo stripslashes($suf_nr_lib_completed_text);
?>
				<div class="booklisting">
<?php
				$nr_book_query = "status=read&num=-1";
				include(TEMPLATEPATH . '/now-reading/nr-shelf.php');
?>
				</div>
<?php
			}
		}
//		library_search_form();
?>
			</div>
		</div>
	</div><!-- /#content -->
</div><!-- /#main-col -->
<?php
get_footer();
?>
