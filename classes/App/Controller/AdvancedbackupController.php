<?php
namespace App\Controller;
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
require_once('BackupController.php');

class AdvancedbackupController extends BackupController
{
    public function action_getDropboxUploads(){
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $result = $this->model->getDropboxUploads($id);
        $this->model->returnJSON($result);
    }

    public function action_dropboxUpload()
    {
        $this->model->loadRequest();
        $path = $this->model->getVar('path', null);
        $folder = $this->model->getVar('folder', null);
        $result = $this->model->dropboxUpload($path, $folder);
        $this->model->returnJSON($result);
    }

    public function action_getOneDriveUploads()
    {
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $result = $this->model->getOneDriveUploads($id);
        $this->model->returnJSON($result);
    }

    public function action_oneDriveUpload(){
        $this->model->loadRequest();
        $path = $this->model->getVar('path', null);
        $folderID = $this->model->getVar('folderID', null);
        $result = $this->model->oneDriveUpload($path, $folderID);
        $this->model->returnJSON($result);
    }

    public function action_getGoogleDriveUploads()
    {
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $result = $this->model->getGoogleDriveUploads($id);
        $this->model->returnJSON($result);
    }

    public function action_googledrive_upload()
    {
        $this->model->loadRequest();
        $path = $this->model->getVar('path', null);
        $folderID = $this->model->getVar('folderID', null);
        $result = $this->model->googledrive_upload($path, $folderID);
        $this->model->returnJSON($result);
    }
    public function action_sendemail()
    {
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $type = $this->model->getVar('type', null);
        $result = $this->model->sendemail($id, $type);
        $this->model->returnJSON($result);
    }
}

?>