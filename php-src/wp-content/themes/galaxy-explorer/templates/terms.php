<?php
/*
Template Name: Terms and Conditions Template
*/

get_header(); ?>

<div class="content-page-wrapper">
	<div class="container">
	
		<?php while ( have_posts() ) : the_post(); ?>
	
			<div class="content-page">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
					<h1><?php the_title(); ?></h1>
						
					<?php the_content(); ?>
					
					<?php /*
					<div id="section-individual">
						<h2><?php the_field('acf_tc_individual_title'); ?></h2>
						<?php the_field('acf_tc_individual_content'); ?>
					</div>
					
					<div id="section-group">
						<h2><?php the_field('acf_tc_group_title'); ?></h2>
						<?php the_field('acf_tc_group_content'); ?>
					</div>
					*/?>
				</div>
			</div>
			
		<?php endwhile; ?>

	</div>
</div>

<?php get_footer(); ?>
