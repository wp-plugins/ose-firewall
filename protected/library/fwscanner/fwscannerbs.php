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
oseFirewall::callLibClass ( 'fwscanner', 'fwscanner' );
oseFirewall::loadJSON ();
class oseFirewallScannerBasic extends oseFirewallScanner {
	public function scanAttack() {
		$scanResult = $this->checkCountryStatus();
		if ($scanResult == true)
		{
			return; 
		}
		else
		{ 
			$scanResult = $this->ScanLayer1();
		}
		if (! empty ( $scanResult )) {
			$status = $this->getBlockIP();
			$this->addACLRule ( $status, $scanResult ['impact'] );
			$content = oseJSON::encode ( $scanResult ['detcontent_content'] );
			$attacktypeID = $this->getAttackTypeID ( $scanResult ['rule_id'] );
			$this->addDetContent ( $attacktypeID, $content, $scanResult ['rule_id'], $scanResult ['keyname']);
			$this->controlAttack (0);
		}
		unset ( $scanResult );
	}
	protected function ScanLayer1() {
		$options = $this->getScanOptions ();
		if (isset ( $options ['sfspam'] ) && $options ['sfspam'] == true && function_exists('curl_exec')==true) {
			$scanResult = $this->CheckIsSpambot ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['blockbl_method'] ) && $options ['blockbl_method'] == true) {
			$scanResult = $this->BlockblMethod ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['check_mua'] ) && $options ['check_mua'] == true) {
			$scanResult = $this->checkMUA ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['checkdfi'] ) && $options ['checkdfi'] == true) {
			$scanResult = $this->checkDFI ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['checkrfi'] ) && $options ['checkrfi'] == true) {
			$scanResult = $this->checkRFI ();
			if (! empty ( $scanResult )) {
				exit ();
				return $scanResult;
			}
		}
		if (isset ( $options ['checkdos'] ) && $options ['checkdos'] == true) {
			$scanResult = $this->checkDoS ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['checkjsinjection'] ) && $options ['checkjsinjection'] == true) {
			$scanResult = $this->checkJSInjection ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['checksqlinjection'] ) && $options ['checksqlinjection'] == true) {
			$scanResult = $this->checkSQLInjection ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['checktrasversal'] ) && $options ['checktrasversal'] == true) {
			$scanResult = $this->checktrasversal ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['block_query_longer_than_255char'] ) && $options ['block_query_longer_than_255char'] == true) {
			$scanResult = $this->checkQuerytooLong ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		if (isset ( $options ['file_upload_validation'] ) && $options ['file_upload_validation'] == true) {
			$scanResult = $this->scanUploadFiles ();
			if (! empty ( $scanResult )) {
				return $scanResult;
			}
		}
		return false;
	}
	
	private function getScanOptions() {
		$query = "SELECT * FROM `#__osefirewall_basicrules` WHERE `action` = 1";
		$this->db->setQuery ( $query );
		$results = $this->db->loadResultArray ();
		$return = array ();
		foreach ( $results as $result ) {
			$return [strtolower ( $result ['rule'] )] = true;
		}
		return $return;
	}
	private function getAttackTypeID($rule_id) {
		$query = "SELECT `attacktype` FROM `#__osefirewall_basicrules` WHERE `id`  = " . ( int ) $rule_id;
		$this->db->setQuery ( $query );
		$result = ( object ) ($this->db->loadResult ());
		$attacktype = oseJSON::decode ( $result->attacktype );
		return $attacktype [0];
	}
	private function addDetContent($attacktypeID, $detcontent_content = null, $rule_id = null, $keyname = null) {
		$exists = $this->isDetContentExists ( $attacktypeID, $rule_id );
		if (! empty ( $exists )) {
			return;
		}
		$detattacktype_id = $this->insertDetAttacktype ( $attacktypeID );
		$var_id = $this->insertVarKey($keyname);
		if (! empty ( $detattacktype_id )) {
			$this->insertDetected ( $detattacktype_id );
			if (! empty ( $detcontent_content ) && ! empty ( $rule_id )) {
				$this->insertDetContentDetail ( $detattacktype_id, $detcontent_content, $rule_id, $var_id );
			}
		}
		return $detattacktype_id;
	}
	private function checkMUA() {
		// Some PHP binaries don't set the $_SERVER array under all platforms
		if (! isset ( $_SERVER )) {
			return;
		}
		if (! is_array ( $_SERVER )) {
			return;
		}
		// Some user agents don't set a UA string at all
		if (! array_key_exists ( 'HTTP_USER_AGENT', $_SERVER )) {
			return;
		}
		$mua = $_SERVER ['HTTP_USER_AGENT'];
		$detected = false;
		if (strstr ( $mua, '<?' )) {
			$detected = true;
		}
		$patterns = array (
				'#c0li\.m0de\.0n#',
				'#libwww-perl#',
				'#<\?(.*)\?>#',
				'#curl#',
				'#^Mozilla\/5\.0$#',
				'#^Mozilla$#',
				'#^Java#' 
		);
		$patterns [] = "/^(curl|wget|winhttp|HTTrack|clshttp|loader|email|harvest|extract|grab|miner|libwww-perl|acunetix|sqlmap|python|nikto|scan).*/i";
		foreach ( $patterns as $i => $pattern ) {
			$matches = array ();
			// libwww-perl fix for w3c
			if ($i == 1) {
				if (preg_match ( $pattern, $mua, $matches ) && ! preg_match ( '#^W3C-checklink#', $mua, $matches )) {
					$detected = true;
				}
				continue;
			} else if (preg_match ( $pattern, $mua, $matches )) {
				$detected = true;
			}
		}
		$matches = array_unique ( $matches );
		unset ( $patterns );
		$return = array ();
		if ($detected == true) {
			$return = $this->composeResult(100, $matches, 3, oseJSON::encode(array(1)), 'server.HTTP_USER_AGENT', 'bs') ;
		}
		return $return;
	}
	// Basic function - Checks Direct Files Inclusion attack
	private function checkDFI() {
		$request = array (
				'GET' => $_GET,
				'POST' => $_POST
		);
		$return = array ();
		$whitelistVars = $this->getWhitelistVars ();
		foreach ( $request as $allVars ) {
			if (empty ( $allVars ))
				continue;
			$matches = $this->DFImathched ( $allVars, $whitelistVars );
			if (! empty ( $matches )) {
				$return = $this->composeResult(100, $matches['value'], 5, oseJSON::encode(array(6)), $matches['key'], 'bs') ;
				break;
			}
		}
		return $return;
	}
	private function DFImathched($array, $whitelistVars) {
		$result = false;
		if (is_array ( $array )) {
			foreach ( $array as $key => $value ) {
				if (in_array ( $key, $whitelistVars )) {
					continue;
				}
				// If there's a null byte in the key, break
				if (strstr ( $key, "\u0000" )) {
					$result = true;
					break;
				}
				// If there's no value, treat the key as a value
				if (empty ( $value )) {
					$value = $key;
				}
				// Scan the value
				if (is_array ( $value )) {
					$result = $this->DFImathched ( $value, $whitelistVars );
				} else {
					// If there's a null byte, break
					if (strstr ( $value, "\u0000" )) {
						$result = true;
						return 'null byte';
						break;
					}
					// If the value starts with a /, ../ or [a-z]{1,2}:, block
					if (preg_match ( '#^(\.\.)\/(\/|[a-z])+#i', $value, $matches )) {
						// Fix 2.0.1: Check that the file exists
						$result = @ file_exists ( $value );
						return array('key'=>$key, 'value'=>$value);
						break;
					}
					if ($result) {
						break;
					}
				}
			}
		}
		return false;
	}
	// Basic function - Checks Remote Files Inclusion attack
	private function checkRFI() {
		$request = array (
				'GET' => $_GET,
				'POST' => $_POST
		);
		$whitelistVars = $this->getWhitelistVars ();
		$regex = array ();
		$regex [] = '#(http|ftp){1,1}(s){0,1}://.*#i';
		$regex [] = "/^.*(%00|(?:((?:ht|f)tp(?:s?)|file|webdav)\:\/\/|~\/|\/).*\.\w{2,3}|(?:((?:ht|f)tp(?:s?)|file|webdav)%3a%2f%2f|%7e%2f%2f).*\.\w{2,3}).*/i";
		foreach ( $request as $allVars ) {
			if (empty ( $allVars ))
				continue;
			foreach ( $regex as $reg ) {
				$matches = $this->RFImathched ( $reg, $allVars, $whitelistVars );
				if (! empty ( $matches )) {
					$return = $this->composeResult(100, $matches['value'], 5, oseJSON::encode(array(5)), $matches['key'], 'bs') ;
                    break;
				}
			}
		}
	}
	private function RFImathched($regex, $array, $whitelistVars) {
		$result = false;
		if (is_array ( $array )) {
			foreach ( $array as $key => $value ) {
				if (in_array ( $key, $whitelistVars )) {
					continue;
				}
				if (is_array ( $value )) {
					$result = $this->RFImathched ( $regex, $value, $whitelistVars);
				} else {
					$result = preg_match ( $regex, $value );
				}
				if ($result) {
					// Can we fetch the file directly?
					$fContents = @ file_get_contents ( $value );
					if (! empty ( $fContents )) {
						$result = (strstr ( $fContents, '<?php' ) !== false);
						if ($result) {
							$result = array('key'=>$key, 'value'=>$value);
							break;
						}
					} else {
						$result = null;
					}
				}
			}
		} elseif (is_string ( $array )) {
			$matches = array ();
			$result = preg_match ( $regex, $array, $matches );
			if ($result) {
				// Can we fetch the file directly?
				$fContents = @ file_get_contents ( $array );
				if (! empty ( $fContents )) {
					$result = (strstr ( $fContents, '<?php' ) !== false);
					if ($result) {
						$result = array('key'=>$key, 'value'=>$matches);
					}
				} else {
					$result = null;
				}
			}
		}
		return $result;
	}
	private function checkDoS() {
        oseFirewall::callLibClass('ipmanager','ipmanager');
		$ipManager = new oseFirewallIpManager($this->db);
        $ip32 = strval($ipManager->getIPLong(true));
		$visits = $this->getVisits();
		$query ="SELECT * FROM `#__osefirewall_iptable_tmp` WHERE `ip32_start` =  ".$this->db->quoteValue($ip32)." LIMIT 1";
		$this->db->setQuery($query);
        $this->db->query();
		$results = $this->db->loadObject();
		if (!$results){
			$last_session_request = strval(time());
			$total_session_request = strval(1);
			$query = "INSERT INTO `#__osefirewall_iptable_tmp` (
					`id`,
					`ip32_start`,
					`last_session_request`,
					`total_session_request`
					)
					VALUES(
					NULL,".$this->db->quoteValue($ip32).",".$this->db->quoteValue($last_session_request).",".$this->db->quoteValue($total_session_request).");";
			$this->db->setQuery($query);
            $this->db->query();
            return;
		}
		else 
		{
		    $lastSessionRequest = (int)($results->last_session_request);
        	$totalSessionRequest = (int)($results->total_session_request);
			if ((time() - $lastSessionRequest) < 10){
				if($totalSessionRequest > 80){
					$return = $this->composeResult(100, "dDos Attack", 4, oseJSON::encode(array(9)), 'server.SESSION', 'bs') ;
				}
				else{
	                $lastSessionRequest = time();
	                $totalSessionRequest = $totalSessionRequest +1;
					$query =" UPDATE `#__osefirewall_iptable_tmp` SET `last_session_request` = " .$this->db->quoteValue((string)$lastSessionRequest).
					", `total_session_request` = " .$this->db->quoteValue((string)$totalSessionRequest).
					" WHERE `ip32_start` = ".$this->db->quoteValue($ip32);
					$this->db->setQuery($query);
	                $this->db->query();
	                return;
				}
			}
			else{
				if ($totalSessionRequest > $visits)
				{
					// real flooding, return true;
					$query =" DELETE FROM `#__osefirewall_iptable_tmp` WHERE `ip32_start` = ". $this->db->quoteValue($ip32);
					$this->db->setQuery($query);
	                $this->db->query();
					$return = $this->composeResult(100, "dDos Attack", 4, oseJSON::encode(array(9)), 'server.SESSION', 'bs') ;
				}
				else
				{
	                $lastSessionRequest = time();
					$query =" UPDATE `#__osefirewall_iptable_tmp` SET `last_session_request` = " .$this->db->quoteValue((string)$lastSessionRequest).
					", `total_session_request` = 1" .
					" WHERE `ip32_start` = ". $this->db->quoteValue($ip32);
					$this->db->setQuery($query);
	                $this->db->query();
	                return;
				}
			}
		}	
	}
	private function checkTrasversal() {
		$return = array ();
		$trasversal = "\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c";
		if (preg_match ( "/^.*(" . $trasversal . ").*/i", $_SERVER ['REQUEST_URI'], $matched )) {
			$return = $this->composeResult(100, $matched [0], 9, oseJSON::encode(array(8)), 'server.REQUEST_URI', 'bs') ;
		}
		return $return;
	}
	private function BlockblMethod() {
		$return = array ();
		/* Method Blacklist */
		if (preg_match ( "/^(TRACE|DELETE|TRACK)/i", $_SERVER ['REQUEST_METHOD'], $matched )) {
			$return = $this->composeResult(100, $matched [0], 2, oseJSON::encode(array(1)), 'server.REQUEST_METHOD', 'bs') ;
		}
		return $return;
	}
	private function checkQuerytooLong() {
		$return = array ();
		if (strlen ( $_SERVER ['QUERY_STRING'] ) > 255) {
			$return = $this->composeResult(100, $_SERVER ['QUERY_STRING'], 10, oseJSON::encode(array(1)), 'server.QUERY_STRING', 'bs') ;
		}
		return $return;
	}
	private function checkJSInjection() {
		$request = array (
				'GET' => $_GET,
				'POST' => $_POST
		);
		$return = array ();
		$matches = array ();
		foreach ( $request as $allVars ) {
			foreach ( $allVars as $element => $value ) {
				if (empty ( $value )) {
					continue;
				}
				if (! is_string ( $value )) {
					continue;
				}
				if (preg_match ( "/((\%3C)|<)((\%2F)|\/)*(javascript|script)+[a-z0-9\%]+((\%3E)|>)/ix", $value, $matches )) 				// if (preg_match('/(?:=\s*[$\w]\s*[\(\[])|(?:\(\s*(?:this|top|window|self|parent|_?content)\s*\))|(?:src\s*=s*(?:\w+:|\/\/))|(?:\w\[("\w+"|\w+\|\|))|(?:[\d\W]\|\|[\d\W]|\W=\w+,)|(?:\/\s*\+\s*[a-z"])|(?:=\s*\$[^([]*\()|(?:=\s*\(\s*")/ms', strtolower($value)))
				{
					$return = $this->composeResult(100, $matches, 7, oseJSON::encode(array(2)), $element, 'bs') ;
					break;
				}
				if (preg_match ( "/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i", $value, $matches )) 				// if (preg_match('/(?:=\s*[$\w]\s*[\(\[])|(?:\(\s*(?:this|top|window|self|parent|_?content)\s*\))|(?:src\s*=s*(?:\w+:|\/\/))|(?:\w\[("\w+"|\w+\|\|))|(?:[\d\W]\|\|[\d\W]|\W=\w+,)|(?:\/\s*\+\s*[a-z"])|(?:=\s*\$[^([]*\()|(?:=\s*\(\s*")/ms', strtolower($value)))
				{
					$return = $this->composeResult(100, $matches, 7, oseJSON::encode(array(2)), $element, 'bs') ;
					break;
				}
				if (preg_match ( "/((\%3C)|<)(javascript|script)+[^\n]+((\%3E)|>)/i", $value, $matches )) 				// if (preg_match('/(?:=\s*[$\w]\s*[\(\[])|(?:\(\s*(?:this|top|window|self|parent|_?content)\s*\))|(?:src\s*=s*(?:\w+:|\/\/))|(?:\w\[("\w+"|\w+\|\|))|(?:[\d\W]\|\|[\d\W]|\W=\w+,)|(?:\/\s*\+\s*[a-z"])|(?:=\s*\$[^([]*\()|(?:=\s*\(\s*")/ms', strtolower($value)))
				{
					$return = $this->composeResult(100, $matches, 7, oseJSON::encode(array(2)), $element, 'bs') ;
					break;
				}
				
			}
		}
		return $return;
	}
	private function checkSQLInjection() {
		$request = array (
				'GET' => $_GET,
				'POST' => $_POST
		);
		$dbprefix = $this->db->getPrefix ();
		$return = array ();
		$matches = array ();
		foreach ( $request as $allVars ) {
			foreach ( $allVars as $element => $value ) {
				$commonSQLInjWords = array (
						'union',
						'union select',
						'insert',
						'from',
						'where',
						'concat',
						'into',
						'cast',
						'truncate',
						'select',
						'delete',
						'having' 
				);
				if (empty ( $value )) {
					continue;
				}
				if (! is_string ( $value )) {
					continue;
				}
				if (preg_match ( '/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i', $value, $matches )) {
					$return = $this->composeResult(100, $matches, 8, oseJSON::encode(array(4)), $element, 'bs') ;
					break;
				}
				if (preg_match ( '/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/ix', $value, $matches )) {
					$return = $this->composeResult(100, $matches, 8, oseJSON::encode(array(4)), $element, 'bs') ;
					break;
				}
				if (preg_match ( '/((\%27)|(\'))union/ix', $value, $matches )) {
					$return = $this->composeResult(100, $matches, 8, oseJSON::encode(array(4)), $element, 'bs') ;
					break;
				}
				if (preg_match ( '/exec(\s|\+)+(s|x)p\w+/ix', $value, $matches )) {
					$return = $this->composeResult(100, $matches, 8, oseJSON::encode(array(4)), $element, 'bs') ;
					break;
				}
			}
		}
		return $return;
	}
	protected function controlAttack($scannerType) 
	{
		$visits = $this->getVisits();
		$blockMode = $this->getblockIP();
		$score = $this->getScore();
		$notified = $this->getNotified();
		if ($score < $this->threshold)
		{
			return;
		}
		// Ensure everything is cleaned before moving on;
		switch ($blockMode)
		{
			case 1:
				$this -> sendEmail('blacklisted', $notified);
				$this -> showBanPage();
			break;
			case 0:
				$this -> sendEmail('403blocked', $notified);
				$this -> show403Page();
			break;
		}
	}
}