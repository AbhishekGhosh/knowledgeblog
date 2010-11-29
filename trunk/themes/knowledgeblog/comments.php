<?php
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Access denied.');
if (!empty($post->post_password)) 
{
	if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { ?>

		<p class="nocomments"><?php _e('Enter your password to view comments.'); ?></p>
				
	<?php
		return;
	}
}
?>
<h2 id="comments"><?php comments_number(__('No Comments'), __('1 Comment'), __('% Comments')); ?></h2>
<?php if ($comments) : ?>
	<ol class="commentlist">
		<?php foreach ($comments as $comment) : ?>
			<li <?php if ($comment->user_id) echo 'class="author"'; ?> id="comment-<?php comment_ID() ?>">
				<span class="avatar"><?php echo get_avatar(get_comment_author_email (), '48'); ?></span>
				<p class="comment-author"><?php comment_author_link() ?></p>
				<p class="comment-data"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date() ?> @ <?php comment_time() ?></a></p>
				<?php if ($comment->comment_approved == '0') : ?>
					<span class="await_mod"><?php _e('Awaiting Moderation'); ?></span>
				<?php endif; ?>
				<p><?php comment_text() ?></p>
			</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>
