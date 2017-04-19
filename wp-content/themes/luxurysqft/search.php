<?php get_header(); ?>
	<div class="main-holder">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<?php if ( have_posts() ) : ?>
						<div class="title">
							<h1><?php printf( __( 'Search Results for: %s', 'luxurysqft' ), '<span>' . get_search_query() . '</span>'); ?></h1>
						</div>
						<div class="holder-articles">
							<ul class="blog-articles text-center">
								<?php while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'blocks/content', get_post_type() ); ?>
								<?php endwhile; ?>
							</ul>
							<?php get_template_part( 'blocks/pager' ); ?>
						</div>
					<?php else : ?>
						<?php get_template_part( 'blocks/not_found' ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>