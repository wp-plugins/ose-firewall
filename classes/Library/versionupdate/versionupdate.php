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
class oseVersionUpdate {
	public function __construct() {
		oseFirewall::loadRequest ();
	}
	public function checkEmptyTable() {
		$result = array ();
		$db = oseFirewall::getDBO ();
		$query = "SELECT `time` FROM `#__osefirewall_updateLog`";
		$db->setQuery ( $query );
		$result = $db->loadObjectList ();
		$db->closeDBO ();
		if (empty ( $result ) == false) {
			return false;
		} else
			return true;
	}
	public function addUpdateLog($value) {
		$db = oseFirewall::getDBO ();
		$varValues = array (
				'time' => $value 
		);
		$id = $db->addData ( 'insert', '#__osefirewall_updateLog', '', '', $varValues );
		$db->closeDBO ();
		return $id;
	}
	public function getLatestLog() {
		$db = oseFirewall::getDBO ();
		$query = "SELECT `time` FROM `#__osefirewall_updateLog` ORDER BY `id` DESC LIMIT 1";
		$db->setQuery ( $query );
		$result = $db->loadResult ();
		$db->closeDBO ();
		return $result; 
	}
	public function getCurrentDate() {
		date_default_timezone_set ( 'Australia/Melbourne' );
		return date ( 'Y-m-d' );
	}
	public function getLatestVersion() {
		$db = oseFirewall::getDBO ();
		$query = "SELECT `version` FROM `#__osefirewall_virusVersion` ORDER BY `version_id` DESC LIMIT 1";
		$db->setQuery ( $query );
		$result = $db->loadResult();
		$db->closeDBO ();
		return $result; 
	}
	public function addPatterns($vsPattern) {
		$db = oseFirewall::getDBO ();
		$varValues = array (
			'patterns' => $vsPattern['patterns'],
			'type_id' => $vsPattern['type_id'],
			'confidence' => $vsPattern['confidence']
		);
		$id = $db->addData ( 'insert', '#__osefirewall_vspatterns', '', '', $varValues );
		$db->closeDBO ();
		return $id;
	}
	public function addVersions($vsVersion) {
		$result = $this->getVersions($vsVersion);
		if (($vsVersion['version'] == $result[version]) && ($vsVersion['plugin'] == $result[plugin])){
			return;
		}
		else{
			$db = oseFirewall::getDBO ();
			$varValues = array (
					'version_id' => 'DEFAULT',
					'version' => $vsVersion['version'],
					'plugin' => $vsVersion['plugin']
			);
			$id = $db->addData ('insert', '#__osefirewall_virusVersion', '', '', $varValues );
			$db->closeDBO ();
			return $id;
		}
	}
	public function getVersions($vsVersion) {
		$db = oseFirewall::getDBO ();
		$query = "SELECT `version_id`, `version`, `plugin` FROM `#__osefirewall_virusVersion` ORDER BY `version_id` DESC LIMIT 1";
		$db->setQuery ( $query );
		$result = $db->loadResult();
		$db->closeDBO ();
		return $result; 
	}
	
	public function getUsername() {
		$db = oseFirewall::getDBO ();
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'Name'";
		$db->setQuery ($query);
		$result = $db->loadResult();
		$db->closeDBO ();
		return $result; 
	}
	public function getPassword() {
		$db = oseFirewall::getDBO ();
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'Password'";
		$db->setQuery ($query);
		$result = $db->loadResult();
		$db->closeDBO ();
		return $result; 
	}
}