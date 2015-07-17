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
    private $configTable = '#__ose_secConfig';
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
		// Close request to clear up some resources
		curl_close($curl);
		print_r($resp);exit;
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
		// Close request to clear up some resources
		curl_close($curl);
		print_r($resp);
		return $resp;
	}
	public function sendRequestReturnRes($content)
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
		// Close request to clear up some resources
		curl_close($curl);
		return $resp;
	}
	public function sendRequestJson($content)
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

    public function getSignature($type)
    {
        $dbo = oseFirewall::getDBO();
        if ($type == 'ath') {
            $flag = $dbo->getTotalNumber('id', '#__osefirewall_advancerules');
        } else {
            $flag = $dbo->getTotalNumber('id', '#__osefirewall_vspatterns');
        }
        $this->live_url = "https://www.centrora.com/accountApi/update/getSignature";
        $content = array();
        $content['webkey'] = $this->getWebKey();
        $content['remoteChecking'] = true;
        $content['task'] = 'getSignatures';
        $content['type'] = $type;
        if (empty($flag)) {
            $content['update'] = 0;
        } else {
            $content['update'] = 1;
        }
        $this->sendRequest($content);
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
	protected function getWebsiteContent ($task) {
		$content = array ();
		$content['url'] = oseFirewall::getSiteURL();
		$content['remoteChecking'] = true;
		$content['task'] = $task;
		$content['email'] = oseFirewall::getAdminEmail();
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
	public function getNumbOfWebsite () {
		$this->live_url = "https://www.centrora.com/accountApi/api/getNumOfWebsite";
		$content = $this ->getWebsiteContent ('getNumOfWebsite');
		$this->sendRequestNoExit($content);
	}
	public function checkSafebrowsing () {
		$this->live_url = 'https://www.centrora.com/accountApi/api/checkSafebrowsing';
		$content = $this ->getWebsiteContent ('checkSafebrowsing');
		$response = $this->sendRequestJson($content);
		$this->updateSafebrowsingStatus($response);
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
    public function returnValueDBQuery($query){
        $dbo = oseFirewall::getDBO();
        $dbo->setQuery($query);
        return $dbo->loadObject()->value;
    }

    public function getLatestVersion (){
        $query2 = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'LatestVersion'";
        $LatestVersion = $this->returnValueDBQuery($query2);
		$checkUpdateInterval = self::checkUpdateInterval();
        if (empty ( $LatestVersion )) {
            $LatestVersion = self::getUpdateCheck();
            $query4 = " INSERT INTO `#__ose_secConfig`(`key`,`value`,`type`) VALUES ('LatestVersion','" . $LatestVersion . "','UpdateCheck')";
            $this->runDbQuery($query4);
        } elseif ( $checkUpdateInterval ) {
            $LatestVersion = self::getUpdateCheck();
            $query6 = " UPDATE `#__ose_secConfig` SET `value`='" . $LatestVersion . "' WHERE `key` LIKE 'LatestVersion'";
            $this->runDbQuery($query6);
        }
        return $LatestVersion;
    }

	private function checkUpdateInterval($timeinterval = 30){
		$query1 = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'LastUpdateCheck'";
		$LastUpdateCheck = $this->returnValueDBQuery($query1);
		$elapsedtime = round(abs(time() - $LastUpdateCheck) / 60,2); //elapsedtime in minutes
		$result = false;
		if (empty ($LastUpdateCheck)) {
			$query3 = "INSERT INTO `#__ose_secConfig`(`key`,`value`,`type`) VALUES ('LastUpdateCheck','" . time() . "','UpdateCheck')";
			$this->runDbQuery($query3);
		} elseif ( $elapsedtime >= $timeinterval) {
			$result = true;
			$query5 = "UPDATE `#__ose_secConfig` SET `value`='" . time() . "' WHERE `key` LIKE 'LastUpdateCheck'";
			$this->runDbQuery($query5);
		}
		return $result;
	}

	private function getUpdateCheck () {
		$url = "http://www.centrora.com/accountApi/version/getLastestVersion";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$json = curl_exec($ch);
		curl_close($ch);
		$json_data = json_decode($json, true);		
		return  $json_data["version"][0];
	}
	public function runAutomaticUpdate () {
		$package = $this->runUpdatebyUrl();
	
		if (!$package) {
			JError::raiseWarning('', JText::_('Automatic Update: Something went wrong while unpacking the download'));
			return false;
		}
	
		// Get an installer instance
		$installer = JInstaller::getInstance();
	
		if (!$installer->install($package['dir'])) {
			JError::raiseWarning('', JText::_('Automatic Update: There was an error installing the package'));
			$result = false;
		} else {
			// Package installed sucessfully
			$msg = JText::sprintf('COM_INSTALLER_INSTALL_SUCCESS', JText::_('COM_INSTALLER_TYPE_TYPE_'.strtoupper($package['type'])));
			$result = true;
		}
	
		// Cleanup the install files
		if (!is_file($package['packagefile'])) {
			$config = JFactory::getConfig();
			$package['packagefile'] = $config->get('tmp_path') . '/' . $package['packagefile'];
		}
	
		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
	
	
		return $result;
	}
	protected function enableFURLOpen () {
		if (function_exists('ini_set'))
		{
			ini_set('allow_url_fopen', on); 
		}
	}
	public function runUpdatebyUrl () {
		$this->enableFURLOpen ();
		// Get a database connector
		$db = JFactory::getDbo();
		$url = "http://www.centrora.com/software/pkg_centrora.zip";
		// Define Temp Folder;
		$config		= JFactory::getConfig();
		$tmp_dest	= $config->get('tmp_path');
		// Download the zip package
		$url_fopen = ini_get('allow_url_fopen');
		if ($url_fopen == true)
		{
			$updatefile = JInstallerHelper::downloadPackage($url);
		}
		else
		{
			$updatefile = $this->downloadThroughCURL ($url, $tmp_dest, 'plg_centrora.zip');
		}
		// Was the package downloaded?
		if (!$updatefile) {
			JError::raiseWarning('', JText::_('Automatic Update: Something went wrong with the download'));
			return false;
		}
		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest . '/' . $updatefile);
		return $package;
	}
	private function downloadThroughCURL ($url, $tmp_dest, $file) {
		$target = $tmp_dest .'/'. $file;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec($curl);
		curl_close($curl);
		$handle = is_int(file_put_contents($target, $contents)) ? true : false;
		return $file;
	}
	public function logout () {
		$query1 = "DELETE FROM `#__ose_secConfig` WHERE `key` = 'webkey' AND `type` ='panel'";
		$query2 = "DELETE FROM `#__ose_secConfig` WHERE `key` = 'verified' AND `type` ='panel'";
		$result = $this->runDbQuery($query1);
		$result = $this->runDbQuery($query2);
		return $result;
	}
	protected function runDbQuery($query) {
		$dbo = oseFirewall::getDBO();
		$dbo->setQuery($query);
		return $dbo->query();
	}
	public function saveCronConfig($custhours, $custweekdays, $schedule_type, $cloudbackuptype, $enabled) {
		$this->live_url = "https://www.centrora.com/accountApi/cronjobs/saveCronSetting";
		$content = array ();
		$content['custhours'] = $custhours;
		$content['custweekdays'] = $custweekdays;
		$content['webKey'] = $this->getWebKey();
		$content['remoteChecking'] = true;
		$content['task'] = 'saveCronSetting';
        $content['schedule_type'] = $schedule_type;
        $content['cloudbackuptype'] = $cloudbackuptype;
        $content['enabled'] = $enabled;
		$this->sendRequest($content);
	}
	public function getCronSettings ($schedule_type) {
		$this->live_url = "https://www.centrora.com/accountApi/cronjobs/getCronSettings";
		$content = array ();
		$content['webKey'] = $this->getWebKey();
		$content['remoteChecking'] = true;
		$content['task'] = 'getCronSettings';
        $content['schedule_type'] = $schedule_type;
		return $this->sendRequestReturnRes($content);
	}
	public function activateCode($code) {
		$this->live_url = "https://www.centrora.com/accountApi/jvzoo/activateCode";
		$content = array ();
		$content['webKey'] = $this->getWebKey();
		$content['remoteChecking'] = true;
		$content['task'] = 'activateCode';
		$content['code'] = $code;
		$this->sendRequest($content);
	}
	// Add order into Centrora Store; 
	public function addOrder($subscriptionPlan, $payment_method, $country_id, $firstname, $lastname, $trackingCode)
	{
		$this->live_url = "https://www.centrora.com/accountApi/orders/addOrder";
		$content = array ();
		$content['webKey'] = $this->getWebKey();
		$content['remoteChecking'] = true;
		$content['task'] = 'addOrder';
		$content['product_id'] = $subscriptionPlan;
		$content['payment_method'] = $payment_method;
		$content['country_id'] = $country_id;
		$content['firstname'] = $firstname;
		$content['lastname'] = $lastname;
		$content['trackingCode'] = $trackingCode;
		$this->sendRequest($content);
	}
	// Get Payment Address; 
	public function getPaymentAddress () {
		$this->live_url = "https://www.centrora.com/accountApi/orders/getPaymentAddress";
		$content = array ();
		$content['webKey'] = $this->getWebKey();
		$content['remoteChecking'] = true;
		$content['task'] = 'getPaymentAddress';
		return $this->sendRequestReturnRes($content);
	}

    /**
     * This function returns a hidden list of directory folders based on the $path.
     * @param $rootpath
     * @param $path Directory path to return list of child directories.
     */
    public function  getFileTree($rootpath,$path){
        try {
            if(is_readable($path)) {
                // Create recursive dir iterator which skips dot folders and Flatten the recursive iterator
                $it = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST,
                    RecursiveIteratorIterator::CATCH_GET_CHILD
                );
                // keep to the base folder
                $it->setMaxDepth(0);
                $files_array = array();
                //array to sort by folder
                foreach ($it as $fileinfo) {
                    if ($fileinfo->isDir()) {
                        $key = $fileinfo->getRealPath();
                        $data = $fileinfo->getFilename();
                        $files_array[$key] = $data;
                    }
                }
                ksort($files_array);
                $list = '<ul id="filetreelist" class="filetree" style="display: none;">';
                if (($_REQUEST['dir']) == '') {
                    $list .= '<li class="folder collapsed" name="filetreeroot" id="/"><a href="#" id="' . $path . '" rel="/">ROOT</a></li>';
                } else {
                    foreach ($files_array as $key => $fileinfo) {
                        if (class_exists('SConfig')){
                            $newkey = $key . ODS.'public_html';
                            $newfileinfo = $fileinfo . ODS.'public_html';
                            $newrel = htmlentities(str_replace($rootpath, "", $newkey));
                             if ((urldecode($_REQUEST['dir']) == '/') || (urldecode($_REQUEST['dir']) == ' ')){
                                 $isrootfolder = true;
                             } else {
                                 $isrootfolder = false;
                             }
                            if (file_exists($newkey) && $isrootfolder){
                                $list .= '<li class="folder collapsed" id="' . $newrel . '"><a href="#" id="' . $newkey . '/"rel="' . $newrel . '/">' . htmlentities($newfileinfo) . '</a></li>';
                            } elseif (file_exists($key) && !$isrootfolder) {
                                $rel = htmlentities(str_replace($rootpath, "", $key));
                                $list .= '<li class="folder collapsed" id="' . $rel . '"><a href="#" id="' . $key . '/"rel="' . $rel . '/">' . htmlentities($fileinfo) . '</a></li>';
                            }
                        } elseif(!class_exists('SConfig')){
                            $rel = htmlentities(str_replace($rootpath, "", $key));
                            $list .= '<li class="folder collapsed" id="' . $rel . '"><a href="#" id="' . $key . '/"rel="' . $rel . '/">' . htmlentities($fileinfo) . '</a></li>';
                        }
                    }
                }
                $list .= '</ul>';
            } else{
                $list = '<ul id="filetreelist" class="filetree" style="display: none;">';
                if (class_exists('SConfig')){
                    $list .= '<li class="" name="" id=""><a href="http://www.centrora.com/centrora-security-suite-tutorial/installing-centrora-suite-dedicated-server/#special"
                            target="_blank" title="Please follow this guide to stop seeing this restriction (right click and open in new tab/window)">
                            {Restricted Permissions}</a></li>';
                } else{
                    $list .= '<li class="" name="" id="" title="This folder is not readable, please check file/folder permissions">{Restricted Permissions}</li>';
                }
                $list .= '</ul>';
            }
            echo($list);
        } catch (Exception $e) {
            $list = '<pre>'.$e.'</pre>';
            echo $list;
        }
    }
	public function getJSONFeed($rssUrl, $limit) {
		$params = array(
			'q' => $rssUrl,
			'v' => '1.0', // API version
			'num' => $limit, // maximum entries (limited)
			'output' => 'json_xml', // mixed content: JSON for feed, XML for full entries (json|xml|json_xml)
			'scoring' => 'h', // include historical entries
		);
		$result = file_get_contents('http://ajax.googleapis.com/ajax/services/feed/load?' . http_build_query($params));
		$json = json_decode($result);
		return $json->responseData;
	}

	public function checkNewsUpdated ($limit = 1){
		#Check for news after 60min
		$checkUpdateInterval = self::checkUpdateInterval(60);
		$result = false;
		if ($checkUpdateInterval ) {
			$data =  $this->getJSONFeed("http://www.centrora.com/category/blog/feed/atom", $limit);
			$LatestNewsHash = hash('md5', serialize($data->feed->entries));

			$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'LastNewsHash'";
			$LastNewsHash = $this->returnValueDBQuery($query);

			$result = self::hasNewsRead(false, 'notread');

			if (empty ( $LastNewsHash )) {
				$query = " INSERT INTO `#__ose_secConfig`(`key`,`value`,`type`) VALUES ('LastNewsHash','" . $LatestNewsHash . "','NewsCheck')";
				$this->runDbQuery($query);
				$result = true;
			} elseif ( $LastNewsHash != $LatestNewsHash) {
				$query = " UPDATE `#__ose_secConfig` SET `value`='" . $LatestNewsHash . "' WHERE `key` LIKE 'LastNewsHash'";
				$this->runDbQuery($query);
				self::hasNewsRead(true, 'notread');
				$result = true;
			}
		}
		return $result;
	}

	public function hasNewsRead ($addValue = true, $readValue = 'notread'){
		$query1 = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'LatestNewsRead'";
		$LatestNewsRead = $this->returnValueDBQuery($query1);
		$result = $LatestNewsRead == $readValue ? true : false;
		if ($addValue){
			if (empty ($LatestNewsRead)) {
				$query3 = "INSERT INTO `#__ose_secConfig`(`key`,`value`,`type`) VALUES ('LatestNewsRead','" . $readValue . "','NewsCheck')";
				$this->runDbQuery($query3);
			} else {
				$result = true;
				$query5 = "UPDATE `#__ose_secConfig` SET `value`='" . $readValue . "' WHERE `key` LIKE 'LatestNewsRead'";
				$this->runDbQuery($query5);
			}
		}
		return $result;
	}
}