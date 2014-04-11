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
	protected static $option = 'ose_firewall';
	public function __construct () {
		$debug = $this->getDebugMode(); 
		$this->setDebugMode ($debug);
	}
	protected function loadViews () {
       
    }
    protected function addMenuActions () {
    	add_action('admin_menu', 'oseFirewall::showmenus');
    } 
	public static function getmenus(){
    	$extension = 'ose_firewall';
		$view = $_GET['page'];
		$menu = '<div class="menu-search">';
		$menu .= '<ul id ="nav">';
		// Dashboard Menu; 
		$menu .= '<li ';
		$menu .= ($view == 'ose_firewall') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_firewall">' . oLang::_get('DASHBOARD_TITLE') . '</a></li>';
		// Anti-Hacking Menu; 
		$menu .= '<li ';
		$menu .= (in_array($view, array('ose_fw_manageips', 'ose_fw_rulesets', 'ose_fw_adrulesets', 'ose_fw_variables'))) ? 'class="current"' : '';
		$menu .= '><a href="#">' . oLang::_get('ANTI_HACKING') . '</a>';
		// SubMenu Anti-Hacking Starts; 
		$menu .= '<ul>';
		$menu .= '<li ';
		$menu .= ($view == 'manageips') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_manageips">' . oLang::_get('MANAGE_IPS') . '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_rulesets') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_rulesets">' . oLang::_get('RULESETS'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_adrulesets') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_adrulesets">' . oLang::_get('ADRULESETS'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_variables') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_variables">' . oLang::_get('VARIABLES'). '</a></li>';
		$menu .= '</ul>';
	    // SubMenu Anti-Hacking Ends;
		$menu .= '</li>';
		// Anti-Virus Menu; 
		$menu .= '<li ';
		$menu .= (in_array($view, array('ose_fw_vsscan', 'ose_fw_vsreport'))) ?'class="current"' : '';
		$menu .= '><a href="#">' . oLang::_get('ANTI_VIRUS') . '</a>';
		// SubMenu Anti-Virus Starts; 
		$menu .= '<ul>';
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_vsscan') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_vsscan">' . oLang::_get('ANTIVIRUS'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_vsreport') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_vsreport">' . oLang::_get('VSREPORT'). '</a></li>';
		$menu .= '</ul>';
	    // SubMenu Anti-Virus Ends;
		$menu .= '</li>';
		// Premium Feature Menu; 
		/*$menu .= '<li ';
		$menu .= ($view == 'antivirus') ? 'class="current"' : '';
		$menu .= '><a href="#">' . oLang::_get('PREMIUM_FEATURES') . '</a>';
		// SubMenu Premium Feature Starts; 
		$menu .= '<ul>';
		$menu .= '<li ';
		$menu .=(in_array($view, array('ose_fw_backup', 'ose_fw_versionupdate'))) ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_countryblock">' . oLang::_get('COUNTRYBLOCK'). '</a></li>';
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_versionupdate') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_versionupdate">' . oLang::_get('VERSION_UPDATE'). '</a></li>';
		$menu .= '</ul>';
	    // SubMenu Premium Feature Ends;
		$menu .= '</li>';*/
		
		// Configuration Menu; 
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_configuration') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_configuration">' . oLang::_get('CONFIGURATION'). '</a></li>';
		
		// Backup Feature Menu
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_backup') ? 'class="current"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_backup">' . oLang::_get('BACKUP'). '</a></li>';
		// BackUp Feature Ends
		
		// Main Feature Ends; 
		$menu .= '</ul>
		
		</div>';
		return $menu;
	}
	public static function showmenus(){
    	add_menu_page( OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL, 'manage_options', 'ose_firewall', 'oseFirewall::dashboard',OSE_FWURL.'/public/images/favicon.ico');
    	add_submenu_page( 'ose_firewall', OSE_DASHBOARD_SETTING, OSE_DASHBOARD, 'manage_options', 'ose_firewall', 'oseFirewall::dashboard' );
		add_submenu_page( 'ose_firewall', ANTIVIRUS, ANTIVIRUS, 'manage_options', 'ose_fw_vsscan', 'oseFirewall::vsscan' );
		add_submenu_page( 'ose_firewall', VSREPORT, VSREPORT, 'manage_options', 'ose_fw_vsreport', 'oseFirewall::vsreport' );
		add_submenu_page( 'ose_firewall', MANAGE_IPS, MANAGE_IPS, 'manage_options', 'ose_fw_manageips', 'oseFirewall::manageips' );
		add_submenu_page( 'ose_firewall', RULESETS, RULESETS, 'manage_options', 'ose_fw_rulesets', 'oseFirewall::rulesets' );
		add_submenu_page( 'ose_firewall', ADRULESETS, ADRULESETS, 'manage_options', 'ose_fw_adrulesets', 'oseFirewall::advancerulesets' );
		add_submenu_page( 'ose_firewall', VARIABLES, VARIABLES, 'manage_options', 'ose_fw_variables', 'oseFirewall::variables' );
		add_submenu_page( 'ose_firewall', CONFIGURATION, CONFIGURATION, 'manage_options', 'ose_fw_configuration', 'oseFirewall::configuration' );
		add_submenu_page( 'ose_firewall', CONFIGURATION, BACKUP, 'manage_options', 'ose_fw_backup', 'oseFirewall::backup' );
		//add_submenu_page( 'ose_firewall', COUNTRYBLOCK, COUNTRYBLOCK, 'manage_options', 'ose_fw_countryblock', 'oseFirewall::countryblock' );
		//add_submenu_page( 'ose_firewall', VERSION_UPDATE, VERSION_UPDATE, 'manage_options', 'ose_fw_versionupdate', 'oseFirewall::versionupdate' );
		add_submenu_page( 'ose_fw_configuration', SEO_CONFIGURATION, SEO_CONFIGURATION, 'manage_options', 'ose_fw_seoconfig', 'oseFirewall::seoconfig' );
		add_submenu_page( 'ose_fw_configuration', SCAN_CONFIGURATION, SCAN_CONFIGURATION, 'manage_options', 'ose_fw_scanconfig', 'oseFirewall::scanconfig' );
		add_submenu_page( 'ose_fw_configuration', ANTIVIRUS_CONFIGURATION, ANTIVIRUS_CONFIGURATION, 'manage_options', 'ose_fw_avconfig', 'oseFirewall::avconfig' );
		add_submenu_page( 'ose_fw_configuration', ANTISPAM_CONFIGURATION, ANTISPAM_CONFIGURATION, 'manage_options', 'ose_fw_spamconfig', 'oseFirewall::spamconfig' );
		add_submenu_page( 'ose_fw_configuration', EMAIL_CONFIGURATION, EMAIL_CONFIGURATION, 'manage_options', 'ose_fw_emailconfig', 'oseFirewall::emailconfig' );
		add_submenu_page( 'ose_fw_configuration', EMAIL_ADMIN, EMAIL_ADMIN, 'manage_options', 'ose_fw_emailadmin', 'oseFirewall::emailadmin' );
		//add_submenu_page( 'ose_firewall', ANTI_VIRUS_DATABASE_UPDATE, ANTI_VIRUS_DATABASE_UPDATE, 'manage_options', 'ose_fw_versionupdate', 'oseFirewall::updateChecking' );
	}
	public static function getAjaxScript() {
    	return "var url = \"".admin_url('admin-ajax.php')."\";".
			   "var option=\"".self::$option."\";";
    }
}