<div id="tab1_2">
    <div class="tab-block  page-heading text-center">
	<?php $current_user = wp_get_current_user(); ?>
	<div class="heading">
	    <span class="name"><?php echo $current_user->display_name ?></span>
	    <span class="link"><?php _e( 'MY AGENTS', 'luxurysqft' ); ?></span>
	</div>
	<p><?php _e( 'You can add and remove agents as you see fit<br/>by pressing the round buttons on the right you are marking the agent to be removed ', 'luxurysqft' ); ?></p>
    </div>
    <div class="tab-description">
	<div class="table-block">
	    <form action="#" data-action="<?php echo admin_url( 'admin-ajax.php' ) ?>" class="remove-form">
		<fieldset>
		    <ul class="links">
			<li><a href="<?php echo admin_url( 'user-new.php' ) ?>"><?php _e( 'Add Agents', 'luxurysqft' ); ?></a></li>
			<li><a class="remove" href="#"><?php _e( 'Remove', 'luxurysqft' ); ?></a></li>
		    </ul>
		    <div class="inner">
			<table>
			    <thead>
				<tr>
				    <th><?php _e( 'Title', 'luxurysqft' ); ?></th>
				    <th><?php _e( 'First name', 'luxurysqft' ); ?></th>
				    <th><?php _e( 'Last name', 'luxurysqft' ); ?></th>
				    <th class="email-cell"><?php _e( 'Email', 'luxurysqft' ); ?></th>
				    <th><?php _e( 'Mobile', 'luxurysqft' ); ?></th>
				    <th><?php _e( 'Remove', 'luxurysqft' ); ?></th>
				</tr>
			    </thead>
			    <tbody>
				<?php $args = array (
				    'role' => 'broker',
				    'exclude' => array( $current_user->ID ),
				    'number' => -1
				);

				$wp_user_query = new WP_User_Query($args);
				$brokers = $wp_user_query->get_results();
				if ( ! empty( $brokers ) ) :
				    foreach ($brokers as $broker) : ?>
					<tr>
					    <td><?php echo get_user_meta( $broker->ID, 'title', true ) ?></td>
					    <td><?php echo get_user_meta( $broker->ID, 'first_name', true ) ?></td>
					    <td><?php echo get_user_meta( $broker->ID, 'last_name', true ) ?></td>
					    <td class="email-cell"><a href="mailto:<?php echo antispambot( $broker->data->user_email ) ?>"><?php echo antispambot( $broker->data->user_email ) ?></a></td>
					    <td><a href="tel:<?php echo clean_phone( get_user_meta( $broker->ID, 'phone', true ) ) ?>"><?php echo get_user_meta( $broker->ID, 'phone', true ) ?></a></td>
					    <td>
						<div class="input-center"><input type="checkbox" name="remove" value="<?php echo $broker->ID ?>"></div>
					    </td>
					</tr>
				    <?php endforeach;
				else : ?>
				    <tr>
					<td colspan="6"><?php _e( 'No brokers found', 'luxurysqft' ); ?></td>
				    </tr>
				<?php endif ?>
			    </tbody>
			</table>
		    </div>
		</fieldset>
	    </form>
	</div>
    </div>
</div>