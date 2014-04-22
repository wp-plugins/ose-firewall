<?php
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once(OSE_FWRECONTROLLERS.ODS.'BaseRemoteController.php');
require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
class BackupRemoteController extends BaseRemoteController
{
	public function __construct($id, $module = null)
	{
		parent::__construct($id, $module = null);
		$this->getModel();
	}
	public function actionBackup()
	{
		oseFirewall::loadLanguage();
		oseFirewall::loadRequest();
		oseFirewall::loadFiles();
		$db = oseFirewall::getDBO();
		$backup_type = oRequest::getInt('backup_type', 3);
		switch ($backup_type)
		{
			case 2:
			case 3:
				$result = $this->model ->backupDB($backup_type);
				break;
		}
		if ($result != false)
		{
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("BACKUP_SUCCESS"), false);
		}
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("BACKUP_FAILED"), false);
		}
	}
	public function actionBackupFile()
	{
		oseFirewall::loadLanguage();
		oseFirewall::loadRequest();
		oseFirewall::loadFiles();
		$db = oseFirewall::getDBO();
		$backup_type = oRequest::getInt('backup_type', 3);
		$step = oRequest::getInt('step');
		$this->model->backupFiles($backup_type, $step);
	}
	public function actionDeleteBackup()
	{
		oseFirewall::loadLanguage();
		oseFirewall::loadRequest();
		oseFirewall::loadFiles();
		$ids = oRequest::getVar('ids', null);
		$ids = oseJSON::decode($ids);
		$result = $this->model ->removeBackUp($ids);
		if ($result == true)
		{
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("DB_DELETE_SUCCESS"), false);
		}
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("DB_DELETE_FAILED"), false);
		}
	}
	
	public function actionGetBackupList()
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
		$results = $this->model->getBackupList();
		oseAjax::returnJSON($results, $mobiledevice);
	}
	
	public function actionDeleteItemByID(){
		oseFirewall::loadRequest();
		$ids = oRequest::getVar('id', null);
		$ids = oseJSON::decode($ids);
		$ids = array($ids);
		$result = $this->model ->removeBackUp($ids);
		if ($result == true)
		{
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("DB_DELETE_SUCCESS"), false);
		}
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("DB_DELETE_FAILED"), false);
		}
	}

}
?>