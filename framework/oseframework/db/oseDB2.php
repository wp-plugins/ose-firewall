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
if (!class_exists('oseDB2', false))
{
	abstract class oseDB2
	{
		protected $dbo = null;
		protected $prefix = null;
		public $query = null;
		public function __construct()
		{
			$this->dbo = $this->getConnection();
			$this->setPrefix ();
		}
		public function getDBO()
		{
			return $this->dbo;
		}
		abstract protected function getConnection();
		abstract protected function setPrefix();
		public function implodeWhere($where = array())
		{
			$where = (count($where) ? ' WHERE ('.implode(') AND (', $where).')' : '');
			return $where;
		}
		public function getPrefix()
		{
			return $this->prefix;
		}
		public function isTableExists($table)
		{
			$query = $this->setQuery($table);
			$data = $this->getDBO()->schema->getTable($query);
			return $data;
		}
		public function setQuery($query)
		{
			$this->query = str_replace('#__', $this->prefix, $query);
			return $this->query;
		}
		public function query()
		{
			$command = $this->dbo->createCommand($this->query);
			return $command->query();
		}
		public function closeDBO()
		{
			$this->dbo->setActive(0);
		}
		public function loadResult()
		{
			$command = $this->dbo->createCommand($this->query);
			return $command->queryRow();
		}
		public function quoteValue($value)
		{
			return $this->dbo->quoteValue($value);
		}
		public function quoteKey($value)
		{
			return $this->dbo->quoteColumnName($value);
		}
		public function quoteTable($value)
		{
			return $this->dbo->quoteTableName($value);
		}
		public function loadResultArray()
		{
			$command = $this->dbo->createCommand($this->query);
			$results = $command->queryAll();
			return $results;
		}
		public function loadArrayList($var)
		{
			$command = $this->dbo->createCommand($this->query);
			$results = $command->queryAll();
			$return = array();
			foreach ($results as $key => $value)
			{
				$return[$key] = $value[$var];
			}
			return $return;
		}
		public function loadObjectList()
		{
			$i = 0;
			$command = $this->dbo->createCommand($this->query);
			$results = $command->queryAll();
			foreach ($results as $result)
			{
				$results[$i] = (object) $result;
				$i++;
			}
			return $results;
		}
		public function loadResultList()
		{
			$i = 0;
			$command = $this->dbo->createCommand($this->query);
			$results = $command->queryAll();
			foreach ($results as $result)
			{
				$results[$i] = $result;
				$i++;
			}
			return $results;
		}
		public function loadObject()
		{
			$command = $this->dbo->createCommand($this->query);
			$result = $command->queryRow();
			if (!empty($result))
			{
				return (object) $result;
			}
			else
			{
				return false;
			}
		}
		public function getTableFields($tables, $typeonly = true)
		{
			settype($tables, 'array'); //force to array
			$result = array();
			foreach ($tables as $tblval)
			{
				$this->setQuery('SHOW FIELDS FROM '.$tblval);
				$fields = $this->loadObjectList();
				if ($typeonly)
				{
					foreach ($fields as $field)
					{
						$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/", '', $field->Type );
					}
				}
				else
				{
					foreach ($fields as $field)
					{
						$result[$tblval][$field->Field] = $field;
					}
				}
			}
			return $result;
		}
		public function countTableFields($tblval)
		{
			$result = array();
			$this->setQuery('SHOW FIELDS FROM '.$tblval);
			$fields = $this->loadObjectList();
			$result = count($fields);
			return $result;
		}
		public function buildInsertQuery($table, $insertValues)
		{
			$filterValues = array();
			foreach ($insertValues as $key => $value)
			{
				$filterValues[$key] = $this->quoteValue($value);
			}
			$tables = $this->getTableFields($table);
			$temp = array();
			foreach ($tables[$table] as $field => $info)
			{
				if (isset($filterValues[$field]) && (!empty($filterValues[$field]) || (is_numeric($filterValues[$field]) && $filterValues[$field] == 0)))
				{
					$temp[$field] = $filterValues[$field];
				}
			}
			$filterValues = $temp;
			$sql = array();
			$sql1 = '(`'.implode('`,`', array_keys($filterValues)).'`)';
			$sql2 = '('.implode(',', $filterValues).')';
			$query = " INSERT INTO `{$table}` {$sql1} VALUES {$sql2}";
			return $query;
		}
		public function buildUpdateQuery($table, $keyId, $keyValue, $updateValues)
		{
			$filterValues = array();
			foreach ($updateValues as $key => $value)
			{
				if ($key == $keyId)
				{
					continue;
				}
				$filterValues[$key] = $this->quoteValue($value);
			}
			$tables = $this->getTableFields($table);
			$temp = array();
			foreach ($tables[$table] as $field => $info)
			{
				if (isset($filterValues[$field]) && (!empty($filterValues[$field]) || (is_numeric($filterValues[$field]) && $filterValues[$field] == 0)))
				{
					$temp[$field] = $filterValues[$field];
				}
			}
			$filterValues = $temp;
			$sql = array();
			foreach ($filterValues as $key => $value)
			{
				$sql[] = "`{$key}` = {$value}";
			}
			$sql = implode(',', $sql);
			$query = " UPDATE `{$table}` SET  ".$sql." WHERE `{$keyId}` = ".$this->quoteValue($keyValue).";";
			return $query;
		}
		private function lockTable($table)
		{
			$query = "LOCK TABLES `$table` WRITE;";
			$this->setQuery($query);
			$result = $this->query();
			return (boolean) $result;
		}
		private function unlockTable()
		{
			$query = "UNLOCK TABLES;";
			$this->setQuery($query);
			$result = $this->query();
			return (boolean) $result;
		}
		public function insertid()
		{
			$this->query();
			return $this->dbo->getLastInsertID();
		}
		public function addData($action, $table, $varKey, $varID, $varValues)
		{
			$this->lockTable ($table);
			if ($action == 'insert')
			{
				$query = $this->buildInsertQuery($table, $varValues);
				$this->setQuery($query);
				$id = $this->insertid();
				$this->unlockTable ();
				return (int) $id;
			}
			elseif ($action == 'update')
			{
				$query = $this->buildUpdateQuery($table, $varKey, $varID, $varValues);
				$this->setQuery($query);
				$this->query();
				$this->unlockTable ();
				return (int) $varID;
			}
		}
		public function getTotalNumber($idname, $table)
		{
			$query = "SELECT COUNT(`".$idname."`) as count FROM `".$table."` ";
			$this->setQuery($query);
			$result = $this->loadResult();
			return (isset($result['count'])) ? $result['count'] : false;
		}
		// Constraint: the $value in the $Conditions must be Integer;
		public function deleteRecord($conditions, $table)
		{
			$where = array();
			foreach ($conditions as $key => $value)
			{
				$where[] = "`".$key."` = ".(int) $value;
			}
			$where = $this->implodeWhere($where);
			$query = "DELETE FROM `".$table."` ".$where;
			$this->setQuery($query);
			$result = $this->query();
			return (boolean) $result;
		}
		public function getTableList()
		{
			$query = "SHOW TABLES ; ";
			$this->setQuery($query);
			$results = $this->loadResultArray();
			$list = array();
			foreach ($results as $result)
			{
				if (!preg_match("/(ose_app_geoip)|(osefirewall_country)/", $result->Tables_in_wordpress_d))
				{
					$tmp = array_values($result);
					$list[] = $tmp[0];
				}
			}
			return $list;
		}
	}
}
