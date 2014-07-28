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
 * CGlobalStateCacheDependency class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CGlobalStateCacheDependency represents a dependency based on a global state value.
 *
 * CGlobalStateCacheDependency checks if a global state is changed or not.
 * If the global state is changed, the dependency is reported as changed.
 * To specify which global state this dependency should check with,
 * set {@link stateName} to the name of the global state.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.caching.dependencies
 * @since 1.0
 */
class CGlobalStateCacheDependency extends CCacheDependency
{
	/**
	 * @var string the name of the global state whose value is to check
	 * if the dependency has changed.
	 * @see CApplication::setGlobalState
	 */
	public $stateName;

	/**
	 * Constructor.
	 * @param string $name the name of the global state
	 */
	public function __construct($name=null)
	{
		$this->stateName=$name;
	}

	/**
	 * Generates the data needed to determine if dependency has been changed.
	 * This method returns the value of the global state.
	 * @return mixed the data needed to determine if dependency has been changed.
	 */
	protected function generateDependentData()
	{
		if($this->stateName!==null)
			return Yii::app()->getGlobalState($this->stateName);
		else
			throw new CException(Yii::t('yii','CGlobalStateCacheDependency.stateName cannot be empty.'));
	}
}
