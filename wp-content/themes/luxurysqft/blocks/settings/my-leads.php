<div id="tab1_3">
    <div class="tab-block page-heading text-center">
	<?php $current_user = wp_get_current_user(); ?>
	<div class="heading">
	    <span class="name"><?php echo $current_user->display_name ?></span>
	    <span class="link"><?php _e( 'MY LEADS', 'luxurysqft' ); ?></span>
	</div>
	<p><?php _e( 'You can add and remove agents as you see fit<br/>by pressing the round buttons on the right you are marking the agent to be removed ', 'luxurysqft' ); ?></p>
    </div>
    <div class="tab-description">
	<div class="table-block">
	    <div class="inner">
		<?php $query = new WP_Query( array(
			'post_type' => 'leads',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'author' => $current_user->ID,
				) );
		if($query->have_posts()) : ?>
		    <table id="my-leads-tab">
			<thead>
			    <tr>
				<th><?php _e( 'Title', 'luxurysqft' ); ?></th>
				<th><?php _e( 'First name', 'luxurysqft' ); ?></th>
				<th><?php _e( 'Last name', 'luxurysqft' ); ?></th>
				<th><?php _e( 'Email', 'luxurysqft' ); ?></th>
				<th><?php _e( 'Mobile', 'luxurysqft' ); ?></th>
				<th><?php _e( 'Date', 'luxurysqft' ); ?></th>
			    </tr>
			</thead>
			<tbody>
			    <?php $i = 0;
			    while ( $query->have_posts() ) : $query->the_post(); ?>
				<tr class="row<?php echo $i % 2 ?>">
				    <?php $id = get_the_ID();
				    $leads_title = get_post_meta( $id, 'leads_title', true );
				    $leads_first_name = get_post_meta( $id, 'leads_first_name', true );
				    $leads_last_name = get_post_meta( $id, 'leads_last_name', true );
				    $leads_telephone = get_post_meta( $id, 'leads_telephone', true );
				    $leads_email = get_post_meta( $id, 'leads_email', true );
				    $leads_message = get_post_meta( $id, 'leads_message', true );
				    //$leads_post_id = get_post_meta( $id, 'leads_post_id', true ); //apartments post_id ?>
	
				    <td class="leads-title"><?php echo $leads_title;?></td>
				    <td class="leads-first-name"><?php echo $leads_first_name ?></td>
				    <td class="leads-last-name"><?php echo $leads_last_name ?></td>
				    <td class="leads-email"><a href="mailto:<?php echo antispambot( $leads_email ) ?>"><?php echo antispambot( $leads_email ) ?></a></td>
				    <td class="leads-mobile"><a href="tel:<?php echo clean_phone( $leads_telephone ) ?>"><?php echo $leads_telephone ?></a></td>
				    <td class="leads-date">
					<?php the_time( 'd/m/y' ) ?>
				    </td>
				</tr>
				<tr class="row<?php echo $i % 2 ?>">
				    <td colspan="6" class="leads-message"><?php if($leads_message): ?><span class="wrap-leads-message"><?php echo $leads_message; ?></span><?php endif; ?></td>
				</tr>
			    <?php $i++;
			    endwhile; ?>
			</tbody>
		    </table>
		<?php endif;
		wp_reset_postdata(); ?>
	    </div>
	</div>
    </div>
</div>