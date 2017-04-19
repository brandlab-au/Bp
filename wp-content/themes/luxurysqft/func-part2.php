<?php
/**
 * Woocommerce support
 */
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

/**
 * Custom read more for single post
 */
function single_read_more( $content ) {
	if ( is_singular() ) {
		global $post;
		$content_parts = explode( '<span id="more-' . $post->ID . '"></span>', $content );

		if ( count( $content_parts ) == 2 ) {
			$second_part = '
	    <div class="open-close">
		<div class="slide">
		
		    ' . $content_parts[1] . '
		    
		</div>
		<div><a class="opener" href="#">' . __( 'READ MORE', 'luxurysqft' ) . '</a></div></div>';
			$content     = $content_parts[0] . $second_part;
		}
	}

	return $content;
}

add_filter( 'the_content', 'single_read_more', 0, 1 );

/**
 * Change body classes
 */
function theme_body_class( $classes, $extra_classes ) {
	if ( is_page_template( 'pages/template-home.php' ) ) {
		$classes[] = 'home loader';
	} elseif ( is_page_template( 'pages/template-settings.php' ) or is_page_template( 'pages/template-buy.php' ) or is_page_template( 'pages/template-saved.php' ) or is_singular( 'apartments' ) or is_singular( 'product' ) or is_shop() ) {
		$classes[] = 'loader';
	}

	if ( is_page_template( 'pages/template-settings.php' ) ) {
		$login_page = get_field( 'login_page', 'option' );
		if ( isset( $_SERVER['HTTP_REFERER'] ) and $_SERVER['HTTP_REFERER'] == $login_page ) {
			$classes[] = 'enter-page';
		}
	}


	if ( is_page_template( 'pages/template-home.php' ) or is_page_template( 'pages/template-buy.php' ) ) {
		if ( isset( $_GET['buy'] ) and ( $_GET['buy'] == 'rr' or $_GET['buy'] == 'cr' ) ):
			$classes[] = ' rent';
		else:
			$classes[] = ' buy';
		endif;
	}
	// List of the only WP generated classes that are not allowed
	$blacklist = array( 'single-post' );

	// Blacklist result: (uncomment if you want to blacklist classes)
	$classes = array_diff( $classes, $blacklist );

	// Add the extra classes back untouched
	return array_merge( $classes, (array) $extra_classes );

	//return $classes;
}

add_filter( 'body_class', 'theme_body_class', 10, 2 );


/**
 * Change wp_pagenavi html
 */
function wp_pagenavi_list( $out ) {
	$out = preg_replace( "/<span class='current'>(\d+)<\/span>/", "<li><strong>$1</strong></li>\n", $out );
	$out = str_replace( "<span class='extend'>", '<li><span>', $out );
	$out = str_replace( "<span class='pages'>", '<li class="pages"><span>', $out );
	$out = str_replace( '</span>', "</span></li>\n", $out );
	$out = str_replace( '<a', '<li><a', $out );
	$out = str_replace( '</a>', "</a></li>\n", $out );

	return $out;
}

add_filter( 'wp_pagenavi', 'wp_pagenavi_list' );

/**
 * Get image info
 *
 * @param int $post Post ID.
 * @param     array sizes
 * @param     bool
 *
 * @return array Image sizes
 */
function get_x2_image( $post_id, $sizes, $post_thumbnail = true ) {
	$image_info   = array();
	$thumbnail_id = $post_thumbnail ? get_post_thumbnail_id( $post_id ) : $post_id;
	if ( is_array( $sizes ) ) {
		foreach ( $sizes as $size ) {
			$image               = wp_get_attachment_image_src( $thumbnail_id, $size );
			$image_info[ $size ] = $image[0];
		}

		$attachment        = get_post( $thumbnail_id );
		$image_info['alt'] = trim( strip_tags( get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) ) );

		if ( empty( $image_info['alt'] ) ) {
			$image_info['alt'] = trim( strip_tags( $attachment->post_excerpt ) );
		} // If not, Use the Caption

		if ( empty( $image_info['alt'] ) ) {
			$image_info['alt'] = trim( strip_tags( $attachment->post_title ) );
		} // Finally, use the title
	}

	return $image_info;
}

/**
 * Show number with delimer
 *
 * @param int
 * @param string
 *
 * @echo number
 */
function add_delimiter( $number, $separator = ',' ) {
	$number = absint( $number );
	echo number_format( $number, 0, $separator, $separator );
}

/**
 * Get country locations
 *
 * @param int $post ID
 *
 * @return array
 */
