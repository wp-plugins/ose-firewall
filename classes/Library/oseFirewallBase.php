<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Centrora Security Firewall
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
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
	die('Direct Access Not Allowed');
}
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
		//$enable = true;
		require_once(OSE_FWFRAMEWORK.ODS.'googleAuthenticator'.ODS.'class_gauthenticator.php');
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
		$enabled = $this->checkOseConfig ('googleVerification', 'bf');
		if ($this->checkOseConfig ('googleVerification', 'bf') == true || $this->checkOseConfig ('googleVerification', 'advscan') == true)
		{
			return true;
		}
		else 
		{
			return false;
		} 
	}
	public function loadBackendFunctions()
	{
		$this->addMenuActions();
		oseFirewall::callLibClass('oem', 'oem');
		$oem = new CentroraOEM() ;
		$oem->defineVendorName();
		self::loadLanguage();
		self::loadJSON();
		$this->loadAjax();
		$this->loadViews();
	}
	public static function loadBackendBasicFunctions()
	{
        oseFirewall::addMenuActions();
        oseFirewall::callLibClass('oem', 'oem');
        $oem = new CentroraOEM() ;
        $oem->defineVendorName();
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
	public function isAdvanceFirewallSettingEnable()
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
		return $this->checkOseConfig ('adRules', 'advscan'); 
	}
	public static function getLocaleString(){
        $lang = oseFirewall::getLocale();
        $lang = str_replace("-", "_",$lang);
        if (strpos('da_DK', $lang) === false && strpos('de_DE', $lang) === false) {
            $lang = 'en_US';
        }
        
        return $lang;
    }
    public static function loadLanguage()
	{
		if (defined('OSE_OEM_LANG_TAG') && OSE_OEM_LANG_TAG =='') {
        	$lang = self::getLocaleString(); 
		}
		else if (defined('OSE_OEM_LANG_TAG')  && OSE_OEM_LANG_TAG !='')
		{
			$lang = OSE_OEM_LANG_TAG; 
		}
		else {
			$lang = 'en_US';
		}
        require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'language'.ODS.'oseLanguage.php');
		require_once(OSE_FWLANGUAGE.ODS.$lang.'.php');
	}
	public static function isDBReady()
	{
		$oseDB2 = self::getDBO();
		$data = $oseDB2->isTableExists('#__osefirewall_backupath');
		if (!empty($data)) 
		{
			self::checkVSTypeTable (); 
			$data = $oseDB2->isTableExists('#__osefirewall_vspatterns');
			$oseDB2->closeDBO();
		}
        //@todo add db version checker here
        self::checkNewDBTables();
		$ready = (!empty($data)) ? true : false;
		return $ready;
	}
	public static function isSigUpdated () {
		$count = self::getCountSignatures();
	    if ($count<12)
	    {
	    	echo '<span class="label label-warning"><i class="glyphicon glyphicon-remove"></i> Warning: Firewall Outdated</span>&nbsp;&nbsp;<button class="btn btn-danger btn-xs fx-button" id="fixSignature" name="fixSignature" onClick="updateSignature(\'#rulesetsTable\')">Fix It</button>';
	    	if (OSE_CMS!='joomla')
	    	{	
	    		echo '<script type="text/javascript">document.getElementById("fixSignature").click();</script>';
	    	}
	    }
	    else
	   {
	    	echo '<span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Firewall Updated</span>';
	    }
	}
	private static function getCountSignatures () {
		$oseDB2 = self::getDBO();
		$query = "SELECT COUNT(id) AS count FROM `#__osefirewall_basicrules`;";
		$oseDB2->setQuery($query); 
		$count = $oseDB2->loadResult();
		return ($count['count']);
	}
	// Version 3.4.0 Table checking; 
	private static function checkVSTypeTable () {
		$oseDB2 = self::getDBO();
		if ( $oseDB2->isTableExists('#__osefirewall_vstypes'))
		{
			$query  = "SELECT COUNT(id) AS count1, COUNT(*) AS count2 FROM `#__osefirewall_vstypes` WHERE `type` = 'O_CLAMAV' ";
			$oseDB2->setQuery($query);
			$result = $oseDB2->loadResult();
			if ($result['count1'] == 1)
			{
				//$oseDB2->closeDBO();
				return true;
			}
			else
			{
				if ( $result['count2']>0 && $oseDB2->isTableExists('#__osefirewall_vspatterns'))
				{
					$queries = array (); 
					$queries[] = "SET FOREIGN_KEY_CHECKS = 0";
					$queries[] = "DROP TABLE IF EXISTS `#__osefirewall_files` ";
					$queries[] = "DROP TABLE IF EXISTS `#__osefirewall_vstypes` ";
					$queries[] = "DROP TABLE IF EXISTS `#__osefirewall_vspatterns` ";
					$queries[] = "DROP TABLE IF EXISTS `#__osefirewall_malware` ";
					$queries[] = "DROP TABLE IF EXISTS `#__osefirewall_logs` ";
					foreach ($queries as $query)
					{
						$oseDB2->setQuery($query);
						$oseDB2->query ();
					}  
				}
				return false; 
			}
		}	
	}
	public static function getGeoIPState()
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
			$oseDB2->closeDBO();
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
	public static function runController ($controller, $action) {
		//global $centrora;
		$centrora = self::runApp();
		$requst = $centrora->runController($controller, $action);
		$requst->execute();
	}
	public static function dashboard()
	{
		self::runController ('DashboardController', 'index');
	}
	public static function manageips()
	{
		self::runController ('ManageipsController', 'index');
	}
	public static function ipform()
	{
		self::runController ('ManageipsController', 'ipform');
	}
	public static function rulesets()
	{
		self::runController ('RulesetsController', 'index');
	}
	public static function configuration()
	{
		self::runController ('ConfigurationController', 'index');
	}

    public static function upload()
    {
        self::runController('UploadController', 'index');
    }
    public static function passcode()
    {
        self::runController('PasscodeController', 'index');
    }
	public static function seoconfig()
	{
		self::runController ('SeoconfigController', 'index');
	}
	public static function scanconfig()
	{
		self::runController ('ScanconfigController', 'index');
	}
	public static function audit()
	{
		self::runController ('AuditController', 'index');
	}
    public static function cfscan()
    {
        self::runController ('cfscanController', 'index');
    }
    public static function fpscan()
    {
        self::runController ('fpscanController', 'index');
    }
    public static function adminemails()
    {
        self::runController('AdminemailsController', 'index');
    }
	public static function spamconfig()
	{
		$app = self::runApp();
		$app->runController('spamconfig', 'index');
	}
	public static function avconfig()
	{
		$app = self::runApp();
		$app->runController('avconfig', 'index');
	}
	public static function emailconfig()
	{
		$app = self::runApp();
		$app->runController('emailconfig', 'index');
	}
	public static function emailadmin()
	{
		$app = self::runApp();
		$app->runController('emailadmin', 'index');
	}
	public static function vsscan()
	{
		self::runController ('VsscanController', 'index');
	}
	public static function vsreport()
	{
		self::runController ('ScanreportController', 'index');
	}
    public static function vlscan()
    {
        self::runController ('VlscanController', 'index');
    }

    public static function mfscan()
    {
        self::runController('MfscanController', 'index');
    }
    public static function surfscan()
    {
        self::runController ('SurfscanController', 'index');
    }
	public static function variables()
	{
		self::runController ('VariablesController', 'index');
	}
	public static function bsconfig()
	{
		self::runController ('BsconfigController', 'index');
	}
	public static function versionupdate()
	{
		$app = self::runApp();
		$app->runController('versionupdate', 'index');
	}
	public static function countryblock()
	{
		self::runController ('CountryblockController', 'index');
	}
	public static function advancerulesets()
	{
		self::runController ('AdvancerulesetsController', 'index');
	}
	public static function backup()
	{
        self::runController('BackupController', 'index');
	}

    public static function authentication()
    {
        self::runController('AuthenticationController', 'index');
    }
    public static function advancedbackup()
    {
        self::runController('AdvancedbackupController', 'index');
    }

    public static function login()
	{
		self::runController ('LoginController', 'index');
	}
	public static function subscription () 
	{
		self::runController ('SubscriptionController', 'index');
	}
	public static function cronjobs () 
	{
		self::runController ('CronjobsController', 'index');
	}
	public static function permconfig ()
	{
		self::runController ('PermconfigController', 'index');
	}
	public static function clamav () 
	{
		$app = self::runApp();
		$app->runController('clamav', 'index');
	}
	public static function apiconfig () 
	{
		$app = self::runApp();
		$app->runController('apiconfig', 'index');
	}
	public static function activation()
	{
		self::runController ('ActivationController', 'index');
	}
	public static function news()
	{
		self::runController('NewsController', 'index');
	}
	public static function showLogo()
	{}
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
		if (!class_exists('PDO'))
		{
			return false;
		}
		$dbConfig = self::getConfig();
		$hostVar = $this->splitHost($dbConfig->host);
		$dbo = new PDO("mysql:host=".$hostVar['host'].";dbname=".$dbConfig->db, $dbConfig->user, $dbConfig->password);
		$stmt = $dbo->query ("SHOW TABLES LIKE '".$dbConfig->prefix."ose_secConfig' ");
		$stmt->setFetchMode(PDO::FETCH_OBJ);
		$result = $stmt->fetch();
		if (empty($result))
		{
			$dbo = null;
			return true;
		}
		else
		{
			$stmt = $dbo->query ("SELECT `value` FROM `".$dbConfig->prefix."ose_secConfig` WHERE `key` = '".$key."' AND `type` = '".$type."'");
			if (!empty($stmt))
			{	
				$stmt->setFetchMode(PDO::FETCH_OBJ);
				$result = $stmt->fetch();
				$dbo = null;
				return (empty($result) || ($result->value == 0)) ? false : true;
			}
			else
			{
				return false;
			}
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
		$phpVersion = $this->comparePHPVersion ();
		if ($phpVersion == false)
		{
			$return[0] = false;
			$return[1] = 'Centrora Security 4.0.0+ requires PHP version 5.3.0 or above, please contact your hosting company to upgrade the PHP version.';
		}
		if (!class_exists('PDO'))
		{
			$return[0] = false;
			$return[1] = 'Class PDO not found in your hosting environment, please follow this <a href="http://www.centrora.com/user-manual/fatal-error-class-pdo-not-found/" target="_blank" >tutorial</a> to enable the PDO class before using the Firewall System.';
		}
		return $return;
	}	
	public function comparePHPVersion () {
		return (version_compare(PHP_VERSION, '5.3.0') >= 0)?true:false;
	}
	public function runReport () {
		oseFirewall::callLibClass('audit', 'audit');
		$audit = new oseFirewallAudit (); 
		$audit -> runReport();
	}
	public static function getTime () {
		self::loadDateClass(); 
		$oseDatetime = new oseDatetime();
		$oseDatetime->setFormat("Y-m-d H:i:s");
		$time = $oseDatetime->getDateTime();
		return $time;  
	}
	public static function enhanceSysSecurity () {
		oseFirewall::callLibClass('audit', 'audit');
		$audit = new oseFirewallAudit (); 
		$audit -> enhanceSysSecurity();  
	}
	public static function getConfiguration($type)
	{
		$db = self::getDBO();
		$return = array();
		$query = "SELECT `key`, `value` FROM `#__ose_secConfig` WHERE `type` = ".$db->quoteValue($type);
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		if (!empty($results))
		{	
			foreach ($results as $result)
			{
				if ($type == 'l2var')
				{
					$return['data'][$result->key] = (int) $result->value;
				}
				else
				{
					$return['data'][$result->key] = self::convertValue($result->key , $result->value);
				}
			}
		}
		else
		{
			$return['data'] = array();
		}
		$return['success'] = true;
		return $return;
	}
	private static function convertValue($key, $value)
	{
		if (is_numeric($value))
		{
			$value = intval($value);
		}
		return $value;
	}
	public static function checkDBReady () {
		if (! oseFirewall::isDBReady ()) {
			if (OSE_CMS =='joomla')
			{
				$url = 'index.php?option=com_ose_firewall&view=configuration';
				header('Location: '.$url);
			}
			else
			{
				$url = 'admin.php?page=ose_fw_configuration';
				echo '<script type="text/javascript">window.location = "'.$url.'";</script>';
			}
		}
	}
	public static function getWebKey () {
		$db = oseFirewall::getDBO();
		$query = "SELECT * FROM `#__ose_secConfig` WHERE `key` = 'website' ";
		$db->setQuery($query);
		$result = $db->loadObject();
		return (!empty($result))?$result->value:null;
	}
	public static function checkSubscriptionStatus ($redirect= true) {
		$db = oseFirewall::getDBO();
		$query = "SELECT * FROM `#__ose_secConfig` WHERE (`key` = 'profileID' OR `key` = 'profileStatus') AND `type` = 'panel'";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$return = array();
		foreach ($results as $result) {
			$return[$result->key] = $result->value;
		}
		if (!empty($return['profileID']) && $return['profileStatus']==2)
		{
			return true;
		}
		else
		{
			if ($redirect == true)
			{	
				oseFirewall::redirectLogin();
			}
			else 
			{
				return false;
			}
		}
	}
	public static function checkWebkey () {
		$webKeys = oseFirewall::getWebkeys();
		if (empty($webKeys['webkey']))
		{
			oseFirewall::redirectLogin();
		}
	}
	public static function checkSubscription () {
		$webKeys = oseFirewall::getWebkeys();
		if (!empty($webKeys['webkey']) && $webKeys['verified']==true)
		{
			oseFirewall::redirectSubscription();
		}
	}
	protected static function getWebkeys () {
		$db = oseFirewall::getDBO();
		$query = "SELECT * FROM `#__ose_secConfig` WHERE (`key` = 'webkey' OR `key` = 'verified') AND `type` = 'panel'";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$return = array();
		foreach ($results as $result) {
			$return[$result->key] = $result->value;
		}
		return $return;
	}
	public static function preRequisitiesCheck() {
		return (version_compare(PHP_VERSION, '5.3.0') < 0)?false:true;
	}
	public static function showNotReady() {
		die('Centrora Security requires PHP 5.3.0 to work properly, please upgrade your PHP version to 5.3.0 or above');
	}

    public static function getActiveReceivers()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `A_email`,`A_name` FROM `#__osefirewall_adminemails` WHERE (`A_status` = 'active')";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $i = 0;
        $return = array();
        foreach ($results as $result) {
            $return[$i]->name = $result->A_name;
            $return[$i]->email = $result->A_email;
            $i++;
        }
        return $return;
    }

    public static function getActiveReceiveEmails()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `A_email` FROM `#__osefirewall_adminemails` WHERE (`A_status` = 'active')";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $i = 0;
        $return = array();
        foreach ($results as $result) {
            $return[$i] = $result->A_email;
            $i++;
        }
        return $return;
    }
    public static function checkNewDBTables (){
        $oseDB2 = self::getDBO();
        $datadomains = $oseDB2->isTableExists('#__osefirewall_domains');
        if(!$datadomains)
        {
            $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_domains` (
                          `D_id`      INT(11)      NOT NULL AUTO_INCREMENT,
                          `D_address` VARCHAR(200) NOT NULL,
                          PRIMARY KEY (`D_id`),
                          UNIQUE KEY `D_address` (`D_address`)
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        $dataadminemails = $oseDB2->isTableExists('#__osefirewall_adminemails');
        if(!$dataadminemails)
        {
            $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_adminemails` (
                          `A_id`     INT(11)     NOT NULL AUTO_INCREMENT,
                          `A_name`   TEXT        NOT NULL,
                          `A_email`  TEXT        NOT NULL,
                          `A_status` VARCHAR(10) NOT NULL,
                          `D_id`     INT(11),
                          PRIMARY KEY (`A_id`),
                          INDEX `#__osefirewall_adminemails_idx1` (`D_id`),
                          FOREIGN KEY (`D_id`) REFERENCES `#__osefirewall_domains` (`D_id`)
                            ON UPDATE CASCADE
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }
        $dataupload = $oseDB2->isTableExists('#__osefirewall_fileuploadext');
        if (!$dataupload) {
            $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_fileuploadext` (
                     `ext_id` int(11) NOT NULL AUTO_INCREMENT,
                     `ext_name` varchar(200) NOT NULL,
                     `ext_type` varchar(200) NOT NULL,
                     `ext_status` tinyint(1) NOT NULL,
                     PRIMARY KEY (`ext_id`),
                     UNIQUE KEY `ext_name` (`ext_name`)
                     ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; ";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
            oseFirewallBase::loadInstaller();
            $installer = new oseFirewallInstaller();
            $dbFile = OSE_FWDATA . ODS . 'dataFileExtension.sql';
            $result = $installer->insertFileExtension($dbFile);
            $installer->closeDBO();
        }
        $datauploadLog = $oseDB2->isTableExists('#__osefirewall_fileuploadlog');
        if (!$datauploadLog) {
            $query = "CREATE TABLE `#__osefirewall_fileuploadlog` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `ip_id` int(11) NOT NULL,
                      `file_name` varchar(100) DEFAULT NULL,
                      `file_type_id` int(11) NOT NULL,
                      `validation_status` tinyint(1) NOT NULL,
                      `vs_scan_status` tinyint(1) NOT NULL,
                      `datetime` datetime NOT NULL,
                      PRIMARY KEY (`id`),
                      INDEX `osefirewall_fileuploadlog_idx1` (`id`),
                      FOREIGN KEY (`ip_id`) REFERENCES `#__osefirewall_acl` (`id`) ON UPDATE CASCADE ON DELETE CASCADE ,
                      FOREIGN KEY (`file_type_id`) REFERENCES `#__osefirewall_fileuploadext` (`ext_id`) ON UPDATE CASCADE ON DELETE CASCADE
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        $vlscanner = $oseDB2->isTableExists('#__osefirewall_vlscanner');
        if (!$vlscanner) {
            $query = "CREATE TABLE `#__osefirewall_vlscanner` (
                      `vls_id` int(11) NOT NULL AUTO_INCREMENT,
                      `vls_type` tinyint(4) NOT NULL,
                      `content` longtext NOT NULL,
                      PRIMARY KEY (`vls_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        $scanhist = $oseDB2->isTableExists('#__osefirewall_scanhist');
        if (!$scanhist) {
            $query = "CREATE TABLE `#__osefirewall_scanhist` (
                      `scanhist_id` int(11) NOT NULL AUTO_INCREMENT,
                      `super_type` varchar(10) NOT NULL,
                      `sub_type` int(11) NOT NULL,
                      `content` longtext NOT NULL,
                      `inserted_on` datetime NOT NULL,
                      PRIMARY KEY (`scanhist_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        $vshash = $oseDB2->isTableExists('#__osefirewall_vshash');
        if (!$vshash) {
            $query = "CREATE TABLE `#__osefirewall_vshash` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `type` tinyint(4) NOT NULL DEFAULT '0',
                      `name` varchar(100) NOT NULL,
                      `hash` text NOT NULL,
                      `inserted_on` datetime DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `unique_id` (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=8883 DEFAULT CHARSET=utf8";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }
		self::ammendDBTables($oseDB2);
    }

	private static function ammendDBTables ($oseDB2)
	{
		#run check on table to ammend
		$addcolumn_detcontdetail = self::checkTabledetcontdetail($oseDB2);
		$addcolumn_files = self::checkTablefiles($oseDB2);
		if ($addcolumn_detcontdetail){
			#Create New DateTime Column
			self::addcolumndetcontdetail($oseDB2);
		}
		if ($addcolumn_files){
			#Create new content column
			self::addcolumnfiles($oseDB2);
		}
	}

	private function checkTabledetcontdetail($oseDB2, $return = true)
	{
		$query = 'DESCRIBE #__osefirewall_detcontdetail;';
		$oseDB2->setQuery($query);
		$result = ($oseDB2->loadObjectList());
		#Run Check for ammending
		foreach ($result as $key => $value) {
			if ($value->Field == 'inserted_on'){
				$return = false;
				break;
			}
		}
		return $return;
	}

	private function addcolumndetcontdetail ($oseDB2)
	{
		$query = 'ALTER TABLE `#__osefirewall_detcontdetail` ADD inserted_on DATETIME NOT NULL;';
		$oseDB2->setQuery($query);
		$oseDB2->query();
		#Add ACL Date for Existing Data for backward Compatibility
		$query = 'UPDATE `#__osefirewall_detcontdetail` as dcd
				INNER JOIN `#__osefirewall_detected` as dc ON dcd.detattacktype_id = dc.detattacktype_id
				INNER JOIN `#__osefirewall_acl` as acl ON dc.acl_id = acl.id
				SET dcd.inserted_on = acl.datetime';
		$oseDB2->setQuery($query);
		$oseDB2->query();
	}

	private function checkTablefiles($oseDB2, $return = true)
	{
		$query = 'DESCRIBE #__osefirewall_files;';
		$oseDB2->setQuery($query);
		$result = ($oseDB2->loadObjectList());
		#Run Check for ammending
		foreach ($result as $key => $value) {
			if ($value->Field == 'content'){
				$return = false;
				break;
			}
		}
		return $return;
	}

	private function addcolumnfiles ($oseDB2)
	{
		$query = 'ALTER TABLE `#__osefirewall_files` ADD content text NULL;';
		$oseDB2->setQuery($query);
		$oseDB2->query();
	}

    public static function affiliateAccountExists () {
    	$config = self::getConfiguration('panel');
    	return (!empty($config['data']['trackingCode']))?$config['data']['trackingCode']:null;
    }
    protected static function checkNewsUpdated(){
    	oseFirewall::callLibClass('panel','panel');
    	$panel = new panel ();
    	return $panel->checkNewsUpdated();
    }
}
