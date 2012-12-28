<?php
/*
Plugin Name: OSE Firewall
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: OSE Firewall - A WordPress Firewall created by Open Source Excellence. It protects your WordPress-powered blog against attacks and hacking. The email alert / notification function is disabled by default, while it can be activated and configured in <strong>Settings -> OSE Firewall</strong>. Please go to your <a href="options-general.php?page=ose_wp_firewall">OSE Firewall configuration</a> page.
Author: Open Sourcce Excellence
Version: 1.0.2
Author URI: http://www.opensource-excellence.com/
*/
define('DS', DIRECTORY_SEPARATOR);
// Initialise
$curlang = get_bloginfo('language');
$langfile = WP_PLUGIN_DIR.DS.dirname( plugin_basename( __FILE__ ) ) . DS.'languages'.DS.$curlang.'.php';
if (file_exists($filename))  
{
	require_once($langfile);
}
else
{
	require_once(WP_PLUGIN_DIR.DS.dirname( plugin_basename( __FILE__ ) ) . DS.'languages'.DS.'en-GB.php');
}

$blog_wpurl  = get_bloginfo('wpurl');
$blog_name   = get_bloginfo('name');
$admin_email = get_option('admin_email');

/* Attack filter */
function ose_wp_firewallfilter($content){
	require_once(WP_PLUGIN_DIR.DS.dirname( plugin_basename( __FILE__ ) ) . DS.'library'.DS.'scan.php');
	$settings  = (array) get_option( 'ose_wp_firewall_settings' );
	global $admin_email, $blog_name; 
	$osefirewall = new oseWPFirewall($settings, $admin_email, $blog_name);  
	$osefirewall -> scan(); 
}

add_action('posts_selection', 'ose_wp_firewallfilter');

function ose_wp_firewallinstall(){
}
if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
	ose_wp_firewallinstall();
}
add_action( 'admin_init', 'ose_wp_firewallinit' );
// Initialise
function ose_wp_firewallinit() {
	register_setting( 'ose_wp_firewall_settings_group', 'ose_wp_firewall_settings', 'ose_wp_firewallvalidation');
}
function ose_wp_firewallvalidation( $input ) {
	$input['osefirewall_email'] = wp_filter_nohtml_kses( $input['osefirewall_email'] );
	return $input;
}
add_filter( 'plugin_action_links', 'ose_wp_firewallsettings_link', 10, 2 );
function ose_wp_firewallsettings_link( $links, $file ) {
	static $this_plugin;

	if( empty( $this_plugin ) )
		$this_plugin = plugin_basename( __FILE__ );

	if ( $file == $this_plugin )
		$links[] = '<a href="' . admin_url( 'options-general.php?page=ose_wp_firewall' ) . '">' . __( 'Settings', 'ose_wp_firewall' ) . '</a>';

	return $links;
}
add_action('admin_menu', 'ose_wp_firewallplugin_menu');
function ose_wp_firewallplugin_menu() {
    add_options_page(OSE_WORDPRESS_FIREWALL, OSE_WORDPRESS_FIREWALL, 'manage_options', 'ose_wp_firewall', 'ose_wp_firewall_settings');
}
function ose_wp_firewall_settings() {
	global $admin_email, $blog_wpurl;
	include_once WP_PLUGIN_DIR.DS.dirname( plugin_basename( __FILE__ ) ) . DS.'templates'.DS.'settings'.DS.'settings.php';
}
function ose_wp_firewallload_languages() {
	load_plugin_textdomain( 'ose_wordpress_firwall', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}
add_action( 'init', 'ose_wp_firewallload_languages' );

