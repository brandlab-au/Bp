<?php global $last_viewed_products; ?>
<?php if( $last_viewed_products ) :
	$products_ids = exclude_id_from_array( get_the_ID(), $last_viewed_products );
	$r = new WP_Query( array(
		'post_type'   => 'product',
		'post__in' => $products_ids,
		'ignore_sticky_posts' => true,
		'posts_per_page' => 5,
		'post__not_in' => get_the_ID(),
		'orderby' => 'post__in',
		)
	);

	if ( $r->have_posts() ) : ?>
		<section class="block-content">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 text-center extra-padding">
						<h3 class="recent"><?php _e( 'Recently viewed', 'luxurysqft' ); ?></h3>
					</div>
					<div class="clearfix"></div>
					<div class="col-xs-12 col-md-12 text-center">
						<!-- products list -->
						<ul class="store-list">
							<?php  while ( $r->have_posts() ) : $r->the_post(); ?>
								<?php get_template_part('blocks/content-product') ?>
							<?php endwhile; ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
<?php endif ?>