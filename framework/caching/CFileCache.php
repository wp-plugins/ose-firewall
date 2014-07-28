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
 * CFileCache class file
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CFileCache provides a file-based caching mechanism.
 *
 * For each data value being cached, CFileCache will use store it in a separate file
 * under {@link cachePath} which defaults to 'protected/runtime/cache'.
 * CFileCache will perform garbage collection automatically to remove expired cache files.
 *
 * See {@link CCache} manual for common cache operations that are supported by CFileCache.
 *
 * @property integer $gCProbability The probability (parts per million) that garbage collection (GC) should be performed
 * when storing a piece of data in the cache. Defaults to 100, meaning 0.01% chance.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.caching
 */
class CFileCache extends CCache
{
	/**
	 * @var string the directory to store cache files. Defaults to null, meaning
	 * using 'protected/runtime/cache' as the directory.
	 */
	public $cachePath;
	/**
	 * @var string cache file suffix. Defaults to '.bin'.
	 */
	public $cacheFileSuffix='.bin';
	/**
	 * @var integer the level of sub-directories to store cache files. Defaults to 0,
	 * meaning no sub-directories. If the system has huge number of cache files (e.g. 10K+),
	 * you may want to set this value to be 1 or 2 so that the file system is not over burdened.
	 * The value of this property should not exceed 16 (less than 3 is recommended).
	 */
	public $directoryLevel=0;

	private $_gcProbability=100;
	private $_gced=false;

	/**
	 * Initializes this application component.
	 * This method is required by the {@link IApplicationComponent} interface.
	 */
	public function init()
	{
		parent::init();
		if($this->cachePath===null)
			$this->cachePath=Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'cache';
		if(!is_dir($this->cachePath))
			mkdir($this->cachePath,0777,true);
	}

	/**
	 * @return integer the probability (parts per million) that garbage collection (GC) should be performed
	 * when storing a piece of data in the cache. Defaults to 100, meaning 0.01% chance.
	 */
	public function getGCProbability()
	{
		return $this->_gcProbability;
	}

	/**
	 * @param integer $value the probability (parts per million) that garbage collection (GC) should be performed
	 * when storing a piece of data in the cache. Defaults to 100, meaning 0.01% chance.
	 * This number should be between 0 and 1000000. A value 0 meaning no GC will be performed at all.
	 */
	public function setGCProbability($value)
	{
		$value=(int)$value;
		if($value<0)
			$value=0;
		if($value>1000000)
			$value=1000000;
		$this->_gcProbability=$value;
	}

	/**
	 * Deletes all values from cache.
	 * This is the implementation of the method declared in the parent class.
	 * @return boolean whether the flush operation was successful.
	 * @since 1.1.5
	 */
	protected function flushValues()
	{
		$this->gc(false);
		return true;
	}

	/**
	 * Retrieves a value from cache with a specified key.
	 * This is the implementation of the method declared in the parent class.
	 * @param string $key a unique key identifying the cached value
	 * @return string the value stored in cache, false if the value is not in the cache or expired.
	 */
	protected function getValue($key)
	{
		$cacheFile=$this->getCacheFile($key);
		if(($time=@filemtime($cacheFile))>time())
			return @file_get_contents($cacheFile);
		elseif($time>0)
			@unlink($cacheFile);
		return false;
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
		if(!$this->_gced && mt_rand(0,1000000)<$this->_gcProbability)
		{
			$this->gc();
			$this->_gced=true;
		}

		if($expire<=0)
			$expire=31536000; // 1 year
		$expire+=time();

		$cacheFile=$this->getCacheFile($key);
		if($this->directoryLevel>0)
			@mkdir(dirname($cacheFile),0777,true);
		if(@file_put_contents($cacheFile,$value,LOCK_EX)!==false)
		{
			@chmod($cacheFile,0777);
			return @touch($cacheFile,$expire);
		}
		else
			return false;
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
		$cacheFile=$this->getCacheFile($key);
		if(@filemtime($cacheFile)>time())
			return false;
		return $this->setValue($key,$value,$expire);
	}

	/**
	 * Deletes a value with the specified key from cache
	 * This is the implementation of the method declared in the parent class.
	 * @param string $key the key of the value to be deleted
	 * @return boolean if no error happens during deletion
	 */
	protected function deleteValue($key)
	{
		$cacheFile=$this->getCacheFile($key);
		return @unlink($cacheFile);
	}

	/**
	 * Returns the cache file path given the cache key.
	 * @param string $key cache key
	 * @return string the cache file path
	 */
	protected function getCacheFile($key)
	{
		if($this->directoryLevel>0)
		{
			$base=$this->cachePath;
			for($i=0;$i<$this->directoryLevel;++$i)
			{
				if(($prefix=substr($key,$i+$i,2))!==false)
					$base.=DIRECTORY_SEPARATOR.$prefix;
			}
			return $base.DIRECTORY_SEPARATOR.$key.$this->cacheFileSuffix;
		}
		else
			return $this->cachePath.DIRECTORY_SEPARATOR.$key.$this->cacheFileSuffix;
	}

	/**
	 * Removes expired cache files.
	 * @param boolean $expiredOnly whether only expired cache files should be removed.
	 * If false, all cache files under {@link cachePath} will be removed.
	 * @param string $path the path to clean with. If null, it will be {@link cachePath}.
	 */
	public function gc($expiredOnly=true,$path=null)
	{
		if($path===null)
			$path=$this->cachePath;
		if(($handle=opendir($path))===false)
			return;
		while(($file=readdir($handle))!==false)
		{
			if($file[0]==='.')
				continue;
			$fullPath=$path.DIRECTORY_SEPARATOR.$file;
			if(is_dir($fullPath))
				$this->gc($expiredOnly,$fullPath);
			elseif($expiredOnly && @filemtime($fullPath)<time() || !$expiredOnly)
				@unlink($fullPath);
		}
		closedir($handle);
	}
}
