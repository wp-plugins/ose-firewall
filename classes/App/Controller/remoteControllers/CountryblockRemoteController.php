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
class CountryblockRemoteController extends BaseRemoteController
{
	public $layout = '//layouts/grids';
	public function actionBlacklistIP()
	{
		$this->changeCountryStatus(1);
	}
	public function actionWhitelistIP()
	{
		$this->changeCountryStatus(3);
	}
	private function changeCountryStatus($status)
	{
		oseFirewall::loadRequest();
		$aclids = oRequest::getVar('ids', null);
		$aclids = oseJSON::decode($aclids);
		$this->changeStatus($aclids, $status);
	}
	private function changeStatus($aclids, $status)
	{
		if (empty($aclids) || empty($status))
		{
			return;
		}
		$result = $this->model ->changeCountryStatus($aclids, $status);
		if ($result == true)
		{
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("IP_RULE_CHANGED_SUCCESS"), false);
		}
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("IP_RULE_CHANGED_FAILED"), false);
		}
	}
	public function actionCreateTables()
	{
		$this->model->createTables();
	}
	public function actionDownLoadTables()
	{
		oseFirewall::loadRequest();
		$step = oRequest::getInt('step');
		$results = $this->model->downloadTables($step);
		oseAjax::returnJSON($results);
	}
	public function actionGetCountryList()
	{
		oseFirewall::loadRequest();
		if (isset($_REQUEST['mobiledevice']))
		{
			$mobiledevice = oRequest::getInt('mobiledevice', 0);
		}
		else
		{
			$mobiledevice = 0;
		}
		$results = $this->model->getCountryList();
		oseAjax::returnJSON($results, $mobiledevice);
	}
	
	public function actionRefreshStat()
	{
		oseFirewall::loadRequest ();
		$return = array();
		$return['results'] = $this ->model->getStatistics();
		oseAjax::returnJSON($return);
	}
}
?>