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
require_once (OSE_FWCONTROLLERS. ODS. 'BaseController.php'); 
class EmailadminController extends BaseController {
	public $layout = '//layouts/grids';
	public function actionGetAdminEmailmap()
	{
		$results = $this->model -> getAdminEmailmap(); 
		oseAjax::returnJSON($results); 
	}
	public function actionGetAdminUsers()
	{
		$results = $this->model -> getAdminUsers(); 
		oseAjax::returnJSON($results);
	}
	public function actionGetEmailList()
	{
		$results = $this->model -> getEmailList(); 
		oseAjax::returnJSON($results);
	}
	public function actionAddadminemailmap()
	{
		oseFirewall::loadRequest (); 
		$userid= oRequest::getInt('useridfield');
		$emailid= oRequest::getInt('emailidfield');
		if (empty($userid) || empty($emailid))
		{
			return; 
		}
		$result = $this->model -> addadminemailmap($userid, $emailid);
		if (!empty($result))
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get('LINKAGE_ADDED_SUCCESS'), true);
		} 
		else
		{
				oseAjax::aJaxReturn(true, 'ERROR', oLang::_get('LINKAGE_ADDED_FAILED'), false);
		}
	}
	public function actionDeleteadminemailmap()
	{
		oseFirewall::loadRequest ();  
		$ids= stripslashes(oRequest::getVar('ids',null));
		$ids = oseJSON::decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this->model -> deleteadminemailmap($ids);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get('LINKAGE_DELETED_SUCCESS'), true);
		} 
		else
		{
				oseAjax::aJaxReturn(true, 'ERROR', oLang::_get('LINKAGE_DELETED_FAILED'), false);
		}
	}
}
?>	