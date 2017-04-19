<?php
/*
Template Name: Login Template
*/
get_header(); ?>
<div class="main-holder">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php if( function_exists( 'bcn_display_list' ) ) : ?>
					<!-- breadcrumbs -->
					<ul class="breadcrumbs">
					<?php bcn_display_list() ?>
					</ul>
				<?php endif ?>
			</div>
			<div class="clearfix"></div>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="col-xs-12 col-sm-6 col-lg-4 col-lg-offset-2 login-col text-center">
					<section>
						<?php $login_title = get_field('login_title');
						$login_desc = get_field('login_description');
						if( !empty($login_desc) or !empty($login_title) ): ?>
							<div class="heading">
								<?php if( $login_title ): ?>
									<h2><?php echo $login_title; ?></h2>
								<?php endif; ?>
								<?php if( $login_desc ): ?>
									<p><?php echo $login_desc; ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if (!is_user_logged_in()) { ?>
							<form name="loginform" id="loginform" class="login-form" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
								<fieldset>
									<div class="row-form">
										<div class="label-holder">
											<label for="user_login"><?php _e( 'Username', 'luxurysqft' ); ?></label>
										</div>
										<div class="description">
											<input type="text" name="log" id="user_login" class="input" value="" size="20" placeholder="Martha" />
										</div>
									</div>
									<div class="row-form">
										<div class="label-holder">
											<label for="user_pass"><?php _e( 'Password', 'luxurysqft' ); ?></label>
										</div>
										<div class="description">
											<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" placeholder="Wayne" />
											<a class="forgot-link" href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Forgot password?', 'luxurysqft' ); ?></a>
										</div>
									</div>
									<div class="row-form">
										<div class="label-holder"></div>
										<div class="description">
											<div class="buttons">
												<?php do_action('facebook_login_button'); ?>
												<input type="submit" name="wp-submit" id="wp-submit" class="button-primary btn" value="Sign in" />
												<input type="hidden" name="redirect_to" value="<?php if($account_page = get_field('account_page', 'option')): echo $account_page; else: the_permalink(); endif; ?>" />
											</div>
										</div>
									</div>
								</fieldset>
							</form>
						<?php } else { // If logged in:
							wp_loginout( get_permalink(get_the_ID()) ); // Display "Log Out" link.
							echo " | ";
							wp_register('', ''); // Display "Site Admin" link.
						} ?>
					</section>
				</div>
				<div class="col-xs-12 col-sm-6 col-lg-4 login-col text-center">
					<section>
						<?php if( get_option( 'users_can_register' ) ) : ?>
							<?php $register_title = get_field('register_title');
							$register_desc = get_field('register_description');
							if( !empty($register_desc) or !empty($register_title) ): ?>
								<div class="heading">
									<?php if( $register_title ): ?>
										<h2><?php echo $register_title; ?></h2>
									<?php endif; ?>
									<?php if( $register_desc ): ?>
										<p><?php echo $register_desc; ?></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php echo do_shortcode('[wppb-register form_name="custom-register"]'); ?>
                            <form class="css-fbl" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                                <?php do_action('facebook_login_button'); ?>
                                <input type="hidden" name="redirect_to" value="<?php if($account_page = get_field('account_page', 'option')): echo $account_page; else: the_permalink(); endif; ?>" />
                            </form>
							<p><?php _e( 'By registering your details you agree to our Terms and Conditions and privacy and cookie policy', 'luxurysqft' ); ?></p>
						<?php endif; ?>
					</section>
				</div>
			<?php endwhile; ?>
			<?php edit_post_link( __( 'Edit', 'luxurysqft' ) ); ?>
			<?php wp_link_pages(); ?>
			<?php comments_template(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>