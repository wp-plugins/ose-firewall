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
 * CEAcceleratorCache class file
 *
 * @author Steffen Dietz <steffo.dietz[at]googlemail[dot]com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CEAcceleratorCache implements a cache application module based on {@link http://eaccelerator.net/ eaccelerator}.
 *
 * To use this application component, the eAccelerator PHP extension must be loaded.
 *
 * See {@link CCache} manual for common cache operations that are supported by CEAccelerator.
 *
 * Please note that as of v0.9.6, eAccelerator no longer supports data caching.
 * This means if you still want to use this component, your eAccelerator should be of 0.9.5.x or lower version.
 *
 * @author Steffen Dietz <steffo.dietz[at]googlemail[dot]com>
 * @package system.caching
 */
class CEAcceleratorCache extends CCache
{
	/**
	 * Initializes this application component.
	 * This method is required by the {@link IApplicationComponent} interface.
	 * It checks the availability of eAccelerator.
	 * @throws CException if eAccelerator extension is not loaded, is disabled or the cache functions are not compiled in.
	 */
	public function init()
	{
		parent::init();
		if(!function_exists('eaccelerator_get'))
			throw new CException(Yii::t('yii','CEAcceleratorCache requires PHP eAccelerator extension to be loaded, enabled or compiled with the "--with-eaccelerator-shared-memory" option.'));
	}

	/**
	 * Retrieves a value from cache with a specified key.
	 * This is the implementation of the method declared in the parent class.
	 * @param string $key a unique key identifying the cached value
	 * @return string the value stored in cache, false if the value is not in the cache or expired.
	 */
	protected function getValue($key)
	{
		$result = eaccelerator_get($key);
		return $result !== NULL ? $result : false;
	}

	/**
	 * Stores a value identified by a key in cache.
	 * This is the implementation of the method declared in the parent class.
	 *
	 * @param string $key the key identifying the value to be cached
	 * @param string $value the value to be cached
	 * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
	 * @return boolean true if the value is successfully stored into cache, false otherwise
	 */
	protected function setValue($key,$value,$expire)
	{
		return eaccelerator_put($key,$value,$expire);
	}

	/**
	 * Stores a value identified by a key into cache if the cache does not contain this key.
	 * This is the implementation of the method declared in the parent class.
	 *
	 * @param string $key the key identifying the value to be cached
	 * @param string $value the value to be cached
	 * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
	 * @return boolean true if the value is successfully stored into cache, false otherwise
	 */
	protected function addValue($key,$value,$expire)
	{
		return (NULL === eaccelerator_get($key)) ? $this->setValue($key,$value,$expire) : false;
	}

	/**
	 * Deletes a value with the specified key from cache
	 * This is the implementation of the method declared in the parent class.
	 * @param string $key the key of the value to be deleted
	 * @return boolean if no error happens during deletion
	 */
	protected function deleteValue($key)
	{
		return eaccelerator_rm($key);
	}

	/**
	 * Deletes all values from cache.
	 * This is the implementation of the method declared in the parent class.
	 * @return boolean whether the flush operation was successful.
	 * @since 1.1.5
	 */
	protected function flushValues()
	{
		// first, remove expired content from cache
		eaccelerator_gc();
		// now, remove leftover cache-keys
		$keys = eaccelerator_list_keys();
		foreach($keys as $key)
			$this->deleteValue(substr($key['name'], 1));
		return true;
	}
}
