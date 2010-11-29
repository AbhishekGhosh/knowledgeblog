<div id="sidebarC" class="sidebar">
	<ul>
		<?php if ( !( function_exists('dynamic_sidebar') && dynamic_sidebar('right') 
                              
                              ) ) { ?>
			<li><h2><?php _e('Archives:'); ?></h2>
				<ul>
				<?php wp_get_archives('type=monthly&limit=12'); ?>
				</ul>
			</li>
			<li><h2><?php _e('Meta:'); ?></h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional'); ?>"><?php _e('Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr>'); ?></a></li>
					<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS 2.0'); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
					<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
					<?php wp_meta(); ?>
				</ul>
			</li>
		<?php } ?>
	</ul>
</div>
