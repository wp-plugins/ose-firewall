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
require_once('BaseModel.php');

class AuthenticationModel extends BaseModel
{
    public function __construct()
    {
        $this->loadLibrary();
        $this->loadDatabase();
    }
    protected function loadLibrary()
    {
        oseFirewall::callLibClass('backup', 'oseBackup');
        oseFirewall::callLibClass('backup/onedrive', 'onedrive');
    }
    public function loadLocalScript()
    {
        $this->loadAllAssets();
        oseFirewall::loadJSFile('CentroraManageIPs', 'authentication.js', false);
    }
    public function getCHeader()
    {
        return oLang::_get('AUTHENTICATION_TITLE');
    }
    public function getCDescription()
    {
        return oLang::_get('AUTHENTICATION_DESC');
    }
    public function oauth($type, $reload)
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->oauth($type, $reload);
        return $return;
    }
    public function dropBoxVerify(){
        $oseBackupManager = new oseBackupManager();
        $dropboxautho = $oseBackupManager->is_authorized();
        if ($dropboxautho == 'fail'){
            return false;
        }elseif ($dropboxautho == 'ok'){
            return true;
        }
    }

    public function oneDriveVerify()
    {
        $oneDrive = new onedriveModelBup ();
        $return = $oneDrive->isAuthenticated();
        return $return;
    }

    public function oauthOneDrive()
    {
        $code = $this->getVar('code', null);
        $oneDrive = new onedriveModelBup ();
        if (!empty($code)) {
            $return = $oneDrive->authorize($code);
        } else {
            $return = $oneDrive->getAuthorizationUrl();
        }
        echo $return;
    }

    public function  onedrive_logout()
    {
        $oneDrive = new onedriveModelBup ();
        $return = $oneDrive->logout();
        return $return;
    }

    public function dropbox_init()
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->is_authorized();
        return $return;
    }

    public function dropbox_logout()
    {
        $backupManager = new oseBackupManager ();
        $return = $backupManager->dropbox_logout();
        return $return;
    }
}