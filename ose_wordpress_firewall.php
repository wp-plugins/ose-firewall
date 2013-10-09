<?php
/*
Plugin Name: OSE Firewall Security
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: OSE Firewall Security - A WordPress Security Firewall created by ProWeb (Protect Website). It protects your WordPress-powered blog against attacks and hacking. The email alert / notification function is disabled by default, while it can be activated and configured in <strong>Settings -> OSE Firewall Security</strong>. Please go to your <a href="admin.php?page=ose_wp_firewall">OSE Firewall Security configuration</a> page.
Author: ProWeb (Protect Website)
Version: 2.2.2
Author URI: http://www.protect-website.com/
*/

// Basic configuration; 
define('DS', DIRECTORY_SEPARATOR);
define('OSEFWDIR', plugin_dir_path(__FILE__) );
require_once (OSEFWDIR.DS.'protected'.DS.'config'.DS.'define.php');
require_once (OSE_FWFRAMEWORK.DS.'oseFirewallWordpress.php');
// Load the OSE Framework ; 
$oseFirewall = new oseFirewall(); 

$oseFirewall -> initSystem ();
if ($oseFirewall -> isBackend ())
{ 
	$oseFirewall -> loadBackendFunctions ();
}
else
{
	oseFirewall::runYiiApp();
    $ready = oseFirewall::isDBReady(); 
    	
	if ($ready == true) 
	{
	    oseFirewall::callLibClass('fwscanner','fwscannerbs'); 	
	    $oseFirewallScanner = new oseFirewallScannerBasic (); 
	    $oseFirewallScanner ->hackScan(); 
	}
}
