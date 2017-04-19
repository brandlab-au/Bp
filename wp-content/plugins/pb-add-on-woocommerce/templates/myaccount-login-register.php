<?php
/**
 * Profile Builder Login and Register Forms template for My Account page in WooCommerce
 *
 */

$wppb_woosync_settings = get_option( 'wppb_woosync_settings');
$shortcode = '[wppb-register]';

if ( $wppb_woosync_settings['RegisterForm'] != 'wppb-default-register' )
    $shortcode = '[wppb-register form_name=' . Wordpress_Creation_Kit_PB::wck_generate_slug( $wppb_woosync_settings['RegisterForm']) .']';

?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="woosync-col2-set" id="woosync_customer_login">

    <div class="woosync-col-1">

        <?php endif; ?>

        <h2><?php _e( 'Login', 'woocommerce' ); ?></h2>

        <?php echo do_shortcode('[wppb-login]')?>


        <?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

    </div>

    <div class="woosync-col-2">

        <h2><?php _e( 'Register', 'woocommerce' ); ?></h2>

        <?php echo do_shortcode($shortcode)?>

    </div>

</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
