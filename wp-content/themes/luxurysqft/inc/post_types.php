<?php
function add_post_type() {
	$labels = array(
		'name'               => _x( 'Properties', 'post type general name', 'luxurysqft' ),
		'singular_name'      => _x( 'Property', 'post type singular name', 'luxurysqft' ),
		'add_new'            => _x( 'Add New', 'apartment', 'luxurysqft' ),
		'add_new_item'       => __( 'Add New Property', 'luxurysqft' ),
		'edit_item'          => __( 'Edit Property', 'luxurysqft' ),
		'new_item'           => __( 'New Property', 'luxurysqft' ),
		'all_items'          => __( 'All Properties', 'luxurysqft' ),
		'view_item'          => __( 'View Property', 'luxurysqft' ),
		'search_items'       => __( 'Search Properties', 'luxurysqft' ),
		'not_found'          => __( 'No Properties found', 'luxurysqft' ),
		'not_found_in_trash' => __( 'No Properties found in Trash', 'luxurysqft' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Properties', 'luxurysqft' ),
	);
	$args   = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'property' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' )
	);
	register_post_type( 'apartments', $args );


	$args = array(
		'label'             => 'Leads',
		'public'            => false,
		'show_in_admin_bar' => false,
		'query_var'         => true,
		'rewrite'           => true,
		'capability_type'   => 'post',
		'has_archive'       => false,
		'hierarchical'      => false,
		'menu_position'     => 5,
		'supports'          => array( 'title', 'author', 'custom-fields' )
	);
	register_post_type( 'leads', $args );
}

add_action( 'init', 'add_post_type' );

function post_types_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['apartments'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => sprintf( __( 'Apartment updated. <a href="%s">View apartment</a>' ), esc_url( get_permalink( $post_ID ) ) ),
		2  => __( 'Custom field updated.' ),
		3  => __( 'Custom field deleted.' ),
		4  => __( 'Apartment updated.' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Apartment restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => sprintf( __( 'Apartment published. <a href="%s">View apartment</a>' ), esc_url( get_permalink( $post_ID ) ) ),
		7  => __( 'Apartment saved.' ),
		8  => sprintf( __( 'Apartment submitted. <a target="_blank" href="%s">Preview apartment</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		9  => sprintf( __( 'Apartment scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview apartment</a>' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Apartment draft updated. <a target="_blank" href="%s">Preview apartment</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'post_types_updated_messages' );

function add_post_type_taxonomies() {
	// Add new taxonomy
	$labels = array(
		'name'              => _x( 'Location', 'taxonomy general name', 'luxurysqft' ),
		'singular_name'     => _x( 'Location', 'taxonomy singular name', 'luxurysqft' ),
		'search_items'      => __( 'Search location', 'luxurysqft' ),
		'all_items'         => __( 'All Locations', 'luxurysqft' ),
		'parent_item'       => __( 'Parent Location', 'luxurysqft' ),
		'parent_item_colon' => __( 'Parent Location:', 'luxurysqft' ),
		'edit_item'         => __( 'Edit Location', 'luxurysqft' ),
		'update_item'       => __( 'Update Location', 'luxurysqft' ),
		'add_new_item'      => __( 'Add New Location', 'luxurysqft' ),
		'new_item_name'     => __( 'New Location', 'luxurysqft' ),
		'menu_name'         => __( 'Location', 'luxurysqft' ),
	);

	register_taxonomy( 'location', array( 'apartments' ), array(
		'hierarchical' => true,
		'labels'       => $labels,
		'show_ui'      => true,
		'query_var'    => true,
		'rewrite'      => array( 'slug' => 'location' ),
	) );

	// Add new product taxonomies
	$labels_brand = array(
		'name'              => _x( 'Brand', 'taxonomy general name', 'luxurysqft' ),
		'singular_name'     => _x( 'Brand', 'taxonomy singular name', 'luxurysqft' ),
		'search_items'      => __( 'Search brand', 'luxurysqft' ),
		'all_items'         => __( 'All Brands', 'luxurysqft' ),
		'parent_item'       => __( 'Parent Brand', 'luxurysqft' ),
		'parent_item_colon' => __( 'Parent Brand:', 'luxurysqft' ),
		'edit_item'         => __( 'Edit Brand', 'luxurysqft' ),
		'update_item'       => __( 'Update Brand', 'luxurysqft' ),
		'add_new_item'      => __( 'Add New Brand', 'luxurysqft' ),
		'new_item_name'     => __( 'New Brand', 'luxurysqft' ),
		'menu_name'         => __( 'Brand', 'luxurysqft' ),
	);

	register_taxonomy( 'brand', array( 'product' ), array(
		'hierarchical' => true,
		'labels'       => $labels_brand,
		'show_ui'      => true,
		'query_var'    => true,
		'rewrite'      => array( 'slug' => 'brand' ),
	) );

	$labels_brand = array(
		'name'              => _x( 'Type', 'taxonomy general name', 'luxurysqft' ),
		'singular_name'     => _x( 'Type', 'taxonomy singular name', 'luxurysqft' ),
		'search_items'      => __( 'Search type', 'luxurysqft' ),
		'all_items'         => __( 'All Types', 'luxurysqft' ),
		'parent_item'       => __( 'Parent Type', 'luxurysqft' ),
		'parent_item_colon' => __( 'Parent Type:', 'luxurysqft' ),
		'edit_item'         => __( 'Edit Type', 'luxurysqft' ),
		'update_item'       => __( 'Update Type', 'luxurysqft' ),
		'add_new_item'      => __( 'Add New Type', 'luxurysqft' ),
		'new_item_name'     => __( 'New Type', 'luxurysqft' ),
		'menu_name'         => __( 'Type', 'luxurysqft' ),
	);

	register_taxonomy( 'type', array( 'product' ), array(
		'hierarchical' => true,
		'labels'       => $labels_brand,
		'show_ui'      => true,
		'query_var'    => true,
		'rewrite'      => array( 'slug' => 'type' ),
	) );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Area', 'taxonomy general name', 'luxurysqft' ),
		'singular_name'              => _x( 'Area', 'taxonomy singular name', 'luxurysqft' ),
		'search_items'               => __( 'Search Areas', 'luxurysqft' ),
		'popular_items'              => __( 'Popular Areas', 'luxurysqft' ),
		'all_items'                  => __( 'All Areas', 'luxurysqft' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Area', 'luxurysqft' ),
		'update_item'                => __( 'Update Area', 'luxurysqft' ),
		'add_new_item'               => __( 'Add New Area', 'luxurysqft' ),
		'new_item_name'              => __( 'New Area', 'luxurysqft' ),
		'separate_items_with_commas' => __( 'Separate areas with commas', 'luxurysqft' ),
		'add_or_remove_items'        => __( 'Add or remove areas', 'luxurysqft' ),
		'choose_from_most_used'      => __( 'Choose from the most used areas', 'luxurysqft' ),
		'not_found'                  => __( 'No areas found.', 'luxurysqft' ),
		'menu_name'                  => __( 'Area', 'luxurysqft' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => true,
	);

	register_taxonomy( 'area', 'apartments', $args );
}

add_action( 'init', 'add_post_type_taxonomies', 0 );
