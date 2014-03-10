<?php
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH')) {
	die('Direct Access Not Allowed');
}
class RemoteLogin{
	
	public function login(){
		oseFirewall::loadLanguage();
		require_once (OSEFWDIR.ODS.'framework'.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php'); 
		$privateKey = null;
		$privateKey = $this -> getPrivateKeyFromDB();
		$info = $this -> decryptInfo($privateKey);
		if(!$info) 
		{
			return false;
		}
		$state = $this->loginWithInfo($info);
		$remoteLogin = oRequest::getInt('remoteLogin', 0); 
		if ($remoteLogin > 0 )
		{
			if($remoteLogin == 1)
			{
				$return = array();
				if($state == false)
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
			if($remoteLogin == 2)
			{
				if($state == true)
				{
					$this -> loadAction();
				}
				else
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
					$return['total'] = 0;
					oseAjax::returnJSON($return);
				}
			}
			
			
		} 
	}
	private function decryptInfo($privateKey){	
		if($privateKey != null){
			$encryptedLogin = oRequest :: getVar('encryptedLogin', null);
			$crypttext = base64_decode($encryptedLogin);	
			$res = openssl_get_privatekey($privateKey);
			openssl_private_decrypt($crypttext, $decrypttext, $res);
			$info = explode("-", $decrypttext);	
			return $info;
		}else{
			return false;
		}
	}
	
	private function getPrivateKeyFromDB(){
		$db = oseFirewall :: getDBO();
		$query = "SELECT `config`.`value`
				  FROM `#__ose_secConfig` AS `config`
				  WHERE `config`.`key` = 'privateAPIKey' ";
		$db->setQuery($query);
		$result = $db->loadObject();
		$privateKey = $result->value;
		return $privateKey;
	}
	
	private function loginWithInfo($info){
		require_once( ABSPATH . "wp-includes/pluggable.php" );
		require_once( ABSPATH . "wp-includes/functions.php" );
		// Perform the login function here;
		$creds = array();
		$creds['user_login'] = $info[0];
		$creds['user_password'] = $info[1];
		$creds['remember'] = true;
		$user = wp_signon( $creds, false );
		if($user->data->ID == null){
			return false;
		}
		return $user;
	}
	private static function callControllerClass($classname)
	{
		require_once (OSE_FWRECONTROLLERS . ODS . $classname.'.php');
	}
	
	private function getRemoteController(){
		// add encrypted login; 
		
		$controller = oRequest :: getVar('controller', null);
		if ($controller!=null)
		{
			$controller = ucfirst($controller);
			$controller = $controller.'RemoteController';
		}
		return $controller;
	}
	
	private function getRemoteAction(){
		$action = oRequest :: getVar('action', null);
		if ($action!=null)
		{
			$action = ucfirst($action);
			$action = 'action'.$action;
		}
		return $action;
	}
	
	private function loadAction(){
		$controller = $this->getRemoteController();
		$action = $this->getRemoteAction();
		if ($action!=null && $controller!=null)
		{
			$this->callControllerClass($controller);
			$RemoteController = new $controller($action);
			$RemoteController -> $action();
		}
		else 
		{	
			//header('Location: '.OSE_WPURL.'/wp-admin/');
			//echo "<script type='text/javascript'>window.location='".OSE_WPURL."/wp-admin/'</script>'";
		}
	}
	
}
?>