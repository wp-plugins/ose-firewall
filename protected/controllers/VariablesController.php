<?php
/**
* @version     2.0 +
* @package       Open Source Excellence Security Suite
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
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once (OSE_FWCONTROLLERS. ODS. 'BaseController.php'); 
class VariablesController extends BaseController {
	public $layout = '//layouts/grids';
	public function actionGetVariables() {
		$results = $this ->model ->getVariables();
		oseAjax::returnJSON($results); 
	}
	public function actionChangeVarStatus()
	{
		oseFirewall::loadRequest (); 
		$id= oRequest ::getInt('id', null);
		$status= oRequest ::getInt('status', null);
		if (empty($id) || (!in_array($status, array(1,2,3))))
		{
			return; 
		}
		$result = $this ->model -> changeVarStatus(array($id), $status);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable rule has been changed successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(true, 'ERROR', oLang::_get("The Variable rule was changed unsuccessfully."), false);
		}
	}
	public function actionAddvariables()
	{
		oseFirewall::loadRequest (); 
		$requesttype= oRequest :: getVar('requesttype',null);
		$variable= oRequest :: getVar('variablefield',null);
		$status= oRequest :: getInt('statusfield',null);
		if (empty($variable) || (!in_array($status, array(1,2,3))))
		{
			return; 
		}
		$variable = $requesttype.'.'.$variable;
		$result = $this ->model -> addvariables($variable, $status);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable rule has been changed successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable rule was changed unsuccessfully."), false);
		}
	}
	public function actionDeletevariable()
	{
		oseFirewall::loadRequest (); 
		$ids= oRequest :: getVar('ids',null);
		$ids = oseJSON :: decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this ->model -> deletevariable($ids);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been changed successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was changed unsuccessfully."), false);
		}
	}
	public function actionLoadJoomlarules()
	{
		$result = $this ->model -> loadJoomlarules();
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	public function actionLoadWordpressrules()
	{
		$result = $this ->model -> loadWordpressrules();
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	public function actionLoadJSocialrules()
	{
		$result = $this ->model -> loadJSocialrules();
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	public function actionClearvariables()
	{
		$result = $this ->model -> clearvariables();
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
}			