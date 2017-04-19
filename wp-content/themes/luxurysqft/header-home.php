<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<!-- set the encoding of your site -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!-- set the viewport width and initial-scale on mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript">
		var pathInfo = {
			base: '<?php echo get_template_directory_uri(); ?>/',
			css: 'css/',
			js: 'js/',
			swf: 'swf/',
		}
	</script>
	<?php wp_head(); ?>
	<link href='https://fonts.googleapis.com/css?family=Wire+One' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,400italic,700' rel='stylesheet'
	      type='text/css'>
</head>

<body <?php body_class(); ?>>


<!-- main container of all the page elements -->
<div id="wrapper" class="wrapper">
	<a class="side-opener" data-burger-menu-id="side-menu" href="#"
	   data-nonce="<?php print wp_create_nonce( 'menu' ); ?>"><span>&nbsp;</span></a>
	<div id="side-menu" class="side-menu">
		<div class="holder">
			<div class="inner">
				<div class="dl-menuwrapper">
					<a class="logo" href="<?php print get_bloginfo( 'url' ); ?>"><?php echo get_bloginfo('name')?></a>
					<a class="buy"
					   href="<?php print get_bloginfo( 'url' ) . '/buy/'; ?>"><?php _e( 'Looking to Buy', 'luxsft' ); ?></a>
					<a class="rent"
					   href="<?php print get_bloginfo( 'url' ) . '/rent/'; ?>"><?php _e( 'Looking to Rent', 'luxsft' ); ?></a>
					<a class="magazine"
					   href="<?php print get_bloginfo( 'url' ) . '/magazine/'; ?>"><?php _e( 'Read Our Magazine', 'luxsft' ); ?></a>
					<a class="shop"
					   href="<?php print get_bloginfo( 'url' ) . '/shop/'; ?>"><?php _e( 'Browse Our Shop', 'luxsft' ); ?></a>
					<div class="small_links">

						<ul class="list-inline">
							<li><a class="link"
							       href="<?php print get_bloginfo( 'url' ) . '/about-us/'; ?>"><?php _e( 'About us', 'luxsft' ); ?></a>
							</li>
							<li class="muted">·</li>
							<li><a class="link"
							       href="<?php print get_bloginfo( 'url' ) . '/contact-us/'; ?>"><?php _e( 'Contact us', 'luxsft' ); ?></a>
							</li>
							<li class="muted">·</li>
							<li><a class="link"
							       href="<?php print get_bloginfo( 'url' ) . '/advertise-with-us/'; ?>"><?php _e( 'Advertise', 'luxsft' ); ?></a>
							</li>
						</ul>

					</div>
				</div>

				<div class="dl-menuwrapper-sub" style="display: none;"></div>
			</div>
		</div>
	</div>
	<div class="side_background"></div>
	<!-- header of the page -->
	<header id="header">
		<nav class="navbar navbar-default">
			<?php $apartments_url = trailingslashit( get_bloginfo( 'url' ) ) . strtolower( get_the_title() ) ?>
			<div class="wrapper">
				<div class="left">
					<div class="left_wrapper clearfix">
						<div class="navbar-header">
							<!-- page logo -->
							<a tabindex="1" class="navbar-brand" href="<?php echo home_url(); ?>">
								<picture>
									<!--[if IE 9]>
									<video style="display: none;"><![endif]-->
									<source srcset="<?php echo get_template_directory_uri(); ?>/images/bank-of-properties.png, <?php echo get_template_directory_uri(); ?>/images/bank-of-properties.png 2x">
									<!--[if IE 9]></video><![endif]-->
									<img src="<?php echo get_template_directory_uri(); ?>/image/bank-of-properties.png"
									     alt="<?php echo esc_attr(get_bloginfo('name')) ?>">
								</picture>
							</a>
						</div>
					</div>
				</div>
				<div class="center">
					<div class="menuwrapper">
						<?php if ( has_nav_menu( 'main' ) ) {
							wp_nav_menu( array(
									'container'      => false,
									'theme_location' => 'main',
									'menu_class'     => 'top-nav',
									'depth'          => 1,
									'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
									'walker'         => new Custom_Walker_Nav_Menu
								)
							);
						} ?>
					</div>

				</div>
				<div class="right">
					<div class="right_wrapper clearfix">

						<ul class="right-menu">
							<?php if ( is_user_logged_in() ) : ?>
								<li>
									<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"><?php _e( 'ACCOUNT', 'luxurysqft' ); ?></a>
								</li>
								<li>
									<a href="<?php echo wp_logout_url(); ?>"><?php _e( 'LOGOUT', 'luxurysqft' ); ?></a>
								</li>
							<?php else : ?>
								<?php if ( get_option( 'users_can_register' ) ) : ?>
									<li>
										<a href="/signup"><?php _e( 'SIGN UP', 'luxurysqft' ); ?></a>
										/
									</li>
								<?php endif ?>
								<li><a href="/signup"><?php _e( 'LOGIN', 'luxurysqft' ); ?></a>
								</li>
							<?php endif ?>
						</ul>

					</div>

				</div>
			</div>

			<?php $class = '';

			if ( strtolower( get_the_title() ) != 'buy' && strtolower( get_the_title() ) != 'rent' ) {
				$class = 'hidden';
			}

			if ( strtolower( get_the_title() ) != 'rent' && strtolower( get_the_title() ) != 'buy' ) {
				$class = 'hidden';
			}

			?>


			<div
				class="wrapper_bottom <?php print isset( $_COOKIE['StorageCookie'] ) && $_COOKIE['StorageCookie'] != '[]' ? '' : $class; ?>">
				<div class="left">
					<div class="left_wrapper">
						<span
							class="saved-items <?php print isset( $_COOKIE['StorageCookie'] ) && $_COOKIE['StorageCookie'] != '[]' ? '' : $class; ?>"><a
								href="<?php echo get_saved_url() ?>"><?php _e( 'SAVED', 'luxurysqft' ); ?> (<strong
									class="amount-items">0</strong>)</a></span>
					</div>
				</div>
				<div class="right <?php print $class; ?>">
					<div class="right_wrapper navigation-list">
						<ul class="form-container form-container-mobile">
							<?php
							$terms = get_terms( 'location', array(
								'hide_empty' => 0
							) );
							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
								<li>
									<select name="city" class="filter-drop">
										<option value="" class="hideme"><?php _e( 'CITY', 'luxurysqft' ); ?></option>
										<?php foreach ( $terms as $term ) :
											if ( $term->parent == 0 )
												continue ?>
											<option
												value="<?php echo $term->term_id ?>" <?php if ( isset( $_GET['city'] ) ) {
												selected( $_GET['city'], $term->term_id );
											} ?>><?php echo $term->name ?></option>
										<?php endforeach ?>
									</select>
								</li>
							<?php endif ?>
							<?php $terms = get_terms( 'area', array(
								'hide_empty' => 0
							) );
							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
								<li>
									<select name="ar" class="filter-drop">
										<option value="" class="hideme"><?php _e( 'AREA', 'luxurysqft' ); ?></option>
										<?php foreach ( $terms as $term ) : ?>
											<option
												value="<?php echo $term->term_id ?>" <?php if ( isset( $_GET['ar'] ) ) {
												selected( $_GET['ar'], $term->term_id );
											} ?>><?php echo $term->name ?></option>
										<?php endforeach ?>
									</select>
								</li>
							<?php endif ?>
							<li>
								<select name="beds" class="filter-drop">
									<option value="" class="hideme"><?php _e( 'BEDS', 'luxurysqft' ); ?></option>
									<option value="1" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 1 );
									} ?>>
										01
									</option>
									<option value="2" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 2 );
									} ?>>
										02
									</option>
									<option value="3" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 3 );
									} ?>>
										03
									</option>
									<option value="4" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 4 );
									} ?>>
										04
									</option>
									<option value="5" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 5 );
									} ?>>
										05
									</option>
									<option value="6" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 6 );
									} ?>>
										06
									</option>
									<option value="7" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 7 );
									} ?>>
										07
									</option>
									<option value="8" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 8 );
									} ?>>
										08
									</option>
									<option value="9" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 9 );
									} ?>>
										09
									</option>
									<option value="10" <?php if ( isset( $_GET['beds'] ) ) {
										selected( $_GET['beds'], 10 );
									} ?>>
										10+
									</option>
								</select>
							</li>
							<li>
								<select name="price" class="filter-drop">
									<option value="" class="hideme"><?php _e( 'PRICE', 'luxurysqft' ); ?></option>
									<option class="price-rent"
									        value="0-1000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '0-1000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										0 - <?php _e( 'AED', 'luxurysqft' ); ?> 1,000
									</option>
									<option class="price-rent"
									        value="1000-2000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '1000-2000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										1,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 2,000
									</option>
									<option class="price-rent"
									        value="2000-3000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '2000-3000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										2,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 3,000
									</option>
									<option class="price-rent"
									        value="3000-4000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '3000-4000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										3,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 4,000
									</option>
									<option class="price-rent"
									        value="4000-5000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '4000-5000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										4,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 5,000
									</option>
									<option class="price-rent"
									        value="5000-6000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '5000-6000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										5,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 6,000
									</option>
									<option class="price-rent"
									        value="6000-7000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '6000-7000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										6,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 7,000
									</option>
									<option class="price-rent"
									        value="7000-8000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '7000-8000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										7,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 8,000
									</option>
									<option class="price-rent"
									        value="8000-9000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '8000-9000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										8,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 9,000
									</option>
									<option class="price-rent"
									        value="9000-10000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '9000-10000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										9,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 10,000
									</option>
									<option class="price-rent"
									        value="10000-9999999999" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '10000-9999999999' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										10,000+
									</option>

									<option class="price-buy"
									        value="0-3000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '0-3000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										0 - <?php _e( 'AED', 'luxurysqft' ); ?> 3,000
									</option>
									<option class="price-buy"
									        value="3000-5000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '3000-5000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										3,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 5,000
									</option>
									<option class="price-buy"
									        value="5000-8000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '5000-8000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										5,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 8,000
									</option>
									<option class="price-buy"
									        value="8000-10000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '8000-10000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										8,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 10,000
									</option>
									<option class="price-buy"
									        value="10000-15000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '10000-15000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										10,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 15,000
									</option>
									<option class="price-buy"
									        value="15000-20000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '15000-20000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										15,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 20,000
									</option>
									<option class="price-buy"
									        value="20000-50000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '20000-50000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										20,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 50,000
									</option>
									<option class="price-buy"
									        value="50000-100000" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '50000-100000' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										50,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 100,000
									</option>
									<option class="price-buy"
									        value="100000-9999999999" <?php if ( isset( $_GET['price'] ) ) {
										selected( $_GET['price'], '10000-9999999999' );
									} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
										100,000+
									</option>
								</select>
							</li>
						</ul>

						<form action="<?php echo $apartments_url ?>" class="" method="get">
							<ul class="form-container">
								<?php
								$terms = get_terms( 'location', array(
									'hide_empty' => 0
								) );

								$city = 'CITY';

								if ( isset( $_GET['city'] ) ) {
									foreach ( $terms as $term ) {
										if ( $term->term_id == $_GET['city'] ) {
											$city = $term->name;
										}
									}
								}

								if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
									<li>
										<a href="#"
										   class="jcf-select jcf-unselectable jcf-select-filter-drop filter-dropdown">
												<span class="jcf-select-text">
													<span class="jcf-option-hideme "><?php print $city; ?></span></span>
											<span class="jcf-select-opener"></span>
										</a>

										<div class="dropdown">

											<ul class="filter-drop">
												<?php foreach ( $terms as $term ) :
													if ( $term->parent == 0 )
														continue ?>
													<li value="<?php echo $term->term_id ?>" <?php if ( isset( $_GET['city'] ) ) {
														selected( $_GET['city'], $term->term_id );
													} ?>>
														<a href="#" class="filter" data-filter="city"
														   data-value="<?php echo $term->term_id ?>"><?php echo $term->name ?></a>
													</li>
												<?php endforeach ?>
											</ul>

										</div>
									</li>
								<?php endif ?>
								<?php $terms = get_terms( 'area', array(
									'hide_empty' => 0
								) );

								$area = 'AREA';

								if ( isset( $_GET['ar'] ) ) {
									foreach ( $terms as $term ) {
										if ( $term->term_id == $_GET['ar'] ) {
											$area = $term->name;
										}
									}
								}

								if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
									<li>
										<a href="#"
										   class="jcf-select jcf-unselectable jcf-select-filter-drop filter-dropdown">
												<span class="jcf-select-text">
													<span class="jcf-option-hideme "><?php print $area; ?></span></span>
											<span class="jcf-select-opener"></span>
										</a>
										<?php
										$total = count( $terms );
										$count = round( $total / 2 );
										?>
										<div class="dropdown clearfix dropdown-two-column">
											<?php
											foreach ( $terms as $key => $term ) {

												if ( $key == 0 ) {
													print '<div class="half"><ul class="filter-drop">';
												}
												if ( $key < $count ) {
													print '<li><a href="#" class="filter" data-filter="area"
														   data-value="' . $term->term_id . '">' . $term->name . '</a></li>';
												}

												if ( $key == $count ) {
													print '</ul></div>';
												}

												//Second half
												if ( $key == ( $total - $count ) ) {
													print '<div class="half"><ul class="filter-drop">';
												}

												if ( $key < $total && $key > $count ) {
													print '<li><a href="#" class="filter" data-filter="area"
														   data-value="' . $term->term_id . '">' . $term->name . '</a></li>';
												}

												if ( $key == $total && $key > $count ) {
													print '</ul></div>';
												}
												?>
											<?php } ?>
										</div>
									</li>
								<?php endif ?>
								<li>
									<a href="#"
									   class="jcf-select jcf-unselectable jcf-select-filter-drop filter-dropdown">
												<span class="jcf-select-text">
													<span class="jcf-option-hideme ">BEDS</span></span>
										<span class="jcf-select-opener"></span>
									</a>
									<ul class="dropdown">
										<li>
											<a class="label">Min Bedrooms</a>
											<select name="beds" class="filter-drop-select select_filter_big"
											        data-filter="beds">
												<option value=""
												        class="hideme"><?php _e( 'BEDS', 'luxurysqft' ); ?></option>
												<option value="1" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 1 );
												} ?>>
													01
												</option>
												<option value="2" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 2 );
												} ?>>
													02
												</option>
												<option value="3" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 3 );
												} ?>>
													03
												</option>
												<option value="4" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 4 );
												} ?>>
													04
												</option>
												<option value="5" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 5 );
												} ?>>
													05
												</option>
												<option value="6" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 6 );
												} ?>>
													06
												</option>
												<option value="7" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 7 );
												} ?>>
													07
												</option>
												<option value="8" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 8 );
												} ?>>
													08
												</option>
												<option value="9" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 9 );
												} ?>>
													09
												</option>
												<option value="10" <?php if ( isset( $_GET['beds'] ) ) {
													selected( $_GET['beds'], 10 );
												} ?>>
													10+
												</option>
											</select>

										</li>
									</ul>
								</li>
								<li>
									<a href="#"
									   class="jcf-select jcf-unselectable jcf-select-filter-drop filter-dropdown">
												<span class="jcf-select-text">
													<span class="jcf-option-hideme ">PRICE</span></span>
										<span class="jcf-select-opener"></span>
									</a>
									<div class="dropdown">
										<a class="label">Price</a>
										<select name="price" class="filter-drop select_filter_big" data-filter="price">
											<option value=""
											        class="hideme"><?php _e( 'PRICE', 'luxurysqft' ); ?></option>
											<option class="price-rent"
											        value="0-1000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '0-1000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												0 - <?php _e( 'AED', 'luxurysqft' ); ?> 1,000
											</option>
											<option class="price-rent"
											        value="1000-2000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '1000-2000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												1,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 2,000
											</option>
											<option class="price-rent"
											        value="2000-3000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '2000-3000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												2,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 3,000
											</option>
											<option class="price-rent"
											        value="3000-4000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '3000-4000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												3,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 4,000
											</option>
											<option class="price-rent"
											        value="4000-5000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '4000-5000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												4,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 5,000
											</option>
											<option class="price-rent"
											        value="5000-6000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '5000-6000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												5,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 6,000
											</option>
											<option class="price-rent"
											        value="6000-7000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '6000-7000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												6,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 7,000
											</option>
											<option class="price-rent"
											        value="7000-8000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '7000-8000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												7,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 8,000
											</option>
											<option class="price-rent"
											        value="8000-9000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '8000-9000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												8,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 9,000
											</option>
											<option class="price-rent"
											        value="9000-10000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '9000-10000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												9,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 10,000
											</option>
											<option class="price-rent"
											        value="10000-9999999999" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '10000-9999999999' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												10,000+
											</option>

											<option class="price-buy"
											        value="0-3000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '0-3000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												0 - <?php _e( 'AED', 'luxurysqft' ); ?> 3,000
											</option>
											<option class="price-buy"
											        value="3000-5000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '3000-5000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												3,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 5,000
											</option>
											<option class="price-buy"
											        value="5000-8000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '5000-8000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												5,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 8,000
											</option>
											<option class="price-buy"
											        value="8000-10000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '8000-10000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												8,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 10,000
											</option>
											<option class="price-buy"
											        value="10000-15000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '10000-15000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												10,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 15,000
											</option>
											<option class="price-buy"
											        value="15000-20000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '15000-20000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												15,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 20,000
											</option>
											<option class="price-buy"
											        value="20000-50000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '20000-50000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												20,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 50,000
											</option>
											<option class="price-buy"
											        value="50000-100000" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '50000-100000' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												50,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 100,000
											</option>
											<option class="price-buy"
											        value="100000-9999999999" <?php if ( isset( $_GET['price'] ) ) {
												selected( $_GET['price'], '10000-9999999999' );
											} ?>><?php _e( 'AED', 'luxurysqft' ); ?>
												100,000+
											</option>
										</select>
									</div>
								</li>
								<?php if ( isset( $_GET['filter'] ) && $_GET['filter'] == 1 ) : ?>
									<li><input type="reset" value="reset"
									           onclick="filter_reset( '<?php echo $apartments_url ?>' ); return false;">
									</li>
								<?php endif ?>
							</ul>

							<?php

							$type = '';

							if ( strtolower( get_the_title() ) == 'buy' ) {
								$type = 'rb';
							}

							if ( strtolower( get_the_title() ) == 'rent' ) {
								$type = 'rr';
							}

							?>
							<input type="hidden" id="buy" name="buy"
							       value="<?php print isset( $type ) ? $type : ''; ?>"/>
							<input type="hidden" id="city" name="city"
							       value="<?php print isset( $_GET['city'] ) ? $_GET['city'] : ''; ?>"/>
							<input type="hidden" id="city" name="city"
							       value="<?php print isset( $_GET['city'] ) ? $_GET['city'] : ''; ?>"/>
							<input type="hidden" id="area" name="ar"
							       value="<?php print isset( $_GET['ar'] ) ? $_GET['ar'] : ''; ?>"/>
							<input type="hidden" name="filter" value="1">
						</form>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<div class="banner-home">
		<div class="title_wrapper">
			<h1 class="title" style="opacity: 1;">Making dreams reality</h1>
			<a class="btn btn-default white"
			   href="<?php print get_bloginfo( 'url' ); ?>/subscribe-to-our-magazine/">SUBSCRIBE NOW</a>
		</div>
		<?php $type = get_field( 'type' );
		$video      = get_field( 'video' );
		if ( ! empty( $type ) and $type == 'video' and ! empty( $video ) ): ?>
			<img src="<?php echo get_template_directory_uri(); ?>/images/transparent.gif" alt="image description"
			     width="1900" height="722">
			<?php echo $video; ?>
		<?php else: ?>
			<picture class="lazy">
				<?php if ( has_post_thumbnail() ) :
					$image_info = get_x2_image( get_the_ID(), array( 'full', 'full' ) ); ?>

					<!--[if IE 9]>
					<video style="display: none;"><![endif]-->
					<source srcset="<?php echo $image_info['full'] ?>, <?php echo $image_info['full'] ?> 2x">
					<!--[if IE 9]></video><![endif]-->
					<img src="<?php echo $image_info['full'] ?>" alt="<?php echo $image_info['alt'] ?>">
				<?php else : ?>
					<!--[if IE 9]>
					<video style="display: none;"><![endif]-->
					<source
						srcset="<?php echo get_template_directory_uri(); ?>/images/img1.jpg, <?php echo get_template_directory_uri(); ?>/images/img1-2x.jpg 2x">
					<!--[if IE 9]></video><![endif]-->
					<img src="<?php echo get_template_directory_uri(); ?>/img1.jpg" alt="image description">
				<?php endif ?>
			</picture>
		<?php endif; ?>
	</div>
	<!-- contain main informative part of the site -->
	<main id="main" role="main">
