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
 * CLogRouter class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CLogRouter manages log routes that record log messages in different media.
 *
 * For example, a file log route {@link CFileLogRoute} records log messages
 * in log files. An email log route {@link CEmailLogRoute} sends log messages
 * to specific email addresses. See {@link CLogRoute} for more details about
 * different log routes.
 *
 * Log routes may be configured in application configuration like following:
 * <pre>
 * array(
 *     'preload'=>array('log'), // preload log component when app starts
 *     'components'=>array(
 *         'log'=>array(
 *             'class'=>'CLogRouter',
 *             'routes'=>array(
 *                 array(
 *                     'class'=>'CFileLogRoute',
 *                     'levels'=>'trace, info',
 *                     'categories'=>'system.*',
 *                 ),
 *                 array(
 *                     'class'=>'CEmailLogRoute',
 *                     'levels'=>'error, warning',
 *                     'emails'=>array('admin@example.com'),
 *                 ),
 *             ),
 *         ),
 *     ),
 * )
 * </pre>
 *
 * You can specify multiple routes with different filtering conditions and different
 * targets, even if the routes are of the same type.
 *
 * @property array $routes The currently initialized routes.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.logging
 * @since 1.0
 */
class CLogRouter extends CApplicationComponent
{
	private $_routes=array();

	/**
	 * Initializes this application component.
	 * This method is required by the IApplicationComponent interface.
	 */
	public function init()
	{
		parent::init();
		foreach($this->_routes as $name=>$route)
		{
			$route=Yii::createComponent($route);
			$route->init();
			$this->_routes[$name]=$route;
		}
		Yii::getLogger()->attachEventHandler('onFlush',array($this,'collectLogs'));
		Yii::app()->attachEventHandler('onEndRequest',array($this,'processLogs'));
	}

	/**
	 * @return array the currently initialized routes
	 */
	public function getRoutes()
	{
		return new CMap($this->_routes);
	}

	/**
	 * @param array $config list of route configurations. Each array element represents
	 * the configuration for a single route and has the following array structure:
	 * <ul>
	 * <li>class: specifies the class name or alias for the route class.</li>
	 * <li>name-value pairs: configure the initial property values of the route.</li>
	 * </ul>
	 */
	public function setRoutes($config)
	{
		foreach($config as $name=>$route)
			$this->_routes[$name]=$route;
	}

	/**
	 * Collects log messages from a logger.
	 * This method is an event handler to the {@link CLogger::onFlush} event.
	 * @param CEvent $event event parameter
	 */
	public function collectLogs($event)
	{
		$logger=Yii::getLogger();
		$dumpLogs=isset($event->params['dumpLogs']) && $event->params['dumpLogs'];
		foreach($this->_routes as $route)
		{
			if($route->enabled)
				$route->collectLogs($logger,$dumpLogs);
		}
	}

	/**
	 * Collects and processes log messages from a logger.
	 * This method is an event handler to the {@link CApplication::onEndRequest} event.
	 * @param CEvent $event event parameter
	 * @since 1.1.0
	 */
	public function processLogs($event)
	{
		$logger=Yii::getLogger();
		foreach($this->_routes as $route)
		{
			if($route->enabled)
				$route->collectLogs($logger,true);
		}
	}
}
