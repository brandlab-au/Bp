<?php if( $image = get_field( 'subscribe_image' ) ) :
    $subscribe = get_field( 'subscribe' );
    $subscribe_button_title = get_field( 'subscribe_button_title' );
    $subscribe_url = get_field( 'subscribe_url' );?>
    <section class="block-content">
	<div class="subscribe-block">
	    <div class="container">
		<div class="row">
		    <div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
			<picture class="lazy">
			    <!--[if IE 9]><video style="display: none;"><![endif]-->
			    <source srcset="<?php echo $image['sizes']['thumbnail_1175x386'] ?>, <?php echo $image['sizes']['thumbnail_2350x772'] ?> 2x">
			    <!--[if IE 9]></video><![endif]-->
			    <img src="<?php echo $image['sizes']['thumbnail_1175x386'] ?>" alt="<?php echo $image['alt'] ?>">
			</picture>
			<?php if( $subscribe or ( $subscribe_button_title && $subscribe_url ) ) : ?>
			    <div class="text-block">
				<div class="holder">
				    <?php if( $subscribe ) : ?>
					<h3><?php echo $subscribe ?></h3>
				    <?php endif ?>
				    <?php if( $subscribe_button_title && $subscribe_url ) : ?>
					<a class="btn btn-default white" href="<?php echo esc_url( $subscribe_url ) ?>"><?php echo $subscribe_button_title ?></a>
				    <?php endif ?>
				</div>
			    </div>
			<?php endif ?>
		    </div>
		</div>
	    </div>
	</div>
    </section>
<?php endif ?>