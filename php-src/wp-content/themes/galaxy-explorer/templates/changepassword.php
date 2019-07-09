<?php
/*
Template Name: Change Password
*/

//check that all the GET fields are valorized
if(	!isset($_GET['action']) 	||
	$_GET['action'] != 'reset'	||
	!isset($_GET['key']) 		||
	!isset($_GET['login']) ){
	
	
	wp_redirect( home_url() ); exit;
	
}


$login = $_GET['login'];
$key = $_GET['key'];

get_header(); ?>



<div class="content-page-wrapper">
	<div class="container">
	
		<?php while ( have_posts() ) : the_post(); ?>
	
			<div id="change-pass" class="content-page">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
					<div class="txt">
						<h1>Password Reset</h1>
						<p>Enter your details below to reset your password.<br /><br /></p>
					</div>
					<form class="general-form" method="post" id="form-reset-password">
						<fieldset>
							<input type="hidden" id="action_reset" name="action" value="handle_reset" />
							
							<input type="hidden" name="get_login" value="<?php echo $login;?>" />
							<input type="hidden" name="get_key" value="<?php echo $key;?>" />
							
							<div id="error-message"></div>
							
							<div class="row">
								<input id="email" name="email" class="addLabel" type="text" value="<?php echo (isset($_POST['email']) ? $_POST['email'] : 'Email address'); ?>" maxlength="150" />
							</div>
							<div class="row">
								<input id="pass" class="addLabel passwordReplace" name="pass" type="password" value="Password" maxlength="150" />
								<span class="side-notes">Minimum 6 characters</span>
							</div>
							<div class="row">
								<input id="repassword" class="addLabel passwordReplace" name="repassword" type="password" value="Re-enter your password" maxlength="150" />
							</div>
							<a href="javascript:void(0);" class="cta-30-white" id="reset_password_submit"><span>Reset</span></a>
						</fieldset>
					</form>
				</div>
			</div>
			
		<?php endwhile; ?>

	</div>
</div>

<?php get_footer(); ?>
