<script type="text/template" id="tmpl-popop-dialog">
	<div class="ls-popup-dialog is-hidden ls-panels-dialog-has-right-sidebar">

		<div class="ls-overlay"></div>

		<div class="ls-title-bar">
			<h3 class="ls-title">{{ data.title }}</h3>
			<a class="ls-items ls-nav"><span class="ls-dialog-text">1 of 3</span></a>
			<a class="ls-all ls-nav"><span class="ls-dialog-icon"></span></a>
			<a class="ls-previous ls-nav"><span class="ls-dialog-icon"></span></a>
			<a class="ls-next ls-nav"><span class="ls-dialog-icon"></span></a>
			<a class="ls-close"><span class="ls-dialog-icon"></span></a>
		</div>

		<div class="ls-sidebar ls-right-sidebar">
			<div class="price">{{{ data.price }}}</div>
			<div class="desc">{{{ data.desc }}}</div>
		</div>

		<div class="ls-content panel-dialog">
			<div class="gallery">
				<# _.each(data.galleryItems, function(val, i){ #>
					<div class="slide">
						<img src="{{data.galleryItems[i]}}"/>
					</div>
					<# }) #>
			</div>
			<div class="gallery-showcase is-hidden">
				<# _.each(data.galleryItems, function(val, i){ #>
					<div class="slide">
						<a href="#" class="item gallery_item">
							<img src="{{data.galleryItems[i]}}"/>
						</a>
					</div>
					<# }) #>
			</div>
		</div>

	</div>
</script>