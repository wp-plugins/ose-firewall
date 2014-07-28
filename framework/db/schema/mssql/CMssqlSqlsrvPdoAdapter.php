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
 * CMssqlSqlsrvPdoAdapter class file.
 *
 * @author Timur Ruziev <resurtm@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2012 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * This is an extension of default PDO class for MSSQL SQLSRV driver only.
 * It provides workaround of the improperly implemented functionalities of PDO SQLSRV driver.
 *
 * @author Timur Ruziev <resurtm@gmail.com>
 * @package system.db.schema.mssql
 * @since 1.1.13
 */
class CMssqlSqlsrvPdoAdapter extends PDO
{
	/**
	 * Returns last inserted ID value.
	 * SQLSRV driver supports PDO::lastInsertId() with one peculiarity: when $sequence's value is null or empty
	 * string it returns empty string. But when parameter is not specified at all it's working as expected
	 * and returns actual last inserted ID (like other PDO drivers).
	 *
	 * @param string|null the sequence name. Defaults to null.
	 * @return integer last inserted ID value.
	 */
	public function lastInsertId($sequence=null)
	{
		if(!$sequence)
			return parent::lastInsertId();
		return parent::lastInsertId($sequence);
	}
}
