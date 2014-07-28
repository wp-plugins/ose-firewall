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
class oseDownloader
{
	private $type = null;
	private $key = null;
	private $url = null; 
	private $live_url = null; 
	public function __construct($type, $key = null)
	{
		$this->type = $type;
		$this->key = $key;
		$this->live_url = "http://www.centrora.com/?";
		$this->url = $this->live_url."download=1&downloadKey=".$this->key;
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
				//oseAjax::aJaxReturn(false, 'ERROR', 'The PHP function \'allow_url_fopen\' is turned off, please turn it on to allow the task to continue.', FALSE);
			}
		}
	}
	private function downloadFile($url, $key)
	{
		$this->setPHPSetting (); 
		// Set the target path to store data
		$target = OSE_FWDATA.ODS.'tmp'.ODS.$key.".data";
		$url_fopen = ini_get('allow_url_fopen'); 
		if ($url_fopen == true)
		{
			$target = $this->downloadThroughFopen($url, $target);
		}
		else
		{
			$target = $this->downloadThroughCURL ($url, $target); 
		}
		return $target; 
	}
	private function downloadThroughFopen ($url, $target = null) {
		$inputHandle = fopen($url, "r");
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
	private function downloadThroughCURL ($url, $target = false) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec($curl);
		curl_close($curl);
		$handle = is_int(file_put_contents($target, $contents)) ? true : false;
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
		$db->closeDBO ();
		return $id;
	}
	
	private function mergeString($scanURL, $content)
	{
		$url = "";
		foreach ($content as $key => $value)
		{
			$tmp[] = @$key.'='.urlencode(@$value);
		}
		$workstring = implode("&", $tmp);
		$url .= $scanURL."&".$workstring;
		return $url;
	}
	public function sendRequest($content)
	{
		$url = $this->mergeString ($this->live_url, $content);
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'Centrora Security Plugin Request Agent'
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		return $resp;
	}
	public function getAPIkey () {
		$db = oseFirewall::getDBO ();
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'privateAPIKey'";	
		$db->setQuery($query);
		$result = $db->loadResult();
		$db->closeDBO ();
		return $result['value'];
	}
	
	public function getRemoteAPIKey () {
		$content = $this->getRemoteConnectionContent('checkSubstatus');
		$response = $this->sendRequest($content);
		return $response;   
	}
	private function getRemoteConnectionContent ($task) {
		oseFirewall::loadUsers ();
		$users = new oseUsers('wordpress');
		$content = array (); 
		$content['url'] = oseFirewall::getSiteURL();  
		$content['remoteChecking'] = true;
		$content['task'] = $task;
		$content['admin_email'] = $users->getUserEmail();
		return $content;
	}
	public function checkSafebrowsing () {
		$content = $this->getRemoteConnectionContent('checkSafebrowsing');
		$response = $this->sendRequest($content);
		return $response;
	}
	public function updateSafebrowsingStatus ($status) {
		oseFirewall::loadFiles(); 
		$filePath = OSE_FWDATA.ODS."tmp".ODS."safebrowsing.data";
		$fileContent = stripslashes($status);
		$result = oseFile::write($filePath, $fileContent);
		return $result;  
	}
	public function getSafeBrowsingStatus () {
		oseFirewall::loadFiles();
		oseFirewall::loadJSON();  
		$filePath = OSE_FWDATA.ODS."tmp".ODS."safebrowsing.data";
		if (file_exists($filePath))
		{
			$result = oseJSON::decode(oseFile::read($filePath));
			return $result;
		}  
		else
		{
			return null; 
		}
	}
	public function getEmailConfig () {
		$db = oseFirewall::getDBO ();
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'receiveEmail'";	
		$db->setQuery($query);
		$result = $db->loadResult();
		$db->closeDBO ();
		return $result['value'];
	}
}