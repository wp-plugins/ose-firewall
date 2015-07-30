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

class UploadController extends \App\Base
{
    public function action_getExtLists()
    {
        $results = $this->model->getExtLists();
        $this->model->returnJSON($results);
    }

    public function action_getLog()
    {
        $results = $this->model->getLog();
        $this->model->returnJSON($results);
    }
    public function action_changeStatus()
    {
        $this->model->loadRequest();
        $status = $this->model->getVar('status', null);
        $id = $this->model->getInt('id', null);
        $result = $this->model->changeStatus($status, $id);
        $this->model->returnJSON($result);
    }

    public function action_saveExt()
    {
        $this->model->loadRequest();
        $name = $this->model->getVar('ext-name', null);
        $type = $this->model->getVar('ext-type', null);
        $status = $this->model->getVar('ext-status', null);
        if (empty($name)) {
            $error = "please provide an extension name";
            $this->model->returnJSON($error);
        } elseif (empty($type)) {
            $error = "please select an extension type";
            $this->model->returnJSON($error);
        } elseif (empty($status)) {
            $error = "please select an extension status";
            $this->model->returnJSON($error);
        } else {
            $result = $this->model->saveExt($name, $type, $status);
            $this->model->returnJSON($result);
        }
    }
}	