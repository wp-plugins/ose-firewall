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
require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS . 'installer' . DS . 'wordpress.php');
class oseFirewallInstaller extends oseInstaller {
	public function __construct() {
		parent :: __construct();
	}
	public function insertAttackType($dbFile) {
		$query = "SELECT COUNT(id) as `count` FROM `#__osefirewall_attacktype` ";
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		if ($result['count'] == 0) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		return true;
	}
	public function insertBasicRules($dbFile) {
		$query = "SELECT COUNT(id) as `count` FROM `#__osefirewall_basicrules` ";
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		if ($result['count'] == 0) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		return true;
	}
	public function insertVspatterns($dbFile) {
		$data = $this->readSQLFile($dbFile);
		$queries = $this->_splitQueries($data);
		
		$query = "SELECT COUNT(id) as `count` FROM `#__osefirewall_vstypes` ";
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		if ($result['count'] == 0) {
			$this->db->setQuery($queries[0]);
				if (!$this->db->query()) {
					return false;
				}
		}
		$query = "SELECT COUNT(id) as `count` FROM `#__osefirewall_vspatterns` ";
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		if ($result['count'] == 0) {
			$this->db->setQuery($queries[1]);
				if (!$this->db->query()) {
					return false;
				}
		}		
		return true;
	}
	public function createACLIPView($dbFile) {
		$exists = $this->isViewExists('#__osefirewall_aclipmap');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		return true;
	}
	public function createAdminEmailView($dbFile) {
		$exists = $this->isViewExists('#__ose_app_adminemailmap');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$query = $this->replaceVars($query);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		return true;
	}
	private function replaceVars($query) {
		$query = str_replace('`users`.`name`', '`users`.`user_nicename` AS `name` ', $query);
		$query = str_replace('`users`.`email`', '`users`.`user_email` AS `email`', $query);
		return $query;
	}
	public function createAttackmapView($dbFile) {
		$exists = $this->isViewExists('#__osefirewall_attackmap');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		return true;
	}
	public function createAttacktypeView($dbFile) {
		$exists = $this->isViewExists('#__osefirewall_attacktypesum');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		return true;
	}
	public function createDetMalwareView($dbFile) {
		$exists = $this->isViewExists('#__osefirewall_detmalware');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		return true;
	}
	public function installGeoIPDB($step, $dbFile) {
		$stage = $this->getGeoIPStage();
		if ($step == $stage || $stage > 6) {
			return true;
		} else {
			$dbFile = str_replace('{num}', $stage, $dbFile);
			$data = $this->readSQLFile($dbFile);
			$queries = $this->_splitQueries($data);
			foreach ($queries as $query) {
				$this->db->setQuery($query);
				if (!$this->db->query()) {
					return false;
				}
			}
			return true;
		}
	}
	private function getGeoIPStage() {
		$query = "SELECT COUNT(`id`) as `count` FROM `#__ose_app_geoip` ";
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		$return = ceil($result['count'] / 25000);
		return $return;
	}
}