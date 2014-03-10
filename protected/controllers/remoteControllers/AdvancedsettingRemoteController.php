<?php
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once(OSE_FWRECONTROLLERS.ODS.'BaseRemoteController.php');
require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
class AdvancedsettingRemoteController extends BaseRemoteController
{
	public $layout = '//layouts/grids';
	public function getModel()
	{
		$modelName = 'AdvancerulesetsModel';
		$this->model = new $modelName();
		return $this->model;
	}
	public function actionGetRulesets()
	{
		$results = $this->model->getRulesets();
		oseAjax::returnJSON($results);
	}
	public function actionChangeRuleStatus()
	{
		oseFirewall::loadRequest();
		$id = oRequest::getInt('id', null);
		$status = oRequest::getInt('status', null);
		if (empty($id) || ($status != 0 && $status != 1))
		{
			return;
		}
		$model = $this->getModel();
		$result = $model->changeRuleStatus(array($id), $status);
		if ($result == true)
		{
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get('ITEM_STATUS_CHANGED_SUCCESS'), FALSE);
		}
		else
		{
			oseAjax::aJaxReturn(true, 'ERROR', oLang::_get('ITEM_STATUS_CHANGED_FAILED'), false);
		}
	}
	
	public function actionRefreshStat(){
		oseFirewall::loadRequest ();
		$return = array();
		$return['results'] = $this ->model->getVersion();
		oseAjax::returnJSON($return);
	}
	
	public function actionActiveAdvRule(){
		oseFirewall::loadRequest (); 
		$ids= oRequest :: getVar('ids',null);
		$ids = oseJSON :: decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this ->model -> changeRuleStatus($ids,(int)1);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The rules has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The rules was restored unsuccessfully."), false);
		}
	}
	
	public function actionInactiveAdvRule(){
		oseFirewall::loadRequest (); 
		$ids= oRequest :: getVar('ids',null);
		$ids = oseJSON :: decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this ->model -> changeRuleStatus($ids,(int)0);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The rules has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The rules was restored unsuccessfully."), false);
		}
	}
	
}
?>