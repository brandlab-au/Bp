<?php
/*
Template Name: Account Template
*/
get_header(); ?>
<div class="main-holder">
    <div class="container">
        <div class="row">
			<?php if( function_exists( 'bcn_display_list' ) ) : ?>
				<div class="col-xs-12 col-md-2">
					<!-- breadcrumbs -->
					<ul class="breadcrumbs">
					<?php bcn_display_list() ?>
					</ul>
				</div>
			<?php endif ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="col-xs-12 col-md-9 col-lg-8">
					<?php if( has_nav_menu( 'account' ) )
					wp_nav_menu( array(
						'theme_location' => 'account',
						'menu_class'     => 'nav navbar-nav',
						'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
						)
					); ?>
					<?php the_content(); ?>
				</div>
			<?php endwhile; ?>
			<?php wp_link_pages(); ?>
			<?php comments_template(); ?>
		</div>
    </div>
</div>

<?php get_footer(); ?>