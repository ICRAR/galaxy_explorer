<?php
/*
 * Template Name: Tool identifier Template
 */

// check if real or session user
$user_id = get_current_custom_user_id();

if (! $user_id) {
	
	// TODO
	echo "ERROR";
	die ();
}

// check if user first time, in case go to tutorial
$show_tutorial = show_tutorial();

if($show_tutorial){
	wp_redirect('/get-started/');
	exit();
}

//also get the total images done by the user for the various popup
$user_total_images = get_user_total_images();

//now, if user is not logged in and has completed the 10 images cap, redirect to register
if(!is_user_logged_in() && $user_total_images >= COMPETITION_IMAGES_SINGLE){
	
	wp_redirect('/register?message=guest');
	exit();
}

// get the image to work with
$image_data = get_image_to_identify();

//calculate values in pixels to draw the starting ellipse
$ellipse_data = convertImageDataToPixels($image_data);


get_header();

?>


<?php while ( have_posts() ) : the_post(); ?>

	<div id="galaxy-tool">
		<input type="hidden" id="processing-image-id" name="processing-image-id" value="<?php echo $image_data->id;?>">
		<input type="hidden" id="processing-image-name" name="processing-image-name" value="<?php echo $image_data->image_name;?>">
		<input type="hidden" name="reference-batch-number" value="<?php echo $image_data->batch_number;?>">
		<div id="tool-intro" class="gradient"> 
			<?php echo get_tool_header_bar();?>
		</div>
		
		<div id="tool-content" class="gradient">
			<div id="tool-container" class="container clear">
				
				
				<div class="tool-steps">
					<div class="steps-container clear">
						<input class="user-input-data" type="hidden" name="step-1-result" id="step-1-result" value="">
						<input class="user-input-data" type="hidden" name="step-2-result" id="step-2-result" value="">
						<input class="user-input-data" type="hidden" name="step-3-result" id="step-3-result" value="">
						<input class="user-input-data" type="hidden" name="step-4-result" id="step-4-result" value="">
						<span id="step-text-result"></span>
						
						<div class="step-wrapper" id="tool-first-step">
							<h2>What's in the centre of this image?</h2>
							<ul>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step1-value" name="step1-value" value="1">
										<p>One main galaxy</p>
										<img src="<?php echo get_image_path()?>/step1-single1.jpg" />
										<img src="<?php echo get_image_path()?>/step1-single2.jpg" />
										<img src="<?php echo get_image_path()?>/step1-single3.jpg" />
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step1-value" name="step1-value" value="2">
										<p>Galaxies colliding</p>
										<img src="<?php echo get_image_path()?>/step1-multiple.jpg" />
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step1-value" name="step1-value" value="3">
										<p>Messy or irregular galaxy</p>
										<img src="<?php echo get_image_path()?>/step1-irregular1.jpg" />
										<img src="<?php echo get_image_path()?>/step1-irregular2.jpg" />
										<img src="<?php echo get_image_path()?>/step1-irregular3.jpg" />
									</a>
								</li>
							</ul>
							<a href="javascript:void(0);" id="step-broken-image">Can't classify</a>
						</div>
						
						
						<div class="step-wrapper" id="tool-second-step">
							<h2>Does the galaxy have any features?</h2>
							<ul>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step2-value" name="step2-value" value="2">
										<p>No features - circle, oval or cigar shape</p>
										<img src="<?php echo get_image_path()?>/step2-no-feature1.jpg" />
										<img src="<?php echo get_image_path()?>/step2-no-feature2.jpg" />
										<img src="<?php echo get_image_path()?>/step4-yellowish-circle-oval3.jpg" />
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step2-value" name="step2-value" value="1">
										<p>Features - spiral arms, central bulge or central bar</p>
										<img src="<?php echo get_image_path()?>/step2-feature1.jpg" />
										<img src="<?php echo get_image_path()?>/step2-feature2.jpg" />
										<img src="<?php echo get_image_path()?>/step2-feature3.jpg" />
									</a>
								</li>
							</ul>
						</div>
						
						
						
						<div class="step-wrapper" id="tool-third-step">
							<h2>What features can you see?<br /><span>(you can choose more than one - see <a href="javascript:void(0)" id="trigger-guide2">Guide</a> for more examples)</span></h2>
							<ul>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step3-value" name="step3-value" value="1">
										<p>Central bar</p>
										<img src="<?php echo get_image_path()?>/step3-bar1.jpg" />
										<img src="<?php echo get_image_path()?>/step3-bar2.jpg" />
										<img src="<?php echo get_image_path()?>/step3-bar3.jpg" />
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step3-value" name="step3-value" value="2">
										<p>Central bulge</p>
										<img src="<?php echo get_image_path()?>/step3-bulge1.jpg" />
										<img src="<?php echo get_image_path()?>/step3-bulge2.jpg" />
										<img src="<?php echo get_image_path()?>/step3-bulge3.jpg" />
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step3-value" name="step3-value" value="3">
										<p>Spiral arms</p>
										<img src="<?php echo get_image_path()?>/step3-spirals1.jpg" />
										<img src="<?php echo get_image_path()?>/step3-spirals2.jpg" />
										<img src="<?php echo get_image_path()?>/step3-spirals3.jpg" />
									</a>
								</li>
							</ul>
							<a href="javascript:void(0);" id="step-3-continue" class="cta-35-next"><span>Next</span></a>
						</div>
						
						
						<div class="step-wrapper" id="tool-fourth-step">
							<h2>What best describes the galaxy?</h2>
							<ul>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step4-value" name="step4-value" value="1">
										<p>Blue-green circle or oval</p>
										<img src="<?php echo get_image_path()?>/step4-blue-circle-oval1.jpg" />
										<img src="<?php echo get_image_path()?>/step4-blue-circle-oval2.jpg" />
										<img src="<?php echo get_image_path()?>/step4-blue-circle-oval3.jpg" />
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step4-value" name="step4-value" value="2">
										<p>Yellow-orange circle or oval</p>
										<img src="<?php echo get_image_path()?>/step4-yellowish-circle-oval1.jpg" />
										<img src="<?php echo get_image_path()?>/step4-yellowish-circle-oval2.jpg" />
										<img src="<?php echo get_image_path()?>/step4-yellowish-circle-oval3.jpg" />
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<input type="hidden" class="step4-value" name="step4-value" value="3">
										<p>Or is it a cigar shape?</p>
										<img src="<?php echo get_image_path()?>/step4-cigar1.jpg" />
										<img src="<?php echo get_image_path()?>/step4-cigar2.jpg" />
										<img src="<?php echo get_image_path()?>/step4-cigar3.jpg" />
									</a>
								</li>
							</ul>
							
						</div>
						
						<div class="step-wrapper" id="tool-fifth-step">
							<h2>What's the size of the galaxy?</h2>
							<p>Fit the ring around the outside of the galaxy.<br />
								Mark any bright stars inside the red ring.</p>
							<div class="tips">
								<p>Tips:</p>
								<p>To fit the ring - drag the red square to the centre of the galaxy; drag the green squares to get the ring to fit around the whole galaxy.</p>
								<p>To mark stars- click to add a star marker, then drag it over the star. Click again to get rid of the marker.</p>
							</div> 
							
							<div class="relative-wrapper clear">
								<a href="javascript:void(0);" id="step-finish" class="cta-35"><span>Finish</span></a>
							</div>
						</div>
						
						<div class="step-wrapper" id="tool-no-canvas-step">
							<h2>Your classification</h2>
							<?php echo get_old_browser_notification();?>
							<p>Your classification was:</p>
							<div id="classification-summary"></div>
							<a href="javascript:void(0);" id="step-finish-no-canvas" class="cta-35"><span>Finish</span></a>
						</div>
						
						<?php /*
						<div class="step-wrapper" id="tool-no-classification">
							<h2>Title</h2>
							<p>copy</p>
							<a href="javascript:void(0);" id="step-finish-no-classification" class="cta-35"><span>Finish</span></a>
						</div>
						*/?>
						
						
					</div>
					
					<div class="step-navigation clear">
						<a class="cta-32" id="tool-restart" href="javascript:void(0)"><span>Restart</span></a>
						<a class="cta-32" id="help-menu-link" href="javascript:void(0)"><span>Help</span></a>
						
						<div id="help-menu">
							<span></span>
							<ul>
								<li><a id="trigger-tutorial" href="javascript:void(0)">Tutorial</a></li>
								<li><a id="trigger-guide" href="javascript:void(0)">Guide</a></li>
								<li><a id="trigger-faq" class="last" href="javascript:void(0)">FAQ</a></li>
							</ul>
						</div>
					</div>
						
				</div>
				
				<div class="tool-image">
					<div id="tool-header">
						<div class="tool-header-info clear">
							<div class="left">
								<div class="clear">
									<p class="image-name">Image <?php echo $image_data->image_name;?>
								</div>
								<p><?php echo convert_galaxy_distance($image_data->dist_ml_yr);?></p>
							</div>
							<div class="right">
								<?php 
									$share_data = array(
											'image_name'		=> $image_data->image_name,
											'image_path' 		=> get_tool_image_path(). '/'. $image_data->image_name .'.png',
											'image_distance'	=> convert_galaxy_distance($image_data->dist_ml_yr)
									);
									
									echo get_share_icons_tool($share_data);
								?>
							</div>
						</div>
						
						<?php echo get_zoom_controls();?>
						
					</div>
					<div class="tool-image-container">
						<input type="hidden" name="ellipse_data_x" id="ellipse_data_x" value="<?php echo $ellipse_data['x'];?>" />
						<input type="hidden" name="ellipse_data_y" id="ellipse_data_y" value="<?php echo $ellipse_data['y'];?>" />
						<input type="hidden" name="ellipse_data_rot" id="ellipse_data_rot" value="<?php echo $ellipse_data['rot'];?>" />
						<input type="hidden" name="ellipse_data_radApx" id="ellipse_data_radA" value="<?php echo $ellipse_data['radApx'];?>" />
						<input type="hidden" name="ellipse_data_radBpx" id="ellipse_data_radB" value="<?php echo $ellipse_data['radBpx'];?>" />
						<div id="tool-image-loading">Loading image...</div>
						<div id="crosshair-bottom"></div>
						<div id="crosshair-right"></div>
						<canvas class="disabled" id="image-canvas"></canvas>
						
						<script type="text/javascript">

							$( window ).load(function() {
	
								setImage("<?php echo get_tool_image_path() ?>/<?php echo $image_data->image_name;?>.png");
	
							});

							
						</script>
						
						<?php /*
						<img src="<?php echo get_tool_image_path() ?>/<?php echo $image_data->file_name;?>.<?php echo $image_data->file_extension;?>">
						*/?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	
<?php endwhile; // end of the loop. ?>


<?php 
	//check if user is elegible for competition popup
	if(is_user_logged_in() && get_user_meta(get_current_user_id(),'popup-competition', true ) == 'true' && COMPETITION_ACTIVE):
?>
	
	
	<script type="text/javascript">
	
	$(document).ready(function() {
	
		// set the background galaxy image
	    showCompetitionPopupOnStart();

	
	});
	</script>

	
<?php endif;?>


<?php 
	//check if I need to trigger login popup
	if(isset($_GET['login']) && $_GET['login'] == 'true' ):
?>
	
	<script type="text/javascript">
	
	$(document).ready(function() {
		
	    $('.menu-login').trigger('click');

	
	});
	</script>

	
<?php endif;?>

<?php /*
<script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer></script>
*/?>
		

<?php get_footer(); ?>
