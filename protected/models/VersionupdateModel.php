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
class VersionupdateModel extends ConfigurationModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function checkUpdateRule() {
		oseFirewall::callLibClass ( 'versionupdate', 'versionupdate' );
		$update = new oseVersionUpdate ();
		$record = $update->getLatestLog ();
		$table = $update->checkEmptyTable ();
		$currentTime = $update->getCurrentDate ();
		if ($table) {
			return true;
		} 
		else if ($currentTime === $record ['time']) {
			return false;
		} 
		else if ($currentTime > $record['time']){
			return true;
		}
		else 
			return false;
	}
	public function insertUpdateLog() {
		oseFirewall::callLibClass ( 'versionupdate', 'versionupdate' );
		$update = new oseVersionUpdate ();
		$currentTime = $update->getCurrentDate ();
		$update->addUpdateLog ( $currentTime );
		return true;
	}
	
	private function getServerVersion() {
		$loginUrl = 'http://update.protect-website.com/index.php';
		$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_URL, $loginUrl );
		curl_setopt ( $ch, CURLOPT_USERAGENT, $agent );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, 'plugin=firewall&action=getVersion' );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		$data = json_decode ( $result, true );
		return $data;
	}
	public function checkUpdateVersion() {
		oseFirewall::callLibClass ( 'versionupdate', 'versionupdate' );
		$update = new oseVersionUpdate ();
		$localVer = $update->getLatestVersion ();
		$remoteVer = $this->getServerVersion ();
		switch (version_compare ( $localVer ['version'], $remoteVer ['version'] )){
			case -1:
				return true;
			default:
				return false;	
		} 
		
	}
	private function getUpdateData() {
		oseFirewall::callLibClass ( 'versionupdate', 'versionupdate' );
		$update = new oseVersionUpdate ();
		$username = $update->getUsername();
		$password = $update->getPassword();
		$localVer = $update->getLatestVersion();
		$loginUrl = 'http://update.protect-website.com/index.php';
		$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_URL, $loginUrl );
		curl_setopt ( $ch, CURLOPT_USERAGENT, $agent );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, 'plugin=firewall&action=updateData&username='.$username[value].'&password='.$password[value].'&version='.$localVer[version]);
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		$data = oseJSON::decode($result);
		return $data;
	}
	public function startUpdate() {
		oseFirewall::callLibClass ( 'versionupdate', 'versionupdate' );
		$update = new oseVersionUpdate();
		$remoteData = $this->getUpdateData();
		if ($remoteData == null){
			return false;
		}
		else {
			foreach ($remoteData->patternlist as $pattern) {
				$vsPattern = array(
						'pattern_id' =>  $pattern->pattern_id,
						'patterns' =>	$pattern->patterns,
						'type_id' => $pattern->type_id,
						'confidence' => $pattern->confidence
				);
				$vsVersion = array(
						'version_id' => $pattern->version_id,
						'version' => $pattern->version,
						'plugin' => $pattern->plugin
				);
				$update->addVersions($vsVersion);
				$update->addPatterns($vsPattern);
			}
			return true;
		}
	}
	public function showstatus() {	
		echo '<button id ="update-button" name ="update-button" class = "button" onClick = "updateVersion();">' . oLang::_get ( 'UPDATEVERSION' ) . '</button>';
	}
	public function getCHeader() {
		return oLang::_get ( 'VERSION_UPDATE_TITLE' );
	}
	public function getCDescription() {
		return oLang::_get ( 'VERSION_UPDATE_DESC' );
	}
	public function loadLocalscript() {
		$baseUrl = Yii::app ()->baseUrl;
		$cs = Yii::app ()->getClientScript ();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScriptFile ( $baseUrl . '/public/js/versionupdate.js', CClientScript::POS_END );
		$cs->registerScriptFile ( $baseUrl . '/public/js/subscribe.js', CClientScript::POS_END );
		
	}
	
	public function actionCreateTables() {
		oseFirewall::loadRequest ();
		$step = oRequest::getInt ('step');
		$retMessage = $this->getRetMessage ( $step );
		switch ($step) {
			case 0 :
				$result = $this->checkUpdateRule ();
				if ($result == true) {
					$step ++;
					$this->throwAjaxRecursive ( $result, 'Success', $retMessage, true, $step );
				} else {
					$this->throwAjaxReturn ( false, 'Error', 'Already Updated Today!', false );
				}
				break;
			case 1 :
				$result = $this->checkUpdateVersion ();
				if ($result == true) {
					$step ++;
					$this->throwAjaxRecursive ( $result, 'Success', $retMessage, true, $step );
				} else {
					$this->throwAjaxReturn ( false, 'Error', 'Already Have Latest Version!', false );
				}
				break;
			case 2 :
				$result = $this->startUpdate ();
				if ($result == true) {
					$step ++;
					$this->throwAjaxRecursive ( $result, 'Success', $retMessage, true, $step );
				} else {
					$this->throwAjaxReturn ( false, 'Error', 'Update failed! Incorrect username/password!', false );
				}
				break;
			case 3 :
				$result = $this->insertUpdateLog ();
				$step ++;
				$this->throwAjaxRecursive ( $result, 'Success', $retMessage, true, $step );
				break;
			default :
				$this->throwAjaxReturn ( true, 'Completed', $retMessage, false );
				break;
		}
	}
	
	private function getRetMessage($step) {
		$return = '';
		$array = array ();
		$array [] = $this->transMessage ( true, oLang::_get ( 'CHECK_UPDATE_RULE' ) );
		$array [] = $this->transMessage ( true, oLang::_get ( 'CHECK_UPDATE_VERSION' ) );
		$array [] = $this->transMessage ( true, oLang::_get ( 'START_UPDATE_VERSION' ) );
		$array [] = $this->transMessage ( true, oLang::_get ( 'UPDATE_LOG' ) );
		$array [] = $this->transMessage ( true, oLang::_get ( 'UPDATE_COMPLETED' ) );
		$i = 0;
		while ( $i <= $step ) {
			$return .= $array [$i];
			$i ++;
		}
		return $return;
	}
	
}




