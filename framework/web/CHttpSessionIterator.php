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
 * CHttpSessionIterator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CHttpSessionIterator implements an interator for {@link CHttpSession}.
 *
 * It allows CHttpSession to return a new iterator for traversing the session variables.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web
 * @since 1.0
 */
class CHttpSessionIterator implements Iterator
{
	/**
	 * @var array list of keys in the map
	 */
	private $_keys;
	/**
	 * @var mixed current key
	 */
	private $_key;

	/**
	 * Constructor.
	 * @param array the data to be iterated through
	 */
	public function __construct()
	{
		$this->_keys=array_keys($_SESSION);
	}

	/**
	 * Rewinds internal array pointer.
	 * This method is required by the interface Iterator.
	 */
	public function rewind()
	{
		$this->_key=reset($this->_keys);
	}

	/**
	 * Returns the key of the current array element.
	 * This method is required by the interface Iterator.
	 * @return mixed the key of the current array element
	 */
	public function key()
	{
		return $this->_key;
	}

	/**
	 * Returns the current array element.
	 * This method is required by the interface Iterator.
	 * @return mixed the current array element
	 */
	public function current()
	{
		return isset($_SESSION[$this->_key])?$_SESSION[$this->_key]:null;
	}

	/**
	 * Moves the internal pointer to the next array element.
	 * This method is required by the interface Iterator.
	 */
	public function next()
	{
		do
		{
			$this->_key=next($this->_keys);
		}
		while(!isset($_SESSION[$this->_key]) && $this->_key!==false);
	}

	/**
	 * Returns whether there is an element at current position.
	 * This method is required by the interface Iterator.
	 * @return boolean
	 */
	public function valid()
	{
		return $this->_key!==false;
	}
}
