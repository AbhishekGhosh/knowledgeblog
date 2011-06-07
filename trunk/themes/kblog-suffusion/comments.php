<?php
/**
 * Threaded comments
 *
 * @package Suffusion
 * @subpackage Templates
 */
?>
<div id="comments">
<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
        die ('Please do not load this page directly. Thanks!');
    }
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", "suf_theme");?></p>
		</div> <!-- #comments -->
	<?php
		return;
	}
	global $options, $suffusion_options, $post, $post_id, $suffusion_unified_options, $user_identity;
	global $suf_comment_label_styles, $suf_comment_label_name, $suf_comment_label_email, $suf_comment_label_uri, $suf_comment_label_your_comment, $suf_comment_label_name_req, $suf_comment_label_email_req, $suf_comments_disabled_all_sel, $suf_comments_disabled, $suf_comments_disabled_msg_for_posts;

	// Begin Comments & Trackbacks
	if ( have_comments() ) { ?>
<h3 class="comments">
	<?php printf(__('%1$s to &#8220;%2$s&#8221;', "suf_theme"), comments_number(__('No Responses', "suf_theme"), __('One Response', "suf_theme"), __('% Responses', "suf_theme")), get_the_title($post->ID));?>
</h3>
<?php
		suffusion_split_comments();
	//	suffusion_comment_navigation(); // Cannot have comment navigation before listing the comments, because at this point we don't know if we are getting all comments or if we are separating out pingbacks and trackbacks
		suffusion_list_comments();
		suffusion_comment_navigation();
		// End Comments
	}

if ('open' == $post->comment_status) :
	$label_style = $suf_comment_label_styles == "plain" ? "" : " fancy ";

	if (function_exists('comment_form')) {
		comment_form(array(
			'fields' => array(
				'author' => "
					<p>
						<label for='author' class='$label_style'>$suf_comment_label_name</label>
						<input type='text' name='author' id='author' class='textarea' value='".esc_attr($comment_author)."' size='28' tabindex='1' />". ($req ? $suf_comment_label_name_req : "")."
					</p>",
				'email' => "
					<p>
						<label for='email' class='$label_style'>$suf_comment_label_email</label>
						<input type='text' name='email' id='email' value='".esc_attr($comment_author_email)."' size='28' tabindex='2' class='textarea' />". ($req ? $suf_comment_label_email_req : ""). "
					</p>",
				'url' => "
					<p>
						<label for='url' class='$label_style'>$suf_comment_label_uri</label>
						<input type='text' name='url' id='url' value='".esc_attr($comment_author_url)."' size='28' tabindex='3' class='textarea' />
					</p>"
			),
			'comment_field' => "
					<p>
						<label for='comment' class='textarea $label_style'>$suf_comment_label_your_comment</label>
						<textarea name='comment' id='comment' cols='60' rows='10' tabindex='4' class='textarea'></textarea>
					</p>",
			'logged_in_as' => '<p class="logged-in-as">'.sprintf(__('Logged in as %s. ', 'suf_theme'), "<a href='".admin_url('profile.php')."'>".$user_identity."</a>").
					' <a href="'.wp_logout_url(apply_filters('the_permalink', get_permalink($post_id))).'">'.__('Log out', 'suf_theme').'</a>.'.'</p>',
			'must_log_in' => '<p class="must-log-in">'.
					'<a href="'.wp_login_url(apply_filters('the_permalink', get_permalink($post_id))).'">'.__('You must be logged in to post a comment.', 'suf_theme').'</a></p>',
			'title_reply' => __('Leave a Reply', "suf_theme"),
			'title_reply_to' => __('Leave a Reply to %s', "suf_theme"),
			'label_submit' => __('Submit Comment', "suf_theme"),
			'comment_notes_before' => "<span></span>",
			'comment_notes_after' => '<p class="form-allowed-tags">'.sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'suf_theme'), '<code>'.allowed_tags().'</code>').'</p>',
			'cancel_reply_link' => __('Cancel reply', 'suf_theme'),
		));
	}
	else {
?>
<div id="respond">
<h3 class="respond"><?php comment_form_title( __('Leave a Reply', "suf_theme"), __('Leave a Reply to %s', "suf_theme") ); ?></h3>
<?php
		if ( get_option('comment_registration') && !$user_ID ) { ?>
<p><a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e("You must be logged in to post a comment.", "suf_theme");?></a></p>
<?php
		}
		else {
	?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php
			if ( $user_ID ) {
				$logged_in_url = "<a href=\"".get_option('siteurl')."/wp-admin/profile.php\">".$user_identity."</a>";
	?>

<p><?php printf(__('Logged in as %s. ', 'suf_theme'), $logged_in_url); ?>
<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e("Log out","suf_theme");?> &raquo;</a></p>
<?php
			}
			else {
?>
	<p>
	<label for="author" class="<?php echo $label_style; ?>"><?php echo $suf_comment_label_name; ?></label>
	<input type="text" name="author" id="author" class="textarea" value="<?php echo $comment_author; ?>" size="28" tabindex="1" />
	<?php if ($req) echo $suf_comment_label_name_req; ?>
	</p>

	<p>
	<label for="email" class="<?php echo $label_style; ?>"><?php echo $suf_comment_label_email; ?></label>
	<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="28" tabindex="2" class="textarea" />
	<?php if ($req) echo $suf_comment_label_email_req; ?>
	</p>

	<p>
	<label for="url" class="<?php echo $label_style; ?>"><?php echo $suf_comment_label_uri; ?></label>
	<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="28" tabindex="3" class="textarea" />
	</p>

<?php
			}
?>

	<p>
	<label for="comment" class="textarea <?php if (isset($label_style)) echo $label_style; ?>"><?php echo $suf_comment_label_your_comment; ?></label>
	<textarea name="comment" id="comment" cols="60" rows="10" tabindex="4" class="textarea"></textarea>
	</p>

<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>

	<?php comment_id_fields(); ?>
	<?php do_action('comment_form', $post->ID); ?>
	<p>
	<input name="submit" id="submit" type="submit" tabindex="5" value="<?php _e('Submit Comment', "suf_theme"); ?>" class="Cbutton" />
	</p>
</form>
<?php
	}
?>
</div>
<?php
}
?>
<?php else : // Comments are closed
	$message_disabled = false;
	if (is_page() && isset($suf_comments_disabled_all_sel) && $suf_comments_disabled_all_sel == 'all') {
		$message_disabled = true;
	}
	else if (is_page() && isset($suf_comments_disabled_all_sel) && $suf_comments_disabled_all_sel != 'all' && isset($suf_comments_disabled)) {
        $disabled_pages = explode(',', $suf_comments_disabled);
        if (is_array($disabled_pages) && count($disabled_pages) > 0 && in_array($post->ID, $disabled_pages)) {
            $message_disabled = true;
        }
    }
    else if (is_singular() && !is_page() && $suf_comments_disabled_msg_for_posts == 'hide'){
        $message_disabled = true;
    }
		if (!$message_disabled) {
?>
<p><?php _e('Sorry, the comment form is closed at this time.', "suf_theme"); ?></p>
<?php
		}
?>
<?php endif; ?>
</div>