<?php
function backup_database_manage_ui(){
	backup_database_admin_styles();
	backup_database_admin_scripts();
	backup_database_top_ui_callout();

	?>

<div class="wrap">
<p><center><a target="_blank" href="http://www.wpseeds.com/product/wp-all-backup/"><img src="<?php echo BACKUP_DATABASE_ROOT_URL .'img/30.jpg' ?>" width="80%"; height="81px">	</a></center></p>
<div id="poststuff">
				<div id="post-body-content">

					<!-- Start of tabs -->	

					<div class="backup_database-tabs">
					  <?php backup_database_admin_page_tabs(); ?>
					  <div class="clearboth"></div>
					</div>		

					<div class="backup_database-wrapper">


<form method="post">
	
						<!--<div class="tab-description">
							<h3> Overview </h3>
						  	<p>
						  		Below are your current backups.
						  	</p>
						</div>-->
												  	
						<table class="widefat">
							<thead>
							    <tr>
							        <th>ID</th>
							        <th>Date</th> 
							        <th>Type</th>
								<th>Location</th>
							        <th>Status</th>       
							        <th>Size</th>
							    </tr>
							</thead>
							<tfoot>
							    <tr>
							    	<th>ID</th>
							    	<th>Date</th>
							    	<th>Type</th>
								<th>Location</th>
							    	<th>Status</th>
							    	<th>Size</th>
							    </tr>
							</tfoot>
							<tbody id="backup_database-backup-list">
								<?php

							$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
			$per_page = 10;
$page = (!empty($_GET['paged'])) ? $_GET['paged'] : 1;
		$offset = ( ($page -1) * $per_page);

$args = array(
	'posts_per_page' => $per_page,
	'post_type' => 'backup-database',
	
	'offset'=> $offset,
	'current'      => $page,
					'show_all'     => false,
					'end_size'     => 1,
					'mid_size'     => 2,
					'prev_next'    => true,
					'prev_text'    => __('« Previous','userpro'),
					'next_text'    => __('Next »','userpro'),
					'type'         => 'plain',
					'add_args' => false ,	
	
);
									//$args = array( 'post_type' => 'backup_database', 'posts_per_page' => 10 );
									$loop = new WP_Query( $args );

									if($loop->have_posts()): while ( $loop->have_posts() ) : $loop->the_post();
									$backup_status =  get_post_meta( $loop->post->ID, 'backup_status', true);
								?>

								<tr id="backup_database-backup-<?= $loop->post->ID; ?>">
							     <td><?php print $loop->post->ID; ?></td>
							     <td><?php the_time('F jS, Y  @ H: i: s'); ?>
							     	<b></b>

							     	<?php if(1==1): ?>
							     	<div class="row-actions">
							     		<span class="download"><a class="download-backup" title="Download this backup to your local computer" href="<?= BACKUP_DATABASE_DOWNLOADER . '?download=' . $loop->post->ID; ?>">Download</a> | </span>
							     		<span class="delete"><a class="backup_database-remove-backup" href="javascript:void(0);" title="Delete the backup from the the server" data-id="<?= $loop->post->ID; ?>">Remove</a> </span>
							     		<span class="options"> |  <a href="javascript:void(0);" title="Backup Options" data-id="<?= $loop->post->ID; ?>">Options</a></span>
							     	</div>
									<?php endif; ?>

							     </td>
							     <td><?php print get_post_meta($loop->post->ID, 'backup_type', true); ?></td>
							 <td><?php $sources= get_post_meta($loop->post->ID, 'backupsource', true); 
									if(!empty($sources))
									{
										foreach($sources as $k=>$v)
											{
												echo $v;
												echo "<br>";							
											}
									}
								
								?></td>
							     <td><b><?php print $backup_status; if($backup_status == 'In Progress') print ' <img class="ajax-loading-backup-browser" src="'.BACKUP_DATABASE_ROOT_DIR .'/assets/loading.gif" width="20" align="top"><br /> <!--<small> 19% Complete</small>-->'; ?></b></td>
							     <td><b><?php print get_post_meta($loop->post->ID, 'backup_size', true); ?></b></td>
							    </tr>
								<?php endwhile; else: ?>
								<tr id="no-backups-found">
									<td id="no-backups-found"> There are no backups found. </td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<?php endif; ?>
					
							</tbody>
							</table>

<?php
$big = 999999999; // need an unlikely integer

echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( $page, get_query_var('paged') ),
	'total' => $loop->max_num_pages
) );
?>


							<br><br>
							<div class="doing-backup" style="margin-bottom: 20px; line-height: 30px; height: 30px; position: relative;">
								<span class="spinner" style="width: 40px; height: 40px; display: inline; position: relative; top: 3px;"></span> <b>Creating Backup...</b>
							</div>
							
							
					</div>
					<!-- / End of tabs -->

				</div>


		<br class="clear">
		</div>

	</div>
	<!-- /wrap -->
<div>
<h1>
Get Flat 30% off on PRO 
<a href="http://www.wpseeds.com/product/wp-all-backup/" target="_blank">Buy Now</a>
Use Coupon code 'UPDATEPRO'
</h1>
</div>

<?php }

function backup_pagination($pages = '', $range = 4)
{ 
     $showitems = ($range * 2)+1; 
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }  
 
     if(1 != $pages)
     {
         echo "<div class=\"backup_pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>"; 
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}
?>
