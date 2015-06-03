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

if (!class_exists('oseDB2', false))
{
	abstract class oseDB2
	{
		protected static $dbh;
		protected $dbo = null;
		protected $prefix = null;
		protected $stm = null;
		public $query = null;
		public function __construct()
		{
			$this->dbo = self::getConnection();
			$this->setPrefix ();
		}
		public function getDBO(){}
		protected static function getConnection() {}
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
			$this->setQuery("SHOW TABLES LIKE '".$table."';");
			$this->stm = $this->dbo->prepare($this->query);
			$this->stm->execute();
			$result = $this->stm->fetch(PDO::FETCH_ASSOC);
			return (!empty($result))?true:false;
		}
		public function setQuery($query)
		{
			$this->query = str_replace('#__', $this->prefix, $query);
			return $this->query;
		}
		public function query()
		{
			$this->stm = $this->dbo->prepare($this->query);
			return $this->stm->execute();
		}
		public function closeDBO()
		{
			if (!empty($this->stm))
			{	
				$this->stm->closeCursor(); 
				unset($this->stm);
			}
			//$this->dbo = null;
		}
		public function closeDBOFinal()
		{
			$this->dbo = null;
			self::$dbh = null;
			unset($this->dbo);
		}
		public function loadResult()
		{
			$this->stm = $this->dbo->prepare($this->query);
			$this->stm->execute();
			$result = $this->stm->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		public function quoteValue($value)
		{
			return $this->dbo->quote($value);
		}
		public function quoteKey($value)
		{
			return $this->quoteTable($value);
		}
		public function quoteTable($value)
		{
			return "`".$value."`";
		}
		public function loadResultArray()
		{
			$this->stm = $this->dbo->prepare($this->query);
			$this->stm->execute();
			$results = $this->stm->fetchAll(PDO::FETCH_NUM);
			return $results;
		}
		public function loadArrayList()
		{
			$this->stm = $this->dbo->prepare($this->query);
			$this->stm->execute();
			$results = $this->stm->fetchAll(PDO::FETCH_ASSOC);
			return $results;
		}
		public function loadObjectList()
		{
			$this->stm = $this->dbo->prepare($this->query);
			$this->stm->execute();
			$results = $this->stm->fetchAll(PDO::FETCH_CLASS);
			return $results;
		}
		public function loadResultList()
		{
			$this->stm = $this->dbo->prepare($this->query);
			$this->stm->execute();
			$results = $this->stm->fetchAll(PDO::FETCH_ASSOC);
			return $results;
		}
		public function loadObject($table = null)
		{
			$this->stm = $this->dbo->prepare($this->query);
			$this->stm->execute();
			$obj = $this->stm->fetchObject();
			return $obj;
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
			if (is_int($keyValue))
			{
				$query = " UPDATE `{$table}` SET  ".$sql." WHERE `{$keyId}` = ".(int)$keyValue.";";
			}
			else
			{
				$query = " UPDATE `{$table}` SET  ".$sql." WHERE `{$keyId}` = ".$this->quoteValue($keyValue).";";
			}
			return $query;
		}
		private function lockTable($table)
		{
			$query = "LOCK TABLES `$table` WRITE;";
			$this->setQuery($query);
			$this->dbo->exec($this->query);
		}
		private function unlockTable()
		{
			$query = "UNLOCK TABLES;";
			$this->setQuery($query);
			$this->dbo->exec($this->query);
		}
		public function flushReadLock ($table) 
		{
			$query = "FLUSH TABLES `$table` ;";
			$this->setQuery($query);
			$this->dbo->exec($this->query);
		}		
		public function insertid()
		{
			$this->query();
			return $this->dbo->getLastInsertID();
		}
		public function prepareTransactions ($table) {
			$this->dbo->beginTransaction();
			$this->flushReadLock ($table);
			$this->lockTable ($table);
		}
		public function addData($action, $table, $varKey, $varID, $varValues)
		{
			try {
					$this->prepareTransactions ($table);
					if ($action == 'insert')
					{
						$this->setQuery($this->buildInsertQuery($table, $varValues));
						$this->dbo->exec($this->query);
						$varID = $this->dbo->lastInsertId();
						$this->unlockTable ();
						$this->dbo->commit();
						return (int) $varID;
					}
					elseif ($action == 'update')
					{
						$this->setQuery($this->buildUpdateQuery($table, $varKey, $varID, $varValues));
						$count = $this->dbo->exec($this->query);
						$this->unlockTable ();
						$result = $this->dbo->commit();
						return $result;
					}
			} catch(PDOException $e) {
				$this->dbo->rollBack();
				return $e;
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
			$this->setQuery("DELETE FROM `".$table."` ".$where);
			$count = $this->dbo->exec($this->query);
			return ($count>0)?true:false;
		}

        public function deleteRecordString($conditions, $table)
        {
            $where = array();
            foreach ($conditions as $key => $value) {
                $where[] = "`" . $key . "` = " . $this->quoteValue($value);
            }
            $where = $this->implodeWhere($where);
            $this->setQuery("DELETE FROM `" . $table . "` " . $where);
            $count = $this->dbo->exec($this->query);
            return ($count > 0) ? true : false;
        }
		public function truncateTable ($table) 
		{
			$this->setQuery ("TRUNCATE ".$this->QuoteTable($table));
			$this->dbo->exec('SET FOREIGN_KEY_CHECKS = 0;');
			$this->stm = $this->dbo->prepare($this->query);
			return $this->stm->execute();
		}
		public function dropTable ($table)
		{
			$this->setQuery ("DROP TABLE ".$this->QuoteTable($table));
			$this->dbo->exec('SET FOREIGN_KEY_CHECKS = 0;');
			$this->stm = $this->dbo->prepare($this->query);
			return $this->stm->execute();
		} 
		public function getCurrentConnection () {
			$this->setQuery("SHOW STATUS WHERE `variable_name` = 'Threads_connected'");
			return $this->loadResult();
		}

        public function getlastinert()
        {
            $varID = $this->dbo->lastInsertId();
            return $varID;
        }
	}
}