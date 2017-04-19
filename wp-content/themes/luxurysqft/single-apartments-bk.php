<?php if ( isset( $_GET['map'] ) && $_GET['map'] == 'json' ) {
	$map = get_field( 'map' );

	$temp = array(
		'markers' => array(
			array(
				'location' => array(
					$map['lat'],
					$map['lng']
				),
			),
		),
	);
	header( 'Content-Type: application/json' );
	echo json_encode( $temp );
	exit;
}
get_header(); ?>
<?php if ( is_singular( 'apartments' ) ) :
	global $post;

	$id        = $post->ID;
	$author_id = $post->post_author;

	$user_info = get_userdata( absint( $author_id ) );
	$mail      = $user_info->user_email;
	$name      = $user_info->display_name;

	?>
	<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModal3">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" class="contact-form">
						<fieldset>
							<div class="contact-modal">
								<h2><?php _e( 'CONTACT BROKER', 'luxurysqft' ); ?></h2>
								<div class="row-form">
									<div class="radio-block">
										<span class="title"><?php _e( 'Title', 'luxurysqft' ); ?>:</span>
										<div class="holder">
											<div class="inner">
												<input id="radio3" value="mr" type="radio" name="title">
												<label for="radio3"><?php _e( 'MR', 'luxurysqft' ); ?></label>
											</div>
											<div class="inner">
												<input id="radio4" value="ms" type="radio" name="title">
												<label for="radio4"><?php _e( 'MS', 'luxurysqft' ); ?></label>
											</div>
											<div class="inner">
												<input id="radio5" value="mrs" type="radio" name="title">
												<label for="radio5"><?php _e( 'MRS', 'luxurysqft' ); ?></label>
											</div>
										</div>
									</div>
								</div>
								<div class="row-form">
									<label for="contact1"><?php _e( 'First Name', 'luxurysqft' ); ?></label>
									<input id="contact1" name="first_name" type="text">
								</div>
								<div class="row-form">
									<label for="contact5"><?php _e( 'Last Name', 'luxurysqft' ); ?></label>
									<input id="contact5" name="last_name" type="text">
								</div>
								<div class="row-form">
									<label for="contact3"><?php _e( 'Telephone', 'luxurysqft' ); ?></label>
									<input id="contact3" name="telephone" type="tel">
								</div>
								<div class="row-form">
									<label for="contact2"><?php _e( 'Email', 'luxurysqft' ); ?></label>
									<input id="contact2" name="email" type="email" data-required="true">
								</div>
								<div class="row-form">
									<label for="contact4"><?php _e( 'Message', 'luxurysqft' ); ?></label>
									<?php
									$price_text = '';
									if ( $price = get_field( 'price', $id ) ) :

										if ( $price == 'POR' ) {

											$price_text = '( Price on request )';

										} else {

											$price_int  = absint( $price );
											$price_tmp  = number_format( $price_int, 0, ',', ',' );
											$price_text = __( 'AED', 'luxurysqft' ) . ' ' . $price_tmp;
										}
									else:

										$price_text = '( Price on request )';

									endif; ?>

									<textarea id="contact4" name="message" cols="10" rows="10"
									          placeholder="<?php _e( 'Dear Agent, I\'m interested in ' . get_the_title() . ' ' . (string) get_town( get_the_ID(), $country_info['term_id'] ) . ' (reference number ' . get_field( 'field_56c5cd6984248', $id ) . ') listed on ' . get_bloginfo( 'url' ) . ' for ' . $price_text . '. Please contact me, I would like to know more.', 'luxurysqft' ); ?>"></textarea>
								</div>
								<input type="hidden" name="broker"
								       value="<?php echo ( is_singular( 'apartments' ) && is_object( $post ) ) ? $post->post_author : null ?>">
								<input type="hidden" name="post_id"
								       value="<?php echo ( is_singular( 'apartments' ) && is_object( $post ) ) ? $post->ID : null ?>">
								<input class="btn btn-default" type="submit" value="SUBMIT">
								<?php $privacy_link = get_field( 'privacy_link', 'option' );
								$privacy_link = $privacy_link ? $privacy_link : '#' ?>
								<p><?php _e( 'By submitting this form, I certify that I accept the ', 'luxurysqft' ); ?>
									<a
										href="<?php echo $privacy_link ?>"><?php _e( 'Privacy Notice', 'luxurysqft' ); ?></a>
								</p>
							</div>
							<div class="thanks-lightbox">
								<h2><?php _e( 'THANK YOU', 'luxurysqft' ); ?></h2>
								<p>The agent will contact you shortly, or you may contact the number below.</p>
								<span class="ref-title">REFERENCE NUMBER</span>
								<a class="tel-number"
								   href="tel:<?php print get_field( 'field_56c5cd6984248', $id ); ?>"><?php print get_field( 'field_56c5cd6984248', $id ); ?></a>
								<br/>
								<a class="phone"
								   href="tel:<?php print get_user_meta( $author_id, 'agent_phone' )[0]; ?>"><?php print get_user_meta( $author_id, 'agent_phone' )[0]; ?></a>
								<br/>
								<a class="email"
								   href="mailto:<?php print $mail; ?>"><?php print $mail; ?></a>
								<div class="data-info">
									<div class="logo-holder">
										<picture>
											<img src="<?php print get_user_meta( $author_id, 'company_logo' )[0]; ?>"
											     alt="image description">
										</picture>
									</div>
									<span
										class="name-logo"><?php print get_user_meta( $author_id, 'company_name' )[0]; ?></span>
									<span class="name"><?php print $name; ?></span>
									<a class="view fa fa-bars" href="#">View all our properties for sale</a>
								</div>

							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php

