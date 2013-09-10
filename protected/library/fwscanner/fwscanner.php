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
if (!defined('OSE_FRAMEWORK')) {
	die('Direct Access Not Allowed');
}
class oseFirewallScanner {
	private $ip = null;
	private $ip32 = null;
	private $ipStatus = null;
	private $url = null;
	private $referer = null;
	private $tags = null;
	private $target = null;
	private $converters = array ();
	private $allowExts = null;
	private $logtime = null;
	protected $db = null;
	private $json = null;
	protected $tolerance = 5;
	protected $threshold = 35;
	protected $blockIP = true;
	private $aclid = null;
	protected $scanGoogleBots = true;
	protected $scanYahooBots = true;
	protected $scanMsnBots = true;
	protected $devMode = false;
	protected $slient_max_att = 10;
	protected $banpagetype = false;
	protected $sfspam = false;
	protected $sfs_confidence = 30;
	protected $visits = 0;
	protected $blockMode = 1;
	protected $replaced  = array(); 
	public function __construct() {
		$this->initSetting();
	}
	private function initSetting() {
		$this->setDBO();
		$this->setTargetURL();
		$this->setReferer();
		$this->setClientIP();
		$this->setConfig();
	}
	private function setConfig() {
		$query = 'SELECT `key`, `value` FROM `#__ose_secConfig` WHERE `type` IN ("seo", "scan", "addons")';
		$this->db->setQuery($query);
		$results = $this->db->loadResultArray();
		foreach ($results as $result)
		{
			$key = $result['key']; 
			if (in_array($key, array('threshold', 'slient_max_att', 'sfs_confidence')))
			{
				$this->$key = (int) $result['value'] ;
			}
			else
			{
				$this->$key = $result['value'] ;
			} 
		}
	}
	private function setTargetURL() {
		$this->url = ((!empty($_SERVER['HTTPS'])) ? "https://" : "http://") . str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
	private function setReferer() {
		if (isset ($_SERVER['HTTP_REFERER'])) {
			$this->referer = $_SERVER['HTTP_REFERER'];
		} else {
			$this->referer = 'N/A';
		}
	}
	private function setClientIP() {
		oseFirewall::callLibClass('ipmanager','ipmanager');
		$ipmanager = new oseFirewallIpManager($this->db);
		$this->ip = $ipmanager->getIP();
		$this->ip32 = $ipmanager->getIPLong(true);
		$this->ipStatus = $ipmanager->getIPStatus();
		$this->aclid = $ipmanager->getACLID();
	}
	private function setDBO() {
		$this->db = oseFirewall::getDBO();
	}
	public function hackScan() {
		$continue = $this->checkContinue();
		if ($continue === false) {
			if (class_exists('oseDBO')) {
				//$this->db->__destruct();
			}
			return;
		}
		else if ($this->ipStatus == 1) {
			$this->showBanPage();
		}
		else
		{
			$this->scanAttack();
		}
		$this->db->closeDBO ();
	}
	protected function controlAttack() {
		
		$a = $this->getVisits();
		$b = $this->getblockIP();
        $score = $this->getScore();
		$notified = $this->getNotified();
 		if ($score < $this->threshold)
 		{
 			return; 
 		}
		// Ensure everything is cleaned before moving on; 
		switch ($b)
		{
			case 1:
				$this -> sendEmail('blacklisted', $notified); 
				$this -> showBanPage(); 
			break;
			case 0:
				if ($a <= $this->tolerance ){
					
					$this -> updateVisits();
					$this -> show403Page();
				}
				else{
					$this -> updateStatus(1); 
					$this -> sendEmail('blacklisted', $notified);
					$this -> showBanPage();
				}
			break;
			case 2:
				if ($a <= $this->slient_max_att)
				{
					$this -> updateVisits();
					$url = $this -> filterAttack(true);
					$this -> sendEmail('filtered', $notified);
					$this->redirect($url);
				}
				else
				{
					$this -> filterAttack(false);
					$this -> updateStatus(1); 
					$this -> sendEmail('blacklisted', $notified);
					$this -> showBanPage();
				}
			break;
		}
	}
	private function filterAttack($redirect)
	{
		$query= 'SELECT `attacktypeid`,`rule_id`, `keyname`, `content` FROM `#__osefirewall_attackmap` WHERE `aclid` = '. (int)$this->aclid.' GROUP BY `rule_id` ORDER BY LENGTH(`content`) DESC';
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		foreach ($results as $result)
		{
			if($result->attacktypeid==1)
			{
				$url = $this->convertL1Attack($result->content);
				if ($redirect==true)
				{
					return $url; 
				}
			}
			elseif (!empty($result->keyname) && !empty($result->rule_id))
			{
				$this->convertL2Attack($result->rule_id, $result->keyname);
			}
		} 
	}
	private function convertL1Attack($content)
	{
		$content = $this->cleanVariable($content);
		$this->replaced['original']['URL'] = ((!empty($_SERVER['HTTPS'])) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$_SERVER['REQUEST_URI'] = $this->cleanVariable($_SERVER['REQUEST_URI']);
		$_SERVER['QUERY_STRING'] = $this->cleanVariable($_SERVER['QUERY_STRING']); 
		$_SERVER['REQUEST_URI'] = str_replace($content, '', $_SERVER['REQUEST_URI']);
		$_SERVER['QUERY_STRING'] = str_replace($content, '', $_SERVER['QUERY_STRING']);
		$redirect = ((!empty($_SERVER['HTTPS'])) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$this->replaced['filtered']['URL'] = $redirect;
		return $redirect; 
	}
	private function convertL2Attack($rule_id, $keyname)
	{
		$tmp = explode('.', $keyname);
		if (!empty($tmp) && is_array($tmp))
		{ 
			switch($tmp[0])
			{
				case 'GET':
				   $this->replaced['original']['GET'][$tmp[1]] = $_GET[$tmp[1]];
				   $_GET[$tmp[1]] = $this->filterVariable($_GET[$tmp[1]], $rule_id);
				   $this->replaced['filtered']['GET'][$tmp[1]] = $_GET[$tmp[1]];
				break; 
				case 'POST':
				   $this->replaced['original']['POST'][$tmp[1]] = $_POST[$tmp[1]];	
				   $_POST[$tmp[1]] = $this->filterVariable($_POST[$tmp[1]], $rule_id);
				   $this->replaced['filtered']['POST'][$tmp[1]] = $_POST[$tmp[1]]; 
				break; 
			}
		}
	}
	private function filterVariable($var, $rule_id)
	{
		$pattern = '/'.$this -> getPattern($rule_id).'/ims'; 
		$var = preg_replace($pattern, '', $var);
		return $var; 
	}
	private function getPattern($rule_id)
	{
		$query = "SELECT `filter` FROM `#__osefirewall_filters` WHERE `id` = " . (int) $rule_id;
		$this->db->setQuery($query);
		$filter =  $this->db->loadResult();
		return $filter;   
	}
	private function cleanVariable($var)
	{
		return html_entity_decode(urldecode($var)); 
	}
	private function redirect($url)
	{
		header('Location: '.$url);
	}
	private function updateStatus($status)
	{
		$varValues = array (
				'status' => (int)$status
			);
		$result = $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
		return (boolean)$result;
	}
	
	public function getBlockIP() {
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'blockIP'";
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return (int)$result->value;
		
	}
	private function getScore()
	{
		$query = "SELECT `score` FROM `#__osefirewall_aclipmap` WHERE `id` = ". (int)$this->aclid; 
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return (int)$result->score; 
	}
	public function getVisits()
	{
		$query = "SELECT `visits` FROM `#__osefirewall_aclipmap` WHERE `id` =16";//. (int)$this->aclid; 
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return (int)$result->visits; 
		
	}
	private function getNotified()
	{
		$query = "SELECT `notified` FROM `#__osefirewall_aclipmap` WHERE `id` = ". (int)$this->aclid; 
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return (int)$result->notified;
	}
	private function updateVisits()
	{
		$query = "UPDATE `#__osefirewall_acl` SET `visits` = (`visits` +1) WHERE `id` = ". (int)$this->aclid; 
		$this->db->setQuery($query);
		$result = $this->db->query(); 
		return (boolean)$result; 
	}
	protected function addACLRule($status, $score) {
		$page_id = $this->addPages();
		$referer_id = $this->addReferer();
		if (empty ($this->aclid)) {
			$varValues = array (
				'id' => $this->aclid,
				'name' => $this->ip,
				'datetime' => date('Y-m-d h:i:s'),
				'score' => (int)$score,
				'status' => (int) $status,
				'referers_id' => $referer_id,
				'pages_id' => $page_id,
				'visits' => 1 
			);
			$ipmanager = new oseFirewallIpManager($this->db);
			$aclid = $ipmanager->getACLID();
			if (empty($aclid))
			{
				$this->aclid = $this->db->addData('insert', '#__osefirewall_acl', null, null, $varValues);
				if (!empty ($this->aclid)) {
					$ipmanager->addIP('ip', $this->aclid);
				}
			}
		}
		else
		{
			$varValues = array (
				'score' => (int)$score,
				'status' => (int) $status
			);
			$this->aclid = $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
		}
	}
	private function updateACLScore($score)
	{
		$varValues = array (
				'score' => $score
			);
		$this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
	}
	private function addL1DetContent($attacktypeID, $detcontent_content = null, $rule_id = null) {
		$exists = $this->isDetContentExists($attacktypeID, $rule_id);
		if (!empty ($exists)) {
			return;
		}
		$detattacktype_id = $this->insertDetAttacktype($attacktypeID);
		if (!empty ($detattacktype_id)) {
			$this->insertDetected($detattacktype_id);
			if (!empty ($detcontent_content) && !empty ($rule_id)) {
				$this->insertDetContentDetail($detattacktype_id, $detcontent_content, $rule_id, null);
			}
		}
		return $detattacktype_id;
	}
	private function addL2DetContent($attacktypeIDArray, $detcontent_content, $rule_id, $varKey) {
		$attacktypeIDArray = oseJSON :: decode($attacktypeIDArray);
		foreach ($attacktypeIDArray as $attacktypeID) {
			$exists = $this->isDetContentExists($attacktypeID, $rule_id);
			if ($exists==true) {
				continue;
			} else {
				$detattacktype_id = $this->insertDetAttacktype($attacktypeID);
				if (!empty ($detattacktype_id)) {
					$this->insertDetected($detattacktype_id);
					if (!empty ($detcontent_content) && !empty ($rule_id)) {
						$var_id = $this->insertVarKey($varKey);
						$this->insertDetContentDetail($detattacktype_id, $detcontent_content, $rule_id, $var_id);
					}
				}
			}
		}
	}
	private function insertVarKey($varKey) {
		$query = 'SELECT `id` FROM `#__osefirewall_vars` WHERE `keyname` = ' . $this->db->quoteValue($varKey);
		$this->db->setQuery($query);
		$id = $this->db->loadResult();
		if (empty ($id)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'keyname' => $varKey,
				'status' => 1
			);
			$id = $this->db->addData('insert', '#__osefirewall_vars', null, null, $varValues);
			return $id;
		} else {
			return $id;
		}
	}
	protected function insertDetAttacktype($attacktypeID) {
		$varValues = array (
			'id' => 'DEFAULT',
			'attacktypeid' => (int) $attacktypeID
		);
		$detattacktype_id = $this->db->addData('insert', '#__osefirewall_detattacktype', null, null, $varValues);
		return $detattacktype_id;
	}
	protected function insertDetected($detcontent_id) {
		$varValues = array (
			'acl_id' => (int) $this->aclid,
			'detattacktype_id' => (int) $detcontent_id
		);
		$this->db->addData('insert', '#__osefirewall_detected', null, null, $varValues);
	}
	protected function isDetContentExists($attacktypeID, $rule_id = null) {
		$where = array ();
		$where[] = '`aclid` = ' . (int) $this->aclid;
		$where[] = '`attacktypeid` = ' . (int) $attacktypeID;
		if (!empty ($rule_id)) {
			$where[] = '`rule_id` = ' . (int) $rule_id;
		}
		$where = $this->db->implodeWhere($where);
		$query = 'SELECT COUNT(`aclid`) as `count` FROM `#__osefirewall_attackmap` ' . $where;
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return $result->count;
	}
	protected function insertDetContentDetail($detattacktype_id, $detcontent, $rule_id, $var_id = null) {
		$detcontent_id = $this->insertDetContent($detcontent);
		$varValues = array (
			'detattacktype_id' => (int) $detattacktype_id,
			'detcontent_id' => $detcontent_id,
			'rule_id' => $rule_id
		);
		if (!empty ($var_id)) {
			$varValues['var_id'] = $var_id;
		}
		$this->db->addData('insert', '#__osefirewall_detcontdetail', null, null, $varValues);
		return;
	}
	private function insertDetContent($detcontent) {
		$id = $this->getDetContentID ($detcontent);
		if (empty ($id)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'content' => $detcontent
			);
			$id = $this->db->addData('insert', '#__osefirewall_detcontent', null, null, $varValues);
			return $id;
		} else {
			return $id;
		}
	}
	private function getDetContentID ($detcontent)
	{
		$query = 'SELECT `id` FROM `#__osefirewall_detcontent` WHERE `content` = ' . $this->db->quoteValue($detcontent);
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return $result ->id;
	}
	private function addPages() {
		$query = 'SELECT `id`, `visits` FROM `#__osefirewall_pages` WHERE `page_url` = ' . $this->db->quoteValue($this->url, true);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		if (empty ($results)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'page_url' => $this->url,
				'action' => 1,
				'visits' => 1
			);
			$id = $this->db->addData('insert', '#__osefirewall_pages', null, null, $varValues);
		} else {
			$varValues = array (
				'visits' => $results->visits + 1
			);
			$id = $this->db->addData('update', '#__osefirewall_pages', 'id', $results->id, $varValues);
		}
		return $id;
	}
	private function addReferer() {
		$query = 'SELECT `id` FROM `#__osefirewall_referers` WHERE `referer_url` = ' . $this->db->quoteValue($this->referer, true);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		if (empty ($results)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'referer_url' => $this->referer
			);
			$id = $this->db->addData('insert', '#__osefirewall_referers', null, null, $varValues);
		} else {
			$id = $results->id;
		}
		return $id;
	}
	private function getAllowBots() {
		$bots = array ();
		if ($this->scanGoogleBots === false) {
			$bots[] = 'Google';
		}
		if ($this->scanMsnBots === false) {
			$bots[] = 'msnbot';
		}
		if ($this->scanYahooBots === false) {
			$bots[] = 'Yahoo';
		}
		return $bots;
	}
	private function set($var, $value) {
		$this-> $var = $value;
	}
	private function get($var) {
		return $this-> $var;
	}
	private function checkContinue() {
		if ($this->devMode == true) {
			return false;
		}
		$bots = $this->getAllowBots();
		$ipmanager = new oseFirewallIpManager($this->db);
		if (COUNT($bots) > 0 && $ipmanager->isSearchEngineBot(implode('|', $bots), $_SERVER['HTTP_USER_AGENT'])) {
			$this->set('blockIP', 2);
			$this->set('slient_max_att', 20);
			return false;
		}
		if ($this->ipStatus == 3) {
			return false;
		}
		if (preg_match("/wp-login/", $_SERVER['SCRIPT_NAME'])) {
			return false; 
		}
		if((preg_match("/administrator\/*index.?\.php$/", $_SERVER['SCRIPT_NAME'])) || !empty($_SESSION['secretword']))
		{
			if(isset($this->secretword) && $this->secretword == $_SERVER['QUERY_STRING'])
			{
				$_SESSION['secretword'] = $this->secretword;
				return false; 
			}
		}
		if (isset ($_REQUEST['option'])) {
			if (COUNT($_REQUEST['option']) == 1 && (in_array($_REQUEST['option'], array (
					'com_ose_fileman',
					'com_ose_antihacker',
					'com_ose_antivirus',
					'com_civicrm'
				)))) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	private function showBanPage() {
		$adminEmail = (isset ($this->adminEmail)) ? $this->adminEmail: '';
		$customBanPage = (!empty ($this->customBanpage)) ? $this->customBanpage: 'Banned';
		$pageTitle = (!empty ($this->pageTitle)) ? $this->pageTitle : 'OSE Security Suite';
		$metaKeys = (!empty ($this->metaKeywords)) ? $this->metaKeywords : 'OSE Security Suite';
		$metaDescription = (!empty ($this->metaDescription)) ? $this->metaDescription : 'OSE Security Suite';
		$metaGenerator = (!empty ($this->metaGenerator)) ? $this->metaGenerator : 'OSE Security Suite';
		$banhtml = $this->getBanPage($adminEmail, $pageTitle, $metaKeys, $metaDescription, $metaGenerator, $customBanPage);
		echo $banhtml; exit;
	}
	private function getBanPage($adminEmail, $pageTitle, $metaKeys, $metaDescription, $metaGenerator, $customBanPage)
	{
		$banbody = $this->getBanPageBody($customBanPage, $adminEmail);
		$banhtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
						<head>
							  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
							  <meta name="robots" content="index, follow" />
							  <meta name="keywords" content="' . $metaKeys . '" />
							  <meta name="description" content="' . $metaDescription . '" />
							  <meta name="generator" content="' . $metaGenerator . '" />
							  <title>' . $pageTitle . '</title>
						</head>
						<body>
						'.$banbody.'	  
						</body>
					</html>';
		return $banhtml;
	}
	private function getBanPageBody($customBanPage, $adminEmail)
	{
		$banbody = '<div style="margin:auto;width:780px;border:0px solid #0082b0;padding:0px 10px 10px 10px;z-index:100;color:#000000;">
							  <br/>
								' . $customBanPage . '
							  <div style="font-family: arial,helvetica,sans-serif;background-color:#ffffff;padding: 10px 0px 0px 0px" align="center"><font color="#666666" size="1">Your IP address is ' . $this->ip . '. If you believe this is an error, please contact the <a href="mailto:' . $adminEmail . '?Subject=Inquiry:%20Banned%20for%20suspicious%20hacking%20behaviour - IP: ' . $this->ip . ' - Violation"> Webmaster </a>.</font></div>
					</div>';
		return $banbody;
	}
	private function show403Page() {
		$adminEmail = (isset ($this->adminEmail)) ? $this->adminEmail: '';
		$customBanPage = (!empty ($this->customBanpage)) ? $this->customBanpage: 'Banned';
		$banbody = $this->getBanPageBody($customBanPage, $adminEmail);
		header('HTTP/1.1 403 Forbidden');
		$banbody  = "<html>
						<head>
							<title>403 Forbidden</title>
						</head>
						<body>
								".$banbody."
						</body>
					 </html>";
		echo $banbody;			 
		exit;
	}
	private function sendEmail($type, $notified)
	{
		$config_var = $this->getConfigVars(); 
		oseFirewall::loadEmails();
		$oseEmail = new oseEmail('firewall');
		$email = $oseEmail->getEmailByType($type);
		$email = $this->convertEmail($email, $config_var);
		$result = $oseEmail->sendMail($email, $config_var);
		if ($result == true)
		{
			$this->updateNotified(1); 
		}
	}
	private function updateNotified($status)
	{
		$varValues = array (
				'notified' => (int)$status
			);
		$result = $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
		return (boolean)$result;
	}
	private function convertEmail($email, $config_var)
	{
		$attackType = $this->getAttackTypes();
		$attackType = implode(',', $attackType); 
		$ipURL = $this ->getIPURL($config_var); 
		$violation = $this->getViolation();
		$totalImpact = $this->getScore(); 
		$email->subject = $email->emailSubject." for [".$_SERVER['HTTP_HOST']."]";
		$email->body = str_replace('[attackType]', $attackType, $email->emailBody);
		$email->body = str_replace('[violation]', $violation, $email->body);
		$email->body = str_replace('[logtime]', $this->logtime, $email->body);
		$email->body = str_replace('[ip]', $this->ip, $email->body);
		$email->body = str_replace('[target]', $this->target, $email->body);
		$email->body = str_replace(array('[referrer]', '[referer]'), $this->referer, $email->body);
		$email->body = str_replace('[aclid]', $ipURL, $email->body);
		$email->body = str_replace('[score]', $totalImpact, $email->body);
		return $email; 
	}
	private function getViolation()
	{
		$return = '';
		if (!empty($this->replaced['original']))
		{
			foreach ($this->replaced['original'] as $key => $replaced)
			{
				if ($key =='URL')
				{
					$return .= $key. ' <br />redirected from <font color="red">'.$this->replaced['original'][$key]. '</font> <br />to '. $this->replaced['filtered'][$key].'<br/>'; 
				}
				elseif (in_array($key, array('GET', 'POST')))
				{
					foreach ($replaced as $k => $v)
					{
						$return .= $key.'.'.$k.' changed from <font color="red">'.$this->replaced['original'][$key][$k].'</font> to '. $this->replaced['filtered'][$key][$k].'<br/>';
					}
				}
 		}
		}
 		return $return; 
	}
	private function getIPURL($config_var)
	{
		return "<a href='".$config_var->live_site."/administrator/index.php?option=com_ose_antihacker&view=manageips&id=".$this->aclid."'>".$this->aclid."</a>";
	}
	private function getAttackTypes()
	{
		$query = 'SELECT DISTINCT `name` FROM `#__osefirewall_attackmap` WHERE `aclid` = '.(int)$this->aclid; 
		$this->db->setQuery($query); 
		$results = $this->db -> loadResultArray(); 
		return $results;  	
	}
	private function getConfigVars()
	{
		if (class_exists('SConfig'))
		{
			$config = new SConfig(); 
			return $config; 
		}
		elseif (class_exists('JConfig'))
		{
			$config = new JConfig(); 
			return $config; 
		}
	}
	protected function CheckIsSpambot() {
		// Initiate and declare spambot/errorDetected as false - as we're just getting started
		$isspamcheck = $this ->isspamcheck();
		$scanReturn = array ();
		$spambot = false;
		if ($spambot != true && $this->ip != "" && $isspamcheck == false) {
			$data = array ();
			$data["ip"] = $this->ip;
			$data["f"] = 'json';
			$json_return = $this->posttoSFS($data);
			$result = oseJSON :: decode($json_return);
			$this->updatespamcheck(true);
			if (!isset($result->ip->confidence)) {
				return false;
			}
			elseif ($result->ip->appears == 1 && $result->ip->confidence >= (int)$this->sfs_confidence) // Was the result was registered
			{
				$spambot = true; // Check failed. Result indicates dangerous.
				$scanReturn['detcontent_content'] = oseJSON::encode($result->ip); 
				$scanReturn['rule_id'] = 1;
				$scanReturn['impact'] = 100;
				return $scanReturn; 
			} else {
				return false; // Check passed. Result returned safe.
			}
			unset ($data);
			unset ($json_return);
			unset ($result);
		}
		return $spambot; // Return test results as either true/false or 1/0
	}
	protected function isspamcheck()
	{
		$this->ip32 = ip2long( $this -> ip);
		$query = "SELECT `ischecked` FROM `#__osefirewall_sfschecked` WHERE `ip32_start` = " . $this->db->quoteValue($this->ip32);
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return ($result->ischecked == 1) ? true : false;
	}
	protected function updatespamcheck($ischeck)
	{
		$varValues = array (
				'ip32_start' => $this->ip32,
				'ischecked' => 1
			);
		$this->db->addData('insert', '#__osefirewall_sfschecked', '', '', $varValues);
	}
	protected function posttoSFS($data) {
		$Url = "http://www.stopforumspam.com/api?" . http_build_query($data);
		$Curl = curl_init();
		curl_setopt($Curl, CURLOPT_URL, $Url);
		curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($Curl, CURLOPT_TIMEOUT, 4);
		curl_setopt($Curl, CURLOPT_FAILONERROR, 1);
		$ResultString = curl_exec($Curl);
		curl_close($Curl);
		unset ($Url);
		unset ($Curl);
		return $ResultString;
	}
}
