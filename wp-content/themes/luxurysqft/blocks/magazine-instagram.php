<?php
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>

<div class="block-content bottom-border instagram-block">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
				<h3><?php _e( 'INSTAGRAM POSTS', 'luxurysqft' ); ?></h3>
				<div class="instagram-plugin">
					<picture>
						<!--[if IE 9]>
						<video style="display: none;"><![endif]-->
						<source
							srcset="<?php echo get_template_directory_uri(); ?>/images/img39.jpg, <?php echo get_template_directory_uri(); ?>/images/img39-2x.jpg 2x">
						<!--[if IE 9]></video><![endif]-->
						<img src="<?php echo get_template_directory_uri(); ?>/images/img39.jpg"
						     alt="image description">
					</picture>
				</div>
			</div>
		</div>
	</div>
</div>