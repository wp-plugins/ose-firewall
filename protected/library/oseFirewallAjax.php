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
require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS . 'ajax' . DS . 'oseAjax.php');
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
		$actions = array ('getACLIPMap', 'addips', 'removeips', 'blacklistIP', 'whitelistIP', 'monitorIP', 'updateHost', 'changeIPStatus', 'viewAttack');
		parent::loadActions($actions); 
	}
	public static function loadActionDashboard () {
		$actions = array ('createTables');
		parent::loadActions($actions); 
	}	
	public static function loadActionRulesets () {
		$actions = array ('getRulesets', 'changeRuleStatus');
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
		$actions = array ('getAdminEmailmap','getAdminUsers','getEmailList','addadminemailmap');
		parent::loadActions($actions); 
	}
	public static function loadActionAvconfig () {
		$actions = array ('getConfiguration', 'saveConfAV');
		parent::loadActions($actions); 
	}
	public static function loadActionVsscan () {
		$actions = array ('initDatabase', 'vsscan');
		parent::loadActions($actions); 
	}
	public static function loadActionScanreport () {
		$actions = array ('getTypeList','getMalwareMap','viewfile');
		parent::loadActions($actions); 
	}
	public static function loadActionVariables () {
		$actions = array ('getVariables','addvariables','deletevariable','loadWordpressrules','changeVarStatus','clearvariables');
		parent::loadActions($actions);
	}
	public static function loadActionVersionupdate () {
		$actions = array ('createTables', 'saveUserInfo', 'changeUserInfo');
		parent::loadActions($actions);
	}
}