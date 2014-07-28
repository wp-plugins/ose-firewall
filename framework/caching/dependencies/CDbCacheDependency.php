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
 * CDbCacheDependency class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CDbCacheDependency represents a dependency based on the query result of a SQL statement.
 *
 * If the query result (a scalar) changes, the dependency is considered as changed.
 * To specify the SQL statement, set {@link sql} property.
 * The {@link connectionID} property specifies the ID of a {@link CDbConnection} application
 * component. It is this DB connection that is used to perform the query.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.caching.dependencies
 * @since 1.0
 */
class CDbCacheDependency extends CCacheDependency
{
	/**
	 * @var string the ID of a {@link CDbConnection} application component. Defaults to 'db'.
	 */
	public $connectionID='db';
	/**
	 * @var string the SQL statement whose result is used to determine if the dependency has been changed.
	 * Note, the SQL statement should return back a single value.
	 */
	public $sql;
	/**
	 * @var array parameters (name=>value) to be bound to the SQL statement specified by {@link sql}.
	 * @since 1.1.4
	 */
	public $params;

	private $_db;

	/**
	 * Constructor.
	 * @param string $sql the SQL statement whose result is used to determine if the dependency has been changed.
	 */
	public function __construct($sql=null)
	{
		$this->sql=$sql;
	}

	/**
	 * PHP sleep magic method.
	 * This method ensures that the database instance is set null because it contains resource handles.
	 * @return array
	 */
	public function __sleep()
	{
		$this->_db=null;
		return array_keys((array)$this);
	}

	/**
	 * Generates the data needed to determine if dependency has been changed.
	 * This method returns the value of the global state.
	 * @return mixed the data needed to determine if dependency has been changed.
	 */
	protected function generateDependentData()
	{
		if($this->sql!==null)
		{
			$db=$this->getDbConnection();
			$command=$db->createCommand($this->sql);
			if(is_array($this->params))
			{
				foreach($this->params as $name=>$value)
					$command->bindValue($name,$value);
			}
			if($db->queryCachingDuration>0)
			{
				// temporarily disable and re-enable query caching
				$duration=$db->queryCachingDuration;
				$db->queryCachingDuration=0;
				$result=$command->queryRow();
				$db->queryCachingDuration=$duration;
			}
			else
				$result=$command->queryRow();
			return $result;
		}
		else
			throw new CException(Yii::t('yii','CDbCacheDependency.sql cannot be empty.'));
	}

	/**
	 * @return CDbConnection the DB connection instance
	 * @throws CException if {@link connectionID} does not point to a valid application component.
	 */
	protected function getDbConnection()
	{
		if($this->_db!==null)
			return $this->_db;
		else
		{
			if(($this->_db=Yii::app()->getComponent($this->connectionID)) instanceof CDbConnection)
				return $this->_db;
			else
				throw new CException(Yii::t('yii','CDbCacheDependency.connectionID "{id}" is invalid. Please make sure it refers to the ID of a CDbConnection application component.',
					array('{id}'=>$this->connectionID)));
		}
	}
}
