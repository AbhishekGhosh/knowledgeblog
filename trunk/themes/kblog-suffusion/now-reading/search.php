<?php
/**
 * Search template for the Now Reading plugin.
 *
 * @package Suffusion
 * @subpackage NowReading
 */

get_header();
?>

<div id="main-col">
<?php suffusion_before_begin_content(); ?>
	<div id="content">
<?php suffusion_after_begin_content(); ?>
		<div class="post fix nr-post">
			<h1 class="posttitle">Search Results for <?php search_query(); ?></h1>
			<div class="bookdata fix">
<?php
if( can_now_reading_admin() ) {
?>
				<div class="manage">
					<a href="<?php manage_library_url(); ?>"><?php _e('Manage Books', NRTD);?></a>
				</div>
<?php
}
?>
			</div>

			<div class="booklisting">
<?php
$nr_book_query = "status=all&num=-1&search={$GLOBALS['query']}";
include(TEMPLATEPATH . '/now-reading/nr-shelf.php');
?>
			</div> <!-- /.booklisting -->
<?php
//library_search_form();
?>
<!--			<p><a href="<?php library_url(); ?>">&larr; Back to library</a></p>-->
		</div><!-- /.nr-post -->
	</div><!-- /#content -->
</div>
<?php
get_footer();
?>
