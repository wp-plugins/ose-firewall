<?php
/**
 * @version     6.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Open Source Excellence CPU
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 30-Sep-2010
 * @author        Updated on 30-Mar-2013
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @copyright Copyright (C) 2008 - 2010- ... Open Source Excellence
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
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH'))
{
	die("Direct Access Not Allowed");
}
class oseDownloader
{
	private $type = null;
	private $key = null;
	private $url = null; 
	public function __construct($type, $key = null)
	{
		$this->type = $type;
		$this->key = $key;
		$live_url = "http://www.centrora.com/?";
		$this->url = $live_url."download=1&downloadKey=".$this->key;
		oseFirewall::loadFiles(); 
	}
	public function download()
	{
		$file = $this->downloadFile($this->url, $this->key);
		if (!empty($file)) 
		{
			oseFirewall::loadInstaller(); 
			$installer = new oseFirewallInstaller (); 
			$result = $installer -> insertAdvRuleset ($file, $this->type); 
			oseFile::delete($file); 
			return $result; 
		}
		else
		{
			return false; 
		}
	}
	public function update()
	{
		$file = $this->downloadFile($this->url, $this->key);
		if (!empty($file)) 
		{
			oseFirewall::loadInstaller(); 
			$installer = new oseFirewallInstaller (); 
			$result = $installer -> updateAdvRuleset ($file, $this->type); 
			oseFile::delete($file); 
			return $result; 
		}
		else
		{
			return false; 
		}
	}
	private function setPHPSetting () {
		if (function_exists('ini_set'))
		{
			ini_set("allow_url_fopen", 1); 
		}
		if (function_exists('ini_get'))
		{
			if (ini_get('allow_url_fopen') == 0)
			{
				oseAjax::aJaxReturn(false, 'ERROR', 'The PHP function \'allow_url_fopen\' is turned off, please turn it on to allow the task to continue.', FALSE);
			}
		}
	}
	private function downloadFile($url, $key)
	{
		$this->setPHPSetting (); 
		$inputHandle = fopen($url, "r");
		// Set the target path to store data
		$target = OSE_FWDATA.ODS.'tmp'.ODS.$key.".data";
		if (!$inputHandle)
		{
			return false;
		}
		$meta_data = stream_get_meta_data($inputHandle);
		// Initialise contents buffer
		$contents = null;
		while (!feof($inputHandle))
		{
			$contents .= fread($inputHandle, 8192);
			if ($contents === false)
			{
				return false;
			}
		}
		// Write buffer to file
		$handle = is_int(file_put_contents($target, $contents)) ? true : false;
		if ($handle)
		{
			// Close file pointer resource
			fclose($inputHandle);
		}
		return $target;
	}
	public function updateVersion ($type, $version) {
		$db = oseFirewall::getDBO ();
		$query = "SELECT * FROM `#__osefirewall_versions` WHERE `type` = ". $db->QuoteValue($type);
		$db->setQuery($query); 
		$result = $db->loadObject();
		if (empty($result))
		{
			$varValues = array (
				'version_id' => 'NULL',
				'number' => $version,
				'type' => $type
			);
			$id = $db->addData('insert', '#__osefirewall_versions', null, null, $varValues);
		}
		else 
		{
			$varValues = array (
				'number' => $version,
				'type' => $type
			);
			$id = $db->addData('update', '#__osefirewall_versions', 'version_id', $result->version_id, $varValues);
		}
		return $id;
	}
}
