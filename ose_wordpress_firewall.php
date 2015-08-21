<?php
/*
Plugin Name: Centrora Security
Plugin URI: http://wordpress.org/extend/plugins/ose-firewall/
Description: Centrora Security (previously OSE Firewall) - A WordPress Security Firewall plugin created by Centrora. Protect your WordPress site by identify any malicious codes, spam, virus, SQL injection, and security vulnerabilities.
Author: Centrora (Previously ProWeb)
Version: 5.0.8
Author URI: http://www.centrora.com/
//@todo change Icon URI
Icon URI: https://secure.gravatar.com/avatar/26d6eb39bd2613b318ad7a64f3641480?d=mm&s=300&r=G
*/
if (version_compare(PHP_VERSION, '5.3.0') < 0) {
	die("<div style='background-color: #fff;
				    border-left: 4px solid #ffba00;
				    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
				    display: inline-block;
				    font-size: 14px;
				    line-height: 19px;
				    margin: 25px auto 0 300px;
				    padding: 11px 15px;
    				text-align: left; 
					width: 800px;'>Centrora requires PHP 5.3.0, please contact your hosting company to update your PHP version. It will take them 5 seconds to do so.</div>");
}
if (function_exists("ini_set")) {
    ini_set("display_errors", "off");
}
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
		/*
		 * Attach Centrora to MainWP as an extension if MainWP Main or MainWP Child is installed and active
		 */
		$oseFirewall -> loadBackendFunctions ();
		if (file_exists(dirname(OSEAPPDIR).'/mainwp/mainwp.php') || file_exists(dirname(OSEAPPDIR).'/mainwp-child/mainwp-child.php')) {	
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( 'mainwp/mainwp.php' ) || is_plugin_active( 'mainwp-child/mainwp-child.php')) {
				oseFirewall::callLibClass('multisites', 'mainwp');
				$CentroraMainWP = new CentroraMainWP(__FILE__);
			}
		}
		oseFirewall::checkHtaccess();
        oseFirewall::loadRequest();
		$task = oRequest :: getVar('task', null);
        $filename = oRequest:: getVar('filename', null);

		if ($task == 'downloadBackupFile') {
			oseFirewall::callLibClass('backup', 'oseBackup');
			oseBackupManager::downloadBackupFile();
        } elseif ($task == 'downloadCSV') {
            oseFirewall::callLibClass('firewallstat', 'firewallstatBase');
            $oseFirewallStat = new oseFirewallStatBase();
            $oseFirewallStat->downloadcsv($filename);
        }
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
    $runBackup = oRequest :: getInt('runBackup', 0);
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
                $type = oRequest :: getInt('type', 0);
				$remoteLogin = new RemoteLogin();
				$remoteLogin->vsScanning($step, $type);
			}
            else if ($runBackup == 1)
            {
                $cloudbackuptype = oRequest :: getInt('cloudbackuptype', 0);
				$upload = oRequest :: getInt('upload', 0);
				$fileNum = oRequest :: getInt('fileNum', 0);
                $remoteLogin = new RemoteLogin();
                $remoteLogin->runBackup($cloudbackuptype, $upload , $fileNum );
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
$bfconfig = oseFirewall::getConfiguration('bf');
$status = $bfconfig['data']['bf_status'];
if(!empty($status)) {
    oseFirewall::callLibClass('fwscanner', 'fwscanner');
    $fwscanner = new oseFirewallScanner();
    add_filter('authenticate', array($fwscanner, 'antiBruteForce'), 40, 3);
}
if (!class_exists('GoogleAuthenticator', false))
{
	$oseFirewall -> initGAuthenticator ();
}
function destroy_session()
{
    session_destroy();
}
function checkIPstatus()
{
    /*
        * Check IP status to protect against brute force attack
        */
    $bfconfig = oseFirewall::getConfiguration('bf');
    $status = $bfconfig['data']['bf_status'];
    if(!empty($status)) {
        oseFirewall::callLibClass('fwscanner', 'fwscanner');
        $fwscanner = new oseFirewallScanner();
        if ($fwscanner->ipStatus == 4) {
            $fwscanner->showBanPage();
        }
    }
}
add_action('wp_logout', 'destroy_session');
$ready = oseFirewall::isDBReady();
if($ready == true){
    add_action('wp_loaded', 'checkIPstatus');
}

//add_action( 'admin_notices', 'display_notice');
//
//function display_notice(){
//    include(  OSE_FWASSETS.'/views/template/notice/template-notice-main.php' );
//}

/*
//\PHPBenchmark\Monitor::instance()->snapshot('After loading Centrora');
 */