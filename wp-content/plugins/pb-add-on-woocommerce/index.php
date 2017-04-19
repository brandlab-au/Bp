<?php
/**
 * Plugin Name: Profile Builder - WooCommerce Sync Add-on
 * Plugin URI: http://www.cozmoslabs.com/wordpress-profile-builder
 * Description: Syncs Profile Builder with WooCommerce, allowing you to manage the Shipping and Billing fields from WoCommerce with Profile Builder.
 * Version: 1.4.2
 * Author: Cozmoslabs, Adrian Spiac
 * Author URI: http://www.cozmoslabs.com
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPL2
 */
/*  Copyright 2016 Cozmoslabs (www.cozmoslabs.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*
* Define plugin path
*/
define('WPPBWOO_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));


/*
 * Include the file for creating the WooCommerce Sync subpage
 */
if (file_exists(WPPBWOO_PLUGIN_DIR . '/woosync-page.php'))
    include_once(WPPBWOO_PLUGIN_DIR . '/woosync-page.php');


/*
* Check for updates
*
*/
if (file_exists(WPPBWOO_PLUGIN_DIR . '/update/update-checker.php')) {
    include_once(WPPBWOO_PLUGIN_DIR . '/update/update-checker.php');

    //we don't know what version we have installed so we need to check both
    $localSerial = get_option('wppb_profile_builder_pro_serial');
    if( empty( $localSerial ) )
        $localSerial = get_option('wppb_profile_builder_hobbyist_serial');

    $wppb_woo_update = new wppb_PluginUpdateChecker('http://updatemetadata.cozmoslabs.com/?localSerialNumber=' . $localSerial . '&uniqueproduct=CLPBWOO', __FILE__, 'wppb-woo-sync-add-on');
}


// Makes sure the plugin is defined before trying to use it
if ( ! function_exists( 'is_plugin_active_for_network' ) )
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );


//Remove Billing and Shipping fields from the backend fields listing (runs at plugin deactivation -> register_deactivation_hook)
function wppb_remove_woo_billing_shipping_fields()
{
    if (get_option('wppb_manage_fields')) {
        $wppb_manage_fields = get_option('wppb_manage_fields');
        foreach ($wppb_manage_fields as $key => $value) {
            if (($value['field'] == 'WooCommerce Customer Billing Address') || ($value['field'] == 'WooCommerce Customer Shipping Address'))
                unset($wppb_manage_fields[$key]);
        }
        update_option('wppb_manage_fields', array_values($wppb_manage_fields));
    }
}
register_deactivation_hook(__FILE__, 'wppb_remove_woo_billing_shipping_fields');



