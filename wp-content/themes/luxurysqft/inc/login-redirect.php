<?php

add_action('wp_logout','go_login_page');
function go_login_page(){
	$login_page = get_field('login_page', 'option');
	if(!$login_page):
		$login_page = 'http://luxurysqft.stgng.com/signup/';
	endif;
	wp_redirect( $login_page );
	exit();
}