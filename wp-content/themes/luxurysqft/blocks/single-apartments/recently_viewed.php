<?php global $last_viewed_apartments;
$apartments_ids = exclude_id_from_array( get_the_ID(), $last_viewed_apartments );
$query = new WP_Query( array(
	'post_type' => 'apartments',
	'posts_per_page' => 3,
	'post_status' => 'publish',
	'ignore_sticky_posts' => true,
	'post__in' => $apartments_ids,
	'orderby' => 'post__in'
		) );
if( $query->have_posts() && $apartments_ids ) : ?>
    <!-- products list -->
    <section class="block-content">
	<div class="container">
	    <div class="row">
		<div class="col-xs-12 text-center">
		    <h3><?php _e( 'Recently viewed', 'luxurysqft' ); ?></h3>
		</div>
		<div class="clearfix"></div>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		    <?php get_template_part( 'blocks/content', get_post_type() ); ?>
		<?php endwhile; ?>
		<div class="clearfix"></div>
	    </div>
	</div>
    </section>
<?php endif;
wp_reset_postdata(); ?>