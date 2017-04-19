<?php get_header(); ?>
	<div class="main-holder">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<?php if( function_exists( 'bcn_display_list' ) ) : ?>
						<!-- breadcrumbs -->
						<ul class="breadcrumbs">
							<?php bcn_display_list() ?>
						</ul>
					<?php endif ?>
				</div>
				<div class="col-xs-12">
					<?php if ( have_posts() ) : ?>
						<div class="holder-articles">
							<ul class="blog-articles text-center">
								<?php $i = 1; while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'blocks/content', get_post_type() ); ?>
								<?php $i++; endwhile; ?>
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