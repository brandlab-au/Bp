<?php
function change_currency_symbol( $currency_symbol, $currency ) {
    if( $currency == 'AED' ) {
        $currency_symbol = $currency;
    }

    return $currency_symbol; 
}
add_filter('woocommerce_currency_symbol', 'change_currency_symbol', 10, 2);

function add_class_on_category_link( $term_links ){
    if( is_array( $term_links ) ) {
        foreach( $term_links as $key => $link ) {
            $term_links[ $key ] = str_replace( 'rel="tag"', 'rel="tag" class="category"', $link ); 
        }
    }

    return $term_links;
}
add_filter( 'term_links-product_cat', 'add_class_on_category_link' );

add_action( 'init', 'luxurysqft_remove_wc' );
function luxurysqft_remove_wc() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}

add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 35;' ), 20 );

add_filter( 'woocommerce_breadcrumb_defaults', 'luxurysqft_woocommerce_breadcrumbs' );
function luxurysqft_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => '</li><li>',
            'wrap_before' => '<div class="col-xs-12"><ul class="breadcrumbs"><li>',
            'wrap_after'  => '</li></ul></div><div class="clearfix"></div>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Store', 'breadcrumb', 'woocommerce' ),
        );
}

add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
function woo_custom_breadrumb_home_url() {
    return get_permalink(get_option( 'woocommerce_shop_page_id' ));
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
function woo_custom_cart_button_text() {
    return __( 'ADD TO BASKET', 'woocommerce' );
}

add_filter( 'woocommerce_output_related_products_args', 'luxurysqft_related_products_args' );
function luxurysqft_related_products_args( $args ) {
    $args['posts_per_page'] = 5; // 5 related products
    $args['columns'] = 1; // arranged in 1 columns
    return $args;
}