<?php
/**
 * Add support for custom fields created with Profile Builder to be displayed on WooCommerce Checkout page
 *
 */

/*
 *  Add selected Profile Builder custom fields to WooCommerce Checkout page
 */
function wppb_woo_add_checkout_fields($checkout_fields){
    $wppb_manage_fields = get_option('wppb_manage_fields', 'not_found');

    if ($wppb_manage_fields != 'not_found'){

        foreach ($wppb_manage_fields as $field) {

            if ( isset($field['woocommerce-checkout-field']) && ($field['woocommerce-checkout-field'] == 'Yes') ) {

                switch ($field['field']) {

                    case 'Checkbox':
                        $field['field'] = 'checkbox-pb'; // we need to change this since Woo has a checkout field type, but we want to add our own html output using their filter
                        $field['default-value'] = $field['default-options'];
                        break;

                    case 'Textarea' :
                        $field['default-value'] = $field['default-content'];
                        break;

                    case 'Default - Biographical Info' :
                        $field['default-value'] = $field['default-content'];
                        $field['field'] = 'textarea';
                        break;

                    case 'Select':
                    case 'Radio':
                        $field['default-value'] = $field['default-option'];
                        break;

                    case 'MailChimp Subscribe':
                        $field['meta-name'] = 'custom_field_mailchimp_subscribe_' . $field['id'];
                        $field['field'] = 'wppb_mailchimp';
                        break;

                }

                if ( function_exists('wppb_handle_meta_name') ) {
                    $checkout_fields['account'][wppb_handle_meta_name($field['meta-name'])] = array(
                        'type' 			    => ( ($field['field'] == 'Input') || ($field['field'] == 'Phone') || ($field['field'] == 'Number') || ($field['field'] == 'Default - First Name') || ($field['field']== 'Default - Last Name') || ($field['field']== 'Default - Nickname') ) ? 'text' : strtolower($field['field']),
                        'label' 		    => $field['field-title'],
                        'description'       => $field['description'],
                        'required'          => ($field['required'] == 'Yes' ? true : false),
                        'id'                => $field['id'],
                        'options'           => ( (count(explode(',', $field['options']))) == (count(explode(',', $field['labels']))) ) ? array_combine( array_map('trim', explode(',', $field['options'])) , array_map('trim', explode(',', $field['labels'])) ) : array_combine( array_map('trim', explode(',', $field['options'])) , array_map('trim', explode(',', $field['options'])) ),
                        'default'           => $field['default-value'],
                        'date-format'       => isset($field['date-format']) ? $field['date-format'] : '',
                        'mailchimp-list'    => !empty( $field['mailchimp-lists'] ) ? $field['mailchimp-lists'] : '',
                        'mailchimp-checked' => !empty( $field['mailchimp-default-checked'] ) ? true : false
                    );
                }
            }
        }

    }

    return $checkout_fields;
}
add_filter('woocommerce_checkout_fields','wppb_woo_add_checkout_fields');


/*
 * Save custom fields information added on WooCommerce Checkout page
 */
