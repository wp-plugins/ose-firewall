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
class RemoteLogin
{
	public function login()
	{
		oseFirewall::loadLanguage();
		require_once(OSEFWDIR.ODS.'framework'.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
		$this->checkEncryptFunction () ;
		$privateKey = null;
		$privateKey = $this->getPrivateKeyFromDB();
		$info = $this->decryptInfo($privateKey);
		if (!$info)
		{
			$return = $this->loginFailedInfo();
			oseAjax::returnJSON($return);
		}
		$state = $this->loginWithInfo($info);
		$remoteLogin = oRequest::getInt('remoteLogin', 0);
		if ($remoteLogin > 0)
		{
			if ($remoteLogin == 1)
			{
				$return = array();
				if ($state == false)
				{
					$return['success'] = false;
				}
				else
				{
					$return['success'] = true;
					$return['admin_url'] = OSE_WPURL.'/wp-admin/';
				}
				oseAjax::returnJSON($return);
			}
			if ($remoteLogin == 2)
			{
				if ($state == true)
				{
					$this->loadAction();
				}
				else
				{
					$return = $this->loginFailedInfo();
					oseAjax::returnJSON($return);
				}
			}
		}
	}
	private function checkEncryptFunction () {
		if (!function_exists('mcrypt_decrypt')) {
			$return = $this->mcryptNotExists(); 
			oseAjax::returnJSON($return);
		}
	}
	private function loginFailedInfo()
	{
		$return = array();
		$return['id'] = 1;
		$return['results']['id'] = 0;
		$return['results']['name'] = oLang::_get('LOGIN_FAILED');
		$return['results']['patterns'] = oLang::_get('LOGIN_FAILED');
		$return['results']['description'] = oLang::_get('LOGIN_FAILED');
		$return['results']['Server'] = oLang::_get('LOGIN_FAILED');
		$return['results']['rule'] = oLang::_get('LOGIN_FAILED');
		$return['results']['filename'] = oLang::_get('LOGIN_FAILED');
		$return['results']['keyname'] = oLang::_get('LOGIN_FAILED');
		$return['results']['info'] = oLang::_get('LOGIN_FAILED');
		$return['results']['login'] = oLang::_get('LOGIN_STATUS');
		$return['total'] = 0;
		return $return;
	}
	private function mcryptNotExists()
	{
		$return = array();
		$return['id'] = 1;
		$return['results']['id'] = 0;
		$return['results']['name'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['patterns'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['description'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['Server'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['rule'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['filename'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['keyname'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['info'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['login'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['total'] = 0;
		return $return;
	}
	private function decryptInfo($privateKey)
	{
		if ($privateKey != null)
		{
			$encryptedLogin = oRequest::getVar('encryptedLogin', null);
			oseFirewall::callLibClass('cipher', 'Cipher');
			$Cipher = new Cipher();
			$Cipher->setSecretKey($privateKey);
			$result = $Cipher->decrypt($encryptedLogin);
			if ($result == false)
			{
				return false;
			}
			$info = array($result);
			return $info;
		}
		else
		{
			return false;
		}
	}
	private function getPrivateKeyFromDB()
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT `config`.`value`
				  FROM `#__ose_secConfig` AS `config`
				  WHERE `config`.`key` = 'privateAPIKey' ";
		$db->setQuery($query);
		$result = $db->loadObject();
		$db->closeDBO ();
		$privateKey = $result->value;
		return $privateKey;
	}
	private function loginWithInfo($info)
	{
		require_once(ABSPATH."wp-includes/pluggable.php");
		require_once(ABSPATH."wp-includes/functions.php");
		// Perform the login function here;
		$user = get_user_by('login', $info[0]);
		if (empty($user) || $user->ID == null)
		{
			return false;
		}
		else
		{
			wp_set_auth_cookie($user->ID, true, false);
			return true;
		}
	}
	private static function callControllerClass($classname)
	{
		require_once(OSE_FWRECONTROLLERS.ODS.$classname.'.php');
	}
	private function getRemoteController()
	{
		// add encrypted login;
		$controller = oRequest::getVar('controller', null);
		if ($controller != null)
		{
			$controller = ucfirst($controller);
			$controller = $controller.'RemoteController';
		}
		return $controller;
	}
	private function getRemoteAction()
	{
		$action = oRequest::getVar('action', null);
		if ($action != null)
		{
			$action = ucfirst($action);
			$action = 'action'.$action;
		}
		return $action;
	}
	private function loadAction()
	{
		$controller = $this->getRemoteController();
		$action = $this->getRemoteAction();
		if ($action != null && $controller != null)
		{
			$this->callControllerClass($controller);
			$RemoteController = new $controller($action);
			$RemoteController->$action();
		}
		else
		{
			//header('Location: '.OSE_WPURL.'/wp-admin/');
			//echo "<script type='text/javascript'>window.location='".OSE_WPURL."/wp-admin/'</script>'";
		}
	}
	public function updateSignature () {
		$this->validateIP ();
		$downloadtype=oRequest :: getInt('downloadtype', 0);
		$action = ($downloadtype==0)?"actionDownload":"actionUpdate"; 
		$this->callControllerClass('DownloadRemoteController');
		$RemoteController = new DownloadRemoteController('download');
		$RemoteController->$action();
	}
	public function vsPatternUpdate () {
		$this->validateIP ();
		$this->callControllerClass('DownloadRemoteController');
		$RemoteController = new DownloadRemoteController('download');
		$RemoteController->actionDownload();
	}
	public function vsScanning ($step) {
		$this->validateIP ();
		$this->callControllerClass('DownloadRemoteController');
		$RemoteController = new DownloadRemoteController('download');
		$RemoteController->actionVsscan($step);
	}
	private function validateIP () {
		$ip = $this->getRealIP(); 
		// Centrora server IP List; 
		$iplist = array ('50.30.36.40', '209.126.106.161', '123.3.53.2');
		if (in_array($ip, $iplist) == false)
		{
			die("Invalid Request"); 
		}
	}
	public function updateSafeBrowsing () {
		//$this->validateIP ();
		$status=oRequest :: getVar('status', null);
		if (empty($status)){
			return;	
		}
		else
		{
			$status = base64_decode($status);
			oseFirewall::callLibClass('downloader', 'oseDownloader');
			$downloader = new oseDownloader('ath', null);
			$response = $downloader->updateSafebrowsingStatus($status);
			return $response;
		}
	}
	private function getRealIP()
	{
		$ip = false;
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip)
			{
				array_unshift($ips, $ip);
				$ip = false;
			}
			$this->tvar = phpversion();
			for ($i = 0, $total = count($ips); $i < $total; $i++)
			{
				if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i]))
				{
					if (version_compare($this->tvar, "5.0.0", ">="))
					{
						if (ip2long($ips[$i]) != false)
						{
							$ip = $ips[$i];
							break;
						}
					}
					else
					{
						if (ip2long($ips[$i]) != - 1)
						{
							$ip = $ips[$i];
							break;
						}
					}
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
}
?>