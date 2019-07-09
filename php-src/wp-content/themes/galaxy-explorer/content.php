<?php
/**
 * @package galaxy explorer
 */
?>

<div class="content-page">
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<h1><?php the_title(); ?></h1>
			
			<?php the_content(); ?>
		
	</div>
</div>