function get_country( $post_id ) {
	$locations         = wp_get_post_terms( $post_id, 'location', array( 'parent' => 0 ) );
	$country           = $term_ids = $result = $html = array();
	$result['country'] = $result['country_links'] = '';
	$result['term_id'] = array();

	if ( $locations ) {
		foreach ( $locations as $location ) {
			$country[]  = $location->name;
			$html[]     = '<a href="' . get_term_link( $location ) . '">' . $location->name . '</a>';
			$term_ids[] = $location->term_id;
		}
		$result['country']       = implode( $country, ',' );
		$result['country_links'] = implode( $html, ',' );
		$result['term_id']       = $term_ids;
	}

	return $result;
}

/**
 * Get town locations
 *
 * @param int $post ID
 * @param     array
 *
 * @return sting
 */
function get_town( $post_id, $term_id ) {
	$html = '';
	if ( is_array( $term_id ) ) {
		foreach ( $term_id as $id ) {
			$locations = wp_get_post_terms( $post_id, 'location', array( 'parent' => $id ) );
			if ( $locations ) {
				foreach ( $locations as $location ) {
					$html .= ', <a href="' . get_term_link( $location ) . '">' . $location->name . '</a>';
				}
			}
		}
	}

	return $html;
}

/**
 * Get args
 *
 * @return array
 */
function get_apartment_args() {
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	} else {
		$paged = 1;
	}

	$args = array(
		'post_type'           => 'apartments',
		'posts_per_page'      => 24,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'paged'               => $paged
	);

	$buy_variants = array(
		'rb',
		'rr',
		'cb',
		'cr',
		'ib',
		'ir'
	);
	if ( isset( $_GET['buy'] ) && in_array( $_GET['buy'], $buy_variants ) ) {
		// custom fields query
		$args['meta_query']   = array();
		$args['meta_query'][] = array(
			'key'     => 'buyrent',
			'value'   => $_GET['buy'],
			'compare' => '=',
		);
	}


	if ( isset( $_GET['type'] ) && ! empty( $_GET['type'] ) ) {
		// custom fields query
		if ( ! isset( $args['meta_query'] ) ) {
			$args['meta_query'] = array();
		}

		$args['meta_query'][] = array(
			'key'     => 'type',
			'value'   => absint( $_GET['type'] ),
			'type'    => 'numeric',
			'compare' => '=',
		);
	}

	if ( isset( $_GET['beds'] ) && ! empty( $_GET['beds'] ) ) {
		// custom fields query
		if ( ! isset( $args['meta_query'] ) ) {
			$args['meta_query'] = array();
		}

		$number               = absint( $_GET['beds'] );
		$args['meta_query'][] = array(
			'key'     => 'bedrooms',
			'value'   => $number,
			'type'    => 'numeric',
			'compare' => ( $number == 10 ) ? '>=' : '=',
		);
	}

	if ( isset( $_GET['price'] ) && ! empty( $_GET['price'] ) ) {
		// custom fields query
		if ( ! isset( $args['meta_query'] ) ) {
			$args['meta_query'] = array();
		}

		$price = explode( '-', $_GET['price'] );

		$args['meta_query'][] = array(
			'key'     => 'price',
			'value'   => array( absint( $price[0] ), absint( $price[1] ) ),
			'type'    => 'numeric',
			'compare' => 'BETWEEN',
		);
	}

	$terms = array();
	if ( isset( $_GET['country'] ) && ! empty( $_GET['country'] ) ) {
		$terms[] = absint( $_GET['country'] );
	}

	if ( isset( $_GET['city'] ) && ! empty( $_GET['city'] ) ) {
		$terms[] = absint( $_GET['city'] );
	}

	if ( ! empty( $terms ) ) {
		// terms query
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'location',
				'field'    => 'term_id',
				'terms'    => $terms,
			)
		);
	}

	if ( isset( $_GET['ar'] ) && ! empty( $_GET['ar'] ) ) {
		$term_id   = absint( $_GET['ar'] );
		$term_args = array(
			'taxonomy' => 'area',
			'field'    => 'term_id',
			'terms'    => array( $term_id ),
		);
		if ( ! isset( $args['tax_query'] ) ) {
			$args['tax_query'] = array(
				$term_args
			);
		} else {
			$args['tax_query']['relation'] = 'AND';
			$args['tax_query'][]           = $term_args;
		}
	}

	return $args;
}

/**
 * Get count of the posts
 *
 * @return string
 */
function get_post_count() {
	global $wp_query;

	$found_posts = $wp_query->found_posts;
	$page        = isset( $wp_query->query['paged'] ) ? $wp_query->query['paged'] : 1;
	$per_page    = isset( $wp_query->query['posts_per_page'] ) ? $wp_query->query['posts_per_page'] : get_option( 'posts_per_page' );
	$post_count  = $wp_query->post_count;

	$first_number  = $page == 1 ? $page : ( $page - 1 ) * $per_page + 1;
	$second_number = $page == 1 ? $per_page : $page * $per_page;

	if ( $second_number > $found_posts ) {
		$second_number = $found_posts;
	}

	if ( $found_posts > 1000 ) {
		$found_posts = '1000+';
	}

	return '<p><span class="number">' . $first_number . '</span>-<span class="number-pages">' . $second_number . '</span> of <span class="full-number">' . $found_posts . '</span></p>';
}

