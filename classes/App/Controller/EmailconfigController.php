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
class EmailconfigController extends BaseController {
	public $layout = '//layouts/grids';
	public function actionGetEmails()
	{
		$results = $this->model -> getEmails(); 
		oseAjax::returnJSON($results); 
	}
	public function actionGetEmailParams()
	{
		oseFirewall::loadRequest ();
		$id= oRequest::getInt('id', null);
		if (empty($id))
		{
			return; 
		}
		$model= $this->getModel('Emails');
		$results = $model -> getEmailParams($id); 
		oseAjax::returnJSON($results);
	}
	
	public function actionGetEmail()
	{
		oseFirewall::loadRequest (); 
		$id= oRequest::getInt('id');
		if (empty($id))
		{
			return; 
		}
		$results = $this -> model -> getEmail($id); 
		$return = array(); 
		$return['success'] = true;
		$return['data'] = $results['results'];
		oseAjax::returnJSON($return);
	}
	public function actionSaveemail()
	{
		oseFirewall::loadRequest (); 
		$id= oRequest::getInt('id', 0);
		$emailType= oRequest::getVar('emailType', null);
		$emailBody= oRequest::getHTML('emailBody', null);
		$emailSubject= oRequest::getVar('emailSubject', null);
		if (empty($id))
		{
			return; 
		}
		$result = $this-> model -> saveemail($id, $emailType, $emailBody, $emailSubject);
		if (!empty($result))
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get('EMAIL_TEMPLATE_UPDATED_SUCCESS'), false);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('EMAIL_TEMPLATE_UPDATED_FAILED'), false);
		}
	}
}
?>	