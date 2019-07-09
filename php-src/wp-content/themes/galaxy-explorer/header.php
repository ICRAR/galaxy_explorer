<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package galaxy explorer
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<!-- for Facebook -->          
<meta property="og:title" content="Galaxy Explorer" />
<meta property="og:type" content="website" />
<meta property="og:image" content="<?php echo get_image_path();?>/facebook_share_general2.jpg" />
<meta property="og:url" content="<?php echo esc_url( home_url( '/' ) );?>" />
<meta property="og:description" content="Calling citizen scientists of Earth!  We need you to help classify galaxies far, far away. All you need is an internet connection and you can help out on a real research project." />

<meta name="WT.cg_n" content="GalaxyExplorer" />

<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="icon" type="image/x-icon" href="<?php echo get_image_path();?>/favicon.ico" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />



<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/webtrends/webtrends.load.science.js"></script>

<?php wp_head(); ?>

<?php 
//PLUGIN BUG (yoast google analytics).
//Ok this is very strange. with the plugin enable and the option to place the GA code in the header, ONLY with firebug activated, pages are running twice!!
//This is not good especially fot the tool page, where I'm picking up an image randomly and copy somewhere, so running twice it will copy the image twice.
//Hoever if I insert the code yoast code manually, this is not happening. 
//Itried to debug Yoast plugin and it seems the problem is in the function "spool_analytics", but couldn't narrow down

if ( function_exists( 'yoast_analytics' ) ) {
  yoast_analytics();
}

?>
</head>

<body <?php body_class(); ?>>
<p id="skipnav"><a href="#main-content">Skip navigation</a></p<
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none !important;
    }
  </style>
<![endif]-->

	
<div id="wrapper" class="hfeed site">


	<?php do_action( 'before' ); ?>
	
	<div id="header">
	
		<div class="container clear">
	
			<h2 class="logo-abc"><a href="http://www.abc.net.au/" target="_blank" title="ABC Home Page" rel="home"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></h2>
			<h1 class="logo-galaxy"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" ><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></h1>
		
			<div class="menu-bar">
			
				<?php if ( is_user_logged_in()): ?>
				
					<?php
						
						//create menu
						$menu = wp_nav_menu( array( 'theme_location' 	=> 'primary',
													'menu'				=> 'main-menu-logged-in',
													'menu_id'      		=> 'menu-main-menu',
													'menu_class'   		=> 'menu-loggedin',
													'container' 		=> false
						));
		
					?>
					
				<?php else: ?>
				
					<?php
		
						//create menu
						$menu = wp_nav_menu( array( 'theme_location' 	=> 'primary',
													'menu'				=> 'main-menu',
													'menu_id'    		=> 'menu-main-menu',
													'container' 		=> false
						));
					?>
					
				<?php endif; ?>
				
				
				
				<div class="toggle-block none" >
				<?php if ( is_user_logged_in()): ?>
				
					<a class="menu-login" href="<?php echo wp_logout_url( home_url() ); ?>"><span>Logout</span></a>
				
				<?php else:?>
				
					<a class="menu-login" href="javascript:void(0);"><span>Login</span></a>
					<div class="slide dropdown" style="display:none;">
							<div class="login-form">
								<form class="form"  method="post" id="form-login" action="">
									<fieldset>
										<h2>Login to Galaxy Explorer</h2>
		                            	<p>Login to join the Galaxy Explorer crew.</p> 
		                                <p>Haven't registered yet? <a href="<?php echo esc_url( home_url( '/' ) ).'register/';?>">Join now</a></p>
									<div class="form-error"></div>
									<input type="hidden" id="action_login" name="action" value="handle_login" />
									<div class="input-section">
										<div class="row">
											<label for="login_email">Email Address</label>
											<input type="text" id="login_email" class="addLabel" name="login_email" value="Email" />
										</div>
										<div class="row">
											<label for="login_password">Password</label>
											<input type="password" id="login_password" class="addLabel passwordReplace" name="login_password" value="Password" />
										</div>
									</div>
									<a href="javascript:void(0);" class="switch-action">Forgotten your password?</a>
									<div class="relative-wrapper clear">
										<a id="login_submit" href="javascript:void(0)" class="cta-30-white"><span>Login</span></a>
									</div>
								</fieldset>
							</form>
							
							<form class="form"  method="post" id="form-forgot" action="">
								<fieldset>
									<h2>Recover your password</h2>
									<p>Enter your email address and we'll email  you with instructions on how to reset your password.</p> 
									<div class="form-error"></div>
									<input type="hidden" id="action_forgot" name="action" value="handle_forgot" />
									<div class="input-section">
										<div class="row">
											<label for="forgot_email">Email Address</label>
											<input type="text" id="forgot_email" class="addLabel" name="forgot_email" value="Email" />
										</div>
									</div>
									<a href="javascript:void(0);" class="switch-action">Back to login</a>
									<div class="relative-wrapper">
										<a id="forgot_submit" href="javascript:void(0)" class="cta-30-white"><span>Send</span></a>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					
				<?php endif;?>	
				</div>
			</div>
		</div>
	</div>

	<div id="main" class="site-main"><a name="main-content" id="main-content"></a>