/**
 * Get url
 *
 * @return string or false
 */
function get_apartments_url() {
	global $wpdb;
	$result = $wpdb->get_row(
		"
	    SELECT post_id 
	    FROM $wpdb->postmeta 
	    WHERE meta_key = '_wp_page_template'
	    AND meta_value = 'pages/template-buy.php'
	    "
	);

	if ( $result ) {
		return get_permalink( $result->post_id );
	}

	return false;
}

/**
 * Get url
 *
 * @return string or false
 */
function get_saved_url() {
	global $wpdb;
	$result = $wpdb->get_row(
		"
	    SELECT post_id 
	    FROM $wpdb->postmeta 
	    WHERE meta_key = '_wp_page_template'
	    AND meta_value = 'pages/template-saved.php'
	    "
	);

	if ( $result ) {
		return get_permalink( $result->post_id );
	}

	return false;
}

/**
 * add to global all id from cookie
 */
function get_all_id_selected_apartments() {
	global $saved_ids;
	if ( isset( $_COOKIE['StorageCookie'] ) && preg_match_all( '/(\d+)/', $_COOKIE['StorageCookie'], $matches ) ) :
		$saved_ids = $matches[1];
	endif;
}

add_action( 'init', 'get_all_id_selected_apartments' );

/**
 * add id in cookies
 * need use global $last_viewed_apartments, $last_viewed_products;
 */
function last_viewed_posts( $name ) {
	global $last_viewed_apartments, $last_viewed_products;
	//apartments
	if ( is_singular( 'apartments' ) ) {
		$apartments             = get_last_viewed_posts( 'apartments' );
		$set_apartments         = $apartments['set_products'];
		$last_viewed_apartments = $apartments['last_viewed'];

		setcookie( 'last_viewed_apartments', $set_apartments, time() + 3600, '/' );
	}
	//product
	if ( is_singular( 'product' ) ) {
		$products             = get_last_viewed_posts( 'product' );
		$set_products         = $products['set_products'];
		$last_viewed_products = $products['last_viewed'];

		setcookie( 'last_viewed_product', $set_products, time() + 3600, '/' );
	}
}

add_action( 'get_header', 'last_viewed_posts' );

/**
 * Get last viewed ids
 *
 * @param string slug of post_type
 *
 * @return array
 */
function get_last_viewed_posts( $post_type ) {
	$results    = $temp = array();
	$current_id = get_the_ID();
	if ( isset( $_COOKIE[ 'last_viewed_' . $post_type ] ) ) {
		if ( preg_match_all( '/(\d+)/', $_COOKIE[ 'last_viewed_' . $post_type ], $matches ) ) {
			$results = exclude_id_from_array( $current_id, $matches[1] );
			array_unshift( $results, $current_id );
		} else {
			$results[] = $current_id;
		}
	} elseif ( ! isset( $_COOKIE[ 'last_viewed_' . $post_type ] ) ) {
		$results[] = $current_id;
	}
	$results = array_slice( $results, 0, 10 );

	$temp['set_products'] = implode( ',', $results );
	$temp['last_viewed']  = isset( $matches[1] ) ? $matches[1] : array();

	return $temp;
}

/**
 * Exclude id from array
 *
 * @param int   ID
 * @param array with ids
 *
 * @return array
 */
function exclude_id_from_array( $id, $ids ) {
	$key = array_search( $id, $ids );

	if ( $key !== false ) {
		unset( $ids[ $key ] );
	}

	return $ids;
}

/**
 * Get type of apartment from acf
 *
 * @param int
 * @param bool
 *
 * @return if second parameter bool, then return array, in other case return string or false
 */
function get_apartment_type( $number, $return_choices = false ) {
	global $wpdb;
	$result = $wpdb->get_row(
		"
	    SELECT `post_content` FROM `$wpdb->posts` WHERE `post_excerpt` = 'type' AND `post_type` = 'acf-field'
	    "
	);

	if ( $result ) {
		$data = maybe_unserialize( $result->post_content );

		if ( $return_choices ) {
			return $data["choices"];
		}

		foreach ( $data["choices"] as $key => $value ) {
			if ( $key == $number ) {
				return $value;
			}
		}
	}

	return false;
}

/**
 * AJAX add leads post (callback)
 */
