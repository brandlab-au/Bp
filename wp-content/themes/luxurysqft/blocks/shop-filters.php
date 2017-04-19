<form action="<?php echo get_permalink(get_option( 'woocommerce_shop_page_id' )); ?>" class="navigation-list filter-form" data-load-container="#ajax-target" method="get" >
	<fieldset>
		<ul class="form-container">
			<?php if ( $parent_cats = get_terms('product_cat') ) : ?>
				<li>
					<div class="alt-select">
						<select class="filter-drop" name="parent_cat">
							<option class="hideme" value=""><?php _e( 'FURNITURE', 'luxurysqft' ); ?></option>
							<?php foreach( $parent_cats as $cat ): ?>
								<?php if( $cat->parent != 0 ) continue; ?>
								<option value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</li>
			<?php endif; ?>
			<?php if ( $types = get_terms('type') ) : ?>
				<li>
					<select class="filter-drop"  name="type">
						<option class="hideme" value=""><?php _e( 'NEW IN', 'luxurysqft' ); ?></option>
						<?php foreach( $types as $type ): ?>
							<option value="<?php echo $type->slug; ?>"><?php echo $type->name; ?></option>
						<?php endforeach; ?>
					</select>
				</li>
			<?php endif; ?>
			<?php if ( $cats = get_terms('product_cat' ) ) : ?>
				<li>
					<select class="filter-drop" name="cat">
						<option class="hideme" value=""><?php _e( 'CATEGORY', 'luxurysqft' ); ?></option>
						<?php foreach( $cats as $cat ): ?>
							<?php if( $cat->parent == 0 ) continue; ?>
							<option value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>
						<?php endforeach; ?>
					</select>
				</li>
			<?php endif; ?>
			<?php if ( $brands = get_terms('brand') ) : ?>
				<li>
					<select class="filter-drop" name="brand">
						<option class="hideme" value=""><?php _e( 'BRAND', 'luxurysqft' ); ?></option>
						<?php foreach( $brands as $brand ): ?>
							<option value="<?php echo $brand->slug; ?>"><?php echo $brand->name; ?></option>
						<?php endforeach; ?>
					</select>
				</li>
			<?php endif; ?>
			<li>
				<select class="filter-drop" name="price">
					<option class="hideme" value=""><?php _e( 'PRICE', 'luxurysqft' ); ?></option>
					<option value="0-1000"><?php _e( 'AED', 'luxurysqft' ); ?> 0 - <?php _e( 'AED', 'luxurysqft' ); ?> 1,000</option>
					<option value="1000-2000"><?php _e( 'AED', 'luxurysqft' ); ?> 1,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 2,000</option>
					<option value="2000-3000"><?php _e( 'AED', 'luxurysqft' ); ?> 2,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 3,000</option>
					<option value="3000-4000"><?php _e( 'AED', 'luxurysqft' ); ?> 3,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 4,000</option>
					<option value="4000-5000"><?php _e( 'AED', 'luxurysqft' ); ?> 4,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 5,000</option>
					<option value="5000-6000"><?php _e( 'AED', 'luxurysqft' ); ?> 5,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 6,000</option>
					<option value="6000-7000"><?php _e( 'AED', 'luxurysqft' ); ?> 6,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 7,000</option>
					<option value="7000-8000"><?php _e( 'AED', 'luxurysqft' ); ?> 7,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 8,000</option>
					<option value="8000-9000"><?php _e( 'AED', 'luxurysqft' ); ?> 8,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 9,000</option>
					<option value="9000-10000"><?php _e( 'AED', 'luxurysqft' ); ?> 9,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 10,000</option>
					<option value="10000-1000000"><?php _e( 'AED', 'luxurysqft' ); ?>10,000+</option>
				</select>
			</li>
			<?php if ( $colours = get_terms('pa_colour') ) : ?>
				<li>
					<select class="filter-drop" name="color">
						<option class="hideme" value=""><?php _e( 'COLOUR', 'luxurysqft' ); ?></option>
						<?php foreach( $colours as $color ): ?>
							<option value="<?php echo $color->slug; ?>"><?php echo $color->name; ?></option>
						<?php endforeach; ?>
					</select>
				</li>
			<?php endif; ?>
			<li><input type="submit" value="search"></li>
			<li><input type="reset" value="reset"></li>
		</ul>
	</fieldset>
</form>