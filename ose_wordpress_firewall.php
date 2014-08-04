<?php
/*
Plugin Name: Centrora Security
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: Centrora Security (previously OSE Firewall) - A WordPress Security Firewall plugin created by Centrora. Protect your WordPress site by identify any malicious codes, spam, virus, SQL injection, and security vulnerabilities. If you are managing multiple sites, try out <a href='www.centrora.com/centrora-features'>Centrora Panel</a> for multiple sites security management.  
Author: Centrora (Previously ProWeb)
Version: 3.6.1
Author URI: http://www.centrora.com/
*/

// Basic configuration; 
define('ODS', DIRECTORY_SEPARATOR);
define('OFRONTENDSCAN', false);
define('OSEFWDIR', plugin_dir_path(__FILE__) );
require_once (OSEFWDIR.ODS.'protected'.ODS.'config'.ODS.'define.php');
require_once (OSE_FWFRAMEWORK.ODS.'oseFirewallWordpress.php');
require_once (OSEFWDIR.ODS.'protected'.ODS.'library'.ODS.'RemoteLogin'.ODS.'RemoteLogin.php');
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
    $signatureUpdate = oRequest :: getInt('signatureUpdate', 0);
    $safeBrowsingUpdate = oRequest :: getInt('safeBrowsingUpdate', 0);
    $userID = null;
    if($remote > 0) {
    	$remoteLogin = new RemoteLogin();
    	$userInfo = $remoteLogin -> login();
    }
    else
    {
		if ($ready == true) 
		{
			$oseFirewall->runReport (); 
			if ($signatureUpdate == 1)
			{
				$remoteLogin = new RemoteLogin();
				$remoteLogin->updateSignature();
			}
			else if ($safeBrowsingUpdate  == 1)
			{
				$remoteLogin = new RemoteLogin();
				$remoteLogin->updateSafeBrowsing();
			}
			else
			{
				$oseFirewall -> enhanceSysSecurity();
				$isAdvanceFirewallScanner = $oseFirewall->isAdvanceFirewallSettingEnable();
				if($isAdvanceFirewallScanner == true){
					oseFirewall::callLibClass('fwscanner','fwscannerbs');
					oseFirewall::callLibClass('fwscanner','fwscannerad');
					
					$oseFirewallScanner = new oseFirewallScannerBasic ();
			    	$oseFirewallScanner ->hackScan(); 
			    	
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
}
if (!class_exists('GoogleAuthenticator', false))
{
	$oseFirewall -> initGAuthenticator ();
}