<div id="sidebarB" class="sidebar">
	<ul>
		<?php if ( !( function_exists('dynamic_sidebar') && dynamic_sidebar('left') ) ) { ?>
			<li><h2><?php _e('Pages:'); ?></h2>
				<ul>
				<?php wp_list_pages('title_li=&depth=1&sort_column=menu_order'); ?>
				</ul>
			</li>
			<li><h2><?php _e('Categories:'); ?></h2>
				<ul>
				<?php
                                                                                                  
					wp_list_categories('title_li=&orderby=name&hierarchical=1');
				?>
				</ul>
			</li>
		<?php } ?>
	</ul>
</div>
