<div id="tab2">
    <div class="tab-block page-heading text-center">
	<?php $current_user = wp_get_current_user(); ?>
	<div class="heading">
	    <span class="name"><?php echo $current_user->display_name ?></span>
	    <span class="link"><?php _e( 'Address', 'luxurysqft' ); ?></span>
	</div>
	<p><?php _e( 'This is your Luxury sqft account. Click on the following sections to manage your personal information, your previous orders, track your order or your gift cards.', 'luxurysqft' ); ?></p>
    </div>
    <div class="tab-description">
	<div class="tab-form">
	    <div class="holder-form">
		<h3><?php _e( 'Manage your Address', 'luxurysqft' ); ?></h3>
		<?php echo do_shortcode('[wppb-edit-profile form_name="custom-address"]'); ?>
	    </div>
	</div>
    </div>
</div>