function wppb_woo_save_checkout_extra_fields( $user_id, $request_data ){
    $wppb_manage_fields = get_option( 'wppb_manage_fields', 'not_found' );

    if( $wppb_manage_fields != 'not_found' ) {
        foreach ($wppb_manage_fields as $field){
            if ( isset($request_data[wppb_handle_meta_name($field['meta-name'])]) ) {
                if( $field['field'] == 'Checkbox' ){
                    $checkbox_values = implode(',', $request_data[wppb_handle_meta_name($field['meta-name'])]);
                    update_user_meta( $user_id, $field['meta-name'], $checkbox_values );
                }
                else update_user_meta($user_id, $field['meta-name'], $request_data[wppb_handle_meta_name($field['meta-name'])]);
            }
        }
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'wppb_woo_save_checkout_extra_fields', 10, 2 );


/*
 * Add "WooCommerce Checkout Field" checkbox to the field properties in Manage Fields page
 */
function wppb_woo_checkout_field_to_manage_fields( $fields ) {
    $woo_checkout_manage_field = array( 'type' => 'select', 'slug' => 'woocommerce-checkout-field', 'title' => __( 'Display on WooCommerce Checkout', 'profile-builder-woocommerce-add-on' ), 'options' => array( 'No', 'Yes' ), 'default' => 'No', 'description' => __( 'Whether the field should be added to the WooCommerce checkout form or not', 'profile-builder-woocommerce-add-on' ) );
    array_push( $fields, $woo_checkout_manage_field );
    return $fields;
}
add_filter( 'wppb_manage_fields', 'wppb_woo_checkout_field_to_manage_fields');


/*
 * Add Heading field support for WooCommerce checkout
 */
function wppb_woo_checkout_field_heading( $output, $key, $args, $value){
    $output = '<h4>'. $args['label'] . '</h4>' . '<span class="description">' .esc_attr($args['description']). '</span>';
    return $output;
}
add_filter('woocommerce_form_field_heading','wppb_woo_checkout_field_heading', 10, 4);


/*
* Add Checkbox field support for WooCommerce checkout
*/
function wppb_woo_checkout_field_checkbox( $output, $key, $args, $value){
    //check if the field is required or not
    if ( $args['required'] ) {
        $args['class'][] = 'validate-required';
        $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
    } else {
        $required = '';
    }

    $output = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

    if ( ! empty( $args['options'] ) ) {
        if ( $args['label'] ) {
            $output .= '<label for="' . esc_attr( current( array_keys( $args['options'] ) ) ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required  . '</label>';
        }

        foreach ( $args['options'] as $option_key => $option_text ) {
            $output .= '<input type="checkbox" class="input-checkbox" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ). '[]' . '" id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"';
            if ( in_array( trim( $option_key ), array_map('trim', explode(',', $args['default'] )) ))
                $output .= ' checked="checked"';
            $output .= '/><label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="checkbox ' . implode( ' ', $args['label_class'] ) .'">' . $option_text . '</label>';
        }
    }

    $output .= '</p>';
    return $output;
}
add_filter('woocommerce_form_field_checkbox-pb','wppb_woo_checkout_field_checkbox', 10, 4);


/*
*  Add Datepicker field support for WooCommerce checkout
*/
function wppb_woo_checkout_field_datepicker( $output, $key, $args, $value){

    // add necessary scripts
    wp_enqueue_style( 'profile-builder-datepicker-ui-lightness', WPPB_PLUGIN_URL.'front-end/extra-fields/datepicker/ui-lightness/jquery-ui-1.8.14.custom.css', false, PROFILE_BUILDER_VERSION );
    wp_enqueue_script( 'wppb-datepicker-script', WPPB_PLUGIN_URL.'front-end/extra-fields/datepicker/script-datepicker.js', array(), PROFILE_BUILDER_VERSION, true );
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_style( 'wppb-datepicker-style', WPPB_PLUGIN_URL . 'front-end/extra-fields/datepicker/datepicker-style.css', array(), PROFILE_BUILDER_VERSION );

    $label_id = $args['id'];
    $output = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

    //check if the field is required or not
    if ( $args['required'] ) {
        $args['class'][] = 'validate-required';
        $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
    } else {
        $required = '';
    }

    if ( $args['label'] ) {
        $output .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
    }

    $output .= '<input type="text" class="input-text custom_field_datepicker' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" '.$args['maxlength'].' value="' . esc_attr( $value ) . '" data-dateformat="'. $args['date-format'] .'" />';

    if ( $args['description'] ) {
        $output .= '<span class="description">' . esc_html( $args['description'] ) . '</span>';
    }

    $output .= '</p>';
    return $output;
}
add_filter('woocommerce_form_field_datepicker','wppb_woo_checkout_field_datepicker', 10, 4);


/*
 * Add Upload and Avatar field support for WooCommerce checkout
 */
function wppb_woo_checkout_field_upload_avatar( $output, $key, $args, $value){

    /* media upload add here, this should be added just once even if called multiple times */
    wp_enqueue_media();

    if ( !wp_script_is('wppb-upload-script', 'enqueued') )
        wp_enqueue_script( 'wppb-upload-script', WPPB_PLUGIN_URL.'front-end/extra-fields/upload/upload.js', array('jquery'), PROFILE_BUILDER_VERSION, true );
    if ( !wp_style_is('profile-builder-upload-css', 'enqueued') )
        wp_enqueue_style( 'profile-builder-upload-css', WPPB_PLUGIN_URL.'front-end/extra-fields/upload/upload.css', false, PROFILE_BUILDER_VERSION );

    $label_id = $args['id'];
    $output = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

    //check if the field is required or not
    if ( $args['required'] ) {
        $args['class'][] = 'validate-required';
        $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
    } else {
        $required = '';
    }

    if ( $args['label'] ) {
        $output .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
    }

    $input_value = !empty( $_POST[$key] ) ? $_POST[$key] : '';
    $field = wppb_get_field_by_id_or_meta( $args['id']);
    $output .= wppb_woo_make_upload_button( $field, $input_value );

    if ( $args['description'] ) {
        $output .= '<span class="description">' . esc_html( $args['description'] ) . '</span>';
    }

    $output .= '</p>';

    return $output;

}
add_filter('woocommerce_form_field_upload', 'wppb_woo_checkout_field_upload_avatar', 10, 4);
add_filter('woocommerce_form_field_avatar', 'wppb_woo_checkout_field_upload_avatar', 10, 4);


function wppb_woo_make_upload_button( $field, $input_value ){
    $upload_button = '';
    $upload_input_id = str_replace( '-', '_', Wordpress_Creation_Kit_PB::wck_generate_slug( $field['meta-name'] ) );

    /* container for the image preview (or file ico) and name and file type */
    if( !empty( $input_value ) ){
        /* it can hold multiple attachments separated by comma */
        $values = explode( ',', $input_value );
        foreach( $values as $value ) {
            if( !empty( $value ) ){
                $thumbnail = wp_get_attachment_image($value, array(80, 80), true);
                $file_name = get_the_title($value);
                $file_type = get_post_mime_type($value);
                $attachment_url = wp_get_attachment_url($value);
                $upload_button .= '<div id="' . esc_attr($upload_input_id) . '_info_container" class="upload-field-details" data-attachment_id="' . $value . '">';
                $upload_button .= '<div class="file-thumb">';
                $upload_button .= "<a href='{$attachment_url}' target='_blank' class='wppb-attachment-link'>" . $thumbnail . "</a>";
                $upload_button .= '</div>';
                $upload_button .= '<p><span class="file-name">';
                $upload_button .= $file_name;
                $upload_button .= '</span><span class="file-type">';
                $upload_button .= $file_type;
                $upload_button .= '</span>';
                $upload_button .= '<span class="wppb-remove-upload">' . __('Remove', 'profile-builder') . '</span>';
                $upload_button .= '</p></div>';
            }
        }
        $hide_upload_button = 'style="display:none;"';
    }
    else{
        $hide_upload_button = '';
    }

    $upload_button .= '<a href="#" class="button wppb_upload_button" id="upload_' . esc_attr(Wordpress_Creation_Kit_PB::wck_generate_slug($field['meta-name'], $field)) . '_button" '.$hide_upload_button.' data-uploader_title="' . $field["field-title"] . '" data-uploader_button_text="'. __( 'Select File', 'profile-builder' ) .'" data-upload_mn="'. $field['meta-name'] .'" data-upload_input="' . esc_attr($upload_input_id) . '"';

    if (is_user_logged_in())
        $upload_button .= 'data-uploader_logged_in="true"';
    $upload_button .= ' data-multiple_upload="false"';

    $upload_button .= '>' . __('Upload ', 'profile-builder') . '</a>';


    $upload_button .= '<input id="'. esc_attr( $upload_input_id ) .'" type="hidden" size="36" name="'. esc_attr( Wordpress_Creation_Kit_PB::wck_generate_slug( $field['meta-name'], $field ) ) .'" value="'. $input_value .'"/>';

    return $upload_button;
}


/*
 * Add MailChimp field support for WooCommerce checkout
 *
 */
function wppb_woo_checkout_field_mailchimp( $output, $key, $args, $value ) {

    $checked = ( !empty( $value ) ? 'checked' : ( $args['mailchimp-checked'] ? 'checked' : '' ) );

    $output = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '_field">';

    // The label and checkbox
    $output .= '<label for="custom_field_mailchimp_subscribe_' . $args['id'] . '">';

    $output .= '<input name="custom_field_mailchimp_subscribe_' . $args['id'] . '" id="custom_field_mailchimp_subscribe_' . $args['id'] . '" class="extra_field_mailchimp" type="checkbox" value="' . $args['mailchimp-list'] . '" ' . $checked . ' />';

    $output .= $args['label'] . '</label>';

    // Add description
    if ( $args['description'] ) {
        $output .= '<span class="description">' . esc_html( $args['description'] ) . '</span>';
    }

    $output .= '</p>';

    return $output;

}
add_filter('woocommerce_form_field_wppb_mailchimp','wppb_woo_checkout_field_mailchimp', 10, 4);


/*
 * Subscribe the user to the MailChimp newsletter
 *
 */
function wppb_woo_mailchimp_subscribe( $user_id, $request_data ) {

    // Make sure the MailChimp add-on is present
    if( !class_exists( 'WPPB_MailChimp' ) || !function_exists( 'wppb_mci_api_subscribe_get_args' ) || !function_exists( 'wppb_mci_api_subscribe' ) )
        return;

    // Get the API key and continue only if it is valid
    $wppb_mci_settings          = get_option('wppb_mci_settings');
    $wppb_mci_api_key_validated = get_option('wppb_mailchimp_api_key_validated', false);

    if( isset( $wppb_mci_settings['api_key'] ) && !empty( $wppb_mci_settings['api_key'] ) && $wppb_mci_api_key_validated != false ) {

        // Connect to the API
        $api = new WPPB_MailChimp( $wppb_mci_settings['api_key'] );

        // Find the MailChimp posted values
        foreach( $request_data as $key => $value ) {
            if( strpos( $key, 'custom_field_mailchimp_subscribe_' ) !== false ) {

                $list_id = sanitize_text_field( $request_data[$key] );

                // Get the args
                $args = wppb_mci_api_subscribe_get_args( $request_data, $list_id, $user_id, 'register' );

                // Change the e-mail to the one from WooCommerce
                $args['email']['email'] = ( !empty( $request_data['billing_email'] ) ? $request_data['billing_email'] : '' );

                // Subscribe the user
                wppb_mci_api_subscribe( $wppb_mci_settings['api_key'], apply_filters( 'wppb_woo_mailchimp_subscribe_args', $args, $request_data, $user_id ) );

            }
        }

    }

}
add_action( 'woocommerce_checkout_update_user_meta', 'wppb_woo_mailchimp_subscribe', 10, 2 );


/*
 * Validation for PB Phone & Number fields on the WooCommerce checkout page ( display errors in case the entered value is invalid )
 *
 */
function wppb_woo_checkout_display_pb_field_errors(){

    $manage_fields = get_option('wppb_manage_fields', 'not_found');

    if ($manage_fields != 'not_found') {

        foreach ($manage_fields as $field_key => $field_value) {

            // Phone field validation
            if ( ($field_value['field'] == 'Phone') && ($field_value['woocommerce-checkout-field'] == 'Yes') && !empty($field_value['phone-format']) ) {

                // Make sure phone number doesn't contain characters
                if ( ( 0 < strlen( trim( preg_replace( '/[\s\#0-9_\-\+\(\)]/', '', $_POST[$field_value['meta-name']] ) ) ) ) && ( function_exists('wc_add_notice') ) ) {

                     wc_add_notice('<strong>' . $field_value['field-title'] . '</strong> ' . __('is not a valid phone number.', 'woocommerce'), 'error');
                }

            }

            // Number field validation
            if ( ($field_value['field'] == 'Number') && ($field_value['woocommerce-checkout-field'] == 'Yes') )  {

                if ( !empty($_POST[$field_value['meta-name']]) && !is_numeric($_POST[$field_value['meta-name']]) && ( function_exists('wc_add_notice') ) ) {
                    wc_add_notice('<strong>' . $field_value['field-title'] . '</strong> ' . __('is not a number.', 'profile-builder-woocommerce-add-on'), 'error');
                }

                if ( !empty($_POST[$field_value['meta-name']]) && !empty($field_value['number-step-value']) &&
                    ( sprintf( round( $_POST[$field_value['meta-name']] / $field_value['number-step-value'] ) ) != sprintf( $_POST[$field_value['meta-name']] / $field_value['number-step-value'] ) ) ) {
                    wc_add_notice('<strong>' . $field_value['field-title'] . '</strong> ' . __('must be a multiplier of ', 'profile-builder-woocommerce-add-on') . $field_value['number-step-value'], 'error');
                }

                if ( (!empty($_POST[$field_value['meta-name']]) || $_POST[$field_value['meta-name']] == '0') && (!empty($field_value['min-number-value']) || $field_value['min-number-value'] == '0')
                    && ($_POST[$field_value['meta-name']] < $field_value['min-number-value']) ){
                    wc_add_notice('<strong>' . $field_value['field-title'] . '</strong> ' . __('must be a greater than or equal to ', 'profile-builder-woocommerce-add-on') . $field_value['min-number-value'], 'error');
                }

                if ( (!empty($_POST[$field_value['meta-name']]) || $_POST[$field_value['meta-name']] == '0') && (!empty($field_value['max-number-value']) || $field_value['max-number-value'] == '0')
                    && ($_POST[$field_value['meta-name']] > $field_value['max-number-value']) ) {
                    wc_add_notice('<strong>' . $field_value['field-title'] . '</strong> ' . __('must be less than or equal to ', 'profile-builder-woocommerce-add-on') . $field_value['max-number-value'], 'error');
                }

            }

        }
    }
}
add_action('woocommerce_checkout_process', 'wppb_woo_checkout_display_pb_field_errors');