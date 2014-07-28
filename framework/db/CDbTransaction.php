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
 * CDbTransaction class file
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CDbTransaction represents a DB transaction.
 *
 * It is usually created by calling {@link CDbConnection::beginTransaction}.
 *
 * The following code is a common scenario of using transactions:
 * <pre>
 * $transaction=$connection->beginTransaction();
 * try
 * {
 *    $connection->createCommand($sql1)->execute();
 *    $connection->createCommand($sql2)->execute();
 *    //.... other SQL executions
 *    $transaction->commit();
 * }
 * catch(Exception $e)
 * {
 *    $transaction->rollback();
 * }
 * </pre>
 *
 * @property CDbConnection $connection The DB connection for this transaction.
 * @property boolean $active Whether this transaction is active.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.db
 * @since 1.0
 */
class CDbTransaction extends CComponent
{
	private $_connection=null;
	private $_active;

	/**
	 * Constructor.
	 * @param CDbConnection $connection the connection associated with this transaction
	 * @see CDbConnection::beginTransaction
	 */
	public function __construct(CDbConnection $connection)
	{
		$this->_connection=$connection;
		$this->_active=true;
	}

	/**
	 * Commits a transaction.
	 * @throws CException if the transaction or the DB connection is not active.
	 */
	public function commit()
	{
		if($this->_active && $this->_connection->getActive())
		{
			Yii::trace('Committing transaction','system.db.CDbTransaction');
			$this->_connection->getPdoInstance()->commit();
			$this->_active=false;
		}
		else
			throw new CDbException(Yii::t('yii','CDbTransaction is inactive and cannot perform commit or roll back operations.'));
	}

	/**
	 * Rolls back a transaction.
	 * @throws CException if the transaction or the DB connection is not active.
	 */
	public function rollback()
	{
		if($this->_active && $this->_connection->getActive())
		{
			Yii::trace('Rolling back transaction','system.db.CDbTransaction');
			$this->_connection->getPdoInstance()->rollBack();
			$this->_active=false;
		}
		else
			throw new CDbException(Yii::t('yii','CDbTransaction is inactive and cannot perform commit or roll back operations.'));
	}

	/**
	 * @return CDbConnection the DB connection for this transaction
	 */
	public function getConnection()
	{
		return $this->_connection;
	}

	/**
	 * @return boolean whether this transaction is active
	 */
	public function getActive()
	{
		return $this->_active;
	}

	/**
	 * @param boolean $value whether this transaction is active
	 */
	protected function setActive($value)
	{
		$this->_active=$value;
	}
}
