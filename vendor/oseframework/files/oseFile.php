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
/** Originally taken from:
 * @package     Joomla.Platform
 * @subpackage  FileSystem
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
class oseFile
{
	public function __construct()
	{
	}
	public static function getExt($file)
	{
		$dot = strrpos($file, '.') + 1;
		return substr($file, $dot);
	}
	public static function stripExt($file)
	{
		return preg_replace('#\.[^.]*$#', '', $file);
	}
	public static function makeSafe($file)
	{
		$regex = array(
			'#(\.){2,}#',
			'#[^A-Za-z0-9\.\_\- ]#',
			'#^\.#'
		);
		return preg_replace($regex, '', $file);
	}
	public static function clean($path, $ds = DIRECTORY_SEPARATOR)
	{
		$path = trim($path);
		if (empty($path))
		{
			return false;
			;
		}
		else
		{
			// Remove double slashes and backslashes and convert all slashes and backslashes to DS
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}
		return $path;
	}
	public static function delete($files)
	{
		if (!is_array($files))
		{
			$files = (array) $files;
		}
		foreach ($files as $file)
		{
			$file = self::clean($file);
			@chmod($file, 0777);
			// In case of restricted permissions we zap it one way or the other
			// as long as the owner is either the webserver or the ftp
			if (!@unlink($file))
			{
				return false;
			}
		}
		return true;
	}

    public static function deletefolder($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::deletefolder("$dir/$file") : self::delete("$dir/$file");
        }
        return rmdir($dir);
    }
	public static function read($filename, $incpath = false, $amount = 0, $chunksize = 8192, $offset = 0)
	{
		// Initialise variables.
		$data = null;
		$filename = preg_replace('#/+#', '/', $filename);
		if ($amount && $chunksize > $amount)
		{
			$chunksize = $amount;
		}
		if (false === $fh = fopen($filename, 'rb', $incpath))
		{
			return false;
		}
		clearstatcache();
		if ($offset)
		{
			fseek($fh, $offset);
		}
		if ($fsize = @filesize($filename))
		{
			if ($amount && $fsize > $amount)
			{
				$data = fread($fh, $amount);
			}
			else
			{
				$data = fread($fh, $fsize);
			}
		}
		else
		{
			$data = '';
			// While it's:
			// 1: Not the end of the file AND
			// 2a: No Max Amount set OR
			// 2b: The length of the data is less than the max amount we want
			while (!feof($fh) && (!$amount || strlen($data) < $amount))
			{
				$data .= fread($fh, $chunksize);
			}
		}
		fclose($fh);
		return $data;
	}
	public static function createFolder($path = '', $mode = 0755)
	{
		// First set umask
		$origmask = @umask(0);
		// Create the path
		if (!$ret = @mkdir($path, $mode))
		{
			@umask($origmask);
			return false;
		}
		// Reset umask
		@umask($origmask);
		return true;
	}
	public static function write($file, &$buffer, $use_streams = false, $append = false)
	{
		@set_time_limit(ini_get('max_execution_time'));
		// If the destination directory doesn't exist we need to create it
		if (!file_exists(dirname($file)))
		{
			self::createFolder(dirname($file));
		}
		$file = self::clean($file);
		if ($append==false)
		{
			$ret = is_int(file_put_contents($file, $buffer)) ? true : false;			
		}
		else
		{
			$ret = is_int(file_put_contents($file, $buffer, FILE_APPEND)) ? true : false;			
		}
		return $ret;
	}
	public static function exists($file)
	{
		return is_file(self::clean($file));
	}
	public static function getName($file)
	{
		// Convert back slashes to forward slashes
		$file = str_replace('\\', '/', $file);
		$slash = strrpos($file, '/');
		if ($slash !== false)
		{
			return substr($file, $slash + 1);
		}
		else
		{
			return $file;
		}
	}
}
