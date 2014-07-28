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
 * CChainedLogFilter class file
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2012 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CChainedLogFilter allows you to attach multiple log filters to a log route (See {@link CLogRoute::$filter} for details).
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package system.logging
 * @since 1.1.13
 */
class CChainedLogFilter extends CComponent implements ILogFilter
{
	/**
	 * @var array list of filters to apply to the logs.
	 * The value of each array element will be passed to {@link Yii::createComponent} to create
	 * a log filter object. As a result, this can be either a string representing the
	 * filter class name or an array representing the filter configuration.
	 * In general, the log filter classes should implement {@link ILogFilter} interface.
	 * Filters will be applied in the order they are defined.
	 */
	public $filters=array();

	/**
	 * Filters the given log messages by applying all filters configured by {@link filters}.
	 * @param array $logs the log messages
	 */
	public function filter(&$logs)
	{
		foreach($this->filters as $filter)
			Yii::createComponent($filter)->filter($logs);
	}
}