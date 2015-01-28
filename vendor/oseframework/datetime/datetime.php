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

class oseDatetime
{
	protected $timezone = 'America/Los_Angeles';
	protected $format = "Y-m-d H:i:s";
	public function __construct()
	{
		$this->getTimeZone();
	}
	private function getTimeZone()
	{
		if (function_exists('ini_get'))
		{
			$timezone = ini_get('date.timezone');
			if (empty($timezone))
			{
				$this->setTimeZone ();
			}
			else
			{
				$timezone = date_default_timezone_get();
				$this->setTimeZone ($timezone);
			}
		}
	}
	private function setTimeZone($timezone = null)
	{
		if (function_exists('ini_set') && empty($timezone))
		{
			ini_set('date.timezone', $this->timezone);
		}
		else
		{
			$this->timezone = $timezone;
		}
	}
	public function getDateTime()
	{
		$tz_object = new DateTimeZone($this->timezone);
		$datetime = new DateTime("now", $tz_object);
		return $datetime->format($this->format);
	}
	public function setFormat($format)
	{
		$this->format = $format;
	}
	public function getTimeZonePub()
	{
		return $this->timezone;
	}
}
