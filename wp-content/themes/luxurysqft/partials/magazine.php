<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>
<div class="owl-carousel">

	<?php foreach ( get_categories() as $cat ) :
		if ( strpos( strtolower( $cat->name ), 'magazine' ) !== false ) : ?>
			<div class="item">

				<div class="top clearfix">
					<a href="#" class="back" data-link="main">
						<i class="fa fa-long-arrow-left"
						   aria-hidden="true"></i> <?php _e( 'Go back', 'luxsft' ); ?>
					</a>
					<a href="#" class="read_now read_magazine"
					   data-id="<?php print $cat->term_id; ?>"><?php _e( 'Read now', 'luxsft' ); ?></a>
				</div>

				<div class="center">
					<div>
						<?php

						$img_src = get_field( 'magazine_image', $cat );

						if ( $img_src ) { ?>

							<img src="<?php print $img_src; ?>"/>

						<?php } else { ?>

							<img
								src="https://placeholdit.imgix.net/~text?txtsize=33&txt=400%C3%97450&w=400&h=450"/>

						<?php }


						?>
					</div>
				</div>
				<div class="bottom clearfix">
					<div class="left">
												<span
													class="title"><?php print get_field( 'magazine_title', $cat ); ?></span>
												<span
													class="date"><?php

													$date = get_field( 'magazine_date', $cat );

													if ( $date ) {
														$old_date_timestamp = strtotime( $date );
														print date( 'F Y', $old_date_timestamp );
													}

													?></span>
					</div>
					<div class="right">
						<a class="btn btn-default"
						   href="<?php print get_bloginfo( 'url' ); ?>/subscribe-to-our-magazine/">SUBSCRIBE</a>
					</div>
				</div>
			</div>
			<?php
		endif;
	endforeach; ?>
</div>