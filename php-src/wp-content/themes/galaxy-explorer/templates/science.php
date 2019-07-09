<?php
/*
Template Name: The Science Template
*/

get_header(); ?>

<div class="content-page-wrapper">
	<div class="container">

	<?php while ( have_posts() ) : the_post(); ?>
		
		<div class="content-page" id="science" > 
			
			<div id="page-<?php the_ID(); ?>" <?php post_class(array('clear')); ?>>
				
				<div id="subpages-list" class="clear">
					
					<?php
		
						//get the list of subpages
						$args = array(	'sort_order' => 'ASC',
										'sort_column' => 'menu_order',
										'child_of' => get_the_ID(),
										'post_type' => 'page',
										'post_status' => 'publish'
						);
	
						$subpages = get_pages($args); 
						$counter = 1;
					?>
					
					<?php foreach($subpages as $page): ?>
					
						<div class="subpage-tile<?php echo ($counter %2 == 0)? ' even': ' odd' ?>">
						
							<h2><a href="<?php echo get_permalink( $page->ID )?>"><?php echo $page->post_title?></a></h2>
						
							<?php if(has_post_thumbnail($page->ID)): ?>
								<a title="<?php echo $page->post_title?>" href="<?php echo get_permalink( $page->ID )?>"><?php echo get_the_post_thumbnail($page->ID, 'science-subpage-list' )?></a>
							<?php endif;?>
	
							<a href="<?php echo get_permalink( $page->ID )?>"><?php echo get_field('acf_excerpt',$page->ID);?></a>
							
							<?php $counter++;?>
							
						</div>
						
					<?php endforeach;?>
				</div>
				
				<a href="/classify/" class="cta-30-white left"><span>Get started</span></a>
			</div>
		</div>
		
	<?php endwhile; // end of the loop. ?>

	</div>
</div>

<?php get_footer(); ?>
