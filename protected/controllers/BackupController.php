<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Open Source Excellence WordPress Firewall
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 01-Jun-2013
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once(OSE_FWCONTROLLERS.ODS.'BaseController.php');
class BackupController extends BaseController
{
	public $layout = '//layouts/grids';
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
	public function actionBackup()
	{
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
		oseFirewall::loadRequest();
		oseFirewall::loadFiles();
		$db = oseFirewall::getDBO();
		$backup_type = oRequest::getInt('backup_type', 3);
		$step = oRequest::getInt('step');
		$this->model->backupFiles($backup_type, $step);
	}
	public function actionDeleteBackup()
	{
		oseFirewall::loadRequest();
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
	
	public function actionDownloadBackupDB(){
		oseFirewall::loadRequest();
		$id = oRequest::getVar('ids', null);
		$this->model ->downloadBackupDB($id);
		
	}
	
	public function actionDownloadBackupFile(){
		oseFirewall::loadRequest();
		$id = oRequest::getVar('ids', null);
		$this->model ->downloadBackupFile($id);
	} 
}
?>	