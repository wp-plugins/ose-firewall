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
require_once(ABSPATH . 'wp-includes/pluggable.php');
/*
 * Attach plugin to MainWP as an extension and render settings and site pages.
 */
class CentroraMainWP {
    protected $childFile ='';
    protected $childKey  = false;
    protected $mainWPActivated = true;
    protected $childEnabled = true;
    protected $plugin_handle = "mainwp-oseFirewall";
    
    public function __construct($childfilepassed){
        $this->childFile = $childfilepassed;
        
        self::hide_wpadmin_panels(); // hide admin panels for child sites rendered in mainwp
        
        add_filter('mainwp-getextensions', array($this,'get_ose_Firewall_extension'));
        
        $this->mainWPActivated = apply_filters('mainwp-activated-check', false);
        
        if ($this->mainWPActivated !== false) {
            self::activate_ose_Firewall_plugin();
        } else {
            add_action('mainwp-activated', array($this,'activate_ose_Firewall_plugin'));
        }       
    }
    
    public function activate_ose_Firewall_plugin() {
    	
    	$this->mainWPActivated = apply_filters('mainwp-activated-check', $this->mainWPActivated);
    	
        $this->childEnabled = apply_filters('mainwp-extension-enabled-check', $this->childFile);
        if (!$this->childEnabled) return;
        
        $this->childKey = $this->childEnabled['key'];
        
        if (function_exists("mainwp_current_user_can")&& !mainwp_current_user_can("extension", "mainwp-oseFirewall"))
        	return;
        if ($this->childEnabled) {
        	 
        	self::load_subpages(); //load subpages
        	 
        	
        }
        
    }
    public function get_ose_Firewall_extension($extensions) {
        
    	$extensions[] = array(	'plugin' => $this->childFile, 
    							'api' => $this->plugin_handle, 
    							'mainwp' => false, //disable mainwp 'enable button' check based on activation. @todo change this to true when uploading to the MainWP extensions store.
    							'callback' => array(&$this, 'ose_Firewall_extension_settings'), 
    							'apiManager' => false); //disable api key check for testing. @todo change this to true when uploading to the MainWP extensions store. 
        
    	return $extensions;
    }    
    public function load_subpages() {
        // only show Firewall Configuration on individual site pages
        $siteID = 0;
        if (isset($_GET['id']) && !empty($_GET['id']))
            $siteID = $_GET['id'];
        else if (isset($_GET['backupid']) && !empty($_GET['backupid']))
            $siteID = $_GET['backupid'];
        else if (isset($_GET['dashboard']) && !empty($_GET['dashboard']))
            $siteID = $_GET['dashboard'];
        else if (isset($_GET['scanid']) && !empty($_GET['scanid']))
            $siteID = $_GET['scanid'];
        
        if ($siteID != 0){
			//check if ose-firewall is installed and active on the child site before loading subpages
	        $oseFirewallChildWebsite = MainWPDB::Instance()->getWebsiteById($siteID);
			$allPlugins = json_decode($oseFirewallChildWebsite->plugins, true);
			for ($i = 0; $i < count($allPlugins); $i++) {
                $plugin = $allPlugins[$i];
                if ($plugin['active'] != 0) {
                	if (stristr($plugin['slug'], 'ose-firewall/ose_wordpress_firewall.php')) {		        				            
		        		add_filter( 'mainwp-getsubpages-sites', array($this,'add_ose_Firewall_config_page'));
			            add_filter( 'mainwp-getsubpages-sites', array($this,'add_ose_Firewall_finetune_page'));
			            add_filter( 'mainwp-getsubpages-sites', array($this,'add_ose_Firewall_manageIP_page'));
                	}
                }
            }
	    }
    }
    /*
     * Hide admin panels if using Centrora called from MainWP
     */
    public function hide_wpadmin_panels () {
    	
    	if (isset($_GET['ControllPanel']) && !empty($_GET['ControllPanel']) && $_GET['ControllPanel'] = 'MainWP'){
    		if(stripos($this->childFile, 'ose-firewall')>0) {
                echo <<<HTML
				<style type="text/css">
					#adminmenu, #adminmenu .wp-submenu, #adminmenuback, #wpadminbar, #adminmenuwrap {
						width: 0px;
						display: none;
					}
					#wpcontent {
						margin-left: 0px !important;
					}
					#wpfooter, .bs-component, .navbar, .navbar-default,.update-nag {
						display:none !important;
					}
					.wp-toolbar{
						padding-top: 0px !important;
					}
				</style>
HTML;
            }
        }
    }
   /*
    * Call the General 'all sites' settings page -> Shown under Extension settings.
    */
   public function ose_Firewall_extension_settings() {
   		do_action('mainwp-pageheader-extensions', $this->childFile);
       
		if ($this->childEnabled) {
			echo '<h1>Centrora Security</h1>
				<blockquote>
				<p>Please manage the local instance of Centrora Security from the WordPress plugin <a href="'. admin_url('admin.php?page=ose_firewall') .'">settings</a> page.</p>
				<p>To mange Centrora for a specific site select the site under <a href="'.admin_url('admin.php?page=managesites').'">Sites</a></p>
				<blockquote>';
       } else {
			echo '<div class="mainwp_info-box-yellow"><strong>The Extension has to be <a href="'.admin_url('admin.php?page=Extensions').'">enabled</a> to change the settings.</strong></div>';
       }   
       do_action('mainwp-pagefooter-extensions', $this->childFile);
   }   
   /*
    * Functions used to add & render the Centrora mainWP subpages for the specific site selected.
    */
   public function add_ose_Firewall_config_page( $subpages ) {   
       $subpages[] = array('slug' => 'OseFirewallConfigSettings',
               'title' => 'Centrora: '.FIREWALL_CONFIGURATION,
               'plugin' => $this->childFile,
               'key' => $this->childKey,
               'callback' => array($this,'render_ose_Firewall_config_settings'));
       return $subpages;
   }
   public function add_ose_Firewall_finetune_page( $subpages ) {   
       $subpages[] = array('slug' => 'OseFirewallFineTuneSettings',
               'title' => 'Centrora: '.FIREWALL_RULES,
               'plugin' => $this->childFile,
               'key' => $this->childKey,
               'callback' => array($this,'render_ose_Firewall_finetune_settings'));
       return $subpages;
   }
   public function add_ose_Firewall_manageIP_page( $subpages ) {   
       $subpages[] = array('slug' => 'OseFirewallmanageIPSettings',
               'title' => 'Centrora: '.MANAGE_IPS,
               'plugin' => $this->childFile,
               'key' => $this->childKey,
               'callback' => array($this,'render_ose_Firewall_manageIP_settings'));
       return $subpages;
   }
   public function render_ose_Firewall_config_settings() {
       do_action('mainwp-pageheader-sites', 'OseFirewallConfigSettings');
   
       $siteID = $_GET["id"];
       $this->childKey = apply_filters('mainwp-extension-enabled-check', $this->childFile)['key'];
   
       //Make a secure connection to the child site and load the page ose_fw_bsconfig
       $oseFirewallChildWebsite = MainWPDB::Instance()->getWebsiteById($siteID);
       ?> <iframe width="100%" height="1000"
               src="<?php echo MainWPUtility::getGetDataAuthed($oseFirewallChildWebsite, 'admin.php?ControllPanel=MainWP&page=ose_fw_bsconfig'); ?>"></iframe>
        <?php
           
       do_action('mainwp-pagefooter-sites', 'OseFirewallConfigSettings');
   }
   public function render_ose_Firewall_finetune_settings() {
       do_action('mainwp-pageheader-sites', 'OseFirewallfinetuneSettings');
   
       $siteID = $_GET["id"];
       $this->childKey = apply_filters('mainwp-extension-enabled-check', $this->childFile)['key'];
   
       //Make a secure connection to the child site and load the page ose_fw_rulesets
       $oseFirewallChildWebsite = MainWPDB::Instance()->getWebsiteById($siteID);
       ?> <iframe width="100%" height="1000"
               src="<?php echo MainWPUtility::getGetDataAuthed($oseFirewallChildWebsite, 'admin.php?ControllPanel=MainWP&page=ose_fw_rulesets'); ?>"></iframe>
        <?php
   
       do_action('mainwp-pagefooter-sites', 'OseFirewallfinetuneSettings');
   }
   public function render_ose_Firewall_manageIP_settings() {
       do_action('mainwp-pageheader-sites', 'OseFirewallmanageIPSettings');
   
       $siteID = $_GET["id"];
       $this->childKey = apply_filters('mainwp-extension-enabled-check', $this->childFile)['key'];
   
       //Make a secure connection to the child site and load the ose_fw_manageips page
       $oseFirewallChildWebsite = MainWPDB::Instance()->getWebsiteById($siteID);
       ?> <iframe width="100%" height="1000"
               src="<?php echo MainWPUtility::getGetDataAuthed($oseFirewallChildWebsite, 'admin.php?ControllPanel=MainWP&page=ose_fw_manageips'); ?>"></iframe>
        <?php
   
       do_action('mainwp-pagefooter-sites', 'OseFirewallmanageIPSettings');
   }
}

