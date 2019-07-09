<?php
/*
Template Name: FAQ Template
*/

get_header(); ?>

<div class="content-page-wrapper">
	<div class="container">
	
	<?php while ( have_posts() ) : the_post(); ?>

		<div class="content-page" id="faq"> 
			
			<div id="page-<?php the_ID(); ?>" <?php post_class(array('clear')); ?>>
				
				<h1><?php the_title(); ?></h1>
					
				<?php the_content(); ?>
				
				<div id="faq-section">
				
					<?php $rows_sections = get_field('acf_faq_section'); ?>
					
					<?php if($rows_sections):?>
					
						<?php foreach($rows_sections as $row_section):?>
						
							<h2><?php echo $row_section['acf_faq_section_name']?></h2>
							
							<?php $rows_faqs = $row_section['acf_faq_row'];?>
							
							<?php if($rows_faqs):?>
							
								<?php foreach($rows_faqs as $row):?>
								
									<div class="faq-item">
										
										<a class="faq-question" href="javascript:void(0);"><?php echo $row['acf_faq_row_question']?></a>
										<div class="faq-answer">
											<?php echo $row['acf_faq_row_answer'];?>
										</div>
									</div>
									
	
								<?php endforeach;?>
							<?php endif;?>
						<?php endforeach;?>
					<?php endif;?>
				</div>
				
				<div id="questionForm">
					<h2>Ask a question</h2>
					<?php echo do_shortcode( '[contact-form-7 id="127" title="Ask a question"]' ); ?>
				</div>
				
				<div id="questionForm-submit">
					<h2>Ask a question</h2>
					<p>Thanks for your question. We'll email you to let you know when we've posted up the answer.</p>
				</div>
				
			</div><!-- #post-## -->
		</div>
			

		

	<?php endwhile; // end of the loop. ?>

	</div>
</div>

<?php get_footer(); ?>
