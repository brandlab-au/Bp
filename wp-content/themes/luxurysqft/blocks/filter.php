<?php $apartments_url = get_apartments_url() ?>
<form action="<?php echo $apartments_url ?>" class="navigation-list" method="get">
    <fieldset>
		<ul class="form-container">
			<li>
				<div class="alt-select">
					<select name="buy" class="filter-drop">
						<option value="" class="hideme"><?php _e( 'buy', 'luxurysqft' ); ?></option>
						<option data-attr="buy" value="rb" <?php if( isset( $_GET[ 'buy' ] ) ) selected( $_GET[ 'buy' ], 'rb' ); ?>><?php _e( 'Residential Buy', 'luxurysqft' ); ?></option>
						<option data-attr="rent" value="rr" <?php if( isset( $_GET[ 'buy' ] ) ) selected( $_GET[ 'buy' ], 'rr' ); ?>><?php _e( 'Residential Rent', 'luxurysqft' ); ?></option>
						<option data-attr="buy" value="cb" <?php if( isset( $_GET[ 'buy' ] ) ) selected( $_GET[ 'buy' ], 'cb' ); ?>><?php _e( 'Commercial Buy', 'luxurysqft' ); ?></option>
						<option data-attr="rent" value="cr" <?php if( isset( $_GET[ 'buy' ] ) ) selected( $_GET[ 'buy' ], 'cr' ); ?>><?php _e( 'Commercial Rent', 'luxurysqft' ); ?></option>
					</select>
				</div>
			</li>
			<?php
			$terms = get_terms( 'location', array(
								'hide_empty' => 0
							) );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
				<li>
					<select name="country" class="filter-drop">
						<option value="" class="hideme"><?php _e( 'COUNTRY', 'luxurysqft' ); ?></option>
						<?php foreach ( $terms as $term ) :
						if( $term->parent != 0 ) continue ?>
						<option value="<?php echo $term->term_id ?>" <?php if( isset( $_GET[ 'country' ] ) ) selected( $_GET[ 'country' ], $term->term_id ); ?>><?php echo $term->name ?></option>
						<?php endforeach ?>
					</select>
				</li>
				<li>
					<select name="city" class="filter-drop">
						<option value="" class="hideme"><?php _e( 'CITY', 'luxurysqft' ); ?></option>
						<?php foreach ( $terms as $term ) :
						if( $term->parent == 0 ) continue ?>
						<option value="<?php echo $term->term_id ?>" <?php if( isset( $_GET[ 'city' ] ) ) selected( $_GET[ 'city' ], $term->term_id ); ?>><?php echo $term->name ?></option>
						<?php endforeach ?>
					</select>
				</li>
			<?php endif ?>
			<?php $terms = get_terms( 'area', array(
								'hide_empty' => 0
							) );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
				<li>
					<select name="ar" class="filter-drop">
						<option value="" class="hideme"><?php _e( 'AREA', 'luxurysqft' ); ?></option>
						<?php foreach ( $terms as $term ) : ?>
						<option value="<?php echo $term->term_id ?>" <?php if( isset( $_GET[ 'ar' ] ) ) selected( $_GET[ 'ar' ], $term->term_id ); ?>><?php echo $term->name ?></option>
						<?php endforeach ?>
					</select>
				</li>
			<?php endif ?>
			<?php $types = (array) get_apartment_type( 0, true ) ?>
			<li>
				<select name="type" class="filter-drop">
				<option value="" class="hideme"><?php _e( 'TYPE', 'luxurysqft' ); ?></option>
				<?php foreach( $types as $key => $value ) : ?>
					<option value="<?php echo $key ?>" <?php if( isset( $_GET[ 'type' ] ) ) selected( $_GET[ 'type' ], $key ); ?>><?php echo $value ?></option>
				<?php endforeach ?>
				</select>
			</li>
			<li>
				<select name="beds" class="filter-drop">
				<option value="" class="hideme"><?php _e( 'BEDS', 'luxurysqft' ); ?></option>
				<option value="1" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 1 ); ?>>01</option>
				<option value="2" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 2 ); ?>>02</option>
				<option value="3" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 3 ); ?>>03</option>
				<option value="4" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 4 ); ?>>04</option>
				<option value="5" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 5 ); ?>>05</option>
				<option value="6" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 6 ); ?>>06</option>
				<option value="7" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 7 ); ?>>07</option>
				<option value="8" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 8 ); ?>>08</option>
				<option value="9" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 9 ); ?>>09</option>
				<option value="10" <?php if( isset( $_GET[ 'beds' ] ) ) selected( $_GET[ 'beds' ], 10 ); ?>>10+</option>
				</select>
			</li>
			<li>
				<select name="price" class="filter-drop">
					<option value="" class="hideme"><?php _e( 'PRICE', 'luxurysqft' ); ?></option>
					<option class="price-rent" value="0-1000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '0-1000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 0 - <?php _e( 'AED', 'luxurysqft' ); ?> 1,000</option>
					<option class="price-rent" value="1000-2000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '1000-2000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 1,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 2,000</option>
					<option class="price-rent" value="2000-3000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '2000-3000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 2,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 3,000</option>
					<option class="price-rent" value="3000-4000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '3000-4000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 3,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 4,000</option>
					<option class="price-rent" value="4000-5000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '4000-5000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 4,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 5,000</option>
					<option class="price-rent" value="5000-6000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '5000-6000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 5,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 6,000</option>
					<option class="price-rent" value="6000-7000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '6000-7000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 6,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 7,000</option>
					<option class="price-rent" value="7000-8000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '7000-8000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 7,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 8,000</option>
					<option class="price-rent" value="8000-9000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '8000-9000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 8,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 9,000</option>
					<option class="price-rent" value="9000-10000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '9000-10000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 9,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 10,000</option>
					<option class="price-rent" value="10000-9999999999" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '10000-9999999999' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 10,000+</option>
					
					<option class="price-buy" value="0-3000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '0-3000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 0 - <?php _e( 'AED', 'luxurysqft' ); ?> 3,000</option>
					<option class="price-buy" value="3000-5000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '3000-5000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 3,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 5,000</option>
					<option class="price-buy" value="5000-8000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '5000-8000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 5,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 8,000</option>
					<option class="price-buy" value="8000-10000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '8000-10000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 8,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 10,000</option>
					<option class="price-buy" value="10000-15000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '10000-15000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 10,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 15,000</option>
					<option class="price-buy" value="15000-20000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '15000-20000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 15,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 20,000</option>
					<option class="price-buy" value="20000-50000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '20000-50000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 20,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 50,000</option>
					<option class="price-buy" value="50000-100000" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '50000-100000' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 50,000 - <?php _e( 'AED', 'luxurysqft' ); ?> 100,000</option>
					<option class="price-buy" value="100000-9999999999" <?php if( isset( $_GET[ 'price' ] ) ) selected( $_GET[ 'price' ], '10000-9999999999' ); ?>><?php _e( 'AED', 'luxurysqft' ); ?> 100,000+</option>
				</select>
			</li>
			<li class="hidden"><input type="hidden" name="filter" value="1"></li>
			<li><input type="submit" value="search"></li>
			<?php if( isset( $_GET[ 'filter' ] ) && $_GET[ 'filter' ] == 1 ) : ?>
				<li><input type="reset" value="reset" onclick="filter_reset( '<?php echo $apartments_url ?>' ); return false;"></li>
			<?php endif ?>
		</ul>
    </fieldset>
</form>
