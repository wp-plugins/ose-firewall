<div id="container">
	<div id = "stage">
	<div id="header">
		<img src="<?php echo OSEFWURL.'/assets/images/scan.png'?>" alt="<?php _e(OSE_VIRUS_SCAN, 'ose_wordpress_firwall'); ?>">
		<h1> <?php _e( OSE_WORDPRESS_FIREWALL_SETTING, 'ose_wordpress_firwall' ); ?></h1>
		<p> <?php _e( OSE_PLEASE_CONFIG_FIREWALL, 'ose_wp_firewall' ); ?></p>
	</div>	
	<div class="clear" id="poststuff" style="width: 98%;">
		<form method="post" action="options.php">
		<?php
			// Get Setting from system; 
			settings_fields( 'ose_wp_firewall_settings_group' );
			$settings = get_option( 'ose_wp_firewall_settings' );
			$osefirewall_email           = isset( $settings['osefirewall_email'] ) ? $settings['osefirewall_email'] : $admin_email;
			$osefirewall_blockbl_method  = isset( $settings['osefirewall_blockbl_method'] ) ? $settings['osefirewall_blockbl_method'] : false;
			$osefirewall_checkmua  = isset( $settings['osefirewall_checkmua'] ) ? $settings['osefirewall_checkmua'] : false;
			$osefirewall_serverip  = isset( $settings['osefirewall_serverip'] ) ? $settings['osefirewall_serverip'] : $_SERVER['SERVER_ADDR'];
			$osefirewall_checkdos  = isset( $settings['osefirewall_checkdos'] ) ? $settings['osefirewall_checkdos'] : false;
			$osefirewall_checkdfi  = isset( $settings['osefirewall_checkdfi'] ) ? $settings['osefirewall_checkdfi'] : false;
			$osefirewall_checkrfi  = isset( $settings['osefirewall_checkrfi'] ) ? $settings['osefirewall_checkrfi'] : false;
			$osefirewall_checktrasversal   = isset( $settings['osefirewall_checktrasversal'] ) ? $settings['osefirewall_checktrasversal'] : false;
			$osefirewall_checkjsinjection  = isset( $settings['osefirewall_checkjsinjection'] ) ? $settings['osefirewall_checkjsinjection'] : false;
			$osefirewall_checksqlinjection  = isset( $settings['osefirewall_checksqlinjection'] ) ? $settings['osefirewall_checksqlinjection'] : false;
			$osefirewall_query_too_long  = isset( $settings['osefirewall_query_too_long'] ) ? $settings['osefirewall_query_too_long'] : false;
			$osefirewall_blockpage       = isset( $settings['osefirewall_blockpage'] ) ? $settings['osefirewall_blockpage'] : 'osefirewall_logo';
			$osefirewall_whitelistvars       = isset( $settings['osefirewall_whitelistvars'] ) ? $settings['osefirewall_whitelistvars'] : '';
			$osefirewall_maxtolerance       = isset( $settings['osefirewall_maxtolerance'] ) ? $settings['osefirewall_maxtolerance'] : 5;
			$osefirewall_customban = isset($settings['osefirewall_customban'])?$settings['osefirewall_customban']:'';
			$osefirewall_mode = isset($settings['osefirewall_mode'])?$settings['osefirewall_mode']:1;
			$osefirewall_suitepath = isset($settings['osefirewall_suitepath'])?$settings['osefirewall_suitepath']:'';
			?>
		 <section>
				<h2><?php _e(NOTIFICATION_EMAIL_ATTACKS, 'ose_wp_firewall'); ?></h2>
				<div class="inside">
					<table class="widefat">
						<tr valign="top">
							<th scope="row">
							<?php _e(EMAIL_ADDRESS , 'ose_wp_firewall'); ?></th>
							<td>
								<input type="text" size = "50" name="ose_wp_firewall_settings[osefirewall_email]" value="<?php echo $osefirewall_email; ?>" />
							</td>
						</tr>
					</table>
				</div>
		</section>
			
		<section>
				<h2><?php _e(FIREWALL_SCANNING_OPTIONS, 'ose_wp_firewall'); ?></h2>
				<div class="inside">
					<table class="widefat">
					
					<tr valign="top">
							<th scope="row"><?php _e(OSE_PROTECTION_MODE, 'ose_wp_firewall'); ?>
							</th>
							<td><select name="ose_wp_firewall_settings[osefirewall_mode]">
									<option  <?php selected( '1', $osefirewall_mode ); ?> value="0">
										<?php _e(OSE_DEVELOPMENT, 'ose_wp_firewall'); ?>
									</option>
									<option  <?php selected( '1', $osefirewall_mode ); ?> value="1">
										<?php _e(OSE_FIREWALL_ONLY, 'ose_wp_firewall'); ?>
									</option>
									<option  <?php selected( '2', $osefirewall_mode ); ?> value="2">
										<?php _e(OSE_SECSUITE_ONLY, 'ose_wp_firewall'); ?>
									</option>
									<option  <?php selected( '3', $osefirewall_mode ); ?> value="3">
										<?php _e(OSE_FWANDSUITE, 'ose_wp_firewall'); ?>
									</option>
							</select></td>
						</tr>
					
						<tr valign="top">
							<th scope="row"><?php _e(OSE_SUITE_PATH, 'ose_wp_firewall'); ?>
							</th>
							<td><textarea name="ose_wp_firewall_settings[osefirewall_suitepath]" id ="osefirewall_suitepath" ><?php echo $osefirewall_suitepath; ?> </textarea></td>
						</tr>
						
					
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(BLOCKBL_METHOD, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" name="ose_wp_firewall_settings[osefirewall_blockbl_method]" value="1" id="osefirewall_blockbl_method"  <?php checked( '1', $osefirewall_blockbl_method ); ?> /></td>
						</tr>
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(CHECK_MUA, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" name="ose_wp_firewall_settings[osefirewall_checkmua]" value="1" id="osefirewall_checkmua"  <?php checked( '1', $osefirewall_checkmua ); ?> /></td>
						</tr>				
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(SERVERIP, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="text" name="ose_wp_firewall_settings[osefirewall_serverip]" id="osefirewall_serverip"  value= "<?php echo $osefirewall_serverip; ?>" /></td>
						</tr>										
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(checkTrasversal, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" name="ose_wp_firewall_settings[osefirewall_checktrasversal]" value="1" id="osefirewall_checktrasversal"  <?php checked( '1', $osefirewall_checktrasversal ); ?> /></td>
						</tr>
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(checkDOS, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" name="ose_wp_firewall_settings[osefirewall_checkdos]" value="1" id="osefirewall_checkdos"  <?php checked( '1', $osefirewall_checkdos ); ?> /></td>
						</tr>
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(checkDFI, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" name="ose_wp_firewall_settings[osefirewall_checkdfi]" value="1" id="osefirewall_checkdfi"  <?php checked( '1', $osefirewall_checkdfi ); ?> /></td>
						</tr>
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(checkRFI, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" name="ose_wp_firewall_settings[osefirewall_checkrfi]" value="1" id="osefirewall_checkrfi"  <?php checked( '1', $osefirewall_checkrfi ); ?> /></td>
						</tr>
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(checkJSInjection, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" name="ose_wp_firewall_settings[osefirewall_checkjsinjection]" value="1" id="osefirewall_checkjsinjection"  <?php checked( '1', $osefirewall_checkjsinjection ); ?> /></td>
						</tr>
						<tr valign="top" class="alternate">
							<th scope="row"><?php _e(checkSQLInjection, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" name="ose_wp_firewall_settings[osefirewall_checksqlinjection]" value="1" id="osefirewall_checksqlinjection"  <?php checked( '1', $osefirewall_checksqlinjection ); ?> /></td>
						</tr>
					</table>
				</div>
		</section>
		
		<!-- /Notifications for attacks type -->
		<section>
				<h2><?php _e(OTHER_SETTING, 'ose_wp_firewall'); ?></h2>
				<div class="inside">
					<table class="widefat">
						<tr valign="top">
							<th scope="row"><?php _e(BLOCK_QUERY_LONGER_THAN_255CHAR, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="checkbox" 
									   name="ose_wp_firewall_settings[osefirewall_query_too_long]" 
									   value="1" 
									   id="osefirewall_query_too_long"
								<?php checked( '1', $osefirewall_query_too_long ); ?> />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e(BLOCK_PAGE, 'ose_wp_firewall'); ?>
							</th>
							<td><select name="ose_wp_firewall_settings[osefirewall_blockpage]">
									<option  <?php selected( 'osefirewall_logo', $osefirewall_blockpage ); ?> value="osefirewall_logo">
										<?php _e(OSE_BAN_PAGE, 'ose_wp_firewall'); ?>
									</option>
									<option  <?php selected( 'osefirewall_blank', $osefirewall_blockpage ); ?> value="osefirewall_blank">
										<?php _e(BLANK_PAGE, 'ose_wp_firewall'); ?>
									</option>
									<option  <?php selected( 'osefirewall_403error', $osefirewall_blockpage ); ?> value="osefirewall_403error">
										<?php _e(ERROR403_PAGE, 'ose_wp_firewall'); ?>
									</option>
									<option  <?php selected( 'osefirewall_custom', $osefirewall_blockpage ); ?> value="osefirewall_custom">
										<?php _e(CUSTOM_BANNING_MESSAGE, 'ose_wp_firewall'); ?>
									</option>									
							</select></td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e(WHITELIST_VARS, 'ose_wp_firewall'); ?>
							</th>
							<td><textarea name="ose_wp_firewall_settings[osefirewall_whitelistvars]" id ="osefirewall_whitelistvars" ><?php echo $osefirewall_whitelistvars; ?> </textarea></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e(MAX_TOLERENCE, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="text" name="ose_wp_firewall_settings[osefirewall_maxtolerance]" id ="osefirewall_maxtolerance" value ="<?php echo $osefirewall_maxtolerance; ?>" /></td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e(CUSTOM_BANNING_MESSAGE, 'ose_wp_firewall'); ?>
							</th>
							<td><div id="postdivrich" class="postarea">
								<?php wp_editor($osefirewall_customban, 'ose_wp_firewall_settings[osefirewall_customban]', array('dfw' => true, 'tabfocus_elements' => '', 'editor_height' => 360) ); ?>
								</div>
							</td>
						</tr>
						
					</table>
				</div>
		</section>
		<section>
				<h2><?php _e(TEST_CONFIGURATION, 'ose_wp_firewall'); ?></h2>
				<div class="inside">
					<table class="widefat">
						<tr valign="top">
							<th scope="row">
							<?php printf( __( '%1$s'.TEST_CONFIGURATION_NOW.'%2$s', 'ose_wp_firewall' ), '<a href="'.OSEWPURL.'/index.php?s=<script>alert(31337)</script>" target="_blank">', '</a>' ); ?>
							</th>
						</tr>
					</table>
				</div>
		</section>
		<!-- /Test configuration -->
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