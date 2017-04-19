<?php
function apartments_ajax_load( $post_id ) {
	//update_post_meta( $post_id, '_prime', date('YmdGis') );
	$current_user = wp_get_current_user();
	$r            = new WP_Query( array(
		'post_type'           => 'apartments',
		'posts_per_page'      => - 1,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'author'              => $current_user->ID,
	) );
	if ( $r->have_posts() ) :
		while ( $r->have_posts() ) : $r->the_post();
			$prime = get_field( '_prime' );
			if ( empty( $prime ) or ! isset( $prime ) ):
				update_post_meta( get_the_ID(), '_prime', '0' );
			endif;
		endwhile;
	endif;
	wp_reset_postdata();

	$edit_url = wp_nonce_url( home_url() . '/my-account/settings/', 'edit_post_from_listings', 'edit_apartment' );
	$query    = new WP_Query( array(
		'post_type'           => 'apartments',
		'posts_per_page'      => - 1,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'author'              => $current_user->ID,
		'orderby'             => 'meta_value',
		'meta_key'            => '_prime',
		'order'               => 'DESC'
	) );
	if ( $query->have_posts() ) :
		global $saved_ids; ?>
		<?php $i = 0;
		while ( $query->have_posts() ) : $query->the_post();

			$saved = is_array( $saved_ids ) && in_array( get_the_ID(), $saved_ids ) ? 'saved' : '' ?>
			<li data-post-id="<?php the_ID() ?>" class="apartment-block">

				<div class="visual lazy">
					<?php if ( has_post_thumbnail() ) :
						$image_info = get_x2_image( get_the_ID(), array(
							'thumbnail_456x307',
							'thumbnail_912x614'
						) );
						$apartment_images = get_field( 'apartment_images', get_the_ID() );

						if ( isset( $apartment_images[2] ) ) {
							$second_image_url = wp_get_attachment_image_src( $apartment_images[2]['ID'], 'thumbnail_936x662' )[0];
						}

						?>
						<div class="visual lazy">
							<a href="<?php the_permalink() ?>" class="img-apartment">
								<img src="<?php echo $image_info['thumbnail_456x307'] ?>"
								     alt="<?php echo $image_info['alt'] ?>">
								<?php if ( isset( $second_image_url ) ) { ?>
									<img src="<?php print $second_image_url; ?>" class="second"/>
								<?php } ?>
							</a>
							<a href="#" data-product-id="<?php the_ID() ?>"
							   class="function-button fa fa-heart-o fa-heart fa-times <?php echo $saved ?>"></a>
						</div>
					<?php endif ?>
					<div class="drop-block">
						<div class="holder">
							<ul class="btn-list">
								<li><a class="lightbox" data-fancybox-type="ajax"
								       href="<?php echo add_query_arg( 'id', get_the_ID(), $edit_url ) ?>"><?php _e( 'EDIT PROPERTY', 'luxurysqft' ); ?></a>
								</li>
								<?php $account_page = get_field( 'account_page', 'option' ); ?>
								<li><a href="#" class="prime"><?php _e( 'MAKE THIS PRIME', 'luxurysqft' ); ?></a></li>
								<?php $images = get_field( 'apartment_images' ); ?>
								<?php if ( ! empty( $images ) or has_post_thumbnail() ): ?>
									<li><a href="#popup-gallery<?php echo the_ID(); ?>"
									       class="lightbox"><?php _e( 'GALLERY', 'luxurysqft' ); ?></a></li>
								<?php endif; ?>
								<li><a class="remove open" href="#"><?php _e( 'REMOVE PROPERTY', 'luxurysqft' ); ?></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<?php $country_info = get_country( get_the_ID() ); ?>
				<div class="description">
					<h3>
						<a href="<?php the_permalink() ?>"><?php the_title() ?></a><?php echo get_town( get_the_ID(), $country_info['term_id'] ) ?>
					</h3>
					<span class="country"><?php echo $country_info['country'] ?></span>
					<?php $bedrooms = get_field( 'bedrooms' );
					$sqft           = get_field( 'sqft' );
					$price          = get_field( '_price' );
					if ( $bedrooms or $sqft or $price ) : ?>
						<ul class="value-list">
							<?php if ( $bedrooms ) : ?>
								<li>
									<span class="title"><?php _e( 'Bedrooms', 'luxurysqft' ); ?></span>
									<span class="value"><?php echo $bedrooms ?></span>
								</li>
							<?php endif ?>
							<?php if ( $sqft ) : ?>
								<li>
									<span class="title"><?php _e( 'Sq.ft', 'luxurysqft' ); ?></span>
									<span class="value"><?php add_delimiter( $sqft ) ?></span>
								</li>
							<?php endif ?>
							<?php if ( $price ) : ?>
								<li>
									<span class="title"><?php _e( 'Price', 'luxurysqft' ); ?></span>
									<?php if ( $price == 'POR' ) { ?>
										<span class="value"><?php print $price; ?></span>
									<?php } else { ?>
										<span
											class="value"><?php _e( 'AED', 'luxurysqft' ); ?> <?php add_delimiter( $price ) ?></span>
									<?php } ?>

								</li>
							<?php endif ?>
						</ul>
					<?php endif ?>
				</div>
				<div class="popup-remove">
					<a class="close" href="#"><span>&nbsp;</span></a>
					<strong
						class="text-remove"><?php _e( 'Are you sure you want to delete this property, as delete the property removes this from the system for ever?', 'luxurysqft' ); ?></strong>
					<div class="buttons-remove">
						<a class="btn btn-default remove-block" href="#"><?php _e( 'Yes', 'luxurysqft' ); ?></a>
						<a class="btn btn-default open" href="#"><?php _e( 'No', 'luxurysqft' ); ?></a>
					</div>
				</div>
			</li>
			<?php $i ++; endwhile;
	else:
		get_template_part( 'blocks/not_found' );
	endif;
	wp_reset_postdata();
}