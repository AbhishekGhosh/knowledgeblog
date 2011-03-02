<div id="sidebarA" class="sidebar">
	<ul>
		<?php if ( !( function_exists('dynamic_sidebar') && dynamic_sidebar('top') ) ){ ?>
			<li><h2><?php _e("Search"); ?></h2>
				<ul>
					<li><?php include (TEMPLATEPATH . '/searchform.php'); ?></li>
				</ul>
			</li>
		<?php } ?>
	</ul>
</div>
