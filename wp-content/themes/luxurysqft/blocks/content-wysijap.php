<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="title">
	    <?php the_title( '<h1>', '</h1>' ); ?>		
	</div>
	<div class="content">
		<?php the_post_thumbnail( 'full' ); ?>
		<?php the_content(); ?>
	</div>
</div>
