<?php
function backup_database_overview_ui(){ 
global $wpdb;
$tables =    $wpdb->get_col('SHOW TABLES');


	if( isset( $_POST['db_backup'] ) && $_POST['db_backup'] == 'db_backup' ){
		
		if( ! empty($_POST['backup_type'] ) ){

			print '<h2>Backup Database</h2>';

			switch ( $_POST['backup_type'] ){
				
				

				case 'database_backup':
					print "<p>Starting Database Backup...</p>";
					flush(); sleep(1);
					backup_database_do_database_backup($_POST['other_tables']);
					exit;
					break;

					// do nothing if nothing is found
			}
		}


	}
	
	backup_database_admin_styles();
	backup_database_admin_scripts();
	backup_database_top_ui_callout();
	
	?>



	<div class="wrap">
<p><center><a target="_blank" href="http://www.wpseeds.com/product/wp-all-backup/"><img src="<?php echo BACKUP_DATABASE_ROOT_URL .'img/30.jpg' ?>" width="80%"; height="81px">	</a></center></p>
		<div id="poststuff">

			<?php if(isset($_GET['settings-updated'])): ?>
					<div id="setting-error-settings_updated" class="updated settings-error"> 
						<p>
							<strong>Settings saved.</strong>
						</p>
					</div>
			<?php endif; ?>
				<div id="post-body-content">

					<!-- Start of tabs -->	




					<div class="backup_database-tabs">
					  <?php backup_database_admin_page_tabs(); ?>
					  <div class="clearboth"></div>
					</div>		

					<div class="backup_database-wrapper">



<form class="dbtable" method="post" action="">
<table >
 <div id="container">
<?php

	global $Backup_Database,$wpdb;
	$backup_database = new Backup_Database();
	$other_tables = array();
		$also_backup = array();
	
		// Get complete db table list	
		$all_tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
		$all_tables = array_map(create_function('$a', 'return $a[0];'), $all_tables);
		// Get list of WP tables that actually exist in this DB (for 1.6 compat!)


		$wp_backup_default_tables = array_intersect($all_tables, $backup_database->core_table_names);
		// Get list of non-WP tables
		$other_tables = array_diff($all_tables, $wp_backup_default_tables);

                    
		?><div class="left_col"><b><?php

		_e("These core WordPress tables will always be backed up:",'db_backup');echo "</b>";
		foreach ($wp_backup_default_tables as $table) {

		?>
			
 			 <div>
  			  <?php echo $table; ?></div>
 			 
		<?php 

		}	?></div><div class="right_col"><b><?php
_e(" You may choose to include any of the following tables:",'db_backup'); echo "</b>";

		foreach ($other_tables as $table) {

		?>
 		 <div>
     	<input type="checkbox" name="other_tables[]" checked="checked" value="<?php echo $table; ?>" /> <?php echo $table; ?></div>
  		
		
				
		<?php 

		}?>		
</div>
</div>
		

</table>
<div style="clear:both;"></div>

<br>
	<input type="hidden" name="db_backup" value="db_backup" id="db_backup">
	<input type="hidden" name="backup_type" value="database_backup" id="create_backup">
	<center><input type="submit" class="backup_database_button" name="submit" id="create-database-backup"  value="<?php _e('Create Database Backup','backup_db'); ?>"  /></center>



</form>
<div>
<h1>
Get Flat 30% off on PRO 
<a href="http://www.wpseeds.com/product/wp-all-backup/" target="_blank">Buy Now</a>
Use Coupon code 'UPDATEPRO'
</h1>
</div>
	</div>
	<!-- /wrap -->

<?php }?>
