<?php
/*
Plugin Name: OSE Firewall
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: OSE Firewall - A WordPress Firewall created by Open Source Excellence. It protects your WordPress-powered blog against attacks and hacking. The email alert / notification function is disabled by default, while it can be activated and configured in <strong>Settings -> OSE Firewall</strong>. Please go to your <a href="admin.php?page=ose_wp_firewall">OSE Firewall configuration</a> page.
Author: Open Sourcce Excellence
Version: 2.0.1
Author URI: http://www.opensource-excellence.com/
*/

// Basic configuration; 
define('DS', DIRECTORY_SEPARATOR);
define('OSEFWDIR', plugin_dir_path(__FILE__) );
require_once (OSEFWDIR.DS.'protected'.DS.'config'.DS.'define.php');
require_once (OSE_FWFRAMEWORK.DS.'oseFirewall.php');
// Load the OSE Framework ; 
$oseFirewall = new oseFirewall(true); 

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