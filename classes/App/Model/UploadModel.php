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

class UploadModel extends BaseModel
{
    public function __construct()
    {
        $this->loadLibrary();
        $this->loadDatabase();
    }

    public function getCHeader()
    {
        return oLang:: _get('FILEEXTENSION_TITLE');
    }

    public function getCDescription()
    {
        return oLang:: _get('FILEEXTENSION_DESC');

    }

    protected function loadLibrary()
    {
        oseFirewall::callLibClass('uploadmanager', 'uploadmanager');
    }

    public function loadLocalScript()
    {
        $this->loadAllAssets();
        oseFirewall::loadJSFile('CentroraManageIPs', 'upload.js', false);
    }

    public function getExtLists()
    {
        $return = array();
        $uploadManager = new oseFirewallUploadManager();
        if (oseFirewall::isDBReady()) {
            $return = $uploadManager->getExtLists();
        } else {
            $return = $this->getEmptyReturn();
        }
        $return['draw'] = $this->getInt('draw');
        return $return;
    }

    public function getLog()
    {
        $return = array();
        $uploadManager = new oseFirewallUploadManager();
        if (oseFirewall::isDBReady()) {
            $return = $uploadManager->getLog();
        } else {
            $return = $this->getEmptyReturn();
        }
        $return['draw'] = $this->getInt('draw');
        return $return;
    }

    public function changeStatus($status, $id)
    {
        $uploadManager = new oseFirewallUploadManager();
        $return = $uploadManager->changeStatus($status, $id);
        return $return;
    }

    public function getExtType()
    {
        $return = '<option value="Text Files">Text Files</option><option value="Data Files">Data Files</option><option value="Audio Files">Audio Files</option><option value="Video Files">Video Files</option>' .
            '<option value="3D Image Files">3D Image Files</option><option value="Raster Image Files">Raster Image Files</option><option value="Vector Image Files">Vector Image Files</option><option value="Page Layout Files">Page Layout Files</option><option value="Spreadsheet Files">Spreadsheet Files</option>' .
            '<option value="Database Files">Database Files</option><option value="Executable Files">Executable Files</option><option value="Game Files">Game Files</option><option value="CAD Files">CAD Files</option><option value="GIS Files">GIS Files</option><option value="Web Files">Web Files</option>' .
            '<option value="Plugin Files">Plugin Files</option><option value="Font Files">Font Files</option><option value="System Files">System Files</option><option value="Settings Files">Settings Files</option><option value="Encoded Files">Encoded Files</option><option value="Compressed Files">Compressed Files</option>' .
            '<option value="Disk Image Files">Disk Image Files</option><option value="Developer Files">Developer Files</option><option value="Backup Files">Backup Files</option><option value="Misc Files">Misc Files</option>';
        return $return;
    }

    public function saveExt($name, $type, $status)
    {
        $uploadManager = new oseFirewallUploadManager();
        $return = $uploadManager->saveExt($name, $type, $status);
        return $return;
    }

    public function migrate()
    {
        $confArray = $this->getConfiguration('scan');
        $migrateData = $confArray['data']['allowExts'];
        $uploadManager = new oseFirewallUploadManager();
        $return = $uploadManager->migrate($migrateData);
        return $return;
    }
}