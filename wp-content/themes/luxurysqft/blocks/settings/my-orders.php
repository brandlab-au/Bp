<div id="tab3">
    <div class="tab-block page-heading text-center">
	<?php $current_user = wp_get_current_user(); ?>
	<div class="heading">
	    <span class="name"><?php echo $current_user->display_name ?></span>
	    <span class="link"><?php _e( 'My orders', 'luxurysqft' ); ?></span>
	</div>
	<p><?php _e( 'You can add and remove agents as you see fit<br/>by pressing the round buttons on the right you are marking the agent to be removed ', 'luxurysqft' ); ?></p>
    </div>
    <div class="tab-description">
	<div class="table-block">
	    <div class="inner">
		<?php echo do_shortcode( '[woocommerce_my_account]' ) ?>
	    </div>
	</div>
    </div>
</div>