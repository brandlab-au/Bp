<?php
/*
Template Name: Home Template
*/
get_header( 'home' ); ?>

<?php //get_template_part( 'blocks/saved' ) ?>

<?php get_template_part( 'blocks/home/apartments' ) ?>

<?php get_template_part( 'blocks/home/advertisement' ) ?>

<?php get_template_part( 'blocks/home/luxury' ) ?>

<?php get_template_part( 'blocks/home/products' ) ?>

<?php get_template_part( 'blocks/home/subscribe' ) ?>

<?php get_template_part( 'blocks/home/info' ) ?>

<?php get_footer(); ?>