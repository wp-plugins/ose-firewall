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
require_once (dirname(__FILE__).ODS.'oseFirewallBase.php');
class oseFirewall extends oseFirewallBase {
	protected static $option = 'com_ose_firewall';
	public function __construct () {
		$debug = $this->getDebugMode(); 
		$this->setDebugMode (true);
	}
	public function initSystem()
	{
		$this->initYiiConfiguration ();
		if (OFRONTENDSCAN==false)
		{
			$this->startSession ();
		} 
	}
	protected function loadViews () {
       	$view = JRequest::getVar('view');
    	$tmpl = JRequest :: getVar('tmpl');
		if (empty ($tmpl)) {
			JRequest :: setVar('tmpl', 'component');
		}
       	if (empty ($view))
       	{
       		oseFirewall::dashboard();
       	} 
       	else
       	{
       		oseFirewall::$view();
       	}
    }
    protected function addMenuActions () {
    	//add_action('admin_menu', 'oseFirewall::showmenus');
    } 
    public static function getmenus(){
    	$db = JFactory :: getDBO();
		$query = "SELECT * FROM `#__menu` WHERE `alias` =  'Centrora Security'";
		$db->setQuery($query);
		$results = $db->loadResult();
		if (empty ($results)) {
			$query = "UPDATE `#__menu` SET `alias` =  'Centrora Security', `path` =  'Centrora Security', `published`=1, `img` = '\"components/com_ose_firewall/public/images/favicon.ico\"'  WHERE `component_id` = ( SELECT extension_id FROM `#__extensions` WHERE `element` ='com_ose_firewall')  AND `client_id` = 1 ";
			$db->setQuery($query);
			$db->query();
		}
		$db->closeDBO(); 
		$extension = 'com_ose_firewall';
		$view = JRequest :: getVar('view');
		$menu = '<div class="menu-search">';
		$menu .= '<ul id ="nav">';
		// Dashboard Menu; 
		$menu .= '<li ';
		$menu .= ($view == 'ose_firewall') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=dashboard">' . oLang::_get('DASHBOARD_TITLE') . '</a></li>';
		
		$menu .= '<li ';
		$menu .= (in_array($view, array('vsscan', 'vsreport', 'clamav'))) ?'class="current"' : '';
		$menu .= '><a href="#">' . oLang::_get('ANTI_VIRUS') . '</a>';
		// SubMenu Anti-Virus Starts; 
		$menu .= '<ul>';
		$menu .= '<li ';
		$menu .= ($view == 'vsscan') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsscan">' . oLang::_get('ANTIVIRUS'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'vsreport') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsreport">' . oLang::_get('VSREPORT'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'clamav') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=clamav">' . oLang::_get('CLAMAV'). '</a></li>';
		$menu .= '</ul>';
	    // SubMenu Anti-Virus Ends;
		$menu .= '</li>';
		// Anti-Virus Menu; 
		
		// Anti-Hacking Menu; 
		$menu .= '<li ';
		$menu .= (in_array($view, array('manageips', 'rulesets', 'advancerulesets', 'variables'))) ? 'class="current"' : '';
		$menu .= '><a href="#">' . oLang::_get('ANTI_HACKING') . '</a>';
		// SubMenu Anti-Hacking Starts; 
		$menu .= '<ul>';
		$menu .= '<li ';
		$menu .= ($view == 'manageips') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=manageips">' . oLang::_get('MANAGE_IPS') . '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'rulesets') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=rulesets">' . oLang::_get('RULESETS'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'advancerulesets') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=advancerulesets">' . oLang::_get('ADRULESETS'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'variables') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=variables">' . oLang::_get('VARIABLES'). '</a></li>';
		$menu .= '<li ';
		$menu .=($view == 'countryblock') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=countryblock">' . oLang::_get('COUNTRYBLOCK'). '</a></li>';
		$menu .= '</ul>';
		// SubMenu Anti-Hacking Ends;
		$menu .= '</li>';
		
		// Backup Feature Menu
		$menu .= '<li ';
		$menu .= ($view == 'backup') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=backup">' . oLang::_get('BACKUP'). '</a></li>';
		// BackUp Feature Ends
		
		// Configuration Menu; 
		$menu .= '<li ';
		$menu .= ($view == 'configuration') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=configuration">' . oLang::_get('CONFIGURATION'). '</a></li>';
		// Configuration Feature Ends
		
		// About Menu
		$menu .= '<li ';
		$menu .= ($view == 'about') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=about">' . oLang::_get('ABOUT'). '</a></li>';
		// About Ends
		
		// Main Feature Ends; 
		$menu .= '</ul>';
		return $menu;
	}
	public static function getAjaxScript() {
    	return "var url = \"".OURI::base()."index.php\";".
			   "var option=\"".self::$option."\";";
    }
	public static function showLogo()
	{
		$url = 'http://www.centrora.com';
		$appTitle = OSE_WORDPRESS_FIREWALL;
		$head = '<div id="logo-labels">';
		if (OSE_CMS == 'joomla')
		{
			$head .= '<div id="back-to-admin"><a href="index.php" >Back to Admin Panel</a></div>';
		}
		$head .= '<div class="text-normal support-center"><span class="help-icons"><a href="http://www.centrora.com/support-center/" target="__blank"><img width="40" height="40" alt="" src="'.OSE_FWRELURL.'/public/images/con05.png"></a></span><h4>Need Help?</h4></div>
		   		  <div class="text-normal"><span class="help-icons"><a href="http://www.centrora.com/tutorial/" target="__blank"><img width="40" height="40" alt="" src="'.OSE_FWRELURL.'/public/images/con016.png"></a></span><h4>User Manual</h4></div>
				  <div class="text-normal"><span class="help-icons"><a href="http://www.centrora.com/cleaning/" target="__blank"><img width="40" height="40" alt="" src="'.OSE_FWRELURL.'/public/images/con017.png"></a></span><h4>Malware Removal</h4></div>';
		$head .= '<div class ="version-normal">'.self::getVersion ().'</div> ';
		$head .= '</div>';
		echo $head;
		echo oseFirewall::getmenus();
	}
	private static function getVersion () {
		$xml = JFactory::getXML(JPATH_ADMINISTRATOR .'/components/com_ose_firewall/ose_firewall.xml');
		$version = (string)$xml->version;
		return 'Version: '.$version; 
	}
	public static function loadNounce () {
		return 'test'; 
	}
	public static function getScanPath () {
		oseFirewall::loadRequest ();
		$scan_path = oRequest::getVar('scan_path', null);
		if (!empty($scan_path))
		{
			return $scan_path; 
		}
		else
		{
			return JPATH_SITE;
		}
	}
	public static function getDashboardURLs () {
		$url = array (); 
		$url[]= 'index.php?option=com_ose_firewall&view=vsscan';
		$url[]= 'index.php?option=com_ose_firewall&view=manageips';
		$url[]= 'index.php?option=com_ose_firewall&view=backup';
		$url[]= 'index.php?option=com_ose_firewall&view=configuration';
		$url[]= 'index.php?option=com_ose_firewall&view=scanconfig';
		$url[]= 'index.php?option=com_ose_firewall&view=seoconfig';
		$url[]= 'index.php?option=com_ose_firewall&view=advancerulesets';
		return $url; 
	}
	public static function getAdminEmail () {
		$config = oseJoomla::getConfig();
		return $config->mailfrom;
	}
	public static function getSiteURL () {
		return JURI::root();
	}
	public static function getConfigVars () {
		if (class_exists('SConfig'))
		{
			$config = new SConfig();
			return $config;
		}
		elseif (class_exists('JConfig'))
		{
			$config = new JConfig();
			return $config;
		}
	}
}