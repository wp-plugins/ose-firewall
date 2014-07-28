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
 * CInlineFilter class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CInlineFilter represents a filter defined as a controller method.
 *
 * CInlineFilter executes the 'filterXYZ($action)' method defined
 * in the controller, where the name 'XYZ' can be retrieved from the {@link name} property.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.filters
 * @since 1.0
 */
class CInlineFilter extends CFilter
{
	/**
	 * @var string name of the filter. It stands for 'XYZ' in the filter method name 'filterXYZ'.
	 */
	public $name;

	/**
	 * Creates an inline filter instance.
	 * The creation is based on a string describing the inline method name
	 * and action names that the filter shall or shall not apply to.
	 * @param CController $controller the controller who hosts the filter methods
	 * @param string $filterName the filter name
	 * @return CInlineFilter the created instance
	 * @throws CException if the filter method does not exist
	 */
	public static function create($controller,$filterName)
	{
		if(method_exists($controller,'filter'.$filterName))
		{
			$filter=new CInlineFilter;
			$filter->name=$filterName;
			return $filter;
		}
		else
			throw new CException(Yii::t('yii','Filter "{filter}" is invalid. Controller "{class}" does not have the filter method "filter{filter}".',
				array('{filter}'=>$filterName, '{class}'=>get_class($controller))));
	}

	/**
	 * Performs the filtering.
	 * This method calls the filter method defined in the controller class.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 */
	public function filter($filterChain)
	{
		$method='filter'.$this->name;
		$filterChain->controller->$method($filterChain);
	}
}
