<div id="tab5">
	<div class="tab-block page-heading text-center">
		<?php $current_user = wp_get_current_user(); ?>
		<div class="heading">
			<span class="name"><?php echo $current_user->display_name ?></span>
			<span class="link"><?php _e( 'Saved Properties', 'luxurysqft' ); ?></span>
		</div>
		<p><?php _e( 'This is your Luxury sqft account. Click on the following sections to manage your personal information, your previous orders, track your order or your gift cards.', 'luxurysqft' ); ?></p>
	</div>
	<div class="tab-description">
		<h3><?php _e( 'Properties you have saved', 'luxurysqft' ) ?></h3>
		<?php global $saved_ids;
		if ( $saved_ids ) :
			$args = array(
				'post_type'           => 'apartments',
				'posts_per_page'      => - 1,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'post__in'            => $saved_ids,
			);

			$query = new WP_Query( $args );
			if ( $query->have_posts() ) : ?>
				<ul class="apartments-list">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<li id="apartment-<?php the_ID() ?>" class="apartment-block">
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
						</li>
					<?php endwhile; ?>
				</ul>
			<?php endif;
			wp_reset_postdata(); ?>
		<?php else : ?>
			<h3><em><?php _e( "Sorry, but you are not selected apartments.", 'luxurysqft' ); ?></em></h3>
		<?php endif ?>
	</div>
</div>