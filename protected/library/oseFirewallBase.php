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
if (class_exists('Sconfig') || class_exists('Jconfig'))
{
	require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'joomla.php');
	class oseFirewallRoot extends oseJoomla
	{
		protected static $option = 'com_ose_firewall';
	}
	define('OSE_CMS', 'joomla');
}
else
{
	require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'wordpress.php');
	class oseFirewallRoot extends oseWordpress
	{
		protected static $option = 'ose_firewall';
	}
	define('OSE_CMS', 'wordpress');
}
class oseFirewallBase extends oseFirewallRoot
{
	public function __construct()
	{
		$debug = $this->getDebugMode();
		$this->setDebugMode ($debug);
	}
	public function initSystem()
	{
		$this->initYiiConfiguration ();
		add_action('init', array($this, 'startSession'), 1);
	}
	public function startSession() {
	    if(!session_id()) {
	        session_start();
	    }
	}
	public function initGAuthenticator()
	{
		/*if (!class_exists('CDbConnection')) 
		{ 
			oseFirewall::runYiiApp(); 
		}*/
		//$enable = true;
		require_once(OSEFWDIR.ODS.'protected'.ODS.'library'.ODS.'googleAuthenticator'.ODS.'class_gauthenticator.php');
		$enable = $this->isGAuthenticatorEnabled();
		if ($enable == true)
		{
			require_once(OSE_FWFRAMEWORK.ODS.'googleAuthenticator'.ODS.'class_gauthenticator.php');
			$gauthenticator = new GoogleAuthenticator();
			add_action('init', array($gauthenticator, 'init'));
		}
	}
	private function isGAuthenticatorEnabled()
	{
		return $this->checkOseConfig ('googleVerification', 'scan'); 
	}
	public function loadBackendFunctions()
	{
		$this->addMenuActions();
		self::loadLanguage();
		self::loadJSON();
		$this->loadAjax();
		$this->loadViews();
	}
	public function loadBackendBasicFunctions()
	{
		$this->addMenuActions();
		self::loadLanguage();
	}
	public static function loadInstaller()
	{
		require_once(OSE_FWFRAMEWORK.ODS.'oseFirewallInstaller.php');
	}
	private function loadAjax()
	{
		require_once(dirname(__FILE__).ODS.'oseFirewallAjax.php');
		oseFirewallAjax::loadAppActions();
	}
	
	public function  isAdvanceFirewallSettingEnable()
	{
		$configEnable = $this->isAdvanceSettingConfigEnable();
		if($configEnable == false){
			return false;
		}
		
		$dbReady = oseFirewallBase :: isAdvanceSettingConfigDBReady();
		if($dbReady == false){
			return false;
		}else{
			return true;
		}  
	}
	
	public static function isAdvanceSettingConfigDBReady()
	{
		$oseDB2 = self::getDBO();
		$data = $oseDB2->isTableExists('#__osefirewall_advancerules');
		if(empty($data))
		{
			return false;
		}
		$query = "SELECT COUNT(*) as `count` FROM `#__osefirewall_advancerules` ";
		$oseDB2->setQuery($query);
		$result = $oseDB2->loadResult();
		return ($result['count'] > 0) ? true : false;
	}
	
	
	public static function isAdvancePatternConfigDBReady()
	{
		$oseDB2 = self::getDBO();
		$data = $oseDB2->isTableExists('#__osefirewall_advancepatterns');
		if(empty($data))
		{
			return false;
		}
		$query = "SELECT COUNT(*) as `count` FROM `#__osefirewall_advancepatterns` ";
		$oseDB2->setQuery($query);
		$result = $oseDB2->loadResult();
		return ($result['count'] > 0) ? true : false;
	}
	
	public static function isCountryBlockConfigDBReady()
	{
		$oseDB2 = self::getDBO();
		$data = $oseDB2->isTableExists('#__osefirewall_country');
		if(empty($data))
		{
			return false;
		}
		$query = "SELECT COUNT(*) as `count` FROM `#__osefirewall_country` ";
		$oseDB2->setQuery($query);
		$result = $oseDB2->loadResult();
		return ($result['count'] > 0) ? true : false;
	}
	
	private function  isAdvanceSettingConfigEnable(){
		return $this->checkOseConfig ('adRules', 'scan'); 
	}
	
