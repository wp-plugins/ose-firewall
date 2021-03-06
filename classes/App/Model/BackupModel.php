<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Centrora Security Firewall
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
if (! defined ( 'OSE_FRAMEWORK' ) && ! defined ( 'OSEFWDIR' ) && ! defined ( '_JEXEC' )) {
	die ( 'Direct Access Not Allowed' );
}
require_once ('BaseModel.php');
class BackupModel extends BaseModel {

	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
    public function is_authorized(){
    }
	protected function loadLibrary() {
		oseFirewall::callLibClass ( 'backup', 'oseBackup' );
	}
	public function loadLocalScript() {
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ( 'CentroraManageIPs', 'backup.js', false );
	}
	public function getCHeader() {
		return oLang::_get ( 'BACKUP_TITLE' );
	}
	public function getCDescription() {
		return oLang::_get ( 'BACKUP_DESC' );
	}
	public function getBackupList() {
		$backupManager = new oseBackupManager ();
		$return = $backupManager->getBackupList ();
		return $return;
	}
    public function oauth(){
    }
	public function backup($backup_type, $backup_to) {
		$backupManager = new oseBackupManager ();
		$return ['data'] = utf8_encode ( $backupManager->backup ( $backup_type, $backup_to ) );
		return $return;
	}
    public function contBackup($sourcePath, $outZipPath, $serializefile, $recall) {
        $backupManager = new oseBackupManager ();
        $backupManager-> addFilesToArchive ($sourcePath, $outZipPath, $serializefile, $recall);
    }
	public function deleteBackUp($ids) {
		$backupManager = new oseBackupManager ();
		$result = $backupManager->deleteBackUp ( $ids );
        return $result;
	}
    public function downloadBackupFile(){
        oseBackupManager::downloadBackupFile();
    }
}