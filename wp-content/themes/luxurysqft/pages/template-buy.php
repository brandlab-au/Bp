<?php
/*
Template Name: Buy Template
*/
get_header();
$page_id = get_the_ID() ?>

	<div class="main-container buy-rent">
		<!-- products list -->
		<?php $args = get_apartment_args();
		
		$args['meta_key'] = 'buyrent';
		$args['meta_value'] = 'rb';
		

		$res = query_posts($args);
		if (have_posts()) : ?>
			<section class="apartment-section">
				<div class="container">
					<div class="row">
						<?php $i = 0;
						while (have_posts()) : the_post(); ?>
							<?php get_template_part('blocks/content', get_post_type()); ?>
							<?php if ($i % 3 == 2) : ?>
								<div class="clearfix"></div>
							<?php endif ?>
							<?php if ($i == 11) break ?>
							<?php $i++;
						endwhile; ?>
					</div>
				</div>
			</section>
			<?php if ($advertisement = get_field('advertisement', $page_id)) : ?>
				<section class="block-content">
					<!-- advertisement block -->
					<div class="advertisement-block">
						<div class="container">
							<div class="row">
								<div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
									<h2><?php _e('ADVERTISEMENT', 'luxurysqft'); ?></h2>
									<?php echo $advertisement ?>
								</div>
							</div>
						</div>
					</div>
				</section>
			<?php endif ?>
			<?php if (count($res) > 12) : ?>
				<!-- products list -->
				<section class="apartment-section">
					<div class="container">
						<div class="row">
							<?php $i = 0;
							while (have_posts()) : the_post(); ?>
								<?php get_template_part('blocks/content', get_post_type()); ?>
								<?php if ($i % 3 == 2) : ?>
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
						<?php get_template_part('blocks/pager'); ?>
					</div>
				</div>
			</section>
		<?php endif;
		wp_reset_postdata(); ?>
	</div>

<?php get_footer(); ?>