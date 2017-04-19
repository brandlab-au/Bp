<li>
	<?php if ( has_post_thumbnail() ): ?>
		<div class="visual">
			<a href="<?php the_permalink(); ?>">
				<picture>
					<?php $image_info = get_x2_image( get_the_ID(), array(
						'thumbnail_560x335',
						'thumbnail_1120x670'
					) ); ?>
					<!--[if IE 9]>
					<video style="display: none;"><![endif]-->
					<source
						srcset="<?php echo $image_info['thumbnail_560x335'] ?>, <?php echo $image_info['thumbnail_1120x670'] ?> 2x">
					<!--[if IE 9]></video><![endif]-->
					<img src="<?php echo $image_info['thumbnail_560x335'] ?>" alt="<?php echo $image_info['alt'] ?>">
				</picture>
			</a>
		</div>
	<?php endif; ?>
	<div class="description">
		<?php the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
		<?php theme_the_excerpt(); ?>
		<div class="date">
			<span class="hr"></span>
			<span
				class="title"><?php print human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'luxsft' ); ?></span>

			<?php
			$last_word = '';
			$cats      = wp_get_post_categories( get_the_ID() );
			if ( $cats ): ?>
				<?php foreach ( $cats as $cat ): ?>
					<?php $cat_name = get_cat_name( $cat ); ?>
					<?php $pieces = explode( ' ', $cat_name );
					$number       = true;
					if ( strtolower( $cat_name ) == 'communities' ) {
						$number = false;
					}
					$str       = preg_replace( '/\W\w+\s*(\W*)$/', '$1', $cat_name );
					$last_word = array_pop( $pieces ); ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<span
				class="category"><?php _e( 'Editor\'s choice ', 'luxsft' ); ?> | <?php _e( 'Issue', 'luxsft' );
				print ' ';
				print $last_word; ?></span>
		</div>
	</div>
</li>
