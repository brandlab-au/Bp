<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<!-- set the encoding of your site -->
	<meta charset="<?php bloginfo('charset'); ?>">
	<!-- set the viewport width and initial-scale on mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript">
		var pathInfo = {
			base: '<?php echo get_template_directory_uri(); ?>/',
			css: 'css/',
			js: 'js/',
			swf: 'swf/',
			single: 'apartments',
			ajax_nonce: '<?php echo wp_create_nonce('apartments-broker-form'); ?>'
		}
	</script>
	<?php wp_head(); ?>
	<link href='https://fonts.googleapis.com/css?family=Wire+One' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,400italic,700' rel='stylesheet'
	      type='text/css'>
</head>
<body <?php body_class(); ?>>
<!-- lightbox -->
<?php if (is_singular('apartments')) :
	global $post;

	?>
	<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModal3">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<form action="<?php echo admin_url('admin-ajax.php') ?>" class="contact-form">
						<fieldset>
							<div class="contact-modal">
								<h2><?php _e('CONTACT BROKER', 'luxurysqft'); ?></h2>
								<div class="row-form">
									<div class="radio-block">
										<span class="title"><?php _e('Title', 'luxurysqft'); ?>:</span>
										<div class="holder">
											<div class="inner">
												<input id="radio3" value="mr" type="radio" name="title">
												<label for="radio3"><?php _e('MR', 'luxurysqft'); ?></label>
											</div>
											<div class="inner">
												<input id="radio4" value="ms" type="radio" name="title">
												<label for="radio4"><?php _e('MS', 'luxurysqft'); ?></label>
											</div>
											<div class="inner">
												<input id="radio5" value="mrs" type="radio" name="title">
												<label for="radio5"><?php _e('MRS', 'luxurysqft'); ?></label>
											</div>
										</div>
									</div>
								</div>
								<div class="row-form">
									<label for="contact1"><?php _e('First Name', 'luxurysqft'); ?></label>
									<input id="contact1" name="first_name" type="text">
								</div>
								<div class="row-form">
									<label for="contact5"><?php _e('Last Name', 'luxurysqft'); ?></label>
									<input id="contact5" name="last_name" type="text">
								</div>
								<div class="row-form">
									<label for="contact3"><?php _e('Telephone', 'luxurysqft'); ?></label>
									<input id="contact3" name="telephone" type="tel">
								</div>
								<div class="row-form">
									<label for="contact2"><?php _e('Email', 'luxurysqft'); ?></label>
									<input id="contact2" name="email" type="email" data-required="true">
								</div>
								<div class="row-form">
									<label for="contact4"><?php _e('Message', 'luxurysqft'); ?></label>
									<textarea id="contact4" name="message" cols="10" rows="10"
									          placeholder="<?php _e('Dear Agent, I\'m interested in Type D 2 BR Apt with Sea View in Tiara Tanzanite, Palm Jumeirah (reference number LUX-TM-S-2606) listed on www.luxurysqft.com for AED 3,850,000. Please contact me, I would like to know more.', 'luxurysqft'); ?>"></textarea>
								</div>
								<input type="hidden" name="broker"
								       value="<?php echo (is_singular('apartments') && is_object($post)) ? $post->post_author : null ?>">
								<input type="hidden" name="post_id"
								       value="<?php echo (is_singular('apartments') && is_object($post)) ? $post->ID : null ?>">
								<input class="btn btn-default" type="submit" value="SUBMIT">
								<?php $privacy_link = get_field('privacy_link', 'option');
								$privacy_link = $privacy_link ? $privacy_link : '#' ?>
								<p><?php _e('By submitting this form, I certify that I accept the ', 'luxurysqft'); ?><a
											href="<?php echo $privacy_link ?>"><?php _e('Privacy Notice', 'luxurysqft'); ?></a>
								</p>
							</div>
							<div class="thanks-lightbox">
								<?php

								var_dump($post);

								?>
								<h2><?php _e('THANK YOU', 'luxurysqft'); ?></h2>
								<p>The agent will contact you shortly, or you may contact the number below.</p>
								<span class="ref-title">REFERENCE NUMBER</span>
								<a class="tel-number" href="tel:LUXTMS2606">LUX-TM-S-2606</a>
								<br/>
								<a class="phone" href="tel:044270505">04 427 0505</a>
								<br/>
								<a class="email"
								   href="mailto:&#112;&#111;&#114;&#116;&#097;&#108;&#064;&#116;&#114;&#105;&#109;&#101;&#116;&#097;&#114;&#101;&#097;&#108;&#101;&#115;&#116;&#097;&#116;&#101;&#046;&#099;&#111;&#109;">
									&#112;&#111;&#114;&#116;&#097;&#108;&#064;&#116;&#114;&#105;&#109;&#101;&#116;&#097;&#114;&#101;&#097;&#108;&#101;&#115;&#116;&#097;&#116;&#101;&#046;&#099;&#111;&#109;</a>
								<div class="data-info">
									<div class="logo-holder">
										<picture>
											<!--[if IE 9]>
											<video style="display: none;"><![endif]-->
											<source srcset="[template-url]/images/small-logo.png, [template-url]/images/small-logo-2x.png 2x">
											<!--[if IE 9]></video><![endif]-->
											<img src="[template-url]/images/small-logo.png" alt="image description">
										</picture>
									</div>
									<span class="name-logo">TRIM ETA REAL ESTATE</span>
									<span class="name">Alan Yakubov <mark>(RERA brn 1208I)</mark></span>
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
<!-- main container of all the page elements -->
<div id="wrapper">
	<a class="side-opener" data-burger-menu-id="side-menu" href="#"><span>&nbsp;</span></a>
	<!-- header of the page -->
	<header id="header">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="header-holder">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav"
					        aria-expanded="false">
						<span class="sr-only"><?php _e('Toggle navigation', 'luxurysqft'); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div id="side-menu" class="side-menu">
						<div class="holder">
							<div class="inner">
								<div class="dl-menuwrapper">
									<?php if (has_nav_menu('main'))
										wp_nav_menu(array(
														'container'      => false,
														'theme_location' => 'main',
														'menu_class'     => 'dl-menu nav-side nav navbar-nav',
														'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
														'walker'         => new Custom_Walker_Nav_Menu
												)
										); ?>
								</div>
								<?php get_template_part('blocks/filter'); ?>
							</div>
						</div>
					</div>
					<div class="navbar-header">
						<!-- page logo -->
						<a tabindex="1" class="navbar-brand" href="<?php echo home_url(); ?>">
							<picture>
								<!--[if IE 9]>
								<video style="display: none;"><![endif]-->
								<source srcset="<?php echo get_template_directory_uri(); ?>/images/logo.png, <?php echo get_template_directory_uri(); ?>/images/logo-2x.png 2x">
								<!--[if IE 9]></video><![endif]-->
								<img src="<?php echo get_template_directory_uri(); ?>/logo.png"
								     alt="<?php echo esc_attr(get_bloginfo('name')) ?>">
							</picture>
						</a>
					</div>
					<ul class="login-list">
						<?php if (is_user_logged_in()) : ?>
							<li>
								<a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"><?php _e('ACCOUNT', 'luxurysqft'); ?></a>
							</li>
							<li><a href="<?php echo wp_logout_url(); ?>"><?php _e('LOGOUT', 'luxurysqft'); ?></a></li>
						<?php else : ?>
							<?php $login_page = get_field('login_page', 'option'); ?>
							<?php if (get_option('users_can_register')) : ?>
								<li><a href="<?php if ($login_page): echo $login_page;
									else: echo wp_registration_url(); endif; ?>"><?php _e('SIGN UP', 'luxurysqft'); ?></a>
								</li>
							<?php endif ?>
							<li><a href="<?php if ($login_page): echo $login_page;
								else: echo wp_login_url(); endif; ?>"><?php _e('LOGIN', 'luxurysqft'); ?></a></li>
						<?php endif ?>
					</ul>
					<!-- main navigation of the page -->
					<div class="collapse navbar-collapse" id="nav">
						<?php if (has_nav_menu('main'))
							wp_nav_menu(array(
											'container'      => false,
											'theme_location' => 'main',
											'menu_class'     => 'nav navbar-nav',
											'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
											'depth'          => 1
									)
							); ?>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<!-- contain main informative part of the site -->
	<main id="main" role="main">