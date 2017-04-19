<?php
error_reporting(E_ALL);

define('WP_USE_THEMES', false);
require_once('./wp-load.php');
require_once('./wp-blog-header.php');
require_once('./wp-admin/includes/admin.php');
require_once('./wp-admin/includes/media.php');
require_once('./wp-admin/includes/file.php');

//var_dump(dirname(__FILE__) . '/xml1.xml');
$xml     = file_get_contents(dirname(__FILE__) . '/xml5.xml');
$xml_obj = simplexml_load_string($xml);

$total = count($xml_obj->children());

//$location2 = term_exists((string) $xml_obj->Listing[5]->Community, 'area');
//
//if ($location2 !== 0 && $location2 !== null && ! empty($location2)) {
//	wp_set_object_terms(621, (string) $xml_obj->Listing[5]->Community, 'area');
//	die();
//} else {
//	$term2 = wp_insert_term(
//			(string) $xml_obj->Listing[intval($i)]->Community, // the term
//			'area', // the taxonomy
//			array(
//					'description' => ''
//			)
//	);
//	wp_set_object_terms($the_post_id, $term2['term_id'], 'area', true);
//	print $term2['term_id'];
//}

function get_http_response_code($url) {
	$headers = get_headers($url);

	return substr($headers[0], 9, 3);
}


function process_image($image, $post_id) {

	if ( ! empty($image)) {

		// Add Featured Image to Post
		$upload_dir = wp_upload_dir(); // Set upload folder
		$image      = str_replace('amp;', '', $image);
		$image_data = file_get_contents($image); // Get image data

		if (get_http_response_code($image) != "200") {
			return '';
		} else {
			$image_data = file_get_contents($image);
		}

		$length       = 30;
		$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$filename     = $randomString . '.jpg';

// Check folder permission and define file location
		if (wp_mkdir_p($upload_dir['path'])) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		file_put_contents($file, $image_data);

		$wp_filetype = wp_check_filetype($filename, null);
		$attachment  = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name($filename),
				'post_content'   => '',
				'post_status'    => 'inherit'
		);
		$attach_id   = wp_insert_attachment($attachment, $file, $post_id);
		$attach_data = wp_generate_attachment_metadata($attach_id, $file);
		wp_update_attachment_metadata($attach_id, $attach_data);
		set_post_thumbnail($post_id, $attach_id);

		return $attach_id;

	}

}


