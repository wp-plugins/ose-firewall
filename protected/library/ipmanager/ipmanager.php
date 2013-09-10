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
	die("Direct Access Not Allowed");
}
class oseFirewallIpManager {
	private $ip = null;
	private $ipend = null;
	private $ipStatus = null;
	private $aclid = null;
	private $db = null;
	public function __construct($db) {
		$this->db = $db; 
		$this->setIP();
		$this->checkIPStatus();
	}
	public function getIP() {
		return $this->ip;
	}
	public function getIPLong($start = true) {
		if ($start == true) {
			return ip2long($this->ip);
		} else {
			return ip2long($this->ipend);
		}
	}
	public function setIPRange($ipstart, $ipend) {
		$this->ip = $ipstart;
		$this->ipend = $ipend;
	}
	private function setIP() {
		$this->ip = $this->getRealIP();
	}
	public function resetIP($ip) {
		$this->ip = $ip;
	}
	public function getIPStatus() {
		return $this->ipStatus;
	}
	public function getACLID() {
		return $this->aclid;
	}
	public function checkIPValidity($start = true) {
		if ($start == true) {
			return $this->checkIsValidIP($this->ip);
		} else {
			return $this->checkIsValidIP($this->ipend);
		}
	}
	private function checkIPStatus() {
		$ipLong = $this->getIPLong(true);
		$query = "SELECT `id`, `status` FROM `#__osefirewall_aclipmap` " .	"WHERE `ip32_start` = " . $this->db->quoteValue($ipLong);
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		if (empty ($result)) {
			$query = "SELECT `id`, `status` FROM `#__osefirewall_aclipmap` " .	"WHERE `ip32_start`<= " . $this->db->quoteValue($ipLong) . " AND " . $this->db->quoteValue($ipLong) . "<=`ip32_end`";
			$this->db->setQuery($query);
			$result = $this->db->loadObject();
		}
		if (!empty ($result)) {
			$this->aclid = $result->id;
			$this->ipStatus = $result->status;
		} else {
			$this->aclid = null;
			$this->ipStatus = null;
		}
	}
	public function checkIPRangeStatus() {
		$db = oseFirewall :: getDBO();
		$ipStartLong = $this->getIPLong(true);
		$ipEndLong = $this->getIPLong(false);
		$query = "SELECT `id`, `status` FROM `#__osefirewall_aclipmap` " .		"WHERE `ip32_start`= " . $this->db->quoteValue($ipStartLong) . " AND `ip32_end`=" . $this->db->quoteValue($ipEndLong);
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		if (!empty ($result)) {
			$this->aclid = $result->id;
			$this->ipStatus = $result->status;
		} else {
			$this->aclid = null;
			$this->ipStatus = null;
		}
	}
	public function addIP($type, $aclid) {
		if ($type == 'ip') {
			$ipstart = $this->getIPLong(true);
			$ipend = $this->getIPLong(true);
			$iptype = 0;
		} else {
			$ipstart = $this->getIPLong(true);
			$ipend = $this->getIPLong(false);
			$iptype = 1;
		}
		return $this->InsertIP($aclid, $ipstart, $ipend, $iptype);
	}
	private function InsertIP($aclid, $ipstart, $ipend, $iptype) {
		$db = oseFirewall :: getDBO();
		$query = "SELECT `ipid` FROM `#__osefirewall_aclipmap` " .		"WHERE `ip32_start`= " . $this->db->quoteValue($ipstart) . " AND " . $this->db->quoteValue($ipend) . "=`ip32_end`";
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		if (empty ($result)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'ip32_start' => $ipstart,
				'ip32_end' => $ipend,
				'acl_id' => $aclid,
				'iptype' => $iptype
			);
			$id = $this->db->addData('insert', '#__osefirewall_iptable', '', '', $varValues);
		} else {
			return $result->ipid;
		}
	}
	private function getRealIP() {
		$ip = false;
		if (!empty ($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		if (!empty ($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip) {
				array_unshift($ips, $ip);
				$ip = false;
			}
			$this->tvar = phpversion();
			for ($i = 0, $total = count($ips); $i < $total; $i++) {
				if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])) {
					if (version_compare($this->tvar, "5.0.0", ">=")) {
						if (ip2long($ips[$i]) != false) {
							$ip = $ips[$i];
							break;
						}
					} else {
						if (ip2long($ips[$i]) != -1) {
							$ip = $ips[$i];
							break;
						}
					}
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	private function isSearchEngineBot($crawlers, $userAgent) {
		$isCrawler = (preg_match("/$crawlers/", $userAgent) > 0);
		return $isCrawler;
	}
	private function checkIsValidIP($ipAddress) {
		$ipAddress = explode('.', $ipAddress);
		self :: toInteger($ipAddress, array (
			1,1,1,1
		));
		foreach ($ipAddress as $key => $ip) {
			if (!isset ($ip)) {
				return array (
					false,
					oLang::_get("IP_EMPTY")
				);
			}
			elseif ($ip > 255) {
				return array (
					false,
					oLang::_get("IP_INVALID_PLEASE_CHECK")
				);
			}
		}
		return array (
			true,
			null
		);
	}
	public static function toInteger(& $array, $default = null) {
		if (is_array($array)) {
			foreach ($array as $i => $v) {
				$array[$i] = (int) $v;
			}
		} else {
			if ($default === null) {
				$array = array ();
			}
			elseif (is_array($default)) {
				self :: toInteger($default, null);
				$array = $default;
			} else {
				$array = array (
					(int) $default
				);
			}
		}
	}
}