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
class DashboardController extends BaseController {
	public $layout = '//layouts/main';
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionCreateTables() {
		$this ->model->actionCreateTables();
	}
	public function actionChangeusername() {
		oseFirewall :: loadRequest();
		$username = oRequest :: getVar('username', null);
		if (empty($username))
		{
			oseAjax::aJaxReturn(true, 'ERROR', oLang::_get('USERNAME_CANNOT_EMPTY'), false);
		}
		else
		{
			$result = $this ->model->changeusername($username);
			if ($result == true)
			{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get('USERNAME_UPDATE_SUCCESS'), false);
			}
			else
			{
				oseAjax::aJaxReturn(true, 'ERROR', oLang::_get('USERNAME_UPDATE_FAILED'), false);
			}
		}
	}
	public function actionCheckSafebrowsing () {
		$model= $this->getModel();
		$result = $model -> checkSafebrowsing();
		print_r($result); exit;
	}
	public function actionUpdateSafebrowsingStatus() {
		oseFirewall :: loadRequest();
		$status = oRequest :: getVar('status', null);
		if (empty($status))
		{
			return; 
		}
		$model= $this->getModel();
		$result = $model -> updateSafebrowsingStatus($status);
		return $result; 
	}
}
?>	