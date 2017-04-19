<?php $type = get_field( 'type' );
$bedrooms = get_field( 'bedrooms' );
$toilet = get_field( 'toilet' );
$bathrooms = get_field( 'bathrooms' );
$sqft = get_field( 'sqft' );
$parking = get_field( 'parking' );
if( $type or $bedrooms or $toilet or $bathrooms or $sqft or $parking ) : ?>
    <ul class="description-list">
	<?php if( $type ) : ?>
	    <li>
		<div class="visual">
		    <picture>
			<!--[if IE 9]><video style="display: none;"><![endif]-->
			<source srcset="<?php echo get_template_directory_uri() ?>/images/img16.png, <?php echo get_template_directory_uri() ?>/images/img16-2x.png 2x">
			<!--[if IE 9]></video><![endif]-->
			<img src="<?php echo get_template_directory_uri() ?>/img16.png" alt="image description" width="20" height="18">
		    </picture>
		</div>
		<span class="text"><?php echo get_apartment_type( $type ) ?></span>
	    </li>
	<?php endif ?>
	<?php if( $sqft ) : ?>
	    <li>
		<div class="visual">
		    <picture>
			<!--[if IE 9]><video style="display: none;"><![endif]-->
			<source srcset="<?php echo get_template_directory_uri() ?>/images/img17.png, <?php echo get_template_directory_uri() ?>/images/img17-2x.png 2x">
			<!--[if IE 9]></video><![endif]-->
			<img src="<?php echo get_template_directory_uri() ?>/img17.png" alt="image description" width="18" height="18">
		    </picture>
		</div>
		<span class="text"><?php echo $sqft ?> <?php _e( 'sq.ft', 'luxurysqft' ); ?></span>
	    </li>
	<?php endif ?>
	<?php if( $parking ) : ?>
	    <li>
		<div class="visual">
		    <picture>
			<!--[if IE 9]><video style="display: none;"><![endif]-->
			<source srcset="<?php echo get_template_directory_uri() ?>/images/img18.png, <?php echo get_template_directory_uri() ?>/images/img18-2x.png 2x">
			<!--[if IE 9]></video><![endif]-->
			<img src="<?php echo get_template_directory_uri() ?>/img18.png" alt="image description" width="34" height="19">
		    </picture>
		</div>
		<span class="text"><?php echo $parking ?> <?php _e( 'Parking', 'luxurysqft' ); ?></span>
	    </li>
	<?php endif ?>
	<?php if( $bedrooms ) : ?>
	    <li>
		<div class="visual">
		    <picture>
			<!--[if IE 9]><video style="display: none;"><![endif]-->
			<source srcset="<?php echo get_template_directory_uri() ?>/images/img19.png, <?php echo get_template_directory_uri() ?>/images/img19-2x.png 2x">
			<!--[if IE 9]></video><![endif]-->
			<img src="<?php echo get_template_directory_uri() ?>/img19.png" alt="image description" width="23" height="15">
		    </picture>
		</div>
		<span class="text"><?php echo $bedrooms ?> <?php _e( 'bedroom', 'luxurysqft' ); ?><?php if( $bedrooms != 1 ) _e( 's', 'luxurysqft' ) ?></span>
	    </li>
	<?php endif ?>
	<?php if( $toilet ) : ?>
	    <li>
		<div class="visual">
		    <picture>
			<!--[if IE 9]><video style="display: none;"><![endif]-->
			<source srcset="<?php echo get_template_directory_uri() ?>/images/img20.png, <?php echo get_template_directory_uri() ?>/images/img20-2x.png 2x">
			<!--[if IE 9]></video><![endif]-->
			<img src="<?php echo get_template_directory_uri() ?>/img20.png" alt="image description" width="20" height="20">
		    </picture>
		</div>
		<span class="text"><?php echo $toilet ?> <?php _e( 'Toilet', 'luxurysqft' ); ?></span>
	    </li>
	<?php endif ?>
	<?php if( $bathrooms ) : ?>
	    <li>
		<div class="visual">
		    <picture>
			<!--[if IE 9]><video style="display: none;"><![endif]-->
			<source srcset="<?php echo get_template_directory_uri() ?>/images/img21.png, <?php echo get_template_directory_uri() ?>/images/img21-2x.png 2x">
			<!--[if IE 9]></video><![endif]-->
			<img src="<?php echo get_template_directory_uri() ?>/img21.png" alt="image description" width="25" height="23">
		    </picture>
		</div>
		<span class="text"><?php echo $bathrooms ?> <?php _e( 'Bathroom', 'luxurysqft' ); ?><?php if( $bathrooms != 1 ) _e( 's', 'luxurysqft' ) ?></span>
	    </li>
	<?php endif ?>
    </ul>
<?php endif ?>