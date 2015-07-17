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
		
		$menu = '<div class="navbar navbar-default">';
		$menu .= '<div class="navbar-collapse collapse navbar-responsive-collapse">';
		$menu .= '<ul id ="nav" class="nav navbar-nav">';
		// Dashboard Menu;
		$menu .= '<li ';
		$menu .= ($view == 'dashboard') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=dashboard"><i class="glyphicon glyphicon-dashboard"></i> ' . oLang::_get('DASHBOARD_TITLE') . '</a></li>';
		
		$menu .= '<li ';
        $menu .= (in_array($view, array('manageips', 'rulesets', 'bsconfig', 'countryblock', 'variables'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-fire"></i> ' . oLang::_get('FIREWALL') . '<b class="caret"></b></a>';
		// SubMenu Anti-Virus Starts;
		$menu .= '<ul class="dropdown-menu">';
		$menu .= '<li ';
		$menu .= ($view == 'manageips') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=manageips">' . oLang::_get('MANAGE_IPS') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'variables') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=variables">' . oLang::_get('VARIABLES_MANAGEMENT') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'countryblock') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=countryblock">' . oLang::_get('COUNTRYBLOCK') . '</a></li>';

        $menu .= '<li ';
		$menu .= ($view == 'bsconfig') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=bsconfig">' . oLang::_get('FIREWALL_CONFIGURATION') . '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'rulesets') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=rulesets">' . oLang::_get('FIREWALL_RULES'). '</a></li>';


		$menu .= '</ul>';
		// SubMenu Anti-Virus Ends;
		$menu .= '</li>';
		// Anti-Virus Menu;
		
		// Anti-Hacking Menu;
		$menu .= '<li ';
        $menu .= (in_array($view, array('vsscan', 'vsreport'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-screenshot"></i> ' . oLang::_get('ANTIVIRUS') . '<b class="caret"></b></a>';
		// SubMenu Anti-Hacking Starts;
		$menu .= '<ul class="dropdown-menu">';

		$menu .= '<li ';
		$menu .= ($view == 'vsscan') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsscan">' . oLang::_get('ANTIVIRUS'). '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'vsreport') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsreport">' . oLang::_get('VSREPORT'). '</a></li>';

        $menu .= '</ul>';
        // SubMenu Anti-Hacking Ends;
        $menu .= '</li>';
		/*
		 $menu .= '<li ';
		$menu .= ($view == 'ose_fw_clamav') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_clamav">' . oLang::_get('CLAMAV'). '</a></li>';
		*/
        $menu .= '<li ';
        $menu .= (in_array($view, array('backup', 'advancebackup', 'authentication'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-duplicate"></i> ' . oLang::_get('O_BACKUP') . '<b class="caret"></b></a>';
        // SubMenu Backup Starts;
        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= ($view == 'backup') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=backup">' . oLang::_get('BACKUP_MANAGER') . '</a></li>';

		
		$menu .= '<li ';
		$menu .= ($view == 'advancedbackup') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=advancedbackup">' . oLang::_get('ADVANCEDBACKUP') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'authentication') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=authentication">' . oLang::_get('AUTHENTICATION') . '</a></li>';

		$menu .= '</ul>';
		// SubMenu Backup Ends;
		$menu .= '</li>';
		
        $menu .= '<li ';
        $menu .= (in_array($view, array('permconfig'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-list-alt"></i> ' . oLang::_get('FILE_PERMISSION') . '<b class="caret"></b></a>';
        // SubMenu File Permissions Starts;
        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= ($view == 'permconfig') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=permconfig">' . oLang::_get('PERMCONFIG') . '</a></li>';

        $menu .= '</ul>';
        // SubMenu File Permissions Ends;
        $menu .= '</li>';
        
        // System Menu;
        $menu .= '<li ';
        $menu .= (in_array($view, array('adminemails', 'cronjobs', 'audit', 'configuration'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cd"></i> ' . oLang::_get('ADMINISTRATION') . '<b class="caret"></b></a>';
        // SubMenu System Starts;
        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= ($view == 'adminemails') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=adminemails">' . oLang::_get('ADMINEMAILS') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'audit') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=audit">' . oLang::_get('AUDIT_WEBSITE') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'cronjobs') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=cronjobs">' . oLang::_get('CRONJOBS') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'configuration') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=configuration">' . oLang::_get('INSTALLATION') . '</a></li>';

        $menu .= '</ul>';
        // SubMenu System Ends;
        $menu .= '</li>';
		// System Ends

		if (class_exists('SConfig'))
		{
			// About Menu
			$menu .= '<li ';
			$menu .= (in_array($view, array('activation'))) ? 'class="active"' : '';
			$menu .= '><a href="index.php?option=' . $extension . '&view=activation"><i class="glyphicon glyphicon-flash"></i> ' . oLang::_get('ACTIVATION_CODES'). '</a></li>';
			// About Ends
		}
        $menu .= self::addSuiteMenu();

        // My account menu starts
        $menu .= '<li ';
        $menu .= (in_array($view, array('login', 'subscription'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-scale"></i> ' . oLang::_get('MY_ACCOUNT') . '<b class="caret"></b></a>';
        // SubMenu My Account Starts;
        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= '><a href="http://www.centrora.com/store/subscription-packages/" target="_blank">' . oLang::_get('MY_PREMIUM_SERVICE') . '</a></li>';

        $menu .= '<li ';
        $menu .= (in_array($view, array('login', 'subscription'))) ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=login">' . oLang::_get('LOGIN_OR_SUBSCIRPTION') . '</a></li>';
		// SubMenu My Account Ends;
        $menu .= '</ul>';
        // My account menu ends
        $menu .= '</li>';
		// Main Feature Ends;
		$menu .= '</ul></div></div>';
        return $menu;
	}
	protected static function addSuiteMenu () {
		$option = JRequest::getVar('option', null);
		$menu = '';
		$menu .= '<li class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> Administrator Menu<b class="caret"></b></a>';
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

        $menu .= '><a href="index.php?option=com_login&task=logout&'.self::loadNounce().'=1">Logout</a></li>';

        $menu .= '</ul></li>';
		return $menu;
	}
	public static function getAjaxScript() {
    	return "var ajaxurl = \"".OURI::base()."index.php\";".
			   "var option=\"".self::$option."\";";
    }
	public static function showLogo()
	{
		$oem = new CentroraOEM() ;
		$head = '<nav class="navbar navbar-default" role="navigation">';
		$head .= '<div class ="everythingOnOneLine">
					<div class ="col-lg-12">
						<div class="logo"><img src="'.OURI::base().'components/com_ose_firewall/public/images/logo5.png" width="250px" alt ="Centrora Logo"/></div>'.$oem->showOEMName ();
		#server version: -1 Old, 0 Same, +1 New
		$serverversion = self::getServerVersion();
		$isOutdated = (self::getVersionCompare($serverversion) > 0)?true:false;
		$hasNews = self::checkNewsUpdated();
		$head .='<div id ="versions"> <div class ="'.(($isOutdated==true)?'version-outdated':'version-updated').'"><i class="glyphicon glyphicon-'.(($isOutdated==true)?'remove':'ok').'"></i>  '.self::getVersion ().'</div>';
		$urls = self::getDashboardURLs();
		oseFirewall::loadJSFile ('CentroraUpdateApp', 'VersionAutoUpdate.js', false);
		self::getAjaxScript();
		if ($isOutdated) { 
			$head .= '<button class="version-update" type="button" onclick="showAutoUpdateDialogue(\''.$serverversion.'\', \''.$urls[8].'\')"/><i class="glyphicon glyphicon-refresh"></i> Update to : '.$serverversion.'</button>';
		}
		$head .= '</div>';
		
		$head .='<div class="centrora-news"><i class="glyphicon glyphicon-bullhorn"></i> <a class="color-white" href="'.$urls[8].'">What\'s New? </a><i class="glyphicon glyphicon-'.(($hasNews==true)?'asterisk':'').' color-magenta"></i></div>';
		
		if (oseFirewall::affiliateAccountExists()==false)
		{
			$head .='<div class="centrora-affiliates"><button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#affiliateFormModal" href="#" ><i class="glyphicon glyphicon-magnet"></i> '.oLang::_get('AFFILIATE_TRACKING').'</button></div>';
		}
		$head .= oseFirewall::getmenus();
		
		$head .= '</div></div>';
		$head .= '<div class="navbar-top">
					 <div class="col-lg-1 col-sm-6 col-xs-6 col-md-6">
						<div class="pull-left">
						</div>
					 </div>
					<div class="col-lg-11 col-sm-6 col-xs-6 col-md-6">
					 <div class="pull-right">
						<ul class="userMenu ">';
		
		$head .= $oem->getTopBarURL ();
		if (OSE_CMS == 'joomla')
		{
			$head .= '<li><a href="index.php" title="Home">Quick links:&nbsp;&nbsp;&nbsp;<i class="im-home7"></i> <span class="hidden-xs hidden-sm hidden-md">Centrora</span> </a></li>';
		}
		$head .=	'</ul>
					 </div>
					</div>
				 </div>';
		$head .='</nav>';
		#take care of ajax js to run unpdate		
		if(isset($_POST['updateaction']) && !empty($_POST['updateaction'])) {
		    $action = $_POST['updateaction'];
		    switch($action) {
		        case 'upgrade-plugin' : self::runUpdate() ;break;
		    }
		}
		
		echo $head;
	}
	# Run the automatic update procedures
	private static function runUpdate(){
		oseFirewall::callLibClass('panel','panel');
		$panel2 = new panel ();
		return $panel2->runAutomaticUpdate();	
	}
	#Check for version updates	
	private static function getServerVersion(){
		oseFirewall::callLibClass('panel','panel');
		$panel = new panel ();
		return $panel->getLatestVersion();	
	}
	#Compare local version with the update server version
	private static function getVersionCompare($serverversion){
		$xml = JFactory::getXML(JPATH_ADMINISTRATOR .'/components/com_ose_firewall/ose_firewall.xml');
		$localversion = (string)$xml->version;
		$compareversions = version_compare($serverversion, $localversion) ;
		return $compareversions;
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
		$url[]= 'index.php?option=com_ose_firewall&view=rulesets';
		$url[]= 'index.php?option=com_ose_firewall&view=bsconfig';
		$url[]= 'index.php?option=com_ose_firewall&view=news';
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
			$url = OSE_FWURL.'public/js/'.$filename;
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
			$url = OSE_FWURL.'public/messages/'.$filename;
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
			$url = OSE_FWURL.'public/css/'.$filename;
		}
		else
		{
			$url = $filename;
		}
		JHtml::stylesheet($url);
	}
	public static function loadCSSURL ($tag, $url) {
		JHtml::stylesheet($url);
	}
	public static function redirectLogin () {
		echo '<script type="text/javascript">location.href="index.php?option=com_ose_firewall&view=login"</script>';
	}
	public static function redirectSubscription () {
		echo '<script type="text/javascript">location.href="index.php?option=com_ose_firewall&view=subscription"</script>';
	}
	public static function isBadgeEnabled () { 
		return true;
	}
	public static function getConfigurationURL () {
		return 'index.php?option=com_ose_firewall&view=bsconfig';
	}
}