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
class oseFirewallStatBase
{
	protected $db = null;
	protected $country_ready = false;
	protected $where = array (); 
	protected $orderBy = ' ';
	protected $limitStm = ' ';
	public function __construct()
	{
		$this->setDBO ();
		oseFirewall::callLibClass('convertviews', 'convertviews');
		oseFirewall::loadRequest();
		$this->country_ready = oseFirewall::isGeoDBReady();	
	}
	protected function setDBO () {
		$this->db = oseFirewall::getDBO();
	}
	public function getAttackSummary()
	{
		return $this->convertAttackSummary($this->getAttackSummaryDB());
	}
	private function getAttackSummaryDB()
	{
		$attrList = array("COUNT(`acl`.`id`) as `count`", "`acl`.`datetime` as `date`", " `attacktype`.`id` as `attacktypeid`");
		$sql = convertViews::convertAttackTypesum($attrList);
		$query = $sql."WHERE DATEDIFF( NOW(), datetime ) <= 10 AND `acl`.`status` IN (1,2)  "."GROUP BY DATE(datetime), attacktypeid ";
		$this->db->setQuery($query);
		$result = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $result;
	}
	private function convertAttackSummary($results)
	{
		$return = array();
		$i = 0;
		foreach ($results as $result)
		{
			if (!isset($return[$i]['date']))
			{
				$return[$i]['date'] = $result->date;
			}
			if ($result->date != $return[$i]['date'])
			{
				$i++;
				$return[$i]['date'] = $result->date;
			}
			$result->attacktypeid = (!empty($result->attacktypeid)) ? $result->attacktypeid : 0;
			$return[$i]['type'.$result->attacktypeid] = (int) $result->count;
		}
		return $return;
	}
	private function getAttackTypeArray()
	{
		$results = $this->getAttackTypesDB();
		$return = array();
		foreach ($results as $result)
		{
			$return[$result->id] = $result->name;
		}
		return $return;
	}
	private function getAttackTypesDB($ids = array())
	{
		$where = array();
		if (!empty($ids))
		{
			$where[] = "`id` NOT IN (".implode(",", $ids).")";
		}
		$where = $this->db->implodeWhere($where);
		$query = "SELECT `id`, `name` FROM `#__osefirewall_attacktype` ".$where;
		$this->db->setQuery($query);
		$result = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $result;
	}
	public function getAttackTypes($ids)
	{
		return $this->getAttackTypesDB($ids);
	}
	public function getAdvanceRulesVersion()
	{
		$query = "SELECT number, type FROM `#__osefirewall_versions` WHERE `type` = 'ath'";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $this->convertAdvanceRulesStatistic($results);
	}
	public function getAdvancePatternsVersion()
	{
		$query = "SELECT number, type FROM `#__osefirewall_versions` WHERE (`type` = 'avs' OR `type` = 'bsav') ORDER BY `number` DESC LIMIT 1";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $this->convertAdvanceRulesStatistic($results);
	}
	public function getAdvanceRulesStatistic()
	{
		$query = "SELECT action as status, count(action) as number FROM `#__osefirewall_advancerules` group by action";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $this->convertBasicRulesStatistic($results);
	}
	public function convertAdvanceRulesStatistic($results)
	{
		$i = 0;
		$return = array();
		foreach ($results as $result)
		{
			if (!empty($results[$i]->number))
			{
				$return['version'] = $results[$i]->number;
			}
			if (!empty($results[$i]->type))
			{
				$return['type'] = $results[$i]->type;
			}
			$i++;
		}
		return $return;
	}
	public function getBasicRulesStatistic()
	{
		$query = "SELECT action as status, count(action) as number FROM `#__osefirewall_basicrules` group by action";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $this->convertBasicRulesStatistic($results);
	}
	public function convertBasicRulesStatistic($results)
	{
		$i = 0;
		$return = array();
		foreach ($results as $result)
		{
			if (isset($results[$i]->status) && !empty($results[$i]->status))
			{
				switch ($results[$i]->status)
				{
				case 1:
					$return['active'] = $results[$i]->number;
					break;
				case 0:
					$return['inactive'] = $results[$i]->number;
				}
			}
			$i++;
		}
		return $return;
	}
	public function getVarStatistic()
	{
		
		$query = "SELECT status, count(status) as number FROM `#__osefirewall_vars` group by status;";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $this->convertVarStatistic($results);
	}
	public function convertVarStatistic($results)
	{
		$i = 0;
		$return = array();
		foreach ($results as $result)
		{
			if (isset($results[$i]->status) && !empty($results[$i]->status))
			{
				switch ($results[$i]->status)
				{
				case 1:
					$return['blacklisted'] = $results[$i]->number;
					break;
				case 2:
					$return['filtered'] = $results[$i]->number;
					break;
				case 3:
					$return['whitelisted'] = $results[$i]->number;
					break;
				}
			}
			$i++;
		}
		return $return;
	}
	public function getACLIPStatistic()
	{
		$query = "SELECT status, count(status) as number FROM `#__osefirewall_acl` group by status;";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $this->convertStatistic($results);
	}
	public function convertStatistic($results)
	{
		$i = 0;
		$return = array();
		foreach ($results as $result)
		{
			if (isset($results[$i]->status) && !empty($results[$i]->status))
			{
				switch ($results[$i]->status)
				{
				case 1:
					$return['blacklisted'] = $results[$i]->number;
					break;
				case 2:
					$return['monitored'] = $results[$i]->number;
					break;
				case 3:
					$return['whitelisted'] = $results[$i]->number;
					break;
				}
			}
			$i++;
		}
		return $return;
	}
	public function getACLIPMap()
	{
		$columns = oRequest::getVar('columns', null);
		$limit = oRequest::getInt('length', 15);
		$start = oRequest::getInt('start', 0);
		$search = oRequest::getVar('search', null);
		$orderArr = oRequest::getVar('order', null);
		$sortby = null;
		$orderDir = 'asc';
		if (!empty($columns[7]['search']['value']))
		{
			$status = $columns[7]['search']['value'];
		}
		else
		{
			$status = null;
		}
        if (!empty($columns[6]['search']['value'])) {
            $variable = $columns[6]['search']['value'];
        } else {
            $variable = null;
        }
		if (!empty($orderArr[0]['column'])) 
		{
			$sortby = $columns[$orderArr[0]['column']]['data'];
			$orderDir = $orderArr[0]['dir'];
		}
        $return = $this->getACLIPMapDB($search['value'], $status, $variable, $start, $limit, $sortby, $orderDir);
		$return['data'] = $this->convertACLIPMap($return['data']);
		return $return;
	}
	public function getLatestTraffic () {
		$limit = 5;
		$start = 0;
		$sortby = null;
		$orderDir = 'asc';
		$status = -1;
		$return = $this->getACLIPMapDB(null, null, null, 0, 5, 'datetime', 'desc');
		$return['data'] = $this->convertACLIPMap($return['data']);
		return $return;
	}
	protected function getWhereName ($search) {
		$this->where[] = "`name` LIKE ".$this->db->quoteValue($search.'%', true)." OR `ip32_start` = ".$this->db->quoteValue(ip2long($search), true);
	}
	protected function getWhereStatus ($status) {
		if ($status == 2)
		{
            $this->where[] = "`acl`.`status` = " . (int)$status . " or `status` = " . (int)0;
		}
		else
		{
            $this->where[] = "`acl`.`status` = " . (int)$status;
		}
	}

