<?php
/**
* @version     2.0 +
* @package       Open Source Excellence Security Suite
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
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
class DownloadModel extends BaseModel {
	public function __construct() {	
	}
	public function download($type, $downloadKey) {
		oseFirewall::callLibClass('downloader', 'oseDownloader');
		$downloader = new oseDownloader($type, $downloadKey);
		return $downloader ->download ();
	}
	public function updateVersion ($type, $version) {
		oseFirewall::callLibClass('downloader', 'oseDownloader');
		$downloader = new oseDownloader($type, null);
		return $downloader ->updateVersion ($type, $version);
	}
	public function update($type, $downloadKey) {
		oseFirewall::callLibClass('downloader', 'oseDownloader');
		$downloader = new oseDownloader($type, $downloadKey);
		return $downloader ->update ();
	}
}