function ajax_apartments_request() {
	check_ajax_referer( 'apartments-broker-form', 'security' );

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$post_data = array();

		$parts = explode( '&', $_POST['data'] );
		foreach ( $parts as $part ) {
			$temp                  = explode( '=', $part );
			$post_data[ $temp[0] ] = isset( $temp[1] ) ? sanitize_text_field( urldecode( $temp[1] ) ) : '';
		}

		$args = array(
			'post_title'  => md5( time() . rand() ),
			'post_type'   => 'leads',
			'post_status' => 'publish',
			'post_author' => $post_data['broker'],
		);

		// Insert the post into the database.
		$id = wp_insert_post( $args );
		if ( $id ) {
			add_post_meta( $id, 'leads_title', $post_data['title'] );
			add_post_meta( $id, 'leads_first_name', $post_data['first_name'] );
			add_post_meta( $id, 'leads_last_name', $post_data['last_name'] );
			add_post_meta( $id, 'leads_telephone', $post_data['telephone'] );
			add_post_meta( $id, 'leads_email', $post_data['email'] );
			add_post_meta( $id, 'leads_message', $post_data['message'] );
			add_post_meta( $id, 'leads_post_id', $post_data['post_id'] ); //apartments post_id
		}
	}

	exit;
}

add_action( 'wp_ajax_ajax_apartments_request', 'ajax_apartments_request' );
add_action( 'wp_ajax_nopriv_ajax_apartments_request', 'ajax_apartments_request' );

/**
 * AJAX edit apartment, only view
 */
