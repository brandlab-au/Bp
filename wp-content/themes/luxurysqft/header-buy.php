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
	<link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,400italic,700' rel='stylesheet' type='text/css'>
</head>
<body <?php body_class(); ?>>
	<!-- main container of all the page elements -->
	<div id="wrapper" class="wrapper">
		<a class="side-opener" data-burger-menu-id="side-menu" href="#"><span>&nbsp;</span></a>
		<!-- header of the page -->
		<header id="header">
			<nav class="navbar navbar-default">
				<div class="container">
					<div class="header-holder">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav" aria-expanded="false">
							<span class="sr-only"><?php _e( 'Toggle navigation', 'luxurysqft' ); ?></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<div id="side-menu" class="side-menu">
							<div class="holder">
								<div class="inner">
									<div class="dl-menuwrapper">
										<?php if( has_nav_menu( 'main' ) )
											wp_nav_menu( array(
												'container' => false,
												'theme_location' => 'main',
												'menu_class'     => 'dl-menu nav-side nav navbar-nav',
												'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
												'walker'         => new Custom_Walker_Nav_Menu
												)
										); ?>
									</div>
									<?php // get_template_part( 'blocks/filter' ); ?>
								</div>
							</div>
						</div>
						<div class="navbar-header">
							<!-- page logo -->
							<a tabindex="1" class="navbar-brand" href="<?php echo home_url(); ?>">
								<picture>
								<!--[if IE 9]><video style="display: none;"><![endif]-->
								<source srcset="<?php echo get_template_directory_uri(); ?>/images/logo.png, <?php echo get_template_directory_uri(); ?>/images/logo-2x.png 2x">
								<!--[if IE 9]></video><![endif]-->
								<img src="<?php echo get_template_directory_uri(); ?>/logo.png" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ) ?>">
								</picture>
							</a>
						</div>
						<ul class="login-list">
						<?php if ( is_user_logged_in() ) : ?>
							<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e( 'ACCOUNT', 'luxurysqft' ); ?></a></li>
							<li><a href="<?php echo wp_logout_url(); ?>"><?php _e( 'LOGOUT', 'luxurysqft' ); ?></a></li>
						<?php else : ?>
							<?php $login_page = get_field('login_page', 'option'); ?>
							<?php if( get_option( 'users_can_register' ) ) : ?>
								<li><a href="<?php if($login_page): echo $login_page; else: echo wp_registration_url(); endif; ?>"><?php _e( 'SIGN UP', 'luxurysqft' ); ?></a></li>
							<?php endif ?>
							<li><a href="<?php if($login_page): echo $login_page; else: echo wp_login_url(); endif; ?>"><?php _e( 'LOGIN', 'luxurysqft' ); ?></a></li>
						<?php endif ?>
						</ul>
						<!-- main navigation of the page -->
						<div class="collapse navbar-collapse" id="nav">
							<?php if( has_nav_menu( 'main' ) )
							wp_nav_menu( array(
								'container' => false,
								'theme_location' => 'main',
								'menu_class'     => 'nav navbar-nav',
								'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
								'depth' => 1
								)
							); ?>
						</div>
					</div>
				</div>
			</nav>
		</header>
		<?php if( has_post_thumbnail() ) :
			$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail_1900x162' );
			$style_bg = 'style="background:url(' . $image[0] . ') no-repeat;background-size:cover;"';
		else :
			$style_bg = '';
		endif ?>
		<div class="banner-top" <?php echo $style_bg ?>>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 text-center">
						<div class="holder">
							<h1><?php the_title() ?></h1>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- contain main informative part of the site -->
		<main id="main" role="main">