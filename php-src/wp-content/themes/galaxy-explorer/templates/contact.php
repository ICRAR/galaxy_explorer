<?php
/*
Template Name: Contact Us Template
*/

get_header(); ?>

<div class="content-page-wrapper">
	<div class="container">
	
		<?php while ( have_posts() ) : the_post(); ?>
	
			<div class="content-page">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
					<h1><?php the_title(); ?></h1>
						
					<div id="contactForm">
						<?php the_content(); ?>
					</div>
					
					<div id="submitted-panel">
						<?php the_field('acf_form_submitted_message'); ?>
					</div>
					
				</div>
			</div>
			
		<?php endwhile; ?>

	</div>
</div>

<?php get_footer(); ?>
