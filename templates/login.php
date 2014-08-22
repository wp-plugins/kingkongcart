<?php
	if(!is_user_logged_in()){
?>
<?php get_header(); ?>

<div class="entry-content" style="text-align:center">

<h2><?php the_title(); ?></h2>

<div><?php echo $post->post_content; ?></div>
<form name="loginform" id="loginform" action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
	<p>
		<label>아이디<br />
		<input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" /></label>
	</p>
	<p>

		<label>비밀번호<br />
		<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
	</p>
	<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> 기억하기</label></p>
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="kingkongtheme_button" value="Log In" tabindex="100" />
		<input type="hidden" name="redirect_to" value="<?php echo get_option('home'); ?>/wp-admin/" />

		<input type="hidden" name="testcookie" value="1" />
	</p>
</form>

<p id="nav">
<a href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword" title="Password Lost and Found">비밀번호 찾기</a>
</p>

</div>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>

<?php
	}
?>