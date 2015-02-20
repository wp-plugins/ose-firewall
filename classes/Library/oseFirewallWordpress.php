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
	protected static $option = 'ose_firewall';
	public function __construct () {
		$debug = $this->getDebugMode(); 
		$this->setDebugMode (true);
	}
	protected function loadViews () {
		
    }
	public function initSystem()
	{
		add_action('init', array($this, 'startSession'), 1);
	}
    protected function addMenuActions () {
    	add_action('admin_menu', 'oseFirewall::showmenus');
    } 
	public static function getmenus(){
    	$extension = 'ose_firewall';
		$view = $_GET['page'];
		$menu = '<div class="bs-component">';
		$menu .= '<div class="navbar navbar-default">';
		$menu .= '<div class="navbar-collapse collapse navbar-responsive-collapse">';
		$menu .= '<ul id ="nav" class="nav navbar-nav">';
		// Dashboard Menu; 
		$menu .= '<li ';
		$menu .= ($view == 'ose_firewall') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_firewall">' . oLang::_get('DASHBOARD_TITLE') . '</a></li>';
		
		$menu .= '<li ';
		$menu .= (in_array($view, array('ose_fw_manageips', 'ose_fw_rulesets','ose_fw_variables','audit'))) ?'class="dropdown"' : 'class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">' . oLang::_get('SECURITY_MANAGEMENT') . '<b class="caret"></b></a>';
		// SubMenu Anti-Virus Starts; 
		$menu .= '<ul class="dropdown-menu">';
		$menu .= '<li ';
		$menu .= ($view == 'manageips') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_manageips">' . oLang::_get('MANAGE_IPS') . '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_rulesets') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_rulesets">' . oLang::_get('RULESETS'). '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_variables') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_variables">' . oLang::_get('VARIABLES_MANAGEMENT'). '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'audit') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_audit">' . oLang::_get('AUDIT_WEBSITE'). '</a></li>';
		
		$menu .= '</ul>';
	    // SubMenu Anti-Virus Ends;
		$menu .= '</li>';
		// Anti-Virus Menu; 
		
		// Anti-Hacking Menu; 
		$menu .= '<li ';
		$menu .= (in_array($view, array('ose_fw_adrulesets', 'ose_fw_variables'))) ? 'class="active dropdown"' : 'class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">' . oLang::_get('PREMIUM_SERVICE') . '<b class="caret"></b></a>';
		// SubMenu Anti-Hacking Starts; 
		$menu .= '<ul class="dropdown-menu">';
		
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_adrulesets') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_adrulesets">' . oLang::_get('ADRULESETS'). '</a></li>';
		
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_vsscan') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_vsscan">' . oLang::_get('ANTIVIRUS'). '</a></li>';
		
		
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_vsreport') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_vsreport">' . oLang::_get('VSREPORT'). '</a></li>';
		
		/*
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_clamav') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_clamav">' . oLang::_get('CLAMAV'). '</a></li>';
		*/
		
		
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_countryblock') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_countryblock">' . oLang::_get('COUNTRYBLOCK'). '</a></li>';
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
		$menu .= '><a href="admin.php?page=ose_fw_configuration">' . oLang::_get('INSTALLATION'). '</a></li>';
		// Configuration Feature Ends
		
		// About Menu
		$menu .= '<li ';
		$menu .= ($view == 'login') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_login">' . oLang::_get('MY_PREMIUM_SERVICE'). '</a></li>';
		
		// About Ends
		
		// Main Feature Ends; 
		$menu .= '</ul></div></div></div>';
		return $menu;
	}
	public static function showmenus(){
    	add_menu_page( OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL, 'manage_options', 'ose_firewall', 'oseFirewall::dashboard',OSE_FWURL.'/public/images/favicon.ico');
    	add_submenu_page( 'ose_firewall', OSE_DASHBOARD_SETTING, OSE_DASHBOARD, 'manage_options', 'ose_firewall', 'oseFirewall::dashboard' );
		add_submenu_page( 'ose_firewall', ANTIVIRUS, ANTIVIRUS, 'manage_options', 'ose_fw_vsscan', 'oseFirewall::vsscan' );
		//add_submenu_page( 'ose_firewall', CLAMAV, CLAMAV, 'manage_options', 'ose_fw_clamav', 'oseFirewall::clamav' );
		add_submenu_page( 'ose_firewall', VSREPORT, VSREPORT, 'manage_options', 'ose_fw_vsreport', 'oseFirewall::vsreport' );
		add_submenu_page( 'ose_firewall', MANAGE_IPS, MANAGE_IPS, 'manage_options', 'ose_fw_manageips', 'oseFirewall::manageips' );
		//add_submenu_page( 'ose_firewall', ADD_IPS, ADD_IPS, 'manage_options', 'ose_fw_addips', 'oseFirewall::ipform' );
		add_submenu_page( 'ose_firewall', AUDIT_WEBSITE, AUDIT_WEBSITE, 'manage_options', 'ose_fw_audit', 'oseFirewall::audit' );
		
		add_submenu_page( 'ose_firewall', RULESETS, RULESETS, 'manage_options', 'ose_fw_rulesets', 'oseFirewall::rulesets' );
		add_submenu_page( 'ose_firewall', ADRULESETS, ADRULESETS, 'manage_options', 'ose_fw_adrulesets', 'oseFirewall::advancerulesets' );
		add_submenu_page( 'ose_firewall', VARIABLES, VARIABLES, 'manage_options', 'ose_fw_variables', 'oseFirewall::variables' );
		add_submenu_page( 'ose_firewall', CONFIGURATION, CONFIGURATION, 'manage_options', 'ose_fw_configuration', 'oseFirewall::configuration' );
		add_submenu_page( 'ose_firewall', BACKUP, BACKUP, 'manage_options', 'ose_fw_backup', 'oseFirewall::backup' );
		add_submenu_page( 'ose_firewall', COUNTRYBLOCK, COUNTRYBLOCK, 'manage_options', 'ose_fw_countryblock', 'oseFirewall::countryblock' );
		add_submenu_page( 'ose_firewall', LOGIN, LOGIN, 'manage_options', 'ose_fw_login', 'oseFirewall::login' );
		add_submenu_page( 'ose_firewall', SUBSCRIPTION, SUBSCRIPTION, 'manage_options', 'ose_fw_subscription', 'oseFirewall::subscription' );
		//add_submenu_page( 'ose_firewall', VERSION_UPDATE, VERSION_UPDATE, 'manage_options', 'ose_fw_versionupdate', 'oseFirewall::versionupdate' );
		add_submenu_page( 'ose_fw_configuration', SEO_CONFIGURATION, SEO_CONFIGURATION, 'manage_options', 'ose_fw_seoconfig', 'oseFirewall::seoconfig' );
		add_submenu_page( 'ose_fw_configuration', SCAN_CONFIGURATION, SCAN_CONFIGURATION, 'manage_options', 'ose_fw_scanconfig', 'oseFirewall::scanconfig' );
		add_submenu_page( 'ose_fw_configuration', ANTIVIRUS_CONFIGURATION, ANTIVIRUS_CONFIGURATION, 'manage_options', 'ose_fw_avconfig', 'oseFirewall::avconfig' );
		add_submenu_page( 'ose_fw_configuration', ANTISPAM_CONFIGURATION, ANTISPAM_CONFIGURATION, 'manage_options', 'ose_fw_spamconfig', 'oseFirewall::spamconfig' );
		add_submenu_page( 'ose_fw_configuration', EMAIL_CONFIGURATION, EMAIL_CONFIGURATION, 'manage_options', 'ose_fw_emailconfig', 'oseFirewall::emailconfig' );
		add_submenu_page( 'ose_fw_configuration', EMAIL_ADMIN, EMAIL_ADMIN, 'manage_options', 'ose_fw_emailadmin', 'oseFirewall::emailadmin' );
		add_submenu_page( 'ose_fw_configuration', API_CONFIGURATION, API_CONFIGURATION, 'manage_options', 'ose_fw_apiconfig', 'oseFirewall::apiconfig' );
		//add_submenu_page( 'ose_firewall', ANTI_VIRUS_DATABASE_UPDATE, ANTI_VIRUS_DATABASE_UPDATE, 'manage_options', 'ose_fw_versionupdate', 'oseFirewall::updateChecking' );
	}
	public static function getAjaxScript() {
		//add_action('admin_head', 'oseFirewall::showAjaxHeader');
    }
    public static function showAjaxHeader() {
    	echo '<script type="text/javascript" >';
    	echo "var url = \"".admin_url('admin-ajax.php')."\";".
    			"var option=\"".self::$option."\";";
    	echo '</script>';
    }
