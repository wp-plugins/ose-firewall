<?php 
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once (OSE_FWCONTROLLERS. ODS. 'BaseController.php');
oseFirewall::loadLanguage();
oseFirewall::loadJSON();
class BaseRemoteController extends BaseController {
	public function getModel () {
		$modelName = str_replace ('RemoteController', 'Model', get_class($this)) ;
		$this->model = new $modelName();    
		return $this->model;   
	}

}	
?>