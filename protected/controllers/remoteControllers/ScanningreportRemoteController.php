<?php
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once(OSE_FWRECONTROLLERS.ODS.'BaseRemoteController.php');
require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
class ScanningreportRemoteController extends BaseRemoteController
{
	public $layout = '//layouts/grids';
	public function getModel()
	{
		$modelName = 'ScanreportModel';
		$this->model = new $modelName();
		return $this->model;
	}
	public function actionGetTypeList()
	{
		$results = $this->model->getTypeList();
		oseAjax::returnJSON($results);
	}
	public function actionGetMalwareMap()
	{
		$results = $this->model->getMalwareMap();
		oseAjax::returnJSON($results);
	}
	public function actionViewfile()
	{
		oseFirewall::loadRequest();
		$id = oRequest::getInt('id', null);
		if (empty($id))
		{
			return;
		}
		$results = $this->model->viewfile($id);
		print_r($results);
		exit;
	}
	public function actionRefreshStat()
	{
		oseFirewall::loadRequest();
		$return = array();
		$return['results'] = $this->model->getStatistics();
		oseAjax::returnJSON($return);
	}
}
?>