<?php if( $map = get_field( 'map' ) ) : ?>
    <!-- cycle carousel -->
    <div class="cycle-gallery">
	<div class="mask">
	    <div class="slideset">
		<div class="slide">
		    <?php /*<a href="#popup1" rel="lightbox1">*/ ?>
			<div class="map-holder">
			    <div id="popup1" class="map-popup">
				<img src="<?php echo get_template_directory_uri() ?>/images/map.png" alt="image description" />
				<div class="map-box">
				    <div class="map-canvas" data-markers="<?php echo add_query_arg( 'map', 'json', get_permalink() ) ?>" data-styles="<?php echo get_template_directory_uri() ?>/map/styles.json"></div>
				</div>
			    </div>
			</div>
		    <?php /*</a>*/ ?>
		</div>
	    </div>
	</div>
	<?php /*
	<a class="btn-prev fa fa-angle-left" href="#"></a>
	<a class="btn-next fa fa-angle-right" href="#"></a>
	<div class="info">
	    <div class="holder">
		<span class="info-number"><span class="current-num">1</span> of <span class="total-num">9</span></span>
		<a class="zoom fa fa-expand" href="#"></a>
	    </div>
	</div>
	*/ ?>
    </div>
<?php endif ?>