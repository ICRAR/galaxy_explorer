<?php
/**
 * The Template for displaying all single posts.
 *
 * @package galaxy explorer
 */

get_header(); ?>

<div class="content-page-wrapper">
	<div class="container">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

	<?php endwhile; // end of the loop. ?>

	</div>
</div>

<?php get_footer(); ?>