// Check if WooCommerce is active
if ( ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) || (is_plugin_active_for_network('woocommerce/woocommerce.php')) )  {

    /**
     * Initialize the translation for the Plugin.
     *
     * @since v.1.0
     *
     * @return null
     */
    function wppb_woo_init_translation()
    {
        $current_theme = wp_get_theme();
        if( !empty( $current_theme->stylesheet ) && file_exists( get_theme_root().'/'. $current_theme->stylesheet .'/local_pb_lang' ) )
            load_plugin_textdomain( 'profile-builder-woocommerce-add-on', false, basename( dirname( __FILE__ ) ).'/../../themes/'.$current_theme->stylesheet.'/local_pb_lang' );
        else
            load_plugin_textdomain( 'profile-builder-woocommerce-add-on', false, basename(dirname(__FILE__)) . '/translation/' );
    }

    add_action('init', 'wppb_woo_init_translation', 8);

    /* Allow PB to manage Billing fields from WooCommerce*/
    if (file_exists(WPPBWOO_PLUGIN_DIR . '/billing-fields.php'))
        include_once(WPPBWOO_PLUGIN_DIR . '/billing-fields.php');

    /* Allow PB to manage Shipping fields from WooCommerce*/
    if (file_exists(WPPBWOO_PLUGIN_DIR . '/shipping-fields.php'))
        include_once(WPPBWOO_PLUGIN_DIR . '/shipping-fields.php');

    /* Add support for custom fields created with PB to be displayed on WooCommerce Checkout page  */
    if (file_exists(WPPBWOO_PLUGIN_DIR . '/woo-checkout-field-support.php'))
        include_once(WPPBWOO_PLUGIN_DIR . '/woo-checkout-field-support.php');

    register_activation_hook(__FILE__, 'wppb_prepopulate_woo_billing_shipping_fields');


    //Add Country Select script
    function wppb_woo_country_select_scripts(){
        wp_register_script( 'woo-country-select', plugin_dir_url(__FILE__) . 'assets/js/country-select.js' , array('jquery'));

        if ( class_exists('WC_Countries') ) {
            $WC_Countries_Obj = new WC_Countries();
            $locale = $WC_Countries_Obj->get_country_locale();
        }
        else $locale = array();

        // Localize the script with new data
        $translation_array = array(
            'countries'              => json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) ),
            'i18n_select_state_text' => esc_attr__( 'Select an option&hellip;', 'woocommerce' ),
            'locale'                 => json_encode( $locale )
        );
        wp_localize_script( 'woo-country-select', 'wc_country_select_params', $translation_array );
        wp_enqueue_script( 'woo-country-select' );
    }
    add_action('wp_enqueue_scripts','wppb_woo_country_select_scripts');


    // Function that enqueues the necessary admin scripts
    function wppb_woo_sync_scripts( $hook )
    {
		if ( $hook == 'profile-builder_page_manage-fields' ) {
			wp_enqueue_script( 'wppb-woo-sync', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', array( 'jquery', 'wppb-manage-fields-live-change' ), '1.0', true );
		}
    }
    add_action('admin_enqueue_scripts', 'wppb_woo_sync_scripts');


    //Add Billing and Shipping fields in the backend fields drop-down select
    add_filter('wppb_manage_fields_types', 'wppb_add_woo_billing_shipping_fields');

    // Add Woo Shipping and Billing fields to the unique fields list + skip check for empty meta
    add_filter('wppb_unique_field_list', 'wppb_add_woo_billing_shipping_fields');

    function wppb_add_woo_billing_shipping_fields($fields)
    {
        $fields[] = 'WooCommerce Customer Billing Address';
        $fields[] = 'WooCommerce Customer Shipping Address';
        return $fields;
    }


    //Add Billing and Shipping fields in the backend fields listing, (runs at plugin activation -> register_activation_hook)
    function wppb_prepopulate_woo_billing_shipping_fields()
    {
        if (get_option('wppb_manage_fields')) {
            $wppb_manage_fields = get_option('wppb_manage_fields');

            if (function_exists('wppb_get_unique_id')) {
                //Add Billing fields
                $wppb_manage_fields[] = array('field' => 'WooCommerce Customer Billing Address', 'field-title' => __('Billing Address', 'profile-builder-woocommerce-add-on'), 'meta-name' => 'wppbwoo_billing', 'overwrite-existing' => 'No', 'id' => wppb_get_unique_id(), 'description' => __('Displays customer billing fields in front-end. ', 'profile-builder-woocommerce-add-on'), 'row-count' => '5', 'allowed-image-extensions' => '.*', 'allowed-upload-extensions' => '.*', 'avatar-size' => '100', 'date-format' => 'mm/dd/yy', 'terms-of-agreement' => '', 'options' => '', 'labels' => '', 'public-key' => '', 'private-key' => '', 'default-value' => '', 'default-option' => '', 'default-options' => '', 'default-content' => '', 'required' => 'No');
                update_option('wppb_manage_fields', $wppb_manage_fields);

                //Add Shipping fields
                $wppb_manage_fields[] = array('field' => 'WooCommerce Customer Shipping Address', 'field-title' => __('Shipping Address', 'profile-builder-woocommerce-add-on'), 'meta-name' => 'wppbwoo_shipping', 'overwrite-existing' => 'No', 'id' => wppb_get_unique_id(), 'description' => __('Displays customer shipping fields in front-end. ', 'profile-builder-woocommerce-add-on'), 'row-count' => '5', 'allowed-image-extensions' => '.*', 'allowed-upload-extensions' => '.*', 'avatar-size' => '100', 'date-format' => 'mm/dd/yy', 'terms-of-agreement' => '', 'options' => '', 'labels' => '', 'public-key' => '', 'private-key' => '', 'default-value' => '', 'default-option' => '', 'default-options' => '', 'default-content' => '', 'required' => 'No');
                update_option('wppb_manage_fields', $wppb_manage_fields);
            }
        }
    }


    /* Function that returns the fields array with their new names (the ones inserted by the user ) */
    function wppb_woo_get_fields_edited_names( $field_name ){

        $field = wppb_woo_get_field( $field_name );

        //get default field names array
        switch ( $field_name ) {

            case 'WooCommerce Customer Billing Address':
                $fields_array = wppb_woo_get_billing_fields();
                $meta = 'woo-billing-fields-name';
                break;

            case 'WooCommerce Customer Shipping Address':
                $fields_array = wppb_woo_get_shipping_fields();
                $meta = 'woo-shipping-fields-name';
                break;

            default:
                $fields_array = array();
        }

        if ( !empty($fields_array) && !empty($field) && !empty($field[$meta]) ) {

            // get individual field names edited by the user in the UI and put them into an associative array
            $fields_name_array = json_decode($field[$meta], true);

            if ( !empty($fields_name_array) ) {

                foreach ($fields_name_array as $key => $value) {
                    $fields_array[$key]['label'] = $value;
                }

            }

        }

        return $fields_array;

    }

    /* Function that returns the field value for a given field_name ; empty if not found */
    function wppb_woo_get_field ( $field_name ){
        $manage_fields = get_option('wppb_manage_fields', 'not_found');

        if ($manage_fields != 'not_found') {

            foreach ($manage_fields as $field_key => $field_value) {

                if ( ($field_value['field'] == $field_name) )
                    return $field_value;
            }
        }

        return ''; // return empty if not found
    }



    // Add support for selecting which individual WooCommerce Shipping and Billing fields to display
    add_filter('wppb_manage_fields', 'wppb_woo_add_support_individual_billing_shipping_fields');
    function wppb_woo_add_support_individual_billing_shipping_fields( $fields ){

        $billing_fields_array = wppb_woo_get_fields_edited_names('WooCommerce Customer Billing Address');
        $default_billing_fields = array();

        $billing_fields = array();
        foreach( $billing_fields_array as $key => $value ) {
                if ( ($key == 'billing_address_2') && ($value['label'] == '') ) $value['label'] = __('Address line 2','profile-builder-woocommerce-add-on');
                array_push( $billing_fields, '%' . $value['label'] . '%' . $key );
                array_push( $billing_fields, '%' . '' . '%' . 'required_' . $key );
                // set default billing fields and corresponding 'required' values
                array_push( $default_billing_fields, $key);
                if ($value['required'] == 'Yes') array_push( $default_billing_fields, 'required_' . $key );
        }

        $fields[] = array( 'type' => 'woocheckbox', 'slug' => 'woo-billing-fields', 'title' => __( 'Billing Fields', 'profile-builder' ), 'options' => $billing_fields, 'default-options' => $default_billing_fields, 'description' => __( "Select which WooCommerce Billing fields to display to the user ( drag and drop to re-order ) and which should be required", 'profile-builder' ) );
        $fields[] = array( 'type' => 'text', 'slug' => 'woo-billing-fields-sort-order', 'title' => __( 'Billing Fields Order', 'profile-builder' ), 'description' => __( "Save the billing fields order from the billing fields checkboxes", 'profile-builder' ) );
        $fields[] = array( 'type' => 'text', 'slug' => 'woo-billing-fields-name', 'title' => __( 'Billing Fields Name', 'profile-builder' ), 'description' => __( "Save the billing fields names", 'profile-builder' ) );


        $shipping_fields_array = wppb_woo_get_fields_edited_names('WooCommerce Customer Shipping Address');
        $default_shipping_fields = array();


        $shipping_fields = array();
        foreach( $shipping_fields_array as $key => $value ) {
            if ($key == 'shipping_address_2') $value['label'] = __('Address line 2','profile-builder-woocommerce-add-on');
            array_push( $shipping_fields, '%' . $value['label'] . '%' . $key );
            array_push( $shipping_fields, '%' . '' . '%' . 'required_' . $key );
            // set default shipping fields and corresponding 'required' values
            array_push( $default_shipping_fields, $key);
            if ($value['required'] == 'Yes') array_push( $default_shipping_fields, 'required_' . $key );
        }

        $fields[] = array( 'type' => 'woocheckbox', 'slug' => 'woo-shipping-fields', 'title' => __( 'Shipping Fields', 'profile-builder' ), 'options' => $shipping_fields, 'default-options' => $default_shipping_fields, 'description' => __( "Select which WooCommerce Shipping fields to display to the user ( drag and drop to re-order ) and which should be required", 'profile-builder' ) );
        $fields[] = array( 'type' => 'text', 'slug' => 'woo-shipping-fields-sort-order', 'title' => __( 'Shipping Fields Order', 'profile-builder' ), 'description' => __( "Save the shipping fields order from the billing fields checkboxes", 'profile-builder' ) );
        $fields[] = array( 'type' => 'text', 'slug' => 'woo-shipping-fields-name', 'title' => __( 'Shipping Fields Name', 'profile-builder' ), 'description' => __( "Save the shipping fields names", 'profile-builder' ) );

        return $fields;
    }

    /**
     * Function that calls the wppb_edit_form_properties (to initialize field sorting on edit)
     */
    function wppb_woo_initialize_sorting_on_edit( $meta_name, $id, $element_id ){

        if ( $meta_name == 'wppb_manage_fields' ) {
            echo "<script type=\"text/javascript\">wppb_handle_woosync_billing_shipping_field ( '#container_wppb_manage_fields', 'billing' );</script>";
            echo "<script type=\"text/javascript\">wppb_handle_woosync_billing_shipping_field ( '#container_wppb_manage_fields', 'shipping' );</script>";
        }
    }
    add_action('wck_after_adding_form', 'wppb_woo_initialize_sorting_on_edit', 20, 3);


    // Returns the html needed for displaying the custom 'woocheckbox' field type
    add_filter('wck_output_form_field_customtype_woocheckbox', 'wppb_woo_woocheckbox_form_field', 10, 4);
    function wppb_woo_woocheckbox_form_field($element, $value, $details, $single_prefix){

        if( !empty( $details['options'] ) ){

            // we need these to set placeholders for field name inputs based on the default names
            $billing_array = wppb_woo_get_billing_fields();
            $shipping_array = wppb_woo_get_shipping_fields();

            $element .= '<span class="wck-woocheckboxes-heading">'.__('Field Name', 'profile-builder-woocommerce-add-on').'</span>';
            $element .= '<span class="wck-woocheckboxes-heading">'.__('Required', 'profile-builder-woocommerce-add-on').'</span>';

            $element .= '<div class="wck-checkboxes wck-woocheckboxes">';

            $count = 0; // used to add each 2 consecutive checkboxes in a single div

            foreach( $details['options'] as $option ){
                $found = false;
                $count++;

                // if there aren't any previously saved values, use the default options
                if ( ( empty($value) ) && ( !empty($details['default-options']) ) )
                    $value = $details['default-options'];

                if( !is_array( $value ) )
                    $values = explode( ', ', $value );
                else
                    $values = $value;

                if( strpos( $option, '%' ) === false  ){
                    $label = $option;
                    $value_attr = $option;
                    if ( in_array( $option, $values ) )
                        $found = true;
                }
                else{
                    $option_parts = explode( '%', $option );
                    if( !empty( $option_parts ) ){
                        if( empty( $option_parts[0] ) && count( $option_parts ) == 3 ){
                            $label = $option_parts[1];
                            $value_attr = $option_parts[2];
                            if ( in_array( $option_parts[2], $values ) )
                                $found = true;
                        }
                    }
                }

                if ( ($count % 2 ) != 0 ) $element .= '<div>';

                $element .= '<label><input type="checkbox" name="'. $single_prefix . esc_attr( Wordpress_Creation_Kit_PB::wck_generate_slug( $details['title'], $details ) );

                $element .= '" id="';

                /* since the slug below is generated from the value as well we need to determine here if we have a slug or not and not let the wck_generate_slug() function do that */
                if( !empty( $details['slug'] ) )
                    $slug_from = $details['slug'];
                else
                    $slug_from = $details['title'];

                $element .= esc_attr( Wordpress_Creation_Kit_PB::wck_generate_slug( $slug_from . '_' . $value_attr ) ) .'" value="'. esc_attr( $value_attr ) .'"  '. checked( $found, true, false ) .'class="mb-checkbox mb-field" />';

                // Add the placeholder input only for Field Name, do not add it for Required checkbox
                if ( ($count % 2) != 0 ) {

                    // set value for the placeholder -> default field name
                    $placeholder = '';

                    if ( array_key_exists($value_attr , $billing_array ) )
                        $placeholder = ( $billing_array[$value_attr]['label'] != '') ? $billing_array[$value_attr]['label'] : __('Address line 2','profile-builder-woocommerce-add-on');

                    elseif ( array_key_exists($value_attr , $shipping_array ) )
                        $placeholder = ( $shipping_array[$value_attr]['label'] != '') ? $shipping_array[$value_attr]['label'] : __('Address line 2','profile-builder-woocommerce-add-on');

                    $element .= '<input type="text" value="'. esc_html( $label ) .'" placeholder="'. esc_html( $placeholder ) .'" title="'.__('Click to edit ', 'profile-builder-woocommerce-add-on'). esc_html( $placeholder ) .'" class="wck-woocheckbox-field-label" />';
                    $element .= '<span class="dashicons dashicons-edit"></span>';
                }

                $element .= '</label>';

                if ( ($count % 2) == 0 ) $element .= '</div>';
            }
            $element .= '</div>';
        }

        return $element;
    }




    // Handle field validation for each individual Billing and Shipping field
    function wppb_check_woo_individual_fields_val( $message, $field_val, $field_key, $request_data, $form_location ){
        if ( ($field_val['required'] == 'Yes') &&  isset( $request_data[$field_key] ) && ( trim( $request_data[$field_key] ) == '' )   ) {
            return '<span class="wppb-form-error">'.wppb_required_field_error($field_key).'</span>';
        }
        //For Billing Email field check if it's a valid email
        if ( ($field_key == 'billing_email') && ($field_val['required'] == 'Yes') && isset( $request_data[$field_key]) && !is_email( trim( $request_data['billing_email'] ) ) ) {
            return '<span class="wppb-form-error">'.__('The email you entered is not a valid email address.', 'profile-builder-woocommerce-add-on').'</span>';
        }
        return $message;
    }


    // Add extra styling for WooCommerce form fields
    function wppb_woo_add_plugin_stylesheet($hook) {

        if  ( file_exists( plugin_dir_path(__FILE__).'/assets/css/style-fields.css' ) )  {
            // Add style only on the Manage Fields page in backend
            if ( (!empty($hook)) && ($hook == 'profile-builder_page_manage-fields') ) {
                wp_register_style( 'wppb_woo_stylesheet', plugin_dir_url(__FILE__) . 'assets/css/style-fields.css');
                wp_enqueue_style( 'wppb_woo_stylesheet' );
            }
        }

    }
    add_action('admin_enqueue_scripts' , 'wppb_woo_add_plugin_stylesheet');


	// Add style to front-end
	function wppb_woo_add_plugin_stylesheet_front_end() {
		wp_register_style( 'wppb_woo_stylesheet', plugin_dir_url(__FILE__) . 'assets/css/style-fields.css', array('wppb_stylesheet'));
		wp_enqueue_style( 'wppb_woo_stylesheet' );
	}
	add_action('wp_enqueue_scripts' , 'wppb_woo_add_plugin_stylesheet_front_end');


    //Remove Woo Billing and Shipping fields from UserListing & Email Confirmation merge tags (available Meta and Sort Variables list)
    function wppb_woo_remove_shipping_billing_from_userlisting_ec($manage_fields){
        foreach ($manage_fields as $key => $value){
            if (($value['field'] == 'WooCommerce Customer Billing Address') || ($value['field'] == 'WooCommerce Customer Shipping Address')) unset($manage_fields[$key]);
        }
        return array_values($manage_fields);
    }
    add_filter('wppb_userlisting_merge_tags', 'wppb_woo_remove_shipping_billing_from_userlisting_ec');
    add_filter('wppb_email_customizer_get_fields', 'wppb_woo_remove_shipping_billing_from_userlisting_ec');


    /* Add the Woo Shipping Address & Billing Address merge tags we need in Email Customizer */
    add_filter( 'wppb_email_customizer_get_merge_tags', 'wppb_woo_add_tags_in_ec' );
    function wppb_woo_add_tags_in_ec( $merge_tags ){
        /* unescaped because they might contain html */
        $merge_tags[] = array( 'name' => 'wppbwoo_billing', 'type' => 'wppbwoo_billing', 'unescaped' => true, 'label' => __( 'Billing Address', 'profile-builder-woocommerce-add-on' ) );
        $merge_tags[] = array( 'name' => 'wppbwoo_shipping', 'type' => 'wppbwoo_shipping', 'unescaped' => true, 'label' => __( 'Shipping Address', 'profile-builder-woocommerce-add-on' ) );
        return $merge_tags;
    }


    /* Display content in Email Customizer for WooCommerce Billing merge tag */
    add_filter( 'mustache_variable_wppbwoo_billing', 'wppb_woo_handle_merge_tag_wppbwoo_billing', 10, 4 );
    function wppb_woo_handle_merge_tag_wppbwoo_billing( $value, $name, $children, $extra_values){

        $user_id = ( !empty( $extra_values['user_id'] ) ? $extra_values['user_id'] : get_query_var( 'username' ) );
        $billing_fields = wppb_woo_billing_fields_array();

        if( !empty($user_id) && !empty($billing_fields) ) {
           return wppb_woo_ec_output_wppbwoo_billing_shipping($user_id, $billing_fields);
        }
    }

    /* Display content in Email Customizer for WooCommerce Shipping merge tag */
    add_filter( 'mustache_variable_wppbwoo_shipping', 'wppb_woo_handle_merge_tag_wppbwoo_shipping', 10, 4 );
    function wppb_woo_handle_merge_tag_wppbwoo_shipping( $value, $name, $children, $extra_values){

        $user_id = ( !empty( $extra_values['user_id'] ) ? $extra_values['user_id'] : get_query_var( 'username' ) );
        $shipping_fields = wppb_woo_shipping_fields_array();

        if( !empty($user_id) && !empty($shipping_fields) ) {
            return wppb_woo_ec_output_wppbwoo_billing_shipping($user_id, $shipping_fields);
        }
    }

    /* Output Billing and Shipping fields content in Email Customizer*/
    function wppb_woo_ec_output_wppbwoo_billing_shipping($user_id, $fields){

            $user_meta = get_user_meta($user_id);

            // Used to get country name based on country code
            if (class_exists('WC_Countries')) {
                $WC_Countries_Obj = new WC_Countries();
                $country_array = $WC_Countries_Obj->get_allowed_countries();
                $states_array =  $WC_Countries_Obj -> get_allowed_country_states();
            }

            $output = '<table>';
            foreach ($fields as $field_key => $field_val) {

                // display only address fields which aren't empty
                if (!empty($user_meta[$field_key][0])) {
                    $country_code = $user_meta[$field_key][0];

                    if  ( ( ($field_key == 'billing_country') || ($field_key == 'shipping_country') ) && (class_exists('WC_Countries')) ) {
                        $output .= '<tr><td>' . $field_val['label'] . ' : ' . $country_array[$country_code] . '</td></tr>';
                    }
                    elseif ( ( ($field_key == 'billing_state') || ($field_key == 'shipping_state') ) && (class_exists('WC_Countries'))  && !empty($states_array[$country_code][$user_meta[$field_key][0]]) ) {
                            $output .= '<tr><td>' . $field_val['label'] . ' : ' . $states_array[$country_code][$user_meta[$field_key][0]] . '</td></tr>';
                    }
                    else {
                        if ( ($field_key == 'billing_address_2') || ($field_key == 'shipping_address_2') ) $field_val['label'] = __('Address line 2', 'profile-builder-woocommerce-add-on');
                        $output .= '<tr><td>' . $field_val['label'] . ' : ' . $user_meta[$field_key][0] . '</td></tr>';
                    }
                }
            }
            $output .= '</table>';

            return $output;
    }


    /*
     * Replace WooCommerce MyAccount "form-login.php" and "edit account" templates with "myaccount-login-register.php" and "myaccount-edit-profile.php" templates added by our WooSync add-on
     */
    function wppb_woo_replace_myaccount_login_register_edit_account_templates($located, $template_name, $args='', $template_path='', $default_path=''){
        $wppb_woosync_settings = get_option( 'wppb_woosync_settings');

        // make sure Profile Builder is active
        if ( class_exists('Wordpress_Creation_Kit_PB') ) {

            if (!empty($wppb_woosync_settings['RegisterForm'])) {

                if (($template_name == 'myaccount/form-login.php') && (!is_user_logged_in())) {
                    $located = WPPBWOO_PLUGIN_DIR . '/templates/myaccount-login-register.php';
                }
            }

            if (!empty($wppb_woosync_settings['EditProfileForm'])) {

                if (($template_name == 'myaccount/form-edit-account.php') && (is_user_logged_in())) {
                    $located = WPPBWOO_PLUGIN_DIR . '/templates/myaccount-edit-profile.php';
                }
            }
        }

        return $located;
    }
    add_filter('wc_get_template','wppb_woo_replace_myaccount_login_register_edit_account_templates', 10, 5);


}
else {
    /*
     * Display notice if WooCommerce is not active
     */
    function wppb_woo_admin_notice() {
        ?>
        <div class="update-nag">
            <?php _e( 'WooCommerce needs to be installed and activated for Profile Builder - WooCommerce Sync Add-on to work!', 'profile-builder-woocommerce-add-on' ); ?>
        </div>
    <?php
    }
    add_action( 'admin_notices', 'wppb_woo_admin_notice' );
}