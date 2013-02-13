<?php
/*
Plugin Name: OSE Firewall
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: OSE Firewall - A WordPress Firewall created by Open Source Excellence. It protects your WordPress-powered blog against attacks and hacking. The email alert / notification function is disabled by default, while it can be activated and configured in <strong>Settings -> OSE Firewall</strong>. Please go to your <a href="options-general.php?page=ose_wp_firewall">OSE Firewall configuration</a> page.
Author: Open Sourcce Excellence
Author URI: http://www.opensource-excellence.com/
*/
// Hook for adding admin menus
add_action('admin_menu', 'of_add_menu');
// action function for above hook
function of_add_menu() {
	// Add a new top-level menu;
	add_menu_page(OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL, 'manage_options', 'options-general.php?page=ose_wp_firewall', '', plugins_url('ose-firewall/assets/favicon.ico') );
	add_submenu_page('options-general.php?page=ose_wp_firewall', OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL_CONFIG, 'manage_options', 'options-general.php?page=ose_wp_firewall_conf' );
	add_submenu_page('options-general.php?page=ose_wp_firewall', OSE_VIRUS_SCAN, OSE_VIRUS_SCAN, 'manage_options', 'options-general.php?page=ose_wp_firewall_avscan' );
	add_submenu_page('options-general.php?page=ose_wp_firewall', OSE_WORDPRESS_VIRUSSCAN_CONFIG, OSE_WORDPRESS_VIRUSSCAN_CONFIG, 'manage_options', 'options-general.php?page=ose_wp_firewall_avconf' );
	
}
?> 