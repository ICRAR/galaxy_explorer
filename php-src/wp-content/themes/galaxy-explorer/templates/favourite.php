<?php
/*
 * Template Name: Favourite Template
 */
get_header ();

$favourite_list = get_favourite_list();

?>


<?php while ( have_posts() ) : the_post(); ?>

	<div id="favourite-page">
		<div id="tool-intro" class="gradient">
			<?php echo get_tool_header_bar();?>
		</div>
	</div>
	
	<div id="favourite-content" class="gradient">
		<div class="container">
			<div class="favourite-intro">
				<h2><?php echo get_the_title();?></h2>
				
				<?php if(!is_user_logged_in()):?>
				
					<div class="favourite-message clear">
				
						<p>
							Your favourites will only be stored for this session.<br />
							<a href="javascript:void(0)" id="trigger-login-panel">Login</a> or <a href="/register/">register now</a> so you can save them for next time.
						</p> 
						
					</div>
				<?php endif;?>
			</div>
			
			<div class="favourite-back clear">
				<a href="javascript:window.close();" class="cta-30-back"><span>Back to Classifying</span></a>
			</div>
			
			
			<div class="favourite-tiles clear">
			
				
				<?php if ($favourite_list):?>
				
					<?php foreach($favourite_list as $favourite):?>
					
						<?php 
							$image_id = $favourite->image_id;
							$image_name = $favourite->image_name;
							$image_path = get_tool_image_path(). '/'.$favourite->image_name. '.png';
							$favourite_id = $favourite->favourite_id;
							$date_added = date('d/m/Y', strtotime($favourite->date_added));
							$result_id = $favourite->result_id;
							$image_distance = convert_galaxy_distance($favourite->dist_ml_yr);
							
							//special case for tutorial image
							if($image_id == 0){
								
								$image_name = "Tutorial Image";
								$image_path = get_image_path(). '/galaxy_placeholder.jpg';
								$result_id = '';
								$image_distance = '3.1 billion light years from Earth';
								
							}
							
						?>
					
					
					
						<div class="favourite-tile" id="favourite-tile-<?php echo $image_id;?>">
							<a href="javascript:void(0);" class="favourite-tile-delete">Delete</a>
							<a href="javascript:void(0);" class="image-wrapper"><img class="source-data-image" src="<?php echo $image_path?>" /></a>
							<div class="favourite-tile-data">
								<span class="source-favourite-id"><?php echo $favourite_id;?></span>
								<span class="source-data-date-analized"><?php echo $date_added;?></span>
								<?php if($result_id != ''):?>
								
									<?php 
									$classification_array = array();
									
									if(trim($favourite->step1)){
										$classification_array[] = trim($favourite->step1);
									}
									if(trim($favourite->step2)){
										
										if(trim($favourite->step3)){
											$classification_array[] = trim($favourite->step2). ' '.trim($favourite->step3);
										}
										if(trim($favourite->step4)){
											$classification_array[] = trim($favourite->step2). ' '.trim($favourite->step4);
										}
										
										
									}
									
									
									?>
									<span class="source-data-classification">
										<?php echo implode(', ', $classification_array) ;?>
									</span>
									
								<?php endif;?>
								<span class="source-data-image-name"><?php echo $image_name;?></span>
								<span class="source-data-data-distance"><?php echo $image_distance;?></span>
								
							</div>
						</div>
						
					
					<?php endforeach;?>
					
					
				
				<?php else:?>
				
					<p>You don't have any favourite galaxy saved yet!
					
				<?php endif;?>
			</div>
			
			
			<?php //TODO move this in the if, after the loop?>
			<div id="favourite-lightbox-wrapper" style="display:none;">
				
				<div id="favourite-lightbox">
				
					<span id="fav-data-tile-id"></span>
					<span id="fav-data-favourite-id"></span>
					<span id="fav-data-baseurl"><?php echo esc_url( home_url( '/' ) );?></span>
					
					
					<div class="lightbox-image">
						<a id="favourite-lightbox-delete" href="javascript:void(0);">Delete</a>
						<img id="fav-data-image" src="">
					</div>
					
					<div class="lightbox-info">
						<div class="top clear">
							<p>You analysed this image on <span id="fav-data-date-analized">xx/xx/xxxx</span>.</p>
							<div id="fb-root"></div>
							<script type="text/javascript">
								window.fbAsyncInit = function() {
									FB.init({
										appId : "705396276232380",
										status : true,
										cookie : true,
										xfbml : true
									});
								};
								(function() {
									var e = document.createElement("script");
									e.async = true;
									e.src = document.location.protocol
									+ "//connect.facebook.net/en_US/all.js";
					
								document.getElementById("fb-root").appendChild(e);
								}()
					
								);
							</script>
							<?php echo get_share_icons_tool(false, false, false);?>
						</div>
						
						<div class="lightbox-classification">
							<p >Your classification was:<br />
								<span id="fav-data-classification">xxxxxxxxxxxxxxxxxxxxxxxxx</span>
							</p>
						</div>
						
						<div class="bottom clear">
							<div class="left">
								<p>Image ID: <span id="fav-data-image-name">xxxxxxxx</span></p>
								
							</div>
							<div class="right">
								<p>Distance from Earth: <span id="fav-data-distance">xxxxxxxxxxxxxxxxxxx</span></p>
								
							</div>
						</div>
					</div>
					
					<a href="javascript:void(0);" class="favourite-gallery-next">Next</a>
					<a href="javascript:void(0);" class="favourite-gallery-prev">Prev</a>
				</div>
			</div>
		</div>
	</div>


<?php endwhile; // end of the loop. ?>
		

<?php get_footer(); ?>
