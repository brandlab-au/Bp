			</main>
	<?php if( is_page_template( 'pages/template-home.php' ) ) : ?>
		</div>
	<?php endif ?>
	</div>
	<!-- footer of the page -->
	<div class="footer-section">
		<aside class="footer-holder">
			<div class="container">
				<div class="row">
			<?php if( has_nav_menu( 'partner_sites' ) ) : ?>
			<div class="col-xs-12 col-sm-4 col-md-2 footer-col widget">
				<h3><?php _e( 'Partner sites', 'luxurysqft' ); ?></h3>
				<!-- additional navigation -->
				<?php wp_nav_menu( array(
					'container' => false,
					'theme_location' => 'partner_sites',
					'menu_class'     => 'menu',
					'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
					)
				); ?>
			</div>
			<?php endif ?>
			<?php if( has_nav_menu( 'company' ) ) : ?>
			<div class="col-xs-12 col-sm-4 col-md-2 footer-col widget">
				<div class="wrap">
				<h3><?php _e( 'Company', 'luxurysqft' ); ?></h3>
				<!-- additional navigation -->
				<?php wp_nav_menu( array(
					'container' => false,
					'theme_location' => 'company',
					'menu_class'     => 'menu',
					'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
					)
				); ?>
				</div>
			</div>
			<?php endif ?>
			<?php if( has_nav_menu( 'trending_articles' ) ) : ?>
			<div class="col-xs-12 col-sm-4 col-md-4 footer-col widget">
				<div class="wrap">
				<h3><?php _e( 'TRENDING articles', 'luxurysqft' ); ?></h3>
				<!-- additional navigation -->
				<?php wp_nav_menu( array(
					'container' => false,
					'theme_location' => 'trending_articles',
					'menu_class'     => 'menu',
					'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
					)
				); ?>
				</div>
			</div>
			<?php endif ?>
					<div class="col-xs-12 col-md-4 footer-col widget">
						<div class="holder">
				<?php if( has_nav_menu( 'magazine' ) ) : ?>
				<div class="menu-col">
					<h3><?php _e( 'Magazine', 'luxurysqft' ); ?></h3>
					<!-- additional navigation -->
					<?php wp_nav_menu( array(
						'container' => false,
						'theme_location' => 'magazine',
						'menu_class'     => 'menu',
						'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
						)
					); ?>
				</div>
				<?php endif ?>
				<?php $facebook = get_field('facebook', 'option');
				$twitter = get_field('twitter', 'option');
				$instagram = get_field('instagram', 'option');
				$pinterest = get_field('pinterest', 'option');
				$google = get_field('google+', 'option');
				if( $facebook or $twitter or $instagram or $pinterest or $google ) : ?>
				<div class="menu-col">
					<h3><?php _e( 'social', 'bankofproperties' ); ?></h3>
					<!-- social networks -->
					<ul class="social-networks">
<!--					<li><a href="--><?php //echo esc_url( $facebook ) ?><!--"><span class="fa fa-facebook"></span> Facebook.com/BankOfProp</a></li>-->
					<li><a href="<?php echo esc_url( $twitter ) ?>"><span class="fa fa-twitter"></span> Twitter/BankOfProp</a></li>
					<li><a href="<?php echo esc_url( $instagram ) ?>"><span class="fa fa-instagram"></span> Instagram/bankofproperties/</a></li>
					<li><a href="<?php echo esc_url( $pinterest ) ?>"><span class="fa fa-pinterest"></span> Pinterest/BankOfProp</a></li>
					<li><a href="<?php echo esc_url( $google ) ?>"><span class="fa fa-google-plus"></span> Google+/BankOfProp</a></li>
					</ul>
				</div>
				<?php endif ?>
						</div>
						<!-- newsletter form -->
						<?php get_template_part( 'blocks/newsletter' ) ?>
					</div>
				</div>
			</div>
		</aside>
		<footer id="footer">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<p>&copy; <?php echo date( 'Y' ); ?> <?php _e( 'BANK OF PROPERTIES, ALL RIGHTS RESERVED.', 'Bank Of Properties' ); ?></p>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<?php if( is_page_template( 'pages/template-settings.php' ) ) : ?>
		</div>
		<?php get_template_part( 'blocks/popup' ) ?>
	<?php endif; ?>
    <?php wp_footer(); ?>
</body>
</html>