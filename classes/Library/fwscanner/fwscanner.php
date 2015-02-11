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
	protected $silentMode = 1;
	protected $detected = '';
	protected $blockCountry = '';
	protected $spamEmail = false;
	public function __construct() {
		$this->initSetting();
	}
	protected function initSetting() {
		oseFirewall::callLibClass('convertviews','convertviews');
		$this->setDBO();
		$this->setTargetURL();
		$this->setReferer();
		$this->setClientIP();
		$this->setConfig();
	}
	protected function setConfig() {
		$query = 'SELECT `key`, `value` FROM `#__ose_secConfig` WHERE `type` IN ("seo", "scan", "addons", "advscan", "country")';
		$this->db->setQuery($query);
		$results = $this->db->loadArrayList();
		foreach ($results as $result)
		{
			$key = $result['key'];
			if (in_array($key, array('threshold', 'slient_max_att', 'sfs_confidence')))
			{
				$this->$key = (int) $result['value'];
				if ($this->threshold == 0 )
				{
					$this->threshold = 35; 
				}
			}
			else
			{
				$this->$key = $result['value'];
			}
		}
	}
	
	protected function setTargetURL() {
		$this->url = ((!empty($_SERVER['HTTPS'])) ? "https://" : "http://") . str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
	protected function setReferer() {
		if (isset ($_SERVER['HTTP_REFERER'])) {
			$this->referer = $_SERVER['HTTP_REFERER'];
		} else {
			$this->referer = 'N/A';
		}
	}
	protected function setClientIP() {
		oseFirewall::callLibClass('ipmanager','ipmanager');
		$ipmanager = new oseFirewallIpManager($this->db);
		$this->ip = $ipmanager->getIP();
		$this->ip32 = $ipmanager->getIPLong(true);
		$this->ipStatus = $ipmanager->getIPStatus();
		$this->aclid = $ipmanager->getACLID();
	}
	protected function setDBO() {
		$this->db = oseFirewall::getDBO();
	}
	public function hackScan() {
		$continue = $this->checkContinue();
		if ($continue === false) {
			if (class_exists('oseDBO')) {
				$this->db->__destruct();
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
	protected function filterAttack($type)
	{
		$attrList = array("`detattacktype`.`attacktypeid` AS `attacktypeid`", "`detcontdetail`.`rule_id` AS `rule_id`", "`vars`.`keyname` AS `keyname`","`detcontent`.`content` AS `content`");
		$sql = convertViews::convertAttackmap($attrList);
		$query= $sql . 'WHERE `acl`.`id` = '. (int)$this->aclid.' GROUP BY `rule_id` ORDER BY LENGTH(`content`) DESC';
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		foreach ($results as $result)
		{
			if (!empty($result->keyname) && !empty($result->rule_id))
			{	
				if ($type == 'ad')
				{
					$this->convertL2Attack($result->rule_id, $result->keyname);
				}
				else
				{
					$this->convertL1Attack($result->keyname, $result->content);
				}
			}
		}
	}
	protected function convertL1Attack($keyname, $content)
	{
		$tmp = array (); 
		if (isset($_GET[$keyname])) 
		{
			$tmp[0] = 'GET';
			$tmp[1] = $keyname; 
		}
		else if (isset($_POST[$keyname]))
		{
			$tmp[0]= 'POST';
			$tmp[1] = $keyname; 
		}
		$this->replaced['original']['GET'][$tmp[1]] = $_GET[$tmp[1]];
		$_GET[$tmp[1]] = NULL;
		$this->replaced['filtered']['GET'][$tmp[1]] = $_GET[$tmp[1]];
	}
	protected function convertL2Attack($rule_id, $keyname)
	{
		$tmp = explode('.', $keyname);
		$tmp[0] = strtoupper($tmp[0]);
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
	protected function filterVariable($var, $rule_id)
	{
		$pattern = '/'.$this -> getPattern($rule_id).'/ims';
		$var = preg_replace($pattern, '', $var);
		return $var;
	}
	protected function getPattern($rule_id)
	{
		$query = "SELECT `filter` FROM `#__osefirewall_advancerules` WHERE `id` = " . (int) $rule_id;
		$this->db->setQuery($query);
		$result =  $this->db->loadObject();
		return $result->filter;
	}
	protected function cleanVariable($var)
	{
		return html_entity_decode(urldecode($var));
	}
	protected function redirect($url)
	{
		header('Location: '.$url);
	}
	protected function updateStatus($status)
	{
		$varValues = array (
				'status' => (int)$status
		);
		$result = $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
		return (boolean)$result;
	}
	protected function getScore()
	{
		$attrList = array(" `acl`.`score` AS `score`");
		$sql = convertViews::convertAclipmap($attrList);
		$query =  $sql." WHERE `acl`.`id` = ". (int)$this->aclid;
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return (isset($result->score))?(int)$result->score:0;
	}
	public function getVisits()
	{
		$attrList = array(" `acl`.`visits` AS `visits`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql." WHERE `acl`.`id` = ". (int)$this->aclid;
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return (isset($result->visits))?(int)$result->visits:0;

	}
	protected function getNotified()
	{
		$attrList = array(" `acl`.`notified` AS `notified`");
		$sql = convertViews::convertAclipmap($attrList);
		$query = $sql." WHERE `acl`.`id` = ". (int)$this->aclid;
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return (isset($result->notified))?(int)$result->notified:0;
	}
	protected function updateVisits()
	{
		$query = "UPDATE `#__osefirewall_acl` SET `visits` = (`visits` +1) WHERE `id` = ". (int)$this->aclid;
		$this->db->setQuery($query);
		$result = $this->db->query();
		return (boolean)$result;
	}
	protected function getDateTime () {
		oseFirewall::loadDateClass();
		$time = new oseDatetime(); 
		return $time->getDateTime (); 
	}
	protected function addACLRule($status, $score) {
		$page_id = $this->addPages();
		$referer_id = $this->addReferer();
		if (empty ($this->aclid)) {
			$varValues = array (
				'id' => '',
				'name' => $this->ip,
				'datetime' => $this -> getDateTime (),
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
			$this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
		}
	}
	protected function updateACLScore($score)
	{
		$varValues = array (
				'score' => $score
		);
		$this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
	}
	protected function addL1DetContent($attacktypeID, $detcontent_content = null, $rule_id = null) {
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
	protected function addL2DetContent($attacktypeIDArray, $detcontent_content, $rule_id, $varKey) {
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
	public function insertVarKey($varKey) {
		$query = 'SELECT `id` FROM `#__osefirewall_vars` WHERE `keyname` = ' . $this->db->quoteValue($varKey);
		$this->db->setQuery($query);
		$id = $this->db->loadResult();
		if (empty ($id)) {
			$varValues = array (
				'keyname' => $varKey,
				'status' => 1
			);
			$id = $this->db->addData('insert', '#__osefirewall_vars', null, null, $varValues);
			return $id;
		} else {
			$tmp = array_values($id); 
			$id = $tmp[0]; 
			return $id;
		}
	}
	protected function insertDetAttacktype($attacktypeID) {
		$varValues = array (
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
		$where[] = '`acl`.`id` = ' . (int) $this->aclid;
		$where[] = '`detattacktype`.`attacktypeid` = ' . (int) $attacktypeID;
		if (!empty ($rule_id)) {
			$where[] = '`detcontdetail`.`rule_id` = ' . (int) $rule_id;
		}
		$where = $this->db->implodeWhere($where);
		$sql = convertViews::convertAttackmap(array('COUNT(`acl`.`id`) as `count`'));
		$query = $sql . $where;
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return $result->count;
	}
	protected function insertDetContentDetail($detattacktype_id, $detcontent, $rule_id, $var_id) {
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
	protected function insertDetContent($detcontent) {
		$id = $this->getDetContentID ($detcontent);
		if (empty ($id)) {
			$varValues = array (
				'content' => $detcontent
			);
			$id = $this->db->addData('insert', '#__osefirewall_detcontent', null, null, $varValues);
			return $id;
		} else {
			return $id;
		}
	}
	protected function getDetContentID ($detcontent)
	{
		$query = 'SELECT `id` FROM `#__osefirewall_detcontent` WHERE `content` = ' . $this->db->quoteValue($detcontent);
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult());
		return (isset($result ->id))?$result ->id:null;
	}
	protected function addPages() {
		$query = 'SELECT `id`, `visits` FROM `#__osefirewall_pages` WHERE `page_url` = ' . $this->db->quoteValue($this->url, true);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		if (empty ($results)) {
			$varValues = array (
				'page_url' => $this->url,
				'action' => 1,
				'visits' => 1
			);
			$id = $this->db->addData('insert', '#__osefirewall_pages', null, null, $varValues);
		} else {
			$varValues = array (
				'visits' => $results->visits + 1
			);
			$this->db->addData('update', '#__osefirewall_pages', 'id', $results->id, $varValues);
			$id = $results->id;
		}
		return $id;
	}
	protected function addReferer() {
		$query = 'SELECT `id` FROM `#__osefirewall_referers` WHERE `referer_url` = ' . $this->db->quoteValue($this->referer, true);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		if (empty ($results)) {
			$varValues = array (
				'referer_url' => $this->referer
			);
			$id = $this->db->addData('insert', '#__osefirewall_referers', null, null, $varValues);
		} else {
			$id = $results->id;
		}
		return $id;
	}
	protected function getAllowBots() {
		$bots = array ();
		if ($this->scanGoogleBots == false) {
			$bots[] = 'Google';
		}
		if ($this->scanMsnBots == false) {
			$bots[] = 'msnbot';
		}
		if ($this->scanYahooBots == false) {
			$bots[] = 'Yahoo';
		}
		return $bots;
	}
	protected function set($var, $value) {
		$this-> $var = $value;
	}
	protected function get($var) {
		return $this-> $var;
	}
	protected function checkContinue() {
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
		if (isset($_GET['action']) && $_GET['action'] =='register') {
			return true;
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
					'com_ose_firewall',
					'com_akeeba',
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
	protected function customRedirect () {
		if (!empty($this->customBanURL))
		{
			header( 'Location: '.$this->customBanURL ) ;
		}	
	}
	protected function showBanPage() {
		$this->customRedirect ();
		$adminEmail = (isset ($this->adminEmail)) ? $this->adminEmail: '';
		$customBanPage = (!empty ($this->customBanpage)) ? $this->customBanpage: 'Banned';
		$pageTitle = (!empty ($this->pageTitle)) ? $this->pageTitle : 'Centrora Security';
		$metaKeys = (!empty ($this->metaKeywords)) ? $this->metaKeywords : 'Centrora Security';
		$metaDescription = (!empty ($this->metaDescription)) ? $this->metaDescription : 'Centrora Security';
		$metaGenerator = (!empty ($this->metaGenerator)) ? $this->metaGenerator : 'Centrora Security';
		$banhtml = $this->getBanPage($adminEmail, $pageTitle, $metaKeys, $metaDescription, $metaGenerator, $customBanPage);
		echo $banhtml; 
		$this->db->closeDBO(); 
		exit;
	}
	protected function getBanPage($adminEmail, $pageTitle, $metaKeys, $metaDescription, $metaGenerator, $customBanPage)
	{
		$banbody = $this->getBanPageBody($customBanPage, $adminEmail);
		header('Content-type: text/html; charset=UTF-8') ;
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
	protected function getBanPageBody($customBanPage, $adminEmail)
	{
		$banbody = '<div style="margin:auto;width:780px;border:0px solid #0082b0;padding:0px 10px 10px 10px;z-index:100;color:#000000;">
							  <br/>
								' . $customBanPage . '
							  <div style="font-family: arial,helvetica,sans-serif;background-color:#ffffff;padding: 10px 0px 0px 0px" align="center"><font color="#666666" size="1">Your IP address is ' . $this->ip . '. If you believe this is an error, please contact the <a href="mailto:' . $adminEmail . '?Subject=Inquiry:%20Banned%20for%20suspicious%20hacking%20behaviour - IP: ' . $this->ip . ' - Violation"> Webmaster </a>.</font></div>
					</div>';
		$banbody = str_replace ('info@opensource-excellence.com', $adminEmail, $banbody);
		$banbody = str_replace ('info@your-website.com', $adminEmail, $banbody);
		$banbody = str_replace ('OSE Team', 'Management Team', $banbody);
		return $banbody;
	}
	protected function show403Page() {
		$adminEmail = (isset ($this->adminEmail)) ? $this->adminEmail:oseFirewall::getAdminEmail(); 
		$customBanPage = (!empty ($this->customBanpage)) ? $this->customBanpage: 'Banned';
		$banbody = $this->getBanPageBody($customBanPage, $adminEmail);
		header('HTTP/1.1 200 OK');
		header('Content-type: text/html; charset=UTF-8') ;
		$banbody  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
						<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
							<title>403 Forbidden</title>
						</head>
						<body>
								'.$banbody.'
						</body>
					 </html>';
		echo $banbody;
		$this->db->closeDBO();
		exit;
	}
	protected function show403Msg ($msg) {
		header('HTTP/1.1 403 Forbidden');
		$banbody  = "<html>
						<head>
							<title>403 Forbidden</title>
						</head>
						<body>
								".$msg."
						</body>
					 </html>";
		echo $banbody;
		exit;
	}
	protected function sendEmail($type, $notified)
	{
		if ($this->receiveEmail == true && $notified == 0)
		{
			$config_var = $this->getConfigVars();
			oseFirewall::loadEmails();
			$oseEmail = new oseEmail('firewall');
			$email = $this->getEmailByType($type);
			$email = $this->convertEmail($email, $config_var);
			$receiptient = new stdClass();
			$receiptient->name = "Administrator";
			$receiptient->email = ($this->adminEmail=="info@opensource-excellence.com")?oseFirewall::getAdminEmail():$this->adminEmail;
			$result = $oseEmail->sendMailTo($email, $config_var, array($receiptient));
			$oseEmail->closeDBO ();
			if ($result == true)
			{
				$this->updateNotified(1);
			}
		}
	}
	protected function getEmailByType ($type) {
		$email = new stdClass();
		switch ($type) {
			case 'blacklisted':
				$email->subject = 'Centrora Security Alert For a Blacklisted Entry';
				break;
			case 'filtered':
				$email->subject = 'Centrora Security Alert For a Filtered Entry';
				break;
			case '403blocked':
				$email->subject = 'Centrora Security Alert For an Access Denied Entry';
				break;
		}
		$email->body = file_get_contents(dirname(__FILE__).'/email.tpl');
		return $email;
	}
	protected function updateNotified($status)
	{
		$varValues = array (
				'notified' => (int)$status
		);
		$result = $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
		return (boolean)$result;
	}
	protected function convertEmail($email, $config_var)
	{
		$attackTypetmp = $this->getAttackTypes();
		$attackType ='';
		foreach ($attackTypetmp as $key =>$value) {
			$attackType .= implode(',', $value).', ';	
		}
		$ipURL = $this ->getIPURL($config_var);
		$violation = $this->getViolation();
		$score = $this->getScore();
		$email->subject = $email->subject." for [".$_SERVER['HTTP_HOST']."]";
		$email->body = str_replace('{name}', 'Administrator', $email->body);
		$email->body = str_replace('{header}', $email->subject, $email->body);
		$email->body = str_replace('{attackType}', $attackType, $email->body);
		$email->body = str_replace('{violation}', $violation, $email->body);
		$email->body = str_replace('{logtime}', $this->logtime, $email->body);
		$email->body = str_replace('{ip}', $this->ip, $email->body);
		$email->body = str_replace('{ip_id}', $this->aclid, $email->body);
		$email->body = str_replace('{target}', $this->url, $email->body);
		$email->body = str_replace(array('{referrer}', '{referer}'), $this->referer, $email->body);
		$email->body = str_replace('{score}', $score, $email->body);
		return $email;
	}
	protected function getViolation()
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
		else
		{
			$return = $this->detected; 
		}
		return $return;
	}
	protected function getIPURL($config_var)
	{
		return null;
		//return "<a href='".$config_var->live_site."/administrator/index.php?option=com_ose_antihacker&view=manageips&id=".$this->aclid."'>".$this->aclid."</a>";
	}
	protected function getAttackTypes()
	{
		$attrList = array("DISTINCT `attacktype`.`name` AS `name`");
		$sql = convertViews::convertAttackmap($attrList);
		$query = $sql.'WHERE `acl`.`id` = '.(int)$this->aclid;
		$this->db->setQuery($query);
		$results = $this->db -> loadResultArray();
		return $results;
	}
	protected function getConfigVars()
	{
		return oseFirewall::getConfigVars(); 
	}
	protected function CheckSpambotEmail() {
		// Initiate and declare spambot/errorDetected as false - as we're just getting started
		$isspamcheck = $this ->isspamcheck();
		$scanReturn = array ();
		$spambot = false;
		if ($spambot != true && $this->ip != "" && (int)$isspamcheck <3) {
			$email = '';
			if (isset($_POST['jform']['email1']) && !empty($_POST['jform']['email1']))
			{
				$email = $_POST['jform']['email1'];
			}
			if (isset($_POST['user_email']) && !empty($_POST['user_email']))
			{
				$email = $_POST['user_email'];
			}
			if (!empty($email))
			{
				$data = array ();
				$data["email"] = $_POST['jform']['email1'];
				$data["f"] = 'json';
				$json_return = $this->posttoSFS($data);
				$result = oseJSON :: decode($json_return);
				$this->updatespamcheck(2);
				if (!isset($result->email->confidence)) {
					return false;
				}
				elseif ($result->email->appears == 1 && $result->email->confidence >= (int)$this->sfs_confidence) // Was the result was registered
				{
					$spambot = true; // Check failed. Result indicates dangerous.
					$return = $this->composeResult(100, oseJSON::encode($data["email"]), 1, 11, 'server.HTTP_CLIENT_IP') ;
					$return['spamtype'] = 'email';
					return $return;
				} else {
					return false; // Check passed. Result returned safe.
				}
				unset ($data);
				unset ($json_return);
				unset ($result);
			}
		}
		return $spambot; // Return test results as either true/false or 1/0
	}
	protected function CheckIsSpambot() {
		// Initiate and declare spambot/errorDetected as false - as we're just getting started
		$isspamcheck = $this ->isspamcheck();
		$scanReturn = array ();
		$spambot = false;
		if ($spambot != true && $this->ip != "" && (int)$isspamcheck < 1) {
			$data = array ();
			$data["ip"] = $this->ip;
			$data["f"] = 'json';
			$json_return = $this->posttoSFS($data);
			$result = oseJSON :: decode($json_return);
			$this->updatespamcheck(1);
			if (!isset($result->ip->confidence)) {
				return false;
			}
			elseif ($result->ip->appears == 1 && $result->ip->confidence >= (int)$this->sfs_confidence) // Was the result was registered
			{
				$spambot = true; // Check failed. Result indicates dangerous.
				$return = $this->composeResult(100, oseJSON::encode($result->ip), 1, 11, 'server.HTTP_CLIENT_IP') ;
				return $return;
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
	protected function updatespamcheck($type)
	{
		$varValues = array (
				'ip32_start' => $this->ip32,
				'ischecked' => (int)$type
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
	protected function scanUploadFiles () {
		$this->getAllowExts ();
		$scanResult = $this->checkFileTypes();
		return $scanResult; 
	}
	protected function getAllowExts () {
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'allowExts' ";
		$this->db->setQuery($query);
		$result = (object) $this->db->loadResult();
		$this->allowExts = (!empty($result->value))?$result->value:null;
	}
	protected function cleanFileVariable($fileVar)
	{
		if (is_array($fileVar)) {
			foreach ($fileVar as $filetmp) {
				$fileVar= $filetmp;
				break;
			}
		}
		return $fileVar;
	}
	protected function checkFileTypes() {
		if (!empty ($this->allowExts)) {
			foreach ($_FILES as $file) {
				if (!empty ($file['tmp_name'])) {
					if (is_array($file['tmp_name']))
					{
						$file['tmp_name'] = $file['tmp_name'][0];
					}
					if (!empty($file['tmp_name']))
					{
						$file['tmp_name'] = $this->cleanFileVariable($file['tmp_name']);
						$file['type'] = $this->cleanFileVariable($file['type']);
						$mimeType = $this->getMimeType($file);
						$ext = explode('/', $file['type']);
						$allowExts = explode(",", trim($this->allowExts));
						$allowExts = array_map('trim', $allowExts);
						if ($ext[1] == 'vnd.openxmlformats-officedocument.wordprocessingml.document' && ($mimeType[1] != $ext[1])) {
							$ext[1] = 'msword';
						}
						if ($ext[1] != $mimeType[1]) {
							$return = $this->composeResult(100, $file['name'], 11, oseJSON::encode(array(13)), 'server.FILE_TYPE') ;
							$this->unlinkUPloadFiles();
							return $return;
						}
						elseif (in_array($mimeType[1], $allowExts) == false) {
							$this -> show403Msg('The upload of this file type ' . $mimeType[1] . ' is not allowed this website. If you are the server administrator, please add the extensions in the configuraiton in the configuration panel first.');
						}
						else
						{
							return null;
						}
					}
				}
			}
		}
	}
	protected function getMimeType($file) {
		$mimeType = $this->getFileInfo($file['tmp_name']);
		if (empty ($mimeType)) {
			$mimeType = $this -> checkisPHPfile($file['tmp_name']);
		}
		if (!empty ($mimeType)) {
			if (strstr($mimeType, '/')!=false)
			{
				$mimeType = explode("/", $mimeType);
			}
			else
			{
				$tmp = explode(" ", $mimeType);
				$mimeType = array();
				$mimeType[0] = strtolower ($tmp [1]);
				$mimeType[1] = strtolower ($tmp [0]);
			}
		} else {
			$mimeType = explode("/", $file['type']);
		}
		return $mimeType;
	}
	protected function getFileInfo($filename) {
		if (!defined('FILEINFO_MIME_TYPE')) {
			define('FILEINFO_MIME_TYPE', 1);
		}
		$defined_functions = get_defined_functions();
		if ((in_array('finfo_open', $defined_functions['internal'])) || function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$content_type = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $content_type;
		}
		elseif (function_exists('mime_content_type')) {
			$content_type = mime_content_type($filename);
			return $content_type;
		} else {
			return false;
		}
	}
	protected function checkisPHPfile($file) {
		if (empty($file)) {
			return false;
		}
		if (filesize($file) > '2048000') {
			return false;
		}
		$data = file($file);
		$data = implode("\r\n", $data);
		$pattern = "/(\<\?)|(\<\?php)/";
		if (preg_match($pattern, $data)) {
			return 'application/x-httpd-php';
		} else {
			return false;
		}
	}
	protected function unlinkUPloadFiles () {
		if (is_array($_FILES['tmp_name']))
		{
			foreach ($_FILES['tmp_name'] as $filetmp)
			{
				unlink($filetmp);
				break;
			}
		}
		else
		{
			unlink($_FILES['tmp_name']);
		}
		unset ($_FILES);
	}
	protected function checkCountryStatus () {
		$ready = oseFirewall::getGeoIPState(); 
		if ($ready == true)
		{
			if ($this->blockCountry == false)
			{
				return false; 
			}
			else 
			{
				$query = "SELECT country.`status` FROM `#__ose_app_geoip` as `ip` LEFT JOIN `#__osefirewall_country` AS `country` ON country.country_code = ip.country_code WHERE ".$this->db->QuoteValue($this->ip32)." BETWEEN ip.ip32_start AND ip.ip32_end ";
				$this->db->setQuery($query);
				$result = $this->db->loadObject();
				if (!empty($result))
				{
					if ($result->status == 1)
					{
						$this->showCountryBlockMsg ();
					} 
					else if ($result->status == 2)
					{
						return false; 
					}
					else if ($result->status == 3)
					{
						return true; 
					}
				}
				else
				{
					return false;
				}
			}
		} 
		else
		{
			return false;
		}
	}
	protected function showCountryBlockMsg () {
		$style= "font-family: arial; background: none repeat scroll 0 0 #0C56B0; border-bottom: 5px solid #4D91E2;color: #FFFFFF; padding: 10px; font-size: 12px; text-align: center;";
		$html = "<div style='".$style."'>Your country is not allowed to access in this website</div>";
		die($html);
	}
	protected function clearWhitelistVars ($request) {
		$varArray = $this->getWhitelistVars();
		foreach ($request as $method => $array )
		{
			foreach ($array as $key => $value)
			{
				if (in_array($method.'.'.$key, $varArray))
				{
					unset($request[$method][$key]);	
				}
			}
		}
		return $request; 
	}
	protected function getWhitelistVars() {
		$query = "SELECT `keyname` FROM `#__osefirewall_vars` WHERE `status`  = 3 ";
		$this->db->setQuery ( $query );
		$results = $this->db->loadArrayList ( 'keyname' );
		return $results;
	} 
	protected function composeResult($impact, $content, $rule_id, $attackTypeID, $keyname, $type) {
		$return = array ();
		$return ['impact'] = $impact;
		$return ['attackTypeID'] = $attackTypeID;
		$return ['detcontent_content'] = $content;
		$return ['keyname'] = $keyname;
		$return ['rule_id'] = $rule_id;
		$return ['type'] = $type;
		return $return;
	}
	protected function logDomain() {
		$serverName = $_SERVER['SERVER_NAME'];
		$query ="SELECT * FROM `#__osefirewall_logs` WHERE `comp` = 'dom' AND `status` = ".$this->db->quoteValue($serverName)." LIMIT 1";
		$this->db->setQuery($query);
		$this->db->query();
		$result = $this->db->loadObject();
		if (!$result) {
			$time = $this->getDateTime ();
			$query = "INSERT INTO `#__osefirewall_logs` (
					`id`,
					`date`,
					`comp`,
					`status`
					)
					VALUES(
					NULL,".$this->db->quoteValue($time).",'dom',".$this->db->quoteValue($serverName).");";
			$this->db->setQuery($query);
			$this->db->query();
		}
	}
}