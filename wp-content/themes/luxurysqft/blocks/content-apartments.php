<?php global $saved_ids, $saved_template;
$saved = is_array( $saved_ids ) && in_array( get_the_ID(), $saved_ids ) ? 'saved' : '' ?>
<div id="apartment-<?php the_ID() ?>" class="col-xs-12 col-sm-4 apartment-block">
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
				<?php endif; ?>
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
								class="value"><?php echo get_woocommerce_currency_symbol();?> <?php add_delimiter( $price ) ?></span>
						<?php } ?>

					</li>

				<?php else: ?>
					<li>
						<span class="title"><?php _e( 'Price', 'luxurysqft' ); ?></span>
						<span class="value"><?php print 'POR'; ?></span>
					</li>
				<?php endif; ?>
			</ul>
		<?php endif ?>
	</div>
</div>