<?php
/**
 * Template Name: Log In
 *
 * Displays a "log in" page to your users.
 *
 * @package Suffusion
 * @subpackage Templates
 */

if (isset($_SERVER['REQUEST_METHOD']) && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'login' ) {
	global $error;
	$login = wp_signon(array('user_login' => esc_attr($_POST['user-name']), 'user_password' => esc_attr($_POST['password']), 'remember' => esc_attr($_POST['remember-me'])), false);
}

get_header();
?>
    <div id="main-col">
<?php
suffusion_page_navigation();
suffusion_before_begin_content();
?>
  <div id="content">
    <div class="post fix" id="post-<?php the_ID(); ?>">
<?php
suffusion_after_begin_post();
?>
        <div class="entry fix">
<?php
suffusion_content();
if (is_user_logged_in()) {
	global $user_ID;
	$login = get_userdata($user_ID);
	printf(__('You are currently logged in as <a href="%1$s" title="%2$s">%2$s</a>.', 'suf_theme'), get_author_posts_url($login->ID), esc_attr($login->display_name));
?>
		<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out'); ?>"><?php _e('Log out'); ?></a>
<?php
}
else if (isset($login) && isset($login->ID)) {
	$login = get_userdata($login->ID);
	printf(__('You have successfully logged in as <a href="%1$s" title="%2$s">%2$s</a>.', 'suf_theme'), get_author_posts_url($login->ID), esc_attr($login->display_name));
}
else {
	if ($error) {
		echo $error;
	}
	if (function_exists('wp_login_form')) {
		wp_login_form(array(
			'redirect' => site_url(),
			'id_username' => 'user-name',
			'id_password' => 'password',
			'id_submit' => 'submit',
			'id_remember' => 'remember-me',
		));
	}
	else {
?>
		<form action="<?php the_permalink(); ?>" method="post" class="sign-in">
			<p class="form-username">
				<label for="user-name"><?php _e('Username'); ?></label>
				<input type="text" name="user-name" id="user-name" class="text-input" value="<?php if (isset($_POST['user-name'])) echo esc_attr($_POST['user-name']); ?>" />
			</p><!-- .form-username -->

			<p class="form-password">
				<label for="password"><?php _e('Password'); ?></label>
				<input type="password" name="password" id="password" class="text-input" />
			</p><!-- .form-password -->

			<p class="form-submit">
				<input type="submit" name="submit" class="submit button" value="<?php _e('Log in'); ?>" />
				<input class="remember-me checkbox" name="remember-me" id="remember-me" type="checkbox" checked="checked" value="forever" />
				<label for="remember-me"><?php _e('Remember me'); ?></label>
				<input type="hidden" name="action" value="login" />
			</p><!-- .form-submit -->
		</form><!-- .sign-in -->
<?php
	}
}
?>
		</div><!--/entry -->
		<?php suffusion_before_end_post(); ?>
	<?php comments_template(); ?>

	</div><!--/post -->
</div></div>
<?php
get_footer();
?>