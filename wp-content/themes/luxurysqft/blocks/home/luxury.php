<?php if( have_rows('luxury_interiors') ) : ?>
    <section class="block-content">
	<div class="container">
	  <div class="row">
	    <?php if( $luxury_title = get_field('luxury_title') ) : ?>
		<div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
		    <h2><?php echo $luxury_title ?></h2>
		</div>
	    <?php endif ?>
	    <div class="clearfix"></div>
	    <?php $i = 0;
	    while ( have_rows('luxury_interiors') ) : the_row(); ?>
		<!-- product item -->
		<?php if( $i % 2 == 0 ): ?>
		    <div class="col-xs-12 col-sm-6 col-md-5 col-md-offset-1 text-center post-block">
		<?php else : ?>
		    <div class="col-xs-12 col-sm-6 col-md-5 text-center post-block">
		<?php endif ?>
		    <?php $image = get_sub_field('image');
		    $description = get_sub_field('description');
		    $link_title = get_sub_field('link_title');
		    $shop_link = get_sub_field('shop_link');
		    if( $image ): ?>
			<div class="visual lazy">
				<a href="<?php if( $shop_link ): echo esc_url( $shop_link ); else: echo '#'; endif; ?>">
					<picture>
						<!--[if IE 9]><video style="display: none;"><![endif]-->
						<source srcset="<?php echo $image['sizes']['thumbnail_565x307'] ?>, <?php echo $image['sizes']['thumbnail_1130x614'] ?> 2x">
						<!--[if IE 9]></video><![endif]-->
						<img src="<?php echo $image['sizes']['thumbnail_565x307'] ?>" alt="<?php echo esc_attr( $image['alt'] ) ?>">
					</picture>
				</a>
			</div>
		    <?php endif ?>
		    <?php if( $description or ( $link_title && $shop_link) ) : ?>
			<div class="description">
			    <?php echo wpautop( $description ) ?>
			    <?php if( $link_title && $shop_link ) : ?>
				<a class="btn btn-default" href="<?php echo esc_url( $shop_link )?>"><?php echo $link_title ?></a>
			    <?php endif ?>
			</div>
		    <?php endif ?>
		</div>
	    <?php $i++;
	    endwhile ?>
	  </div>
	</div>
    </section>
<?php endif ?>