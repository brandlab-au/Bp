<?php
/*
Template Name: Saved Template
*/
get_header( );
$page_id = get_the_ID() ?>
<?php global $saved_ids, $saved_template;
$saved_template = true;
if ( $saved_ids ) :
    if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
    elseif ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
    else { $paged = 1; }
    
    $args = array(
	    'post_type' => 'apartments',
	    'posts_per_page' => 24,
	    'post_status' => 'publish',
	    'ignore_sticky_posts' => true,
	    'post__in' => $saved_ids,
	    'paged' => $paged
	);
    $res = query_posts( $args );
    if( have_posts() ) : ?>
	<!-- products list -->
	<section class="apartment-section">
	    <div class="container">
		<div class="row">
		    <?php $i = 0;
		    while( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'blocks/content', get_post_type() ); ?>
			<?php if( $i % 3 == 2) : ?>
			    <div class="clearfix"></div>
			<?php endif ?>
			<?php if( $i == 11 ) break ?>
		    <?php $i++;
		    endwhile; ?> 
		</div>
	    </div>
	</section>
	<?php if( $advertisement = get_field( 'advertisement', $page_id ) ) : ?>
	    <section class="block-content">
		<!-- advertisement block -->
		<div class="advertisement-block">
		    <div class="container">
				<div class="row">
					<div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
						<h2><?php _e( 'ADVERTISEMENT', 'luxurysqft' ); ?></h2>
						<?php echo $advertisement ?>
					</div>
				</div>
		    </div>
		</div>
	    </section>
	<?php endif ?>
	<?php if( count( $res ) > 12 ) : ?>
	    <!-- products list -->
	    <section class="apartment-section">
			<div class="container">
				<div class="row">
				<?php $i = 0;
				while( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'blocks/content', get_post_type() ); ?>
					<?php if( $i % 3 == 2) : ?>
					<div class="clearfix"></div>
					<?php endif ?>
				<?php $i++;
				endwhile; ?>
				</div>
			</div>
	    </section>
	<?php endif ?>
	<section class="apartment-section">
	    <div class="container">
			<div class="row">
				<?php get_template_part( 'blocks/pager' ); ?>
			</div>
	    </div>
	</section>
    <?php else : ?>
	<section class="apartment-section">
	    <div class="container">
			<div class="row">
				<h2 class="apartment-error"><a href="<?php echo get_saved_url() ?>"><?php _e( "Please go to the first page", 'luxurysqft' ); ?></a></h2>
			</div>
	    </div>
	</section>
    <?php endif;
    wp_reset_postdata();
else : ?>
    <section class="apartment-section">
		<div class="container">
			<div class="row">
				<h2 class="apartment-error"><?php _e( "sorry, you need to select some property.", 'luxurysqft' ); ?></h2>
			</div>
		</div>
    </section>
<?php endif ?>
<?php get_footer(); ?>