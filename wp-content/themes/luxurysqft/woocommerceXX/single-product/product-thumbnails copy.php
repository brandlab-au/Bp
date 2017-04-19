<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();

if ( $attachment_ids or has_post_thumbnail() ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	?>
	<div class="cycle-gallery">
		<div class="mask">
			<div class="slideset">
				<?php if ( has_post_thumbnail() ) {
					$image_link = wp_get_attachment_url( get_post_thumbnail_id() );
					$image_caption 	= esc_attr( get_post_field( 'post_excerpt', get_post_thumbnail_id()) );
					$image1 = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail_936x662' );
					$image2 = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail_1872x1324' ); ?>
					
					<div class="slide">
						<a href="<?php echo $image_link; ?>" rel="lightbox1">
							<picture class="lazy">
								<!--[if IE 9]><video style="display: none;"><![endif]-->
								<source srcset="<?php echo $image1[0]; ?>, <?php echo $image2[0]; ?> 2x">
								<!--[if IE 9]></video><![endif]-->
								<img src="<?php echo $image1[0] ?>" alt="<?php echo $image_caption; ?>">
							</picture>
						</a>
					</div>
				<?php } ?>
				<?php foreach ( $attachment_ids as $attachment_id ) {
		
					$classes = array( 'zoom' );
		
					if ( $loop === 0 || $loop % $columns === 0 )
						$classes[] = 'first';
		
					if ( ( $loop + 1 ) % $columns === 0 )
						$classes[] = 'last';
		
					$image_link = wp_get_attachment_url( $attachment_id );
		
					if ( ! $image_link )
						continue;
		
					$image_title 	= esc_attr( get_the_title( $attachment_id ) );
					$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );
		
					$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
						'title'	=> $image_title,
						'alt'	=> $image_title
						) );
			
					$image_class = esc_attr( implode( ' ', $classes ) );
					$image1 = wp_get_attachment_image_src( $attachment_id, 'thumbnail_936x662' );
					$image2 = wp_get_attachment_image_src( $attachment_id, 'thumbnail_1872x1324' );?>
					
					<div class="slide">
						<a href="<?php echo $image_link; ?>" rel="lightbox1">
							<picture class="lazy">
								<!--[if IE 9]><video style="display: none;"><![endif]-->
								<source srcset="<?php echo $image1[0]; ?>, <?php echo $image2[0]; ?> 2x">
								<!--[if IE 9]></video><![endif]-->
								<img src="<?php echo $image1[0] ?>" alt="<?php echo $image_caption; ?>">
							</picture>
						</a>
					</div>
					<?php $loop++;
				}

			?></div>
		</div>
		<a class="btn-prev fa fa-angle-left" href="#"></a>
		<a class="btn-next fa fa-angle-right" href="#"></a>
		<div class="info">
			<div class="holder">
				<span class="info-number"><span class="current-num">1</span> of <span class="total-num"><?php echo $loop; ?></span></span>
				<a class="zoom fa fa-expand" href="#"></a>
			</div>
		</div>
	</div>
	<?php
} ?>