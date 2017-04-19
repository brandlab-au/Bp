<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see        http://docs.woothemes.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.0.0
 */
?>

<?php
$title = '';
if ( is_product_category() || is_product_tag() || is_product_taxonomy() ) {
	global $product;

	$obj   = get_queried_object();
	$title = $obj->name ? $obj->name : '';

} ?>

<?php if ( !is_product() ) { ?>
	<div class="heading-holder">
		<h2><?php _e( 'Store', 'luxsft' ); ?><?php
			if ( ! empty( $title ) ) {
				print '<span class="link">' . $title . '</span>';
			}
			?></h2>
	</div>
<?php } ?>

<div class="col-xs-12 col-md-12 text-center">
	<!-- products list -->
	<ul class="store-list" id="ajax-target">
