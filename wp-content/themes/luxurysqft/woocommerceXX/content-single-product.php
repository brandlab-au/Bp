<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>
<div class="description-section">
	<div class="container">
		<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" class="row">
			<?php echo woocommerce_breadcrumb(); ?>
			<div class="col-xs-12 col-md-6 col-lg-8">
				<!-- social plugin container -->
				<div class="social-list">
					<div class="holder">
						<span class='st_facebook'></span>
						<span class='st_twitter'></span>
						<span class='st_instagram'></span>
					</div>
				</div>
		
				<?php
					/**
					 * woocommerce_before_single_product_summary hook.
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-4">
				<div class="description">
					<?php if( $brands = wp_get_post_terms( get_the_ID(), 'brand' ) ):
						foreach( $brands as $brand ): ?>
							<span class="name"><span class="post-title"><?php echo $brand->name; ?></span></span>
						<?php endforeach; ?>
					<?php endif; ?>
		
					<?php
						/**
						 * woocommerce_single_product_summary hook.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 */
						do_action( 'woocommerce_single_product_summary' );
					?><?php global $product;
					$dimensions_width = $product->get_width();
					$dimensions_length = $product->get_length();
					$dimensions_height = $product->get_height();
					$material = get_field('material');
					if( !empty($dimensions_height) or !empty($dimensions_length) or !empty($dimensions_width) or !empty($material) or !empty($brands) ): ?>
						<ul class="detail-list">
							<?php if( !empty($dimensions_height) or !empty($dimensions_length) or !empty($dimensions_width) ): ?>
								<li>
									<span class="title"><?php _e( 'DIMENSIONS', 'luxurysqft' ); ?></span>
									<p><?php _e( 'Each frame -', 'luxurysqft' ); ?>
									<?php if( $dimensions_length ): echo 'Lenght '.$dimensions_length. get_option( 'woocommerce_dimension_unit' ).' x '; endif;
									if( $dimensions_width ): echo 'Width '.$dimensions_width. get_option( 'woocommerce_dimension_unit' ).' x '; endif;
									if( $dimensions_height ): echo 'Height '.$dimensions_height. get_option( 'woocommerce_dimension_unit' ); endif; ?>.</p>
								</li>
							<?php endif; ?>
							<?php if( $material ): ?>
								<li>
									<span class="title"><?php _e( 'MATERIAL', 'luxurysqft' ); ?></span>
									<p><?php echo $material; ?></p>
								</li>
							<?php endif; ?>
							<?php if( !empty($brands) ): ?>
								<li>
									<p><a href="<?php echo get_term_link($brands[0], 'brand'); ?>"><?php _e( 'Discover More From This Brand', 'luxurysqft' ); ?></a></p>
								</li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
					<div class="holder-text">
						<h3><?php _e( 'ARCHITECTURAL IMPACT', 'luxurysqft' ); ?></h3>
						<?php the_content(); ?>
					</div>
				
					<?php
						/**
						 * woocommerce_after_single_product_summary hook.
						 *
						 * @hooked woocommerce_output_product_data_tabs - 10
						 * @hooked woocommerce_upsell_display - 15
						 * @hooked woocommerce_output_related_products - 20
						 */
						//do_action( 'woocommerce_after_single_product_summary' );
					?>
				</div>
			</div>
			<meta itemprop="url" content="<?php the_permalink(); ?>" />
		
		</div><!-- #product-<?php the_ID(); ?> -->
	</div>
</div>
<?php echo woocommerce_output_related_products(); ?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
<?php get_template_part( 'blocks/recently-viewed-products' ) ?>
