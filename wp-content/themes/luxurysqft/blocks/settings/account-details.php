<div id="tab1">
    <div class="tab-block page-heading text-center">
		<?php $current_user = wp_get_current_user(); ?>
		<div class="heading">
			<span class="name"><?php echo $current_user->display_name ?></span>
			<span class="link"><?php _e( 'Settings', 'luxurysqft' ); ?></span>
		</div>
		<p><?php _e( 'This is your Luxury sqft account. Click on the following sections to manage your personal information, your previous orders, track your order or your gift cards.', 'luxurysqft' ); ?></p>
    </div>
    <div class="tab-description">
		<div class="tab-form">
			<div class="holder-form">
				<h3><?php _e( 'Account details', 'luxurysqft' ); ?></h3>
				<?php if( isset($current_user->roles[0]) ): ?>
					<ul class="role-list">
						<?php if( $current_user->roles[0] == 'administrator' ): ?><li class="active"><a href="#"><?php _e( 'Administrator', 'luxurysqft' ); ?></a></li><?php endif; ?>
						<li<?php if( $current_user->roles[0] == 'owner' ): ?> class="active"<?php endif; ?>><a href="#"><?php _e( 'House Owner', 'luxurysqft' ); ?></a></li>
						<li<?php if( $current_user->roles[0] == 'broker' ): ?> class="active"<?php endif; ?>><a href="#"><?php _e( 'Property Broker', 'luxurysqft' ); ?></a></li>
						<li<?php if( $current_user->roles[0] == 'customer' ): ?> class="active"<?php endif; ?>><a href="#"><?php _e( 'Shopper', 'luxurysqft' ); ?></a></li>
					</ul>
				<?php endif; ?>
				<div id="account-form">
					<?php if( $current_user->roles[0] == 'administrator' or $current_user->roles[0] == 'owner' ): ?>
						<?php echo do_shortcode('[wppb-edit-profile form_name="account-details"]'); ?>
					<?php elseif( $current_user->roles[0] == 'broker' ): ?>
						<?php echo do_shortcode('[wppb-edit-profile form_name="account-details-property-broker"]'); ?>
					<?php elseif( $current_user->roles[0] == 'customer' ): ?>
						<?php echo do_shortcode('[wppb-edit-profile form_name="account-details-shopper"]'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
    </div>
</div>