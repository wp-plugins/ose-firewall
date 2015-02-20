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
require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'ajax' . ODS . 'oseAjax.php');
class oseFirewallAjax extends oseAjax{
	public static function loadAppActions () {
		if (!empty($_REQUEST['controller']))
		{
			$method = 'loadAction'.$_REQUEST['controller'];	
		}
		else
		{
			$method = 'loadActionDashboard';
		}
		if (method_exists('oseFirewallAjax',$method))
		{
			self::$method(); 
		}
	}
	public static function loadActionManageips () {
		$actions = array ('getACLIPMap', 'addips', 'removeips', 'removeAllIPs', 'blacklistIP', 'whitelistIP', 'monitorIP', 'updateHost', 'changeIPStatus', 'viewAttack', 'importcsv', 'exportcsv', 'downloadCSV', 'getLatestTraffic');
		parent::loadActions($actions); 
	}
	public static function loadActionDashboard () {
		$actions = array ('getCountryStat', 'getTrafficData','checkWebBrowsingStatus');
		parent::loadActions($actions); 
	}	
	public static function loadActionRulesets () {
		$actions = array ('getRulesets', 'changeRuleStatus');
		parent::loadActions($actions); 
	}
	public static function loadActionAdvancerulesets () {
		$actions = array ('getRulesets', 'changeRuleStatus', 'checkAPI');
		parent::loadActions($actions);
	}
	public static function loadActionSeoconfig () {
		$actions = array ('getConfiguration', 'saveConfigSEO');
		parent::loadActions($actions); 
	}
	public static function loadActionScanconfig () {
		$actions = array ('getConfiguration', 'saveConfigScan');
		parent::loadActions($actions); 
	}
	public static function loadActionSpamconfig () {
		$actions = array ('getConfiguration', 'saveConfAddon');
		parent::loadActions($actions); 
	}
	public static function loadActionEmailconfig () {
		$actions = array ('getEmails','getEmailParams','getEmail', 'saveemail');
		parent::loadActions($actions); 
	}
	public static function loadActionEmailadmin () {
		$actions = array ('getAdminEmailmap','getAdminUsers','getEmailList','addadminemailmap','deleteadminemailmap');
		parent::loadActions($actions); 
	}
	public static function loadActionAvconfig () {
		$actions = array ('getConfiguration', 'saveConfAV');
		parent::loadActions($actions); 
	}
	public static function loadActionVsscan () {
		$actions = array ('initDatabase', 'vsscan', 'updatePatterns', 'checkScheduleScanning');
		parent::loadActions($actions); 
	}
	public static function loadActionScanreport () {
		$actions = array ('getTypeList','getMalwareMap','viewfile');
		parent::loadActions($actions); 
	}
	public static function loadActionVariables () {
		$actions = array ('getVariables','addvariables','deletevariable','loadWordpressrules','loadJoomlarules','loadJSocialrules','changeVarStatus','clearvariables','scanvar','filtervar','ignorevar','deleteAllVariables');
		parent::loadActions($actions);
	}
	public static function loadActionVersionupdate () {
		$actions = array ('createTables', 'saveUserInfo', 'changeUserInfo');
		parent::loadActions($actions);
	}
	public static function loadActionCountryblock () {
		$actions = array ('downLoadTables' , 'createTables' , 'getCountryList' , 'changeCountryStatus' , 'blacklistCountry' , 'whitelistCountry', 'monitorCountry', 'changeAllCountry', 'deleteCountry', 'deleteAllCountry');
		parent::loadActions($actions);
	}
	public static function loadActionBackup () {
		$actions = array ('backup', 'getBackupList', 'backupFile' , 'deleteBackup' , 'deleteItemByID', 'downloadBackupDB' , 'downloadBackupFile' , 'authorizeAppAccess', 'checkAuth', 'getDropboxAPI');
		parent::loadActions($actions);
	}
	public static function loadactionUninstall(){
		$actions = array ('uninstallTables');
		parent::loadActions($actions);
	}	
	public static function loadactionApiconfig(){
		$actions = array ('saveConfigScan','getConfiguration');
		parent::loadActions($actions);
	}	
	public static function loadActionLogin () {
		$actions = array ('validate','verifyKey','updateKey','createaccount', 'getNumbOfWebsite');
		parent::loadActions($actions);
	}
	public static function loadActionAudit () {
		$actions = array ('createTables','changeusername', 'checkSafebrowsing', 'updateSafebrowsingStatus', 'uninstallTables','getPHPConfig', 'getTrackingCode', 'saveTrackingCode', 'updateSignature');
		parent::loadActions($actions);
	}
	public static function loadActionSubscription () {
		$actions = array ('getSubscription', 'getToken','linkSubscription','updateProfileID', 'logout');
		parent::loadActions($actions);
	}
}