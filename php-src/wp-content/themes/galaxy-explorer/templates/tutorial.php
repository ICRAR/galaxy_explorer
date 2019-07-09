<?php
/*
 * Template Name: Tutorial Template
 */

// check if real or session user
$user_id = get_current_custom_user_id();

if (!$user_id) {
	
	// TODO
	echo "ERROR";
	die ();
}

$show_tutorial = show_tutorial();

get_header ();

?>




<?php while ( have_posts() ) : the_post(); ?>

	<div id="almost-ready"<?php echo ($show_tutorial)?' class="first-time"':'';?>>
		<div id="almost-ready-intro" class="gradient">
			<?php echo get_tool_header_bar();?>
		</div>
		
		<div id="almost-ready-content" class="gradient">
			<div id="tool-container" class="container clear">
				<div class="tool-steps">
					<h2><?php echo get_the_title()?></h2>
								
					<?php echo get_the_content();?>
					
					<div id="almost-ready-register" class="clear">
						<a class="cta-37" id="trigger-tutorial" href="javascript:void(0)"><span>Watch the tutorial</span></a>
					
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
					
					<?php //tutorial-wrapper?>
					<div id="tutorial-popup">
					
						<a id="tutorial-close" href="javascript:void(0);"></a>
						
						<div class="step-content"></div>
						
						<div class="navigation">
							<a id="cta-tut-prev" href="javascript:void(0);"><span>Prev</span></a>
							<a id="cta-tut-next" href="javascript:void(0);"><span>Next</span></a>
						</div>
					</div>
					
					<?php //tutorial-storyboard?>
					<div id="storyboard" style="display:none">
						
						<div id="storyboard-step1">
							
							<h2>Galaxy Explorer Tutorial</h2>
							<p>Welcome! This brief tutorial will show you how to be a galaxy explorer - and there's a 
			                	longer video tutorial available at the end if you'd like more information. </p>
			                
			            </div>
			            
			            <div id="storyboard-step2">
			                <p>Explain this step</p>
			            </div>
			            
			            <div id="storyboard-step3">
			                <p>End tutorial</p>
			                <a class="cta-tut" id="finish-tutorial" href="javascript:void(0)"><span>Start Identifing</span></a>
			            </div>
			            
					</div>
					
					
				</div>
				
					
			</div>
		</div>
	</div>


<?php endwhile; // end of the loop. ?>
		

<?php get_footer(); ?>
