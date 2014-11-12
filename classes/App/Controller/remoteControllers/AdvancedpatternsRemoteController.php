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
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
 die('Direct Access Not Allowed');
}
require_once(OSE_FWRECONTROLLERS.ODS.'BaseRemoteController.php');
require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
class AdvancedpatternsRemoteController extends BaseRemoteController
{
	public $layout = '//layouts/grids';
	public function actionGetRulesets()
	{
		$results = $this->model->getRulesets();
		oseAjax::returnJSON($results);
	}
	public function actionChangeRuleStatus()
	{
		oseFirewall::loadRequest();
		$id = oRequest::getInt('id', null);
		$status = oRequest::getInt('status', null);
		if (empty($id) || ($status != 0 && $status != 1))
		{
			return;
		}
		$model = $this->getModel();
		$result = $model->changeRuleStatus(array($id), $status);
		if ($result == true)
		{
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get('ITEM_STATUS_CHANGED_SUCCESS'), FALSE);
		}
		else
		{
			oseAjax::aJaxReturn(true, 'ERROR', oLang::_get('ITEM_STATUS_CHANGED_FAILED'), false);
		}
	}
	
	
	public function actionRefreshStat(){
		oseFirewall::loadRequest ();
		$return = array();
		$return['results'] = $this ->model->getVersion();
		oseAjax::returnJSON($return);
	}
	
}
?>