function ajax_edit_apartment() {
	if ( isset( $_GET['edit_apartment'] ) && wp_verify_nonce( $_GET['edit_apartment'], 'edit_post_from_listings' ) ) :
		global $post;
		$post_id = absint( $_GET['id'] );

		if ( empty( $post_id ) ) {
			exit( __( '<div class="modal-lg" role="document"><div class="modal-content"><div class="modal-body"><div class="edit-form"><h2>Wrong post id.</h2></div></div></div></div>', 'luxurysqft' ) );
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			exit( __( '<div class="modal-lg" role="document"><div class="modal-content"><div class="modal-body"><div class="edit-form"><h2>You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?</h2></div></div></div></div>', 'luxurysqft' ) );
		}

		if ( $post->post_type != 'apartments' ) {
			exit( __( '<div class="modal-lg" role="document"><div class="modal-content"><div class="modal-body"><div class="edit-form"><h2>Wrong post type.</h2></div></div></div></div>', 'luxurysqft' ) );
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			exit( __( '<div class="modal-lg" role="document"><div class="modal-content"><div class="modal-body"><div class="edit-form"><h2>You are not allowed to edit this item.</h2></div></div></div></div>', 'luxurysqft' ) );
		}

		if ( 'trash' == $post->post_status ) {
			exit( __( '<div class="modal-lg" role="document"><div class="modal-content"><div class="modal-body"><div class="edit-form"><h2>You can&#8217;t edit this item because it is in the Trash. Please restore it and try again.</h2></div></div></div></div>', 'luxurysqft' ) );
		}
		?>
		<!-- lightbox -->
		<div class="modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<?php setup_postdata( $post ); ?>
					<div id="ajax_response"></div>
					<form class="edit-form" data-src="<?php echo admin_url( 'admin-ajax.php' ) ?>" action="#">
						<fieldset>
							<h2><?php _e( 'ADD/EDIT PROPERTY', 'luxurysqft' ); ?></h2>
							<div class="wrap">
								<div class="col-form">
									<div class="row-holder">
										<div class="column">
											<label for="edit1"><?php _e( 'Main Title', 'luxurysqft' ); ?></label>
											<input id="edit1" name="title" type="text"
											       placeholder="<?php echo esc_attr( get_the_title() ) ?>"
											       value="<?php echo esc_attr( get_the_title( get_the_ID() ) ) ?>">
										</div>
									</div>
									<?php $terms = get_terms( 'location', array(
										'hide_empty' => 0
									) );
									$locations   = wp_get_post_terms( get_the_ID(), 'location' );
									$country     = $city = array();
									if ( $locations ) {
										foreach ( $locations as $location ) {
											if ( $location->parent != 0 ) {
												$city[] = $location->term_id;
											} else {
												$country[] = $location->term_id;
											}
										}
									}

									if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
										<div class="row-holder">
											<div class="column">
												<label for="edit2"><?php _e( 'Country', 'luxurysqft' ); ?></label>
												<select name="country" id="edit2">
													<option value="">---</option>
													<?php foreach ( $terms as $term ) :
														if ( $term->parent != 0 )
															continue ?>
														<option
															value="<?php echo $term->term_id ?>" <?php if ( in_array( $term->term_id, $country ) )
															echo 'selected="selected"' ?>><?php echo $term->name ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="row-holder">
											<div class="column">
												<label for="edit3"><?php _e( 'City', 'luxurysqft' ); ?></label>
												<select name="city" id="edit3">
													<option value="">---</option>
													<?php foreach ( $terms as $term ) :
														if ( $term->parent == 0 )
															continue ?>
														<option
															value="<?php echo $term->term_id ?>" <?php if ( in_array( $term->term_id, $city ) )
															echo 'selected="selected"' ?>><?php echo $term->name ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
									<?php endif ?>
									<?php $map = get_field( 'map' ); ?>
									<div class="row-holder">
										<div class="column ui-widget">
											<label for="searchTextField"><?php _e( 'Address', 'luxurysqft' ); ?></label>
											<input id="searchTextField" name="address" type="text"
											       placeholder="Renaissance villa, lake views, Emirates Hills, Dubai, UAE"
											       value="<?php if ( isset( $map['address'] ) )
												       echo esc_attr( $map['address'] ) ?>">
											<div id="results"></div>
										</div>
									</div>
									<?php $type = get_field( 'type' );
									$types      = (array) get_apartment_type( 0, true ); ?>
									<div class="row-holder">
										<div class="column">
											<label for="edit5"><?php _e( 'Type of Property', 'luxurysqft' ); ?></label>
											<select name="type" id="edit5">
												<option value="">---</option>
												<?php foreach ( $types as $key => $value ) : ?>
													<option
														value="<?php echo $key ?>" <?php selected( $type, $key ); ?>><?php echo $value ?></option>
												<?php endforeach ?>
											</select>
										</div>
									</div>
									<?php $buyrent = get_field( 'buyrent' ) ?>
									<div class="row-holder">
										<div class="column">
											<label for="edit6"><?php _e( 'Contract Type', 'luxurysqft' ); ?></label>
											<select name="contract_type" id="edit6">
												<option value="">---</option>
												<option
													value="rb" <?php selected( $buyrent, 'rb' ); ?>><?php _e( 'Residential Buy', 'luxurysqft' ); ?></option>
												<option
													value="rr" <?php selected( $buyrent, 'rr' ); ?>><?php _e( 'Residential Rent', 'luxurysqft' ); ?></option>
												<option
													value="cb" <?php selected( $buyrent, 'cb' ); ?>><?php _e( 'Commercial Buy', 'luxurysqft' ); ?></option>
												<option
													value="cr" <?php selected( $buyrent, 'cr' ); ?>><?php _e( 'Commercial Rent', 'luxurysqft' ); ?></option>
												<option
													value="ib" <?php selected( $buyrent, 'ib' ); ?>><?php _e( 'International Buy', 'luxurysqft' ); ?></option>
												<option
													value="ir" <?php selected( $buyrent, 'ir' ); ?>><?php _e( 'International Rent', 'luxurysqft' ); ?></option>
											</select>
										</div>
									</div>
									<?php $price = get_field( '_price' ) ?>
									<div class="row-holder">
										<div class="column">
											<label for="edit7"><?php _e( 'Price', 'luxurysqft' ); ?></label>
											<input id="edit7" name="price" type="text"
											       value="<?php echo absint( $price ) ?>">
										</div>
									</div>
									<?php $areas = get_terms( 'area', 'hide_empty=0' );
									$curr_area   = wp_get_post_terms( get_the_ID(), 'area' );
									if ( ! empty( $areas ) && ! is_wp_error( $areas ) ) : ?>
										<div class="row-holder">
											<div class="column">
												<label for="edit77"><?php _e( 'Area', 'luxurysqft' ); ?></label>
												<select name="area" id="edit77">
													<option value="">---</option>
													<?php foreach ( $areas as $term ) : ?>
														<option
															value="<?php echo $term->term_id ?>" <?php if ( $term->term_id == $curr_area[0]->term_id )
															echo 'selected="selected"' ?>><?php echo $term->name ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
									<?php endif; ?>
									<?php $toilet = get_field( 'toilet' ) ?>
									<div class="row-holder">
										<div class="column">
											<label for="edit78"><?php _e( 'Toilet', 'luxurysqft' ); ?></label>
											<input id="edit78" name="toilet" type="text" placeholder="Toilet"
											       value="<?php echo $toilet; ?>">
										</div>
									</div>
								</div>
								<div class="col-form">
									<div class="row-holder">
										<div class="column">
											<div class="image-holder featured-image">
												<?php if ( has_post_thumbnail() ): ?>
													<?php $image_info = get_x2_image( get_the_ID(), array(
														'thumbnail_456x307',
														'thumbnail_912x614'
													) ); ?>
													<picture>
														<!--[if IE 9]>
														<video style="display: none;"><![endif]-->
														<source
															srcset="<?php echo $image_info['thumbnail_456x307'] ?>, <?php echo $image_info['thumbnail_912x614'] ?> 2x">
														<!--[if IE 9]></video><![endif]-->
														<img src="<?php echo $image_info['thumbnail_456x307'] ?>"
														     alt="<?php echo $image_info['alt'] ?>">
													</picture>
													<input type="hidden" name="featured"
													       value="<?php echo get_post_thumbnail_id(); ?>">
												<?php endif; ?>
											</div>
										</div>
									</div>
									<?php if ( current_user_can( 'upload_files' ) ) : ?>
										<div class="row-holder">
											<div class="column">
												<label
													for="frontend-media2"><?php _e( 'Featured Image upload', 'luxurysqft' ); ?></label>
												<input id="frontend-media2" type="button"
												       value="<?php _e( 'upload', 'luxurysqft' ); ?>">
											</div>
										</div>
									<?php endif ?>
									<ul id="apartment-gallery" class="photos">
										<?php if ( $apartment_images = get_field( 'apartment_images' ) ) : ?>
											<?php foreach ( $apartment_images as $image ) : ?>
												<li>
													<picture>
														<!--[if IE 9]>
														<video style="display: none;"><![endif]-->
														<source
															srcset="<?php echo $image['sizes']['thumbnail_38x38']; ?>, <?php echo $image['sizes']['thumbnail_76x76'] ?> 2x">
														<!--[if IE 9]></video><![endif]-->
														<img src="<?php echo $image['sizes']['thumbnail_38x38']; ?>"
														     alt="<?php echo $image['alt'] ?>">
													</picture>
													<input type="hidden" name="attachments[]"
													       value="<?php echo $image['ID'] ?>">
												</li>
											<?php endforeach ?>
										<?php endif ?>
									</ul>
									<?php if ( current_user_can( 'upload_files' ) ) : ?>
										<div class="row-holder">
											<div class="column">
												<label
													for="frontend-media"><?php _e( 'Image upload', 'luxurysqft' ); ?></label>
												<input id="frontend-media" type="button"
												       value="<?php _e( 'upload', 'luxurysqft' ); ?>">
											</div>
										</div>
									<?php endif ?>
									<div class="row-holder">
										<div class="column">
											<label
												for="edit14"><?php _e( 'Description of property', 'luxurysqft' ); ?></label>
											<textarea name="content" id="edit14" cols="10" rows="10"
											          placeholder="Add your description"><?php echo $post->post_content ?></textarea>
										</div>
									</div>
									<?php $bedrooms = get_field( 'bedrooms' );
									$bathrooms      = get_field( 'bathrooms' );
									$sqft           = get_field( 'sqft' );
									$parking        = get_field( 'parking' );
									//$toilet = get_field( 'toilet' );
									?>
									<div class="row-holder">
										<div class="column small">
											<label for="edit10"><?php _e( 'Bedrooms', 'luxurysqft' ); ?></label>
											<input id="edit10" name="bedrooms" type="text"
											       value="<?php echo absint( $bedrooms ) ?>">
										</div>
										<div class="column small">
											<label for="edit11"><?php _e( 'Bathrooms', 'luxurysqft' ); ?></label>
											<input id="edit11" name="bathrooms" type="text"
											       value="<?php echo absint( $bathrooms ) ?>">
										</div>
										<div class="column medium">
											<label for="edit13"><?php _e( 'Sq Ft', 'luxurysqft' ); ?></label>
											<input id="edit13" name="sqft" type="text" placeholder="22000"
											       value="<?php echo absint( $sqft ) ?>">
										</div>
										<div class="column small">
											<label for="edit12"><?php _e( 'Parking', 'luxurysqft' ); ?></label>
											<input id="edit12" name="parking" type="text"
											       value="<?php echo absint( $parking ) ?>">
										</div>
									</div>
									<?php $reference_no = get_field( 'reference_no' ); ?>
									<div class="row-holder">
										<div class="column">
											<label for="edit9"><?php _e( 'REF No:', 'luxurysqft' ); ?></label>
											<input id="edit9" name="ref-no" type="text" placeholder="0012345668"
											       value="<?php echo absint( $reference_no ) ?>">
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="id" value="<?php echo $post_id ?>">
							<input class="btn btn-default" type="submit" value="SUBMIT">
						</fieldset>
					</form>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
		<?php exit;
	endif;
}