	public static function loadLanguage()
	{
		require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'language'.ODS.'oseLanguage.php');
		$lang = self::getLocale();
		if (file_exists(OSE_FWLANGUAGE.ODS.$lang.'.php'))
		{
			require_once(OSE_FWLANGUAGE.ODS.$lang.'.php');
		}
		else
		{
			require_once(OSE_FWLANGUAGE.ODS.'en_US.php');
		}
	}
	public static function isDBReady()
	{
		$oseDB2 = self::getDBO();
		$data = $oseDB2->isTableExists('#__osefirewall_backupath');
		$ready = (!empty($data)) ? true : false;
		return $ready;
	}
	private static function getGeoIPState()
	{
		$oseDB2 = self::getDBO();
		$query = "SHOW TABLES LIKE '#__ose_app_geoip' ";
		$oseDB2->setQuery($query);
		$result = $oseDB2->loadResult();
		if (!empty($result))
		{
			$query = "SELECT COUNT(`id`) as `count` FROM `#__ose_app_geoip` ";
			$oseDB2->setQuery($query);
			$result = $oseDB2->loadResult();
			return ($result['count'] > 0) ? true : false;
		}
		else
		{
			return false;
		}
	}
	public static function isGeoDBReady()
	{
		$geoipState = self::getGeoIPState();
		return $geoipState;
	}
	public static function dashboard()
	{
		self::runYiiApp();
		Yii::app()->runController('dashboard/index');
	}
	public static function manageips()
	{
		self::runYiiApp();
		Yii::app()->runController('manageips/index');
	}
	public static function rulesets()
	{
		self::runYiiApp();
		Yii::app()->runController('rulesets/index');
	}
	public static function configuration()
	{
		self::runYiiApp();
		Yii::app()->runController('configuration/index');
	}
	public static function seoconfig()
	{
		self::runYiiApp();
		Yii::app()->runController('seoconfig/index');
	}
	public static function scanconfig()
	{
		self::runYiiApp();
		Yii::app()->runController('scanconfig/index');
	}
	public static function spamconfig()
	{
		self::runYiiApp();
		Yii::app()->runController('spamconfig/index');
	}
	public static function avconfig()
	{
		self::runYiiApp();
		Yii::app()->runController('avconfig/index');
	}
	public static function emailconfig()
	{
		self::runYiiApp();
		Yii::app()->runController('emailconfig/index');
	}
	public static function emailadmin()
	{
		self::runYiiApp();
		Yii::app()->runController('emailadmin/index');
	}
	public static function vsscan()
	{
		self::runYiiApp();
		Yii::app()->runController('vsscan/index');
	}
	public static function vsreport()
	{
		self::runYiiApp();
		Yii::app()->runController('scanreport/index');
	}
	public static function variables()
	{
		self::runYiiApp();
		Yii::app()->runController('variables/index');
	}
	public static function versionupdate()
	{
		self::runYiiApp();
		Yii::app()->runController('versionupdate/index');
	}
	public static function countryblock()
	{
		self::runYiiApp();
		Yii::app()->runController('countryblock/index');
	}
	public static function advancerulesets()
	{
		self::runYiiApp();
		Yii::app()->runController('advancerulesets/index');
	}
	public static function backup()
	{
		self::runYiiApp();
		Yii::app()->runController('backup/index');
	}
	public static function showLogo()
	{
		$url = 'http://www.centrora.com';
		$appTitle = OSE_WORDPRESS_FIREWALL;
		$head = '<div id="logo-labels">
					<div class="text-normal"><span class="help-icons"><a href="http://www.centrora.com/support-center/" target="__blank"><img width="56" height="56" alt="" src="'.OSE_FWRELURL.'/public/images/con05.png"></a></span><h4>Need Help?</h4></div>
					<div class="text-normal"><span class="help-icons"><a href="http://www.centrora.com/tutorial/" target="__blank"><img width="56" height="56" alt="" src="'.OSE_FWRELURL.'/public/images/con016.png"></a></span><h4>User Manual</h4></div>
					<div class="text-normal"><span class="help-icons"><a href="http://www.centrora.com/cleaning/" target="__blank"><img width="56" height="56" alt="" src="'.OSE_FWRELURL.'/public/images/con017.png"></a></span><h4>Malware Removal</h4></div>';
		if (OSE_CMS == 'joomla')
		{
			$head .= '<div id="back-to-admin"><a href="index.php" >Back to Admin Panel</a></div>';
		}
		$head .= '</div>';
		echo $head;
		echo oseFirewall::getmenus();
	}
	public static function callLibClass($folder, $classname)
	{
		require_once(OSE_FWFRAMEWORK.ODS.$folder.ODS.$classname.'.php');
	}
	public static function loadBackendBasic()
	{
		$baseUrl = Yii::app()->baseUrl;
		$cs = Yii::app()->getClientScript();
		oseFirewall::loadallJs($cs);
		oseFirewall::loadbackendCSS($cs, $baseUrl);
		$cs->registerScript('oseAjax', oseFirewall::getAjaxScript(), CClientScript::POS_BEGIN);
	}
	public static function loadFrontendBasic()
	{
		$baseUrl = Yii::app()->baseUrl;
		$cs = Yii::app()->getClientScript();
		oseFirewall::loadallJs($cs);
		oseFirewall::loadFrontendCSS($cs, $baseUrl);
		$cs->registerScript('oseAjax', oseFirewall::getAjaxScript(), CClientScript::POS_BEGIN);
	}
	public static function loadBackendAll()
	{
		oseFirewall::loadBackendBasic();
		oseFirewall::loadGridAssets();
		oseFirewall::loadFormAssets();
	}
	public static function loadFrontendAll()
	{
		oseFirewall::loadFrontendBasic();
		oseFirewall::loadGridAssets();
		oseFirewall::loadFormAssets();
	}
	public function getDebugMode()
	{
		return $this->checkOseConfig ('debugMode', 'scan'); 
	}

	private function checkOseConfig ($key, $type) {
		$dbConfig = self::getConfig();
		$hostVar = $this->splitHost($dbConfig->host);
		$dbo = new PDO("mysql:host=".$hostVar['host'].";dbname=".$dbConfig->db, $dbConfig->user, $dbConfig->password);
		$stmt = $dbo->query ("SHOW TABLES LIKE '".$dbConfig->prefix."ose_secConfig' ");
		$stmt->setFetchMode(PDO::FETCH_OBJ);
		$result = $stmt->fetch();
		if (empty($result))
		{
			return true;
		}
		else
		{
			$stmt = $dbo->query ("SELECT `value` FROM `".$dbConfig->prefix."ose_secConfig` WHERE `key` = '".$key."' AND `type` = '".$type."'");
			$stmt->setFetchMode(PDO::FETCH_OBJ);
			$result = $stmt->fetch();
			return (empty($result) || ($result->value == 0)) ? false : true;
		}
	}
	private function splitHost($host)
	{
		$tmp = explode(":", $host);
		$return = array();
		$return["host"] = $tmp[0];
		$return["port"] = "";
		if (!empty($tmp[1]))
		{
			$return["port"] = ";port=".$tmp[1];
		}
		return $return;
	}
	public function checkSystem()
	{
		$return = array();
		$return[0] = true;
		$return[1] = null;
		if (!class_exists('PDO'))
		{
			$return[0] = false;
			$return[1] = 'Class PDO not found in your hosting environment, please follow this <a href="https://www.protect-website.com/fatal-error-class-pdo-not-found/" target="_blank" >tutorial</a> to enable the PDO class before using the Firewall System.';
		}
		if (!is_writable(OSEFWDIR.ODS.'assets'))
		{
			$return[0] = false;
			$return[1] = 'Assets path not writable, please follow this <a href="https://www.protect-website.com/application-runtime-path-valid/" target="_blank" >tutorial</a> to enable change the file permissions of this path: '.OSEFWDIR.ODS.'assets/ writable';
		}
		if (!is_writable(OSEFWDIR.ODS.'protected'.ODS.'runtime'))
		{
			$return[0] = false;
			$return[1] = 'Runtime path not writable, please follow this <a href="https://www.protect-website.com/application-runtime-path-valid/" target="_blank" >tutorial</a> to enable change the file permissions of this path: '.OSEFWDIR.ODS.'assets/ writable';
		}
		return $return;
	}
}
