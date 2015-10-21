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

    public static function loadActionPasscode()
    {
        $actions = array('verify', 'changePasscode', 'disablePasscode', 'check');
        parent::loadActions($actions);
    }
	public static function loadActionManageips () {
        $actions = array('getACLIPMap', 'addips', 'removeips', 'removeAllIPs', 'blacklistIP', 'whitelistIP', 'monitorIP', 'updateHost', 'changeIPStatus', 'viewAttack', 'importcsv', 'exportcsv', 'downloadCSV', 'getLatestTraffic', 'getKeyName', 'check');
		parent::loadActions($actions); 
	}
	public static function loadActionDashboard () {
        $actions = array('getCountryStat', 'getTrafficData', 'checkWebBrowsingStatus', 'getMalwareMap', 'getBackupList', 'check');
		parent::loadActions($actions); 
	}	
	public static function loadActionRulesets () {
        $actions = array('getRulesets', 'changeRuleStatus', 'check');
		parent::loadActions($actions); 
	}
	public static function loadActionAdvancerulesets () {
        $actions = array('getRulesets', 'changeRuleStatus', 'checkAPI', 'downloadRequest', 'downloadSQL', 'check');
		parent::loadActions($actions);
	}
	public static function loadActionSeoconfig () {
        $actions = array('getConfiguration', 'saveConfigSEO', 'check');
		parent::loadActions($actions); 
	}
	public static function loadActionScanconfig () {
        $actions = array('getConfiguration', 'saveConfigScan', 'showGoogleSecret', 'check');
		parent::loadActions($actions); 
	}

    public static function loadActionUpload()
    {
        $actions = array('getExtLists', 'changeStatus', 'saveExt', 'getLog', 'check');
        parent::loadActions($actions);
    }
	public static function loadActionSpamconfig () {
        $actions = array('getConfiguration', 'saveConfAddon', 'check');
		parent::loadActions($actions); 
	}
	public static function loadActionEmailconfig () {
        $actions = array('getEmails', 'getEmailParams', 'getEmail', 'saveemail', 'check');
		parent::loadActions($actions); 
	}
	public static function loadActionEmailadmin () {
        $actions = array('getAdminEmailmap', 'getAdminUsers', 'getEmailList', 'addadminemailmap', 'deleteadminemailmap', 'check');
		parent::loadActions($actions); 
	}
	public static function loadActionAvconfig () {
        $actions = array('getConfiguration', 'saveConfAV', 'check');
		parent::loadActions($actions); 
	}
	public static function loadActionVsscan () {
        $actions = array('initDatabase', 'vsscan', 'updatePatterns', 'checkScheduleScanning', 'getFileTree', 'check', 'getLastScanRecord');
		parent::loadActions($actions); 
	}
	public static function loadActionScanreport () {
        $actions = array('getTypeList', 'getMalwareMap', 'viewfile', 'quarantinevs', 'bkcleanvs', 'deletevs', 'restorevs', 'batchqt', 'batchbkcl', 'batchrs', 'batchdl', 'markasclean', 'check');
		parent::loadActions($actions); 
	}
	public static function loadActionVariables () {
        $actions = array('getVariables', 'addvariables', 'deletevariable', 'loadWordpressrules', 'loadJoomlarules', 'loadJSocialrules', 'changeVarStatus', 'clearvariables', 'scanvar', 'filtervar', 'ignorevar', 'deleteAllVariables', 'check');
		parent::loadActions($actions);
	}
	public static function loadActionVersionupdate () {
        $actions = array('createTables', 'saveUserInfo', 'changeUserInfo', 'check');
		parent::loadActions($actions);
	}
	public static function loadActionCountryblock () {
        $actions = array('downLoadTables', 'createTables', 'getCountryList', 'changeCountryStatus', 'blacklistCountry', 'whitelistCountry', 'monitorCountry', 'changeAllCountry', 'deleteCountry', 'deleteAllCountry', 'check');
		parent::loadActions($actions);
	}
	public static function loadActionAdvancedbackup()
    {
        $actions = array('backup', 'getBackupList', 'deleteBackup', 'dropboxUpload', 'sendemail', 'oneDriveUpload', 'googledrive_upload', 'getGoogleDriveUploads', 'getOneDriveUploads', 'getDropboxUploads', 'check');
        parent::loadActions($actions);
    }

    public static function loadActionAdminemails()
    {
        $actions = array('saveDomain', 'saveAdmin', 'getAdminList', 'getDomain', 'changeStatus', 'deleteAdmin', 'saveEmailEditor', 'restoreDefault', 'getSecManagers','saveSecManager','changeBlock', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionAuthentication()
    {
        $actions = array('oauth', 'onedrive_logout', 'dropbox_logout', 'dropbox_init', 'googledrive_logout', 'check');
        parent::loadActions($actions);
    }
	public static function loadActionBackup () {
        $actions = array('backup', 'getBackupList', 'deleteBackup', 'sendemail', 'check');
		parent::loadActions($actions);
	}
	public static function loadactionUninstall(){
        $actions = array('uninstallTables', 'check');
		parent::loadActions($actions);
	}	
	public static function loadactionApiconfig(){
        $actions = array('saveConfigScan', 'getConfiguration', 'check');
		parent::loadActions($actions);
	}	
	public static function loadActionLogin () {
        $actions = array('validate', 'verifyKey', 'updateKey', 'createaccount', 'getNumbOfWebsite', 'addOEM', 'check');
		parent::loadActions($actions);
	}
	public static function loadActionAudit () {
        $actions = array('createTables', 'changeusername', 'checkSafebrowsing', 'updateSafebrowsingStatus', 'uninstallTables', 'getPHPConfig', 'saveTrackingCode', 'updateSignature', 'check', 'googleRot');
		parent::loadActions($actions);
	}
	public static function loadActionSubscription () {
        $actions = array('getSubscription', 'getToken', 'linkSubscription', 'updateProfileID', 'logout', 'getPaymentAddress', 'addOrder', 'check');
		parent::loadActions($actions);
	}
	public static function loadActionCronjobs () {
        $actions = array('saveCronConfig', 'check');
		parent::loadActions($actions);
	}
	public static function loadActionPermConfig () {
        $actions = array('getDirFileList', 'getFileTree', 'editperms', 'check');
		parent::loadActions($actions);
	}
	public static function loadActionNews () {
		$actions = array('getFeed', 'check');
		parent::loadActions($actions);
	}
}