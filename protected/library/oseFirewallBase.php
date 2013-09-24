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
if (class_exists('JFactory'))
{
	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS . 'joomla.php');
	class oseFirewallRoot extends oseJoomla {
		protected static $option = 'com_ose_firewall';
	} 
	define('OSE_CMS', 'joomla');
}
else
{
	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS . 'wordpress.php');
	class oseFirewallRoot extends oseWordpress {
		protected static $option = 'ose_firewall';
	}
	define('OSE_CMS', 'wordpress');
}
class oseFirewallBase extends oseFirewallRoot {
	public function __construct () {
		$debug = $this->getDebugMode();
		$this->setDebugMode ($debug);
	}
	public function loadBackendFunctions()
	{
		$this -> addMenuActions () ;
		self :: loadLanguage () ;
		self :: loadJSON ();
		$this -> loadAjax ();
		$this -> loadViews ();
	}
	public static function loadInstaller () {
		require_once (OSE_FWFRAMEWORK.DS . 'oseFirewallInstaller.php');
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
		$query = "SHOW TABLES LIKE '#__ose_app_geoip' ";
		$oseDB2->setQuery($query);
		$result = $oseDB2->loadResult();
		if (!empty($result))
		{
			$query = "SELECT COUNT(`id`) as `count` FROM `#__ose_app_geoip` ";
			$oseDB2->setQuery($query);
			$result = $oseDB2->loadResult();
			return ($result['count']>0)?true:false;
		}
		else
		{
			return true;
		}
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
		$appTitle = 'OSE <span>FIREWALL</span>&trade;';
		$head= '<div id="logo-labels">
					<h1><a href="'.$url.'" target= "_blank">'.$appTitle.'</a></h1>
					<div id="support"><a href="http://www.protect-website.com/need-help/" target="__blank">Need Help?</a></div>
					<div id="user-manual"><a href="https://www.protect-website.com/user-manual/" target="__blank">User Manual</a></div>
					<div id="need-cleaning"><a href="https://www.protect-website.com/website-malware-removal-services/" target="__blank">Malware Removal</a></div>';
		if (OSE_CMS =='joomla')
		{
			$head .= '<div id="back-to-admin"><a href="index.php" >Back to Admin Panel</a></div>';	
		}
		$head .= '</div>';
		echo $head; 
		echo oseFirewall::getmenus();
	}
	public static function callLibClass($folder, $classname)
	{
		require_once (OSE_FWFRAMEWORK. DS . $folder . DS . $classname.'.php');
	}
	public static function loadBackendBasic () {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		oseFirewall::loadallJs ($cs);
		oseFirewall::loadbackendCSS ($cs,$baseUrl);
		$cs->registerScript('oseAjax', oseFirewall::getAjaxScript(), CClientScript::POS_BEGIN);
	}
	public static function loadFrontendBasic () {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		oseFirewall::loadallJs ($cs);
		oseFirewall::loadFrontendCSS ($cs,$baseUrl);
		$cs->registerScript('oseAjax', oseFirewall::getAjaxScript(), CClientScript::POS_BEGIN);
	}
	public static function loadBackendAll ()
	{
		oseFirewall::loadBackendBasic ();
		oseFirewall::loadGridAssets ();
		oseFirewall::loadFormAssets ();
	}
	public static function loadFrontendAll ()
	{
		oseFirewall::loadFrontendBasic ();
		oseFirewall::loadGridAssets ();
		oseFirewall::loadFormAssets ();
	}
}