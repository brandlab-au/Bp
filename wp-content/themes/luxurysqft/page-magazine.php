<?php
/**
 * Template Name: Magazine
 *
 * @package    WordPress
 * @subpackage Twenty_Fourteen
 * @since      Twenty Fourteen 1.0
 */

get_header(); ?>
	<div class="main-container">
		<div class="articles-page">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<?php
						$j     = 0;
						$terms = get_terms( 'category' );
						$ids   = array();
						foreach ( $terms as $key => $term ) {

							if ( strpos( strtolower( $term->name ), 'magazine' ) !== false ) {
								array_push( $ids, $term->term_id );
							}
						}

						$r = new WP_Query( array(
								'post_type'      => 'post',
								'post_status'    => 'publish',
								'posts_per_page' => 1,
								'tax_query'      => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'id',
										'terms'    => $ids
									)
								)
							)
						);


						if ( $r->have_posts() ) : while ( $r->have_posts() ) : $r->the_post(); ?>
							<div class="box-information text-center">
								<?php the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
								<?php theme_the_excerpt(); ?>
								<?php $id = get_the_ID(); ?>
							</div>
						<?php endwhile; ?>
						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
						<?php $page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
						<?php $r = new WP_Query( array(
								'post_type'      => 'post',
								'post_status'    => 'publish',
								'paged'          => $page,
								'posts_per_page' => - 1,
								'post__not_in'   => array( $id ),
								'offset'         => 1,
								'tax_query'      => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'id',
										'terms'    => $ids
									)
								)
							)
						);
						if ( $r->have_posts() ) : ?>
							<div class="holder-articles">
								<ul class="blog-articles text-center">
									<?php while ( $r->have_posts() ) : $r->the_post(); ?>
										<?php
										if ( $j == 5 ) {
											get_template_part( 'blocks/magazine', 'instagram' );
										}
										get_template_part( 'blocks/content', get_post_type() );
										$j ++;
										?>
									<?php endwhile; ?>
								</ul>
								<?php get_template_part( 'blocks/pager' ); ?>
							</div>
						<?php else : ?>
							<?php get_template_part( 'blocks/not_found' ); ?>
						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>