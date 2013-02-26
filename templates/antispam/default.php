<div id="container">
	<div id = "stage">
	<div id="header">
		<img src="<?php echo OSEFWURL.'/assets/images/antispam.png'?>" alt="<?php _e(OSE_WORDPRESS_ANTISPAM_CONFIG, 'ose_wordpress_firewall'); ?>">
		<h1> <?php _e( OSE_WORDPRESS_ANTISPAM_CONFIG, 'ose_wordpress_firewall' ); ?></h1>
		<p> <?php _e( OSE_WORDPRESS_ANTISPAM_CONFIG, 'ose_wp_firewall' ); ?></p>
	</div>	
	<div class="clear" id="poststuff" style="width: 98%;">
		<form method="post" action="options.php">
		<?php
			// Get Setting from system; 
			settings_fields( 'ose_wp_firewall_antispam_group' );
			$settings = get_option( 'ose_wp_firewall_antispam' );
			$osefirewall_sfspam = isset( $settings['osefirewall_sfspam'] ) ? $settings['osefirewall_sfspam'] : false;
			$sfs_confidence = isset( $settings['sfs_confidence'] ) ? $settings['sfs_confidence'] : 50;
			?>
		 <section>
				<h2><?php _e(OSE_WORDPRESS_ANTISPAM_CONFIG, 'ose_wp_firewall'); ?></h2>
				<div class="inside">
					<table class="widefat">
						<tr valign="top">
							<th scope="row"><?php _e(OSE_ENABLE_SFSPAM, 'ose_wp_firewall'); ?>
							</th>
							<td><select name="ose_wp_firewall_antispam[osefirewall_sfspam]">
									<option  <?php selected( '1', $osefirewall_sfspam ); ?> value="1">
										<?php _e(OSE_YES, 'ose_wp_firewall'); ?>
									</option>
									<option  <?php selected( '0', $osefirewall_sfspam ); ?> value="0">
										<?php _e(OSE_NO, 'ose_wp_firewall'); ?>
									</option>
							</select></td>
						</tr>
						<tr valign="top">
							<th scope="row">
							<?php _e(OSE_SFS_CONFIDENCE , 'ose_wp_firewall'); ?></th>
							<td>
								<input type="text" size = "50" name="ose_wp_firewall_antispam[sfs_confidence]" value="<?php echo $sfs_confidence; ?>" />
							</td>
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
	</div>
	<!-- /poststuff -->
</div>
<!-- /wrap -->