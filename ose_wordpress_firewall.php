<?php
/*
Plugin Name: Centrora Security
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: Centrora Security (previously OSE Firewall) - A WordPress Security Firewall plugin created by Centrora. Protect your WordPress site by identify any malicious codes, spam, virus, SQL injection, and security vulnerabilities. If you are managing multiple sites, try out <a href='www.centrora.com/centrora-features'>Centrora Panel</a> for multiple sites security management.  
Author: Centrora (Previously ProWeb)
Version: 4.1.2
Author URI: http://www.centrora.com/
*/

// Basic configuration; 
define('ODS', DIRECTORY_SEPARATOR);
//require_once(dirname(__FILE__).ODS.'benchmark/init.php');
//\PHPBenchmark\Monitor::instance()->snapshot('Before loading Centrora');
define('OFRONTENDSCAN', false);
define('OSEFWDIR', plugin_dir_path(__FILE__) );
require_once (OSEFWDIR.ODS.'assets'.ODS.'config'.ODS.'define.php');
require_once (OSE_FWFRAMEWORK.ODS.'oseFirewallWordpress.php');
// Do a pre-requisity check for PHP version;
$ready = oseFirewall::preRequisitiesCheck();
if ($ready == false)
{	
	if (oseFirewall::isBackendStatic())
	{	
		oseFirewall::showNotReady();
	}
	else 
	{
		return;	
	}
}
// If PHP 5.3.0 is satisfied, continue;
require_once (OSEFWDIR.ODS.'classes'.ODS.'Library'.ODS.'RemoteLogin'.ODS.'RemoteLogin.php');
require_once (OSEFWDIR.'/vendor/autoload.php');
// Load the OSE Framework ;
$oseFirewall = new oseFirewall();
$systemReady = $oseFirewall -> checkSystem () ;
$oseFirewall -> initSystem ();

if ($oseFirewall -> isBackend () && $oseFirewall -> isAdminAjax () == false)
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
	$ready = oseFirewall::isDBReady();
	oseFirewall::loadRequest();
	$remote = oRequest :: getInt('remoteLogin', 0);
	$signatureUpdate = oRequest :: getInt('signatureUpdate', 0);
	$safeBrowsingUpdate = oRequest :: getInt('safeBrowsingUpdate', 0);
	$vsScanning = oRequest :: getInt('vsScanning', 0);
	$verifyKey = oRequest::getInt('verifyKey', 0);
	$updateProfile = oRequest::getInt('updateProfile', 0);
	$userID = null;
	if($remote > 0) {
		$remoteLogin = new RemoteLogin();
		$userInfo = $remoteLogin -> login();
	}
	else
	{
		if ($ready == true)
		{
			//$oseFirewall->runReport ();
			if ($verifyKey == true)
			{
				$remoteLogin = new RemoteLogin();
				$remoteLogin->verifyKey();
			}
			else if ($updateProfile == true)
			{
				$profileID = oRequest::getVar('profileID', null);
				$profileStatus = oRequest::getVar('profileStatus', null);
				$remoteLogin = new RemoteLogin();
				$remoteLogin->updateProfile($profileID, $profileStatus);
			}
			else if ($signatureUpdate == 1)
			{
				$remoteLogin = new RemoteLogin();
				$remoteLogin->updateSignature();
			}
			else if ($safeBrowsingUpdate  == 1)
			{
				$remoteLogin = new RemoteLogin();
				$remoteLogin->updateSafeBrowsing();
			}
			else if ($vsScanning == 1)
			{
				$step = oRequest :: getInt('step', 0);
				$remoteLogin = new RemoteLogin();
				$remoteLogin->vsScanning($step);
			}
			else
			{
				$oseFirewall -> enhanceSysSecurity();
				$isAdvanceFirewallScanner = $oseFirewall->isAdvanceFirewallSettingEnable();
				if($isAdvanceFirewallScanner == true){
					oseFirewall::callLibClass('fwscanner', 'fwscannerad');
					$oseFirewallScanner = new oseFirewallScannerAdvance();
					$oseFirewallScanner->hackScan();
				}
				else
				{
					oseFirewall::callLibClass('fwscanner', 'fwscannerbs');
					$oseFirewallScanner = new oseFirewallScannerBasic();
					$oseFirewallScanner->hackScan();
				}
			}
		}
	}
}
if (!class_exists('GoogleAuthenticator', false))
{
	$oseFirewall -> initGAuthenticator ();
}
/*
//\PHPBenchmark\Monitor::instance()->snapshot('After loading Centrora');
 */