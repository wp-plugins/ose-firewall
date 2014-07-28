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
require_once (OSE_FWCONTROLLERS . ODS . 'BaseController.php');
class VersionupdateController extends BaseController {
	public $layout = '//layouts/grids';
	public function actionCreateTables() {
		oseFirewall::loadRequest ();
		$this->model->actionCreateTables();
	}
	public function actionSaveUserInfo() {
		oseFirewall::loadRequest ();
		$type = oRequest :: getVar('type', null);
		if (empty($type)) {return;}
		$data = array();
		$data['Name'] = oRequest :: getVar('username', null);
		$data['Password'] = oRequest :: getVar('password', null);
		$model= $this->getModel('Configuration');
		$model ->saveConfiguration($type, $data);
	}
	
	public function actionChangeUserInfo() {
		oseFirewall::loadRequest ();
		$type = oRequest :: getVar('type', null);
		if (empty($type)) {return;}
		$data = array();
		$data['Name'] = oRequest :: getVar('username', null);
		$data['Password'] = oRequest :: getVar('password', null);
		$model= $this->getModel('Configuration');
		$model ->saveConfiguration($type, $data);
	}
}