    protected function getWhereVarible($variable)
    {
        if ($variable !== 'null') {
            $this->where[] = "`vars`.`keyname` = '" . $variable . "'";
        } else {
            $this->where[] = "`vars`.`keyname` IS NULL";
        }
    }
	protected function getOrderBy ($sortby, $orderDir) {
		if (empty($sortby))
		{	
			$this->orderBy= " ORDER BY datetime DESC";
		}
		else
		{	
			$this->orderBy= " ORDER BY ".addslashes($sortby).' '.addslashes($orderDir);
		}
	}
	protected function getLimitStm ($start, $limit) {
		if (!empty($limit))
		{
			$this->limitStm = " LIMIT ".(int)$start.", ".(int)$limit;
		}
	}
	private function getAllRecords ($where) {
		$attrList = array("`acl`.`id` AS `id`","`acl`.`country_code` AS `country_code`", "`acl`.`score`AS `score`", " `acl`.`name` AS `name`",
            "`ip`.`iptype` AS `iptype`", "`ip`.`ip32_start` AS `ip32_start`", "`vars`.`keyname` AS `keyname`", "`acl`.`status` AS `status`", "`acl`.`host` AS `host`", "`acl`.`datetime` AS `datetime`, `acl`.`visits` AS `visits`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql.$where.' GROUP BY `acl`.`id`, `vars`.`id` '.$this->orderBy." ".$this->limitStm;
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		return $results;		
	}
	private function getAllCounts($where) {
		$return = array();
		// Get total count
		$attrList = array("COUNT(`acl`.`id`) AS count");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql.$where.' GROUP BY `acl`.`id`, `vars`.`id` '.$this->orderBy." ";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$return['recordsFiltered'] = count($results);
		
		$query = $sql.' GROUP BY `acl`.`id`, `vars`.`id` '.$this->orderBy." ";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$return['recordsTotal'] = count($results);
		return $return;
	}

    private function getACLIPMapDB($search, $status, $variable, $start, $limit, $sortby, $orderDir)
	{
		$return = array (); 
		if (!empty($search)) {$this->getWhereName ($search);}
		if (!empty($status)) {$this->getWhereStatus ($status);}
        if (!empty($variable)) {
            $this->getWhereVarible($variable);
        }
		$this->getOrderBy ($sortby, $orderDir);
		if (!empty($limit)) {$this->getLimitStm ($start, $limit);}
		$where = $this->db->implodeWhere($this->where);
		// Get Records Query;
		$return['data'] = $this->getAllRecords ($where);
		$counts = $this->getAllCounts($where);
        $return['recordsTotal'] = (int)$counts['recordsTotal'];
        $return['recordsFiltered'] = (int)$counts['recordsFiltered'];
		return $return;
	}
    private function getTotalIP()
    {
        $db = oseFirewall::getDBO();
        $result = $db->getTotalNumber('id', '#__osefirewall_acl');
        $db->closeDBO();
        return $result;
    }
	private function convertACLIPMap($results)
	{
		$i = 0;
		$return = array (); 
		foreach ($results as $result)
		{
			$return[$i] = $result;
			if ($this->country_ready == true && (!isset($result->country_code) || empty($result->country_code)))
			{
				$return[$i]->country_code = $this->updateCountryCode($result->id, $result->ip32_start);
				$return[$i]->country_code = $this->getCountryImage($result->country_code);
			}
			else if ($this->country_ready == true && (!isset($result->country_code) || !empty($result->country_code)))
			{
				$return[$i]->country_code = $this->getCountryImage($result->country_code);
			}
			else
			{
				$return[$i]->country_code ='';
			}
			$return[$i]->ip32_start = long2ip((float) $result->ip32_start);
            $return[$i]->keyname = $result->keyname;
			if (empty($result->host))
			{
				//$return[$i]->host = $this->updateIPHost($result->id, $result->ip32_start);
			}
			$return[$i]->view = $this->getViewIcon($result->id);
			$return[$i]->statusraw = $result->status;
			$return[$i]->status = $this->getStatusIcon($result->id, $result->status);
			$return[$i]->checkbox = '';
			$i++;
		}
		return $return;
	}
	private function getViewIcon($id)
	{
        return "<a href='javascript:void(0);' title = 'View detail' onClick= 'viewIPdetail(" . urlencode($id) . ")' ><i class='im-dashboard'></i></a>";
	}
	private function getStatusIcon($id, $status)
	{
		switch ($status)
		{
		case '3':
            return "<a href='javascript:void(0);' title = 'WhiteList' onClick= 'changeItemStatus(" . urlencode($id) . ", 2)' ><div class='grid-accept'></div></a>";
			break;
		case '2':
		case '0':
            return "<a href='javascript:void(0);' title = 'Monitering' onClick= 'changeItemStatus(" . urlencode($id) . ", 1)' ><div class='grid-error'></div></a>";
			break;
		case '1':
            return "<a href='javascript:void(0);' title = 'BlackList' onClick= 'changeItemStatus(" . urlencode($id) . ", 3)' ><div class='grid-block'></div></a>";
			break;
		default:
			return '';
			break;
		}
	}
	public function updateHost($acl_id)
	{
		$aclinfo = $this->getACLIPMapByIDDB($acl_id);
		$ip_start = long2ip($aclinfo->ip32_start);
		$result = $this->updateIPHost($acl_id, $ip_start);
		if ($result == false)
		{
			return false;
		}
		return true;
	}
	private function updateIPHost($acl_id, $ip_start)
	{
		$host = $this->get_host($ip_start);
		
		$varValues = array(
				'host' => $host
		);
		$result = $this->db->addData('update', '#__osefirewall_acl', 'id', $acl_id, $varValues);
		$this->db->closeDBO ();
		if ($result == true)
		{
			return $host;
		}
		else
		{
			return false;
		}
	}
	private function get_host($ip)
	{
		if (empty($ip))
		{
			return 'N/A';
		}
		$ptr = implode(".", array_reverse(explode(".", $ip))).".in-addr.arpa";
		$host = dns_get_record($ptr, DNS_PTR);
		if ($host == null)
		{
			return $ip;
		}
		else
		{
			return $host[0]['target'];
		}
	}
	private function getCountryImage($country_code)
	{
		if (empty($country_code) || $country_code =='--')
		{
			return '';
		}
		else
		{
			return "<img src='".OSE_FWPUBLICURL."/images/flags/".strtolower($country_code).".png' alt='".$country_code."' />";
		}
	}
	private function getCountryCodebyIP($ip32_start)
	{
		$query = " SELECT `country_code` FROM `#__ose_app_geoip` ".
				 " WHERE `ip32_start`<= ".$this->db->QuoteValue($ip32_start)." AND ".$this->db->QuoteValue($ip32_start)." <= `ip32_end`;";
		$this->db->setQuery($query);
		$country = $this->db->loadResult();
		$this->db->closeDBO(); 
		$country  = (isset($country['country_code']))?strtolower($country['country_code']):''; 
		return $country;
	}
	private function updateCountryCode($acl_id, $ip32_start)
	{
		$country_code = $this->getCountryCodebyIP($ip32_start);
		if (empty($country_code)) {
			$country_code = '--';
		}
		$varValues = array(
			'country_code' => $country_code
		);
		$result = $this->db->addData('update', '#__osefirewall_acl', 'id', (int) $acl_id, $varValues);
		$this->db->closeDBO ();
		if ($result == true)
		{
			return $country_code;
		}
		else
		{
			return false;
		}
	}
	public function removeACLRule($aclid)
	{
		$ids = $this->getIDSOnACLID($aclid);

		if (!empty($ids['detattacktype_id']))
		{
			$result = $this->deleteAttackTypeID($aclid, $ids['detattacktype_id']);
			if ($result == false)
			{
				return false;
			}
		}
		if (!empty($ids['ipid']))
		{
			$result = $this->deleteIPID($aclid, $ids['ipid']);
			if ($result == false)
			{
				return false;
			}
		}
		if (!empty($ids['aclid']))
		{
            $this->deleteIPtableID($aclid);
            $result = $this->deleteACLID($aclid);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}

    private function deleteIPtableID($aclid)
    {
        $result = $this->db->deleteRecord(array('id' => $aclid), '#__osefirewall_iptable');
        $this->db->closeDBO();
        return $result;
    }
	public function removeAllACLRule () {
		$result = true;
		$result = $this->db->truncateTable('#__osefirewall_detected');
		$result = $this->db->truncateTable('#__osefirewall_detcontdetail');
		$result = $this->db->truncateTable('#__osefirewall_detattacktype');
		$result = $this->db->truncateTable('#__osefirewall_iptable');
		$result = $this->db->truncateTable('#__osefirewall_acl');
		return $result;
	}
	private function deleteACLID($aclid)
	{
		$result = $this->db->deleteRecord(array('id' => $aclid), '#__osefirewall_acl');
		$this->db->closeDBO ();
		return $result;
	}
	private function deleteIPID($aclid, $ipid)
	{
		
		$result = $this->db->deleteRecord(array('id' => $ipid, 'acl_id' => $aclid), '#__osefirewall_iptable');
		$this->db->closeDBO ();
		return $result;
	}
	private function deleteAttackTypeID($aclid, $ids)
	{
		foreach ($ids as $detattacktype_id)
		{
			$result = $this->deleteAttackTypeIDDB($aclid, $detattacktype_id);
			if ($result == false)
			{
				return false;
				break;
			}
		}
		return true;
	}
	private function deleteAttackTypeIDDB($aclid, $orgdetattacktype_id)
	{
		$detattacktype_id = $orgdetattacktype_id ['detattacktype_id'];
		$result = $this->db->deleteRecord(array('acl_id' => $aclid, 'detattacktype_id' => $detattacktype_id), '#__osefirewall_detected');
		if ($result == true)
		{
			$result = $this->db->deleteRecord(array('detattacktype_id' => $detattacktype_id), '#__osefirewall_detcontdetail');
			if ($result == true)
			{
				$result = $this->db->deleteRecord(array('id' => $detattacktype_id), '#__osefirewall_detattacktype');
			}
		}
		$this->db->closeDBO ();
		return $result;
	}
	private function getIDSOnACLID($aclid)
	{
		$return = array();
		$return['aclid'] = $aclid;
		$return['ipid'] = $this->getIPIDOnAclidDB($aclid);
		$return['detattacktype_id'] = $this->getDetAttackIDOnAclidDB($aclid);
		return $return;
	}
	private function getIPIDOnAclidDB($aclid)
	{
		$attrList = array("`ip`.`id` AS `ipid`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql."WHERE `acl`.`id` = ".(int) $aclid;
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		$this->db->closeDBO ();
		return (isset($result['ipid'])) ? $result['ipid'] : false;
	}
	private function getDetAttackIDOnAclidDB($aclid)
	{
		$query = "SELECT `detattacktype_id` FROM `#__osefirewall_detected` WHERE `acl_id` = ".(int) $aclid;
		$this->db->setQuery($query);
		$results = $this->db->loadArrayList('detattacktype_id');
		$this->db->closeDBO ();
		return $results;
	}
	public function changeACLStatus($aclid, $status)
	{
		
		$varValues = array(
			'status' => (int) $status
		);
		$result = $this->db->addData('update', '#__osefirewall_acl', 'id', (int) $aclid, $varValues);
		$this->db->closeDBO ();
		return $result;
	}
	public function getAttackDetail($aclid)
	{
		$aclrule = $this->getACLIPMapByIDDB($aclid);
		$html = "<div width='100%' class='form-horizontal group-border stripped'>";
		if (empty($aclrule))
		{
			$html .= "<div class='form-group'><label class='col-sm-3 control-label'>".oLang::_get('Result')."</label><div class='col-sm-9'>".oLang::_get("No attack information found")."</div></div>";
		}
		else
		{
			$aclattackmap = $this->getACLAttackMapByIDDB($aclid);
			$html .= "<div class='form-group'><label class='col-sm-3 control-label'>".oLang::_get('IP Access Rule ID')."</label><div class='col-sm-9'>".$aclrule->id."</div></div>";
			$html .= "<div class='form-group'><label class='col-sm-3 control-label'>".oLang::_get('Country')."</label><div class='col-sm-9'>".$this->getCountryImage($aclrule->country_code)."</div></div>";
			$html .= "<div class='form-group'><label class='col-sm-3 control-label'>".oLang::_get('Logged Time')."</label><div class='col-sm-9'>".$aclrule->datetime."</div></div>";
			$html .= "<div class='form-group'><label class='col-sm-3 control-label'>".oLang::_get('Referer')."</label><div class='col-sm-9'>".$this->getRefererByIDDB($aclrule->referers_id)."</div></div>";
			$html .= "<div class='form-group'><label class='col-sm-3 control-label'>".oLang::_get('Target')."</label><div class='col-sm-9'>".$this->getPageByIDDB($aclrule->pages_id)."</div></div>";
			$html .= "<div class='form-group'><label class='col-sm-3 control-label'>---Attack detection---</label></div>";
			if (!empty($aclattackmap))
			{
				$tmp = null;
				$attackTypeArray = array();
				foreach ($aclattackmap as $item)
				{
					//need to be test
					if (!isset($detcontent_id) || $item->attacktypeid == 1 || $item->attacktypeid == 11)
					{
						//$detcontent_id = $item->detcontent_id;
					}
					if (!isset($tag))
					{
						$tag = $item->tag;
					}
					if (!isset($content) || $item->attacktypeid == 1 || $item->attacktypeid == 11)
					{
						$content = $item->content;
					}
					if ($item->attacktypeid == 1 || $item->attacktypeid == 11 || (!isset($detcontent_id)) || $detcontent_id != $item->detcontent_id )
					{
						if (!empty($attackTypeArray))
						{
							$html .= $this->printAttackType($attackTypeArray);
							$attackTypeArray = array();
						}
						if ($item->attacktypeid == 1 || $item->attacktypeid == 11)
						{
							if ( $item->rule_id == 10 || $item->rule_id == 1 )  {
								$detcontent_id == $item->detcontent_id;
								$html .= "<div class='form-group'><label class='col-sm-3 control-label'>Attack Type ".$item->attacktypeid.' '.$item->name."</label><div class='col-sm-9'><span style='color:red;'></span></div></div>";
								if (!isset($var) || $var != $item->var_id)
								{
									if ($item->keyname=='server.HTTP_CLIENT_IP') {
										$keyname = 'IP Address';
									}
									else
									{
										$keyname = $item->keyname;
									}
									$html .= "<div class='form-group'><label class='col-sm-3 control-label'>Detected Variable:</label><div class='col-sm-9'><span style='color:red;'>".$keyname."</span></div></div>";
								}
								$detcontent_id = $item->detcontent_id;
								if ($item->attacktypeid == 11) {
									$tmp = json_decode(stripcslashes($item->content));
									$content = 'IP is found on <a href ="http://stopforumspam.com/ipcheck/'.$aclrule->name.'" target="_blank">StopForumSpam</a>';
								}
								else
								{
									$content = htmlentities($item->content);
								}
								$var = $item->var_id;
								$html .= "<div class='form-group'>";
								$html .= "<label class='col-sm-3 control-label'>Detected Content: </label><div class='col-sm-9'><span style='color:red;'>".$content."</span></div></div>";
							}
							else
							{
								$detcontent_id == $item->detcontent_id;
								$html .= "<label class='col-sm-3 control-label'>Attack Type ".$item->attacktypeid.' '.$item->name."</label>";
							}
						}
						else
						{
							if (!isset($var) || $var != $item->var_id)
							{
								$html .= "<div class='form-group'>";
								
								$html .= "<label class='col-sm-3 control-label'>Detected Variable:</label><div class='col-sm-9'><span style='color:red;'>".$item->keyname."</span></div></div>";
							}
							$detcontent_id = $item->detcontent_id;
							$content = $item->content;
							$var = $item->var_id;
							$html .= "<div class='form-group'>";
							$html .= "<label class='col-sm-3 control-label'>Detected Content: </label><div class='col-sm-9'><span style='color:red;'>".htmlentities($item->content)."</span></div></div>";
						}
						if (!isset($item->tag) || ($tag != $item->tag))
						{
							$attackTypeArray[] = $item->name;
							$tag = $item->tag;
						}
					}
					else if ($detcontent_id == $item->detcontent_id && ($tag != $item->tag))
					{
							$attackTypeArray[] = $item->name;
					}
					else
					{
						//$html .= "</div>";	
					}
				}
				$html .= $this->printAttackType($attackTypeArray);
			}
		}
		$html .= "</div>";
		return $html;
	}
	private function getACLAttackMapByIDDB($id)
	{
		$attrList = array('*');
		$sql = convertViews::convertAttackmap($attrList);
		$query = $sql." WHERE `acl`.`id` = ".(int) $id." ORDER BY `detcontdetail`.`var_id` ASC, `detcontdetail`.`detcontent_id` ASC";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $results;
	}
	private function getACLIPMapByIDDB($id)
	{
		$attrList = array("*");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql."WHERE `acl`.`id` = ".(int) $id;
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		$this->db->closeDBO ();
		return $result;
	}
	private function getRefererByIDDB($id)
	{
		$query = "SELECT `referer_url` FROM `#__osefirewall_referers` WHERE `id` =".(int) $id;
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		$this->db->closeDBO ();
		return $result['referer_url'];
	}
	private function getPageByIDDB($id)
	{
		$query = "SELECT `page_url` FROM `#__osefirewall_pages` WHERE `id` =".(int) $id;
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		$this->db->closeDBO ();
		return $result['page_url'];
	}
	private function printAttackType($attackTypeArray)
	{
		$html = '';
		if (!empty($attackTypeArray))
		{
			$html .= "<div class='form-group'><label class='col-sm-3 control-label'>AttackType:</label><div class='col-sm-9'>";
			foreach ($attackTypeArray as $type)
			{
				$html .= $type.",";
			}
			$html .= "</div>";
			$html .= "</div>";
		}
		return $html;
	}
	protected function getActionIcon($id, $status)
	{
		switch ($status)
		{
		case '0':
			return "<a href='#' title = 'Inactive' onClick= 'changeItemStatus(".urlencode($id).", 1)' ><div class='grid-block'></div></a>";
			break;
		case '1':
			return "<a href='#' title = 'Active' onClick= 'changeItemStatus(".urlencode($id).", 0)' ><div class='grid-accept'></div></a>";
			break;
		default:
			return '';
			break;
		}
	}
	private function getDetattacktypeIDByRuleID($rule_id, $attacktypeidArray)
	{
		$attacktypeids = '('.implode(',', $attacktypeidArray).')';
		$attrList = array("`detcontdetail`.`detattacktype_id` AS `detattacktype_id`");
		$sql = convertViews::convertAttackmap($attrList);
		$query = $sql." WHERE `detcontdetail`.`rule_id` =".(int) $rule_id." AND `detattacktype`.`attacktypeid` IN ".$attacktypeids;
		$this->db->setQuery($query);
		$result = $this->db->loadResultArray();
		$this->db->closeDBO ();
		return $result;
	}
	private function getDetattacktypeIDByVarID($var_id)
	{
		$attrList = array("`detcontdetail`.`detattacktype_id` AS `detattacktype_id`");
		$sql = convertViews::convertAttackmap($attrList);
		$query = $sql." WHERE `detcontdetail`.`var_id` =".(int) $var_id;
		$this->db->setQuery($query);
		$result = $this->db->loadResultArray();
		$this->db->closeDBO ();
		return $result;
	}
	private function deleteAttackTypeDetailByID($detattacktype_id)
	{
		$result = $this->db->deleteRecord(array('detattacktype_id' => $detattacktype_id), '#__osefirewall_detcontdetail');
		$this->db->closeDBO ();
		return $result;
	}
	private function deleteAttackTypebyID($detattacktype_id)
	{
		$result = $this->db->deleteRecord(array('id' => $detattacktype_id), '#__osefirewall_detattacktype');
		$this->db->closeDBO ();
		return $result;
	}
	// $attacktype reads as 1,2,3,4,5; return as [1,2,3,4,5]
	private function attackTypeEncode($attacktype)
	{
		$attacktype = explode(',', $attacktype);
		$i = 0;
		foreach ($attacktype as $val)
		{
			$attacktype[$i] = (int) $val;
			$i++;
		}
		return oseJSON::encode($attacktype);
	}
	private function attackTypeDecode($attacktypes, $attackids)
	{
		// $attacktype reads as [1,2,3,4,5]; return as Rule1,Rule2,Rule3,Rule4,Rule5
		$attackids = oseJSON::decode($attackids);
		$return = array();
		foreach ($attackids as $attackid)
		{
			$return[] = $attacktypes[(int) $attackid];
		}
		$return = implode(", ", $return);
		return $return;
	}
	public function blacklistvariables($variable_ids)
	{
		foreach ($variable_ids as $variable_id)
		{
			$result = $this->blacklistvariablesByID($variable_id);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function blacklistvariablesByID($variable_id)
	{
		$varValues = array(
			'status' => (int) 1
		);
		$result = $this->db->addData('update', '#__osefirewall_vars', 'id', (int) $variable_id, $varValues);
		$this->db->closeDBO ();
		return $result;
	}
	public function whitelistvariables($variable_ids)
	{
		foreach ($variable_ids as $variable_id)
		{
			$result = $this->whitelistvariablesByID($variable_id);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function whitelistvariablesByID($variable_id)
	{
		$varValues = array(
			'status' => (int) 3
		);
		$result = $this->db->addData('update', '#__osefirewall_vars', 'id', (int) $variable_id, $varValues);
		$this->db->closeDBO ();
		return $result;
	}
	public function filtervariables($variable_ids)
	{
		foreach ($variable_ids as $variable_id)
		{
			$result = $this->filtervariablesByID($variable_id);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function filtervariablesByID($variable_id)
	{
		$varValues = array(
			'status' => (int) 2
		);
		$result = $this->db->addData('update', '#__osefirewall_vars', 'id', (int) $variable_id, $varValues);
		$this->db->closeDBO ();
		return $result;
	}
	private function deleteDectectedAttacks($detattacktype_ids)
	{
		foreach ($detattacktype_ids as $detattacktype_id)
		{
			$result = $this->deleteAttackTypeDetailByID($detattacktype_id);
			if ($result == false)
			{
				return false;
			}
			$result = $this->deleteDetectedByID($detattacktype_id);
			if ($result == false)
			{
				return false;
			}
			$result = $this->deleteAttackTypebyID($detattacktype_id);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function deleteDetectedByID($detattacktype_id)
	{
		$result = $this->db->deleteRecord(array('detattacktype_id' => $detattacktype_id), '#__osefirewall_detected');
		$this->db->closeDBO ();
		return $result;
	}
	private function deleteFilterbyID($id)
	{
		$result = $this->db->deleteRecord(array('id' => $id), '#__osefirewall_filters');
		$this->db->closeDBO ();
		return $result;
	}
	public function getVariables()
	{
		$columns = oRequest::getVar('columns', null);
		$limit = oRequest::getInt('length', 15);
		$start = oRequest::getInt('start', 0);
		$search = oRequest::getVar('search', null);
		$orderArr = oRequest::getVar('order', null);
		$sortby = null;
		$orderDir = 'asc';
		$status = $columns[3]['search']['value'];
		if (!empty($orderArr[0]['column']))
		{
			$sortby = $columns[$orderArr[0]['column']]['data'];
			$orderDir = $orderArr[0]['dir'];
		}
		$return = $this->getVariablesDB($search['value'], $status, $start, $limit, $sortby, $orderDir);
		$return['data'] = $this->convertVariables($return['data'], 'basic');
		return $return;
	}
	private function getVariablesDB($search, $status, $start, $limit, $sortby, $orderDir)
	{
		$return = array ();
		if (!empty($search))
		{
			$this->where[] = "`keyname` LIKE ".$this->db->quoteValue('%'.$search.'%', true);
		}
		if ($status == '1' || $status == '3')
		{
			$this->where[] = "`status` = ".(int) $status;
		}
		if (!empty($sortby)) {$this->getOrderBy ($sortby, $orderDir);}
		if (!empty($limit)) {$this->getLimitStm ($start, $limit);}
		$where = $this->db->implodeWhere($this->where);
		// Get Records Query;
		$return['data'] = $this->getAllVariables ($where);
		$counts = $this->getAllCountsVariables($where);
		$return['recordsTotal'] = $counts['recordsTotal'];
		$return['recordsFiltered'] = $counts['recordsFiltered'];
		return $return;
	}
	private function getAllVariables ($where) {
		$sql = 'SELECT * FROM '.$this->db->QuoteTable('#__osefirewall_vars');
		$query = $sql.$where.$this->orderBy." ".$this->limitStm;
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		return $results;
	}
	private function getAllCountsVariables ($where) {
		$return = array();
		// Get total count
		$sql = 'SELECT COUNT(`id`) AS count FROM '.$this->db->QuoteTable('#__osefirewall_vars');
		$this->db->setQuery($sql);
		$result = $this->db->loadObject();
		$return['recordsTotal'] = $result->count;
		// Get filter count
		$this->db->setQuery($sql.$where);
		$result = $this->db->loadObject();
		$return['recordsFiltered'] = $result->count;
		return $return;
	}
	private function convertVariables($results)
	{
		$i = 0;
		foreach ($results as $result)
		{
			switch ($results[$i]->status)
			{
			case 1:
				$status = 'Actively scanned';
				break;
			case 2:
				$status = 'Actively filtered';
				break;
			case 3:
				$status = 'Ignored / whitelisted';
				break;
			}
			$results[$i]->status = $this->getStatusIcon($results[$i]->id, $results[$i]->status);
			$results[$i]->statusexp = $status;
			$results[$i]->checkbox = '';
			$i++;
		}
		return $results;
	}
	public function getVariablesTotal()
	{
		$result = $this->db->getTotalNumber('id', '#__osefirewall_vars');
		$this->db->closeDBO ();
		return $result;
	}
	public function changeVarStatus($id, $status)
	{
		$varValues = array(
			'status' => (int) $status
		);
		$result = $this->db->addData('update', '#__osefirewall_vars', 'id', (int) $id, $varValues);
		$this->db->closeDBO ();
		return $result;
	}
	public function addvariables($variable, $status)
	{
		$varObject = $this->getVariablebyName($variable);
		if (empty($varObject))
		{
			$varValues = array(
				'keyname' => $variable,
				'status' => (int) $status
			);
			$id = $this->db->addData ('insert', '#__osefirewall_vars', '', '', $varValues);
			$this->db->closeDBO ();
			return $id;
		}
		else
		{
			return $varObject->id;
		}
	}
	private function getVariablebyName($variable)
	{
		$query = "SELECT * FROM `#__osefirewall_vars`"." WHERE `keyname` = ".$this->db->quoteValue($variable);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		$this->db->closeDBO ();
		return $results;
	}
	public function deletevariable($id)
	{
		$detattacktype_ids = $this->getDetattacktypeIDByVarID($id);
		$result = $this->deleteDectectedAttacks($detattacktype_ids);
		if ($result == true)
		{
			$result = $this->deleteVariablebyID($id);
		}
		return $result;
	}
	private function deleteVariablebyID($id)
	{
		$result = $this->db->deleteRecord(array('id' => $id), '#__osefirewall_vars');
		$this->db->closeDBO ();
		return $result;
	}
	public function deleteAllVariables () {
		$result = true;
		$vars = $this->getAllVariables ('');
		foreach ($vars as $var) {
			$result = $this->deletevariable($var->id);
		}
		return $result;
	}
	public function loadDefaultRules($type)
	{
		switch ($type)
		{
		case 'joomla':
			$keys = $this->loadJoomlaKeys();
			break;
		case 'wordpress':
			$keys = $this->loadWordpressKeys();
			break;
		case 'jomsocial':
			$keys = $this->loadJSocialKeys();
			break;
		}
		foreach ($keys as $variable)
		{
			$result = $this->addvariables($variable, 3);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function loadJoomlaKeys()
	{
		$keys = array();
		$keys[] = 'COOKIE.CFCLIENT_AVON';
		$keys[] = 'COOKIE.CFCLIENT_LAUSANNE';
		$keys[] = 'COOKIE.CFCLIENT_CFGLOBALS';
		$keys[] = 'COOKIE.omp__super_properties';
		$keys[] = 'COOKIE._okbk';
		$keys[] = 'COOKIE.__utmz';
		$keys[] = 'POST.install_url';
		$keys[] = 'POST.json';
		$keys[] = 'POST.text';
		$keys[] = 'POST.text_mail_new_registration_registrant';
		$keys[] = 'POST.install_directory';
		$keys[] = 'POST.cfg_reg_first_visit_url';
		$keys[] = 'POST.cfg_reg_pend_appr_msg';
		$keys[] = 'POST.cfg_reg_welcome_msg';
		$keys[] = 'POST.filterfieldlist';
		$keys[] = 'POST.params';
		$keys[] = 'POST.sortfields';
		$keys[] = 'POST.title';
		$keys[] = 'POST.url';
		return $keys;
	}
	private function loadWordpressKeys()
	{
		$keys = array();
		$keys[] = 'COOKIE.CFCLIENT_AVON';
		$keys[] = 'COOKIE.CFCLIENT_LAUSANNE';
		$keys[] = 'COOKIE.CFCLIENT_CFGLOBALS';
		$keys[] = 'COOKIE.omp__super_properties';
		$keys[] = 'COOKIE._okbk';
		$keys[] = 'COOKIE.__utmz';
		$keys[] = 'POST.siteurl';
		$keys[] = 'POST.redirect_to';
		$keys[] = 'POST.return_to';
		$keys[] = 'POST.home';
		$keys[] = 'POST.current_url';
		$keys[] = 'POST.referredby';
		$keys[] = 'POST._wp_original_http_referer';
		$keys[] = 'POST.link';
		$keys[] = 'POST._jd_wp_twitter';
		return $keys;
	}
	private function loadJSocialKeys()
	{
		$keys = array();
		$keys[] = 'POST.arg0';
		$keys[] = 'POST.arg1';
		$keys[] = 'POST.arg2';
		$keys[] = 'POST.arg3';
		$keys[] = 'POST.arg4';
		$keys[] = 'POST.arg5';
		$keys[] = 'POST.arg6';
		$keys[] = 'POST.arg7';
		$keys[] = 'POST.arg8';
		$keys[] = 'POST.arg9';
		$keys[] = 'POST.arg10';
		$keys[] = 'POST.rows';
		return $keys;
	}
	public function clearvariables()
	{
		$variables = $this->getVariablesDB('', '', 0, '');
		foreach ($variables as $variable)
		{
			$result = $this->deletevariable($variable->id);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	public function getConfiguration($type)
	{
		return oseFirewall::getConfiguration($type);
	}
	public function getConfigurationByName($name)
	{
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = ".$this->db->quoteValue($name);
		$this->db->setQuery($query);
		$results = $this->db->loadResultList();
		$this->db->closeDBO ();
		if ($results[0]['value'] == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function saveConfiguration($type, $data)
	{
		foreach ($data as $key => $value)
		{
			$this->removeDuplicateKey ($key, $type);
			$keyID = $this->isKeyExistsConf($key, $type);
			if (empty($keyID))
			{
				$result = $this->ConfVariableInsert($key, $value, $type);
			}
			else
			{
				$result = $this->ConfVariableUpdate($keyID, $value, $type);
			}
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function removeDuplicateKey($key, $type)
	{
		$query = "SELECT `id`, `key`, `type` FROM `#__ose_secConfig` WHERE `key` = ".$this->db->quoteValue($key);
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		foreach ($results as $result) {
			if ($result->type != $type)
			{
				$this->db->deleteRecord(array('id'=>$result->id), '#__ose_secConfig');
			}
		}
	}
	private function isKeyExistsConf($key, $type)
	{
		$query = "SELECT `id` FROM `#__ose_secConfig` WHERE `key` = ".$this->db->quoteValue($key)." AND `type` = ".$this->db->quoteValue($type);
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		$this->db->closeDBO ();
		return (isset($result['id'])) ? $result['id'] : false;
	}
	private function ConfVariableInsert($key, $value, $type)
	{
		$varValues = array(
			'key' => $key,
			'value' => $value,
			'type' => $type
		);
		$id = $this->db->addData ('insert', '#__ose_secConfig', '', '', $varValues);
		$this->db->closeDBO ();
		return $id;
	}
	private function ConfVariableUpdate($keyID, $value, $type)
	{
		$varValues = array(
			'value' => $value,
		    'type' => $type
		);
		$result = $this->db->addData('update', '#__ose_secConfig', 'id', (int) $keyID, $varValues);
		$this->db->closeDBO ();
		return $result;
	}
	// OSE Firewall Basic Rulesets
	public function getRulesets()
	{
		$columns = oRequest::getVar('columns', null);
		$limit = oRequest::getInt('length', 15);
		$start = oRequest::getInt('start', 0);
		$search = oRequest::getVar('search', null);
		$orderArr = oRequest::getVar('order', null);
		$sortby = null;
		$orderDir = 'asc';
		$status = $columns[3]['search']['value'];
		if (!empty($orderArr[0]['column']))
		{
			$sortby = $columns[$orderArr[0]['column']]['data'];
			$orderDir = $orderArr[0]['dir'];
		}
		$return = $this->getRulesetsDB($search['value'], $status, $start, $limit, $sortby, $orderDir);
		$return['data'] = $this->convertRulesets($return['data'], 'basic');
		return $return;
	}
	// OSE Firewall Advance Rulesets
	public function getAdvanceRulsets()
	{
		$columns = oRequest::getVar('columns', null);
		$limit = oRequest::getInt('length', 15);
		$start = oRequest::getInt('start', 0);
		$search = oRequest::getVar('search', null);
		$orderArr = oRequest::getVar('order', null);
		$sortby = null;
		$orderDir = 'asc';
		$status = $columns[4]['search']['value'];
		if (!empty($orderArr[0]['column']))
		{
			$sortby = $columns[$orderArr[0]['column']]['data'];
			$orderDir = $orderArr[0]['dir'];
		}
		$return = $this->getAdvanceRulesetsDB($search['value'], $status, $start, $limit, $sortby, $orderDir);
		$return['data'] = $this->convertRulesets($return['data'], 'advance');
		return $return;
	}
	// OSE Firewall Advance Virus Patterns
	public function getAdvancePatterns()
	{
		$limit = oRequest::getInt('limit', 15);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		$status = oRequest::getInt('status', null);
		$start = $limit * ($page - 1);
		return $this->convertPatterns($this->getAdvancePatternsDB($search, $status, $start, $limit));
	} 
	private function getRulesetsDB($search, $status, $start, $limit, $sortby, $orderDir)
	{
		$return = array ();
		if (!empty($search)) {$this->getWhereName ($search);}
		if ($status == '1' || $status == '0')
		{
			$this->where[] = "`action` = ".(int) $status;
		}
		if (!empty($sortby)) {$this->getOrderBy ($sortby, $orderDir);}
		if (!empty($limit)) {$this->getLimitStm ($start, $limit);}
		$where = $this->db->implodeWhere($this->where);
		// Get Records Query;
		$return['data'] = $this->getAllRulesets ($where,'bs');
		$counts = $this->getAllCountsRulesets($where,'bs');
		$return['recordsTotal'] = $counts['recordsTotal'];
		$return['recordsFiltered'] = $counts['recordsFiltered'];
		return $return;
	}
	private function getRulesetsTable ($type) {
		return ($type=='bs')?'#__osefirewall_basicrules':'#__osefirewall_advancerules';
	}
	private function getAllRulesets ($where, $type) {
		$table = $this->getRulesetsTable ($type);
		$sql = 'SELECT * FROM '.$this->db->QuoteTable($table);
		$query = $sql.$where.$this->orderBy." ".$this->limitStm;
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		return $results;
	}
	private function getAllCountsRulesets ($where, $type) {
		$return = array();
		// Get total count
		$table = $this->getRulesetsTable ($type);
		$sql = 'SELECT COUNT(`id`) AS count FROM '.$this->db->QuoteTable($table);
		$this->db->setQuery($sql);
		$result = $this->db->loadObject();
		$return['recordsTotal'] = $result->count;
		// Get filter count
		$this->db->setQuery($sql.$where);
		$result = $this->db->loadObject();
		$return['recordsFiltered'] = $result->count;
		return $return;
	}
	private function getAdvanceRulesetsDB($search, $status, $start, $limit, $sortby, $orderDir)
	{
		$return = array ();
		if (!empty($search)) {$this->getWhereName ($search);}
		if ($status == '1' || $status == '0')
		{
			$this->where[] = "`action` = ".(int) $status;
		}
		if (!empty($sortby)) {$this->getOrderBy ($sortby, $orderDir);}
		if (!empty($limit)) {$this->getLimitStm ($start, $limit);}
		$where = $this->db->implodeWhere($this->where);
		// Get Records Query;
		$return['data'] = $this->getAllRulesets ($where, 'ad');
		$counts = $this->getAllCountsRulesets($where, 'ad');
		$return['recordsTotal'] = $counts['recordsTotal'];
		$return['recordsFiltered'] = $counts['recordsFiltered'];
		return $return;
	}
	private function getAdvancePatternsDB($search, $status, $start, $limit)
	{
		$where = array();
		if ($status === 1 || $status === 0)
		{
			$where[] = "`action` = ".(int) $status;
		}
		$where = $this->db->implodeWhere($where);
		$query = "SELECT p.*, t.type FROM `#__osefirewall_advancepatterns` as p LEFT JOIN `#__osefirewall_vstypes` as t ON p.type_id = t.id".$where." ORDER BY id ASC LIMIT ".$start.", ".$limit;
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $results;
	}
	private function convertRulesets($results, $type)
	{
		$i = 0;
		$attacktypes = $this->getAttackTypeArray();
		$anchors =$this->getAllTutorialLinks (); 
		foreach ($results as $result)
		{
			$link='<a href="http://www.centrora.com/centrora-security-tutorial/centrora-security-basic-firewall-explanations/#'.$anchors[$results[$i]->id].'" target="_blank"><i class="fa fa-info-circle ruletips"></i></a>';
			$results[$i]->rule = ($type=='basic')?oLang::_get($results[$i]->rule).'&nbsp;'.$link:oLang::_get($results[$i]->description);
			$results[$i]->action = $this->getActionIcon($results[$i]->id, $results[$i]->action);
			$results[$i]->attacktype = $this->attackTypeDecode($attacktypes, $results[$i]->attacktype);
			$results[$i]->checkbox ='';
			$i++;
		}
		return $results;
	}
	private function getAllTutorialLinks () {
		$links = array (); 
		$links[1]="stopforumspam";
		$links[2]="blacklistedmethods";
		$links[3]="maluseragent";
		$links[4]="ddos";
		$links[5]="RFI";
		$links[6]="DFI";
		$links[7]="JSInjection";
		$links[8]="SQLInjection";
		$links[9]="dirtraveral";
		$links[10]="longquery";
		return $links;
	}
	private function convertPatterns($results)
	{
		$i = 0;
		$attacktypes = $this->getAttackTypeArray();
		foreach ($results as $result)
		{
			$results[$i]->type = oLang::_get($results[$i]->type);
			$results[$i]->confidence = $results[$i]->confidence.'%';
			$results[$i]->patterns = htmlentities($results[$i]->patterns);
			$results[$i]->action = $this->getActionIcon($results[$i]->id, $results[$i]->status);
			$results[$i]->checkbox ='';
			//$results[$i]->attacktype = $this->attackTypeDecode($attacktypes, $results[$i]->attacktype);
			$i++;
		}
		return $results;
	}
	public function getRulesetsTotal()
	{
		
		$result = $this->db->getTotalNumber('id', '#__osefirewall_basicrules');
		$this->db->closeDBO ();
		return $result;
	}
	public function getAdvanceRulesetsTotal()
	{
		
		$result = $this->db->getTotalNumber('id', '#__osefirewall_advancerules');
		$this->db->closeDBO ();
		return $result;
	}
	public function getAdvancePatternsTotal()
	{
		
		$result = $this->db->getTotalNumber('id', '#__osefirewall_advancepatterns');
		$this->db->closeDBO ();
		return $result;
	}
	public function changeRulesetStatus($id, $status)
	{
		
		$varValues = array(
			'action' => (int) $status
		);
		$result = $this->db->addData('update', '#__osefirewall_basicrules', 'id', (int) $id, $varValues);
		$this->db->closeDBO ();
		return $result;
	}
	public function changeAdvanceRulesetStatus($id, $status)
	{
		
		$varValues = array(
			'action' => (int) $status
		);
		$result = $this->db->addData('update', '#__osefirewall_advancerules', 'id', (int) $id, $varValues);
		$this->db->closeDBO ();
		return $result;
	}
	public function getCurrentSignatureVersion () {
		if (oseFirewall::isDBReady())
		{
			 
			$query = "SELECT `number` FROM `#__osefirewall_versions` WHERE `type` = 'ath'";
			$this->db->setQuery($query); 
			$result = $this->db->loadResult();
			return (isset($result['number']))?$result['number']:'';  
		}
		else
		{
			return null; 
		}
	}
	public function checkRuleExists()
	{
		$query = "SHOW TABLES LIKE '#__osefirewall_advancerules';";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		return (empty($results)) ? false : true;
	}
	public function getCountryStat() {
		$return = array();
		$query = "SELECT country_code, COUNT(country_code) AS count FROM `#__osefirewall_acl` WHERE `country_code` IS NOT NULL AND `status` = 1 GROUP BY `country_code`;";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		foreach ($results as $result) {
			$return[strtoupper($result->country_code)]= $result->count;
		}
		return $return;
	}
	public function getTrafficData () {
		$return = array();
		$query = "SELECT HOUR(`datetime`) as hour, COUNT(id) AS count FROM `#__osefirewall_acl` ". 
				 "WHERE (`status` = 1) AND `datetime` > DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY HOUR(`datetime`) ORDER BY `datetime` ASC";
		$this->db->setQuery($query);
        $return[0] = $this->db->loadObjectList();
        $query = "SELECT HOUR(`datetime`) as hour, COUNT(id) AS count FROM `#__osefirewall_acl` " .
            "WHERE (`status` = 2) AND `datetime` > DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY HOUR(`datetime`) ORDER BY `datetime` ASC";
        $this->db->setQuery($query);
        $return[1] = $this->db->loadObjectList();
        $query = "SELECT HOUR(`datetime`) as hour, COUNT(id) AS count FROM `#__osefirewall_acl` " .
            "WHERE (`status` = 3) AND `datetime` > DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY HOUR(`datetime`) ORDER BY `datetime` ASC";
        $this->db->setQuery($query);
        $return[2] = $this->db->loadObjectList();
        return $return;
	}

    private function headerArray()
    {
        return array('name', 'ip_start', 'ip_end', 'ip_type', 'ip_status');
    }

    private function getOutputData()
    {
        $output = implode(",", $this->headerArray()) . "\n";
        $results = $this->getACLIPMap();
        foreach ($results['data'] as $data) {
            $output .= $this->getTmpOutput($data) . "\n";
        }
        return $output;
    }

    private function getTmpOutput($data)
    {
        $tmp = array();
        $tmp[] = $data->name;
        $tmp[] = $data->ip32_start;
        $tmp[] = $data->ip32_end;
        $tmp[] = $data->iptype;
        $tmp[] = $data->statusraw;
        $return = implode(",", $tmp);
        return $return;
    }

    public function downloadcsv($filename)
    {
        $fileContent = $this->getOutputData();

        ob_clean();
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Length: " . strlen($fileContent));
        // Output to browser with appropriate mime type, you choose ;)
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        print_r($fileContent);
        exit;
    }
}