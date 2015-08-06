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
class RulesetsController extends \App\Base {
	public function action_GetRulesets() {
        $results = $this->model->getRulesets();
		$this->model->returnJSON($results);   
	}
	public function action_ChangeRuleStatus () {
		$this->model->loadRequest();
		$id= $this->model->getInt('id', null);
		$status= $this->model->getInt('status', null);
		if (empty($id) || ($status!=0 && $status!=1))
		{
			$this->model->showSelectionRequired ();
		}
		$result = $this->model -> changeRuleStatus(array($id), $status);
		if ($result==true)
		{
			$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang('ITEM_STATUS_CHANGED_SUCCESS'), FALSE);
		} 
		else
		{
			$this->model->aJaxReturn(true, 'ERROR', $this->model->getLang('ITEM_STATUS_CHANGED_FAILED'), false);
		}
	}
}	