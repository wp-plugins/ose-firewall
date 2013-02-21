<div id="container">
	<div id = "stage">
	<div id="header">
		<img src="<?php echo OSEFWURL.'/assets/images/scan.png'?>" alt="<?php _e(OSE_VIRUS_SCAN, 'ose_wordpress_firewall'); ?>">
		<h1><?php _e( OSE_WORDPRESS_VIRUSSCAN_CONFIG, 'ose_wordpress_firewall' ); ?></h1>
		<p> <?php _e( OSE_VIRUS_SCAN_DESC, 'ose_wp_firewall' ); ?></p>
	</div>
	
	<div class="clear" id="poststuff" style="width: 98%;">
		<form method="post" action="options.php">
		<?php
			// Get Setting from system; 
			settings_fields( 'ose_wp_firewall_avsetting_group' );
			$settings = get_option( 'ose_wp_firewall_avsetting' );
			$osefirewall_email           = isset( $settings['osefirewall_email'] ) ? $settings['osefirewall_email'] : $admin_email;
			$maxfilesize       = isset( $settings['maxfilesize'] ) ? $settings['maxfilesize'] : 0;
			$file_ext = isset( $settings['file_ext'] ) ? $settings['file_ext'] : 'htm,html,shtm,shtml,css,js,php,php3,php4,php5,inc,phtml,jpg,jpeg,gif,png,bmp,c,sh,pl,perl,cgi,txt,htaccess';
			?>
		<!-- 	
		 <section>
				<h2><?php _e(NOTIFICATION_EMAIL_ATTACKS, 'ose_wp_firewall'); ?></h2>
				<div class="inside">
					<table class="widefat">
						<tr valign="top">
							<th scope="row">
							<?php _e(EMAIL_ADDRESS , 'ose_wp_firewall'); ?></th>
							<td>
								<input type="text" size = "50" name="ose_wp_firewall_avsetting[osefirewall_email]" value="<?php echo $osefirewall_email; ?>" />
							</td>
						</tr>
					</table>
				</div>
		</section>
		
		<section>
				<h2><?php _e(OSE_ID_INFO, 'ose_wp_firewall'); ?></h2>
				<div class="inside">
					<table class="widefat">
						<tr valign="top">
							<th scope="row">
							<?php _e(OSE_ID , 'ose_wp_firewall'); ?></th>
							<td>
								<input type="text" size = "50" name="ose_wp_firewall_avsetting[osefirewall_email]" value="<?php echo $osefirewall_email; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
							<?php _e(OSE_PASS , 'ose_wp_firewall'); ?></th>
							<td>
								<input type="text" size = "50" name="ose_wp_firewall_avsetting[osefirewall_email]" value="<?php echo $osefirewall_email; ?>" />
							</td>
						</tr>
					</table>
				</div>
		</section>
		 -->
		<section>
				<h2><?php _e(OSE_SCANNING_SETTING, 'ose_wp_firewall'); ?></h2>
				<div class="inside">
					<table class="widefat">
						<tr valign="top">
							<th scope="row"><?php _e(FILEEXTSCANNED, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="text" size = "70" name="ose_wp_firewall_avsetting[file_ext]" id ="file_ext" value ="<?php echo $file_ext; ?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e(DONOTSCAN, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="text" name="ose_wp_firewall_avsetting[maxfilesize]" id ="maxfilesize" value ="<?php echo $maxfilesize; ?>" /></td>
						</tr>
						
					</table>
				</div>
		</section>
		<section>
			<p class="submit">
				<input type="submit" class="btn fork" value="<?php esc_attr_e(SAVE_CHANGES, 'ose_wp_firewall' ) ?>" />
			</p>
		</section>	
		</form>
	</div>
	<!-- /poststuff -->
</div>
	</div>
<!-- /wrap -->