<?php get_header(); ?>
	<section class="password-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 text-center">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_title( '<div class="heading"><h2>', '</h2></div>' ); ?>
					<?php the_post_thumbnail( 'full' ); ?>
					<?php the_content(); ?>
					<?php edit_post_link( __( 'Edit', 'luxurysqft' ) ); ?>
				<?php endwhile; ?>
				<?php wp_link_pages(); ?>
				<?php comments_template(); ?>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>