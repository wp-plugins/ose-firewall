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
class oseFirewallAudit
{
	private $warning = array();
	private $urls = array();
	public function __construct()
	{
		oseFirewall::callLibClass('firewallstat', 'firewallstatPro');
		$this->urls = oseFirewall::getDashboardURLs();
	}
	public function isDevelopModelEnable($print = true)
	{
		$return = '';
		$dbReady = oseFirewall::isDBReady();
		$action = ($print == true) ? '<a class="btn btn-danger btn-xs fx-button" href ="#" data-target="#configModal" data-toggle="modal">Fix It</a>' : '';
		if ($dbReady == true)
		{
			$oseFirewallStat = new oseFirewallStat();
			$isEnable = $oseFirewallStat->getConfigurationByName('devMode');
			if ($isEnable)
			{
				$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('DISDEVELOPMODE')." ".$action."</li>";
			}
			else
			{
				$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('DEVELOPMODE_DISABLED').' </li>';
			}
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	protected function getAdvFirewallURL () {
		if (OSE_CMS =='joomla')
		{
			$url = 'index.php?option=com_ose_firewall&view=advancerulesets';
		}
		else
		{
			$url = 'admin.php?page=ose_fw_adrulesets';
		}
		return $url;
	}
	public function isAdFirewallReady($print = true)
	{
		$return = '';
		$oseFirewallStat = new oseFirewallStatPro();
		$isReady = $oseFirewallStat->isAdFirewallReady();
		$url = $this->getAdvFirewallURL ();
		$action = ($print == true) ? '<a class="btn btn-danger btn-xs fx-button" href ="'.$url.'" target="_blank">Fix It</a>' : '';
		if (!$isReady)
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('ADVANCERULESNOTREADY')." ".$action." </li>";
		}
		else
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('ADVANCERULES_READY').' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function isAdminExistsReady($print = true)
	{
		$return = '';
		$oseFirewallStat = new oseFirewallStatPro();
		$userID = $oseFirewallStat->isUserAdminExist ();
		if ($userID != false)
		{
			$action = ($print == true) ? '<a href="#" data-target="#adminFormModal" data-toggle="modal" class="btn btn-danger btn-xs fx-button" >Fix It</a>' : '';
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('ADMINUSER_EXISTS')." ".$action." </li>";
		}
		else
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('ADMINUSER_REMOVED').' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function isGAuthenticatorReady($print = true)
	{
		if (OSE_CMS == 'joomla')
		{
			return;
		}
		else
		{
			$oseFirewallStat = new oseFirewallStatPro();
			$ready = $oseFirewallStat->isGAuthenticatorReady ();
			$action = ($print == true) ? '<a class="btn btn-danger btn-xs fx-button" href ="http://www.centrora.com/plugin-tutorial/google-2-step-verification/" target="_blank">Fix It</a>' : '';
			if ($ready == true)
			{
				$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('GAUTHENTICATOR_READY')."</li>";
			}
			else
			{
				$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('GAUTHENTICATOR_NOTUSED')." ".$action.' </li>';
			}
			if ($print == true)
			{
				echo $return;
			}
			else
			{
				return $return;
			}
		}
	}
	public function isVSScanningReady($print = true)
	{
		$oseFirewallStat = new oseFirewallStatPro();
		$ready = $oseFirewallStat->isGAuthenticatorReady ();
		$action = ($print == true) ? '<a class="btn btn-danger btn-xs fx-button" href ="http://www.centrora.com/plugin-tutorial/google-2-step-verification/" target="_blank">Fix It</a>' : '';
		if ($ready == true)
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('GAUTHENTICATOR_READY')."</li>";
		}
		else
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('GAUTHENTICATOR_NOTUSED')." ".$action.' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function isWPUpToDate($print = true)
	{
		if (OSE_CMS == 'joomla')
		{
			return;
		}
		else
		{
			$oseFirewallStat = new oseFirewallStatPro();
			$updated = $oseFirewallStat->isWPUpToDate ();
			global $wp_version;
			$wp_version = htmlspecialchars($wp_version);
			$action = ($print == true) ? '<a href="update-core.php" class="btn btn-danger btn-xs fx-button">Fix It</a>' : '';
			if ($updated == true)
			{
				$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('WORDPRESS_UPTODATE').$wp_version."</li>";
			}
			else
			{
				$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('WORDPRESS_OUTDATED').$wp_version.". ".$action.' </li>';
			}
			if ($print == true)
			{
				echo $return;
			}
			else
			{
				return $return;
			}
		}
	}
	public function isGoogleScan($print = true)
	{
		$return = '';
		$oseFirewallStat = new oseFirewallStatPro();
		$enabled = $oseFirewallStat->isGoogleScan ();
		$action = ($print == true) ? '<a href="#" data-target="#seoConfigModal" data-toggle="modal" class="btn btn-danger btn-xs fx-button">Fix It</a>' : '';
		if ($enabled == true)
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('GOOGLE_IS_SCANNED').". ".$action."</li>";
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function isSignatureUpToDate($print = true)
	{
		$return = '';
		$oseFirewallStat = new oseFirewallStatPro();
		$version = $oseFirewallStat->getCurrentSignatureVersion();
		$url = $this->getAdvFirewallURL ();
		$action = ($print == true) ? '<a href="'.$this->urls[6].'" class="btn btn-danger btn-xs fx-button">Fix It</a>' : '';
		if ($version > O_LATEST_SIGNATURE)
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('SIGNATURE_UPTODATE')."</li>";
		}
		else
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('SIGNATURE_OUTDATED').". ".$action.' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function checkRegisterGlobals ($print) {
		$return = '';
		$enable = $this->getPHPConfig('register_globals');
		$action = ($print == true) ? '<a href="#" class="btn btn-danger btn-xs fx-button" onClick="checkphpConfig();">Fix It</a>' : '';
		if ($enable == false)
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('REG_GLOBAL_OFF')."</li>";
		}
		else
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('REG_GLOBAL_ON')." .".$action.' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function checkSafeMode ($print) {
		$return = '';
		$enable = $this->getPHPConfig('safe_mode');
		$action = ($print == true) ? '<a href="#" class="btn btn-danger btn-xs fx-button" onClick="checkphpConfig();">Fix It</a>' : '';
		if ($enable == false)
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('SAFEMODE_OFF')."</li>";
		}
		else
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('SAFEMODE_ON')." .".$action.' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function checkURLFopen ($print) {
		$return = '';
		$enable = $this->getPHPConfig('allow_url_fopen');
		$action = ($print == true) ? '<a href="#"  class="btn btn-danger btn-xs fx-button" onClick="checkphpConfig();">Fix It</a>' : '';
		if ($enable == false)
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('URL_FOPEN_OFF')."</li>";
		}
		else
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('URL_FOPEN_ON').". ".$action.' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function checkDisplayErrors ($print) {
		$return = '';
		$enable = $this->getPHPConfig('display_errors');
		$action = ($print == true) ? '<a href="#" class="btn btn-danger btn-xs fx-button" onClick="checkphpConfig();">Fix It</a>' : '';
		if ($enable == false)
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('DISPLAY_ERROR_OFF')."</li>";
		}
		else
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('DISPLAY_ERROR_ON')." .".$action.' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function checkDisableFunctions ($print) {
		$return = '';
		$result = $this->getDisableFunctions();
		$action = ($print == true) ? '<a href="#"  class="btn btn-danger btn-xs fx-button" onClick="checkphpConfig();">Fix It</a> ' : '';
		if ($result['result'] == false)
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('DISABLE_FUNCTIONS_READY').$result['off']."</li>";
		}
		else
		{
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('DISABLE_FUNCTIONS_NOTREADY').$result['on']." ".$action.' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	private function getDisableFunctions () {
		$return = array (); 
		$funcArray =  array('exec','passthru','shell_exec','system','proc_open','curl_multi_exec','show_source','eval');
		foreach ($funcArray as $func) {
			$on = function_exists($func);
			if ($on == true)
			{
				$return['on'][]= $func;
			} 
			else 
			{
				$return['off'][]= $func;
			}
		}
		if (isset($return['on']) && !empty($return['on']))
		{
			$return['result'] = true; 
		}
		else
		{
			$return['result'] = false;
		}
		if (isset($return['on']) && !empty($return['on']))
		{
			$return['on'] = implode(",", $return['on']);
		}
		else
		{
			$return['on'] = '';
		}
		if (isset($return['off']) && !empty($return['off']))
		{
			$return['off'] = implode(",", $return['off']);
		}
		else
		{
			$return['off'] = '';
		}
		return $return; 
	}
	private function getPHPConfig ($key) {
		if (function_exists('ini_get'))
		{
			return ini_get($key);
		}
		else
		{
			return 'N/A';
		}
	}
	public function runReport()
	{
		$continue = $this->checkContinue();
		if ($continue == false)
		{
			return;
		}
		else
		{
			$this->loadPreRequisities ();
			$report = $this->getReportContent();
			$template = $this->loadTemplate();
			$newreport = $this->translateTemplate($report, $template);
			$this->sendEmail($newreport);
		}
	}
	private function sendEmail($report)
	{
		$email = new stdClass();
		$email->subject = "Centrora Security Daily Audit Report";
		$email->body = $report;
		$config_var = oseFirewall::getConfigVars();
		$receiptient = new stdClass();
		$receiptient->name = "Administrator";
		$receiptient->email = $config_var->mailfrom;
		$this->sendMail($email, $config_var, $receiptient);
	}
	public function sendMail($email, $config_var, $receiptient)
	{
		require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'emails'.ODS.'oseEmailHelper.php');
		require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'emails'.ODS.'phpmailer'.ODS.'phpmailer.php');
		require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'emails'.ODS.'phpmailer'.ODS.'smtp.php');
		if (empty($receiptient))
		{
			return false;
		}
		$email->body = str_replace('[user]', $receiptient->name, $email->body);
		$mailer = new PHPMailer();
		$mailer->From = $config_var->mailfrom;
		$mailer->FromName = $config_var->fromname;
		if ($config_var->mailer == 'smtp')
		{
			$mailer->useSMTP($config_var->smtpauth, $config_var->smtphost, $config_var->smtpuser, $config_var->smtppass, $config_var->smtpsecure, $config_var->smtpport);
		}
		$recipient = OSEMailHelper::cleanLine($receiptient->email);
		$mailer->AddAddress($recipient);
		$mailer->Subject = OSEMailHelper::cleanLine($email->subject);
		$mailer->Body = OSEMailHelper::cleanText($email->body);
		$mailer->IsHTML(true);
		$mailer->Send();
		return true;
	}
	private function checkContinue()
	{
		$dbReady = oseFirewall::isDBReady();
		if ($dbReady == false)
		{
			return false;
		}
		else
		{
			$time = oseFirewall::getTime();
			$db = oseFirewall::getDBO();
			$schedule = $this->checkAuditSchedule ($db);
			if (empty($schedule))
			{
				$db->closeDBO();
				return false;
			}
			else
			{
				$interval = $this->getInterval ($schedule);
				$query = "SELECT * FROM `#__osefirewall_logs` AS log WHERE `comp` = 'aud'";
				$db->setQuery($query);
				$result = $db->loadObject();
				if (empty($result))
				{
					$this->insertLogTime ($db);
					$db->closeDBO();
					return true;
				}
				else
				{
					$query = "SELECT * FROM `#__osefirewall_logs` AS log WHERE `comp` = 'aud' AND DATEDIFF( ".$db->QuoteValue($time).", log.date)>=".(int) $interval;
					$db->setQuery($query);
					$result = $db->loadObject();
					if (!empty($result))
					{
						$this->updateLogTime ($db, '');
						$db->closeDBO();
						return true;
					}
					else
					{
						$db->closeDBO();
						return false;
					}
				}
			}
		}
	}
	private function getInterval($schedule)
	{
		$days = 1;
		switch ($schedule)
		{
		default:
		case 1:
			$days = 1;
			break;
		case 2:
			$days = 7;
			break;
		case 3:
			$days = 30;
			break;
		}
		return $days;
	}
	private function checkAuditSchedule($db)
	{
		$query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'auditReport'";
		$db->setQuery($query);
		$result = $db->loadResult();
		return ($result['value']);
	}
	private function insertLogTime($db)
	{
		$time = oseFirewall::getTime();
		$varValues = array(
			'date' => $time,
			'comp' => 'aud',
			'status' => '',
		);
		$website_id = $db->addData('insert', '#__osefirewall_logs', '', '', $varValues);
	}
	private function updateLogTime($db, $status = '')
	{
		$time = oseFirewall::getTime();
		$varValues = array(
			'date' => $time,
			'status' => $status,
		);
		$website_id = $db->addData('update', '#__osefirewall_logs', 'comp', 'aud', $varValues);
	}
	private function translateTemplate($report, $template)
	{
		$config = oseFirewall::getConfigVars();
		$template = str_replace("[report]", $report, $template);
		$template = str_replace("[website]", $config->url, $template);
		$template = str_replace("[web_url]", $config->url."/wp-admin/admin.php?page=ose_fw_adrulesets", $template);
		$status = $this->getSafeBrowsingStatus();
		if (empty($status))
		{
			$status = $this->getStatusObject ();
			$status = $this->getStatusTable ($status);
			$template = str_replace("[safebrowsing]", "Not checked yet, please access the <a href='".$config->url."/wp-admin/admin.php?page=ose_firewall'> Dashboard </a> to check if your website is clean.<br/>".$status, $template);
		}
		else
		{
			$status = $this->getStatusTable ($status);
			$template = str_replace("[safebrowsing]", $status, $template);
		}
		return $template;
	}
	private function getStatusObject()
	{
		$status = new stdClass();
		$status->norton = 'n/a';
		$status->bitdefender = 'n/a';
		$status->avg = 'n/a';
		$status->mcafee = 'n/a';
		$status->google = 'n/a';
		return $status;
	}
	private function loadTemplate()
	{
		oseFirewall::loadFiles();
		$oseFile = new oseFile();
		$template = $oseFile->read(dirname(__FILE__)."/template.html");
		return $template;
	}
	private function getReportContent()
	{
		$this->isDevelopModelEnable(false);
		$this->isAdminExistsReady(false);
		$this->isGAuthenticatorReady(false);
		$this->isWPUpToDate (false);
		$this->isGoogleScan (false);
		$this->isAdFirewallReady(false);
		$this->isSignatureUpToDate(false);
		$this->checkRegisterGlobals(false);
		$this->checkSafeMode(false);
		$this->checkURLFopen(false);
		$this->checkDisplayErrors(false);
		$this->checkDisableFunctions(false);
		$config_var = oseFirewall::getConfigVars();
		
		if (!empty($this->warning))
		{
			$report = "<div style='font-weight: bold; color: red;'>Please note that your website is not 100% secure. Please review the following items in the <a href='".$config_var->url."/wp-admin/admin.php?page=ose_firewall'>dashboard</a> and fix them.</div>";
			$report .= '<ul class="list-group">';
			foreach ($this->warning as $warning)
			{
				$report .= '<li class="list-group-item">'.$warning.'</li>';
			}
			$report .= '</ul>';
		}
		else
		{
			$report = "<div style='font-weight: bold; color: #49FF40;'>Great! Everything looks right now.</div>";
		}
		$report .= "<br/>";
		$total = $this->getTotalBlockWebsites();
		$report .= "<div>Total website blocked since Centrora security is installed: ".$total."</div>";
		return $report;
	}
	private function getTotalBlockWebsites()
	{
		$oseFirewallStat = new oseFirewallStatPro();
		$total = $oseFirewallStat->getTotalBlockWebsites();
		return $total;
	}
	private function loadPreRequisities()
	{
		oseFirewall::loadLanguage();
		require_once(ABSPATH."wp-includes/pluggable.php");
		require_once(ABSPATH."wp-includes/functions.php");
		require_once(ABSPATH."wp-admin/includes/update.php");
	}
	public function showSafeBrowsingBar($print = true)
	{
		$dbReady = oseFirewall::isDBReady();
		if ($dbReady == true)
		{
			$safeBrowsingStatus = $this->getSafeBrowsingStatus ();
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('SAFE_BROWSING_CHECKUP_UPDATED');
			$return .= $this->getStatusTable ($safeBrowsingStatus);
			$return .= "</li>";
		}
		else
		{
			$return = '<div class ="warning"><div class="warning-content">'.oLang::_get('CHECK_SAFE_BROWSING').' </div>'.'</div>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function getSafeBrowsingStatus()
	{
		oseFirewall::callLibClass('panel', 'panel');
		$panel = new panel();
		$status = $panel->getSafeBrowsingStatus();
		return $status;
	}
	private function isSafeBrowsingStatusUpdated($safeBrowsingStatus)
	{
		if (isset($safeBrowsingStatus->checkup_date) || isset($safeBrowsingStatus->time))
		{
			if (!isset($safeBrowsingStatus->checkup_date))
			{
				$safeBrowsingStatus->checkup_date = $safeBrowsingStatus->time; 
			}
			$datetime1 = new DateTime($safeBrowsingStatus->checkup_date);
			$datetime2 = new DateTime();
			$interval = $datetime1->diff($datetime2);
			return ($interval->days > 2) ? false : true;
		}
		else
		{
			return false;
		}
	}
	private function getStatusTable($status)
	{
		$status = $status->status->safeBrowsing;
		$table = '<br/><br/><table class="table">';
		$tr1 = '';
		$tr2 = '';
		if (!empty($status))
		{
			foreach ($status as $key => $value)
			{
				$tr1 .= '<th class="status'.$key.'"  style="text-align:center;">'.str_replace("_", " ", ucfirst($key)).'</th>';
				if ($value =='ok')
				{
					$class='success';
				}
				else if ($value =='unknown')
				{
					$class='warning';
				}
				else
				{
					$class='danger';
				}
				$tr2 .= '<td class="statusItem" style="text-align:center;"><span class="strong text-'.$class.'">'.$value.'</span></td>';
			}
		}	
		$table .= '<tr>'.$tr1.'</tr>';
		$table .= '<tr>'.$tr2.'</tr>';
		$table .= '</table>';
		return $table;
	}
	public function enhanceSysSecurity () {
		$dbReady = oseFirewall::isDBReady();
		if ($dbReady == true) {
			$settings = $this->checkSysSecurity(); 
			foreach ($settings as $setting)
			{
				if ($setting->value==1)
				{
					$this->changePHPSetting($setting->key);
				}
			}
		}
	}
	private function checkSysSecurity()
	{
		$db = oseFirewall::getDBO (); 
		$query = "SELECT `key`, `value` FROM `#__ose_secConfig` WHERE (`key` = 'registerGlobalOff' OR `key` = 'safeModeOff' OR `key` = 'urlFopenOff' OR `key` = 'displayErrorsOff' OR `key` = 'phpFunctionsOff')";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		$db->closeDBO();
		return ($result);
	}
	private function changePHPSetting ($key)
	{
		if (function_exists('ini_set'))
		{
			switch ($key) {
				case 'registerGlobalOff':
					ini_set('register_global', 0);
				break;
				case 'safeModeOff':
					ini_set('safe_mode', 0);
				break;
				case 'urlFopenOff':
					ini_set('allow_url_fopen', 0);
				break;
				case 'displayErrorsOff':
					ini_set('display_errors', 0);
				break;
				case 'phpFunctionsOff':
					ini_set('disable_functions', 'exec,passthru,shell_exec,system,proc_open,curl_multi_exec,show_source');
				break;
			}
		}
	}
	public function checkSysPlugin($print = true)
	{
		$return = '';
		$oseFirewallStat = new oseFirewallStatPro();
		$plugin = $this->isPluginEnabled ('plugin', 'centrora', 'system');
		if (empty($plugin) || !$plugin->enabled)
		{
			$action = ($print == true) ?  $this->getPluginActionLink($plugin): '';
			$this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> '.oLang::_get('SYSTEM_PLUGIN_DISABLED').$action." </li>";
		}
		else
		{
			$return = '<li class="list-group-item"><span class="label label-success">OK</span> '.oLang::_get('SYSTEM_PLUGIN_READY').' </li>';
		}
		if ($print == true)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}
	public function getPluginActionLink ($plugin) {
		return '<a class="btn btn-danger btn-xs fx-button" href ="index.php?option=com_plugins&task=plugin.edit&extension_id='.$plugin->extension_id.'" target="_blank">Fix It</a>';
	}
	public function isPluginEnabled ($type, $element, $folder) {
		$db = oseFirewall::getDBO (); 
		$query = "SELECT `extension_id`, `enabled` FROM `#__extensions` WHERE `type` = ".$db->QuoteValue($type)." AND `element` = ".$db->QuoteValue($element)." AND `folder` = ".$db->QuoteValue($folder);
		$db->setQuery($query);
		$result = $db->loadObject();
		$db->closeDBO();
		return ($result);
	}
}
