<?php if( is_shop() ):
    $id = get_option( 'woocommerce_shop_page_id' );
else:
    $id = get_the_ID();
endif; ?>
<?php if( $advertisement = get_field( 'advertisement', $id ) ) : ?>
    <section class="block-content">
	<!-- advertisement block -->
	<div class="advertisement-block">
	    <div class="container">
		<div class="row">
		    <div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
			<h2><?php _e( 'ADVERTISEMENT', 'luxurysqft' ); ?></h2>
			<?php echo $advertisement ?>
		    </div>
		</div>
	    </div>
	</div>
    </section>
<?php endif ?>