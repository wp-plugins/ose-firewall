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

class AdminemailsController extends \App\Base
{
    public function action_saveDomain()
    {
        $this->model->loadRequest();
        $domain = $this->model->getVar('domain-address', null);
        $pattern = "/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/";
        //  $pattern = "/localhost:8888/";
        if (empty($domain)) {
            $error = "please fill out all the form";
            $this->model->returnJSON($error);
        } elseif (preg_match($pattern, $domain)) {
            $result = $this->model->saveDomain($domain);
            $this->model->returnJSON($result);
        } else {
            $error = "please provide valid domain address, for example, www.domain.com";
            $this->model->returnJSON($error);
        }
    }

    public function action_saveAdmin()
    {
        $this->model->loadRequest();
        $name = $this->model->getVar('admin-name', null);
        $email = $this->model->getVar('admin-email', null);
        $status = $this->model->getVar('admin-status', null);
        $domain = $this->model->getVar('admin-domain', null);
        $pattern = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-zA-Z]{2,6}(?:\.[a-zA-Z]{2})?)$/";
        if (empty($name) || empty($email) || empty($status) || empty($domain)) {
            $error = "please fill out all the form";
            $this->model->returnJSON($error);
        } elseif (preg_match($pattern, $email)) {
            $result = $this->model->saveAdmin($name, $email, $status, $domain);
            $this->model->returnJSON($result);

        } else {
            $error = "please provide valid email address";
            $this->model->returnJSON($error);
        }
    }

    public function action_saveEmailEditor()
    {
        $this->model->loadRequest();
        $content = $_POST['emailEditor'];
        $result = $this->model->saveEmailEditor($content);
        $this->model->returnJSON($result);
    }
    public function action_getAdminList()
    {
        $this->model->loadRequest();
        $result = $this->model->getAdminList();
        $this->model->returnJSON($result);
    }

    public function action_getDomain()
    {
        $this->model->loadRequest();
        $result = $this->model->getDomain();
        $this->model->returnJSON($result);
    }

    public function action_changeStatus()
    {
        $this->model->loadRequest();
        $status = $this->model->getVar('status', null);
        $id = $this->model->getInt('id', null);
        $result = $this->model->changeStatus($status, $id);
        $this->model->returnJSON($result);
    }

    public function action_deleteAdmin()
    {
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $result = $this->model->deleteAdmin($id);
        $this->model->returnJSON($result);
    }

    public function action_restoreDefault()
    {
        $result = $this->model->restoreDefault();
        $this->model->returnJSON($result);
    }
}
