<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$id = absint( $_POST['id'] );

if ( $id > 0 ) {
	$args = array( 'posts_per_page' => 5, 'category' => $id );

	$issues = get_posts( $args );

	if ( ! empty( $issues ) ) {

		$term = get_term( $id, 'category' );

		?>
		<div class="magazine_issues clearfix">
			<div class="left">

				<span class="title"><?php print get_field( 'magazine_title', $term ); ?></span>
				<span class="date"><?php

					$date = get_field( 'magazine_date', $term );

					if ( $date ) {
						$date_obj = new DateTime( $date );
						print $date_obj->format( 'F Y' );
					}

					?></span>

			</div>

			<div class="right">
				<?php
				$i = 0;
				global $post;
				foreach ( $issues as $post ) : setup_postdata( $post ); ?>
					<a href="<?php print get_the_permalink(); ?>"
					   class="issue <?php print ( ++ $i % 2 == 0 ) ? 'even' : 'odd'; ?>"><?php print get_the_title(); ?></a>
					<?php
					$i ++;
				endforeach;
				wp_reset_postdata();

				?>
				<div class="bottom_nav clearfix">
					<a href="" class="back" data-link="sub">
						<i class="fa fa-long-arrow-left"
						   aria-hidden="true"></i> <?php _e( 'Go back', 'luxsft' ); ?>
					</a>
					<a href="<?php print get_bloginfo( 'url' ) . '/category/' . $term->slug; ?>" class="read_now">
						<?php _e( 'View all', 'luxsft' ); ?>
					</a>
				</div>

			</div>

		</div>
	<?php }

}