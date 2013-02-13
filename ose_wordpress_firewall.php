<?php
/*
Plugin Name: OSE Firewall
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: OSE Firewall - A WordPress Firewall created by Open Source Excellence. It protects your WordPress-powered blog against attacks and hacking. The email alert / notification function is disabled by default, while it can be activated and configured in <strong>Settings -> OSE Firewall</strong>. Please go to your <a href="options-general.php?page=ose_wp_firewall">OSE Firewall configuration</a> page.
Author: Open Sourcce Excellence
Version: 1.5.0
Author URI: http://www.opensource-excellence.com/
*/
define('DS', DIRECTORY_SEPARATOR);
define('OSEFWDIR', WP_PLUGIN_DIR.DS.dirname( plugin_basename( __FILE__ ) ) );
// Initialise
require_once (OSEFWDIR.DS.'library'.DS.'define.php');
require_once (OSEFWLIBRARY.DS.'osewphelper.php');
require_once (OSEFWLIBRARY.DS.'osewpfunctions.php');
require_once (OSEFWLIBRARY.DS.'osewpUtils.php');

if (isset($_GET['action']) && $_GET['action'] =='activate') {
	ose_wp_firewallinstall();
}
else
{	
	if(! osewpUtils::isAdmin()){ 
		return; 
	}
	$osewphelper = new OSEWPhelper(); 
	$osewphelper->loadLanguage(); 
	$osewphelper->addwpactions();
	if (!isset($_GET['action']) && $_GET['action'] !='activate') {
		$osewphelper->loadadminmenu();
	}
	
	
	foreach(array('activate', 'scan', 'scanvs','showtotal','showinfected') as $func){
		add_action('wp_ajax_osefirewall_' . $func, 'osewpUtils::ajaxReceiver');
	}
	require_once (OSEFWLIBRARY.DS.'oseJSON.php');
	require_once (OSEFWLIBRARY.DS.'osewpScanEngine.php');
}

