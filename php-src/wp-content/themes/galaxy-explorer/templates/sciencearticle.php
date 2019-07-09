<?php
/*
Template Name: The Science Subpage Template
*/

get_header(); ?>

<div class="content-page-wrapper">
	<div class="container">
	
	<?php while ( have_posts() ) : the_post(); ?>
	
		<div class="content-page" id="science_aticle">
		
			<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
				
				<div class="article-sharing-top">
					<?php 
						//set the sharing image
						if(has_post_thumbnail()){
							$image_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
						}else{
							$image_url = get_image_path(). "/facebook_share_general.jpg";
						}
						
						$title = "Galaxy explorer - ".get_the_title();
						
						echo get_article_sharing_icons('top',$image_url,$title);
					?>
				</div>
					
				<h1><?php echo get_the_title();?></h1>
				
				<div class="content-intro-blue">
					<?php echo get_field('acf_blue_intro');?>
				</div>
				
				<div class="article-content">
					<?php the_content(); ?>
					
					<div class="article-sharing-bottom">
					<?php 
						//set the sharing image
						if(has_post_thumbnail()){
							$image_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
						}else{
							$image_url = get_image_path(). "/facebook_share_general.jpg";
						}
						
						$title = "Galaxy explorer - ".get_the_title();
						
						echo get_article_sharing_icons('bottom',$image_url,$title);
					?>
					</div>
				</div>
				
				
				<?php /*
				<div id="science-article-banner" class="clear">
					
					<h2>Start classifying galaxies</h2>
					
					<a href="/classify/" class="cta-40-white"><span>Get started</span></a>
				</div>
				*/?>
				
				<div id="subpages-list" class="clear">
					
					<h2>More Science</h2>
					
					<?php
						
						//get the list of parent subpages 
						$args = array(	'sort_order' => 'ASC',
										'sort_column' => 'menu_order',
										'exclude' => get_the_ID(),
										'child_of' => $post->post_parent,
										'post_type' => 'page',
										'post_status' => 'publish'
						); 
					
						$pages = get_pages($args); 
						$counter = 1;
					?>
						
					<?php foreach($pages as $page):?>
			
						<div class="subpage-tile<?php echo ($counter %2 == 0)? ' even': ' odd' ?>">
						
							<h4><a title="<?php echo $page->post_title?>" href="<?php echo get_permalink( $page->ID )?>"><?php echo get_the_title($page->ID)?></a></h4>
							<?php if(has_post_thumbnail($page->ID)): ?>
								<a title="<?php echo $page->post_title?>" href="<?php echo get_permalink( $page->ID )?>"><?php echo get_the_post_thumbnail($page->ID, 'science-subpage-list' )?></a>
							<?php endif;?>
	
							<a href="<?php echo get_permalink( $page->ID )?>"><?php echo get_field('acf_excerpt',$page->ID);?></a>
							
							<?php $counter++;?>
						</div>
						
					<?php endforeach;?>
					
					<div class="subpage-tile<?php echo ($counter %2 == 0)? ' even': ' odd' ?>">
						
						<h4><a title="Start classifying galaxies" href="/classify/">Start classifying galaxies</a></h4>
						<a title="Start classifying galaxies" href="/classify/"><img class="wp-post-image" src="<?php echo get_image_path()?>/start-classifying-promo.jpg" /></a>

						<a title="Start classifying galaxies" href="/classify/"><p>Take a trip to some of the furthest reaches of our Universe to help Australian scientists understand how galaxies grow and evolve.</p></a>
						
					</div>
					
				</div>
				
			</div>
		</div>
		
		
	<?php endwhile; // end of the loop. ?>

	</div>
</div>

<?php get_footer(); ?>
