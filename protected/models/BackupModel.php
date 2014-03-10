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
class BackupModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
		oseFirewall::callLibClass('backup', 'oseBackup');
	}
	public function loadLocalScript() {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScriptFile($baseUrl . '/public/js/backup.js', CClientScript::POS_END);
	}
	public function getCHeader() {
		return oLang :: _get('BACKUP_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('BACKUP_DESC');
	}
	public function getBackupList()
	{
		$return = array(); 
		$backupManager = new oseBackupManager($this->db, null);
		if(oseFirewall::isDBReady()){
			$return['id']=1; 		
			$return['results']= $backupManager->getBackupList();
			if (empty($return['results']))
			{
				$return['results']['id'] = 0;
				$return['results']['name'] = 'N/A';
			}
			$return['total']= (int)$backupManager->getBackupTotal();
		}else{
			$return['id'] = 1;
			$return['results']['id'] = 0;
			$return['results']['name'] = 'N/A';
			$return['total'] = 0;
		}
		return $return; 
	}
	
	public function backupDB($backup_type)
	{
		$backupManager = new oseBackupManager($this->db, $backup_type);
		$backupResult = $backupManager->backupDB(); 
		$result = null;
		if($backupResult == false)
		{	
			$result = $this->assembleArray (false, 'ERROR', DB_BACKUP_FAILED_INCORRECT_PERMISSIONS, $continue = false, $id = null);
		}
		else if($backupResult == true && $backup_type != 3)
		{
			$InsertDBResult = $backupManager->insertbkDB();
			$result = $this->assembleArray(true, 'SUCCESS', DB_BACKUP_SUCCESS, $continue = false, $id = null);
		}
		else
		{
			$result = $this->assembleArray (true, 'SUCCESS', DB_BACKUP_SUCCESS, $continue = true, $id = null);
		} 
		oseAjax::returnJSON($result);
		/*$result = null;
		if($backupResult == false)
		{	
			$result = $this->assembleArray (false, 'ERROR', DB_BACKUP_FAILED_INCORRECT_PERMISSIONS, $continue = false, $id = null);
		}
		else if($backupResult == true && $backup_type != 3)
		{
			$InsertDBResult = $backupManager->insertbkDB();
			$result = $this->assembleArray(true, 'SUCCESS', DB_BACKUP_SUCCESS, $continue = false, $id = null);
		}
		else
		{
			$InsertDBResult = $backupManager->insertbkDB();
			$result = $this->assembleArray (true, 'SUCCESS', DB_BACKUP_SUCCESS, $continue = true, $id = null);
		} 
		oseAjax::returnJSON($result);*/
	}
	
	private function assembleArray($result, $status, $msg, $continue, $id)
	{
		$return = array(
			'success' => (boolean) $result,
			'status' => $status,
			'result' => $msg,
			'cont' => (boolean) $continue,
			'id' => (int) $id
		);
		return $return;
	}
	
	public function removeBackUp($ids)
	{
		$backupManager = new oseBackupManager($this->db, $backup_type);
		foreach ($ids as $id)
		{
			$result = $backupManager->removeBackUp($id);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	
	public function downloadBackupDB($id){
		$backupManager = new oseBackupManager($this->db);
		$result = $backupManager -> getBackupDBByID($id);
		$file = $result-> dbBackupPath .".gz";
		$backupManager->downloadBackupFiles($file);
		
	}
	
	public function downloadBackupFile($id){
		$backupManager = new oseBackupManager($this->db);
		$result = $backupManager -> getBackupDBByID($id);
		$file = $result-> fileBackupPath;
		$backupManager->downloadBackupFiles($file);
	
	}
	
	public function getBackupRecordByID($id){
		$backupManager = new oseBackupManager($this->db);
		$result = $backupManager -> getBackupDBByID($id);
		return $result;
	}
	
	
	public function backupFiles($backup_type, $step) 
	{
		oseFirewall::callLibClass('backup','oseBackup'); 
		$backupManager = new oseBackupManager ($this->db, $backup_type);
		$result = $backupManager -> backupFiles($step);
		if($backup_type == 3){
			if($result['cont'] == false){
				$backupManager->insertbkDB();
			}
		}
		
		else if($backup_type == 1){
			if($result ['cont'] == false){	
				$backupManager->insertbkDB();
			}
		}
		oseAjax::returnJSON($result);
		
	}
	
	public function changeACLStatus($aclids, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($aclids as $aclid)
		{
			$result = $oseFirewallStat->changeACLStatus($aclid, $status);
			if ($result == false)
			{
				return false;
			}
		}
		return true; 
	}
	
	public function getAttackDetail($aclid)
	{
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getAttackDetail($aclid); 
	}
}