<?php if( $similar_properties = get_field( 'similar_properties' ) ) : ?>	
    <!-- products list -->
    <section class="block-content">
	<div class="container">
	    <div class="row">
		<div class="col-xs-12 text-center">
		    <h3><?php _e( 'SIMILAR PROPERTIES', 'luxurysqft' ); ?></h3>
		</div>
		<div class="clearfix"></div>
		<?php foreach( $similar_properties as $post ) :
		    setup_postdata($post); ?>
		    <?php get_template_part( 'blocks/content', get_post_type() ); ?>
		<?php endforeach; 
		wp_reset_postdata(); ?>
		<div class="clearfix"></div>
	    </div>
	</div>
    </section>
<?php endif ?>