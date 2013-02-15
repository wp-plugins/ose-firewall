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
function ose_wp_firewallsettings_link($links, $file) {
	static $this_plugin;
	if (empty($this_plugin))
	{
		$this_plugin = plugin_basename(__FILE__);
	}
	if (strstr($this_plugin, 'ose-firewall'))
	{	
		$links[] = '<a href="' . admin_url('options-general.php?page=ose_wp_firewall') . '">' . __('Settings', 'ose_wp_firewall') . '</a>';
	}
	return $links;
}
function ose_wp_firewallinit() {
	register_setting('ose_wp_firewall_settings_group', 'ose_wp_firewall_settings', 'ose_wp_firewallvalidation');
	register_setting('ose_wp_firewall_avsetting_group', 'ose_wp_firewall_avsetting', 'ose_wp_firewallvalidation');
}
function ose_wp_firewallvalidation($input) {
	$input['osefirewall_email'] = wp_filter_nohtml_kses($input['osefirewall_email']);
	return $input;
}
function ose_wp_firewallfilter($content) {
	$settings = (array) get_option('ose_wp_firewall_settings');
	switch ($settings['osefirewall_mode']) {
		case 0:
			// do nothing;
			break;
		case 1: 
		default:
			ose_wp_scanwtfirewall($settings);
			break;
		case 2:
			ose_wp_scanwtsuite($settings['osefirewall_suitepath']);
			break;
		case 3:
			ose_wp_scanwtsuite($settings['osefirewall_suitepath']);
			ose_wp_scanwtfirewall($settings);
			break;
	}
}
function ose_wp_scanwtsuite($path)
{
	if (file_exists($path))
	{	
		require_once($path . DS . 'administrator'.DS.'scan.php');
	}
}
function ose_wp_scanwtfirewall($settings)
{
	$osewphelper = new OSEWPhelper();
	require_once(OSEFWLIBRARY . DS . 'scan.php');
	$osefirewall = new oseWPFirewall($settings, $osewphelper->admin_email, $osewphelper->blog_name);
	$osefirewall->scan();
}
function ose_wp_firewallinstall() {
	ose_wp_installSQL(); 
}
function ose_wp_installSQL()
{
	global $wpdb;
	$query = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."osefw_files` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`filename` text NOT NULL,
				`type` varchar(20) NOT NULL,
				`checked` tinyint(1) NOT NULL DEFAULT '0',
				`patterns` text,
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 ;";
	$return = $wpdb->query($query);
	
	$query = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."osefw_status` (
				  `ctime` double(17,6) unsigned NOT NULL,
				  `level` tinyint(3) unsigned NOT NULL,
				  `type` char(5) NOT NULL,
				  `msg` varchar(1000) NOT NULL,
				  KEY `k1` (`ctime`),
				  KEY `k2` (`type`)
				) ;
	 ;";
	$return = $wpdb->query($query);
	return $return; 
}
function ose_wp_firewallplugin_menu() {
	add_options_page(OSE_WORDPRESS_FIREWALL, OSE_WORDPRESS_FIREWALL, 'manage_options', 'ose_wp_firewall', 'ose_wp_firewall_main');
	add_options_page(OSE_WORDPRESS_FIREWALL_CONFIG, OSE_WORDPRESS_FIREWALL_CONFIG, 'manage_options', 'ose_wp_firewall_conf', 'ose_wp_firewall_settings');
	add_options_page(OSE_VIRUS_SCAN, OSE_VIRUS_SCAN, 'manage_options', 'ose_wp_firewall_avscan', 'ose_wp_firewall_avscan');
	add_options_page(OSE_WORDPRESS_VIRUSSCAN_CONFIG, OSE_WORDPRESS_VIRUSSCAN_CONFIG, 'manage_options', 'ose_wp_firewall_avconf', 'ose_wp_firewall_avconf');
	add_menu_page(OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL, 'manage_options', 'options-general.php?page=ose_wp_firewall', '', OSEFWURL.'/assets/favicon.ico');
	add_submenu_page('options-general.php?page=ose_wp_firewall', OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL_CONFIG, 'manage_options', 'options-general.php?page=ose_wp_firewall_conf' );
	add_submenu_page('options-general.php?page=ose_wp_firewall', OSE_VIRUS_SCAN, OSE_VIRUS_SCAN, 'manage_options', 'options-general.php?page=ose_wp_firewall_avscan' );
	add_submenu_page('options-general.php?page=ose_wp_firewall', OSE_WORDPRESS_VIRUSSCAN_CONFIG, OSE_WORDPRESS_VIRUSSCAN_CONFIG, 'manage_options', 'options-general.php?page=ose_wp_firewall_avconf' );
	
}

function ose_wp_firewall_main() {
	$osewphelper = new OSEWPhelper();
	$osewphelper->addAssets('css');
	include_once OSEFWTEMPLATES . DS . 'main' . DS . 'default.php';
}

function ose_wp_firewall_settings() {
	$osewphelper = new OSEWPhelper();
	$osewphelper->addAssets('css');
	include_once OSEFWTEMPLATES . DS . 'settings' . DS . 'default.php';
}
function ose_wp_firewall_avconf(){
	$osewphelper = new OSEWPhelper();
	$osewphelper->addAssets('css');
	include_once OSEFWTEMPLATES . DS . 'antivirus' . DS . 'default.php';
}
function ose_wp_firewall_avscan(){
	$osewphelper = new OSEWPhelper();
	$osewphelper->addAssets('css');
	$osewphelper->addAssets('js');
	$osewphelper->setupJSAdminVars($debug=0);
	include_once OSEFWTEMPLATES . DS . 'vsscan' . DS . 'default.php';
}
function ose_wp_firewallload_languages() {
	load_plugin_textdomain('ose_wordpress_firwall', false, OSEFWLANGUAGE);
}