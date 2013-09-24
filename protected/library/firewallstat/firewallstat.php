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
class oseFirewallStat {
	public function __construct() {
		oseFirewall::loadRequest();
	}
	public function getAttackSummary()
	{
		return $this->convertAttackSummary($this->getAttackSummaryDB());
	}
	private function getAttackSummaryDB()
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT COUNT(aclid) as count, DATE(datetime) as date, attacktypeid ".
				 "FROM `#__osefirewall_attacktypesum` ".
				 "WHERE DATEDIFF( NOW(), datetime ) <= 10 AND `status` IN (1,2)  ".
				 "GROUP BY DATE(datetime), attacktypeid ";
		$db->setQuery($query); 
		return $db->loadObjectList();
	}
	private function convertAttackSummary($results)
	{
		$return = array();
		$i=0;  
		foreach ($results as $result)
		{
			if (!isset($return[$i]['date']))
			{
				$return[$i]['date']=$result->date;
			}
			if ($result->date!=$return[$i]['date'])
			{
				$i++;
				$return[$i]['date']=$result->date;
			}
			$result->attacktypeid=(!empty($result->attacktypeid))?$result->attacktypeid:0;
			$return[$i]['type'.$result->attacktypeid]= (int)$result->count;
		}
		return $return;
	}
	private function getAttackTypeArray()
	{
		$results = $this->getAttackTypesDB();
		$return = array(); 
		foreach ($results as $result)
		{
			$return[$result->id]= $result->name;
		}
		return $return;
	}
	private function getAttackTypesDB($ids=array())
	{
		$db = oseFirewall::getDBO ();
		$where = array();
		if (!empty($ids))
		{
			$where[] = "`id` NOT IN (".implode(",", $ids).")";
		}
		$where= $db->implodeWhere($where);
		$query = "SELECT `id`, `name` FROM `#__osefirewall_attacktype` ".$where;
		$db->setQuery($query); 
		return $db->loadObjectList();
	}
	public function getAttackTypes($ids)
	{
		return $this->getAttackTypesDB($ids);
	}
	public function getACLIPMap()
	{
		$limit = oRequest::getInt('limit', 25);
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
		$start = $limit * ($page-1);    
		return $this->convertACLIPMap($this->getACLIPMapDB($search, $status, $start, $limit));
	}
	private function getACLIPMapDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO ();
		$where = array(); 
		if (!empty($search))
		{
			$where[] = "`name` LIKE ".$db->quoteValue($search.'%', true)." OR `ip32_start` = ".$db->quoteValue(ip2long($search), true);;
		}
		if (!empty($status))
		{
			$where[] = "`status` = ".(int)$status;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_aclipmap` " .$where
				 ." ORDER BY datetime DESC LIMIT ".$start.", ".$limit;
		$db->setQuery($query); 
		$results = $db->loadObjectList();
		return $results; 
	}
	private function convertACLIPMap($results)
	{
		$i = 0;
		foreach ($results as $result)
		{
			if (!isset($results[$i]->country_code) || empty($results[$i]->country_code))
			{
				$results[$i]->country_code = $this->updateCountryCode($results[$i]->id, $results[$i]->ip32_start);
			}
			$results[$i]->country_code = $this->getCountryImage($results[$i]->country_code);
			$results[$i]->ip32_start= long2ip((float)$results[$i]->ip32_start);
			$results[$i]->ip32_end= long2ip((float)$results[$i]->ip32_end);
			if (empty($results[$i]->host))
			{
				//$results[$i]->host = $this->updateIPHost($results[$i]->id, $results[$i]->ip32_start);
			}
			$results[$i]->view = $this->getViewIcon($results[$i]->id);
			$results[$i]->status = $this->getStatusIcon($results[$i]->id, $results[$i]->status);
			$i++;
		}
		return  $results;
	}
	private function getViewIcon($id)
	{
		return "<a href='#' onClick= 'viewIPdetail(".urlencode($id).")' ><div class='ose-grid-info'></div></a>"; 
	}
	private function getStatusIcon($id, $status)
	{
		switch($status) {
                   	case '3':
                   		return "<a href='#' onClick= 'changeItemStatus(".urlencode($id).", 2)' ><div class='ose-grid-accept'></div></a>";
                   	break;
                   	case '2':  
                   		case '0':
                   		return "<a href='#' onClick= 'changeItemStatus(".urlencode($id).", 1)' ><div class='ose-grid-error'></div></a>";
                   	break;
                   	case '1': 
                   		return "<a href='#' onClick= 'changeItemStatus(".urlencode($id).", 3)' ><div class='ose-grid-delete'></div></a>";
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
		if ($result ==false)
		{
			return false; 
		}
		return true; 
	}	
	private function updateIPHost($acl_id, $ip_start)
	{
		$host = $this->get_host($ip_start);
		$db = oseFirewall::getDBO ();
		$query = " UPDATE `#__osefirewall_acl` SET `host` = ". $db->quoteValue($host, true).
				 " WHERE `id` = ". (int)$acl_id;
		$db->setQuery($query);
		$result = $db->query() ;
		if ($result == true)
		{
			return $host;
		}
		else
		{
			return false;
		}
	}
	private function get_host($ip){
		if (empty($ip))
		{
			return 'N/A';
		}
        $ptr= implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
        $host = dns_get_record($ptr,DNS_PTR);
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
			$baseUrl = Yii :: app()->baseUrl;
			return "<img src='".$baseUrl."/public/images/flags/".strtolower($country_code).".png' alt='".$country_code."' />";
		}	
	}
	private function getCountryCodebyIP($ip32_start)
	{
		$ip = long2ip($ip32_start);
		$tags = get_meta_tags('http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress='.$ip);
		$country  = (isset($tags['iso2']))?strtolower($tags['iso2']): '';
		return $country;
	}
	private function updateCountryCode($acl_id, $ip32_start)
	{
		$db = oseFirewall::getDBO ();
	 	$country_code = $this->getCountryCodebyIP($ip32_start);
	 	$varValues = array (
				'country_code' => $country_code
		);
		$result = $db->addData('update', '#__osefirewall_acl', 'id', (int)$acl_id, $varValues);
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
		$db = oseFirewall::getDBO ();
		return $db->getTotalNumber('id', '#__osefirewall_aclipmap'); 
	}
	public function remvoeACLRule($aclid)
	{
		$ids = $this->getIDSOnACLID($aclid);
		if (!empty($ids['detattacktype_id']))
		{
			$result = $this->deleteAttackTypeID($aclid, $ids['detattacktype_id']);
			if 	($result==false)
			{
				return false;
			}
		}
		if (!empty($ids['ipid']))
		{
			$result = $this->deleteIPID($aclid, $ids['ipid']);
			if 	($result==false)
			{
				return false;
			}
		} 
		if (!empty($ids['aclid']))
		{
			$result = $this->deleteACLID($aclid);
			if 	($result==false)
			{
				return false;
			}
		} 
		return true; 
	}
	private function deleteACLID($aclid)
	{
		$db = oseFirewall::getDBO ();
		return $db->deleteRecord(array('id'=>$aclid), '#__osefirewall_acl');
	}
	private function deleteIPID($aclid, $ipid)
	{
		$db = oseFirewall::getDBO ();
		return $db->deleteRecord(array('id'=>$ipid, 'acl_id'=>$aclid), '#__osefirewall_iptable');
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
		$db = oseFirewall::getDBO ();
		$result = $db->deleteRecord(array('acl_id'=>$aclid, 'detattacktype_id'=>$detattacktype_id), '#__osefirewall_detected');	
		if ($result == true)
		{
			$result = $db->deleteRecord(array('detattacktype_id'=>$detattacktype_id), '#__osefirewall_detcontdetail');
			if ($result == true)
			{
				$result = $db->deleteRecord(array('id'=>$detattacktype_id), '#__osefirewall_detattacktype');	
			}
		}
		return $result; 	
	}
	private function getIDSOnACLID($aclid)
	{
		$return = array(); 
		$return['aclid']= $aclid;
		$return['ipid'] = $this->getIPIDOnAclidDB($aclid);
		$return['detattacktype_id']=$this->getDetAttackIDOnAclidDB($aclid);
		return $return;  
	} 
	private function getIPIDOnAclidDB($aclid)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT `ipid` FROM `#__osefirewall_aclipmap` WHERE `id` = ". (int)$aclid;
		$db->setQuery($query);
		$result = $db->loadResult();
		return (isset($result['ipid']))?$result['ipid']:false;
	}
	private function getDetAttackIDOnAclidDB($aclid)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT `detattacktype_id` FROM `#__osefirewall_detected` WHERE `acl_id` = ". (int)$aclid;
		$db->setQuery($query);
		$results = $db->loadArrayList('detattacktype_id');
		return $results;
	}
	public function changeACLStatus($aclid, $status)
	{
		$db = oseFirewall::getDBO ();
		$varValues = array (
				'status' => (int)$status
		);
		$result = $db->addData('update', '#__osefirewall_acl', 'id', (int)$aclid, $varValues);
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
			$html .= "<tr><td class='label'><div>".oLang::_get('IP Access Rule ID')."</div></td><td class='attackcontent'>".$aclrule->id."</td></tr>";
			$html .= "<tr><td class='label'><div>".oLang::_get('Country')."</div></td><td class='attackcontent'>".$this->getCountryImage($aclrule->country_code)."</td></tr>";
			$html .= "<tr><td class='label'><div>".oLang::_get('Logged Time')."</div></td><td class='attackcontent'>".$aclrule->datetime."</td></tr>";
			$html .= "<tr><td class='label'><div>".oLang::_get('Referer')."</div></td><td class='attackcontent'>".$this->getRefererByIDDB($aclrule->referers_id) ."</td></tr>";
			$html .= "<tr><td class='label'><div>".oLang::_get('Target')."</div></td><td class='attackcontent'>".$this->getPageByIDDB($aclrule->pages_id) ."</td></tr>";
		
			$aclattackmap = $this->getACLAttackMapByIDDB($aclid);
			if (!empty($aclattackmap))
			{
				$tmp= null;
				
				foreach ($aclattackmap as $item)
				{
					
					if (!isset($detcontent_id) || $item->attacktypeid==1 || $item->attacktypeid==11)
					{
						$detcontent_id = $item->detcontent_id;
					}
					if (!isset($tag) || $item->attacktypeid==1 || $item->attacktypeid==11)
					{
						$tag = $item->tag;
					}
					if (!isset($content) || $item->attacktypeid==1 || $item->attacktypeid==11)
					{
						$content = $item->content;
					}
					if ($item->attacktypeid==1 || $item->attacktypeid==11 || ($detcontent_id !=$item->detcontent_id && $tag != $item->tag && $content != $item->content))
					{
						$html .="<tr>";
						if ($item->attacktypeid==1 || $item->attacktypeid==11)
						{
							$detcontent_id ==$item->detcontent_id;
							$html .= "<td class='label'><div>".$item->name."</div></td>";
						}
						else
						{
							$html .= "<td class='label'><div>".$item->keyname."</div></td>";
						}
						
						$detcontent_id = $item->detcontent_id;
						$tag = $item->tag;
						$content = $item->content;
						$html .= "<td class='attackcontent'>";
						$html .= "Detected Content: <span style='color:red;'>".$item->content."</span>";
					}
					else if ($detcontent_id ==$item->detcontent_id && ($item->attacktypeid!=1 && $item->attacktypeid!=11) && ($tag != $item->tag))
					{
						$html .= ', '.$item->name;
					}
					else if ($detcontent_id ==$item->detcontent_id && ($item->attacktypeid!=1 && $item->attacktypeid!=11))
					{
						$html .= "<tr><td class='label'><div>AttackType: </div></td><td>".$item->name."</td>";
					}						
					else
					{
						$html .= "</td>";
						$html .= "</tr>";
					}
				}
				
			}			
		}
		$html .= "</table>";
		return $html; 
	}
	private function getACLAttackMapByIDDB($id)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT * FROM `#__osefirewall_attackmap` WHERE `aclid` = ". (int)$id.
				 " ORDER BY `var_id` ASC, `detcontent_id` ASC"; 
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results;
	}
	private function getACLIPMapByIDDB($id)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT * FROM `#__osefirewall_aclipmap` WHERE `id` = ". (int)$id; 
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result; 
	}
	private function getRefererByIDDB($id)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT `referer_url` FROM `#__osefirewall_referers` WHERE `id` =".(int)$id;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result['referer_url'];  
	}
	private function getPageByIDDB($id)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT `page_url` FROM `#__osefirewall_pages` WHERE `id` =".(int)$id;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result['page_url'];    
	}
	
	protected function getActionIcon($id, $status)
	{
		switch($status) {
                   	case '0': 
                   		return "<a href='#' onClick= 'changeItemStatus(".urlencode($id).", 1)' ><div class='ose-grid-delete'></div></a>";
                   	break;
                   	case '1': 
                   		return "<a href='#' onClick= 'changeItemStatus(".urlencode($id).", 0)' ><div class='ose-grid-accept'></div></a>";
                   	break;
                   	default: 
                   	 	return '';
                   	break;	
        }
	}
	private function getDetattacktypeIDByRuleID($rule_id, $attacktypeidArray)
	{
		$db = oseFirewall::getDBO ();
		$attacktypeids = '('.implode(',', $attacktypeidArray).')';   
		$query = "SELECT `detattacktype_id` FROM `#__osefirewall_attackmap` WHERE `rule_id` =".(int)$rule_id. " AND `attacktypeid` IN ". $attacktypeids; 
		$db ->setQuery($query);
		return $db->loadResultArray(); 
	}
	private function getDetattacktypeIDByVarID($var_id)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT `detattacktype_id` FROM `#__osefirewall_attackmap` WHERE `var_id` =".(int)$var_id; 
		$db ->setQuery($query);
		return $db->loadResultArray(); 
	}
	private function deleteAttackTypeDetailByID($detattacktype_id)
	{
		$db = oseFirewall::getDBO ();
		return $db->deleteRecord(array('detattacktype_id'=>$detattacktype_id), '#__osefirewall_detcontdetail');
	}
	private function deleteAttackTypebyID($detattacktype_id)
	{
		$db = oseFirewall::getDBO ();
		return $db->deleteRecord(array('id'=>$detattacktype_id), '#__osefirewall_detattacktype');
	}
	// $attacktype reads as 1,2,3,4,5; return as [1,2,3,4,5]  
	private function attackTypeEncode($attacktype)
	{
		$attacktype = explode(',', $attacktype);
		$i = 0 ;
		foreach($attacktype as $val)
		{
			$attacktype[$i]= (int)$val;
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
			$return[]= $attacktypes[(int)$attackid];
		}
		$return = implode(", ", $return); 
		return $return;	
	}
	
	private function deleteDectectedAttacks($detattacktype_ids)
	{
		foreach ($detattacktype_ids as $detattacktype_id)
		{ 
			$result = $this->deleteAttackTypeDetailByID($detattacktype_id);
			if ($result==false)
			{
				return false;
			}
			$result = $this->deleteDetectedByID($detattacktype_id);
			if ($result==false)
			{
				return false;
			}
			$result = $this->deleteAttackTypebyID($detattacktype_id);
			if ($result==false)
			{
				return false;
			}
		}
		return true; 
	}
	private function deleteDetectedByID($detattacktype_id)
	{
		$db = oseFirewall::getDBO ();
		return $db->deleteRecord(array('detattacktype_id'=>$detattacktype_id), '#__osefirewall_detected');
	}
	private function deleteFilterbyID($id)
	{
		$db = oseFirewall::getDBO ();
		return $db->deleteRecord(array('id'=>$id), '#__osefirewall_filters');
	}
	public function getVariables()
	{
		$limit = oRequest::getInt('limit', 25);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		$status = oRequest::getInt('status', null);
		$start = $limit * ($page-1);  
		return $this->convertVariables($this->getVariablesDB($search, $status, $start, $limit));
	}
	private function getVariablesDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO ();
		$where = array(); 
		if (!empty($search))
		{
			$where[] = "`keyname` LIKE ".$db->quoteValue('%'.$search.'%', true);
		}
		if ($status===1 || $status===2 || $status===3)
		{
			$where[] = "`status` = ".(int)$status;
		}
		if (!empty($limit))
		{
			$limit = "ASC LIMIT ".$start.", ".$limit;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_vars`".$where
				 ." ORDER BY id ".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList(); 
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
			$i ++; 
		}
		return $results; 
	}
	public function getVariablesTotal()
	{
		$db = oseFirewall::getDBO ();
		return $db->getTotalNumber('id', '#__osefirewall_vars');
	}
	public function changeVarStatus($id, $status)
	{
		$db = oseFirewall::getDBO ();
		$varValues = array (
				'status' => (int)$status
		);
		return $db->addData('update', '#__osefirewall_vars', 'id', (int)$id, $varValues);
	}
	public function addvariables($variable, $status)
	{
		$varObject = $this->getVariablebyName($variable);
		if (empty($varObject))
		{
			$db = oseFirewall::getDBO ();
			$varValues = array(
						'id' => 'DEFAULT',
						'keyname' => $variable,
						'status' => (int)$status
					);
			$id = $db->addData ('insert', '#__osefirewall_vars', '', '', $varValues);
			return $id;
		}
		else
		{
			return $varObject->id;
		}
	}
	private function getVariablebyName($variable)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT * FROM `#__osefirewall_vars`".
				 " WHERE `keyname` = ".$db->quoteValue($variable);
		$db->setQuery($query);
		$results = $db->loadObject(); 
		return $results; 
	}	
	public function deletevariable($id)
	{
		$detattacktype_ids = $this->getDetattacktypeIDByVarID($id);
		$result = $this->deleteDectectedAttacks($detattacktype_ids);	
		if ($result==true)
		{
			$result = $this->deleteVariablebyID($id);
		}	
		return $result; 
	}
	private function deleteVariablebyID($id)
	{
		$db = oseFirewall::getDBO ();
		return $db->deleteRecord(array('id'=>$id), '#__osefirewall_vars');
	}
	public function loadDefaultRules($type)
	{
		switch($type)
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
			if ($result==false)
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
			if ($result==false)
			{
				return false;
			}
		}
		return true;
	}
	public function getConfiguration($type)
	{
		$return = array(); 
		$db = oseFirewall::getDBO ();
		$query = "SELECT `key`, `value` FROM `#__ose_secConfig` WHERE `type` = ". $db->quoteValue($type); 
		$db->setQuery($query); 
		$results = $db->loadObjectList();
		foreach ($results as $result)
		{
			if ($type == 'l2var')
			{
				$return['data'][$result->key] = (int)$result->value;
			}
			else
			{
				$return['data'][$result->key] =  $this->convertValue($result->key , $result->value);
			}
		}
		$return['success'] = true; 
		return $return;  
	}
	private function convertValue($key, $value)
	{
		if (is_numeric($value))
		{
			$value = intval ($value);
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
		$db = oseFirewall::getDBO ();
		$query = "SELECT `id` FROM `#__ose_secConfig` WHERE `key` = ". $db->quoteValue($key). " AND `type` = ". $db->quoteValue($type); 
		$db->setQuery($query);
		$result = $db->loadResult();
		return (isset($result['id']))?$result['id']:false;
	}
	private function ConfVariableInsert($key, $value, $type)
	{
		$db = oseFirewall::getDBO ();
		$varValues = array(
						'id' => 'DEFAULT',
						'key' => $key,
						'value' => $value, 
						'type' => $type
					);
		$id = $db->addData ('insert', '#__ose_secConfig', '', '', $varValues);
		return $id;
	}
	private function ConfVariableUpdate($keyID, $value)
	{
		$db = oseFirewall::getDBO ();
		$varValues = array (
				'value' => $value
		);
		return $db->addData('update', '#__ose_secConfig', 'id', (int)$keyID, $varValues);
	}
	// OSE Firewall Basic Rulesets 
	public function getRulesets()
	{
		$limit = oRequest::getInt('limit', 25);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		$status = oRequest::getInt('status', null);
		$start = $limit * ($page-1);  
		return $this->convertRulesets($this->getRulesetsDB($search, $status, $start, $limit));
	}
	private function getRulesetsDB($search, $status, $start, $limit)
	{
		$db = oseFirewall::getDBO ();
		$where = array(); 
		if ($status===1 || $status===0)
		{
			$where[] = "`action` = ".(int)$status;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_basicrules`".$where
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
			$results[$i]->rule=oLang::_get($results[$i]->rule); 
			$results[$i]->action = $this->getActionIcon($results[$i]->id, $results[$i]->action);
			$results[$i]->attacktype = $this->attackTypeDecode($attacktypes, $results[$i]->attacktype);
			$i ++; 
		}
		return $results;
	}
	public function getRulesetsTotal()
	{
		$db = oseFirewall::getDBO ();
		return $db->getTotalNumber('id', '#__osefirewall_basicrules');
	}
	public function changeRulesetStatus ($id, $status) {
		$db = oseFirewall::getDBO ();
		$varValues = array (
				'action' => (int)$status
		);
		return $db->addData('update', '#__osefirewall_basicrules', 'id', (int)$id, $varValues);
		
	}
}