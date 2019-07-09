<?php
/*
Template Name: Homepage Template
*/

get_header(); ?>


<?php while ( have_posts() ) : the_post(); ?>
	
	
	<div class="home-intro">
		<div class="container clear">
			<a id="callout-image" href="http://www.scienceweek.net.au" target="_blank"><img src="<?php echo get_image_path()?>/nsw_logo.png" alt="national science week" width="110" height="60" /></a>
			<h2>Be a citizen scientist</h2>
			<img src="<?php echo get_image_path()?>/home_intro_logo.png" alt="GALAXY EXPLORER" width="560" height="60" />
			<p>
				Classify a galaxy far, far away...<br />
				Take a trip to some of the furthest reaches of our Universe to help Australian scientists understand how galaxies grow and evolve.
			</p>
			<ul class="clear">
				<li><a href="/classify/" class="cta-37"><span>Get started</span></a></li>
				<li><a href="/about/" class="cta-37"><span>Learn more</span></a></li>
				
			</ul>
		</div>
	
	</div>
	
	
	<div class="progress-statement">
		<div class="container clear">
			<ul class="clear">
				<li><span><?php echo number_format(get_total_galaxies_under_analysis());?></span>galaxies to go</li>
				<li><span><?php echo number_format(get_total_galaxies_completed());?></span>galaxies completed</li>
				<li><span><?php echo number_format(get_option('total_galaxy_subcribers'));?></span>galactic explorers</li>
				<?php if (COMPETITION_ACTIVE):?>
					<li><a href="/win-a-telescope/"><span>WIN</span>a telescope</a></li>
				<?php else:?>
					<li><span>FINISHED</span>1st galaxy batch</a></li>
				<?php endif;?>
			</ul>
		</div>
	</div>
	
	
	
	<?php $rows_sections = get_field('acf_home_page_section');?>
	
	<?php foreach ($rows_sections as $section):?>
	
		<?php //handle tiles section?>
		<?php if(($left_image = $section['acf_home_section_tiles'][0]['acf_home_section_tiles_left_image']) &&
				 ($left_image_title = $section['acf_home_section_tiles'][0]['acf_home_section_tiles_left_title']) &&
				 ($right_image = $section['acf_home_section_tiles'][0]['acf_home_section_tiles_right_image']) &&
				 ($right_image_title = $section['acf_home_section_tiles'][0]['acf_home_section_tiles_right_title']) ):?>
				 
			<?php $left_image_link = $section['acf_home_section_tiles'][0]['acf_home_section_tiles_left_link']; ?>
			<?php $right_image_link = $section['acf_home_section_tiles'][0]['acf_home_section_tiles_right_link']; ?>
			 
			 
			 	 
			<div class="tiles-section gradient">
				<div class="container clear">
					<div class="tile-content first">
						
						<?php if($left_image_link):?>
							<a href="<?php echo $left_image_link;?>">
						<?php endif;?>
						
						<img alt="<?php echo $left_image_title;?>" src="<?php echo $left_image['url'];?>" width="485" height="235">
						<span><?php echo $left_image_title;?></span>
						
						<?php if($left_image_link):?>
							</a>
						<?php endif;?>
					</div>
					<div class="tile-content gradient">
						
						<?php if($right_image_link):?>
							<a href="<?php echo $right_image_link;?>">
						<?php else: ?>
							<a href="http://www.scienceweek.net.au/" target="_blank">
						<?php endif;?>
						
						<img alt="<?php echo $right_image_title;?>" src="<?php echo $right_image['url'];?>" width="485" height="235">
						<span><?php echo $right_image_title;?></span>
						
						<?php if(true || $right_image_link):?>
							</a>
						<?php endif;?>
					</div>
				</div>
			</div>
		<?php endif;?>
		
		
		<?php //handle callout section?>
		<?php if(($callout_title = $section['acf_home_section_callout'][0]['acf_home_section_callout_title']) &&
				 ($callout_copy = $section['acf_home_section_callout'][0]['acf_home_section_callout_copy'])):?>
				 
			<?php $callout_link = $section['acf_home_section_callout'][0]['acf_home_section_callout_link']; ?>
			
			
			<div class="callout-section gradient">
				<div class="container clear">
					
					<?php if($callout_link):?>
						<a href="<?php echo $callout_link;?>">
					<?php endif;?>
					
					<h3><?php echo $callout_title?></h3>
					
					<?php if($callout_link):?>
						</a>
					<?php endif;?>
					
					<?php echo $callout_copy;?>
				</div>
			</div>
			
		<?php endif;?>
		
	<?php endforeach;?>
	
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
