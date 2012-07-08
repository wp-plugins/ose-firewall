<div class="wrap">
	<h2> <?php _e( OSE_WORDPRESS_FIREWALL_SETTING, 'ose_wordpress_firwall' ); ?></h2>
	<p> <?php _e( OSE_WORDPRESS_FIREWALL_SETTING_DESC, 'ose_wp_firewall' ); ?></p>
	<div class="clear" id="poststuff" style="width: 560px;">
		<form method="post" action="options.php">
		<?php
			// Get Setting from system; 
			settings_fields( 'ose_wp_firewall_settings_group' );
			$settings = get_option( 'ose_wp_firewall_settings' );
			$osefirewall_email           = isset( $settings['osefirewall_email'] ) ? $settings['osefirewall_email'] : $admin_email;
			$osefirewall_blockbl_method  = isset( $settings['osefirewall_blockbl_method'] ) ? $settings['osefirewall_blockbl_method'] : false;
			$osefirewall_checkmua  = isset( $settings['osefirewall_checkmua'] ) ? $settings['osefirewall_checkmua'] : false;
			$osefirewall_checkdos  = isset( $settings['osefirewall_checkdos'] ) ? $settings['osefirewall_checkdos'] : false;
			$osefirewall_checkdfi  = isset( $settings['osefirewall_checkdfi'] ) ? $settings['osefirewall_checkdfi'] : false;
			$osefirewall_checkrfi  = isset( $settings['osefirewall_checkrfi'] ) ? $settings['osefirewall_checkrfi'] : false;
			$osefirewall_checkjsinjection  = isset( $settings['osefirewall_checkjsinjection'] ) ? $settings['osefirewall_checkjsinjection'] : false;
			$osefirewall_checksqlinjection  = isset( $settings['osefirewall_checksqlinjection'] ) ? $settings['osefirewall_checksqlinjection'] : false;
			$osefirewall_query_too_long  = isset( $settings['osefirewall_query_too_long'] ) ? $settings['osefirewall_query_too_long'] : false;
			$osefirewall_blockpage       = isset( $settings['osefirewall_blockpage'] ) ? $settings['osefirewall_blockpage'] : 'osefirewall_logo';
			$osefirewall_whitelistvars       = isset( $settings['osefirewall_whitelistvars'] ) ? $settings['osefirewall_whitelistvars'] : '';
			?>
		 <div class="postbox">
				<h3 style="cursor: default;">
				<?php _e(NOTIFICATION_EMAIL_ATTACKS, 'ose_wp_firewall'); ?></h3>
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
			</div>
			<!-- /Manage email -->
			<div class="postbox">
				<h3 style="cursor: default;">
				<?php _e(FIREWALL_SCANNING_OPTIONS, 'ose_wp_firewall'); ?></h3>
				<div class="inside">
					<table class="widefat">
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
			</div>
			<!-- /Notifications for attacks type -->
			<div class="postbox">
				<h3 style="cursor: default;">
				<?php _e(OTHER_SETTING, 'ose_wp_firewall'); ?></h3>
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
							</select></td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e(WHITELIST_VARS, 'ose_wp_firewall'); ?>
							</th>
							<td><input type="textarea" name="ose_wp_firewall_settings[osefirewall_whitelistvars]" id ="osefirewall_whitelistvars" value ="<?php echo $osefirewall_whitelistvars; ?>" /></td>
						</tr>
					</table>
				</div>
			</div>
			<!-- /Under the Hood -->
			<div class="postbox">
				<h3 style="cursor: default;">
				<?php _e(TEST_CONFIGURATION, 'ose_wp_firewall'); ?></h3>
				<div class="inside">
					<table class="widefat">
						<tr valign="top">
							<th scope="row">
							<?php printf( __( '%1$s'.TEST_CONFIGURATION_NOW.'%2$s', 'ose_wp_firewall' ), '<a href="'.$blog_wpurl.'/?s=<script>alert(31337)</script>" target="_blank">', '</a>' ); ?>
							</th>
						</tr>
					</table>
				</div>
			</div>
			<!-- /Test configuration -->
			<p class="submit">
				<input type="submit" class="button-primary"
					value="<?php esc_attr_e(SAVE_CHANGES, 'ose_wp_firewall' ) ?>" />
			</p>
		</form>
	</div>
	<!-- /poststuff -->
</div>
<!-- /wrap -->