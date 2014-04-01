<?php
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once(OSE_FWRECONTROLLERS.ODS.'BaseRemoteController.php');
require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
class AdvancedpatternsRemoteController extends BaseRemoteController
{
	public $layout = '//layouts/grids';
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
	
}
?>