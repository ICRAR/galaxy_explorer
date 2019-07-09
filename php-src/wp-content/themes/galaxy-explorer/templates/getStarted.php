<?php
/*
 * Template Name: Get Started Template
 */

// check if real or session user
$user_id = get_current_custom_user_id();

if (!$user_id) {
	
	// TODO
	echo "ERROR";
	die ();
}

if(is_user_logged_in()){

	wp_redirect('/classify/tutorial/');
	exit();
}

get_header ();

?>




<?php while ( have_posts() ) : the_post(); ?>

	<div id="get-started">
		<div id="get-started-intro" class="gradient">
			<?php echo get_tool_header_bar();?>
		</div>
		
		<div id="get-started-content" class="gradient">
			<div class="container clear">
				<div class="tool-steps">
					<h2><?php echo get_the_title()?></h2>
								
					<?php echo get_the_content();?>
					
					<div id="get-started-register" class="clear">
						<a class="cta-37" href="/classify/tutorial/"><span>I'll register later</span></a>
						<a class="cta-37" href="/register/"><span>Register now</span></a>
					</div>
					
					<div id="get-started-login">
						<span>Already registered?</span>
						<a href="javascript:void(0)">Login now</a>
					</div>
					
						
				</div>
				
				<div class="tool-image">
					<div id="tool-header">
						<div class="tool-header-info clear">
							<div class="left">
								<div class="clear">
									<p class="image-name">Tutorial Image</p>
								</div>
								<p>&nbsp;</p>
							</div>
							<div class="right">
								<?php 
									$share_data = array(
											'image_path' 		=> get_image_path()."/galaxy_placeholder.jpg",
											'image_name'		=> 'Tutorial Image',
											'image_distance'	=> '3.1 billion light years from Earth'
									);
									echo get_share_icons_tool($share_data, true);
								?>
							</div>
						</div>
						
						<?php echo get_zoom_controls();?>
						
						
						
					</div>
					
					<div class="tool-image-container">
						<input type="hidden" id="processing-image-id" name="processing-image-id" value="0">
						
						<img src="<?php echo get_image_path()?>/galaxy_placeholder.jpg" width="600" height="600" style="display: block" />
						
					</div>
				</div>
			</div>
		</div>
	</div>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
