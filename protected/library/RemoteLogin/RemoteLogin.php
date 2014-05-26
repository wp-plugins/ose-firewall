<?php
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH'))
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
}
?>