<?php if( $products = get_field('interiors') ) : ?>
    <section class="block-content">
	<div class="container">
	    <div class="row">
		<div class="col-xs-12 col-md-12 text-center">
		    <div class="heading-holder">
			<?php if( $interiors_title = get_field('interiors_title') ) : ?>
			    <h2><?php echo $interiors_title ?></h2>
			<?php endif ?>
			<a class="link" href="<?php echo get_post_type_archive_link( 'product' ); ?>"><?php _e( 'Store', 'luxurysqft' ); ?></a>
		    </div>
		    <!-- products list -->
		    <ul class="store-list">
			<?php foreach( $products as $post ) :
			    setup_postdata($post); ?>
			    <li>
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
								    <img src="<?php print $second_image_url; ?>" class="second" />
							    <?php } ?>
						    </a>
						    <a href="#" data-product-id="<?php the_ID() ?>"
						       class="function-button fa fa-heart-o fa-heart fa-times <?php echo $saved ?>"></a>
					    </div>
				    <?php endif ?>
				<h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
				<?php if( function_exists( 'wc_get_product' ) ) :
                    $_product = wc_get_product( get_the_ID() );
					if( $brands = wp_get_post_terms( get_the_ID(), 'brand' ) ):
						$counter = count($brands);
						$i = 1; foreach( $brands as $brand ): ?>
							<a href="<?php echo get_term_link($brand, 'brand'); ?>" class="category"><?php echo $brand->name; ?></a>
							<?php if( $i != $counter ): ?>,<?php endif; ?>
						<?php $i++; endforeach; ?>
					<?php endif; ?>
				    <span class="price"><?php echo $_product->get_price_html(); ?></span>
				<?php endif ?>
			    </li> 
			<?php endforeach;
			wp_reset_postdata();?>
		    </ul>
		</div>
	    </div>
	</div>
    </section>
<?php endif ?>