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
if(!function_exists('wp_get_current_user')) {
	include(ABSPATH . "wp-includes/pluggable.php");
}
class osewpUtils {
	public static function getWPVersion(){
		if(wordfence::$wordfence_wp_version){
			return wordfence::$wordfence_wp_version;
		} else {
			global $wp_version;
			return $wp_version;
		}
	}
	public static function isAdminPageMU(){
		if(preg_match('/^[\/a-zA-Z0-9\-\_\s\+\~\!\^\.]*\/wp-admin\/network\//', $_SERVER['REQUEST_URI'])){ 
			return true; 
		}
		return false;
	}
	public static function getSiteBaseURL(){
		return rtrim(site_url(), '/') . '/';
	}
	public static function longestLine($data){
		$lines = preg_split('/[\r\n]+/', $data);
		$max = 0;
		foreach($lines as $line){
			$len = strlen($line);
			if($len > $max){
				$max = $len;
			}
		}
		return $max;
	}
	public static function longestNospace($data){
		$lines = preg_split('/[\r\n\s\t]+/', $data);
		$max = 0;
		foreach($lines as $line){
			$len = strlen($line);
			if($len > $max){
				$max = $len;
			}
		}
		return $max;
	}
	public static function requestMaxMemory(){
		if(wfConfig::get('maxMem', false) && (int) wfConfig::get('maxMem') > 0){
			$maxMem = (int) wfConfig::get('maxMem');
		} else {
			$maxMem = 256;
		}
		if( function_exists('memory_get_usage') && ( (int) @ini_get('memory_limit') < $maxMem ) ){
			self::iniSet('memory_limit', $maxMem . 'M');
		}
	}
	public static function isAdmin(){
		if(is_multisite()){
			if(current_user_can('manage_network')){
				return true;
			}
		} else {
			if(current_user_can('manage_options')){
				return true;
			}
		}
		return false;
	}
	public static function isWindows(){
		if(! self::$isWindows){
			if(preg_match('/^win/i', PHP_OS)){
				self::$isWindows = 'yes';
			} else {
				self::$isWindows = 'no';
			}
		}
		return self::$isWindows == 'yes' ? true : false;
	}
	public static function funcEnabled($func){
		if(! function_exists($func)){ return false; }
		$disabled = explode(',', ini_get('disable_functions'));
		foreach($disabled as $f){
			if($func == $f){ return false; }
		}
		return true;
	}
	public static function iniSet($key, $val){
		if(self::funcEnabled('ini_set')){
			@ini_set($key, $val);
		}
	}
	public static function ajax_scan_callback()
	{
		self::status(4, 'info', "Ajax request received to start scan.");
		$osewpScanEngine = new osewpScanEngine();
		$err = $osewpScanEngine->startScan();
		if($err){
			return array('errorMsg' => $err);
		} else {
			return array("ok" => 1);
		}
	}	
	public static function ajax_showtotal_callback()
	{
		self::status(4, 'info', "Ajax request received to show total files.");
		$osewpScanEngine = new osewpScanEngine();
		$err = $osewpScanEngine->totalFiles();
		if($err){
			return array('errorMsg' => $err);
		} else {
			return array("ok" => 1);
		}
	}
	public static function ajax_showinfected_callback()
	{
		self::status(4, 'info', "Ajax request received to show infected files.");
		$osewpScanEngine = new osewpScanEngine();
		$err = $osewpScanEngine->totalinfectedFiles();
		if($err){
			return array('errorMsg' => $err);
		} else {
			return array("ok" => 1);
		}
	}
	public static function ajax_scanvs_callback()
	{
		self::status(4, 'info', "Ajax request received to start scan.");
		$osewpScanEngine = new osewpScanEngine();
		$err = $osewpScanEngine->startVSScan();
		if($err){
			return array('errorMsg' => $err);
		} else {
			return array("ok" => 1);
		}
	}
	public static function addStatus($level, $type, $msg) {
		global $wpdb;
		$wpdb->insert($wpdb->prefix.'osefw_status', 
				array(
						'ctime' => sprintf('%.6f', microtime(true)), 
						'level' => $level,
						'type' => $type,
						'msg' => $msg
						), 
				array ('%s', '%d', '%s', '%s'));
		return $wpdb->insert_id;
	}
	public static function status($level /* 1 has highest visibility */, $type /* info|error */, $msg){
		if($level > 3 && $level < 10 && (! self::isDebugOn())){ //level 10 and higher is for summary messages
			return false;
		}
		if($type != 'info' && $type != 'error'){ error_log("Invalid status type: $type"); return; }
		if(self::$printStatus){
			echo "STATUS: $level : $type : $msg\n";
		} else {
			self::addStatus($level, $type, $msg);
		}
	}
	public static function isDebugOn()
	{
		return false;
	}
	public static function ajaxReceiver(){
		if(! self::isAdmin()){
			die(json_encode(array('errorMsg' => "You appear to have logged out or you are not an admin. Please sign-out and sign-in again.")));
		}
		$func = $_POST['action'];
		$nonce = $_POST['nonce'];
		
		if(! wp_verify_nonce($nonce, 'wp-ajax')){
			die(json_encode(array('errorMsg' => "Your browser sent an invalid security token to OSE Firewall. Please try reloading this page or signing out and in again.")));
		}
		$func = str_replace('osefirewall_', '', $func);
		$returnArr = call_user_func('osewpUtils::ajax_' . $func . '_callback');
		if($returnArr === false){
			$returnArr = array('errorMsg' => "OSE Firewall encountered an internal error executing that request.");
		}
			
		if(! is_array($returnArr)){
			error_log("Function $func did not return an array and did not generate an error.");
			$returnArr = array();
		}
		if(isset($returnARr['nonce'])){
			error_log("OSE Firewall ajax function return an array with 'nonce' already set. This could be a bug.");
		}
		$returnArr['nonce'] = wp_create_nonce('wp-ajax');
		die(json_encode($returnArr));
	}
	public static function jsonReturn($return)
	{
		print_r(oseJSON::encode($return)); exit;
	}
}


?>
