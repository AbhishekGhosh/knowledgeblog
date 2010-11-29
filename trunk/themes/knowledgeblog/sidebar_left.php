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
					if ( function_exists( wp_list_categories ) ) {
						wp_list_categories('title_li=&orderby=name&hierarchical=1');
					}
					else {
						wp_list_cats('sort_column=name&optioncount=0&title_li=');
					}
				?>
				</ul>
			</li>
		<?php } ?>
	</ul>
</div>
