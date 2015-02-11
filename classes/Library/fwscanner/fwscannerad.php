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
oseFirewall::callLibClass ( 'fwscanner', 'fwscannerbs' );
oseFirewall::callLibClass ( 'fwscanner', 'Converter' );
oseFirewall::loadJSON ();
class oseFirewallScannerAdvance extends oseFirewallScannerBasic {
	protected $visits = 0;
	protected $score = 0;
	public function scanAttack() {
		$scanResult = $this->checkCountryStatus();
		if ($scanResult == true)
		{
			// Reset scanning result;
			$scanResult = null;
		}
		else
		{
			$scanResult = $this->ScanLayer1();
			if (! empty ( $scanResult )) {
				$status = $this->getBlockIP();
				$this->addACLRule ( $status, $scanResult ['impact'] );
				$this->detected = implode(",", $scanResult ['detcontent_content']);
				if (!empty($scanResult['spamtype']) && $scanResult['spamtype'] =='email')
				{	
					$this->blockIP = 1 ;
					$this->spamEmail = true;
					$content = oseJSON::encode ( array('email'=>$scanResult ['detcontent_content'] ));
				}
				else
				{
					$content = oseJSON::encode ( $scanResult ['detcontent_content'] );
				}
				$attacktypeID = $this->getAttackTypeID ( $scanResult ['rule_id'] );
				$this->addDetContent ( $attacktypeID, $content, $scanResult ['rule_id'], $scanResult ['keyname']);
				$this->controlAttack (0);
			}
			else
			{
				$scanResult = $this->ScanLayer2();
				if (! empty ( $scanResult )) {
					$scannerType = $scanResult['0']['type'];
					$status = $this->getBlockIP();
					$this->addACLRule ( $status, $this -> sumImpact($scanResult) );
					foreach($scanResult as $result){
						$this->detected .= implode(",", $result ['detcontent_content']);
						$content = oseJSON::encode ( $result ['detcontent_content'] );
						//each 'get' or 'post' request may triggers more than one kind of attack type
						//record each attack type individually
						$attacktypes = oseJSON::decode($result ['attackTypeID']);
						foreach($attacktypes as $attacktype){
							$attacktypeID = $attacktype;
							$this->addDetContent ( $attacktypeID, $content, $result ['rule_id'], $result['keyname'] );
						}
					}
					$this->controlAttack ($scannerType);
				}
			}
		}
		unset ( $scanResult );
	}
	public function getBlockIP() {
		$this->visits = $this->getVisits();
		$this->score = $this->getScore();
		if ($this->silentMode == true && $this->visits <= $this->slient_max_att)
		{
			return 0; 
		}
		else if ($this->silentMode == false && $this->score < $this->threshold) 
		{
			return 0;
		}
		else
		{
			return $this->blockIP;
		}
	}
	private function ScanLayer2() {
		$impact = 0;
		$options = $this->getScanOptions ();
		$request = array (
				'GET' => $_GET,
				'POST' => $_POST
		);
		if(!isset($request)){
			return false;
		}
		if(!isset($options)){
			return false;
		}
		$request = $this -> clearWhitelistVars($request);
		if(empty($request['GET']) && empty($request['POST'])){
			return false;
		}
		$request = $this -> convertVariables($request);
		$request_str = $this ->groupRequest($request);
		$tmpResults = array();	
		foreach($options as $option){
			if(preg_match_all("/".$option['filter']."/ims",$request_str, $matchs)){
				foreach($request as $index => $singleRequest){
					//scan each content of a sigle get or post
					foreach($singleRequest AS $key =>$value){	
						$attackContent = $value;
						$attackVar = ($index==0)?"get.".$key:"post.".$key;
						preg_match_all ( "/".$option['filter']."/ims", $attackContent, $matched );
				
						if(!empty($matched[0])){
							$tmpResult = $this -> composeResult($option['impact'], $matched[0], $option['id'], $option['attacktype'], $attackVar, 'ad');
							$tmpResults[] = $tmpResult;
							$impact += $option['impact'];
						}
					}
				} 
			}
		}
		return $tmpResults;
	}
	private function groupRequest($request){
		$request_Str = null;
		if(isset($request)){
			$get_Str = implode("\n", $request['GET']);
			$post_Str = implode("\n", $request['POST']);
			$request_Str = implode("\n", array($get_Str, $post_Str));	
		}
		return $request_Str; 
	} 
	private function convertVariables ($requestArray) {
		foreach ($requestArray as $arrayKey => $request)
		{
			foreach ($request as $key=>$value)
			{ 	
				if (is_array($value))
				{
					$requestArray[$arrayKey][$key] = $this-> convertArrayVariables ($requestArray[$arrayKey][$key], $value) ;
				}
				else
				{
					$requestArray[$arrayKey][$key]=IDS_Converter::runAll($value);
				}
			}
		}
		return $requestArray;
	}
	private function convertArrayVariables ($originalArray, $requestArray) {
		foreach ($requestArray as $key => $value)
		{
			if (is_array($value))
			{
				$originalArray[$key]= $this->convertArrayVariables ($originalArray[$key], $value);
			}
			else
			{
				$originalArray[$key]=IDS_Converter::runAll($value);
			}
		}
		return $originalArray; 
	}
	private function sumImpact($scanResult){
		$score = 0;
		foreach ($scanResult as $result){
			$score += $result['impact'];
		}
		return $score;
	}
	
	private function getScanOptions() {
		$query = "SELECT * FROM `#__osefirewall_advancerules` WHERE `action` = 1";
		$this->db->setQuery ( $query );
		$results = $this->db->loadArrayList ();
		return $results;
	}
	
	private function getAttackTypeID($attackTypeID) {
		$query = "SELECT `attacktype` FROM `#__osefirewall_basicrules` WHERE `id`  = " . ( int ) $attackTypeID;
		$this->db->setQuery ( $query );
		$result = ( object ) ($this->db->loadResult ());
		$attacktype = oseJSON::decode ( $result->attacktype );
		return $attacktype [0];
	}

	private function addDetContent($attacktypeID, $detcontent_content, $rule_id, $keyname) {
		$exists = $this->isDetContentExists ( $attacktypeID, $rule_id );
		if (! empty ( $exists )) {
			return;
		}
		$detattacktype_id = $this->insertDetAttacktype ( $attacktypeID );
		$var_id = $this->insertVarKey($keyname);
		if (! empty ( $detattacktype_id ) && !empty($var_id)) {
			$this->insertDetected ( $detattacktype_id );
			if (! empty ( $detcontent_content ) && ! empty ( $rule_id )) {
				$this->insertDetContentDetail ( $detattacktype_id, $detcontent_content, $rule_id, $var_id );
			}
		}
		return $detattacktype_id;
	}
	protected function controlAttack($scannerType) 
	{
		$visits = $this->getVisits();
		$score = $this->getScore();
		$notified = $this->getNotified();
		if (($this->silentMode == false && $score < $this->threshold) || ($this->silentMode == true && $visits <= $this->slient_max_att) && $this->spamEmail == false)
		{
			$this -> updateVisits();
			$url = $this -> filterAttack($scannerType);
			$this -> sendEmail('filtered', $notified);
		}
		else
		{	
			switch ($this->blockIP)
			{
				case 1:
					$this -> updateStatus($this->blockIP);
					$this -> sendEmail('blacklisted', $notified);
					$this -> logDomain();
					$this -> showBanPage();
				break;
				case 0:
					$this -> sendEmail('403blocked', $notified);
					$this -> logDomain();
					$this -> show403Page();
				break;
			}
		}
	}
}