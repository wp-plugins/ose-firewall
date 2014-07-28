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

/**
 * CDbTableSchema class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CDbTableSchema is the base class for representing the metadata of a database table.
 *
 * It may be extended by different DBMS driver to provide DBMS-specific table metadata.
 *
 * CDbTableSchema provides the following information about a table:
 * <ul>
 * <li>{@link name}</li>
 * <li>{@link rawName}</li>
 * <li>{@link columns}</li>
 * <li>{@link primaryKey}</li>
 * <li>{@link foreignKeys}</li>
 * <li>{@link sequenceName}</li>
 * </ul>
 *
 * @property array $columnNames List of column names.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.db.schema
 * @since 1.0
 */
class CDbTableSchema extends CComponent
{
	/**
	 * @var string name of this table.
	 */
	public $name;
	/**
	 * @var string raw name of this table. This is the quoted version of table name with optional schema name. It can be directly used in SQLs.
	 */
	public $rawName;
	/**
	 * @var string|array primary key name of this table. If composite key, an array of key names is returned.
	 */
	public $primaryKey;
	/**
	 * @var string sequence name for the primary key. Null if no sequence.
	 */
	public $sequenceName;
	/**
	 * @var array foreign keys of this table. The array is indexed by column name. Each value is an array of foreign table name and foreign column name.
	 */
	public $foreignKeys=array();
	/**
	 * @var array column metadata of this table. Each array element is a CDbColumnSchema object, indexed by column names.
	 */
	public $columns=array();

	/**
	 * Gets the named column metadata.
	 * This is a convenient method for retrieving a named column even if it does not exist.
	 * @param string $name column name
	 * @return CDbColumnSchema metadata of the named column. Null if the named column does not exist.
	 */
	public function getColumn($name)
	{
		return isset($this->columns[$name]) ? $this->columns[$name] : null;
	}

	/**
	 * @return array list of column names
	 */
	public function getColumnNames()
	{
		return array_keys($this->columns);
	}
}
