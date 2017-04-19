<?php
function products_ajax_load(){

	$paged = (isset($_GET['paged'])) ? (int) $_GET['paged'] : 1;
	$args = array(
		'post_type'   => 'product',
		'posts_per_page' => 35,
		'paged' => $paged,
	);
	
	$args['tax_query'] = array('relation' => 'AND');
	if (isset($_REQUEST['parent_cat']) and !empty($_REQUEST['parent_cat']) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'product_cat',
			'field'            => 'slug',
			'terms'            => $_REQUEST['parent_cat'],
		);
	}
	if (isset($_REQUEST['cat']) and !empty($_REQUEST['cat']) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'product_cat',
			'field'            => 'slug',
			'terms'            => $_REQUEST['cat'],
		);
	}
	if (isset($_REQUEST['type']) and !empty($_REQUEST['type']) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'type',
			'field'            => 'slug',
			'terms'            => $_REQUEST['type'],
		);
	}
	if (isset($_REQUEST['brand']) and !empty($_REQUEST['brand']) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'brand',
			'field'            => 'slug',
			'terms'            => $_REQUEST['brand'],
		);
	}
	if (isset($_REQUEST['color']) and !empty($_REQUEST['color']) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'pa_colour',
			'field'            => 'slug',
			'terms'            => $_REQUEST['color'],
		);
	}
	$args['meta_query'] = array('relation' => 'AND');
	if(isset($_REQUEST['price']) and !empty($_REQUEST['price']) ){
		$price = explode('-', $_REQUEST['price']);
		$args['meta_query'][]	= array(
			array(
				'key'		=> '_price',
				'value'   => $price,
				'type'    => 'numeric',
				'compare' =>	'BETWEEN',
			)
		);
	}
	
	$r = new WP_Query( $args);
	if ( $r->have_posts() ) :
	while ( $r->have_posts() ) : $r->the_post();
		get_template_part('blocks/content-product');
	endwhile;
	else:
		get_template_part( 'blocks/not_found' );
	endif; 
	wp_reset_postdata(); 
}