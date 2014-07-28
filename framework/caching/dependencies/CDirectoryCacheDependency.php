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
 * CDirectoryCacheDependency class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CDirectoryCacheDependency represents a dependency based on change of a directory.
 *
 * CDirectoryCacheDependency performs dependency checking based on the
 * modification time of the files contained in the specified directory.
 * The directory being checked is specified via {@link directory}.
 *
 * By default, all files under the specified directory and subdirectories
 * will be checked. If the last modification time of any of them is changed
 * or if different number of files are contained in a directory, the dependency
 * is reported as changed. By specifying {@link recursiveLevel},
 * one can limit the checking to a certain depth of the directory.
 *
 * Note, dependency checking for a directory is expensive because it involves
 * accessing modification time of multiple files under the directory.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.caching.dependencies
 * @since 1.0
 */
class CDirectoryCacheDependency extends CCacheDependency
{
	/**
	 * @var string the directory whose change is used to determine if the dependency has been changed.
	 * If any of the files under the directory is changed, the dependency is considered as changed.
	 */
	public $directory;
	/**
	 * @var integer the depth of the subdirectories to be recursively checked.
	 * If the value is less than 0, it means unlimited depth.
	 * If the value is 0, it means checking the files directly under the specified directory.
	 */
	public $recursiveLevel=-1;
	/**
	 * @var string the regular expression matching valid file/directory names.
	 * Only the matching files or directories will be checked for changes.
	 * Defaults to null, meaning all files/directories will qualify.
	 */
	public $namePattern;

	/**
	 * Constructor.
	 * @param string $directory the directory to be checked
	 */
	public function __construct($directory=null)
	{
		$this->directory=$directory;
	}

	/**
	 * Generates the data needed to determine if dependency has been changed.
	 * This method returns the modification timestamps for files under the directory.
	 * @return mixed the data needed to determine if dependency has been changed.
	 */
	protected function generateDependentData()
	{
		if($this->directory!==null)
			return $this->generateTimestamps($this->directory);
		else
			throw new CException(Yii::t('yii','CDirectoryCacheDependency.directory cannot be empty.'));
	}

	/**
	 * Determines the last modification time for files under the directory.
	 * This method may go recursively into subdirectories if {@link recursiveLevel} is not 0.
	 * @param string $directory the directory name
	 * @param integer $level level of the recursion
	 * @return array list of file modification time indexed by the file path
	 */
	protected function generateTimestamps($directory,$level=0)
	{
		if(($dir=@opendir($directory))===false)
			throw new CException(Yii::t('yii','"{path}" is not a valid directory.',
				array('{path}'=>$directory)));
		$timestamps=array();
		while(($file=readdir($dir))!==false)
		{
			$path=$directory.DIRECTORY_SEPARATOR.$file;
			if($file==='.' || $file==='..')
				continue;
			if($this->namePattern!==null && !preg_match($this->namePattern,$file))
				continue;
			if(is_file($path))
			{
				if($this->validateFile($path))
					$timestamps[$path]=filemtime($path);
			}
			else
			{
				if(($this->recursiveLevel<0 || $level<$this->recursiveLevel) && $this->validateDirectory($path))
					$timestamps=array_merge($timestamps, $this->generateTimestamps($path,$level+1));
			}
		}
		closedir($dir);
		return $timestamps;
	}

	/**
	 * Checks to see if the file should be checked for dependency.
	 * This method is invoked when dependency of the whole directory is being checked.
	 * By default, it always returns true, meaning the file should be checked.
	 * You may override this method to check only certain files.
	 * @param string $fileName the name of the file that may be checked for dependency.
	 * @return boolean whether this file should be checked.
	 */
	protected function validateFile($fileName)
	{
		return true;
	}

	/**
	 * Checks to see if the specified subdirectory should be checked for dependency.
	 * This method is invoked when dependency of the whole directory is being checked.
	 * By default, it always returns true, meaning the subdirectory should be checked.
	 * You may override this method to check only certain subdirectories.
	 * @param string $directory the name of the subdirectory that may be checked for dependency.
	 * @return boolean whether this subdirectory should be checked.
	 */
	protected function validateDirectory($directory)
	{
		return true;
	}
}