add_action( 'get_header', 'ajax_edit_apartment' );

/**
 * This filter insures users only see their own media
 */
function filter_media( $query ) {
	// admins get to see everything
	if ( ! current_user_can( 'manage_options' ) ) {
		$query['author'] = get_current_user_id();
	}

	return $query;
}

add_filter( 'ajax_query_attachments_args', 'filter_media' );

/**
 * AJAX save apartment (callback)
 */
function ajax_settings_apartment_save() {
	check_ajax_referer( 'settings-apartment-edit-form', 'security' );

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$post_data                = array();
		$post_data['attachments'] = array();

		$parts = explode( '&', $_POST['data'] );
		foreach ( $parts as $part ) {
			$temp = explode( '=', $part );

			$name = strripos( $temp[0], 'attachments' );

			if ( $name !== false ) {
				$post_data['attachments'][] = isset( $temp[1] ) ? absint( $temp[1] ) : '';
			} elseif ( $temp[0] == 'content' ) {
				$post_data[ $temp[0] ] = isset( $temp[1] ) ? urldecode( $temp[1] ) : '';
			} else {
				$post_data[ $temp[0] ] = isset( $temp[1] ) ? sanitize_text_field( urldecode( $temp[1] ) ) : '';
			}
		}

		if ( ! isset( $post_data['lat'] ) or ! isset( $post_data['lng'] ) ) {
			if ( ! empty( $post_data['address'] ) ) {
				$coordinates      = get_lat_lng( $post_data['address'] );
				$post_data['lat'] = $coordinates['lat'];
				$post_data['lng'] = $coordinates['lng'];
			}
		}

		$ajax_response = array(
			'status'  => false,
			'message' => ''
		);

		header( 'Content-Type: application/json' );

		$post_id = absint( $post_data['id'] );

		if ( empty( $post_id ) ) {
			$ajax_response['message'] = __( 'Wrong post id.', 'luxurysqft' );
			exit( json_encode( $ajax_response ) );
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			$ajax_response['message'] = __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?', 'luxurysqft' );
			exit( json_encode( $ajax_response ) );
		}

		if ( $post->post_type != 'apartments' ) {
			$ajax_response['message'] = __( 'Wrong post type.', 'luxurysqft' );
			exit( json_encode( $ajax_response ) );
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			$ajax_response['message'] = __( 'You are not allowed to edit this item.', 'luxurysqft' );
			exit( json_encode( $ajax_response ) );
		}

		if ( 'trash' == $post->post_status ) {
			$ajax_response['message'] = __( 'You can&#8217;t edit this item because it is in the Trash. Please restore it and try again.', 'luxurysqft' );
			exit( json_encode( $ajax_response ) );
		}

		$args = array(
			'ID'           => $post_data['id'],
			'post_title'   => $post_data['title'],
			'post_content' => $post_data['content'],
		);

		$id = wp_update_post( $args, true );
		if ( is_wp_error( $id ) ) {
			$ajax_response['message'] = __( 'An error occurred when saving the post.', 'luxurysqft' );
			exit( json_encode( $ajax_response ) );
		}

		$terms = array();

		if ( $post_data['country'] ) {
			$terms[] = absint( $post_data['country'] );
		}

		if ( $post_data['area'] ) {
			$areas[] = absint( $post_data['area'] );
		}

		if ( $post_data['city'] ) {
			$terms[] = absint( $post_data['city'] );
		}

		if ( ! empty( $terms ) ) {
			wp_set_post_terms( $id, $terms, 'location' );
		}

		if ( ! empty( $areas ) ) {
			wp_set_post_terms( $id, $areas, 'area' );
		}

		if ( $id ) {
			update_field( 'apartment_images', $post_data['attachments'], $id );
			set_post_thumbnail( $id, $post_data['featured'] );
			update_field( 'map', array(
				'address' => $post_data['address'],
				'lat'     => $post_data['lat'],
				'lng'     => $post_data['lng']
			), $id );

			update_field( 'type', $post_data['type'], $id );
			update_field( 'buyrent', $post_data['contract_type'], $id );
			update_field( 'price', $post_data['price'], $id );
			update_field( 'bedrooms', $post_data['bedrooms'], $id );
			update_field( 'bathrooms', $post_data['bathrooms'], $id );
			update_field( 'sqft', $post_data['sqft'], $id );
			update_field( 'parking', $post_data['parking'], $id );
			update_field( 'reference_no', $post_data['ref-no'], $id );
			update_field( 'toilet', $post_data['toilet'], $id );
		}

		$ajax_response['status']  = true;
		$ajax_response['message'] = __( 'Apartment updated.', 'luxurysqft' );
		exit( json_encode( $ajax_response ) );
	}

	exit;
}

