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
			$query = "UPDATE `#__menu` SET `alias` =  'Centrora Security™', `path` =  'Centrora Security™', `published`=1, `img` = '\"components/com_ose_firewall/public/images/favicon.ico\"'  WHERE `component_id` = ( SELECT extension_id FROM `#__extensions` WHERE `element` ='com_ose_firewall')  AND `client_id` = 1 ";
			$db->setQuery($query);
			$db->query();
		}
		$db->closeDBO(); 
		$extension = 'com_ose_firewall';
		$view = JRequest :: getVar('view');
		
		$menu = '<div class="bs-component">';
		$menu .= '<div class="navbar navbar-default">';
		$menu .= '<div class="navbar-collapse collapse navbar-responsive-collapse">';
		$menu .= '<ul id ="nav" class="nav navbar-nav">';
		// Dashboard Menu;
		$menu .= '<li ';
		$menu .= ($view == 'dashboard') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=dashboard">' . oLang::_get('DASHBOARD_TITLE') . '</a></li>';
		
		$menu .= '<li ';
		$menu .= (in_array($view, array('manageips', 'rulesets','variables','audit'))) ?'class="dropdown"' : 'class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">' . oLang::_get('SECURITY_MANAGEMENT') . '<b class="caret"></b></a>';
		// SubMenu Anti-Virus Starts;
		$menu .= '<ul class="dropdown-menu">';
		$menu .= '<li ';
		$menu .= ($view == 'manageips') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=manageips">' . oLang::_get('MANAGE_IPS') . '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'rulesets') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=rulesets">' . oLang::_get('RULESETS'). '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'variables') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=variables">' . oLang::_get('VARIABLES_MANAGEMENT'). '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'audit') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=audit">' . oLang::_get('AUDIT_WEBSITE'). '</a></li>';
		
		$menu .= '</ul>';
		// SubMenu Anti-Virus Ends;
		$menu .= '</li>';
		// Anti-Virus Menu;
		
		// Anti-Hacking Menu;
		$menu .= '<li ';
		$menu .= (in_array($view, array('advancerulesets', 'vsscan', 'vsreport', 'countryblock'))) ? 'class="active dropdown"' : 'class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">' . oLang::_get('PREMIUM_SERVICE') . '<b class="caret"></b></a>';
		// SubMenu Anti-Hacking Starts;
		$menu .= '<ul class="dropdown-menu">';
		
		$menu .= '<li ';
		$menu .= ($view == 'advancerulesets') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=advancerulesets">' . oLang::_get('ADRULESETS'). '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'vsscan') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsscan">' . oLang::_get('ANTIVIRUS'). '</a></li>';
		
		
		$menu .= '<li ';
		$menu .= ($view == 'vsreport') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsreport">' . oLang::_get('VSREPORT'). '</a></li>';
		
		/*
		 $menu .= '<li ';
		$menu .= ($view == 'ose_fw_clamav') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_clamav">' . oLang::_get('CLAMAV'). '</a></li>';
		*/
		
		
		$menu .= '<li ';
		$menu .= ($view == 'countryblock') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=countryblock">' . oLang::_get('COUNTRYBLOCK'). '</a></li>';
		$menu .= '</ul>';
		// SubMenu Anti-Hacking Ends;
		$menu .= '</li>';
		/*
		 // Backup Feature Menu
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_backup') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_backup">' . oLang::_get('BACKUP'). '</a></li>';
		*/
		// BackUp Feature Ends
		
		// Configuration Menu;
		$menu .= '<li ';
		$menu .= ($view == 'configuration') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=configuration">' . oLang::_get('INSTALLATION'). '</a></li>';
		// Configuration Feature Ends
		
		// About Menu
		$menu .= '<li ';
		$menu .= ($view == 'login') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=login">' . oLang::_get('MY_PREMIUM_SERVICE'). '</a></li>';
		// About Ends
		
		
		$menu .=self::addSuiteMenu ();
		
		// Main Feature Ends;
		$menu .= '</ul></div></div></div>';
		return $menu;
	}
	protected static function addSuiteMenu () {
		$option = JRequest::getVar('option', null);
		$menu = '';
		$menu .= '<li class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">Administrator Menu<b class="caret"></b></a>';
		// SubMenu Anti-Virus Starts;
		$menu .= '<ul class="dropdown-menu">';
		$menu .= '<li ';
		$menu .= ($option == 'com_users') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_users&view=users">User Manager</a></li>';
		
		$menu .= '<li ';
		$menu .= ($option == 'com_installer') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_installer">Extension Manager</a></li>';
		
		$menu .= '<li ';
		$menu .= ($option == 'com_admin') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_admin&view=sysinfo">System Information</a></li>';
		
		$menu .= '<li ';
		$menu .= ($option == 'com_config') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_config">Global Configuration</a></li>';
		
		$menu .= '<li ';
		$menu .= ($option == 'com_plugins') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_plugins&view=plugins">Plugin Manager</a></li>';
		
		$menu .= '<li ';
		$menu .= ($option == 'com_login') ? 'class="active"' : '';
		
		$menu .= '><a href="index.php?option=com_login&task=logout&'.JUtility::getToken().'=1">Logout</a></li>';
		
		$menu .= '</ul></li>';
		return $menu;
	}
	public static function getAjaxScript() {
    	return "var ajaxurl = \"".OURI::base()."index.php\";".
			   "var option=\"".self::$option."\";";
    }
	public static function showLogo()
	{
		$url = 'http://www.centrora.com';
		$appTitle = OSE_WORDPRESS_FIREWALL;
		$head = '<div id="logo-labels">';
		$head .= '<div class ="col-lg-6"><div class ="version-normal">'.self::getVersion ().'</div></div><div class ="col-lg-6">';
		$head .= '<ul class="nav navbar-nav pull-right">';
		if (OSE_CMS == 'joomla')
		{
			$head .= '<li><i class="im-home7"></i><span class="txt"><a href="index.php">Home</a></span></li>';
		}
		$head .= '<li><i class="im-support"></i><span class="txt"><a href="http://www.centrora.com/support-center/" target="__blank">Support</a></span></li>
				  <li><i class="im-stack-list"></i><span class="txt"><a href="http://www.centrora.com/tutorial/" target="__blank">User Manual</a></span></li>
				  <li><i class="im-spinner10"></i><span class="txt"><a href="http://www.centrora.com/tutorial/" target="__blank">Malware Removal</a></span></li>';
		$head .= '</ul></div></div>';
		echo $head;
		echo oseFirewall::getmenus();
	}
	private static function getVersion () {
		$xml = JFactory::getXML(JPATH_ADMINISTRATOR .'/components/com_ose_firewall/ose_firewall.xml');
		$version = (string)$xml->version;
		return 'Version: '.$version; 
	}
	public static function loadNounce () {
		return JSession::getFormToken(); 
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
			return addslashes(JPATH_SITE);
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
	public static function loadJSFile ($tag, $filename, $remote) {
		if ($remote == false)
		{
			$url = OSE_FWURL.'/public/js/'.$filename;
		}
		else
		{
			$url = $filename;
		}
		
		$document = JFactory::getDocument();
		$document->addScript ($url, "text/javascript", true, false);
		//JHtml::script($url);
	}
	public static function loadLanguageJSFile ($tag, $filename, $remote) {
		if ($remote == false)
		{
			$url = OSE_FWURL.'/public/messages/'.$filename;
		}
		else
		{
			$url = $filename;
		}
		JHtml::script($url);
	}
	public static function loadCSSFile ($tag, $filename, $remote) {
		if ($remote == false)
		{
			$url = OSE_FWURL.'/public/css/'.$filename;
		}
		else
		{
			$url = $filename;
		}
		JHtml::stylesheet($url);
	}
	public static function redirectLogin () {
		echo '<script type="text/javascript">location.href="index.php?option=com_ose_firewall&view=login"</script>';
	}
	public static function redirectSubscription () {
		echo '<script type="text/javascript">location.href="index.php?option=com_ose_firewall&view=subscription"</script>';
	}
}