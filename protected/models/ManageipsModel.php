<?php
/**
* @version     2.0 +
* @package       Open Source Excellence Security Suite
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
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
class ManageipsModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function loadLocalScript() {
		$lang = oseFirewall::getLocale (); 
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$cs->registerScriptFile($baseUrl . '/public/messages/'.$lang.'.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile($baseUrl . '/public/js/manageips.js', CClientScript::POS_END);
	}
	public function getCHeader() {
		return oLang :: _get('MANAGEIPS_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('MANAGEIPS_DESC');
	}
	public function getACLIPMap()
	{
		$return = array(); 
		$oseFirewallStat = new oseFirewallStat();
		$return['id']=1; 
		$return['results']= $oseFirewallStat->getACLIPMap();
		if (empty($return['results']))
		{
			$return['results']['id'] = 0;
			$return['results']['name'] = 'N/A';
		}
		$return['total']= $oseFirewallStat->getACLIPTotal();
		return $return; 
	}
	public function addACLRule($name, $ip_start, $ip_end, $ip_type, $ip_status)
	{
		$url = 'http://' . str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		$page_id = $this->addPages($url, 0);
		$referer_id = $this->addReferer();
		$varValues = array (
				'id' => 'DEFAULT',
				'name' => $name,
				'datetime' => date('Y-m-d h:i:s'),
				'score' => 0,
				'status' => (int) $ip_status,
				'referers_id' => $referer_id,
				'pages_id' => $page_id
		);
		$aclid = $this->db->addData('insert', '#__osefirewall_acl', null, null, $varValues);
		if (!empty ($aclid)) {
				$ipmanager = new oseFirewallIpManager($this->db);
				$ipmanager->setIPRange($ip_start, $ip_end);
				$ipmanager->addIP($ip_type, $aclid);
				return true; 
		}
	}
	public function removeACLRule($aclids)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($aclids as $aclid)
		{
			$result = $oseFirewallStat->remvoeACLRule($aclid);
			if ($result == false)
			{
				return false;
			}
		}
		return true; 
	}
	public function changeACLStatus($aclids, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($aclids as $aclid)
		{
			$result = $oseFirewallStat->changeACLStatus($aclid, $status);
			if ($result == false)
			{
				return false;
			}
		}
		return true; 
	}
	public function updateHost($aclids)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($aclids as $aclid)
		{
			$result = $oseFirewallStat->updateHost($aclid);
			if ($result == false)
			{
				return false;
			}
		}
		return true; 
	}
	public function getAttackDetail($aclid)
	{
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getAttackDetail($aclid); 
	}
}