public static function showLogo()
	{
		$url = 'http://www.centrora.com';
		$appTitle = OSE_WORDPRESS_FIREWALL;
		$head = '<nav class="navbar navbar-default" role="navigation">';
		$head .= '<div class="navbar-top">
					 <div class="col-lg-1 col-sm-6 col-xs-6 col-md-6">
						<div class="pull-left">
						</div>
					 </div>
					<div class="col-lg-11 col-sm-6 col-xs-6 col-md-6">
					 <div class="pull-right">
						<ul class="userMenu ">';
		$head .='<li><a href="//www.centrora.com/store/index.php?route=affiliate/login" title="Affiliate"><i class="fa fa-magnet"></i> <span class="hidden-xs hidden-sm hidden-md">Affiliate</span> </a></li>
						<li><a href="https://www.centrora.com/store/index.php?route=account/login" title="My Account"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md">My Account</span> </a></li>
						<li><a href="https://www.centrora.com/support-center/" id="support-center" title="Support"><i class="im-support"></i> <span class="hidden-xs hidden-sm hidden-md">Support</span></a></li>
						<li><a href="http://www.centrora.com/" title="Subscription"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md">Subscription</span></a></li>
						<li><a href="http://www.centrora.com/tutorial/" title="Tutorial"><i class="im-stack-list"></i> <span class="hidden-xs hidden-sm hidden-md">Tutorial</span></a></li>
						<li><a href="http://www.centrora.com/cleaning" title="Malware Removal"><i class="im-spinner10"></i> <span class="hidden-xs hidden-sm hidden-md">Malware Removal</span></a></li>';
		if (OSE_CMS == 'joomla')
		{
			$head .= '<li><a href="index.php" title="Home"><i class="im-home7"></i> <span class="hidden-xs hidden-sm hidden-md">Home</span> </a></li>';
		}				
		$head .=	'</ul>
					 </div>
				   </div>
				 </div>';
		$head .= '<div class ="everythingOnOneLine">
					<div class ="col-lg-12">
						<div class="logo"></div>
					<div class ="version-normal">'.self::getVersion ().'</div>';
		
		#Get update server version
		$plugins = get_plugin_updates();
		foreach ( (array) $plugins as $plugin_file => $plugin_data) {
			if ($plugin_data->update->slug  == "ose-firewall"){
				$serverversion = $plugin_data->update->new_version;}
		}
		
		oseFirewall::loadJSFile ('CentroraUpdateApp', 'VersionAutoUpdate.js', false);
				
		#pass update url to js to run through ajax. Update handled by url function.
		$file ='ose-firewall/ose_wordpress_firewall.php';
		$updateurl = wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file);
		$activateurl = esc_url(wp_nonce_url(admin_url('plugins.php?action=activate&plugin=' . $file), 'activate-plugin_' . $file));

		#Check user, Compare versions then run bootbox js and ajax calls for confirmation
		if (current_user_can('update_plugins') && self::getVersionCompare($serverversion) > 0) { #server version: -1 Old, 0 Same, +1 New	
			$head .= '<input class="version-update" type="button" value="Update to : '.$serverversion.'" 
						onclick="showAutoUpdateDialogue(\'Are you sure you want to update to: '.$serverversion.'?\', 
														\'Update Confirmation\', 
														\'UPDATE\', 
														\''.$updateurl.'\', 
														\''.$file.'\', 
														\''.$activateurl.'\'	)"/>
					  </div></div></nav>';
		} 
		else 
		{
				$head .= '</div></div></nav>';
		}
				
		echo $head;
		echo oseFirewall::getmenus();
	}
	
	#Compare local version with the update server version
	private static function getVersionCompare($serverversion){
	$pluginData = get_plugin_data(OSEFWDIR.'/ose_wordpress_firewall.php');
			$localversion = $pluginData['Version'];
			$compareversions = version_compare($serverversion, $localversion) ;
			return $compareversions;
	}
	private static function getVersion () {
		$pluginData = get_plugin_data(OSEFWDIR.'/ose_wordpress_firewall.php');
		return 'Version: '.	$pluginData['Version']; 
	}
	public static function loadNounce () {
		return wp_create_nonce( 'centnounce' ); 
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
			return addslashes(OSE_ABSPATH);
		}	
	}
	public static function getDashboardURLs () {
		$url = array (); 
		$url[]= 'admin.php?page=ose_fw_vsscan';
		$url[]= 'admin.php?page=ose_fw_manageips';
		$url[]= 'admin.php?page=ose_fw_backup';
		$url[]= 'admin.php?page=ose_fw_configuration';
		$url[]= 'admin.php?page=ose_fw_scanconfig';
		$url[]= 'admin.php?page=ose_fw_seoconfig';
		$url[]= 'admin.php?page=ose_fw_adrulesets';
		return $url; 
	}
	public static function getAdminEmail () {
		return get_option( 'admin_email' );
	}
	public static function getSiteURL () {
		return OSE_WPURL;
	}
	public static function getConfigVars () {
			$bloginfo = new stdClass(); 
			$bloginfo->url = get_bloginfo('url');
			$bloginfo->fromname = get_bloginfo('name');
			$bloginfo->mailfrom = get_bloginfo('admin_email');
			return $bloginfo; 
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
		wp_enqueue_script( $tag, $url, array(), '1.0.0', true );
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
		wp_enqueue_script( $tag, $url, array(), '1.0.0', true );
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
		wp_enqueue_style( $tag, $url );
	}
	public static function loadCSSURL ($tag, $url) {
		wp_enqueue_style( $tag, $url );
	}
	public static function redirectLogin () {
		echo '<script type="text/javascript">location.href="admin.php?page=ose_fw_login"</script>';
	}
	public static function redirectSubscription () {
		echo '<script type="text/javascript">location.href="admin.php?page=ose_fw_subscription"</script>';
	}
	public static function isBadgeEnabled () {
		$results = wp_get_sidebars_widgets ();
		$return = false;
		if (!empty($results)) 
		{
			foreach ($results as $result) {
				if (!empty($result))
				{	
					foreach ($result as $widget)
					{
						if (strstr($widget, 'ose_badge_widget')!=false)
						{
							$return = true;
							break;
						}				
					}
				}
			}
		}
		return $return;
	}
}