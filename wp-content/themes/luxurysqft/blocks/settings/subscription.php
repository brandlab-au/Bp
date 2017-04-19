<div id="tab4">
    <div class="tab-block page-heading text-center">
	<?php $current_user = wp_get_current_user(); ?>
	<div class="heading">
	    <span class="name"><?php echo $current_user->display_name ?></span>
	    <span class="link"><?php _e( 'Settings', 'luxurysqft' ); ?></span>
	</div>
	<p><?php _e( 'This is your Luxury sqft account. Click on the following sections to manage your personal information, your previous orders, track your order or your gift cards.', 'luxurysqft' ); ?></p>
    </div>
    <div class="tab-description">
	<!--START Scripts : this is the script part you can add to the header of your theme-->
	<script type="text/javascript" src="<?php echo home_url(); ?>/wp-content/plugins/wysija-newsletters/js/validate/languages/jquery.validationEngine-en.js?ver=2.7"></script>
	<script type="text/javascript" src="<?php echo home_url(); ?>/wp-content/plugins/wysija-newsletters/js/validate/jquery.validationEngine.js?ver=2.7"></script>
	<script type="text/javascript" src="<?php echo home_url(); ?>/wp-content/plugins/wysija-newsletters/js/front-subscribers.js?ver=2.7"></script>
	<script type="text/javascript">
	    /* <![CDATA[ */
	    var wysijaAJAX = {"action":"wysija_ajax","controller":"subscribers","ajaxurl":"<?php echo home_url(); ?>/wp-admin/admin-ajax.php","loadingTrans":"Loading..."};
	    /* ]]> */
	</script>
	<script type="text/javascript" src="<?php echo home_url(); ?>/wp-content/plugins/wysija-newsletters/js/front-subscribers.js?ver=2.7"></script>
	<!--END Scripts-->
	    
	<div class="widget_wysija_cont html_wysija">
	    <div id="msg-form-wysija-html56c7270b9b9dd-3" class="wysija-msg ajax"></div>
	    <form id="form-wysija-html56c7270b9b9dd-3" method="post" action="#wysija" class="widget_wysija html_wysija tab-form">
		<fieldset>
		    <div class="holder-form">
			<h3><?php _e( 'Manage your Subsciptions', 'luxurysqft' ); ?></h3>
			<div class="row-radio">
			    <div class="holder-radio">
				<input type="radio" class="wysija-radio " name="wysija[field][cf_1]" value="LUXURYSQFT MAGAZINE"  />
				<label class="wysija-radio-label"><?php _e( 'LUXURYSQFT MAGAZINE', 'luxurysqft' ); ?></label>
			    </div>
			    <div class="holder-radio">
				<input type="radio" class="wysija-radio " name="wysija[field][cf_1]" value="SAME AS BILLING ADDRESS"  />
				<label class="wysija-radio-label"><?php _e( 'SAME AS BILLING ADDRESS', 'luxurysqft' ); ?></label>
			    </div>
			</div>
			<div class="row-form">
			    <div class="form-col small">
				<label class="wysija-select-label"><?php _e( 'Title (Mr/Mrs)', 'luxurysqft' ); ?></label>
				<select class="wysija-select " name="wysija[field][cf_4]">
				    <option value="Mr."><?php _e( 'Mr.', 'luxurysqft' ); ?></option>
				    <option value="Mrs."><?php _e( 'Mrs.', 'luxurysqft' ); ?></option>
				</select>
			    </div>
			    <div class="form-col medium">
				<label><?php _e( 'First name', 'luxurysqft' ); ?></label>
				<input type="text" name="wysija[user][firstname]" class="wysija-input " title="First name"  value="" />
				<span class="abs-req">
					<input type="text" name="wysija[user][abs][firstname]" class="wysija-input validated[abs][firstname]" value="" />
				</span>
			    </div>
			    <div class="form-col">
				<label><?php _e( 'Last name', 'luxurysqft' ); ?></label>
				<input type="text" name="wysija[user][lastname]" class="wysija-input " title="Last name"  value="" />
				<span class="abs-req">
					<input type="text" name="wysija[user][abs][lastname]" class="wysija-input validated[abs][lastname]" value="" />
				</span>
			    </div>
			</div>
			<div class="row-form">
			    <div class="form-col">
				<label><?php _e( 'Email', 'luxurysqft' ); ?> <span class="wysija-required">*</span></label>
				<input type="text" name="wysija[user][email]" class="wysija-input validate[required,custom[email]]" title="Email"  value="" />
				<span class="abs-req">
					<input type="text" name="wysija[user][abs][email]" class="wysija-input validated[abs][email]" value="" />
				</span>
			    </div>
			    <div class="form-col">
				<label><?php _e( 'Phone', 'luxurysqft' ); ?></label>
				<input type="text" name="wysija[field][cf_3]" class="wysija-input validate[custom[phone]]" title="Phone"  value="" />
				<span class="abs-req">
					<input type="text" name="wysija[field][abs][cf_3]" class="wysija-input validated[abs][cf_3]" value="" />
				</span>
			    </div>
			</div>
		    </div>
		    <input class="wysija-submit wysija-submit-field btn btn-default" type="submit" value="Subscribe!" />
		    <input type="hidden" name="form_id" value="3" />
		    <input type="hidden" name="action" value="save" />
		    <input type="hidden" name="controller" value="subscribers" />
		    <input type="hidden" value="1" name="wysija-page" />
		    <input type="hidden" name="wysija[user_list][list_ids]" value="1" />
		</fieldset>
	    </form>
	</div>
    </div>
</div>