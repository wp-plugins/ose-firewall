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
require_once (dirname(__FILE__).ODS.'oseFirewallBase.php');
class oseFirewall extends oseFirewallBase {
	protected static $option = 'com_ose_firewall';
	public function __construct () {
		$debug = $this->getDebugMode(); 
		$this->setDebugMode ($debug);
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
		$query = "SELECT * FROM `#__menu` WHERE `alias` =  'OSE Firewall™ Security'";
		$db->setQuery($query);
		$results = $db->loadResult();
		if (empty ($results)) {
			$query = "UPDATE `#__menu` SET `alias` =  'OSE Firewall™ Security', `path` =  'OSE Firewall™ Security', `published`=1, `img` = '\"components/com_ose_firewall/favicon.ico\"'  WHERE `component_id` = ( SELECT extension_id FROM `#__extensions` WHERE `element` ='com_ose_firewall')  AND `client_id` = 1 ";
			$db->setQuery($query);
			$db->query();
		}
		$extension = 'com_ose_firewall';
		$view = JRequest :: getVar('view');
		$menu = '<div class="menu-search">';
		$menu .= '<ul>';
		$menu .= '<li ';
		$menu .= ($view == 'dashboard') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=dashboard">' . oLang::_get('DASHBOARD') . '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'manageips') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=manageips">' . oLang::_get('MANAGE_IPS') . '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'activation') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=rulesets">' . oLang::_get('RULESETS'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'activation') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=variables">' . oLang::_get('VARIABLES'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'activation') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsscan">' . oLang::_get('ANTIVIRUS'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'activation') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsreport">' . oLang::_get('VSREPORT'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'activation') ? 'class="current"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=configuration">' . oLang::_get('CONFIGURATION'). '</a></li>';
		$menu .= '</ul></div>';
		return $menu;
	}
	public static function getAjaxScript() {
    	return "var url = \"".OURI::base()."index.php\";".
			   "var option=\"".self::$option."\";";
    }
}