<?php
/*
Template Name: Shop Template
*/
get_header( ); ?>
<div class="main-holder">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-2">
				<?php if( function_exists( 'bcn_display_list' ) ) : ?>
					<!-- breadcrumbs -->
					<ul class="breadcrumbs">
					<?php bcn_display_list() ?>
					</ul>
				<?php endif ?>
			</div>
			<div class="col-xs-12">
				<?php while ( have_posts() ) : the_post(); ?>
					<div class="heading-holder">
						<?php the_title( '<h2>', '</h2>' ); ?>
						<a class="link" href="<?php echo get_permalink(get_option( 'woocommerce_shop_page_id' )); ?>"><?php _e( 'Store', 'luxurysqft' ); ?></a>
					</div>
					<?php the_content(); ?>
				<?php endwhile; ?>
				<?php wp_link_pages(); ?>
				<?php comments_template(); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>