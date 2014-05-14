<?php 
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once (OSE_FWRECONTROLLERS. ODS. 'BaseRemoteController.php');
require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'ajax' . ODS . 'oseAjax.php');
class DownloadRemoteController extends BaseRemoteController{
	public function __construct($id,$module=null)
	{
		parent::__construct($id,$module=null);
		$this -> getModel () ;
		$this -> model -> isDBReady();
	}
	
	public function actionDownload() 
	{
		oseFirewall::loadFiles ();
		$type= oRequest::getVar('type');
		$version= oRequest::getVar('version');
		$downloadKey = oRequest::getVar('downloadKey');
		$result = $this->model->download($type, $downloadKey);
		if ($result == true)
		{
			$this->model->updateVersion ($type, $version); 
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("ADVRULESET_INSTALL_SUCCESS"), false);
		}   
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("ADVRULESET_INSTALL_FAILED"), false);
		}
	}
	
	public function actionUpdate() 
	{
		oseFirewall::loadFiles ();
		$type= oRequest::getVar('type');
		$version= oRequest::getVar('version');
		$downloadKey = oRequest::getVar('downloadKey');
		$result = $this->model->update($type, $downloadKey);
		if ($result == true)
		{
			$this->model->updateVersion ($type, $version); 
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("ADVRULESET_INSTALL_SUCCESS"), false);
		}   
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("ADVRULESET_INSTALL_FAILED"), false);
		}
	}
}
?>