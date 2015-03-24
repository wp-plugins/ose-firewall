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
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
 die('Direct Access Not Allowed');
}
class ScanreportController extends \App\Base {
	public function action_GetTypeList () {
		$results = $this ->model->getTypeList();
		$this ->model->returnJSON($results);
	}
	public function action_GetMalwareMap() {
		$results = $this ->model->getMalwareMap();
		$this ->model->returnJSON($results);
	}
	public function action_Viewfile () {
		$this ->model->loadRequest (); 	
		$id = $this ->model->getInt('id', null); 
		if (empty($id))
		{
			$this->model->showSelectionRequired ();
		}
		$results = $this ->model->viewfile($id);
		$this->model->returnJSON($results);
	}

    public function action_Backupvs()
    {
        $this->model->loadRequest();
        $id = $this->model->getInt('id', null);
        if (empty($id)) {
            $this->model->showSelectionRequired();
        }
        $returns = $this->model->backupvs($id);
        $this->model->returnJSON($returns);
    }

    public function action_Bkcleanvs()
    {
        $this->model->loadRequest();
        $id = $this->model->getInt('id', null);

        if (empty($id)) {
            $this->model->showSelectionRequired();
        }
        $returns = $this->model->bkcleanvs($id);
        $this->model->returnJSON($returns);
    }

    public function action_Deletevs()
    {
        $this->model->loadRequest();
        $id = $this->model->getInt('id', null);

        if (empty($id)) {
            $this->model->showSelectionRequired();
        }
        $returns = $this->model->deletevs($id);
        $this->model->returnJSON($returns);
    }

    public function action_Restorevs()
    {
        $this->model->loadRequest();
        $id = $this->model->getInt('id', null);

        if (empty($id)) {
            $this->model->showSelectionRequired();
        }
        $returns = $this->model->restorevs($id);
        $this->model->returnJSON($returns);
    }

    public function action_Batchbk()
    {
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $returns = $this->model->batchbk($id);
        $this->model->returnJSON($returns);
    }

    public function action_Batchbkcl()
    {
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $returns = $this->model->batchbkcl($id);
        $this->model->returnJSON($returns);
    }

    public function action_Batchrs()
    {
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $returns = $this->model->batchrs($id);
        $this->model->returnJSON($returns);
    }

    public function action_Batchdl()
    {
        $this->model->loadRequest();
        $id = $this->model->getVar('id', null);
        $returns = $this->model->batchdl($id);
        $this->model->returnJSON($returns);
    }
}
?>	