<?php
/**
* @version     2.0 +
* @package       Open Source Excellence Security Suite
* @subpackage    Open Source Excellence WordPress Firewall
* @author        Open Source Excellence {@link http://www.opensource-excellence.com}
* @author        Created on 01-Jun-2013
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*
*
*  This program is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
*/
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS . 'wordpress.php');
class oseFirewall extends oseWordPress {
	private static $option = 'ose_firewall';
	public function __construct () {
		$debug = $this->getDebugMode(); 
		$this->setDebugMode ($debug);
	}
	public function getDebugMode () {
		global $wpdb;
		$query = "SELECT `value` FROM `".$wpdb->prefix."ose_secConfig` WHERE `key` = 'debugMode' AND `type` = 'scan'";
		$result =  $wpdb->get_var($query);
    	return (empty($result) || ($result==1))?false:true;
	}
	public function loadBackendFunctions()
    {
    	$this -> addMenuActions () ;
    	$this -> loadAjax (); 
    	self :: loadLanguage () ;
    	self :: loadJSON (); 
    }
    public static function loadInstaller () {
    	require_once (OSE_FWFRAMEWORK.DS . 'oseFirewallInstaller.php');
    }
    private function addMenuActions () {
    	add_action('admin_menu', 'oseFirewall::showmenus');
    } 
    private function loadAjax () {
    	require_once (dirname(__FILE__).DS.'oseFirewallAjax.php');
    	oseFirewallAjax :: loadAppActions();  
    }
    public static function loadLanguage () {
    	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS.'language'.DS . 'oseLanguage.php');
    	$lang = self::getLocale (); 
    	if (file_exists (OSE_FWLANGUAGE.DS . $lang.'.php'))
    	{
    		require_once (OSE_FWLANGUAGE.DS . $lang.'.php');
    	}
    	else
    	{
    		require_once (OSE_FWLANGUAGE.DS . 'en_US.php');
    	}
    }
    public static function isDBReady () {
    	$oseDB2 = self::getDBO();
    	$data = $oseDB2 -> isTableExists ('#__ose_secConfig'); 
    	$ready = (!empty($data))?true:false;
		if ($ready == true)
		{
	    	$geoipState = self::getGeoIPState();
	    	$ready = ($geoipState == true)? false:true;
		} 
		return $ready; 
    }
    private static function getGeoIPState() {
    	$oseDB2 = self::getDBO();
    	$query = "SELECT COUNT(`id`) as `count` FROM `#__ose_app_geoip` ";
    	$oseDB2->setQuery($query);
    	$result = $oseDB2->loadResult();
    	$return = ($result['count']>0)?true:false;
    	return $return;
    }
    public static function showmenus(){
    	add_menu_page( OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL, 'manage_options', 'ose_firewall', 'oseFirewall::dashboard', OSE_FWURL.'/public/images/favicon.ico' );
		add_submenu_page( 'ose_firewall', MANAGE_IPS, MANAGE_IPS, 'manage_options', 'ose_fw_manageips', 'oseFirewall::manageips' );
		add_submenu_page( 'ose_firewall', RULESETS, RULESETS, 'manage_options', 'ose_fw_rulesets', 'oseFirewall::rulesets' );
		add_submenu_page( 'ose_firewall', VARIABLES, VARIABLES, 'manage_options', 'ose_fw_variables', 'oseFirewall::variables' );
		add_submenu_page( 'ose_firewall', ANTIVIRUS, ANTIVIRUS, 'manage_options', 'ose_fw_vsscan', 'oseFirewall::vsscan' );
		add_submenu_page( 'ose_firewall', VSREPORT, VSREPORT, 'manage_options', 'ose_fw_vsreport', 'oseFirewall::vsreport' );
		add_submenu_page( 'ose_firewall', CONFIGURATION, CONFIGURATION, 'manage_options', 'ose_fw_configuration', 'oseFirewall::configuration' );
		add_submenu_page( 'ose_fw_configuration', SEO_CONFIGURATION, SEO_CONFIGURATION, 'manage_options', 'ose_fw_seoconfig', 'oseFirewall::seoconfig' );
		add_submenu_page( 'ose_fw_configuration', SCAN_CONFIGURATION, SCAN_CONFIGURATION, 'manage_options', 'ose_fw_scanconfig', 'oseFirewall::scanconfig' );
		add_submenu_page( 'ose_fw_configuration', ANTIVIRUS_CONFIGURATION, ANTIVIRUS_CONFIGURATION, 'manage_options', 'ose_fw_avconfig', 'oseFirewall::avconfig' );
		add_submenu_page( 'ose_fw_configuration', ANTISPAM_CONFIGURATION, ANTISPAM_CONFIGURATION, 'manage_options', 'ose_fw_spamconfig', 'oseFirewall::spamconfig' );
		add_submenu_page( 'ose_fw_configuration', EMAIL_CONFIGURATION, EMAIL_CONFIGURATION, 'manage_options', 'ose_fw_emailconfig', 'oseFirewall::emailconfig' );
		add_submenu_page( 'ose_fw_configuration', EMAIL_ADMIN, EMAIL_ADMIN, 'manage_options', 'ose_fw_emailadmin', 'oseFirewall::emailadmin' );
		//add_submenu_page( 'ose_firewall', ANTI_VIRUS_DATABASE_UPDATE, ANTI_VIRUS_DATABASE_UPDATE, 'manage_options', 'ose_fw_versionupdate', 'oseFirewall::updateChecking' );
	}
	public static function dashboard () {
		self::runYiiApp();
		Yii::app()->runController('dashboard/index');
	}
	public static function manageips () {
		self::runYiiApp();
		Yii::app()->runController('manageips/index');
	}
	public static function rulesets () {
		self::runYiiApp();
		Yii::app()->runController('rulesets/index');
	}
	public static function configuration () {
		self::runYiiApp();
		Yii::app()->runController('configuration/index');
	}
	public static function seoconfig () {
		self::runYiiApp();
		Yii::app()->runController('seoconfig/index');
	}
	public static function scanconfig () {
		self::runYiiApp();
		Yii::app()->runController('scanconfig/index');
	}
	public static function spamconfig () {
		self::runYiiApp();
		Yii::app()->runController('spamconfig/index');
	}
	public static function avconfig () {
		self::runYiiApp();
		Yii::app()->runController('avconfig/index');
	}
	public static function emailconfig () {
		self::runYiiApp();
		Yii::app()->runController('emailconfig/index');
	}
	public static function emailadmin () {
		self::runYiiApp();
		Yii::app()->runController('emailadmin/index');
	}
	public static function vsscan () {
		self::runYiiApp();
		Yii::app()->runController('vsscan/index');
	}
	public static function vsreport () {
		self::runYiiApp();
		Yii::app()->runController('scanreport/index');
	}
	public static function variables () {
		self::runYiiApp();
		Yii::app()->runController('variables/index');
	}
	public static function updateChecking () {
		self::runYiiApp();
		Yii::app()->runController('versionupdate/index');
	}
	public static function showLogo () {
		$url = 'http://www.protect-website.com'; 
		$appTitle = 'OSE <span>FIREWALL</span>®'; 
		echo '<div id="logo-labels">
					<h1><a href="'.$url.'" target= "_blank">'.$appTitle.'</a></h1>
					<div id="support"><a href="http://www.protect-website.com/need-help/" target="__blank">Need Help?</a></div>
					<div id="user-manual"><a href="https://www.protect-website.com/user-manual/" target="__blank">User Manual</a></div>
					<div id="need-cleaning"><a href="https://www.protect-website.com/website-malware-removal-services/" target="__blank">Malware Removal</a></div>		
			  </div>';
	}
	public static function callLibClass($folder, $classname)
	{
		require_once (OSE_FWFRAMEWORK. DS . $folder . DS . $classname.'.php');
	}
}