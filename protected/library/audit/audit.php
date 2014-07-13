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
	die('Direct Access Not Allowed');
}
class oseFirewallAudit
{	
	private $warning = array (); 
	public function __construct()
	{
		oseFirewall::callLibClass('firewallstat', 'firewallstatPro');
	}
	public function isDevelopModelEnable($print = true){
		$dbReady = oseFirewall :: isDBReady();
		$action = ($print == true)?'<div class = "warning-buttons"><a class = "button-primary" href ="admin.php?page=ose_fw_scanconfig" target="_blank">Fix It</a></div>':'';
		if ($dbReady == true)
		{
			$oseFirewallStat = new oseFirewallStat();
			$isEnable = $oseFirewallStat->getConfigurationByName('devMode');
			if($isEnable)
			{
				$this->warning[] = $return = '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('DISDEVELOPMODE')."</div>".$action."</div>";
			}
			else {
				$return = '<div class ="ready">' . oLang :: _get('DEVELOPMODE_DISABLED') .' </div>';
			}
		}
		if ($print==true){echo $return;} 
		else { return $return; }
	}
	
	public function isAdFirewallReady($print = true){
		$oseFirewallStat = new oseFirewallStatPro();
		$isReady = $oseFirewallStat->isAdFirewallReady();
		$action = ($print == true)?'<div class = "warning-buttons"><a class = "button-primary" href ="http://www.centrora.com/centrora-tutorial/enabling-advance-firewall-setting/" target="_blank">Fix It</a></div>':'';
		if(!$isReady)
		{
			$this->warning[] = $return = '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('ADVANCERULESNOTREADY')." </div>". $action." </div>";
		}
		else {
			$return = '<div class ="ready">' . oLang :: _get('ADVANCERULES_READY') .' </div>';
		}
		if ($print==true){echo $return;} 
		else { return $return; }
	}
	public function isAdminExistsReady($print = true){
		$oseFirewallStat = new oseFirewallStatPro();
		$userID = $oseFirewallStat->isUserAdminExist ();
		if($userID != false)
		{
			$action = ($print == true)?'<div class = "warning-buttons"> <a href="#" class="button-primary" onClick = "showForm()">Fix It</a> </div>':'';
			$this->warning[] = $return =  '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('ADMINUSER_EXISTS'). "</div>".$action." </div>";
		}
		else {
			$return = '<div class ="ready">' . oLang :: _get('ADMINUSER_REMOVED') .' </div>';
		}
		if ($print==true){echo $return;} 
		else { return $return; }
	}
	public function isGAuthenticatorReady($print = true){
		$oseFirewallStat = new oseFirewallStatPro();
		$ready = $oseFirewallStat->isGAuthenticatorReady ();
		$action = ($print == true)?'<div class = "warning-buttons"><a class="button-primary" href ="http://www.centrora.com/plugin-tutorial/google-2-step-verification/" target="_blank">Fix It</a></div>':'';
		if($ready == true)
		{
			$return = '<div class ="ready">' . oLang :: _get('GAUTHENTICATOR_READY'). "</div>";
		}
		else {
			$this->warning[] = $return = '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('GAUTHENTICATOR_NOTUSED') ."</div> ". $action. ' </div>';
		}
		if ($print==true){echo $return;} 
		else { return $return; }
	}
	public function isWPUpToDate ($print = true) {
		$oseFirewallStat = new oseFirewallStatPro();
		$updated = $oseFirewallStat->isWPUpToDate ();
		global $wp_version;
		$wp_version = htmlspecialchars($wp_version);
		$action = ($print == true)?'<div class = "warning-buttons"> <a href="update-core.php" class="button-primary">Fix It</a> </div>':'';
		if($updated == true)
		{
			$return = '<div class ="ready">' . oLang :: _get('WORDPRESS_UPTODATE'). $wp_version. "</div>";
		}
		else {
			$this->warning[] = $return = '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('WORDPRESS_OUTDATED') . $wp_version. ".</div> ". $action. ' </div>';
		}
		if ($print==true){echo $return;} 
		else { return $return; }
	}
	public function isGoogleScan ($print = true) {
		$oseFirewallStat = new oseFirewallStatPro();
		$enabled = $oseFirewallStat->isGoogleScan ();
		$action = ($print == true)?'<div class = "warning-buttons"> <a href="admin.php?page=ose_fw_seoconfig" class="button-primary">Fix It</a> </div>':'';
		if($enabled == true)
		{
			$this->warning[] = $return = '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('GOOGLE_IS_SCANNED'). ".</div> ". $action. "</div>";
		}
		if ($print==true){echo $return;} 
		else { return $return; }
	}
	public function isSignatureUpToDate ($print = true) {
		$oseFirewallStat = new oseFirewallStatPro();
		$version = $oseFirewallStat->getCurrentSignatureVersion(); 
		$action = ($print == true)?'<div class = "warning-buttons"> <a href="admin.php?page=ose_fw_adrulesets" class="button-primary">Fix It</a> </div>':'';
		if($version>O_LATEST_SIGNATURE)
		{
			$return =  '<div class ="ready">' . oLang :: _get('SIGNATURE_UPTODATE'). "</div>";
		}
		else {
			$this->warning[] = $return =  '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('SIGNATURE_OUTDATED') . ".</div> ". $action. ' </div>';
		}
		if ($print==true){echo $return;} 
		else { return $return; }
	}
	public function runReport () {
		$continue = $this->checkContinue(); 
		if ($continue  == false) 
		{ 
			return ; 
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
	private function sendEmail($report) {
		$email  = new stdClass(); 
		$email->subject = "Centrora Security Daily Audit Report";
		$email->body = $report;
		$config_var = oseFirewall::getConfigVars();
		$receiptient = new stdClass (); 
		$receiptient->name = "Administrator"; 
		$receiptient->email = $config_var->mailfrom;
		$this->sendMail($email, $config_var, $receiptient);
	}
	public function sendMail($email, $config_var, $receiptient) {
		require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'emails' . ODS . 'oseEmailHelper.php');
		require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'emails' . ODS . 'phpmailer' . ODS . 'phpmailer.php');
		require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'emails' . ODS . 'phpmailer' . ODS . 'smtp.php');
		if (empty ($receiptient)) {
			return false;
		}
		$email->body = str_replace('[user]', $receiptient->name, $email->body);
		$mailer = new PHPMailer();
		$mailer->From = $config_var->mailfrom;
		$mailer->FromName = $config_var->fromname;
		if ($config_var->mailer == 'smtp') {
			$mailer->useSMTP($config_var->smtpauth, $config_var->smtphost, $config_var->smtpuser, $config_var->smtppass, $config_var->smtpsecure, $config_var->smtpport);
		}
		$recipient = OSEMailHelper :: cleanLine($receiptient->email);
		$mailer->AddAddress($recipient);
		$mailer->Subject = OSEMailHelper :: cleanLine($email->subject);
		$mailer->Body = OSEMailHelper :: cleanText($email->body);
		$mailer->IsHTML(true);
		$mailer->Send();
		return true;
	}
	
	private function checkContinue() {
		$dbReady = oseFirewall :: isDBReady();
		if ($dbReady == false)
		{
			return false; 
		}
		else
		{
			$time = oseFirewall::getTime(); 
			$db = oseFirewall::getDBO();
			$query = "SELECT * FROM `#__osefirewall_logs` AS log WHERE `comp` = 'aud'";
			$db ->setQuery($query); 
			$result = $db->loadObject();
			if (empty($result))
			{
				$this->insertLogTime ($db);
				$db ->closeDBO(); 
				return true;
			}
			else
			{
				$query = "SELECT * FROM `#__osefirewall_logs` AS log WHERE `comp` = 'aud' AND DATEDIFF( ".$db->QuoteValue($time).", log.date)>=1";
				$db ->setQuery($query); 
				$result = $db->loadObject(); 
				if (!empty($result))	
				{
					$this->updateLogTime ($db, '');
					$db ->closeDBO(); 
					return true;
				}
				else
				{
					$db ->closeDBO(); 
					return false; 
				}
			}
		}
	}
	private function insertLogTime ($db) {
		$time = oseFirewall::getTime(); 
		$varValues = array(
					'date' => $time,
					'comp'=>'aud',
					'status'=>'',
				);
		$website_id = $db->addData('insert', '#__osefirewall_logs', '', '', $varValues);
	}
	private function updateLogTime ($db, $status='') {
		$time = oseFirewall::getTime(); 
		$varValues = array(
					'date' => $time,
					'status'=>$status,
				);
		$website_id = $db->addData('update', '#__osefirewall_logs', 'comp', 'aud', $varValues);
	}
	private function translateTemplate ($report, $template) {
		$config = oseFirewall::getConfigVars(); 
		$template = str_replace ("[report]", $report, $template); 
		$template = str_replace ("[website]", $config->url, $template);
		$template = str_replace ("[web_url]", $config->url."/wp-admin/admin.php?page=ose_fw_adrulesets", $template);
		return $template; 
	} 
	private function loadTemplate () {
		oseFirewall::loadFiles();
		$oseFile = new oseFile (); 
		$template = $oseFile -> read(dirname(__FILE__)."/template.html");
		return $template;  
	}
	private function getReportContent () {
		$this->isDevelopModelEnable(false); 
		$this->isAdminExistsReady(false);
		$this->isGAuthenticatorReady(false);
		$this->isWPUpToDate (false);
		$this->isGoogleScan (false);
		$this->isAdFirewallReady(false);
		$this->isSignatureUpToDate(false);
		$config_var = oseFirewall::getConfigVars();      
		if (!empty($this->warning))
		{
			$report = "<div style='font-weight: bold; color: red;'>Please note that your website is not 100% secure. Please review the following items in the <a href='".$config_var->url."/wp-admin/admin.php?page=ose_firewall'>dashboard</a> and fix them.</div>";
			$report .= '<ul>';
			foreach ($this->warning as $warning)
			{
				$report .= '<li>'.$warning.'</li>';
			}
			$report .= '</ul>';
		}
		else
		{
			$report = "<div style='font-weight: bold; color: #49FF40;'>Great! Everything looks right now.</div>";
		}
		$report .= "<br/>";
		$total = $this->getTotalBlockWebsites();
		$report .= "<div>Total website blocked since Centrora security is installed: ". $total."</div>"; 
		return $report; 
	}
	private function getTotalBlockWebsites () {
		$oseFirewallStat = new oseFirewallStatPro();
		$total = $oseFirewallStat->getTotalBlockWebsites(); 
		return $total; 
	}
	private function loadPreRequisities () {
		oseFirewall::loadLanguage(); 
		require_once(ABSPATH."wp-includes/pluggable.php");
		require_once(ABSPATH."wp-includes/functions.php");
		require_once(ABSPATH."wp-admin/includes/update.php");
	}
	public function showSafeBrowsingBar ($print = true){
		$dbReady = oseFirewall :: isDBReady();
		$action1 = ($print == true)?' <div class="warning-buttons"><a onclick="checkSafebrowsing()" class="button-primary" href="#">Check Now</a></div>':'';
		$action2 = ($print == true)?' <div class="warning-buttons"><a onclick="checkSafebrowsing()" class="button-primary" href="#">Schedule Now</a></div>':'';
		if ($dbReady == true)
		{
			$safeBrowsingStatus = $this ->getSafeBrowsingStatus ();
			if (!empty($safeBrowsingStatus))
			{
				$isSafeBrowsingStatusUpdated = $this->isSafeBrowsingStatusUpdated ($safeBrowsingStatus);
				if ($isSafeBrowsingStatusUpdated == true)
				{
					$return =  '<div class ="ready">' . oLang :: _get('SAFE_BROWSING_CHECKUP_UPDATED'). $action2."</div>";
					$return .= $this->getStatusTable ($safeBrowsingStatus) ;
				}
				else
				{
					$this->warning[] = $return = '<div class ="warning"><div class="warning-content">' . oLang :: _get('SAFE_BROWSING_CHECKUP_OUTDATED') .' </div>'.$action2.'</div>';
				}
			}
			else {
				$this->warning[] = $return = '<div class ="warning"><div class="warning-content">' . oLang :: _get('CHECK_SAFE_BROWSING') .' </div>'.$action1.'</div>';
			}
		}
		else
		{
			$return = '<div class ="warning"><div class="warning-content">' . oLang :: _get('CHECK_SAFE_BROWSING') .' </div>'.$action1.'</div>';
		}
		if ($print==true){echo $return;} 
		else { return $return; }
	}
	public function getSafeBrowsingStatus () {
		oseFirewall::callLibClass('downloader', 'oseDownloader');
		$downloader = new oseDownloader('ath', null);
		$status = $downloader->getSafeBrowsingStatus($status);
		return $status;
	}
	private function isSafeBrowsingStatusUpdated ($safeBrowsingStatus) {
		$datetime1 = new DateTime($safeBrowsingStatus->checkup_date);
		$datetime2 = new DateTime();
		$interval = $datetime1->diff($datetime2);
		return ($interval->days>=2)?false: true; 
	}
	private function getStatusTable ($status) {
		$table = '<table class="statusTable">';
		$tr1 ='';
		$tr2 ='';
		foreach ($status as $key => $value)
		{
			$tr1 .= '<th class="status'.$key.'">'.ucfirst($key).'</th>';
			$tr2 .= '<td class="statusItem">'.$value.'</td>';
		}
		$table .= '<tr>'.$tr1.'</tr>';
		$table .= '<tr>'.$tr2.'</tr>';
		$table .= '</table>';
		return $table;
	}
}