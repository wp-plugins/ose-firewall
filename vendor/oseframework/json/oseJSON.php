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
if (!class_exists('oseJSON')) {
	class oseJSON
	{
		public static function encode($arr)
		{
			if (version_compare(PHP_VERSION, "5.2", "<"))
			{
				if (file_exists(dirname(__FILE__).ODS."ServicesJSON.php"))
				{
					require_once(dirname(__FILE__).ODS."ServicesJSON.php"); //if php<5.2 need JSON class
					}
				$json = new Services_JSON(); //instantiate new json object
				$data = $json->encode($arr); //encode the data in json format
				}
			else
			{
				$data = json_encode($arr); //encode the data in json format
				}
			return $data;
		}
		public static function decode($json, $assoc = false)
		{
			if (version_compare(PHP_VERSION, "5.2", "<"))
			{
				if (file_exists(dirname(__FILE__).ODS."ServicesJSON.php"))
				{
					require_once(dirname(__FILE__).ODS."ServicesJSON.php"); //if php<5.2 need JSON class
					}
				$Services_json = new Services_JSON(); //instantiate new json object
				$data = $Services_json->decode($json, $assoc); //encode the data in json format
				}
			else
			{
				$data = json_decode($json, $assoc); //encode the data in json format
				}
			return $data;
		}
	}
}
?>