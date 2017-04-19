<div class="property-popup">
    <div class="property-bar">
	<div class="container">
	    <div class="row">
		<div class="col-xs-12">
		    <div class="bar-wrap">
				<?php global $woocommerce;
				$cart_contents_count = $woocommerce->cart->cart_contents_count;
				if( !empty($cart_contents_count)): ?>
					<a href="<?php echo get_permalink(get_option( 'woocommerce_cart_page_id' )); ?>" class="in-basket"><?php _e( 'BASKET', 'luxurysqft' ); ?> (<strong><?php echo str_pad($cart_contents_count, 2, 0, STR_PAD_LEFT); ?></strong>)</a>
				<?php else: ?>
					<a href="<?php echo get_permalink(get_option( 'woocommerce_cart_page_id' )); ?>" class="in-basket"><?php _e( 'BASKET', 'luxurysqft' ); ?> (<strong>0</strong>)</a>
				<?php endif; ?>
				<span class="saved-items"><a href="<?php echo get_saved_url() ?>"><?php _e( 'saved', 'luxurysqft' ); ?> (<strong class="amount-items">0</strong>)</a></span>
			</div>
		</div>
	    </div>
	</div>
    </div>
</div>