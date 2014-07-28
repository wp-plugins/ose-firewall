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
 * YiiBase class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CChoiceFormat is a helper that chooses an appropriate message based on the specified number value.
 * The candidate messages are given as a string in the following format:
 * <pre>
 * 'expr1#message1|expr2#message2|expr3#message3'
 * </pre>
 * where each expression should be a valid PHP expression with 'n' as the only variable.
 * For example, 'n==1' and 'n%10==2 && n>10' are both valid expressions.
 * The variable 'n' will take the given number value, and if an expression evaluates true,
 * the corresponding message will be returned.
 *
 * For example, given the candidate messages 'n==1#one|n==2#two|n>2#others' and
 * the number value 2, the resulting message will be 'two'.
 *
 * For expressions like 'n==1', we can also use a shortcut '1'. So the above example
 * candidate messages can be simplified as '1#one|2#two|n>2#others'.
 *
 * In case the given number doesn't select any message, the last candidate message
 * will be returned.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.i18n
 */
class CChoiceFormat
{
	/**
	 * Formats a message according to the specified number value.
	 * @param string $messages the candidate messages in the format of 'expr1#message1|expr2#message2|expr3#message3'.
	 * See {@link CChoiceFormat} for more details.
	 * @param mixed $number the number value
	 * @return string the selected message
	 */
	public static function format($messages, $number)
	{
		$n=preg_match_all('/\s*([^#]*)\s*#([^\|]*)\|/',$messages.'|',$matches);
		if($n===0)
			return $messages;
		for($i=0;$i<$n;++$i)
		{
			$expression=$matches[1][$i];
			$message=$matches[2][$i];
			if($expression===(string)(int)$expression)
			{
				if($expression==$number)
					return $message;
			}
			elseif(self::evaluate(str_replace('n','$n',$expression),$number))
				return $message;
		}
		return $message; // return the last choice
	}

	/**
	 * Evaluates a PHP expression with the given number value.
	 * @param string $expression the PHP expression
	 * @param mixed $n the number value
	 * @return boolean the expression result
	 */
	protected static function evaluate($expression,$n)
	{
		return @eval("return $expression;");
	}
}