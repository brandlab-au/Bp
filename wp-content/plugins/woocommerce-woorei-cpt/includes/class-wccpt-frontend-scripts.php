<?php
/**
 * Handle frontend scripts
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WCCPT_Frontend_Scripts Class.
 */
class WCCPT_Frontend_Scripts {

	/**
	 * Contains an array of script handles registered by WC.
	 * @var array
	 */
	private static $scripts = array();

	/**
	 * Contains an array of script handles registered by WC.
	 * @var array
	 */
	private static $styles = array();

	/**
	 * Contains an array of script handles localized by WC.
	 * @var array
	 */
	private static $wp_localize_scripts = array();

	/**
	 * Hook in methods.
	 */
	public static function init() {
		
		add_action( 'woocommerce_init', array( __CLASS__, 'setup_scripts' ) );
	}
	
	public static function setup_scripts(){
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
		add_action( 'wp_print_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
	}

	/**
	 * Get styles for the frontend.
	 * @access private
	 * @return array
	 */
	public static function get_styles() {
		return apply_filters( 'woocommerce_enqueue_styles', array(
			'woocommerce-layout' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/css/woocommerce-layout.css',
				'deps'    => '',
				'version' => WC_VERSION,
				'media'   => 'all'
			),
			'woocommerce-smallscreen' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/css/woocommerce-smallscreen.css',
				'deps'    => 'woocommerce-layout',
				'version' => WC_VERSION,
				'media'   => 'only screen and (max-width: ' . apply_filters( 'woocommerce_style_smallscreen_breakpoint', $breakpoint = '768px' ) . ')'
			),
			'woocommerce-general' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/css/woocommerce.css',
				'deps'    => '',
				'version' => WC_VERSION,
				'media'   => 'all'
			),
		) );
	}

	/**
	 * Register a script for use.
	 *
	 * @uses   wp_register_script()
	 * @access private
	 * @param  string   $handle
	 * @param  string   $path
	 * @param  string[] $deps
	 * @param  string   $version
	 * @param  boolean  $in_footer
	 */
	private static function register_script( $handle, $path, $deps = array( 'jquery' ), $version = WC_VERSION, $in_footer = true ) {
		self::$scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
	}

	/**
	 * Register and enqueue a script for use.
	 *
	 * @uses   wp_enqueue_script()
	 * @access private
	 * @param  string   $handle
	 * @param  string   $path
	 * @param  string[] $deps
	 * @param  string   $version
	 * @param  boolean  $in_footer
	 */
	private static function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = WC_VERSION, $in_footer = true ) {
		if ( ! in_array( $handle, self::$scripts ) && $path ) {
			self::register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	/**
	 * Register a style for use.
	 *
	 * @uses   wp_register_style()
	 * @access private
	 * @param  string   $handle
	 * @param  string   $path
	 * @param  string[] $deps
	 * @param  string   $version
	 * @param  string   $media
	 */
	private static function register_style( $handle, $path, $deps = array(), $version = WC_VERSION, $media = 'all' ) {
		self::$styles[] = $handle;
		wp_register_style( $handle, $path, $deps, $version, $media );
	}

	/**
	 * Register and enqueue a styles for use.
	 *
	 * @uses   wp_enqueue_style()
	 * @access private
	 * @param  string   $handle
	 * @param  string   $path
	 * @param  string[] $deps
	 * @param  string   $version
	 * @param  string   $media
	 */
	private static function enqueue_style( $handle, $path = '', $deps = array(), $version = WC_VERSION, $media = 'all' ) {
		if ( ! in_array( $handle, self::$styles ) && $path ) {
			self::register_style( $handle, $path, $deps, $version, $media );
		}
		wp_enqueue_style( $handle );
	}

	/**
	 * Register/queue frontend scripts.
	 */
	public static function load_scripts() {
		global $post;

		if ( ! did_action( 'before_woocommerce_init' ) || empty($post) ) {
			return;
		}
		
		$cpt = WC_CPT_List::get( $post->post_type );
		
		if ( ! WC_CPT_List::is_active( $cpt ) && ( $post->post_type === 'product' ) ) return;

		$suffix               = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$lightbox_en          = 'yes' === get_option( 'woocommerce_enable_lightbox' );
		$ajax_cart_en         = 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' );
		$assets_path          = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
		$frontend_script_path = $assets_path . 'js/frontend/';

		// Chosen is @deprecated as of 2.3 in favour of 2.3. Here for backwards compatibility.
		self::register_script( 'chosen', $assets_path . 'js/chosen/chosen.jquery' . $suffix . '.js', array( 'jquery' ), '1.0.0' );
		self::register_script( 'select2', $assets_path . 'js/select2/select2' . $suffix . '.js', array( 'jquery' ), '3.5.4' );

		// Register any scripts for later use, or used as dependencies
		self::register_script( 'jquery-blockui', $assets_path . 'js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70' );
		self::register_script( 'jquery-payment', $assets_path . 'js/jquery-payment/jquery.payment' . $suffix . '.js', array( 'jquery' ), '1.2.4' );
		self::register_script( 'jquery-cookie', $assets_path . 'js/jquery-cookie/jquery.cookie' . $suffix . '.js', array( 'jquery' ), '1.4.1' );
		self::register_script( 'wc-credit-card-form', $frontend_script_path . 'credit-card-form' . $suffix . '.js', array( 'jquery', 'jquery-payment' ) );
		self::register_script( 'wc-add-to-cart-variation', $frontend_script_path . 'add-to-cart-variation' . $suffix . '.js', array( 'jquery', 'wp-util' ) );
		self::register_script( 'wc-single-product', $frontend_script_path . 'single-product' . $suffix . '.js' );
		self::register_script( 'wc-country-select', $frontend_script_path . 'country-select' . $suffix . '.js' );
		self::register_script( 'wc-address-i18n', $frontend_script_path . 'address-i18n' . $suffix . '.js' );
		self::register_script( 'wc-password-strength-meter', $frontend_script_path . 'password-strength-meter' . $suffix . '.js', array( 'jquery', 'password-strength-meter' ) );

		// Register frontend scripts conditionally
		if ( $ajax_cart_en ) {
			self::enqueue_script( 'wc-add-to-cart', $frontend_script_path . 'add-to-cart' . $suffix . '.js' );
		}
		
		self::enqueue_script( 'prettyPhoto', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.6', true );
		self::enqueue_script( 'prettyPhoto-init', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery','prettyPhoto' ) );
		self::enqueue_style( 'woocommerce_prettyPhoto_css', $assets_path . 'css/prettyPhoto.css' );
		
		
		self::enqueue_script( 'wc-single-product' );

		// Global frontend scripts
		self::enqueue_script( 'woocommerce', $frontend_script_path . 'woocommerce' . $suffix . '.js', array( 'jquery', 'jquery-blockui' ) );
		self::enqueue_script( 'wc-cart-fragments', $frontend_script_path . 'cart-fragments' . $suffix . '.js', array( 'jquery', 'jquery-cookie' ) );

		// CSS Styles
		if ( $enqueue_styles = self::get_styles() ) {
			foreach ( $enqueue_styles as $handle => $args ) {
				self::enqueue_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
			}
		}
	}

	/**
	 * Localize a WC script once.
	 * @access private
	 * @since  2.3.0 this needs less wp_script_is() calls due to https://core.trac.wordpress.org/ticket/28404 being added in WP 4.0.
	 * @param  string $handle
	 */
	private static function localize_script( $handle ) {
		if ( ! in_array( $handle, self::$wp_localize_scripts ) && wp_script_is( $handle ) && ( $data = self::get_script_data( $handle ) ) ) {
			$name                        = str_replace( '-', '_', $handle ) . '_params';
			self::$wp_localize_scripts[] = $handle;
			wp_localize_script( $handle, $name, apply_filters( $name, $data ) );
		}
	}

	/**
	 * Return data for script handles.
	 * @access private
	 * @param  string $handle
	 * @return array|bool
	 */
	private static function get_script_data( $handle ) {
		global $wp;

		switch ( $handle ) {
			case 'woocommerce' :
				return array(
					'ajax_url'    => WC()->ajax_url(),
					'wc_ajax_url' => WC_AJAX::get_endpoint( "%%endpoint%%" )
				);
			break;
			case 'wc-geolocation' :
				return array(
					'wc_ajax_url'  => WC_AJAX::get_endpoint( "%%endpoint%%" ),
					'home_url'     => home_url(),
					'is_available' => ! ( is_cart() || is_account_page() || is_checkout() || is_customize_preview() ) ? '1' : '0',
					'hash'         => isset( $_GET['v'] ) ? wc_clean( $_GET['v'] ) : ''
				);
			break;
			case 'wc-single-product' :
				return array(
					'i18n_required_rating_text' => esc_attr__( 'Please select a rating', 'woocommerce' ),
					'review_rating_required'    => get_option( 'woocommerce_review_rating_required' ),
				);
			break;
			case 'wc-checkout' :
				return array(
					'ajax_url'                  => WC()->ajax_url(),
					'wc_ajax_url'               => WC_AJAX::get_endpoint( "%%endpoint%%" ),
					'update_order_review_nonce' => wp_create_nonce( 'update-order-review' ),
					'apply_coupon_nonce'        => wp_create_nonce( 'apply-coupon' ),
					'remove_coupon_nonce'       => wp_create_nonce( 'remove-coupon' ),
					'option_guest_checkout'     => get_option( 'woocommerce_enable_guest_checkout' ),
					'checkout_url'              => WC_AJAX::get_endpoint( "checkout" ),
					'is_checkout'               => is_page( wc_get_page_id( 'checkout' ) ) && empty( $wp->query_vars['order-pay'] ) && ! isset( $wp->query_vars['order-received'] ) ? 1 : 0,
					'debug_mode'                => defined('WP_DEBUG') && WP_DEBUG,
					'i18n_checkout_error'       => esc_attr__( 'Error processing checkout. Please try again.', 'woocommerce' ),
				);
			break;
			case 'wc-address-i18n' :
				return array(
					'locale'             => json_encode( WC()->countries->get_country_locale() ),
					'locale_fields'      => json_encode( WC()->countries->get_country_locale_field_selectors() ),
					'i18n_required_text' => esc_attr__( 'required', 'woocommerce' ),
				);
			break;
			case 'wc-cart' :
				return array(
					'ajax_url'                     => WC()->ajax_url(),
					'wc_ajax_url'                  => WC_AJAX::get_endpoint( "%%endpoint%%" ),
					'update_shipping_method_nonce' => wp_create_nonce( "update-shipping-method" ),
				);
			break;
			case 'wc-cart-fragments' :
				return array(
					'ajax_url'      => WC()->ajax_url(),
					'wc_ajax_url'   => WC_AJAX::get_endpoint( "%%endpoint%%" ),
					'fragment_name' => apply_filters( 'woocommerce_cart_fragment_name', 'wc_fragments' )
				);
			break;
			case 'wc-add-to-cart' :
				return array(
					'ajax_url'                => WC()->ajax_url(),
					'wc_ajax_url'             => WC_AJAX::get_endpoint( "%%endpoint%%" ),
					'i18n_view_cart'          => esc_attr__( 'View Cart', 'woocommerce' ),
					'cart_url'                => apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url() ),
					'is_cart'                 => is_cart(),
					'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' )
				);
			break;
			case 'wc-add-to-cart-variation' :
				// We also need the wp.template for this script :)
				wc_get_template( 'single-product/add-to-cart/variation.php' );

				return array(
					'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'woocommerce' ),
					'i18n_make_a_selection_text'       => esc_attr__( 'Select product options before adding this product to your cart.', 'woocommerce' ),
					'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' )
				);
			break;
			case 'wc-country-select' :
				return array(
					'countries'                 => json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) ),
					'i18n_select_state_text'    => esc_attr__( 'Select an option&hellip;', 'woocommerce' ),
					'i18n_matches_1'            => _x( 'One result is available, press enter to select it.', 'enhanced select', 'woocommerce' ),
					'i18n_matches_n'            => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'woocommerce' ),
					'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'woocommerce' ),
					'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'woocommerce' ),
					'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'woocommerce' ),
					'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'woocommerce' ),
					'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'woocommerce' ),
					'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'woocommerce' ),
					'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'woocommerce' ),
					'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'woocommerce' ),
					'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'woocommerce' ),
					'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'woocommerce' ),
				);
			break;
			case 'wc-password-strength-meter' :
				return array(
					'min_password_strength' => apply_filters( 'woocommerce_min_password_strength', 3 ),
					'i18n_password_error'   => esc_attr__( 'Please enter a stronger password.', 'woocommerce' ),
					'i18n_password_hint'    => esc_attr__( 'The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).', 'woocommerce' )
				);
			break;
		}
		return false;
	}

	/**
	 * Localize scripts only when enqueued.
	 */
	public static function localize_printed_scripts() {
		foreach ( self::$scripts as $handle ) {
			self::localize_script( $handle );
		}
	}
}

WCCPT_Frontend_Scripts::init();
