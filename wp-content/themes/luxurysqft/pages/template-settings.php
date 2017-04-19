<?php
/*
Template Name: Settings Template
*/
get_header( ); ?>
<nav id="bar">
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
</nav>
<div class="tab-section">
    <div class="container">
	<div class="row">
	    <?php if( function_exists( 'bcn_display_list' ) ) : ?>
		<div class="col-xs-12 col-md-2">
		    <!-- breadcrumbs -->
		    <ul class="breadcrumbs">
			<?php bcn_display_list() ?>
		    </ul>
		</div>
	    <?php endif ?>
	    <div class="col-xs-12 col-md-9 col-lg-8">
		<?php if ( is_user_logged_in() ) : ?>
			<div class="tabset-slide">
                <a class="tabset-opener" href="#"><span>&nbsp;</span></a>
				<!-- tabs switcher -->
				<ul class="tabset">
					<li class="active"><a href="#tab1"><span><?php _e( 'Account Details', 'luxurysqft' ); ?></span></a>
						<!-- tabs switcher -->
						<ul class="sub-tabset">
						<?php /* <li><a href="#tab1_1">- <?php _e( 'Dashboard', 'luxurysqft' ); ?></a></li> */ ?>
						<?php //if ( current_user_can( 'delete_users' ) ) : ?>
							<li><a href="#tab1_2">- <?php _e( 'My Agents', 'luxurysqft' ); ?></a></li>
						<?php //endif ?>
						<li><a href="#tab1_3">- <?php _e( 'My Leads', 'luxurysqft' ); ?></a></li>
						<li><a href="#tab1_4">- <?php _e( 'my listings', 'luxurysqft' ); ?></a></li>
						<?php /* <li><a href="#tab1_5">- <?php _e( 'my statistics', 'luxurysqft' ); ?></a></li> */ ?>
						</ul>
					</li>
					<li><a href="#tab2"><span><?php _e( 'Address', 'luxurysqft' ); ?></span></a></li>
					<li><a href="#tab3"><span><?php _e( 'My orders', 'luxurysqft' ); ?></span></a></li>
					<li><a href="#tab4"><span><?php _e( 'SUBSCRIPTION', 'luxurysqft' ); ?></span></a></li>
					<li><a href="#tab5"><span><?php _e( 'SAVED PROPERTIES', 'luxurysqft' ); ?></span></a></li>
				</ul>
			</div>
		    <!-- tabs content holder -->
		    <div class="tab-content main-tabs">
			<?php get_template_part( 'blocks/settings/account-details' ) // tab1 ?>

			<?php //get_template_part( 'blocks/settings/dashboard' ) // tab1_1 ?>
			<?php if ( current_user_can( 'delete_users' ) ) : ?>
			    <?php get_template_part( 'blocks/settings/my-agents' ) // tab1_2 ?>
			<?php endif ?>
			<?php get_template_part( 'blocks/settings/my-leads' ) // tab1_3 ?>
			<?php get_template_part( 'blocks/settings/my-listings' ) // tab1_4 ?>
			<?php //get_template_part( 'blocks/settings/my-statistics' ) // tab1_5 ?>

			<?php get_template_part( 'blocks/settings/address' ) // tab2 ?>
			<?php get_template_part( 'blocks/settings/my-orders' ) // tab3 ?>
			<?php get_template_part( 'blocks/settings/subscription' ) // tab4 ?>
			<?php get_template_part( 'blocks/settings/saved-properties' ) // tab5 ?>
		    </div>
		<?php else : ?>
		    <?php _e( 'Please login', 'luxurysqft' ); ?>
		<?php endif ?>
	    </div>
	</div>
    </div>
</div>
<?php get_footer(); ?>