add_action( 'wp_ajax_ajax_settings_apartment_save', 'ajax_settings_apartment_save' );

/**
 * AJAX remove apartment in trash (callback)
 */
function ajax_settings_apartment_remove() {
	check_ajax_referer( 'settings-apartment-edit-form', 'security' );

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$post_id = absint( $_POST['id'] );

		if ( empty( $post_id ) ) {
			exit( __( 'Wrong post id.', 'luxurysqft' ) );
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			exit( __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?', 'luxurysqft' ) );
		}

		if ( $post->post_type != 'apartments' ) {
			exit( __( 'Wrong post type.', 'luxurysqft' ) );
		}


		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			exit( __( 'You are not allowed to edit this item.', 'luxurysqft' ) );
		}

		if ( 'trash' == $post->post_status ) {
			exit( __( 'You can&#8217;t edit this item because it is in the Trash. Please restore it and try again.', 'luxurysqft' ) );
		}

		$args = array(
			'ID'          => $post_id,
			'post_status' => 'trash',
		);

		$id = wp_update_post( $args, true );

		if ( is_wp_error( $id ) ) {
			exit( __( 'An error occurred when removing the post.', 'luxurysqft' ) );
		}

		exit( __( 'Apartment was removed.', 'luxurysqft' ) );
	}

	exit;
}

