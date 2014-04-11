<?php
/**
 * @version     6.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Open Source Excellence CPU
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 30-Sep-2010
 * @author        Updated on 30-Mar-2013 
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @copyright Copyright (C) 2008 - 2010- ... Open Source Excellence
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
*/
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH')) {
	die('Direct Access Not Allowed');
}
require_once (OSE_FWFRAMEWORK . ODS. 'firewallstat.php'. ODS. 'firewallStat.php');
class oseFirewallStatPro extends oseFirewallStat {
public function getSignatures()
	{
		$limit = oRequest::getInt('limit', 15);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		$status = oRequest::getInt('status', null);
		$start = $limit * ($page-1);  
		return $this->convertSignatures($this->getSiganturesDB($search, $status, $start, $limit));
	}
	private function getSiganturesDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO ();
		$where = array(); 
		if (!empty($search))
		{
			$where[] = "`signature` LIKE ".$db->quoteValue($search.'%', true);
		}
		if ($status===1 || $status===0)
		{
			$where[] = "`action` = ".(int)$status;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_signatures`".$where
				 ." ORDER BY id ASC LIMIT ".$start.", ".$limit;
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	private function convertSignatures($results)
	{
		$i = 0;
		foreach ($results as $result)
		{
			$results[$i]->signature= htmlspecialchars($results[$i]->signature);
			$results[$i]->action = $this->getActionIcon($results[$i]->id, $results[$i]->action);
			$i ++; 
		}
		return $results; 
	}
	public function getSignaturesTotal()
	{
		$db = oseFirewall::getDBO ();
		return $db->getTotalNumber('id', '#__osefirewall_signatures');
	}
	public function changeL1RuleStatus($id, $status)
	{
		$db = oseFirewall::getDBO ();
		$varValues = array (
				'action' => (int)$status
		);
		return $db->addData('update', '#__osefirewall_signatures', 'id', (int)$id, $varValues);
	}
	public function addsignature($signature, $status)
	{
		$db = oseFirewall::getDBO ();
		$varValues = array(
					'id' => 'DEFAULT',
					'signature' => $signature,
					'action' => (int)$status,
					'attacktype' => '["1"]'
				);
		$id = $db->addData ('insert', '#__osefirewall_signatures', '', '', $varValues);
		return $id; 
	}
	public function deletesignature($id)
	{
		$detattacktype_ids = $this->getDetattacktypeIDByRuleID($id, array(1));
		$result = $this->deleteDectectedAttacks($detattacktype_ids);	
		if ($result==true)
		{	
			$result = $this->deleteSignaturebyID($id);
		}
		return $result; 
	}
	private function deleteSignaturebyID($id)
	{
		$db = oseFirewall::getDBO ();
		return $db->deleteRecord(array('id'=>$id), '#__osefirewall_signatures');
	}
	public function restoreRules($type)
	{
		switch($type)
		{
			case 'signature':
				$this->restoreSignatureDatabase();
			break;
			case 'filters':
				$this->restoreFilterDatabase();
			break;
		}
	}
	private function restoreSignatureDatabase()
	{
		$result = $this->cleanSignatureDatabase('signature');
		if ($result ==true)
		{
			$db = oseFirewall::getDBO ();
			$query = "INSERT INTO `#__osefirewall_signatures` SELECT * FROM `#__osefirewall_signatures_bk`";
			$db->setQuery($query); 
			return $db->query();
		}
		else
		{
			return false; 
		}
	}
	private function restoreFilterDatabase()
	{
		$result = $this->cleanSignatureDatabase('filter');
		if ($result ==true)
		{
			$db = oseFirewall::getDBO ();
			$query = "INSERT INTO `#__osefirewall_filters` SELECT * FROM `#__osefirewall_filters_bk`";
			$db->setQuery($query); 
			return $db->query();
		}
		else
		{
			return false; 
		}
	}
	private function cleanSignatureDatabase($type)
	{
		$db = oseFirewall::getDBO ();
		if ($type=='signature')
		{
			$query = "TRUNCATE `#__osefirewall_signatures`";
		}
		elseif ($type=='filter')
		{
			$query = "TRUNCATE `#__osefirewall_filters`";
		}
		$db->setQuery($query); 
		return $db->query();
	}
	private function getRulesetsDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO ();
		$where = array(); 
		if (!empty($search))
		{
			$where[] = "`description` LIKE ".$db->quoteValue('%'.$search.'%', true);
		}
		if ($status===1 || $status===0)
		{
			$where[] = "`action` = ".(int)$status;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT `id`,`action`,`attacktype`,`impact`,`description` FROM `#__osefirewall_filters`".$where
				 ." ORDER BY id ASC LIMIT ".$start.", ".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList(); 
		return $results;
	}
	private function convertRulesets($results)
	{
		$i = 0;
		$attacktypes = $this->getAttackTypeArray();
		foreach ($results as $result)
		{
			$results[$i]->description=ucfirst($results[$i]->description); 
			$results[$i]->action = $this->getActionIcon($results[$i]->id, $results[$i]->action);
			$results[$i]->attacktype = $this->attackTypeDecode($attacktypes, $results[$i]->attacktype);
			$i ++; 
		}
		return $results; 
	}
	public function getRulesetsTotal()
	{
		$db = oseFirewall::getDBO ();
		return $db->getTotalNumber('id', '#__osefirewall_filters');
	}
	public function changeL2RuleStatus($id, $status)
	{
		$db = oseFirewall::getDBO ();
		$varValues = array (
				'action' => (int)$status
		);
		return $db->addData('update', '#__osefirewall_filters', 'id', (int)$id, $varValues);
		
	}
	public function addruleset($filter, $status, $impact, $description, $attacktype)
	{
		$db = oseFirewall::getDBO ();
		$varValues = array(
					'id' => 'DEFAULT',
					'filter' => $filter,
					'action' => (int)$status,
					'attacktype' => $this->attackTypeEncode($attacktype),
					'impact' => (int)$impact,
					'description' => (string)$description
				);
		$id = $db->addData ('insert', '#__osefirewall_filters', '', '', $varValues);
		return $id; 
	}
	public function deleteruleset($id)
	{
		$detattacktype_ids = $this->getDetattacktypeIDByRuleID($id, array(2,3,4,5,6,7,8,9,10,11,12));
		$result = $this->deleteDectectedAttacks($detattacktype_ids);	
		if ($result==true)
		{
			$result = $this->deleteFilterbyID($id);
		}
		return $result; 
	}
}