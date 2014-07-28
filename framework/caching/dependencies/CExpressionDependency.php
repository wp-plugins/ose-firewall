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
 * CExpressionDependency class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CExpressionDependency represents a dependency based on the result of a PHP expression.
 *
 * CExpressionDependency performs dependency checking based on the
 * result of a PHP {@link expression}.
 * The dependency is reported as unchanged if and only if the result is
 * the same as the one evaluated when storing the data to cache.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.caching.dependencies
 * @since 1.0
 */
class CExpressionDependency extends CCacheDependency
{
	/**
	 * @var string the PHP expression whose result is used to determine the dependency.
	 * The expression can also be a valid serializable PHP callback.
	 * The callback will be passed with a parameter which is the dependency object itself.
	 */
	public $expression;

	/**
	 * Constructor.
	 * @param string $expression the PHP expression whose result is used to determine the dependency.
	 */
	public function __construct($expression='true')
	{
		$this->expression=$expression;
	}

	/**
	 * Generates the data needed to determine if dependency has been changed.
	 * This method returns the result of the PHP expression.
	 * @return mixed the data needed to determine if dependency has been changed.
	 */
	protected function generateDependentData()
	{
		return $this->evaluateExpression($this->expression);
	}
}
