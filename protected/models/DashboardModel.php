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
class DashboardModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
		oseFirewall::callLibClass('firewallstat', 'firewallstatPro');
	}
	public function showStatus() {
		$dbReady = $this->isDBReady();
		if ($dbReady['ready'] == false) {
			echo '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('DBNOTREADY') . ' </div> <div class = "warning-buttons"><a id ="install-button" name ="install-button" class = "button-primary" onClick = "installDB ();">' . oLang :: _get('INSTALLDB') . '</a></div></div>';
		} else {
			echo '<div class ="ready">' . oLang :: _get('READYTOGO') .' </div>';
		}
		$this->isDevelopModelEnable();
		$this->isAdminExistsReady();
		$this->isGAuthenticatorReady();
		$this->isWPUpToDate ();
		$this->isGoogleScan ();
		$this->isSignatureUpToDate(); 
	}
	public function loadLocalScript() {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScriptFile($baseUrl . '/public/js/installer.js', CClientScript::POS_END);
	}
	public function getCHeader() {
		return oLang :: _get('DASHBOARD_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('OSE_WORDPRESS_FIREWALL_UPDATE_DESC');
	}
	public function showHeader () { 
		$html = '<div class="oseseparator"> &nbsp; </div>';
		$html .= '<p></p>';
		echo $html; 
	}
	public function actionCreateTables() {
		oseFirewall :: loadInstaller();
		oseFirewall :: loadRequest();
		$step = oRequest :: getInt('step');
		$retMessage = $this->getRetMessage($step);
		switch ($step) {
			case 0 :
				$result = $this->createTables();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 1 :
				$result = $this->insertConfigData();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 2 :
				$result = $this->insertEmailData();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 3 :
				$result = $this->insertAttackType();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 4 :
				$result = $this->insertBasicRules();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 5 :
				$result = $this->insertVspatterns();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;	
			case 6 :
				$result = $this->createACLIPView();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 7 :
				$result = $this->createAdminEmailView();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 8 :
				$result = $this->createAttackmapView();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 9 :
				$result = $this->createAttacktypeView();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 10 :
			case 11 :
			case 12 :
			case 13 :
			case 14 :
			case 15 :
			case 16 :
				//$result = $this->installGeoIPDB($step -9);
				$result = $this->cleanGeoIPDB($step -9);; 
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 17 :
				$result = $this->createDetMalwareView();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;	
			default :
				$this->throwAjaxReturn(true, 'Completed', $retMessage, false);
				break;
		}
	}
	private function getRetMessage($step) {
		$return = '';
		$array = array ();
		$array[] = $this->transMessage(true, oLang :: _get('CREATE_BASETABLE_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_CONFIGCONTENT_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_EMAILCONTENT_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_ATTACKTYPE_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_BASICRULESET_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_VSPATTERNS_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('CREATE_IPVIEW_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('CREATE_ADMINEMAILVIEW_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('CREATE_ATTACKMAPVIEW_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('CREATE_ATTACKTYPESUMEVIEW_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_STAGE1_GEOIPDATA_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_STAGE2_GEOIPDATA_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_STAGE3_GEOIPDATA_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_STAGE4_GEOIPDATA_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_STAGE5_GEOIPDATA_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_STAGE6_GEOIPDATA_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSERT_STAGE7_GEOIPDATA_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('CREATE_DECMALWAREVIEW_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('INSTALLATION_COMPLETED'));
		$i = 0;
		while ($i <= $step) {
			$return .= $array[$i];
			$i++;
		}
		return $return;
	}
	private function createTables() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'createTable.sql';
		$result = $installer->createTables($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function insertConfigData() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataSecConfig.sql';
		$result = $installer->insertConfigData($dbFile, 'threshold');
		$installer->closeDBO();
		return $result;
	}
	private function insertEmailData() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataAppEmail.sql';
		$result = $installer->insertEmailData($dbFile, 'firewall');
		$installer->closeDBO();
		return $result;
	}
	private function insertAttackType() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataAttacktype.sql';
		$result = $installer->insertAttackType($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function insertBasicRules() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataRulesBasic.sql';
		$result = $installer->insertBasicRules($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function insertVspatterns() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataVspatterns.sql';
		$result = $installer->insertVspatterns($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function createACLIPView() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'viewAclipmap.sql';
		$result = $installer->createACLIPView($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function createAdminEmailView() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'viewAdminEmail.sql';
		$result = $installer->createAdminEmailView($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function createAttackmapView() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'viewAttackmap.sql';
		$result = $installer->createAttackmapView($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function createAttacktypeView() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'viewAttackTypesum.sql';
		$result = $installer->createAttacktypeView($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function createDetMalwareView () {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'viewDetMalware.sql';
		$result = $installer->createDetMalwareView($dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function installGeoIPDB($step) {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'osegeoip{num}.sql';
		$result = $installer->installGeoIPDB($step, $dbFile);
		$installer->closeDBO();
		return $result;
	}
	private function cleanGeoIPDB ($step) {
		$installer = new oseFirewallInstaller();
		$result = $installer->cleanGeoIPDB($step);
		$installer->closeDBO();
		return $result;
	}
	public function isDBReady() {
		$return = array ();
		$return['ready'] = oseFirewall :: isDBReady();
		$return['type'] = 'base';
		return $return;
	}
	public function isDevelopModelEnable(){
		$dbReady = oseFirewall :: isDBReady();
		$action = '<div class = "warning-buttons"><a class = "button-primary" href ="admin.php?page=ose_fw_scanconfig" target="_blank">heal me</a></div>';
		if ($dbReady == true)
		{
			$oseFirewallStat = new oseFirewallStat();
			$isEnable = $oseFirewallStat->getConfigurationByName($type);
			if($isEnable)
			{
				echo '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('DISDEVELOPMODE')."</div>".$action."</div>";
			}
			else {
				echo '<div class ="ready">' . oLang :: _get('DEVELOPMODE_DISABLED') .' </div>';
			}
		}
	}
	public function isAdFirewallReady(){
		$oseFirewallStat = new oseFirewallStatPro();
		$isReady = $oseFirewallStat->isAdFirewallReady();
		$action = '<div class = "warning-buttons"><a class = "button-primary" href ="http://www.centrora.com/centrora-tutorial/enabling-advance-firewall-setting/" target="_blank">heal me</a></div>';
		if(!$isReady)
		{
			echo '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('ADVANCERULESNOTREADY')." </div>". $action." </div>";
		}
		else {
			echo '<div class ="ready">' . oLang :: _get('ADVANCERULES_READY') .' </div>';
		}
	}
	public function isAdminExistsReady(){
		$oseFirewallStat = new oseFirewallStatPro();
		$userID = $oseFirewallStat->isUserAdminExist ();
		if($userID != false)
		{
			$action = '<div class = "warning-buttons"> <a href="#" class="button-primary" onClick = "showForm()">heal me</a> </div>';
			echo '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('ADMINUSER_EXISTS'). "</div>".$action." </div>";
		}
		else {
			echo '<div class ="ready">' . oLang :: _get('ADMINUSER_REMOVED') .' </div>';
		}
	}
	public function isGAuthenticatorReady(){
		$oseFirewallStat = new oseFirewallStatPro();
		$ready = $oseFirewallStat->isGAuthenticatorReady ();
		$action = '<div class = "warning-buttons"><a class="button-primary" href ="http://www.centrora.com/plugin-tutorial/google-2-step-verification/" target="_blank">heal me</a></div>';
		if($ready == true)
		{
			echo '<div class ="ready">' . oLang :: _get('GAUTHENTICATOR_READY'). "</div>";
		}
		else {
			echo '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('GAUTHENTICATOR_NOTUSED') ."</div> ". $action. ' </div>';
		}
	}
	public function isWPUpToDate () {
		$oseFirewallStat = new oseFirewallStatPro();
		$updated = $oseFirewallStat->isWPUpToDate ();
		global $wp_version;
		$wp_version = htmlspecialchars($wp_version);
		$action = '<div class = "warning-buttons"> <a href="update-core.php" class="button-primary">heal me</a> </div>';
		if($updated == true)
		{
			echo '<div class ="ready">' . oLang :: _get('WORDPRESS_UPTODATE'). $wp_version. "</div>";
		}
		else {
			echo '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('WORDPRESS_OUTDATED') . $wp_version. ".</div> ". $action. ' </div>';
		}
	}
	public function isGoogleScan () {
		$oseFirewallStat = new oseFirewallStatPro();
		$enabled = $oseFirewallStat->isGoogleScan ();
		$action = '<div class = "warning-buttons"> <a href="admin.php?page=ose_fw_seoconfig" class="button-primary">heal me</a> </div>';
		if($enabled == true)
		{
			echo '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('GOOGLE_IS_SCANNED'). ".</div> ". $action. "</div>";
		}
	}
	public function changeusername ($username) {
		$oseFirewallStat = new oseFirewallStatPro();
		$updated = $oseFirewallStat->changeusername ($username);
		return $updated;
	}
	
	public function isSignatureUpToDate () {
		$oseFirewallStat = new oseFirewallStatPro();
		$version = $oseFirewallStat->getCurrentSignatureVersion(); 
		$action = '<div class = "warning-buttons"> <a href="admin.php?page=ose_fw_adrulesets" class="button-primary">heal me</a> </div>';
		if($version>O_LATEST_SIGNATURE)
		{
			echo '<div class ="ready">' . oLang :: _get('SIGNATURE_UPTODATE'). $wp_version. "</div>";
		}
		else {
			echo '<div class ="warning"> <div class = "warning-content">' . oLang :: _get('SIGNATURE_OUTDATED') . $wp_version. ".</div> ". $action. ' </div>';
		}
	}
	public function showAuditList(){ 
	
	}
	public function showActionList() {
		
	}
}