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
class ManageipsRemoteController extends BaseRemoteController
{
	public $layout = '//layouts/grids';
	public function actionGetACLIPMap() {
		oseFirewall::loadRequest ();
		if (isset($_REQUEST['mobiledevice']))
		{
			$mobiledevice = oRequest :: getInt('mobiledevice', 0);
		}
		else
		{
			$mobiledevice = 0;
		}
		$results = $this ->model->getACLIPMap();
		oseAjax::returnJSON($results, $mobiledevice);   		 
	}
	public function actionAddips() {
		oseFirewall::loadRequest ();
		$db = oseFirewall::getDBO();  
		$ipmanager = new oseFirewallIpManager($db);
		$ip_start = oRequest :: getVar('ip_start', null); 
		$ip_type =  oRequest :: getVar('ip_type', null);
		$ip_status = oRequest ::getInt('ip_status', 1);
		$title =  oRequest :: getVar('title', 'Backend Added IP');
		if ($ip_type=='ip')
		{
			$ip_end = oRequest :: getVar('ip_start', null);
		} 
		else
		{
			$ip_end = oRequest :: getVar('ip_end', null);
		}
		$ipmanager->setIPRange($ip_start, $ip_end);
		$this->checkIPValidity($ipmanager); 
		$ipmanager->checkIPRangeStatus(); 
		$acl_id = $ipmanager->getACLID();
		if (!empty($acl_id))
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("IP_RULE_EXISTS"), false);
		}
		else
		{
			$result = $this ->model -> addACLRule($title, $ip_start, $ip_end, $ip_type, $ip_status);
			if ($result==true)
			{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("IP_RULE_ADDED_SUCCESS"), false);
			} 
			else
			{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("IP_RULE_ADDED_FAILED"), false);
			}
		}
	}
	public function actionRemoveips()
	{
		oseFirewall::loadRequest (); 
		$aclids= oRequest :: getVar('ids', null);
		$aclids = oseJSON :: decode($aclids); 
		$result = $this ->model -> removeACLRule($aclids);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("IP_RULE_DELETE_SUCCESS"), false);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("IP_RULE_DELETE_FAILED"), false);
		}
	}
	public function actionBlacklistIP()
	{
		$this->changeACLStatus(1);
	}
	public function actionWhitelistIP()
	{
		$this->changeACLStatus(3);
	}
	public function actionMonitorIP()
	{
		$this->changeACLStatus(2);
	}
	private function changeACLStatus($status)
	{
		oseFirewall::loadRequest (); 
		$aclids = oRequest::getVar('ids', null);
		$aclids = oseJSON::decode($aclids); 
		if (empty($aclids))
		{
			return; 
		}
		$result = $this ->model -> changeACLStatus($aclids, $status);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("IP_RULE_CHANGED_SUCCESS"), false);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("IP_RULE_CHANGED_FAILED"), false);
		}
	}
	public function actionChangeIPStatus()
	{
		oseFirewall::loadRequest (); 
		$aclid= oRequest::getInt('id', 0);
		$status= oRequest::getInt('status', 0);
		if (empty($aclid) || empty($status))
		{
			return; 
		}
		$result = $this ->model -> changeACLStatus(array($aclid), $status);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Access Control Rules for this IP / IP Range has been changed successfully."), false);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Access Control Rules for this IP / IP Range has been changed unsuccessfully."), false);
		}
	}
	
	public function actionRefreshStat(){
		oseFirewall::loadRequest ();
		$return = array();
		$return['results'] = $this ->model->getStatistics();
		oseAjax::returnJSON($return);
	}
	
	public function actionUpdateHost()
	{
		oseFirewall::loadRequest (); 
		$aclids= oRequest::getVar('ids', null);
		$aclids = oseJSON::decode($aclids); 
		$result = $this ->model -> updateHost($aclids);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Host for this IP / IP Range was updated successfully."), false);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Host for this IP / IP Range was updated unsuccessfully."), false);
		}
	}
	public function actionViewAttack()
	{
		oseFirewall::loadRequest (); 
		$aclid= oRequest::getInt('id');
		$return = array();
		$return['id'] = 1; 
		$return['results'] = $this ->model->getAttackDetail($aclid);
		oseAjax::returnJSON($return); 
	}
	private function checkIPValidity($ipmanager)
	{
		$result =$ipmanager -> checkIPValidity(true);
		if ($result[0]==false)
		{
			oseAjax::aJaxReturn(false, 'ERROR', $result[1], false);
		}
		$result =$ipmanager -> checkIPValidity(false);
		if ($result[0]==false)
		{
			oseAjax::aJaxReturn(false, 'ERROR', $result[1], false);
		}
	}
	private function getScore ($aclid)
	{
		$db = OSEDBO::instance(); 
		$query = "SELECT `score` FROM `#__oseath_alerts` WHERE `aclid` = ". (int)$aclid;
		$db->setQuery($query); 
		$score = $db->loadResult(); 
		return ($score['score'])?(int)$score['score']:false;   
	}
}
?>