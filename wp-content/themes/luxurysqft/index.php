<?php get_header(); ?>
	<div class="main-container">
		<div class="articles-page">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<?php

						$ids   = array();
						$terms = get_terms('category');

						foreach ($terms as $key => $term) {

							if (strpos(strtolower($term->name), 'communities') !== false) {
								array_push($ids, $term->term_id);
							}
						}

						$r = new WP_Query(array(
										'post_type'        => 'post',
										'post_status'      => 'publish',
										'posts_per_page'   => 1,
										'category__not_in' => $ids
								)
						);

						if ($r->have_posts()) : while ($r->have_posts()) : $r->the_post(); ?>
							<div class="box-information text-center">
								<?php the_title('<h2><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
								<?php theme_the_excerpt(); ?>
								<?php $id = get_the_ID(); ?>
							</div>
						<?php endwhile; ?>
						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
						<?php $page = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
						<?php $r = new WP_Query(array(
										'post_type'        => 'post',
										'post_status'      => 'publish',
										'offset'           => 1,
										'paged'            => $page,
										'post__not_in'     => array($id),
										'category__not_in' => $ids
								)
						);

						if ($r->have_posts()) : ?>
							<div class="holder-articles">
								<ul class="blog-articles text-center">
									<?php while ($r->have_posts()) : $r->the_post(); ?>
										<?php get_template_part('blocks/content', get_post_type()); ?>
									<?php endwhile; ?>
								</ul>
								<?php get_template_part('blocks/pager'); ?>
							</div>
						<?php else : ?>
							<?php get_template_part('blocks/not_found'); ?>
						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
			<div class="block-content bottom-border">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
							<h3><?php _e('INSTAGRAM POSTS', 'luxurysqft'); ?></h3>
							<div class="instagram-plugin">
								<picture>
									<!--[if IE 9]>
									<video style="display: none;"><![endif]-->
									<source srcset="<?php echo get_template_directory_uri(); ?>/images/img39.jpg, <?php echo get_template_directory_uri(); ?>/images/img39-2x.jpg 2x">
									<!--[if IE 9]></video><![endif]-->
									<img src="<?php echo get_template_directory_uri(); ?>/images/img39.jpg"
									     alt="image description">
								</picture>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>