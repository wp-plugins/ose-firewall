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
 * CFilterWidget class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CFilterWidget is the base class for widgets that can also be used as filters.
 *
 * Derived classes may need to override the following methods:
 * <ul>
 * <li>{@link CWidget::init()} : called when this is object is used as a widget and needs initialization.</li>
 * <li>{@link CWidget::run()} : called when this is object is used as a widget.</li>
 * <li>{@link filter()} : the filtering method called when this object is used as an action filter.</li>
 * </ul>
 *
 * CFilterWidget provides all properties and methods of {@link CWidget} and {@link CFilter}.
 *
 * @property boolean $isFilter Whether this widget is used as a filter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.widgets
 * @since 1.0
 */
class CFilterWidget extends CWidget implements IFilter
{
	/**
	 * @var boolean whether to stop the action execution when this widget is used as a filter.
	 * This property should be changed only in {@link CWidget::init} method.
	 * Defaults to false, meaning the action should be executed.
	 */
	public $stopAction=false;

	private $_isFilter;

	/**
	 * Constructor.
	 * @param CBaseController $owner owner/creator of this widget. It could be either a widget or a controller.
	 */
	public function __construct($owner=null)
	{
		parent::__construct($owner);
		$this->_isFilter=($owner===null);
	}

	/**
	 * @return boolean whether this widget is used as a filter.
	 */
	public function getIsFilter()
	{
		return $this->_isFilter;
	}

	/**
	 * Performs the filtering.
	 * The default implementation simply calls {@link init()},
	 * {@link CFilterChain::run()} and {@link run()} in order
	 * Derived classes may want to override this method to change this behavior.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 */
	public function filter($filterChain)
	{
		$this->init();
		if(!$this->stopAction)
		{
			$filterChain->run();
			$this->run();
		}
	}
}