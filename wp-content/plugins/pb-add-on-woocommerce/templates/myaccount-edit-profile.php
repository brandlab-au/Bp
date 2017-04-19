<?php
/**
 * Profile Builder Edit Profile Form template for My Account->Edit Account page in WooCommerce
 */

$wppb_woosync_settings = get_option( 'wppb_woosync_settings');

//Check if there is a specific Edit Profile form we need to display
if ( $wppb_woosync_settings['EditProfileForm'] == 'wppb-default-edit-profile' ){
    echo do_shortcode( '[wppb-edit-profile redirect_url="' . get_permalink( wc_get_page_id( 'myaccount' ) ) .'"]' );
}
else {
    echo do_shortcode( '[wppb-edit-profile form_name="' . Wordpress_Creation_Kit_PB::wck_generate_slug($wppb_woosync_settings['EditProfileForm']) . '"]');
}