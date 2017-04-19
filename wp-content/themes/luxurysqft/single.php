<?php get_header(); ?>
	<div class="main-container">
		<div class="single-post">
			<div class="container">
				<div class="row">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="col-xs-12 col-sm-9">
							<div class="single-holder">
								<div class="side-column">
									<div class="info-post text-center">
										<?php $cats = wp_get_post_categories( get_the_ID() ); 
										if( $cats ): ?>
											<?php foreach( $cats as $cat ): ?>
												<?php $cat_name = get_cat_name( $cat); ?>
												<?php $pieces = explode(' ', $cat_name);
												$number = true;
												if(strtolower($cat_name) == 'communities') {
													$number = false;
												}
												$str = preg_replace('/\W\w+\s*(\W*)$/', '$1', $cat_name);
												$last_word = array_pop($pieces); ?>
												<div class="name-holder">
													<?php if( $str ): ?>
														<span class="name"><?php echo $str; ?></span>
													<?php endif; ?>
													<?php if( $last_word && $number): ?>
														<span class="number"><?php echo $last_word; ?></span>
													<?php endif; ?>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
										<span class="author"><?php _e( 'by', 'luxurysqft' ); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
										<time class="date" datetime="<?php the_time( 'Y-m-d' ) ?>"><?php the_time( 'M j, Y' ) ?> <?php _e( 'at', 'luxurysqft' ); ?> <?php the_time( 'g:i A' ) ?></time>
										<!-- social plugin container -->
										<ul class="social-networks">
											<li><a href="https://www.facebook.com/luxsqft"><span class="fa fa-facebook"></span> Facebook.com/luxurysqft</a></li>
											<li><a href="https://twitter.com/LuxurySQFT"><span class="fa fa-twitter"></span> Twitter/luxurysqft</a></li>
											<li><a href="https://www.instagram.com/luxurysqft/"><span class="fa fa-instagram"></span> Instagram/luxurysqft</a></li>
											<li><a href="https://www.pinterest.com/luxurysqft/"><span class="fa fa-pinterest"></span> Pinterest/luxurysqft</a></li>
											<li><a href="https://plus.google.com/+Luxsqft"><span class="fa fa-google-plus"></span> Google+/luxurysqft</a></li>
										</ul>
									</div>
								</div>
								<div class="post-description">
									<div class="heading">
										<?php the_title( '<h1>', '</h1>' ); ?>
										<?php if( $subtitle = get_field('subtitle') ): ?>
											<p><?php echo $subtitle; ?></p>
										<?php endif; ?>
									</div>
									<?php the_content(); ?>
								</div>
							</div>
							<?php $content = get_field('content');
							$gallery = get_field('gallery');
							$gallery_title = get_field('gallery_title');
							$items = get_field('items');
							if( !empty($content) or !empty($gallery) or !empty($gallery_title) or !empty($items) ): ?>
								<div class="single-holder">
									<?php if( !empty($gallery_title) or !empty($items) ): ?>
										<div class="side-column">
											<div class="some-info">
												<?php if( $gallery_title ): ?>
													<span class="info-title"><?php echo $gallery_title; ?></span>
												<?php endif; ?>
												<?php if( $items ): ?>
													<ul class="item-list">
														<?php foreach( $items as $item ): ?>
															<li>
																<?php if( !empty($item['title']) ): ?>
																	<span class="item"><?php echo $item['title']; ?></span>
																<?php endif; ?>
																<?php if( !empty($item['value']) ): ?>
																	<span class="item"><?php echo $item['value']; ?></span>
																<?php endif; ?>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											</div>
										</div>
									<?php endif; ?>
									<?php if( !empty($gallery) or !empty($content) ): ?>
										<div class="post-description">
											<!-- cycle carousel structure  -->
											<?php if( $gallery ): ?>
												<div class="post-gallery">
													<div class="mask">
														<div class="slideset">
															<?php foreach( $gallery as $image ): ?>
																<div class="slide">
																	<picture>
																		<!--[if IE 9]><video style="display: none;"><![endif]-->
																		<source srcset="<?php echo $image['sizes']['thumbnail_700x466'] ?>, <?php echo $image['sizes']['thumbnail_1400x932'] ?> 2x">
																		<!--[if IE 9]></video><![endif]-->
																		<img src="<?php echo $image['sizes']['thumbnail_700x466'] ?>" alt="<?php echo $image['alt'] ?>">
																	</picture>
																</div>
															<?php endforeach; ?>
														</div>
													</div>
													<a class="btn-prev fa fa-angle-left" href="#">&nbsp;</a>
													<a class="btn-next fa fa-angle-right" href="#">&nbsp;</a>
												</div>
											<?php endif; ?>
											<?php if( $content ):
												echo $content;
											endif; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if( $ad = get_field('advertisement') ): ?>
							<div class="col-xs-12 col-sm-3">
								<div class="ad-holder">
									<?php echo $ad; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>