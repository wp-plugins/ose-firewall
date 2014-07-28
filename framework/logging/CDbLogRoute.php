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
 * CDbLogRoute class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CDbLogRoute stores log messages in a database table.
 *
 * To specify the database table for storing log messages, set {@link logTableName} as
 * the name of the table and specify {@link connectionID} to be the ID of a {@link CDbConnection}
 * application component. If they are not set, a SQLite3 database named 'log-YiiVersion.db' will be created
 * and used under the application runtime directory.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.logging
 * @since 1.0
 */
class CDbLogRoute extends CLogRoute
{
	/**
	 * @var string the ID of CDbConnection application component. If not set, a SQLite database
	 * will be automatically created and used. The SQLite database file is
	 * <code>protected/runtime/log-YiiVersion.db</code>.
	 */
	public $connectionID;
	/**
	 * @var string the name of the DB table that stores log content. Defaults to 'YiiLog'.
	 * If {@link autoCreateLogTable} is false and you want to create the DB table manually by yourself,
	 * you need to make sure the DB table is of the following structure:
	 * <pre>
	 *  (
	 *		id       INTEGER NOT NULL PRIMARY KEY,
	 *		level    VARCHAR(128),
	 *		category VARCHAR(128),
	 *		logtime  INTEGER,
	 *		message  TEXT
	 *   )
	 * </pre>
	 * Note, the 'id' column must be created as an auto-incremental column.
	 * In MySQL, this means it should be <code>id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY</code>;
	 * In PostgreSQL, it is <code>id SERIAL PRIMARY KEY</code>.
	 * @see autoCreateLogTable
	 */
	public $logTableName='YiiLog';
	/**
	 * @var boolean whether the log DB table should be automatically created if not exists. Defaults to true.
	 * @see logTableName
	 */
	public $autoCreateLogTable=true;
	/**
	 * @var CDbConnection the DB connection instance
	 */
	private $_db;

	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		parent::init();

		if($this->autoCreateLogTable)
		{
			$db=$this->getDbConnection();
			try
			{
				$db->createCommand()->delete($this->logTableName,'0=1');
			}
			catch(Exception $e)
			{
				$this->createLogTable($db,$this->logTableName);
			}
		}
	}

	/**
	 * Creates the DB table for storing log messages.
	 * @param CDbConnection $db the database connection
	 * @param string $tableName the name of the table to be created
	 */
	protected function createLogTable($db,$tableName)
	{
		$db->createCommand()->createTable($tableName, array(
			'id'=>'pk',
			'level'=>'varchar(128)',
			'category'=>'varchar(128)',
			'logtime'=>'integer',
			'message'=>'text',
		));
	}

	/**
	 * @return CDbConnection the DB connection instance
	 * @throws CException if {@link connectionID} does not point to a valid application component.
	 */
	protected function getDbConnection()
	{
		if($this->_db!==null)
			return $this->_db;
		elseif(($id=$this->connectionID)!==null)
		{
			if(($this->_db=Yii::app()->getComponent($id)) instanceof CDbConnection)
				return $this->_db;
			else
				throw new CException(Yii::t('yii','CDbLogRoute.connectionID "{id}" does not point to a valid CDbConnection application component.',
					array('{id}'=>$id)));
		}
		else
		{
			$dbFile=Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'log-'.Yii::getVersion().'.db';
			return $this->_db=new CDbConnection('sqlite:'.$dbFile);
		}
	}

	/**
	 * Stores log messages into database.
	 * @param array $logs list of log messages
	 */
	protected function processLogs($logs)
	{
		$command=$this->getDbConnection()->createCommand();
		foreach($logs as $log)
		{
			$command->insert($this->logTableName,array(
				'level'=>$log[1],
				'category'=>$log[2],
				'logtime'=>(int)$log[3],
				'message'=>$log[0],
			));
		}
	}
}
