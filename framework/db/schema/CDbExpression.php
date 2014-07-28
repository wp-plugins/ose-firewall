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
 * CDbExpression class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CDbExpression represents a DB expression that does not need escaping.
 * CDbExpression is mainly used in {@link CActiveRecord} as attribute values.
 * When inserting or updating a {@link CActiveRecord}, attribute values of
 * type CDbExpression will be directly put into the corresponding SQL statement
 * without escaping. A typical usage is that an attribute is set with 'NOW()'
 * expression so that saving the record would fill the corresponding column
 * with the current DB server timestamp.
 *
 * Starting from version 1.1.1, one can also specify parameters to be bound
 * for the expression. For example, if the expression is 'LOWER(:value)', then
 * one can set {@link params} to be <code>array(':value'=>$value)</code>.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.db.schema
 */
class CDbExpression extends CComponent
{
	/**
	 * @var string the DB expression
	 */
	public $expression;
	/**
	 * @var array list of parameters that should be bound for this expression.
	 * The keys are placeholders appearing in {@link expression}, while the values
	 * are the corresponding parameter values.
	 * @since 1.1.1
	 */
	public $params=array();

	/**
	 * Constructor.
	 * @param string $expression the DB expression
	 * @param array $params parameters
	 */
	public function __construct($expression,$params=array())
	{
		$this->expression=$expression;
		$this->params=$params;
	}

	/**
	 * String magic method
	 * @return string the DB expression
	 */
	public function __toString()
	{
		return $this->expression;
	}
}