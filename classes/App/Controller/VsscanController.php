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
class VsscanController extends \App\Base {
	public function action_InitDatabase() {
		$this->model->loadRequest();
		$step= $this->model->getInt('step');
		$path= $this->model->getVar('path', null);
		if (empty($path))
		{
			$this->model->aJaxReturn(false, 'ERROR', oLang::_get("SCANNED_PATH_EMPTY"), false);
		}  
		else
		{
			$path = $this->model->fileClean ($path);
			$this ->model->initDatabase($step, $path);
		}
	}
	public function action_Vsscan() {
		$this->model->loadRequest();
		$step= $this->model->getInt('step');
		$type= $this->model->getInt('type', null);
		$results = $this ->model->vsScan($step, $type);
		$this->model->returnJSON($results); 
	}
	public function action_UpdatePatterns() {
		$this->model->loadRequest();
		$patternType= $this->model->getInt('patternType', 1);
		$results = $this ->model->updatePatterns($patternType);
		print_r($results);exit;
	}
	public function action_CheckScheduleScanning () {
		$this->model->loadRequest();
		$results = $this ->model->checkScheduleScanning();
		print_r($results);exit;
	}
}
?>	