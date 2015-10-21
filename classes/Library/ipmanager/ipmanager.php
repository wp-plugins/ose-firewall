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
class oseFirewallIpManager
{
	private $ip = null;
	private $ipend = null;
	private $ipStatus = null;
	private $aclid = null;
	private $db = null;
	private $countryBlock = null;
	public function __construct($db)
	{
		oseFirewall::callLibClass('convertviews', 'convertviews');
		$this->setIP();
		if ($db!=null) {
			$this->db = $db;
			$this->checkIPStatus();
		}
	}
	public function getIP()
	{
		return $this->ip;
	}
	public function getIPLong($start = true)
	{
		if ($start == true)
		{
			return substr("0000000000" .sprintf ('%u',ip2long($this->ip)), -10);
		}
		else
		{
			return substr("0000000000" .sprintf ('%u',ip2long($this->ipend)), -10);
		}
	}
	public function setIPRange($ipstart, $ipend)
	{
		$this->ip = $this->clearIPAddress($ipstart);
		$this->ipend = $this->clearIPAddress($ipend);
	}
	private function clearIPAddress ($ip) {
		$tmp= explode('.', $ip);
		foreach ($tmp as $key => $val)  {
			$tmp[$key] = ltrim($val, '0');
            if (empty($tmp[$key])) {
                $tmp[$key] = "0";
            }
		}
		return implode('.',$tmp);
	}
	private function setIP()
	{
		$this->ip = $this->getRealIP();
	}
	public function resetIP($ip)
	{
		$this->ip = $ip;
	}
	public function getIPStatus()
	{
		return $this->ipStatus;
	}
	public function getACLID()
	{
		return $this->aclid;
	}
	public function checkIPValidity($start = true)
	{
		if ($start == true)
		{
			return $this->checkIsValidIP($this->ip);
		}
		else
		{
			return $this->checkIsValidIP($this->ipend);
		}
	}
	private function checkCountryIPStatus()
	{
		if ($this->countryBlock && ($this->ipStatus == 3 || $this->ipStatus == 2 || $this->ipStatus == null))
		{
			$ipLong = $this->getIPLong(true);
			$query = "SELECT `country`.`status`
					  FROM `#__osefirewall_country` `country` LEFT JOIN `#__ose_app_geoip` `geoip`
					  ON `country`.`country_code` = `geoip`.`country_code`
					  WHERE `ip32_start`<= ".$this->db->quoteValue($ipLong)." AND ".$this->db->quoteValue($ipLong)."<=`ip32_end`";
			$this->db->setQuery($query);
			$result = $this->db->loadObject();
			if (!empty($result))
			{
				$this->ipStatus = $result->status;
			}
		}
	}
	private function checkCountryBlockEnable()
	{
		$data = $this->db->isTableExists('#__osefirewall_advancerules');
		if (empty($data))
		{
			$this->countryBlock = false;
		}
		else
		{
			$query = "SELECT `value` FROM `#__ose_secConfig` "."WHERE `key` = ".$this->db->quoteValue("blockCountry");
			$this->db->setQuery($query);
			$result = $this->db->loadObject();
			if (!empty($result))
			{
				if ($result->value == 1)
					$this->countryBlock = true;
				else
					$this->countryBlock = false;
			}
			else
			{
				$this->countryBlock = false;
			}
		}
	}
	private function checkIPStatus()
	{
		$ipLong = $this->getIPLong(true);
		$attrList = array("`acl`.`id` AS `id`", "`acl`.`status` AS `status`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql." WHERE `ip32_start` = ".$this->db->quoteValue($ipLong);
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		if (empty($result))
		{
			$attrList = array("`acl`.`id` AS `id`", "`acl`.`status` AS `status`");
			$sql = convertViews::convertAclipmap($attrList);
			$query = $sql." WHERE `ip32_start`<= ".$this->db->quoteValue($ipLong)." AND ".$this->db->quoteValue($ipLong)."<=`ip32_end`";
			$this->db->setQuery($query);
			$result = $this->db->loadObject();
		}
		if (!empty($result))
		{
			$this->aclid = $result->id;
			$this->ipStatus = $result->status;
		}
		else
		{
			$this->aclid = null;
			$this->ipStatus = null;
		}
	}
	public function checkIPRangeStatus()
	{
		$ipStartLong = $this->getIPLong(true);
		$ipEndLong = $this->getIPLong(false);
		$attrList = array("`acl`.`id` AS `id`", "`acl`.`status` AS `status`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql."WHERE `ip32_start`= ".$this->db->quoteValue($ipStartLong)." AND `ip32_end`=".$this->db->quoteValue($ipEndLong);
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		if (!empty($result))
		{
			$this->aclid = $result->id;
			$this->ipStatus = $result->status;
		}
		else
		{
			$this->aclid = null;
			$this->ipStatus = null;
		}
	}
	public function addIP($type, $aclid)
	{
		if ($type == 'ip')
		{
			$ipstart = $this->getIPLong(true);
			$ipend = $this->getIPLong(true);
			$iptype = 0;
		}
		else
		{
			$ipstart = $this->getIPLong(true);
			$ipend = $this->getIPLong(false);
			$iptype = 1;
		}
		return $this->InsertIP($aclid, $ipstart, $ipend, $iptype);
	}
	private function InsertIP($aclid, $ipstart, $ipend, $iptype)
	{
		$attrList = array("`ip`.`id` AS `ipid`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql."WHERE `ip32_start`= ".$this->db->quoteValue($ipstart)." AND ".$this->db->quoteValue($ipend)."=`ip32_end`";
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		if (empty($result))
		{
			$varValues = array(
				'ip32_start' => $ipstart,
				'ip32_end' => $ipend,
				'acl_id' => $aclid,
				'iptype' => $iptype
			);
			$id = $this->db->addData('insert', '#__osefirewall_iptable', '', '', $varValues);
		}
		else
		{
			return $result->ipid;
		}
	}
	private function cidr_match($ip, $cidr)
	{
		list($subnet, $mask) = explode('/', $cidr);
	
		if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet))
		{
			return true;
		}
	
		return false;
	}
	private function isCloudFlareIPs($ip) {
		// Cloudflare IP addresses;
		$array = array();
		$array[] = '103.21.244.0/22';
		$array[] = '103.22.200.0/22';
		$array[] = '103.31.4.0/22';
		$array[] = '104.16.0.0/12';
		$array[] = '108.162.192.0/18';
		$array[] = '141.101.64.0/18';
		$array[] = '162.158.0.0/15';
		$array[] = '172.64.0.0/13';
		$array[] = '173.245.48.0/20';
		$array[] = '188.114.96.0/20';
		$array[] = '190.93.240.0/20';
		$array[] = '197.234.240.0/22';
		$array[] = '198.41.128.0/17';
		$array[] = '199.27.128.0/21';
		
		foreach ($array as $cidr) {
			$result = $this->cidr_match($ip, $cidr);
			if ($result == true) {
				return true;
			}
		}
		return false;
	}
	private function getRealIP()
	{
		$ip = null;
		if (!empty($_SERVER['REMOTE_ADDR']))
		{
			if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				if ($this->isCloudFlareIPs($_SERVER['REMOTE_ADDR'])==true) {
					$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
				}
			}
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		else 
		{
			if (!empty($_SERVER['HTTP_CLIENT_IP']))
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
				if ($ip)
				{
					array_unshift($ips, $ip);
					$ip = null;
				}
				$this->tvar = phpversion();
				for ($i = 0, $total = count($ips); $i < $total; $i++)
				{
					if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i]))
					{
						if (version_compare($this->tvar, "5.0.0", ">="))
						{
							if (ip2long($ips[$i]) != false)
							{
								$ip = $ips[$i];
								break;
							}
						}
						else
						{
							if (ip2long($ips[$i]) != - 1)
							{
								$ip = $ips[$i];
								break;
							}
						}
					}
				}
			}
		}	
		return $ip;
	}
	public function isSearchEngineBot($crawlers, $userAgent)
	{
		$isCrawler = (preg_match("/$crawlers/", $userAgent) > 0);
		return $isCrawler;
	}
	private function checkIsValidIP($ipAddress)
	{
		$ipAddress = explode('.', $ipAddress);
		self::toInteger($ipAddress, array(
			1, 1, 1, 1
		));
		foreach ($ipAddress as $key => $ip)
		{
			if (!isset($ip))
			{
				return array(
					false,
					oLang::_get("IP_EMPTY")
				);
			}
			elseif ($ip > 255)
			{
				return array(
					false,
					oLang::_get("IP_INVALID_PLEASE_CHECK")
				);
			}
		}
		return array(
			true,
			null
		);
	}
	public static function toInteger(&$array, $default = null)
	{
		if (is_array($array))
		{
			foreach ($array as $i => $v)
			{
				$array[$i] = (int) $v;
			}
		}
		else
		{
			if ($default === null)
			{
				$array = array();
			}
			elseif (is_array($default))
			{
				self::toInteger($default, null);
				$array = $default;
			}
			else
			{
				$array = array(
					(int) $default
				);
			}
		}
	}

	public function clearBlacklistIP ($clearIPKey)
	{
		$storedkey = $this->getCronKey();
		if (!empty($storedkey) && $storedkey == $clearIPKey ) {
			$this->loadFirewallStat();
			$oseFirewallStat = new oseFirewallStat();
			$aclids = $this->getBlacklistACLIDs();
			$numcleared = 0;
			foreach ($aclids as $key => $aclid) {
				$result = $oseFirewallStat->removeACLRule($aclid->id);
				if ($result == false) {
					return false;
				}
				$numcleared++;
			}
			return $numcleared;
		} else {
			die ('Invalid Call! Are you sure you are allowed to do this? :)');
		}
	}

	private function getCronKey()
	{
		$confArray = oseFirewall::getConfiguration('advscan');
		if (!empty($confArray['data']['clearCronKey'])) {
			return $confArray['data']['clearCronKey'];
		}
		return;
	}

	private function getBlacklistACLIDs ()
	{
		$query = 'SELECT id FROM wp_osefirewall_acl WHERE status = 1';
		$this->db->setQuery($query);
		return $this->db->loadObjectList();
	}

	private function loadFirewallStat()
	{
		if (OSE_CMS == 'joomla') {
			oseFirewall::callLibClass('firewallstat', 'firewallstatJoomla');
		} else {
			oseFirewall::callLibClass('firewallstat', 'firewallstatWordpress');
		}
	}
}