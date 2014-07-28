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
 * This file contains classes implementing configuration feature.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CConfiguration represents an array-based configuration.
 *
 * It can be used to initialize an object's properties.
 *
 * The configuration data may be obtained from a PHP script. For example,
 * <pre>
 * return array(
 *     'name'=>'My Application',
 *     'defaultController'=>'index',
 * );
 * </pre>
 * Use the following code to load the above configuration data:
 * <pre>
 * $config=new CConfiguration('path/to/config.php');
 * </pre>
 *
 * To apply the configuration to an object, call {@link applyTo()}.
 * Each (key,value) pair in the configuration data is applied
 * to the object like: $object->$key=$value.
 *
 * Since CConfiguration extends from {@link CMap}, it can be
 * used like an associative array. See {@link CMap} for more details.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.collections
 * @since 1.0
 */
class CConfiguration extends CMap
{
	/**
	 * Constructor.
	 * @param mixed $data if string, it represents a config file (a PHP script returning the configuration as an array);
	 * If array, it is config data.
	 */
	public function __construct($data=null)
	{
		if(is_string($data))
			parent::__construct(require($data));
		else
			parent::__construct($data);
	}

	/**
	 * Loads configuration data from a file and merges it with the existing configuration.
	 *
	 * A config file must be a PHP script returning a configuration array (like the following)
	 * <pre>
	 * return array
	 * (
	 *     'name'=>'My Application',
	 *     'defaultController'=>'index',
	 * );
	 * </pre>
	 *
	 * @param string $configFile configuration file path (if using relative path, be aware of what is the current path)
	 * @see mergeWith
	 */
	public function loadFromFile($configFile)
	{
		$data=require($configFile);
		if($this->getCount()>0)
			$this->mergeWith($data);
		else
			$this->copyFrom($data);
	}

	/**
	 * Saves the configuration into a string.
	 * The string is a valid PHP expression representing the configuration data as an array.
	 * @return string the string representation of the configuration
	 */
	public function saveAsString()
	{
		return str_replace("\r",'',var_export($this->toArray(),true));
	}

	/**
	 * Applies the configuration to an object.
	 * Each (key,value) pair in the configuration data is applied
	 * to the object like: $object->$key=$value.
	 * @param object $object object to be applied with this configuration
	 */
	public function applyTo($object)
	{
		foreach($this->toArray() as $key=>$value)
			$object->$key=$value;
	}
}