for ($i = 0; $i < $total; $i++) {

	fwrite(STDERR, 'done: ' . $i . '/' . $total . PHP_EOL);

	//if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Ad_Type), 'rent') !== false) {
	//	print 'rr' .PHP_EOL;
	//}
	//
	//if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Ad_Type), 'sale') !== false) {
	//	print 'rb' .PHP_EOL;
	//}
	//
	//if (2 > 1) continue;

	if (absint((string) $xml_obj->Listing[$i]->Price) < 6000000) continue;

	//$page = get_page_by_title((string) $xml_obj->Listing[intval($i)]->Property_Title, 'object', 'apartments');

	if (false) {

		update_field('field_56c5cd6984248', (string) $xml_obj->Listing[intval($i)]->Unit_Reference_No, $page->ID);

		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Ad_Type), 'rent') !== false) {
			update_field('field_56c63bdcd0e38', 'rr', $page->ID);
		} else {
			update_field('field_56c63bdcd0e38', 'rb', $page->ID);
		}

		/*
		 *
		 * 1 : Apartments
			2 : Villas
			3 : Townhouses
			4 : Penthouses
			5 : Castles
			6 : Bangalows
			7 : Chalets
			8 : RANCHEs
			9 : Lofts
			10 : Mansions
			11 : Islands
			12 : Vinyards
			13 : RESORT LIVING
		 *
		 * */

		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'apartments') !== false) {
			update_field('field_56c5cbff211b6', 1, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'villas') !== false) {
			update_field('field_56c5cbff211b6', 2, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'townhouses') !== false) {
			update_field('field_56c5cbff211b6', 3, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'penthouses') !== false) {
			update_field('field_56c5cbff211b6', 4, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'castles') !== false) {
			update_field('field_56c5cbff211b6', 5, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'bangalows') !== false) {
			update_field('field_56c5cbff211b6', 6, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'chalets') !== false) {
			update_field('field_56c5cbff211b6', 7, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'ranches') !== false) {
			update_field('field_56c5cbff211b6', 8, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'lofts') !== false) {
			update_field('field_56c5cbff211b6', 9, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'mansions') !== false) {
			update_field('field_56c5cbff211b6', 10, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'islands') !== false) {
			update_field('field_56c5cbff211b6', 11, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'vinyards') !== false) {
			update_field('field_56c5cbff211b6', 12, $page->ID);
		}
		if (strpos(strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type), 'resort living') !== false) {
			update_field('field_56c5cbff211b6', 13, $page->ID);
		}

		//Set country
		$parent_term    = term_exists('United Arab Emirates', 'location'); // array is returned if taxonomy is given
		$parent_term_id = $parent_term['term_id']; // get numeric term id
		wp_set_object_terms($page->ID, 'United Arab Emirates', 'location');


		//Set country
		$location = term_exists((string) $xml_obj->Listing[intval($i)]->Emirate, 'location');

		if ($location !== 0 && $location !== null) {
			wp_set_object_terms($page->ID, $xml_obj->Listing[intval($i)]->Emirate, 'location');
		} else {
			$term = wp_insert_term(
					(string) $xml_obj->Listing[intval($i)]->Emirate, // the term
					'location', // the taxonomy
					array(
							'parent' => $parent_term_id
					)
			);
			wp_set_object_terms($page->ID, $term['term_id'], 'location');
		}


		//wp_set_post_tags( $page->ID, (string) $xml_obj->Listing[intval($i)]->Community, true );

		//update_post_meta($page->ID, 'property_google_view', 1);

		$value = array("lat" => (string) $xml_obj->Listing[intval($i)]->Latitude, "lng" => (string) $xml_obj->Listing[intval($i)]->Longitude);
		update_field('field_56c5e24f4748d-56fa3cb491d59', $value, $page->ID);

		//Bathrooms
		if (strtolower((string) $xml_obj->Listing[intval($i)]->No_of_Bathroom) == 'st') {
			update_field('field_56c5cd1e5e467', '1', $page->ID);
		} else {
			update_field('field_56c5cd1e5e467', (string) $xml_obj->Listing[intval($i)]->No_of_Bathroom, $page->ID);
		}


		//Bedrooms
		if (strtolower((string) $xml_obj->Listing[intval($i)]->Bedrooms) == 'st') {
			update_field('field_56c1f6772f1f7', '1', $page->ID);
		} else {
			update_field('field_56c1f6772f1f7', (string) $xml_obj->Listing[intval($i)]->Bedrooms, $page->ID);
		}

		//Rooms
		//if (strtolower((string) $xml_obj->Listing[intval($i)]->No_of_Rooms) == 'st') {
		//	update_post_meta($page->ID, 'bathrooms', 1);
		//} else {
		//	update_post_meta($page->ID, 'property_rooms', (string) $xml_obj->Listing[intval($i)]->No_of_Rooms);
		//}

		//Size
		update_field('field_56c1f67f2f1f8', (string) $xml_obj->Listing[intval($i)]->Unit_Builtup_Area, $page->ID);


		//Price
		update_field('field_56c1f6932f1f9', (string) $xml_obj->Listing[intval($i)]->Price, $page->ID);

		//update_post_meta($page->ID, 'property_label', (string) $xml_obj->Listing[intval($i)]->Frequency);
		//update_post_meta($page->ID, 'property_country', 'United Arab Emirates');

		//Facilities
		//if (count((array) $xml_obj->Listing[intval($i)]->Facilities->facility) > 0) {
		//	$facility_arr    = (array) $xml_obj->Listing[intval($i)]->Facilities->facility;
		//	$facility_wp     = get_option('wp_estate_feature_list');
		//	$facility_wp_arr = array_unique(array_map('trim', explode(',', $facility_wp)));
		//	update_option('wp_estate_feature_list', implode(',', array_unique(array_merge($facility_wp_arr, $facility_arr))));
		//
		//	foreach ($facility_arr as $facility) {
		//		update_post_meta($page->ID, str_replace(' ', '_', trim(preg_replace('/[^\w\s]/', '', strtolower($facility)))), 1);
		//	}
		//
		//}


		//Agent
		//if (count($xml_obj->Listing[intval($i)]->Listing_Agent)) {
		//
		//	$agent = get_page_by_title((string) $xml_obj->Listing[intval($i)]->Listing_Agent, 'object', 'estate_agent');
		//
		//	if ($agent) {
		//
		//		//Set agent to property
		//		update_post_meta($page->ID, 'property_agent', $agent->ID);
		//
		//	} else {
		//
		//		$my_agent = array(
		//				'post_title'   => (string) $xml_obj->Listing[intval($i)]->Listing_Agent,
		//				'post_content' => '',
		//				'post_status'  => 'publish',
		//				'post_type'    => 'estate_agent',
		//		);
		//
		//		$the_agent_id = wp_insert_post($my_agent);
		//
		//		update_post_meta($the_agent_id, 'agent_position', 'Real estate broker');
		//
		//
		//		//header_type
		//		update_post_meta($the_agent_id, 'header_type', 0); // Global
		//
		//		//header_transparent
		//		update_post_meta($the_agent_id, 'header_transparent', 'global'); // Global
		//
		//		//Agent email
		//		if (count($xml_obj->Listing[intval($i)]->Listing_Agent_Email)) {
		//			update_post_meta($the_agent_id, 'agent_email', (string) $xml_obj->Listing[intval($i)]->Listing_Agent_Email);
		//		}
		//
		//		//Agent phone
		//		if (count($xml_obj->Listing[intval($i)]->Listing_Agent_Phone)) {
		//			update_post_meta($the_agent_id, 'agent_phone', (string) $xml_obj->Listing[intval($i)]->Listing_Agent_Phone);
		//		}
		//
		//		//Agent company
		//		if (count($xml_obj->Listing[intval($i)]->company_name)) {
		//			update_post_meta($the_agent_id, 'company_name', (string) $xml_obj->Listing[intval($i)]->company_name);
		//		}
		//
		//		//Set agent to property
		//		update_post_meta($page->ID, 'property_agent', $the_agent_id);
		//
		//	}
		//
		//}

		//
		if (count((array) $xml_obj->Listing[intval($i)]->Images->image) > 0) {

			$images_arr = (array) $xml_obj->Listing[intval($i)]->Images->image;
			process_image($images_arr[0], $page->ID);

		}


	} else {

		$my_post = array(
				'post_title'   => (string) $xml_obj->Listing[intval($i)]->Property_Title,
				'post_content' => str_replace('<![CDATA[', '', str_replace(']]>', '', (string) $xml_obj->Listing[intval($i)]->Web_Remarks)),
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'apartments',
		);

		$the_post_id = wp_insert_post($my_post);

		update_field('field_56c5cd6984248', (string) $xml_obj->Listing[intval($i)]->Unit_Reference_No, $the_post_id);

		if (strpos('rent', strtolower((string) $xml_obj->Listing[intval($i)]->Ad_Type)) !== false) {
			update_field('field_56c63bdcd0e38', 'rr', $the_post_id);
		} else {
			update_field('field_56c63bdcd0e38', 'rb', $the_post_id);
		}

		if (strpos('apartments', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '1', $the_post_id);
		}
		if (strpos('villas', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '2', $the_post_id);
		}
		if (strpos('townhouses', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '3', $the_post_id);
		}
		if (strpos('penthouses', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '4', $the_post_id);
		}
		if (strpos('castles', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '5', $the_post_id);
		}
		if (strpos('bangalows', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '6', $the_post_id);
		}
		if (strpos('chalets', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '7', $the_post_id);
		}
		if (strpos('ranches', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '8', $the_post_id);
		}
		if (strpos('lofts', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '9', $the_post_id);
		}
		if (strpos('mansions', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '10', $the_post_id);
		}
		if (strpos('islands', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '11', $the_post_id);
		}
		if (strpos('vinyards', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '12', $the_post_id);
		}
		if (strpos('resort living', strtolower((string) $xml_obj->Listing[intval($i)]->Unit_Type)) !== false) {
			update_field('field_56c5cbff211b6', '13', $the_post_id);
		}


		//Set country
		$parent_term    = term_exists('United Arab Emirates', 'location', true); // array is returned if taxonomy is given
		$parent_term_id = $parent_term['term_id']; // get numeric term id
		wp_set_object_terms($the_post_id, 'United Arab Emirates', 'location', true);

		//Set location
		$location = term_exists((string) $xml_obj->Listing[intval($i)]->Emirate, 'location');
		if ($location !== 0 && $location !== null) {
			wp_set_object_terms($the_post_id, (string) $xml_obj->Listing[intval($i)]->Emirate, 'location', true);
		} else {
			$term = wp_insert_term(
					(string) $xml_obj->Listing[intval($i)]->Emirate, // the term
					'location', // the taxonomy
					array(
							'parent' => $parent_term_id
					)
			);
			wp_set_object_terms($the_post_id, (string) $xml_obj->Listing[intval($i)]->Emirate, 'location', true);
		}

		//Set area

		$location2 = term_exists((string) $xml_obj->Listing[intval($i)]->Community, 'area');
		if ($location2 !== 0 && $location2 !== null && ! empty($location2)) {
			wp_set_object_terms($the_post_id, $xml_obj->Listing[intval($i)]->Community, 'area', true);
		} else {
			$term2 = wp_insert_term(
					(string) $xml_obj->Listing[intval($i)]->Community, // the term
					'area', // the taxonomy
					array(
							'description' => ''
					)
			);
			wp_set_object_terms($the_post_id, $xml_obj->Listing[intval($i)]->Community, 'area', true);
		}

		$value = array("lat" => (string) $xml_obj->Listing[intval($i)]->Latitude, "lng" => (string) $xml_obj->Listing[intval($i)]->Longitude);
		update_field('field_56c5e24f4748d', $value, $the_post_id);


		//Bathrooms
		if (strtolower((string) $xml_obj->Listing[intval($i)]->No_of_Bathroom) == 'st') {
			update_field('field_56c5cd1e5e467', '1', $the_post_id);
		} else {
			update_field('field_56c5cd1e5e467', (string) $xml_obj->Listing[intval($i)]->No_of_Bathroom, $the_post_id);
		}


		//Bedrooms
		if (strtolower((string) $xml_obj->Listing[intval($i)]->Bedrooms) == 'st') {
			update_field('field_56c1f6772f1f7', '1', $the_post_id);
		} else {
			update_field('field_56c1f6772f1f7', (string) $xml_obj->Listing[intval($i)]->Bedrooms, $the_post_id);
		}


		//Size
		update_field('field_56c1f67f2f1f8', (string) $xml_obj->Listing[intval($i)]->Unit_Builtup_Area, $the_post_id);

		//Price
		update_field('field_56c1f6932f1f9', (string) $xml_obj->Listing[intval($i)]->Price, $the_post_id);


		//Agent
		$agent = get_user_by('email', (string) $xml_obj->Listing[intval($i)]->Listing_Agent_Email);

		if ($agent) {

			//Set agent to property
			$arg = array(
					'ID'          => $the_post_id,
					'post_author' => $agent->ID,
			);
			wp_update_post($arg);

			fwrite(STDERR, 'Agent: ' . $agent->ID . PHP_EOL);

		} else {

			$length2       = 5;
			$randomString2 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length2);

			$login    = esc_html(strtolower(preg_replace('/(\s*)([^\s]*)(.*)/', '$2', (string) $xml_obj->Listing[intval($i)]->Listing_Agent))) . $randomString2;
			$my_agent = array(
					'user_login'    => $login,
					'user_pass'     => wp_generate_password(8, false),
					'user_nicename' => esc_html((string) $xml_obj->Listing[intval($i)]->Listing_Agent),
					'user_email'    => (string) $xml_obj->Listing[intval($i)]->Listing_Agent_Email,
					'display_name'  => esc_html((string) $xml_obj->Listing[intval($i)]->Listing_Agent),
					'nickname'      => $login,
					'first_name'    => esc_html(preg_replace('/(\s*)([^\s]*)(.*)/', '$2', (string) $xml_obj->Listing[intval($i)]->Listing_Agent)),
					'last_name'     => esc_html(str_replace(preg_replace('/(\s*)([^\s]*)(.*)/', '$2', (string) $xml_obj->Listing[intval($i)]->Listing_Agent), '', (string) $xml_obj->Listing[intval($i)]->Listing_Agent)),
					'role'          => 'broker',
			);

			fwrite(STDERR, 'Agent: ' . $agent->ID . PHP_EOL);
			$the_agent_id = wp_insert_user($my_agent);

			//Agent phone
			add_user_meta($the_agent_id, 'agent_phone', addslashes((string) $xml_obj->Listing[intval($i)]->Listing_Agent_Phone));

			//Agent company
			add_user_meta($the_agent_id, 'company_name', addslashes((string) $xml_obj->Listing[intval($i)]->company_name));

			//Company logo
			add_user_meta($the_agent_id, 'company_logo', addslashes((string) $xml_obj->Listing[intval($i)]->company_name));

			//Set agent to property
			$arg = array(
					'ID'          => $the_post_id,
					'post_author' => $the_agent_id,
			);
			wp_update_post($arg);

			fwrite(STDERR, 'Agent: ' . $the_agent_id . PHP_EOL);

		}


		//Images
		if (count((array) $xml_obj->Listing[intval($i)]->Images->image) > 0) {

			$images_arr = (array) $xml_obj->Listing[intval($i)]->Images->image;

			$tmp_arr = array();
			foreach ($images_arr as $image) {
				$id = process_image($image, $the_post_id);
				if ( ! empty($id)) {
					array_push($tmp_arr, $id);
				}
			}

			$array = update_field('field_56c5cba25c846', $tmp_arr, $the_post_id);

		}


	}

}

