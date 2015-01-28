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
class DashboardController extends \App\Base {
	public function action_CheckSafebrowsing() {
		$model = $this->getModel ();
		$result = $model->checkSafebrowsing ();
		print_r ( $result );
		exit ();
	}
	public function action_UpdateSafebrowsingStatus() {
		oseFirewall::loadRequest ();
		$status = oRequest::getVar ( 'status', null );
		if (empty ( $status )) {
			return;
		}
		$model = $this->getModel ();
		$result = $model->updateSafebrowsingStatus ( $status );
		oseAjax::aJaxReturn ( true, 'SUCCESS', 'Status updated successfully', false );
	}
	public function action_getCountryStat() {
		$data = $this->model->getCountryStat();
		$this->model->returnJSON($data);
	}
	public function action_getTrafficData() {
		$data = $this->model->getTrafficData();
		$this->model->returnJSON($data);
	}
	public function action_checkWebBrowsingStatus() {
		$data = $this->model->checkWebBrowsingStatus();
		$this->model->returnJSON($data);
	}
}