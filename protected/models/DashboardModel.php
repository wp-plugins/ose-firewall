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
	}
	public function showStatus() {
		$dbReady = $this->isDBReady();
		if ($dbReady['ready'] == false) {
			echo '<div class ="warning">' . oLang :: _get('DBNOTREADY') . ' &nbsp;&nbsp; <button id ="install-button" name ="install-button" class = "button" onClick = "installDB ();">' . oLang :: _get('INSTALLDB') . '</button></div>';
		} else {
			echo '<div class ="ready">' . oLang :: _get('READYTOGO') . '</div>';
		}
	}
	public function loadLocalScript() {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$cs->registerScriptFile($baseUrl . '/public/js/installer.js');

	}
	public function getCHeader() {
		return oLang :: _get('DASHBOARD_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('OSE_WORDPRESS_FIREWALL_SETTING_DESC');
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
				$result = $this->installGeoIPDB($step -9);
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
		$array[] = $this->transMessage(true, oLang :: _get('INSTALLATION_COMPLETED'));
		$array[] = $this->transMessage(true, oLang :: _get('CREATE_DECMALWAREVIEW_COMPLETED'));
		$i = 0;
		while ($i <= $step) {
			$return .= $array[$i];
			$i++;
		}
		return $return;
	}
	private function createTables() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'createTable.sql';
		$result = $installer->createTables($dbFile);
		return $result;
	}
	private function insertConfigData() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'dataSecConfig.sql';
		$result = $installer->insertConfigData($dbFile, 'threshold');
		return $result;
	}
	private function insertEmailData() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'dataAppEmail.sql';
		$result = $installer->insertEmailData($dbFile, 'firewall');
		return $result;
	}
	private function insertAttackType() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'dataAttacktype.sql';
		$result = $installer->insertAttackType($dbFile);
		return $result;
	}
	private function insertBasicRules() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'dataRulesBasic.sql';
		$result = $installer->insertBasicRules($dbFile);
		return $result;
	}
	private function insertVspatterns() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'dataVspatterns.sql';
		$result = $installer->insertVspatterns($dbFile);
		return $result;
	}
	private function createACLIPView() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'viewAclipmap.sql';
		$result = $installer->createACLIPView($dbFile);
		return $result;
	}
	private function createAdminEmailView() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'viewAdminEmail.sql';
		$result = $installer->createAdminEmailView($dbFile);
		return $result;
	}
	private function createAttackmapView() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'viewAttackmap.sql';
		$result = $installer->createAttackmapView($dbFile);
		return $result;
	}
	private function createAttacktypeView() {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'viewAttackTypesum.sql';
		$result = $installer->createAttacktypeView($dbFile);
		return $result;
	}
	private function createDetMalwareView () {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'viewDetMalware.sql';
		$result = $installer->createDetMalwareView($dbFile);
		return $result;
	}
	private function installGeoIPDB($step) {
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . DS . 'osegeoip{num}.sql';
		$result = $installer->installGeoIPDB($step, $dbFile);
		return $result;
	}
	public function isDBReady() {
		$return = array ();
		$return['ready'] = oseFirewall :: isDBReady();
		$return['type'] = 'base';
		return $return;
	}
}