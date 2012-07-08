<?php
/*
 * OSE WordPress Firewall Uninstall
 *
 * @since 1.0
 */

// Check for the 'WP_UNINSTALL_PLUGIN' constant, before executing
if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	exit();
}
$args = array(
	'ose_wordpress_firwall_settings'
);
// Delete options from the database
delete_option( $args );