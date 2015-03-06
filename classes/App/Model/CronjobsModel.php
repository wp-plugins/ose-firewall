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
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
	die('Direct Access Not Allowed');
}
require_once('BaseModel.php');
class CronjobsModel extends BaseModel
{
	public function __construct()
	{
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	protected function loadLibrary()
	{
		oseFirewall::callLibClass('panel','panel');
	}
	public function loadLocalScript()
	{
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraCronjobs', 'cronjobs.js', false);
	}
	public function getCHeader()
	{
		return oLang::_get('CRONJOBS_TITLE');
	}
	public function getCDescription()
	{
		return oLang::_get('CRONJOBS_DESC');
	}
	public function saveCronConfig($custhours, $custweekdays) {
		$panel = new panel ();
		return $panel->saveCronConfig($custhours, $custweekdays);
	}
	public function getCronSettings () {
		$panel = new panel ();
		$settings = json_decode(json_decode($panel->getCronSettings()));
		$return = array (); 
		foreach ($settings as $key => $val) {
			switch ($key) {
				case 'sun':
					$return[0] = ($val==true)?true:false;
				break;
				case 'mon':
					$return[1] = ($val==true)?true:false;
				break;
				case 'tue':
					$return[2] = ($val==true)?true:false;
				break;
				case 'wed':
					$return[3] = ($val==true)?true:false;
				break;
				case 'thu':
					$return[4] = ($val==true)?true:false;
				break;
				case 'fri':
					$return[5] = ($val==true)?true:false;
				break;
				case 'sat':
					$return[6] = ($val==true)?true:false;
				break;
			}
		}
		$return['hour'] = $settings->hour;
		return $return; 
	}
}
