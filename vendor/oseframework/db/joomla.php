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
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH') && !defined('_JEXEC'))
{
	die('Direct Access Not Allowed');
}
require_once(dirname(__FILE__).ODS.'oseDB2.php');
class oseDB2Joomla extends oseDB2
{
	public function __construct()
	{
		$this->dbo = self::getConnection();
		$this->setPrefix ();
	}
	protected function setPrefix()
	{
		$config = oseJoomla::getConfig();
		$this->prefix = $config->prefix;
	}
	public static function getConnection()
	{
		if(!isset(self::$dbh) && empty(self::$dbh)) {
			$config = oseJoomla::getConfig ();
			$host = explode ( ':', $config->host );
			if (! empty ( $host [1] )) {
				$dsn = 'mysql:host=' . $host [0] . ';port=' . $host [1] . ';dbname=' . $config->db;
			} else {
				$dsn = 'mysql:host=' . $host [0] . ';dbname=' . $config->db;
			}
			try {
				self::$dbh = new PDO($dsn, $config->user, $config->password);
			} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
		}
		return self::$dbh;
		
	}
	public function getTableList()
	{
		$query = "SHOW TABLES ; ";
		$this->setQuery($query);
		$results = $this->loadResultArray();
		$list = array();
		$config = oseJoomla::getConfig();
		$index = "Tables_in_".$config->db;
		foreach ($results as $result)
		{
			if (!preg_match("/(ose_app_geoip)|(osefirewall_country)/", $result[$index]))
			{
				$tmp = array_values($result);
				$list[] = $tmp[0];
			}
		}
		return $list;
	}
}
