<!-- cycle carousel -->
<div class="cycle-gallery" data-sticky_column>
	<?php
	if ( $apartment_images = get_field( 'apartment_images', get_the_ID() ) or has_post_thumbnail() ) :?>
		<div class="mask">
			<div class="slideset">
				<?php if ( has_post_thumbnail() ) :
					$image_info = get_x2_image( get_the_ID(), array( 'full', 'full' ) ); ?>
					<div class="slide">
						<a href="<?php echo $image_info['full']; ?>" rel="lightbox1" class="popup_gallery">
							<picture class="lazy">
								<!--[if IE 9]>
								<video style="display: none;"><![endif]-->
								<source
									srcset="<?php echo $image_info['full']; ?>, <?php echo $image_info['full'] ?> 2x">
								<!--[if IE 9]></video><![endif]-->
								<img src="<?php echo $image_info['full']; ?>"
								     alt="<?php echo $image_info['alt'] ?>">
							</picture>
						</a>
					</div>
				<?php endif ?>
				<?php if ( $apartment_images ) :
					foreach ( $apartment_images as $image ) : ?>
						<div class="slide">
							<a href="<?php echo $image['sizes']['thumbnail_936x662']; ?>" rel="lightbox1"
							   class="popup_gallery">
								<picture class="lazy">
									<!--[if IE 9]>
									<video style="display: none;"><![endif]-->
									<source
										srcset="<?php echo $image['sizes']['thumbnail_936x662']; ?>, <?php echo $image['sizes']['thumbnail_1872x1324'] ?> 2x">
									<!--[if IE 9]></video><![endif]-->
									<img src="<?php echo $image['sizes']['thumbnail_936x662']; ?>"
									     alt="<?php echo $image['alt'] ?>">
								</picture>
							</a>
						</div>
					<?php endforeach;
				endif ?>
			</div>
		</div>
		<a class="btn-prev fa fa-angle-left" href="#"></a>
		<a class="btn-next fa fa-angle-right" href="#"></a>
		<div class="info">
			<div class="holder">
				<span class="info-number"><span class="current-num">1</span> of <span class="total-num">9</span></span>
				<a class="zoom fa fa-expand" href="#"></a>
			</div>
		</div>
	<?php else : ?>
		<div class="mask">
			<div class="slideset">
				<div class="slide">
					<picture class="lazy">
						<img src="<?php echo get_template_directory_uri() ?>/images/placeholder.png" alt="">
					</picture>
				</div>
			</div>
		</div>
	<?php endif ?>
</div>
