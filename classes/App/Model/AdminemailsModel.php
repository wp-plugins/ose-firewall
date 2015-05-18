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
    die ('Direct Access Not Allowed');
}
require_once('BaseModel.php');

class AdminemailsModel extends BaseModel
{
    public function __construct()
    {
        $this->loadLibrary();
        $this->loadDatabase();
    }

    protected function loadLibrary()
    {
        oseFirewall::callLibClass('adminemails', 'oseAdmin');
    }

    public function loadLocalScript()
    {
        $this->loadAllAssets();
        oseFirewall::loadJSFile('CentroraSEOTinyMCE', 'plugins/tinymce/tinymce.min.js', false);
        oseFirewall::loadJSFile('CentroraManageIPs', 'adminemails.js', false);
    }

    public function getCHeader()
    {
        return oLang::_get('ADMINEMAILS_TITLE');
    }

    public function getCDescription()
    {
        return oLang::_get('ADMINEMAILS_DESC');
    }

    public function getAdminList()
    {
        $adminManager = new oseAdminManager();
        $return = $adminManager->getAdminList();
        return $return;
    }

    public function saveAdmin($name, $email, $status, $domain)
    {
        $adminManager = new oseAdminManager();
        $return = $adminManager->saveAdmin($name, $email, $status, $domain);
        return $return;
    }

    public function saveDomain($domain)
    {
        $adminManager = new oseAdminManager();
        $return = $adminManager->saveDomain($domain);
        return $return;
    }

    public function getDomain()
    {
        $adminManager = new oseAdminManager();
        $return = $adminManager->getDomain();
        return $return;
    }

    public function changeStatus($status, $id)
    {
        $adminManager = new oseAdminManager();
        $return = $adminManager->changeStatus($status, $id);
        return $return;
    }

    public function deleteAdmin($id)
    {
        $adminManager = new oseAdminManager();
        $return = $adminManager->deleteAdmin($id);
        return $return;
    }

    public function saveEmailEditor($content)
    {
        $adminManager = new oseAdminManager();
        $return = $adminManager->saveEmailEditor($content);
        return $return;
    }

    public function readEmailTemp()
    {
        $adminManager = new oseAdminManager();
        $return = $adminManager->readEmailTemp();
        return $return;
    }
}