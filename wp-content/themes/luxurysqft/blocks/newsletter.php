<!--START Scripts : this is the script part you can add to the header of your theme-->
<script type="text/javascript" src="<?php echo home_url() ?>/wp-content/plugins/wysija-newsletters/js/validate/languages/jquery.validationEngine-en.js?ver=2.7"></script>
<script type="text/javascript" src="<?php echo home_url() ?>/wp-content/plugins/wysija-newsletters/js/validate/jquery.validationEngine.js?ver=2.7"></script>
<script type="text/javascript" src="<?php echo home_url() ?>/wp-content/plugins/wysija-newsletters/js/front-subscribers.js?ver=2.7"></script>
<script type="text/javascript">
    /* <![CDATA[ */
    var wysijaAJAX = {"action":"wysija_ajax","controller":"subscribers","ajaxurl":"<?php echo home_url() ?>/wp-admin/admin-ajax.php","loadingTrans":"Loading..."};
    /* ]]> */
</script>
<script type="text/javascript" src="<?php echo home_url() ?>/wp-content/plugins/wysija-newsletters/js/front-subscribers.js?ver=2.7"></script>
<!--END Scripts-->
<div class="widget_wysija_cont html_wysija">
    <div id="msg-form-wysija-html56c32ab46d92f-2" class="wysija-msg ajax"></div>
    <form id="form-wysija-html56c32ab46d92f-2" method="post" action="#wysija" class="widget_wysija html_wysija newsletter-form">
	<fieldset class="wysija-paragraph">
	    <h3><?php _e( 'NEWSLETTER', 'luxurysqft' ); ?></h3>
	    <label for="idform"><?php _e( 'Sign up today', 'luxurysqft' ); ?></label>
	    <input class="wysija-submit wysija-submit-field btn-default btn" type="submit" value="Join" />
	    <div class="text-holder">
		<input id="idform" type="text" name="wysija[user][email]" class="wysija-input validate[required,custom[email]]" title="Sign up today"  value="" />
		<span class="abs-req">
		    <input type="text" name="wysija[user][abs][email]" class="wysija-input validated[abs][email]" value="" />
		</span>
	    </div>
	    <input type="hidden" name="form_id" value="2" />
	    <input type="hidden" name="action" value="save" />
	    <input type="hidden" name="controller" value="subscribers" />
	    <input type="hidden" value="1" name="wysija-page" />

	    <input type="hidden" name="wysija[user_list][list_ids]" value="1" />
	</fieldset>
     </form>
</div>