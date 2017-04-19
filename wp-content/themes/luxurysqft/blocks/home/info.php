<?php if( have_rows( 'info_blocks' ) ) : ?>
    <!-- posts list -->
    <section class="block-content">
	<div class="container">
	    <div class="row">
		<?php $i = 0;
		$count = count( get_field( 'info_blocks' ) );
		while ( have_rows( 'info_blocks' ) ) : the_row();
		    if( $i % 3 == 0 ) : ?>
			<div class="col-xs-12 col-sm-4 col-md-3 col-md-offset-1 text-center contact-col">
		    <?php elseif( $i % 3 == 1 ) : ?>
			<div class="col-xs-12 col-sm-4 col-md-4 text-center contact-col">
		    <?php elseif( $i % 3 == 2 ) : ?>
			<div class="col-xs-12 col-sm-4 col-md-3 text-center contact-col">
		    <?php endif ?>
		    <?php $title = get_sub_field('title');
		    $text = get_sub_field('text');
		    $link = get_sub_field('link'); ?>
			<div class="holder">
			    <?php if( $title ) : ?>
				<h3><?php echo $title ?></h3>
			    <?php endif ?>
			    <?php echo wpautop( $text ) ?>
			    <?php if( $link ) : ?>
				<a class="more" href="<?php echo esc_url( $link ) ?>"><?php _e( 'Read More', 'luxurysqft' ); ?></a>
			    <?php endif ?>
			</div>
		    </div>
		    <?php if( $i % 3 == 2 && $i != $count - 1) : ?>
			</div><div class="row">
		    <?php endif ?>
		<?php $i++;
		endwhile ?>
	    </div>
	</div>
    </section>
<?php endif ?>