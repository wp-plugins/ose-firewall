<?php
/**
* @version     1.0 +
* @package       Open Source Excellence Security Suite
* @subpackage    Open Source Excellence WordPress Firewall
* @author        Open Source Excellence {@link http://www.opensource-excellence.com}
* @author        Created on 01-Jul-2012
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
defined('OSEFWDIR') or die;
Class OSEWPhelper {
	var $blog_wpurl = '';
	var $blog_name = '';
	var $admin_email = '';
	var $osefwurl = '';
	
	public function __construct() {
		$this->blog_wpurl = get_bloginfo('wpurl');
		$this->blog_name = get_bloginfo('name');
		$this->admin_email = get_option('admin_email');
		$this->osefwurl = $this->blog_wpurl.'/wp-content/plugins/ose-firewall/';
	}
	public function loadLanguage() {
		$curlang = get_bloginfo('language');
		$langfile = OSEFWLANGUAGE . DS . $curlang . '.php';
		if (file_exists($filename)) {
			require_once($langfile);
		} else {
			require_once(OSEFWLANGUAGE . DS . 'en-GB.php');
		}
	}
	public function loadadminmenu() {
		include_once OSEFWTEMPLATES . DS . 'menus' . DS . 'admin-menu.php';
	}
	public function addwpactions() {
		add_action('posts_selection', 'ose_wp_firewallfilter');
		add_action('admin_init', 'ose_wp_firewallinit');
		add_filter('plugin_action_links', 'ose_wp_firewallsettings_link', 10, 2);
		add_action('admin_menu', 'ose_wp_firewallplugin_menu');
		add_action('init', 'ose_wp_firewallload_languages');
	}
	public function setupJSAdminVars($debug=0){
		wp_localize_script('ose_wp_firewalljs', 'osefirewallAdminVars', array(
		'ajaxURL' => admin_url('admin-ajax.php'),
		'firstNonce' => wp_create_nonce('wp-ajax'),
		'siteBaseURL' => $this->blog_wpurl,
		'debugOn' => $debug
		));
	}
	public function addAssets($type=null)
	{
		if ($type=='css')
		{	
			wp_enqueue_style('wp-pointer');
			wp_enqueue_style('ose-scan-style', $this->osefwurl .'assets/css/scan.css', '');
			wp_enqueue_style('ose-main-style', $this->osefwurl .'assets/css/main.css', '');
		}
		elseif ($type=='js')
		{
			wp_enqueue_script('wp-pointer');
			wp_enqueue_script('json2');
			wp_enqueue_script('jquery.tmpl', $this->osefwurl .'assets/js/jquery.tmpl.min.js', array('jquery'));
			wp_enqueue_script('jquery.colorbox', $this->osefwurl .'assets/js/jquery.colorbox-min.js', array('jquery'));
			wp_enqueue_script('jquery.dataTables', $this->osefwurl .'assets/js/jquery.dataTables.min.js', array('jquery'));
			wp_enqueue_script('ose_wp_firewalljs', $this->osefwurl .'assets/js/admin.js', array('jquery'));
		}
	}
}
?>