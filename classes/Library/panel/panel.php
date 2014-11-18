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
class panel
{
	private $live_url = "";
	public function __construct() {
		
	}
	public function sendRequest($content)
	{
		$query = $this->mergeString ($content);
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->live_url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS =>$query,				
			CURLOPT_USERAGENT => 'Centrora Security Plugin Request Agent',
			CURLOPT_SSL_VERIFYPEER => false 
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		print_r($resp);exit;
		// Close request to clear up some resources
		curl_close($curl);
		return $resp;
	}
	public function sendRequestNoExit($content)
	{
		$query = $this->mergeString ($content);
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $this->live_url,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS =>$query,
		CURLOPT_USERAGENT => 'Centrora Security Plugin Request Agent',
		CURLOPT_SSL_VERIFYPEER => false
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		print_r($resp);
		// Close request to clear up some resources
		curl_close($curl);
		return $resp;
	}
	private function mergeString($content)
	{
		$url = "";
		foreach ($content as $key => $value)
		{
			$tmp[] = @$key.'='.urlencode(@$value);
		}
		$workstring = implode("&", $tmp);
		return $workstring;
	}
	public function validate($website, $email, $password, $token) {
		$this->live_url = "https://www.centrora.com/accountApi/api/validate";
		$content = $this->getRemoteConnectionContent ('validate', $website, $email, $password);
		$content = array_merge($content, $token);
		$this->sendRequest($content);
	}
	private function getRemoteConnectionContent ($task, $website, $email, $password) {
		oseFirewall::loadUsers ();
		$users = new oseUsers('firewall');
		$content = array ();
		$content['url'] = oseFirewall::getSiteURL();
		$content['remoteChecking'] = true;
		$content['task'] = $task;
		$content['website'] = $website;
		$content['email'] = $email;
		$content['password'] = $password;
		if (class_exists('SConfig'))
		{
			$content['cms'] = 'st';
		}
		else if (class_exists('JConfig'))
		{
			$content['cms'] = 'jl';
		}
		else if (defined('WPLANG'))
		{
			$content['cms'] = 'wp';
		}
		$content['ip'] = $this->getMyIP();
		return $content;
	}
	public function createAccount ($firstname, $lastname, $email, $password, $token) {
		$this->live_url = "https://www.centrora.com/accountApi/api/account";
		$content = array ();
		$content['firstname'] = $firstname;
		$content['lastname'] = $lastname;
		$content['email'] = $email;
		$content['password'] = $password;
		$content['ip'] = $this->getMyIP();
		$content['remoteChecking'] = true;
		$content['task'] = 'createAccount';
		$content = array_merge($content, $token);
		$this->sendRequest($content);
	}
	protected function getMyIP () {
		oseFirewall::callLibClass('ipmanager', 'ipmanager');
		$ipmanager = new oseFirewallIpManager (null);
		return $ipmanager->getIP();
	}
	public function verifyKey () {
		$this->live_url = "https://www.centrora.com/accountApi/api/verifyKey";
		$content = array ();
		$content['webkey'] = $this->getWebKey ();
		$content['ip'] = $this->getMyIP();
		$content['remoteChecking'] = true;
		$content['task'] = 'verifyKey';
		$this->sendRequest($content);
	}
	protected function getWebKey () {
		$dbo = oseFirewall::getDBO();
		$query = "SELECT * FROM `#__ose_secConfig` WHERE `key` = 'webkey'";
		$dbo->setQuery($query);
		$webkey = $dbo->loadObject()->value;
		return $webkey;
	}
	public function getSubscriptions() {
		$this->live_url = "https://www.centrora.com/accountApi/api/getSubscriptions";
		$content = array ();
		$content['webkey'] = $this->getWebKey();
		$content['remoteChecking'] = true;
		$content['task'] = 'getSubscriptions';
		$this->sendRequest($content);
	}
	public function linkSubscription($profileID) {
		$this->live_url = "https://www.centrora.com/accountApi/api/linkSubscription";
		$content = array ();
		$content['profileID'] = $profileID;
		$content['webKey'] = $this->getWebKey();
		$content['remoteChecking'] = true;
		$content['task'] = 'linkSubscription';
		$this->sendRequest($content);
	}
	public function getToken () {
		$this->live_url = "https://www.centrora.com/accountApi/api/getToken";
		$content = array ();
		$content['remoteChecking'] = true;
		$content['task'] = 'getToken';
		$this->sendRequestNoExit($content);
	}
	public function getDomainCount () {
		$dbo = oseFirewall::getDBO();
		$query = "SELECT COUNT(id) as count FROM `#__osefirewall_logs` WHERE `comp` = 'dom'";
		$dbo->setQuery($query);
		$webkey = $dbo->loadObject()->count;
		return $webkey;
	}
}