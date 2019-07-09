<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package galaxy explorer
 */

get_header(); ?>

<div class="content-page-wrapper">
	<div class="container">

	<div class="content-page">
		<div id="post-0" class="post error404 not-found">
			
			<h1>Sorry, Page not found! (Error 404)</h1>
						
			<p style="font-size:17px;">The page you requested may have been moved or deleted.</p>
			<p style="font-size:15px;">Try to navigate from the menu or go back to <a href="<?php echo esc_url( home_url( '/' ) )?>">homepage.</a></p>
			
		</div>
	</div>

	</div>
</div>

<?php get_footer(); ?>
