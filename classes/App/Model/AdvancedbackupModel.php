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
 * @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC')) {
    die('Direct Access Not Allowed');
}
require_once('BackupModel.php');

class AdvancedbackupModel extends BackupModel
{
    public function loadLocalScript()
    {
        $this->loadAllAssets();
        oseFirewall::loadJSFile('CentroraManageIPs', 'advancedbackup.js', false);
    }

    public function is_authorized()
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->is_authorized();
        return $return;
    }

    public function getOneDriveUploads($id)
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->getOneDriveUploads($id);
        return $return;
    }

    public function getGoogleDriveUploads($id)
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->getGoogleDriveUploads($id);
        return $return;
    }
    public function oneDriveUpload($path, $folderID){
        $backupManager = new oseBackupManager ();
        $return = $backupManager->oneDriveUpload($path, $folderID);
        return $return;
    }

    public function getDropboxUploads($id)
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->getDropboxUploads($id);
        return $return;
    }

    public function dropboxUpload($path, $folder)
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->dropboxUpload($path, $folder);
        return $return;
    }

    public function googledrive_upload($path, $folderID)
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->googledrive_upload($path, $folderID);
        return $return;
    }
    public function sendemail($id, $type)
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->sendemail($id, $type);
        return $return;
    }

    /**
     * @param $cloudbackuptype
     * @return bool
     */
    public function checkCloudAuthentication ($cloudbackuptype){
        switch($cloudbackuptype){
            case 1:
                return true;
                break;
            case 2:
                oseFirewall::callLibClass('backup', 'oseBackup');
                $oseBackupManager = new oseBackupManager();
                $dropboxautho = $oseBackupManager->is_authorized();
                if ($dropboxautho == 'fail'){
                    return false;
                }elseif ($dropboxautho == 'ok'){
                    return true;
                }
                break;
            case 3:
                oseFirewall::callLibClass('backup/onedrive', 'onedrive');
                $oneDrive = new onedriveModelBup ();
                return $oneDrive->isAuthenticated();
                break;
            case 4:
                oseFirewall::callLibClass('backup/googledrive', 'googledrive');
                $gDrive = new gdriveModelBup ();
                return $gDrive->isAuthenticated();
                break;
            default:
                return false;
        }
    }
}