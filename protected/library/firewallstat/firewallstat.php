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
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH'))
{
	die('Direct Access Not Allowed');
}
class oseFirewallStat
{
	public function __construct()
	{
		oseFirewall::callLibClass('convertviews', 'convertviews');
		oseFirewall::loadRequest();
	}
	public function getAttackSummary()
	{
		return $this->convertAttackSummary($this->getAttackSummaryDB());
	}
	private function getAttackSummaryDB()
	{
		$db = oseFirewall::getDBO();
		$attrList = array("COUNT(`acl`.`id`) as `count`", "`acl`.`datetime` as `date`", " `attacktype`.`id` as `attacktypeid`");
		$sql = convertViews::convertAttackTypesum($attrList);
		$query = $sql."WHERE DATEDIFF( NOW(), datetime ) <= 10 AND `acl`.`status` IN (1,2)  "."GROUP BY DATE(datetime), attacktypeid ";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$where = array();
		if (!empty($ids))
		{
			$where[] = "`id` NOT IN (".implode(",", $ids).")";
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT `id`, `name` FROM `#__osefirewall_attacktype` ".$where;
		$db->setQuery($query);
		$result = $db->loadObjectList();
		$db->closeDBO ();
		return $result;
	}
	public function getAttackTypes($ids)
	{
		return $this->getAttackTypesDB($ids);
	}
	public function getAdvanceRulesVersion()
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT number, type FROM `#__osefirewall_versions` WHERE `type` = 'ath'";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		return $this->convertAdvanceRulesStatistic($results);
	}
	public function getAdvancePatternsVersion()
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT number, type FROM `#__osefirewall_versions` WHERE `type` = 'avs'";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		return $this->convertAdvanceRulesStatistic($results);
	}
	public function getAdvanceRulesStatistic()
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT action as status, count(action) as number FROM `#__osefirewall_advancerules` group by action";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$query = "SELECT action as status, count(action) as number FROM `#__osefirewall_basicrules` group by action";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$query = "SELECT status, count(status) as number FROM `#__osefirewall_vars` group by status;";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$query = "SELECT status, count(status) as number FROM `#__osefirewall_acl` group by status;";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
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
		$limit = oRequest::getInt('limit', 15);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		if (isset($_REQUEST['status']))
		{
			$status = oRequest::getInt('status', null);
		}
		else
		{
			$status = null;
		}
		$start = $limit * ($page - 1);
		return $this->convertACLIPMap($this->getACLIPMapDB($search, $status, $start, $limit));
	}
	private function getACLIPMapDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO();
		$where = array();
		if (!empty($search))
		{
			$where[] = "`name` LIKE ".$db->quoteValue($search.'%', true)." OR `ip32_start` = ".$db->quoteValue(ip2long($search), true);
		}
		if (!empty($status))
		{
			if ($status == 2)
			{
				$where[] = "`status` = ".(int) $status." or `status` = ".(int) 0;
			}
			else
			{
				$where[] = "`status` = ".(int) $status;
			}
		}
		$where = $db->implodeWhere($where);
		$attrList = array("`acl`.`id` AS `id`", "`acl`.`score`AS `score`", " `acl`.`name` AS `name`",
			"`ip`.`iptype` AS `iptype`", "`ip`.`ip32_start` AS `ip32_start`", "`ip`.`ip32_end` AS `ip32_end`", "`acl`.`status` AS `status`", "`acl`.`host` AS `host`", "`acl`.`datetime` AS `datetime`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql.$where." ORDER BY datetime DESC LIMIT ".$start.", ".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		return $results;
	}
	private function convertACLIPMap($results)
	{
		$i = 0;
		foreach ($results as $result)
		{
			if (!isset($results[$i]->country_code) || empty($results[$i]->country_code))
			{
				//TODO: convert country code!
				//$results[$i]->country_code = $this->updateCountryCode($results[$i]->id, $results[$i]->ip32_start);
				}
			$results[$i]->country_code = $this->getCountryImage($results[$i]->country_code);
			$results[$i]->ip32_start = long2ip((float) $results[$i]->ip32_start);
			$results[$i]->ip32_end = long2ip((float) $results[$i]->ip32_end);
			if (empty($results[$i]->host))
			{
				//$results[$i]->host = $this->updateIPHost($results[$i]->id, $results[$i]->ip32_start);
				}
			$results[$i]->view = $this->getViewIcon($results[$i]->id);
			$results[$i]->status = $this->getStatusIcon($results[$i]->id, $results[$i]->status);
			$i++;
		}
		return $results;
	}
	private function getViewIcon($id)
	{
		return "<a href='#' title = 'View detail' onClick= 'viewIPdetail(".urlencode($id).")' ><div class='ose-grid-info'></div></a>";
	}
	private function getStatusIcon($id, $status)
	{
		switch ($status)
		{
		case '3':
			return "<a href='#' title = 'WhiteList' onClick= 'changeItemStatus(".urlencode($id).", 2)' ><div class='ose-grid-accept'></div></a>";
			break;
		case '2':
		case '0':
			return "<a href='#' title = 'Monitering' onClick= 'changeItemStatus(".urlencode($id).", 1)' ><div class='ose-grid-error'></div></a>";
			break;
		case '1':
			return "<a href='#' title = 'BlackList' onClick= 'changeItemStatus(".urlencode($id).", 3)' ><div class='ose-grid-delete'></div></a>";
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
		$db = oseFirewall::getDBO();
		$query = " UPDATE `#__osefirewall_acl` SET `host` = ".$db->quoteValue($host, true)." WHERE `id` = ".(int) $acl_id;
		$db->setQuery($query);
		$result = $db->query();
		$db->closeDBO ();
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
		if (empty($country_code))
		{
			return '';
		}
		else
		{
			$baseUrl = Yii::app()->baseUrl;
			return "<img src='".$baseUrl."/public/images/flags/".strtolower($country_code).".png' alt='".$country_code."' />";
		}
	}
	private function getCountryCodebyIP($ip32_start)
	{
		$ip = long2ip($ip32_start);
		$tags = get_meta_tags('http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress='.$ip);
		$country = (isset($tags['iso2'])) ? strtolower($tags['iso2']) : '';
		return $country;
	}
	private function updateCountryCode($acl_id, $ip32_start)
	{
		$db = oseFirewall::getDBO();
		$country_code = $this->getCountryCodebyIP($ip32_start);
		$varValues = array(
			'country_code' => $country_code
		);
		$result = $db->addData('update', '#__osefirewall_acl', 'id', (int) $acl_id, $varValues);
		$db->closeDBO ();
		if ($result == true)
		{
			return $country_code;
		}
		else
		{
			return false;
		}
	}
	public function getACLIPTotal()
	{
		$db = oseFirewall::getDBO();
		$result = $db->getTotalNumber('id', '#__osefirewall_acl');
		$db->closeDBO ();
		return $result;
	}
	public function remvoeACLRule($aclid)
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
			$result = $this->deleteACLID($aclid);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function deleteACLID($aclid)
	{
		$db = oseFirewall::getDBO();
		$result = $db->deleteRecord(array('id' => $aclid), '#__osefirewall_acl');
		$db->closeDBO ();
		return $result;
	}
	private function deleteIPID($aclid, $ipid)
	{
		$db = oseFirewall::getDBO();
		$result = $db->deleteRecord(array('id' => $ipid, 'acl_id' => $aclid), '#__osefirewall_iptable');
		$db->closeDBO ();
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
	private function deleteAttackTypeIDDB($aclid, $detattacktype_id)
	{
		$db = oseFirewall::getDBO();
		$result = $db->deleteRecord(array('acl_id' => $aclid, 'detattacktype_id' => $detattacktype_id), '#__osefirewall_detected');
		if ($result == true)
		{
			$result = $db->deleteRecord(array('detattacktype_id' => $detattacktype_id), '#__osefirewall_detcontdetail');
			if ($result == true)
			{
				$result = $db->deleteRecord(array('id' => $detattacktype_id), '#__osefirewall_detattacktype');
			}
		}
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$attrList = array("`ip`.`id` AS `ipid`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql."WHERE `acl`.`id` = ".(int) $aclid;
		$db->setQuery($query);
		$result = $db->loadResult();
		$db->closeDBO ();
		return (isset($result['ipid'])) ? $result['ipid'] : false;
	}
	private function getDetAttackIDOnAclidDB($aclid)
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT `detattacktype_id` FROM `#__osefirewall_detected` WHERE `acl_id` = ".(int) $aclid;
		$db->setQuery($query);
		$results = $db->loadArrayList('detattacktype_id');
		$db->closeDBO ();
		return $results;
	}
	public function changeACLStatus($aclid, $status)
	{
		$db = oseFirewall::getDBO();
		$varValues = array(
			'status' => (int) $status
		);
		$result = $db->addData('update', '#__osefirewall_acl', 'id', (int) $aclid, $varValues);
		$db->closeDBO ();
		return $result;
	}
	public function getAttackDetail($aclid)
	{
		$aclrule = $this->getACLIPMapByIDDB($aclid);
		$html = "<table width='100%' class='stat'>";
		if (empty($aclrule))
		{
			$html .= "<tr><td class='label'>".oLang::_get('Result')."</td><td class='attackcontent'>".oLang::_get("No attack information found")."</td></tr>";
		}
		else
		{
			$aclattackmap = $this->getACLAttackMapByIDDB($aclid);
			$html .= "<tr><td class='label'><div>".oLang::_get('IP Access Rule ID')."</div></td><td class='attackcontent'>".$aclrule->id."</td></tr>";
			$html .= "<tr><td class='label'><div>".oLang::_get('Country')."</div></td><td class='attackcontent'>".$this->getCountryImage($aclrule->country_code)."</td></tr>";
			$html .= "<tr><td class='label'><div>".oLang::_get('Logged Time')."</div></td><td class='attackcontent'>".$aclrule->datetime."</td></tr>";
			$html .= "<tr><td class='label'><div>".oLang::_get('Referer')."</div></td><td class='attackcontent'>".$this->getRefererByIDDB($aclrule->referers_id)."</td></tr>";
			$html .= "<tr><td class='label'><div>".oLang::_get('Target')."</div></td><td class='attackcontent'>".$this->getPageByIDDB($aclrule->pages_id)."</td></tr>";
			$html .= "<tr><td class='label'><div>---Attack detection---</div></td></tr>";
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
					if (!isset($tag) || $item->attacktypeid == 1 || $item->attacktypeid == 11)
					{
						//$tag = $item->tag;
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
						$html .= "<tr>";
						if ($item->attacktypeid == 1 || $item->attacktypeid == 11)
						{
							$detcontent_id == $item->detcontent_id;
							$html .= "<td class='label'><div>".$item->name."</div></td>";
						}
						else
						{
							if (!isset($var) || $var != $item->var_id)
							{
								$html .= "<td class='label'>Detected var:<td><span style='color:red;'>".$item->keyname."</span></td></td>";
							}
							$detcontent_id = $item->detcontent_id;
							$content = $item->content;
							$var = $item->var_id;
							$html .= "<tr><td class='attackcontent'>";
							$html .= "Detected Content: <td><span style='color:red;'>".$item->content."</span></td></td>";
						}
						if (!isset($item->tag) || ($tag != $item->tag))
						{
							$attackTypeArray[] = $item->name;
							$tag = $item->tag;
						}
					}
					else
						if ($detcontent_id == $item->detcontent_id && ($tag != $item->tag))
						{
							$attackTypeArray[] = $item->name;
						}
						else
						{
							$html .= "</td>";
							$html .= "</tr>";
						}
				}
				$html .= $this->printAttackType($attackTypeArray);
			}
		}
		$html .= "</table>";
		return $html;
	}
	private function getACLAttackMapByIDDB($id)
	{
		$db = oseFirewall::getDBO();
		$attrList = array('*');
		$sql = convertViews::convertAttackmap($attrList);
		$query = $sql." WHERE `acl`.`id` = ".(int) $id." ORDER BY `detcontdetail`.`var_id` ASC, `detcontdetail`.`detcontent_id` ASC";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		return $results;
	}
	private function getACLIPMapByIDDB($id)
	{
		$db = oseFirewall::getDBO();
		$attrList = array("*");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql."WHERE `acl`.`id` = ".(int) $id;
		$db->setQuery($query);
		$result = $db->loadObject();
		$db->closeDBO ();
		return $result;
	}
	private function getRefererByIDDB($id)
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT `referer_url` FROM `#__osefirewall_referers` WHERE `id` =".(int) $id;
		$db->setQuery($query);
		$result = $db->loadResult();
		$db->closeDBO ();
		return $result['referer_url'];
	}
	private function getPageByIDDB($id)
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT `page_url` FROM `#__osefirewall_pages` WHERE `id` =".(int) $id;
		$db->setQuery($query);
		$result = $db->loadResult();
		$db->closeDBO ();
		return $result['page_url'];
	}
	private function printAttackType($attackTypeArray)
	{
		if (!empty($attackTypeArray))
		{
			$html .= "<tr><td class = 'lable'><div>AttackType:</div></td><td>";
			foreach ($attackTypeArray as $type)
			{
				$html .= $type.",";
			}
			$html .= "</td>";
			$html .= "</tr>";
		}
		return $html;
	}
	protected function getActionIcon($id, $status)
	{
		switch ($status)
		{
		case '0':
			return "<a href='#' title = 'Inactive' onClick= 'changeItemStatus(".urlencode($id).", 1)' ><div class='ose-grid-delete'></div></a>";
			break;
		case '1':
			return "<a href='#' title = 'Active' onClick= 'changeItemStatus(".urlencode($id).", 0)' ><div class='ose-grid-accept'></div></a>";
			break;
		default:
			return '';
			break;
		}
	}
	private function getDetattacktypeIDByRuleID($rule_id, $attacktypeidArray)
	{
		$db = oseFirewall::getDBO();
		$attacktypeids = '('.implode(',', $attacktypeidArray).')';
		$attrList = array("`detcontdetail`.`detattacktype_id` AS `detattacktype_id`");
		$sql = convertViews::convertAttackmap($attrList);
		$query = $sql." WHERE `detcontdetail`.`rule_id` =".(int) $rule_id." AND `detattacktype`.`attacktypeid` IN ".$attacktypeids;
		$db->setQuery($query);
		$result = $db->loadResultArray();
		$db->closeDBO ();
		return $result;
	}
	private function getDetattacktypeIDByVarID($var_id)
	{
		$db = oseFirewall::getDBO();
		$attrList = array("`detcontdetail`.`detattacktype_id` AS `detattacktype_id`");
		$sql = convertViews::convertAttackmap($attrList);
		$query = $sql." WHERE `detcontdetail`.`var_id` =".(int) $var_id;
		$db->setQuery($query);
		$result = $db->loadResultArray();
		$db->closeDBO ();
		return $result;
	}
	private function deleteAttackTypeDetailByID($detattacktype_id)
	{
		$db = oseFirewall::getDBO();
		$result = $db->deleteRecord(array('detattacktype_id' => $detattacktype_id), '#__osefirewall_detcontdetail');
		$db->closeDBO ();
		return $result;
	}
	private function deleteAttackTypebyID($detattacktype_id)
	{
		$db = oseFirewall::getDBO();
		$result = $db->deleteRecord(array('id' => $detattacktype_id), '#__osefirewall_detattacktype');
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$varValues = array(
			'status' => (int) 1
		);
		$result = $db->addData('update', '#__osefirewall_vars', 'id', (int) $variable_id, $varValues);
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$varValues = array(
			'status' => (int) 3
		);
		$result = $db->addData('update', '#__osefirewall_vars', 'id', (int) $variable_id, $varValues);
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$varValues = array(
			'status' => (int) 2
		);
		$result = $db->addData('update', '#__osefirewall_vars', 'id', (int) $variable_id, $varValues);
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$result = $db->deleteRecord(array('detattacktype_id' => $detattacktype_id), '#__osefirewall_detected');
		$db->closeDBO ();
		return $result;
	}
	private function deleteFilterbyID($id)
	{
		$db = oseFirewall::getDBO();
		$result = $db->deleteRecord(array('id' => $id), '#__osefirewall_filters');
		$db->closeDBO ();
		return $result;
	}
	public function getVariables()
	{
		$limit = oRequest::getInt('limit', 15);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		$status = oRequest::getInt('status', null);
		$start = $limit * ($page - 1);
		return $this->convertVariables($this->getVariablesDB($search, $status, $start, $limit));
	}
	private function getVariablesDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO();
		$where = array();
		if (!empty($search))
		{
			$where[] = "`keyname` LIKE ".$db->quoteValue('%'.$search.'%', true);
		}
		if ($status === 1 || $status === 2 || $status === 3)
		{
			$where[] = "`status` = ".(int) $status;
		}
		if (!empty($limit))
		{
			$limit = "ASC LIMIT ".$start.", ".$limit;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_vars`".$where." ORDER BY id ".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		return $results;
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
			$i++;
		}
		return $results;
	}
	public function getVariablesTotal()
	{
		$db = oseFirewall::getDBO();
		$result = $db->getTotalNumber('id', '#__osefirewall_vars');
		$db->closeDBO ();
		return $result;
	}
	public function changeVarStatus($id, $status)
	{
		$db = oseFirewall::getDBO();
		$varValues = array(
			'status' => (int) $status
		);
		$result = $db->addData('update', '#__osefirewall_vars', 'id', (int) $id, $varValues);
		$db->closeDBO ();
		return $result;
	}
	public function addvariables($variable, $status)
	{
		$varObject = $this->getVariablebyName($variable);
		if (empty($varObject))
		{
			$db = oseFirewall::getDBO();
			$varValues = array(
				'keyname' => $variable,
				'status' => (int) $status
			);
			$id = $db->addData ('insert', '#__osefirewall_vars', '', '', $varValues);
			$db->closeDBO ();
			return $id;
		}
		else
		{
			return $varObject->id;
		}
	}
	private function getVariablebyName($variable)
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT * FROM `#__osefirewall_vars`"." WHERE `keyname` = ".$db->quoteValue($variable);
		$db->setQuery($query);
		$results = $db->loadObject();
		$db->closeDBO ();
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
		$db = oseFirewall::getDBO();
		$result = $db->deleteRecord(array('id' => $id), '#__osefirewall_vars');
		$db->closeDBO ();
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
		$return = array();
		$db = oseFirewall::getDBO();
		$query = "SELECT `key`, `value` FROM `#__ose_secConfig` WHERE `type` = ".$db->quoteValue($type);
		$db->setQuery($query);
		$db->closeDBO ();
		$results = $db->loadObjectList();
		foreach ($results as $result)
		{
			if ($type == 'l2var')
			{
				$return['data'][$result->key] = (int) $result->value;
			}
			else
			{
				$return['data'][$result->key] = $this->convertValue($result->key , $result->value);
			}
		}
		$return['success'] = true;
		return $return;
	}
	public function getConfigurationByName($name)
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = ".$db->quoteValue($name);
		$db->setQuery($query);
		$results = $db->loadResultList();
		$db->closeDBO ();
		if ($results[0]['value'] == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	private function convertValue($key, $value)
	{
		if (is_numeric($value))
		{
			$value = intval($value);
		}
		return $value;
	}
	public function saveConfiguration($type, $data)
	{
		foreach ($data as $key => $value)
		{
			$keyID = $this->isKeyExistsConf($key, $type);
			if (empty($keyID))
			{
				$result = $this->ConfVariableInsert($key, $value, $type);
			}
			else
			{
				$result = $this->ConfVariableUpdate($keyID, $value);
			}
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function isKeyExistsConf($key, $type)
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT `id` FROM `#__ose_secConfig` WHERE `key` = ".$db->quoteValue($key)." AND `type` = ".$db->quoteValue($type);
		$db->setQuery($query);
		$result = $db->loadResult();
		$db->closeDBO ();
		return (isset($result['id'])) ? $result['id'] : false;
	}
	private function ConfVariableInsert($key, $value, $type)
	{
		$db = oseFirewall::getDBO();
		$varValues = array(
			'key' => $key,
			'value' => $value,
			'type' => $type
		);
		$id = $db->addData ('insert', '#__ose_secConfig', '', '', $varValues);
		$db->closeDBO ();
		return $id;
	}
	private function ConfVariableUpdate($keyID, $value)
	{
		$db = oseFirewall::getDBO();
		$varValues = array(
			'value' => $value
		);
		$result = $db->addData('update', '#__ose_secConfig', 'id', (int) $keyID, $varValues);
		$db->closeDBO ();
		return $result;
	}
	// OSE Firewall Basic Rulesets
	public function getRulesets()
	{
		$limit = oRequest::getInt('limit', 15);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		$status = oRequest::getInt('status', null);
		$start = $limit * ($page - 1);
		return $this->convertRulesets($this->getRulesetsDB($search, $status, $start, $limit));
	}
	// OSE Firewall Advance Rulesets
	public function getAdvanceRulsets()
	{
		$limit = oRequest::getInt('limit', 15);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		$status = oRequest::getInt('status', null);
		$start = $limit * ($page - 1);
		return $this->convertRulesets($this->getAdvanceRulesetsDB($search, $status, $start, $limit));
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
	private function getRulesetsDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO();
		$where = array();
		if ($status === 1 || $status === 0)
		{
			$where[] = "`action` = ".(int) $status;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_basicrules`".$where." ORDER BY id ASC LIMIT ".$start.", ".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		return $results;
	}
	private function getAdvanceRulesetsDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO();
		$where = array();
		if ($status === 1 || $status === 0)
		{
			$where[] = "`action` = ".(int) $status;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_advancerules`".$where." ORDER BY id ASC LIMIT ".$start.", ".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		return $results;
	}
	private function getAdvancePatternsDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO();
		$where = array();
		if ($status === 1 || $status === 0)
		{
			$where[] = "`action` = ".(int) $status;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT p.*, t.type FROM `#__osefirewall_advancepatterns` as p LEFT JOIN `#__osefirewall_vstypes` as t ON p.type_id = t.id".$where." ORDER BY id ASC LIMIT ".$start.", ".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$db->closeDBO ();
		return $results;
	}
	private function convertRulesets($results)
	{
		$i = 0;
		$attacktypes = $this->getAttackTypeArray();
		foreach ($results as $result)
		{
			$results[$i]->rule = oLang::_get($results[$i]->rule);
			$results[$i]->action = $this->getActionIcon($results[$i]->id, $results[$i]->action);
			$results[$i]->attacktype = $this->attackTypeDecode($attacktypes, $results[$i]->attacktype);
			$i++;
		}
		return $results;
	}
	private function convertAdRulesets($results)
	{
		$i = 0;
		$attacktypes = $this->getAttackTypeArray();
		foreach ($results as $result)
		{
			$results[$i]->description = oLang::_get($results[$i]->description);
			$results[$i]->action = $this->getActionIcon($results[$i]->id, $results[$i]->action);
			$results[$i]->attacktype = $this->attackTypeDecode($attacktypes, $results[$i]->attacktype);
			$i++;
		}
		return $results;
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
			//$results[$i]->attacktype = $this->attackTypeDecode($attacktypes, $results[$i]->attacktype);
			$i++;
		}
		return $results;
	}
	public function getRulesetsTotal()
	{
		$db = oseFirewall::getDBO();
		$result = $db->getTotalNumber('id', '#__osefirewall_basicrules');
		$db->closeDBO ();
		return $result;
	}
	public function getAdvanceRulesetsTotal()
	{
		$db = oseFirewall::getDBO();
		$result = $db->getTotalNumber('id', '#__osefirewall_advancerules');
		$db->closeDBO ();
		return $result;
	}
	public function getAdvancePatternsTotal()
	{
		$db = oseFirewall::getDBO();
		$result = $db->getTotalNumber('id', '#__osefirewall_advancepatterns');
		$db->closeDBO ();
		return $result;
	}
	public function changeRulesetStatus($id, $status)
	{
		$db = oseFirewall::getDBO();
		$varValues = array(
			'action' => (int) $status
		);
		$result = $db->addData('update', '#__osefirewall_basicrules', 'id', (int) $id, $varValues);
		$db->closeDBO ();
		return $result;
	}
	public function changeAdvanceRulesetStatus($id, $status)
	{
		$db = oseFirewall::getDBO();
		$varValues = array(
			'action' => (int) $status
		);
		$result = $db->addData('update', '#__osefirewall_advancerules', 'id', (int) $id, $varValues);
		$db->closeDBO ();
		return $result;
	}
	public function getCurrentSignatureVersion () {
		if (oseFirewall::isDBReady())
		{
			$db = oseFirewall::getDBO(); 
			$query = "SELECT `number` FROM `#__osefirewall_versions` WHERE `type` = 'ath'";
			$db->setQuery($query); 
			$result = $db->loadResult(); 
			return $result['number'];  
		}
		else
		{
			return null; 
		}
	}
}