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
        oseFirewall::callLibClass('firewallstat', 'firewallstatWordpress');
        $oseFirewallStat = new oseFirewallStat();
        $results = $oseFirewallStat->getConfiguration('scan');
        if ($results['data']['strongPassword'] == 1) {
            add_action('user_profile_update_errors', 'oseFirewall::validatePassword', 0, 3);
        }
        if (!empty($results['data']['loginSlug'])) {
            add_action('plugins_loaded', array($this, 'plugins_loaded'), 2);
            // add_action( 'admin_notices', array( $this, 'admin_notices' ) );
            add_action('wp_loaded', array($this, 'wp_loaded'));

            add_filter('site_url', array($this, 'site_url'), 10, 4);
            add_filter('wp_redirect', array($this, 'wp_redirect'), 10, 2);
        }
    }
    protected static function addMenuActions () {
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
		$menu .= '><a href="admin.php?page=ose_firewall"><i class="glyphicon glyphicon-dashboard"></i> ' . oLang::_get('DASHBOARD_TITLE') . '</a></li>';

        $menu .= '<li id="dropdownMenu1"';
        $menu .= (in_array($view, array('ose_fw_manageips', 'ose_fw_variables', 'ose_fw_rulesets', 'ose_fw_countryblock', 'ose_fw_bsconfig', 'ose_fw_upload'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-fire"></i> ' . oLang::_get('FIREWALL') . '<b class="caret"></b></a>';
		// SubMenu Anti-Virus Starts; 
        $menu .= '<ul class="dropdown-menu dropdown-menu-middle" aria-labelledby="dropdownMenu1">';

        $menu .= '<li ';
        $menu .= ($view == 'manageips') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_manageips">' . oLang::_get('MANAGE_IPS') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_variables') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_variables">' . oLang::_get('VARIABLES_MANAGEMENT') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_fileextension') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_upload">' . oLang::_get('FILE_UPLOAD_MANAGEMENT') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_countryblock') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_countryblock">' . oLang::_get('COUNTRYBLOCK') . '</a></li>';

		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_bsconfig') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_bsconfig">' . oLang::_get('FIREWALL_CONFIGURATION'). '</a></li>';


        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_rulesets') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_rulesets">' . oLang::_get('FIREWALL_RULES') . '</a></li>';


		$menu .= '</ul>';
	    // SubMenu Anti-Virus Ends;
		$menu .= '</li>';
		// Anti-Virus Menu; 
		
		// Anti-Hacking Menu; 
		$menu .= '<li ';
        $menu .= (in_array($view, array('ose_fw_vsscan', 'ose_fw_scanreport'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-screenshot"></i> ' . oLang::_get('ANTIVIRUS') . '<b class="caret"></b></a>';
		// SubMenu Anti-Hacking Starts; 
		$menu .= '<ul class="dropdown-menu">';

      	$menu .= '<li ';
		$menu .= ($view == 'ose_fw_vsscan') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_vsscan">' . oLang::_get('ANTIVIRUS'). '</a></li>';
		
		
		$menu .= '<li ';
        $menu .= ($view == 'ose_fw_scanreport') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_scanreport">' . oLang::_get('VSREPORT') . '</a></li>';

        $menu .= '</ul>';
        // SubMenu Anti-Hacking Ends;
        $menu .= '</li>';
		/*
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_clamav') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_clamav">' . oLang::_get('CLAMAV'). '</a></li>';
		*/
        $menu .= '<li ';
        $menu .= (in_array($view, array('ose_fw_backup', 'ose_fw_advancedbackup', 'ose_fw_authentication'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-duplicate"></i> ' . oLang::_get('O_BACKUP') . '<b class="caret"></b></a>';
        // SubMenu Anti-Virus Starts;
        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_backup') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_backup">' . oLang::_get('BACKUP_MANAGER') . '</a></li>';

		
		$menu .= '<li ';
		$menu .= ($view == 'ose_fw_advancedbackup') ? 'class="active"' : '';
		$menu .= '><a href="admin.php?page=ose_fw_advancedbackup">' . oLang::_get('ADVANCEDBACKUP') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_authentication') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_authentication">' . oLang::_get('AUTHENTICATION') . '</a></li>';

        $menu .= '</ul>';
		// SubMenu Anti-Hacking Ends;
		$menu .= '</li>';


        $menu .= '<li ';
        $menu .= (in_array($view, array('ose_fw_permconfig'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-list-alt"></i> ' . oLang::_get('FILE_PERMISSION') . '<b class="caret"></b></a>';
        // SubMenu Anti-Hacking Starts;
        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_permconfig') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_permconfig">' . oLang::_get('PERMCONFIG') . '</a></li>';

        $menu .= '</ul>';

        $menu .= '</li>';
        // Centrora Security Settings  Menu

        $menu .= '<li ';
        $menu .= (in_array($view, array('ose_fw_adminemails', 'ose_fw_audit', 'ose_fw_permconfig', 'ose_fw_cronjobs', 'configuration'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cd"></i> ' . oLang::_get('ADMINISTRATION') . '<b class="caret"></b></a>';
        // SubMenu Anti-Virus Starts;
        $menu .= '<ul class="dropdown-menu">';
        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_adminemails') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_adminemails">' . oLang::_get('ADMINEMAILS') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_audit') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_audit">' . oLang::_get('AUDIT_WEBSITE') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_cronjobs') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_cronjobs">' . oLang::_get('CRONJOBS') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'configuration') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_configuration">' . oLang::_get('INSTALLATION') . '</a></li>';

        $menu .= '</ul>';

        $menu .= '</li>';
        // Centrora Security Settings Ends

		// About Menu

        $menu .= '<li ';
        $menu .= (in_array($view, array('login'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-scale"></i> ' . oLang::_get('MY_ACCOUNT') . '<b class="caret"></b></a>';
        // SubMenu Anti-Virus Starts;
        $menu .= '<ul class="dropdown-menu">';
        $menu .= '<li ';
        $menu .= ($view == 'login') ? 'class="active"' : '';
        $menu .= '><a href="http://www.centrora.com/store/subscription-packages/" target="_blank">' . oLang::_get('MY_PREMIUM_SERVICE') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'login') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_login">' . oLang::_get('LOGIN_OR_SUBSCIRPTION') . '</a></li>';

        $menu .= '</ul>';

        $menu .= '</li>';
		// About Ends
		
		// Main Feature Ends; 
		$menu .= '</ul></div></div></div>';
		return $menu;
	}
	public static function showmenus(){
		oseFirewall::callLibClass('oem', 'oem');
		$oem = new CentroraOEM();
		$oemCustomer = $oem->hasOEMCustomer();
        $oemShowNews = $oem->showNews();
		
    	add_menu_page( OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL, 'manage_options', 'ose_firewall', 'oseFirewall::dashboard',$oem->getFavicon());
    	add_submenu_page( 'ose_firewall', OSE_DASHBOARD_SETTING, OSE_DASHBOARD, 'manage_options', 'ose_firewall', 'oseFirewall::dashboard' );

        add_submenu_page('ose_firewall', ANTIVIRUS, ANTIVIRUS, 'manage_options', 'ose_fw_vsscan', 'oseFirewall::vsscan');
		//add_submenu_page( 'ose_firewall', CLAMAV, CLAMAV, 'manage_options', 'ose_fw_clamav', 'oseFirewall::clamav' );
        add_submenu_page('ose_fw_configuration', VSREPORT, VSREPORT, 'manage_options', 'ose_fw_scanreport', 'oseFirewall::vsreport');
		add_submenu_page( 'ose_firewall', MANAGE_IPS, MANAGE_IPS, 'manage_options', 'ose_fw_manageips', 'oseFirewall::manageips' );
		//add_submenu_page( 'ose_firewall', ADD_IPS, ADD_IPS, 'manage_options', 'ose_fw_addips', 'oseFirewall::ipform' );
        add_submenu_page('ose_fw_configuration', AUDIT_WEBSITE, AUDIT_WEBSITE, 'manage_options', 'ose_fw_audit', 'oseFirewall::audit');
        add_submenu_page('ose_fw_configuration', FIREWALL_RULES, FIREWALL_RULES, 'manage_options', 'ose_fw_rulesets', 'oseFirewall::rulesets');
        add_submenu_page('ose_fw_configuration', FIREWALL_CONFIGURATION, FIREWALL_CONFIGURATION, 'manage_options', 'ose_fw_bsconfig', 'oseFirewall::bsconfig');
        add_submenu_page('ose_fw_configuration', VARIABLES, VARIABLES, 'manage_options', 'ose_fw_variables', 'oseFirewall::variables');
        add_submenu_page('ose_fw_configuration', INSTALLATION, INSTALLATION, 'manage_options', 'ose_fw_configuration', 'oseFirewall::configuration');
        add_submenu_page('ose_firewall', BACKUP, BACKUP, 'manage_options', 'ose_fw_backup', 'oseFirewall::backup');

        add_submenu_page('ose_fw_configuration', AUTHENTICATION, AUTHENTICATION, 'manage_options', 'ose_fw_authentication', 'oseFirewall::authentication');
        add_submenu_page('ose_fw_configuration', ADVANCEDBACKUP, ADVANCEDBACKUP, 'manage_options', 'ose_fw_advancedbackup', 'oseFirewall::advancedbackup');
        add_submenu_page('ose_firewall', PERMCONFIG, PERMCONFIG, 'manage_options', 'ose_fw_permconfig', 'oseFirewall::permconfig');
        add_submenu_page('ose_fw_configuration', ADMINEMAILS, ADMINEMAILS, 'manage_options', 'ose_fw_adminemails', 'oseFirewall::adminemails');

        add_submenu_page('ose_fw_configuration', COUNTRYBLOCK, COUNTRYBLOCK, 'manage_options', 'ose_fw_countryblock', 'oseFirewall::countryblock');
		add_submenu_page( 'ose_firewall', CRONJOBS, CRONJOBS, 'manage_options', 'ose_fw_cronjobs', 'oseFirewall::cronjobs' );
        add_submenu_page('ose_firewall', LOGIN_OR_SUBSCIRPTION, LOGIN_OR_SUBSCIRPTION, 'manage_options', 'ose_fw_login', 'oseFirewall::login');
        add_submenu_page('ose_fw_configuration', SUBSCRIPTION, SUBSCRIPTION, 'manage_options', 'ose_fw_subscription', 'oseFirewall::subscription');
		//add_submenu_page( 'ose_firewall', VERSION_UPDATE, VERSION_UPDATE, 'manage_options', 'ose_fw_versionupdate', 'oseFirewall::versionupdate' );
        add_submenu_page('ose_fw_configuration', FILEEXTENSION, FILEEXTENSION, 'manage_options', 'ose_fw_fileextension', 'oseFirewall::fileextension');
        add_submenu_page('ose_fw_configuration', AUTHENTICATION, AUTHENTICATION, 'manage_options', 'ose_fw_authentication', 'oseFirewall::authentication');
        add_submenu_page('ose_fw_configuration', SEO_CONFIGURATION, SEO_CONFIGURATION, 'manage_options', 'ose_fw_seoconfig', 'oseFirewall::seoconfig');
		add_submenu_page( 'ose_fw_configuration', SCAN_CONFIGURATION, SCAN_CONFIGURATION, 'manage_options', 'ose_fw_scanconfig', 'oseFirewall::scanconfig' );
		add_submenu_page( 'ose_fw_configuration', ANTIVIRUS_CONFIGURATION, ANTIVIRUS_CONFIGURATION, 'manage_options', 'ose_fw_avconfig', 'oseFirewall::avconfig' );
		add_submenu_page( 'ose_fw_configuration', ANTISPAM_CONFIGURATION, ANTISPAM_CONFIGURATION, 'manage_options', 'ose_fw_spamconfig', 'oseFirewall::spamconfig' );
		add_submenu_page( 'ose_fw_configuration', EMAIL_CONFIGURATION, EMAIL_CONFIGURATION, 'manage_options', 'ose_fw_emailconfig', 'oseFirewall::emailconfig' );
		add_submenu_page( 'ose_fw_configuration', EMAIL_ADMIN, EMAIL_ADMIN, 'manage_options', 'ose_fw_emailadmin', 'oseFirewall::emailadmin' );
		add_submenu_page( 'ose_fw_configuration', API_CONFIGURATION, API_CONFIGURATION, 'manage_options', 'ose_fw_apiconfig', 'oseFirewall::apiconfig' );
        if ($oemShowNews) {
            add_submenu_page( 'ose_fw_configuration', NEWS_TITLE, NEWS_TITLE, 'manage_options', 'ose_fw_news', 'oseFirewall::news' );
        }
        add_submenu_page( 'ose_fw_configuration', FILE_UPLOAD_MANAGEMENT, FILE_UPLOAD_MANAGEMENT, 'manage_options', 'ose_fw_upload', 'oseFirewall::upload' );
		//add_submenu_page( 'ose_firewall', ANTI_VIRUS_DATABASE_UPDATE, ANTI_VIRUS_DATABASE_UPDATE, 'manage_options', 'ose_fw_versionupdate', 'oseFirewall::updateChecking' );
        if ($oemCustomer) {
            add_submenu_page('ose_fw_configuration', OEM_PASSCODE, OEM_PASSCODE, 'manage_options', 'ose_fw_passcode', 'oseFirewall::passcode');
        }
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
		$oem = new CentroraOEM() ;
		$head = '<nav class="navbar navbar-default" role="navigation">';
		$head .= '<div class ="everythingOnOneLine">
					<div class ="col-lg-12">';
		$oem = new CentroraOEM();
		$oemCustomer = $oem->hasOEMCustomer();
        $oemShowNews = $oem->showNews();
		if ($oemCustomer) {
			$head .= $oem->addLogo();
		}
		else 
		{
			$head .= '<div class="logo"><img src="'.OSE_FWPUBLICURL.'images/logo5.png" width="250px" alt ="Centrora Logo"/></div>'.$oem->showOEMName ();
		}
		#Get update server version
		$plugins = get_plugin_updates();
		foreach ( (array) $plugins as $plugin_file => $plugin_data) {
			if ($plugin_data->update->slug  == "ose-firewall"){
				$serverversion = $plugin_data->update->new_version;}
		}
		$isOutdated = (self::getVersionCompare($serverversion) > 0)?true:false;
		$head .='<div id ="versions"> <div class ="'.(($isOutdated==true)?'version-outdated':'version-updated').'"><i class="glyphicon glyphicon-'.(($isOutdated==true)?'remove':'ok').'"></i>  '.self::getVersion ().'</div>';
		$urls = $oemShowNews? self::getDashboardURLs() :null ;
		oseFirewall::loadJSFile ('CentroraUpdateApp', 'VersionAutoUpdate.js', false);
		self::getAjaxScript();
		
		#pass update url to js to run through ajax. Update handled by url function.
		$file ='ose-firewall/ose_wordpress_firewall.php';
		$updateurl = wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file);
		$activateurl = esc_url(wp_nonce_url(admin_url('plugins.php?action=activate&plugin=' . $file), 'activate-plugin_' . $file));

		if ($isOutdated) {
			$head .= '<button class="version-update" type="button"
						onclick="showAutoUpdateDialogue(\''.$serverversion.'\', \''.$urls[8].'\',
														\''.$updateurl.'\',
														\''.$file.'\',
														\''.$activateurl.'\')"/>
						<i class="glyphicon glyphicon-refresh"></i> Update to : '.$serverversion.'</button>';
		}
		$head .= '</div>';

        if ($oemShowNews) {
            $hasNews = self::checkNewsUpdated();
            $head .='<div class="centrora-news"><i class="glyphicon glyphicon-bullhorn"></i> <a class="color-white" href="'.$urls[8].'">What\'s New? </a><i class="glyphicon glyphicon-'.(($hasNews==true)?'asterisk':'').' color-magenta"></i></div>';
        }

		if (oseFirewall::affiliateAccountExists()==false && CentroraOEM::hasOEMCustomer()==false)
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
		
		$head .= '<li><a href="index.php" title="Home"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs hidden-sm hidden-md">Home</span> </a></li>';
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
        $url[] = 'admin.php?page=ose_fw_rulesets';
		$url[] = 'admin.php?page=ose_fw_bsconfig';
		$url[] = 'admin.php?page=ose_fw_news';
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
	public static function getConfigurationURL () {
		return 'admin.php?page=ose_fw_bsconfig';
	}

    public static function validatePassword($errors, $update, $userData)
    {
        $password = (isset($_POST['pass1']) && trim($_POST['pass1'])) ? $_POST['pass1'] : false;
        $user_id = isset($userData->ID) ? $userData->ID : false;
        $username = isset($_POST["user_login"]) ? $_POST["user_login"] : $userData->user_login;
        if ($password == false) {
            return $errors;
        }
        if ($errors->get_error_data("pass")) {
            return $errors;
        }
        $user_info = get_userdata($user_id);
        $enforce = implode(', ', $user_info->roles);
        if ($enforce == 'administrator') {
            if (!oseFirewall::isStrongPasswd($password, $username)) {
                $errors->add('pass', "Please choose a stronger password. Use a mix of letters, numbers, and symbols in your password.");
                return $errors;
            }
        }
        return $errors;
    }

    public static function isStrongPasswd($passwd, $username)
    {
        $strength = 0;
        if (strlen(trim($passwd)) < 5)
            return false;
        if (strtolower($passwd) == strtolower($username))
            return false;
        if (preg_match('/(?:password|passwd|mypass|wordpress)/i', $passwd)) {
            return false;
        }
        if ($num = preg_match_all("/\d/", $passwd, $matches)) {
            $strength += ((int)$num * 10);
        }
        if (preg_match("/[a-z]/", $passwd))
            $strength += 26;
        if (preg_match("/[A-Z]/", $passwd))
            $strength += 26;
        if ($num = preg_match_all("/[^a-zA-Z0-9]/", $passwd, $matches)) {
            $strength += (31 * (int)$num);

        }
        if ($strength > 60) {
            return true;
        }
    }

    public function plugins_loaded()
    {

        global $pagenow;

        if (!is_multisite()
            && (strpos($_SERVER['REQUEST_URI'], 'wp-signup') !== false
                || strpos($_SERVER['REQUEST_URI'], 'wp-activate')) !== false
        ) {

            wp_die(__('This feature is not enabled.', 'wps-hide-login'));

        }

        $request = parse_url($_SERVER['REQUEST_URI']);

        if ((strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false
                || untrailingslashit($request['path']) === site_url('wp-login', 'relative'))
            && !is_admin()
        ) {

            $this->wp_login_php = true;

            $_SERVER['REQUEST_URI'] = $this->user_trailingslashit('/' . str_repeat('-/', 10));

            $pagenow = 'index.php';

        } elseif (untrailingslashit($request['path']) === home_url($this->new_login_slug(), 'relative')
            || (!get_option('permalink_structure')
                && isset($_GET[$this->new_login_slug()])
                && empty($_GET[$this->new_login_slug()]))
        ) {

            $pagenow = 'wp-login.php';

        }

    }

    public function wp_loaded()
    {

        global $pagenow;

        if (is_admin()
            && !is_user_logged_in()
            && !defined('DOING_AJAX')
        ) {

            status_header(404);
            nocache_headers();
            include(get_404_template());
            exit;
        }

        $request = parse_url($_SERVER['REQUEST_URI']);

        if ($pagenow === 'wp-login.php'
            && $request['path'] !== $this->user_trailingslashit($request['path'])
            && get_option('permalink_structure')
        ) {

            wp_safe_redirect($this->user_trailingslashit($this->new_login_url())
                . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));

            die;

        } elseif ($this->wp_login_php) {

            if (($referer = wp_get_referer())
                && strpos($referer, 'wp-activate.php') !== false
                && ($referer = parse_url($referer))
                && !empty($referer['query'])
            ) {

                parse_str($referer['query'], $referer);

                if (!empty($referer['key'])
                    && ($result = wpmu_activate_signup($referer['key']))
                    && is_wp_error($result)
                    && ($result->get_error_code() === 'already_active'
                        || $result->get_error_code() === 'blog_taken')
                ) {

                    wp_safe_redirect($this->new_login_url()
                        . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));

                    die;

                }

            }

            $this->wp_template_loader();

        } elseif ($pagenow === 'wp-login.php') {

            global $error, $interim_login, $action, $user_login;

            @require_once ABSPATH . 'wp-login.php';

            die;

        }

    }

    private function wp_template_loader()
    {

        global $pagenow;

        $pagenow = 'index.php';

        if (!defined('WP_USE_THEMES')) {

            define('WP_USE_THEMES', true);

        }

        wp();

        if ($_SERVER['REQUEST_URI'] === $this->user_trailingslashit(str_repeat('-/', 10))) {

            $_SERVER['REQUEST_URI'] = $this->user_trailingslashit('/wp-login-php/');

        }

        require_once(ABSPATH . WPINC . '/template-loader.php');

        die;

    }

    public function site_url($url, $path, $scheme, $blog_id)
    {

        return $this->filter_wp_login_php($url, $scheme);

    }

    public function network_site_url($url, $path, $scheme)
    {

        return $this->filter_wp_login_php($url, $scheme);

    }

    public function wp_redirect($location, $status)
    {

        return $this->filter_wp_login_php($location);

    }

    public function filter_wp_login_php($url, $scheme = null)
    {

        if (strpos($url, 'wp-login.php') !== false) {

            if (is_ssl()) {

                $scheme = 'https';

            }

            $args = explode('?', $url);

            if (isset($args[1])) {

                parse_str($args[1], $args);

                $url = add_query_arg($args, $this->new_login_url($scheme));

            } else {

                $url = $this->new_login_url($scheme);

            }

        }

        return $url;

    }

    private function use_trailing_slashes()
    {

        return ('/' === substr(get_option('permalink_structure'), -1, 1));

    }

    private function new_login_slug()
    {
        $confArray = $this->getConfiguration('scan');
        if (!empty($confArray['data']['loginSlug'])) {
            return $confArray['data']['loginSlug'];
        }
        return;
    }

    private function user_trailingslashit($string)
    {

        return $this->use_trailing_slashes()
            ? trailingslashit($string)
            : untrailingslashit($string);

    }

    public function new_login_url($scheme = null)
    {

        if (get_option('permalink_structure')) {

            return $this->user_trailingslashit(home_url('/', $scheme) . $this->new_login_slug());

        } else {

            return home_url('/', $scheme) . '?' . $this->new_login_slug();

        }

    }
//    public function admin_notices() {
//
//        global $pagenow;
//
//        $out = '';
//
//        if ( ! is_network_admin() && $_GET['page'] == 'ose_fw_bsconfig') {
//
//            echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( 'Your login page is now here: <strong><a href="%1$s">%2$s</a></strong>. Bookmark this page!', 'wps-hide-login' ), $this->new_login_url(), $this->new_login_url() ) . '</p></div>';
//
//        }
//
//    }
}