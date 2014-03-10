<?php
/*
Plugin Name: Centrora Security
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: Centrora Security (previously OSE Firewall) - A WordPress Security Firewall created by ProWeb (Protect Website). It protects your WordPress-powered blog against attacks and hacking. The email alert / notification function is disabled by default, while it can be activated and configured in <strong>Settings -> OSE Firewall Security</strong>. Please go to your <a href="admin.php?page=ose_wp_firewall">OSE Firewall Security configuration</a> page.
Author: Centrora (Previously Protect Website)
Version: 3.0.0 beta1
Author URI: http://www.protect-website.com/
*/

// Basic configuration; 
define('ODS', DIRECTORY_SEPARATOR);
define('OFRONTENDSCAN', false);
define('OSEFWDIR', plugin_dir_path(__FILE__) );
require_once (OSEFWDIR.ODS.'protected'.ODS.'config'.ODS.'define.php');
require_once (OSE_FWFRAMEWORK.ODS.'oseFirewallWordpress.php');
require_once (OSEFWDIR.ODS.'protected'.ODS.'library'.ODS.'RemoteLogin'.ODS.'RemoteLogin.php');
require_once(OSEFWDIR.ODS.'protected'.ODS.'library'.ODS.'googleAuthenticator'.ODS.'class_gauthenticator.php');
// Load the OSE Framework ; 
$oseFirewall = new oseFirewall(); 
$systemReady = $oseFirewall -> checkSystem () ;
$oseFirewall -> initSystem ();

if ($oseFirewall -> isBackend ())
{ 
	if ($systemReady[0] == false)
	{
		$oseFirewall -> loadBackendBasicFunctions(); 
		echo $systemReady[1]; 
		exit; 
	}
	else 
	{
		$oseFirewall -> loadBackendFunctions ();
	}
}
else if ($systemReady[0] == true)
{
	
	oseFirewall::runYiiApp();
    $ready = oseFirewall::isDBReady(); 
    oseFirewall::loadRequest();	
    $remote = oRequest :: getInt('remoteLogin', 0);
    $userID = null;
    if($remote > 0) {
    	$remoteLogin = new RemoteLogin();
    	$userInfo = $remoteLogin -> login();
    }
    else
    {
		if ($ready == true) 
		{
			$isAdvanceFirewallScanner = $oseFirewall->isAdvanceFirewallSettingEnable();
			if($isAdvanceFirewallScanner == true){
				oseFirewall::callLibClass('fwscanner','fwscannerad');
				$oseFirewallScanner = new oseFirewallScannerAdvance ();
		    	$oseFirewallScanner ->hackScan(); 
			}else{
				oseFirewall::callLibClass('fwscanner','fwscannerbs');
				$oseFirewallScanner = new oseFirewallScannerBasic ();
		    	$oseFirewallScanner ->hackScan(); 
			}
		}
    }
}
$oseFirewall -> initGAuthenticator ();