<?php if( function_exists( 'wp_pagenavi' ) ) { ?>
	<!-- pagination block -->
	<div class="col-xs-12 data-pagination">
	    <?php echo get_post_count(); ?>
	    <?php wp_pagenavi( array(
				    'wrapper_tag' => 'ul',
				    'wrapper_class' => 'paging',
				     ) ); ?>
	</div>
<?php } else {
	the_posts_pagination( array(
		'prev_text' => __( 'Previous page', 'luxurysqft' ),
		'next_text' => __( 'Next page', 'luxurysqft' ),
	) );
}