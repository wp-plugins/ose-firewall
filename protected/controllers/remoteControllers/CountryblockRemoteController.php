<?php
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once(OSE_FWRECONTROLLERS.ODS.'BaseRemoteController.php');
require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
class CountryblockRemoteController extends BaseRemoteController
{
	public $layout = '//layouts/grids';
	public function actionBlacklistIP()
	{
		$this->changeCountryStatus(1);
	}
	public function actionWhitelistIP()
	{
		$this->changeCountryStatus(3);
	}
	private function changeCountryStatus($status)
	{
		oseFirewall::loadRequest();
		$aclids = oRequest::getVar('ids', null);
		$aclids = oseJSON::decode($aclids);
		$this->changeStatus($aclids, $status);
	}
	private function changeStatus($aclids, $status)
	{
		if (empty($aclids) || empty($status))
		{
			return;
		}
		$result = $this->model ->changeCountryStatus($aclids, $status);
		if ($result == true)
		{
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("IP_RULE_CHANGED_SUCCESS"), false);
		}
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("IP_RULE_CHANGED_FAILED"), false);
		}
	}
	public function actionCreateTables()
	{
		$this->model->createTables();
	}
	public function actionDownLoadTables()
	{
		oseFirewall::loadRequest();
		$step = oRequest::getInt('step');
		$results = $this->model->downloadTables($step);
		oseAjax::returnJSON($results);
	}
	public function actionGetCountryList()
	{
		oseFirewall::loadRequest();
		if (isset($_REQUEST['mobiledevice']))
		{
			$mobiledevice = oRequest::getInt('mobiledevice', 0);
		}
		else
		{
			$mobiledevice = 0;
		}
		$results = $this->model->getCountryList();
		oseAjax::returnJSON($results, $mobiledevice);
	}
	
	public function actionRefreshStat()
	{
		oseFirewall::loadRequest ();
		$return = array();
		$return['results'] = $this ->model->getStatistics();
		oseAjax::returnJSON($return);
	}
}
?>