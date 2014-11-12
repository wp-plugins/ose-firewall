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
class ManageipsController extends \App\Base {
	public function action_GetACLIPMap() {
		if (isset($_REQUEST['mobiledevice']))
		{
			$mobiledevice = $this->model->getInt('mobiledevice', 0);
		}
		else
		{
			$mobiledevice = 0;
		}
		$results = $this ->model->getACLIPMap();
		$this->model->returnJSON($results, $mobiledevice);   		 
	}
	public function action_GetLatestTraffic () {
		if (isset($_REQUEST['mobiledevice']))
		{
			$mobiledevice = $this->model->getInt('mobiledevice', 0);
		}
		else
		{
			$mobiledevice = 0;
		}
		$results = $this ->model->getLatestTraffic();
		$this->model->returnJSON($results, $mobiledevice);
	}
	public function action_ipform() {
		$this->view->subview = strtolower('ipform');
	}
	public function action_Addips() {
		$this->model->loadRequest();
		$ipmanager = $this->model->getFirewallIpManager ();
		$ip_start = $this->model->getVar('ip_start', null); 
		$ip_type =  $this->model->getVar('ip_type', null);
		$ip_status = $this->model->getInt('ip_status', 1);
		$title =  $this->model->getVar('title', 'Backend Added IP');
		if ($ip_type=='ip')
		{
			$ip_end = $this->model->getVar('ip_start', null);
		} 
		else
		{
			$ip_end = $this->model->getVar('ip_end', null);
		}
		if ((empty($ip_start) || $ip_start =='___.___.___.___')) {
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("IP_EMPTY"), false);
		}
		if ($ip_type =='ips' && (empty($ip_end) || $ip_end=='___.___.___.___')) {
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("IP_EMPTY"), false);
		} 
		$ipmanager->setIPRange($ip_start, $ip_end);
		$this->checkIPValidity($ipmanager); 
		$ipmanager->checkIPRangeStatus(); 
		$acl_id = $ipmanager->getACLID();
		if (!empty($acl_id))
		{
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("IP_RULE_EXISTS"), false);
		}
		else
		{
			$result = $this ->model -> addACLRule($title, $ip_start, $ip_end, $ip_type, $ip_status);
			if ($result==true)
			{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("IP_RULE_ADDED_SUCCESS"), false);
			} 
			else
			{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("IP_RULE_ADDED_FAILED"), false);
			}
		}
	}
	public function action_Removeips()
	{
		$this->model->loadRequest(); 
		$aclids= $this->model->getVar('ids', null);
		$aclids = $this->model->JSON_decode($aclids); 
		if (empty($aclids))
		{
			$this->model->showSelectionRequired ();
		}
		$result = $this ->model -> removeACLRule($aclids);
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("IP_RULE_DELETE_SUCCESS"), false);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("IP_RULE_DELETE_FAILED"), false);
		}
	}
	public function action_removeAllIPs() 
	{
		$result = $this ->model -> removeAllACLRule();
		if ($result==true)
		{
			$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("IP_RULE_DELETE_SUCCESS"), false);
		}
		else
		{
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("IP_RULE_DELETE_FAILED"), false);
		}
	}
	public function action_BlacklistIP()
	{
		$this->changeACLStatus(1);
	}
	public function action_WhitelistIP()
	{
		$this->changeACLStatus(3);
	}
	public function action_MonitorIP()
	{
		$this->changeACLStatus(2);
	}
	private function changeACLStatus($status)
	{
		$this->model->loadRequest(); 
		$aclids = $this->model->getVar('ids', null);
		$aclids = $this->model->JSON_decode($aclids); 
		if (empty($aclids))
		{
			$this->model->showSelectionRequired ();
		}
		$result = $this ->model -> changeACLStatus($aclids, $status);
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("IP_RULE_CHANGED_SUCCESS"), false);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("IP_RULE_CHANGED_FAILED"), false);
		}
	}
	public function action_ChangeIPStatus()
	{
		$this->model->loadRequest(); 
		$aclid= $this->model->getInt('id', 0);
		$status= $this->model->getInt('status', 0);
		if (empty($aclid) || empty($status))
		{
			$this->model->showSelectionRequired (); 
		}
		$result = $this ->model -> changeACLStatus(array($aclid), $status);
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("The Access Control Rules for this IP / IP Range has been changed successfully."), false);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("The Access Control Rules for this IP / IP Range has been changed unsuccessfully."), false);
		}
	}
	public function action_UpdateHost()
	{
		$this->model->loadRequest();
		$aclids= $this->model->getVar('ids', null);
		$aclids = $this->model->JSON_decode($aclids);
		if (empty($aclids))
		{
			$this->model->showSelectionRequired ();
		}
		$result = $this ->model -> updateHost($aclids);
		if ($result==true)
		{
				$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("The Host for this IP / IP Range was updated successfully."), false);
		} 
		else
		{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("The Host for this IP / IP Range was updated unsuccessfully."), false);
		}
	}
	public function action_ViewAttack()
	{
		$this->model->loadRequest(); 
		$aclid= $this->model->getInt('id');
		$return = array();
		$return['id'] = 1; 
		$return['result'] = $this ->model->getAttackDetail($aclid);
		$return['status'] = $this->model->getLang("OSE_SCAN_ACTIVITY");
		$this->model->returnJSON($return); 
	}
	private function checkIPValidity($ipmanager)
	{
		$result =$ipmanager -> checkIPValidity(true);
		if ($result[0]==false)
		{
			$this->model->aJaxReturn(false, 'ERROR', $result[1], false);
		}
		$result =$ipmanager -> checkIPValidity(false);
		if ($result[0]==false)
		{
			$this->model->aJaxReturn(false, 'ERROR', $result[1], false);
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
	public function action_Importcsv () {
		if (empty($_FILES))
		{
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("Please upload a CSV file, there is no files uploaded."), false);
		}
		else
		{	
			$file = $_FILES['csvfile'];
			if ($file['type']!='text/csv')
			{
				$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("Please upload CSV files, file types apart from the CSV is not accepted."), false);
			}
			else
			{
				$result = $this->model->importcsv($file);
				if ($result==true)
				{
					$this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("The IPs were imported successfully"), false);
				} 
				else
				{
					$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("The IPs were imported unsuccessfully."), false);
				}
			}
		}
	}
	public function action_Exportcsv () {
		$result = $this->model->exportcsv();
	}
	public function action_Downloadcsv () {
		$this->model->loadRequest();
		$filename = $this->model->getVar ('filename', null);
		if (empty($filename))
		{
			$this->model->aJaxReturn(false, 'ERROR', 'Invalid file name', false);
		}
		else
		{
			$result = $this->model->downloadcsv($filename);	
		}
	}
}
?>	