add_action( 'wp_ajax_ajax_settings_apartment_remove', 'ajax_settings_apartment_remove' );

/**
 * AJAX remove apartment in trash (callback)
 */
function ajax_settings_apartment_prime() {
	check_ajax_referer( 'settings-apartment-edit-form', 'security' );

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$post_id = absint( $_POST['id'] );

		if ( empty( $post_id ) ) {
			exit( __( 'Wrong post id.', 'luxurysqft' ) );
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			exit( __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?', 'luxurysqft' ) );
		}

		if ( $post->post_type != 'apartments' ) {
			exit( __( 'Wrong post type.', 'luxurysqft' ) );
		}


		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			exit( __( 'You are not allowed to edit this item.', 'luxurysqft' ) );
		}

		if ( 'trash' == $post->post_status ) {
			exit( __( 'You can&#8217;t edit this item because it is in the Trash. Please restore it and try again.', 'luxurysqft' ) );
		}

		update_post_meta( $post_id, '_prime', date( 'YmdGis' ) );
		apartments_ajax_load( $post_id );
		//exit( __( 'fff', 'luxurysqft' ) );

		if ( is_wp_error( $id ) ) {
			exit( __( 'An error occurred when removing the post.', 'luxurysqft' ) );
		}

		//exit( __( 'Apartment was updated to prime.', 'luxurysqft' ) );
	}

	exit;
}

add_action( 'wp_ajax_ajax_settings_apartment_prime', 'ajax_settings_apartment_prime' );

/**
 * AJAX user remove role 'broker' (callback)
 */
function ajax_settings_user_remove() {
	check_ajax_referer( 'settings-apartment-edit-form', 'security' );

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

		if ( ! current_user_can( 'delete_users' ) ) {
			exit( __( 'You can&#8217;t delete users.', 'luxurysqft' ) );
		}

		$parts = explode( '&', $_POST['data'] );
		foreach ( $parts as $part ) {
			$temp = explode( '=', $part );
			if ( isset( $temp[1] ) ) {
				$id   = absint( $temp[1] );
				$user = new WP_User( $id );
				if ( in_array( 'broker', $user->roles ) ) {
					$user->remove_role( 'broker' );
					//wp_delete_user( $id );
					$args  = array(
						'post_type'      => 'apartments',
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
						'author'         => $id
					);
					$query = new WP_Query( $args );
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$id = wp_update_post( array(
								'ID'          => get_the_ID(),
								'post_status' => 'trash',
							), true );
						}
					}
					wp_reset_postdata();
				}
			}
		}
	}

	exit;
}

add_action( 'wp_ajax_ajax_settings_user_remove', 'ajax_settings_user_remove' );

/**
 * Get coordinates
 *
 * @param string Address
 *
 * @return array
 */
function get_lat_lng( $address ) {
	$result      = array(
		'lat' => 1,
		'lng' => 1
	);
	$address     = urlencode( $address );
	$request_url = 'http://maps.googleapis.com/maps/api/geocode/xml?address=' . $address . '&sensor=false';
	$xml         = simplexml_load_file( $request_url );

	if ( $xml && $xml->status == "OK" ) {
		$result['lat'] = (string) $xml->result->geometry->location->lat;
		$result['lng'] = (string) $xml->result->geometry->location->lng;
	}

	return $result;
}

/**
 * Change types for broker role
 *
 * @param array
 *
 * @return array
 */
function change_mime_types( $mimes ) {
	global $current_user;
	if ( in_array( 'broker', $current_user->roles ) ) {
		return array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
		);
	}

	return $mimes;
}

add_filter( 'upload_mimes', 'change_mime_types' );

/**
 * Settings page redirect for not logged in users
 */
function settings_redirect() {
	if ( is_page_template( 'pages/template-settings.php' ) && ! is_user_logged_in() ) {
		$woocommerce_myaccount_page_id = get_option( 'woocommerce_myaccount_page_id', false );
		$redirect_url                  = $woocommerce_myaccount_page_id ? get_permalink( $woocommerce_myaccount_page_id ) : wp_login_url();

		wp_redirect( $redirect_url );
		exit;
	}
}

add_action( 'get_header', 'settings_redirect' );


add_action( 'wp_ajax_show_sub_menu', 'show_sub_menu' );
add_action( 'wp_ajax_nopriv_show_sub_menu', 'show_sub_menu' );

function show_sub_menu() {
	
	if ( check_ajax_referer( 'menu', false, false ) ) {

		if(isset( $_POST['option'] )) {
			
			if($_POST['option'] == 'magazine') {

				get_template_part( 'partials/magazine' );
				
			}

			if($_POST['option'] == 'issue') {

				get_template_part( 'partials/issue' );

			}
			
		}

	}

	die();
}

