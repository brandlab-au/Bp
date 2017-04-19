<?php


// Default settings
include( get_template_directory() . '/inc/default.php' );

// Custom Menu Walker
include( get_template_directory() . '/inc/classes.php' );

// Custom Text Widgets
include( get_template_directory() . '/inc/widgets.php' );

// Theme sidebars
include( get_template_directory() . '/inc/sidebars.php' );

// Theme thumbnails
include( get_template_directory() . '/inc/thumbnails.php' );

// Theme menus
include( get_template_directory() . '/inc/menus.php' );

// Theme css & js
include( get_template_directory() . '/inc/scripts.php' );

// Post types and Taxonomies
include( get_template_directory() . '/inc/post_types.php' );

// WooCommerce
include( get_template_directory() . '/inc/woocommerce.php' );

// Products AJAX load
include( get_template_directory() . '/inc/products-ajax.php' );

// Apartments AJAX load
include( get_template_directory() . '/inc/apartments-ajax.php' );

// Login redirect
include( get_template_directory() . '/inc/login-redirect.php' );

// Second developer
include( get_template_directory() . '/func-part2.php' );

error_reporting(E_ERROR | E_WARNING | E_PARSE);


/**
 * Display the templates for JS in the footer
 */
function luxsft_popup_init(){
	
	include get_template_directory().'/inc/popup.php';
}

add_action( 'wp_footer', 'luxsft_popup_init' );

// define the woocommerce_order_formatted_billing_address callback
function filter_woocommerce_order_formatted_billing_address( $array, $wc_order ) {
    $array = array(
        'first_name'    => $wc_order->billing_first_name,
        'last_name'     => $wc_order->billing_last_name,
        'company'       => 'Company: '.$wc_order->billing_company,
        'address_1'     => 'Address 1: '.$wc_order->billing_address_1,
        'address_2'     => 'Address 2: '.$wc_order->billing_address_2,
        'city'          => 'City: '.$wc_order->billing_city,
        'state'         => 'State: '.$wc_order->billing_state,
        'postcode'      => 'Postcode: '.$wc_order->billing_postcode,
        'country'       => 'Country: '.$wc_order->billing_country
    );
    return $array;
};

// add the filter
add_filter( 'woocommerce_order_formatted_billing_address', 'filter_woocommerce_order_formatted_billing_address', 10, 3 );
