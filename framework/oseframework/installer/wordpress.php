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
require_once (dirname(__FILE__) . DS . 'installer.php');
class oseInstaller extends Installer {
	protected $db = null;
	public function __construct() {
		$this->db = $this->getDatabase();
		oseWordPress :: loadFiles();
		oseWordPress :: loadRequest ();
	}
	protected function getDatabase() {
		$oseDB2 = oseWordpress :: getDBO();
		return $oseDB2;
	}
	public function createTables($dbFile) {
		$data = $this->readSQLFile($dbFile);
		$queries = $this->_splitQueries($data);
		foreach ($queries as $query)
		{	
			$this->db->setQuery($query);
			if(!$this->db->query()) {
				return false;
			}
		}
		return true; 
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
		return true; 
	}
	public function insertEmailData ($dbFile, $key) {
	 	$query = "SELECT COUNT(id) as `count` FROM `#__ose_app_email` WHERE `app` = ". $this->db->quoteValue($key);
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		if ($result['count']==0)
		{
			$query = $this->readSQLFile($dbFile);
			$this->db->setQuery($query);
			if(!$this->db->query()) {
				return false;
			}
		}
		return true; 
	 }
	protected function isViewExists($view)
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
		{
			$query= "SHOW CREATE VIEW `{$view}`";
		}
		else
		{		
			$query= "SHOW TABLE STATUS LIKE '{$view}'";
		}
		$query = $this->db->setQuery($query);
		$result= $this->db->loadResult();
		return (!empty($result))?true: false; 
	}
}