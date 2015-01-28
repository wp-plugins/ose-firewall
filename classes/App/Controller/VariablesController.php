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
class VariablesController extends \App\Base {
	public function action_GetVariables() {
		$results = $this->model->getVariables();
		$this->model->returnJSON($results); 
	}
	public function action_ChangeVarStatus()
	{
		$this->model->loadRequest();
		$id= $this->model->getInt('id', null);
		$status= $this->model->getInt('status', null);
		if (empty($id) || (!in_array($status, array(1,2,3))))
		{
			$this->model->showSelectionRequired ();
		}
		$result = $this->model->changeVarStatus(array($id), $status);
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("VARIABLE_CHANGED_SUCCESS"), true);
		} 
		else
		{
				$this->model->aJaxReturn(true, 'ERROR', $this->model->getLang("VARIABLE_CHANGED_FAILED"), false);
		}
	}
	public function action_Scanvar()
	{
		$this->changeVARStatus(1);
	}
	public function action_Filtervar()
	{
		$this->changeVARStatus(2);
	}
	public function action_Ignorevar()
	{
		$this->changeVARStatus(3);
	}
	private function changeVARStatus($status)
	{
		$this->model->loadRequest();
		$ids = $this->model->getVar('ids', null);
		$ids = $this->model->JSON_decode($ids);
		if (empty($ids))
		{
			$this->model->showSelectionRequired ();
		}
		$result = $this ->model -> changeVARStatus($ids, $status);
		if ($result==true)
		{
			$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("VARIABLE_CHANGED_SUCCESS"), false);
		}
		else
		{
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("VARIABLE_CHANGED_FAILED"), false);
		}
	}
	public function action_Addvariables()
	{
		$this->model->loadRequest();
		$requesttype= $this->model->getVar('requesttype',null);
		$variable= $this->model->getVar('variablefield',null);
		$status= $this->model->getInt('statusfield',null);
		if (empty($variable) || (!in_array($status, array(1,2,3))))
		{
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("VARIABLE_NAME_REQUIRED"), false);
		}
		$variable = $requesttype.'.'.$variable;
		$result = $this ->model->addvariables($variable, $status);
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("VARIABLE_ADDED_SUCCESS"), true);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("VARIABLE_ADDED_FAILED"), false);
		}
	}
	public function action_Deletevariable()
	{
		$this->model->loadRequest();
		$ids= $this->model->getVar('ids',null);
		$ids = $this->model->JSON_decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this ->model->deletevariable($ids);
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("VARIABLE_DELETED_SUCCESS"), true);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("VARIABLE_DELETED_FAILED"), false);
		}
	}
	public function action_DeleteAllVariables()
	{
		$result = $this ->model -> deleteAllVariables();
		if ($result==true)
		{
			$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("VARIABLE_DELETED_SUCCESS"), false);
		}
		else
		{
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("VARIABLE_DELETED_FAILED"), false);
		}
	}
	public function action_LoadJoomlarules()
	{
		$result = $this ->model->loadJoomlarules();
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("The Variable has been restored successfully."), true);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("The Variable was restored unsuccessfully."), false);
		}
	}
	public function action_LoadWordpressrules()
	{
		$result = $this ->model->loadWordpressrules();
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("The Variable has been restored successfully."), true);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("The Variable was restored unsuccessfully."), false);
		}
	}
	public function action_LoadJSocialrules()
	{
		$result = $this ->model->loadJSocialrules();
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("The Variable has been restored successfully."), true);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("The Variable was restored unsuccessfully."), false);
		}
	}
	public function action_Clearvariables()
	{
		$result = $this ->model->clearvariables();
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("The Variable has been restored successfully."), true);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("The Variable was restored unsuccessfully."), false);
		}
	}
}			