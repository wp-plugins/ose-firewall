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
if (! defined ( 'OSE_FRAMEWORK' )) {
	die ( 'Direct Access Not Allowed' );
}
oseFirewall::callLibClass ( 'fwscanner', 'fwscanner' );
oseFirewall::loadJSON ();
class oseFirewallScannerBasic extends oseFirewallScanner {
	public function scanAttack() {
		$scanResult = $this->ScanLayer1 ();
		if (! empty ( $scanResult )) {
			$status = $this->getBlockIP();
			$this->addACLRule ( $status, $scanResult ['impact'] );
			$content = oseJSON::encode ( $scanResult ['detcontent_content'] );
			$attacktypeID = $this->getAttackTypeID ( $scanResult ['rule_id'] );
			$this->addDetContent ( $attacktypeID, $content, $scanResult ['rule_id'] );
			$this->controlAttack ();
		}
		unset ( $scanResult );
	}
	private function ScanLayer1() {
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
		if (isset ( $options ['checkjsinjection'] ) && $options ['osefirewall_checkjsinjection'] == true) {
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
	private function addDetContent($attacktypeID, $detcontent_content = null, $rule_id = null) {
		$exists = $this->isDetContentExists ( $attacktypeID, $rule_id );
		if (! empty ( $exists )) {
			return;
		}
		$detattacktype_id = $this->insertDetAttacktype ( $attacktypeID );
		if (! empty ( $detattacktype_id )) {
			$this->insertDetected ( $detattacktype_id );
			if (! empty ( $detcontent_content ) && ! empty ( $rule_id )) {
				$this->insertDetContentDetail ( $detattacktype_id, $detcontent_content, $rule_id, null );
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
			$return ['impact'] = 100;
			$return ['detcontent_content'] = $matches;
			$return ['rule_id'] = 3;
		}
		return $return;
	}
	// Basic function - Checks Direct Files Inclusion attack
	private function checkDFI() {
		$request = array (
				$_GET,
				$_POST 
		);
		$return = array ();
		$whitelistVars = $this->getWhitelistVars ();
		foreach ( $request as $allVars ) {
			if (empty ( $allVars ))
				continue;
			$matches = $this->DFImathched ( $allVars, $whitelistVars );
			if (! empty ( $matches )) {
				$return ['impact'] = 100;
				$return ['detcontent_content'] = $matches;
				$return ['rule_id'] = 5;
				break;
			}
		}
		return $return;
	}
	private function getWhitelistVars() {
		$query = "SELECT `keyname` FROM `#__osefirewall_vars` WHERE `status`  = 0";
		$this->db->setQuery ( $query );
		$results = $this->db->loadArrayList ( 'keyname' );
		return $results;
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
					if (preg_match ( '#^(/|\.\.|[a-z]{1,2}:\\\)#i', $value )) {
						// Fix 2.0.1: Check that the file exists
						$result = @ file_exists ( $value );
						return $value;
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
				$_GET,
				$_POST 
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
					$return ['impact'] = 100;
					$return ['detcontent_content'] = $matches;
					$return ['rule_id'] = 5;
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
							$result = $value;
							break;
						}
					} else {
						$result = false;
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
						$result = $matches;
					}
				} else {
					$result = false;
				}
			}
		}
		return $result;
	}
	private function checkDoS() {
        $db = oseFirewall::getDBO();
        oseFirewall::callLibClass('ipmanager','ipmanager');
		$ipManager = new oseFirewallIpManager($db);
        $ip32 = strval($ipManager->getIPLong(true));
		$visits = $this->getVisits();
		$query ="SELECT * FROM `#__osefirewall_iptable_tmp` WHERE `ip32_start` =  ".$db->quoteValue($ip32)." LIMIT 1";
		$db->setQuery($query);
        $db->query();
		$results = $db->loadObject();
        $lastSessionRequest = (int)($results->last_session_request);
        $totalSessionRequest = (int)($results->total_session_request);
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
					NULL,".$db->quoteValue($ip32).",".$db->quoteValue($last_session_request).",".$db->quoteValue($total_session_request).");";
			$db->setQuery($query);
            $db->query();
            return;
		}
		if ((time() - $lastSessionRequest) < 10){
			if($totalSessionRequest > 80){
				$return['impact'] =100;
				$return['detcontent_content'] = "dDos Attack";
				$return['rule_id'] =4;
			}
			else{
                $lastSessionRequest = time();
                $totalSessionRequest = $totalSessionRequest +1;
				$query =" UPDATE `#__osefirewall_iptable_tmp` SET `last_session_request` = " .$db->quoteValue((string)$lastSessionRequest).
				", `total_session_request` = " .$db->quoteValue((string)$totalSessionRequest).
				" WHERE `ip32_start` = ".$db->quoteValue($ip32);
				$db->setQuery($query);
                $db->query();
                return;
			}
		}
		else{
			if ($totalSessionRequest > $visits)
			{
				// real flooding, return true;
				$query =" DELETE FROM `#__osefirewall_iptable_tmp` WHERE `ip32_start` = ". $db->quoteValue($ip32);
				$db->setQuery($query);
                $db->query();
				$return['impact'] =100;
				$return['detcontent_content'] = "dDos Attack";
				$return['rule_id'] =4;
			}
			else
			{
                $lastSessionRequest = time();
				$query =" UPDATE `#__osefirewall_iptable_tmp` SET `last_session_request` = " .$db->quoteValue((string)$lastSessionRequest).
				", `total_session_request` = 1" .
				" WHERE `ip32_start` = ". $db->quoteValue($ip32);
				$db->setQuery($query);
                $db->query();
                return;
			}
		}
	}
	private function checkTrasversal() {
		$return = array ();
		$trasversal = "\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c";
		if (preg_match ( "/^.*(" . $trasversal . ").*/i", $_SERVER ['url'], $matched )) {
			$return ['impact'] = 100;
			$return ['detcontent_content'] = $matched [0];
			$return ['rule_id'] = 9;
		}
		return $return;
	}
	private function BlockblMethod() {
		$return = array ();
		/* Method Blacklist */
		if (preg_match ( "/^(TRACE|DELETE|TRACK)/i", $_SERVER ['REQUEST_METHOD'], $matched )) {
			$return ['impact'] = 100;
			$return ['detcontent_content'] = $matched [0];
			$return ['rule_id'] = 2;
		}
		return $return;
	}
	private function checkQuerytooLong() {
		$return = array ();
		if (strlen ( $_SERVER ['QUERY_STRING'] ) > 255) {
			$return ['impact'] = 100;
			$return ['detcontent_content'] = $_SERVER ['QUERY_STRING'];
			$return ['rule_id'] = 10;
		}
		return $return;
	}
	private function checkJSInjection() {
		$request = array (
				$_GET,
				$_POST 
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
				if (preg_match ( "/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/ix", $value, $matches )) 				// if (preg_match('/(?:=\s*[$\w]\s*[\(\[])|(?:\(\s*(?:this|top|window|self|parent|_?content)\s*\))|(?:src\s*=s*(?:\w+:|\/\/))|(?:\w\[("\w+"|\w+\|\|))|(?:[\d\W]\|\|[\d\W]|\W=\w+,)|(?:\/\s*\+\s*[a-z"])|(?:=\s*\$[^([]*\()|(?:=\s*\(\s*")/ms', strtolower($value)))
				{
					$return ['impact'] = 100;
					$return ['detcontent_content'] = $matches;
					$return ['rule_id'] = 7;
					break;
				}
				if (preg_match ( "/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/I", $value, $matches )) 				// if (preg_match('/(?:=\s*[$\w]\s*[\(\[])|(?:\(\s*(?:this|top|window|self|parent|_?content)\s*\))|(?:src\s*=s*(?:\w+:|\/\/))|(?:\w\[("\w+"|\w+\|\|))|(?:[\d\W]\|\|[\d\W]|\W=\w+,)|(?:\/\s*\+\s*[a-z"])|(?:=\s*\$[^([]*\()|(?:=\s*\(\s*")/ms', strtolower($value)))
				{
					$return ['impact'] = 100;
					$return ['detcontent_content'] = $matches;
					$return ['rule_id'] = 7;
					break;
				}
				if (preg_match ( "/((\%3C)|<)[^\n]+((\%3E)|>)/I", $value, $matches )) 				// if (preg_match('/(?:=\s*[$\w]\s*[\(\[])|(?:\(\s*(?:this|top|window|self|parent|_?content)\s*\))|(?:src\s*=s*(?:\w+:|\/\/))|(?:\w\[("\w+"|\w+\|\|))|(?:[\d\W]\|\|[\d\W]|\W=\w+,)|(?:\/\s*\+\s*[a-z"])|(?:=\s*\$[^([]*\()|(?:=\s*\(\s*")/ms', strtolower($value)))
				{
					$return ['impact'] = 100;
					$return ['detcontent_content'] = $matches;
					$return ['rule_id'] = 7;
					break;
				}
				
			}
		}
		return $return;
	}
	private function checkSQLInjection() {
		$request = array (
				$_GET,
				$_POST 
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
					$return ['impact'] = 100;
					$return ['detcontent_content'] = $matches;
					$return ['rule_id'] = 8;
					break;
				}
				if (preg_match ( '/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/ix', $value, $matches )) {
					$return ['impact'] = 100;
					$return ['detcontent_content'] = $matches;
					$return ['rule_id'] = 8;
					break;
				}
				if (preg_match ( '/((\%27)|(\'))union/ix', $value, $matches )) {
					$return ['impact'] = 100;
					$return ['detcontent_content'] = $matches;
					$return ['rule_id'] = 8;
					break;
				}
				if (preg_match ( '/exec(\s|\+)+(s|x)p\w+/ix', $value, $matches )) {
					$return ['impact'] = 100;
					$return ['detcontent_content'] = $matches;
					$return ['rule_id'] = 8;
					break;
				}
			}
		}
		return $return;
	}
}