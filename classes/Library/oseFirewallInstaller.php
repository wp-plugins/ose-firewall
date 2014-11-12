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
if (OSE_CMS == 'joomla')
{
	require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'installer' . ODS . 'joomla.php');
}
else
{
	require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'installer' . ODS . 'wordpress.php');
}
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
		else {
			$query = "SELECT COUNT(id) as `count` FROM `#__osefirewall_basicrules` WHERE `id` = 11";
			$this->db->setQuery($query);
			$result = (object)$this->db->loadResult();
			if (empty($result->count))
			{
				$query = "INSERT INTO `#__osefirewall_basicrules` ( `id` , `rule` ,	`action` , `attacktype` )
						  VALUES ('11', 'FILE_UPLOAD_VALIDATION', '1', '[\"13\"]');";
				$this->db->setQuery($query);
				$result = $this->db->query();
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
		if (!empty($queries[1]) && $result['count'] == 0) {
			$this->db->setQuery($queries[1]);
				if (!$this->db->query()) {
					return false;
				}
		}
		else if (!empty($queries[1]))
		{
			$this->cleanVStable ();
			$this->db->setQuery($queries[1]);
			if (!$this->db->query()) {
				return false;
			}
		}		
		return true;
	}
	private function cleanVStable () {
		$query = "TRUNCATE `#__osefirewall_vspatterns` ";
		$this->db->setQuery($query);
		$result = $this->db->query();
		return $result; 
	}
	public function createACLIPView($dbFile) {
		/* Delete later
		$exists = $this->isViewExists('#__osefirewall_aclipmap');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		*/
		return true;
	}
	public function createAdminEmailView($dbFile) {
		/* Delete later
		$exists = $this->isViewExists('#__ose_app_adminemailmap');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$query = $this->replaceVars($query);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		*/
		return true;
	}
	public function createCountryDB($dbFile){
		$exists = $this->isViewExists('#__osefirewall_country');
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
		if (OSE_CMS =='wordpress') 
		{
			$query = str_replace('`users`.`name`', '`users`.`user_nicename` AS `name` ', $query);
			$query = str_replace('`users`.`email`', '`users`.`user_email` AS `email`', $query);
		}
		return $query;
	}
	public function createAttackmapView($dbFile) {
		/* Delete later
		$exists = $this->isViewExists('#__osefirewall_attackmap');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		*/
		return true;
	}
	public function createAttacktypeView($dbFile) {
		/* Delete later
		$exists = $this->isViewExists('#__osefirewall_attacktypesum');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		*/
		return true;
	}
	public function createDetMalwareView($dbFile) {
		/* Delete later
		$exists = $this->isViewExists('#__osefirewall_detmalware');
		if ($exists == false) {
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		*/
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
	public function cleanGeoIPDB ($step) {
		$stage = $step-1; 
		$dbFile = OSE_FWDATA . ODS . 'osegeoip{num}.sql';
		$dbFile = str_replace('{num}', $stage, $dbFile);
		if (file_exists($dbFile))
		{
			oseFile::delete($dbFile); 
		}	
		return true;
	}
	public function cleanCountryDB(){
		$dbFile = OSE_FWDATA . ODS . 'wp_osefirewall_country.sql';
		$result = true; 
		if(file_exists($dbFile))
		{
			$result = oseFile::delete($dbFile);
		}
		return $result;
	}
	public function insertConfigData($dbFile, $key){
		$query = "SELECT `key` FROM `#__ose_secConfig` WHERE `key` = ". $this->db->quoteValue($key);
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		if (empty($result))
		{
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if(!$this->db->query()) {
				return false;
			}
		}
		else
		{
			$file_ext = $this->getFileExt ();
			if (strpos($file_ext, '[')>-1)
			{
				$query = "UPDATE `#__ose_secConfig` SET `value` = 'htm,html,shtm,shtml,css,js,php,php3,php4,php5,inc,phtml,jpg,jpeg,gif,png,bmp,c,sh,pl,perl,cgi,txt' WHERE `key` ='file_ext';";
				$this->db->setQuery($query);
				if(!$this->db->query()) {
					return false;
				}				
			}	 
		}	
		return true;
	}
	private function getFileExt () {
		$config = oseFirewall::getConfiguration('vsscan');
		$this->config = (object)$config['data'];
		return (isset($this->config->file_ext))?$this->config->file_ext:null; 
	}
	private function getAdvRuleTableName ($type)
	{
		switch ($type) {
			case 'ath':
				return '#__osefirewall_advancerules';
				break;
			case 'avs':
			case 'bsavs':
				return '#__osefirewall_vspatterns';
				break;
		}
	}
	public function insertAdvRuleset($dbFile, $type) {
		$data = $this->readSQLFile($dbFile);
		if (strstr($data,'Centrora Security API')!=false)
		{
			return false;
		}	
		$queries = $this->_splitQueries($data);
		$createTable = $queries[0]; 
		$this->db->setQuery($createTable);
		$result = $this->db->query(); 
		$table = $this -> getAdvRuleTableName ($type); 
		$query = "SELECT COUNT(id) as `count` FROM ". $this->db->QuoteTable($table);
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		if ($result['count'] == 0) {
			$query = $queries[1];
			$this->db->setQuery($query);
			if (!$this->db->query()) {
				return false;
			}
		}
		return true;
	}
	public function updateAdvRuleset ($dbFile, $type) {
		$table = $this -> getAdvRuleTableName ($type); 
		$query = "SELECT COUNT(id) as `count` FROM ". $this->db->QuoteTable($table);
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		if ($result['count'] > 0) {
			$data = $this->readSQLFile($dbFile);
			$queries = $this->_splitQueries($data);
			$this->padTable ($result['count'], COUNT($queries));
			foreach ($queries as $query) 
			{
				$this->db->setQuery($query);
				if (!$this->db->query()) {
					return false;
				}
			}
		}
		return true;
	}
	private function padTable ($original_count, $new_count) {
		$diff = $new_count-$original_count;
		if ($diff>0)
		{
			for ($i=0; $i<$diff; $i++)
			{
				$id = $original_count+1+$i; 
				$query = "INSERT INTO `#__osefirewall_advancerules` (`id`, `filter`, `action`, `attacktype`, `impact`, `description`)  VALUES  
						  (".(int)$id.", '', 1, '', 0, '')";
				$this->db->setQuery($query);
				if (!$this->db->query()) {
					break;
				}
			}
		} 
	}
} 