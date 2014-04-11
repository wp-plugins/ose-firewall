<?php 
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once (OSE_FWRECONTROLLERS. ODS. 'BaseRemoteController.php');
require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'ajax' . ODS . 'oseAjax.php');
class VsscanRemoteController extends BaseRemoteController{
	public function __construct($id,$module=null)
	{
		parent::__construct($id,$module=null);
		$this -> getModel () ;
		$this -> model -> isDBReady();
	}
	
	public function actionInitDatabase() {
		oseFirewall::loadRequest ();
		oseFirewall::loadFiles ();
		$step= oRequest::getInt('step');
		$path= OSE_DEFAULT_SCANPATH;
		if (empty($path))
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("SCANNED_PATH_EMPTY"), false);
		}
		else
		{
			$path = oseFile::clean ($path);
			$this ->model->initDatabase($step, $path);
		}
	}
	
	public function actionVsscan() {
		oseFirewall::loadRequest ();
		$step= oRequest::getInt('step');
		$results = $this ->model->vsScan($step);
		oseAjax::returnJSON($results);
	}
}
?>