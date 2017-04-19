<?php $current_user = wp_get_current_user(); 
$query = new WP_Query( array(
		'post_type' => 'apartments',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'ignore_sticky_posts' => true,
		'author' => $current_user->ID,
		'orderby' => 'meta_value',
		'meta_key' => '_prime',
		'order' => 'DESC'
	) );
if( $query->have_posts() ) : ?>
<div class="popup-holder">
	<?php $i = 0; while ( $query->have_posts() ) : $query->the_post(); ?>
		<div id="popup-gallery<?php echo the_ID(); ?>" class="lightbox2">
			<!-- cycle carousel -->
			<?php $images = get_field('apartment_images'); ?>
			<?php if( !empty($images) or has_post_thumbnail() ): ?>
				<div class="cycle-images">
					<div class="mask">
						<div class="slideset">
							<?php if( has_post_thumbnail() ): ?>
								<div class="slide">
									<?php the_post_thumbnail('full'); ?>
								</div>
							<?php endif; ?>
							<?php foreach( $images as $image ): ?>
								<div class="slide">
									<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<a class="btn-prev" href="#"></a>
					<a class="btn-next" href="#"></a>
				</div>
			<?php endif; ?>
		</div>
	<?php $i++; endwhile; ?>
</div>
<?php endif;
wp_reset_postdata(); ?>