//Disable property bar;
if ( false ) : ?>
	<div class="property-bar">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<a class="back-link"
					   href="<?php echo get_apartments_url() ?>"><?php _e( 'back', 'luxurysqft' ); ?></a>
					<ul class="property-list single-apartments">
						<li<?php if ( ! isset( $_GET['view'] ) ) : ?> class="active"<?php endif ?>><a
								href="<?php echo get_permalink( get_the_ID() ) ?>"><?php _e( 'Description', 'luxurysqft' ); ?></a>
						</li>
						<li<?php if ( isset( $_GET['view'] ) ) : ?> class="active"<?php endif ?>><a
								href="<?php echo add_query_arg( 'view', 'map', get_permalink( get_the_ID() ) ) ?>"><?php _e( 'Map', 'luxurysqft' ); ?></a>
						</li>
						<li><a onclick="window.print(); return false;"
						       href="#"><?php _e( 'Print', 'luxurysqft' ); ?></a>
						</li>
						<li><a data-product-id="<?php the_ID() ?>" href="#"><?php _e( 'Save', 'luxurysqft' ); ?></a>
						</li>
						<li><span class="st_sharethis_custom"><?php _e( 'Share', 'luxurysqft' ); ?></span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

<?php endif; ?>

<?php if ( have_posts() ) :
	the_post();
	$country_info = get_country( get_the_ID() ); ?>
	<div class="description-section">
		<div class="container" data-sticky_parent>
			<div class="row">
				<div class="col-xs-12">
					<?php if ( function_exists( 'bcn_display_list' ) ) : ?>
						<!-- breadcrumbs -->
						<ul class="breadcrumbs">
							<?php bcn_display_list() ?>
						</ul>
					<?php endif ?>
				</div>
				<div class="clearfix"></div>
				<div class="col-xs-12 col-md-8 col-lg-8" style="position: inherit;">

					<?php if ( isset( $_GET['view'] ) && $_GET['view'] == 'map' ) {
						get_template_part( 'blocks/single-apartments/map' );
					} else {
						get_template_part( 'blocks/single-apartments/images' );
					} ?>

				</div>
				<div class="col-xs-12 col-md-4 col-lg-4">
					<!-- product item -->
					<div class="description">
						<span class="name"><span
								class="post-title"><?php the_title() ?></span><?php echo get_town( get_the_ID(), $country_info['term_id'] ) ?></span>
						<?php if ( has_excerpt() ) : ?>
							<span class="announcement"><?php echo nl2br( strip_tags( get_the_excerpt() ) ) ?></span>
						<?php endif ?>
						<?php if ( $price = get_field( 'price' ) ) :

							if ( $price == 'POR' ) { ?>
								<strong
									class="price"><?php _e( 'Price on request' ); ?></strong>
							<?php } else { ?>
								<strong
									class="price"><?php _e( 'AED', 'luxurysqft' ); ?><?php add_delimiter( $price ) ?></strong>
							<?php }
							?>
						<?php else: ?>
							<strong
								class="price"><?php _e( 'Price on request' ); ?></strong>
						<?php endif; ?>
						<a href="#" class="btn btn-default" data-toggle="modal"
						   data-target="#myModal3"><?php _e( 'CONTACT BROKER', 'luxurysqft' ); ?></a>

						<?php get_template_part( 'blocks/single-apartments/apartments_info' ); ?>

						<?php $reference_no = get_field( 'reference_no' );
						if ( $country_info['country_links'] or $reference_no ) : ?>
							<ul class="data-list">
								<?php if ( $reference_no ) : ?>
									<li><?php _e( 'Reference No', 'luxurysqft' ); ?>:
										<mark><?php echo $reference_no ?></mark>
									</li>
								<?php endif ?>
								<?php if ( $country_info['country_links'] ) : ?>
									<li><?php _e( 'Country', 'luxurysqft' ); ?>
										: <?php echo $country_info['country_links'] ?></li>
								<?php endif ?>
							</ul>
						<?php endif ?>

						<div class="holder-text">
							<?php the_content() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>

<?php get_template_part( 'blocks/single-apartments/similar_properties' ); ?>

<?php get_template_part( 'blocks/single-apartments/recently_viewed' ); ?>

<?php get_footer(); ?>