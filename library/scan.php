<?php
/**
* @version     1.0 +
* @package       Open Source Excellence Security Suite
* @subpackage    Open Source Excellence WordPress Firewall
* @author        Open Source Excellence {@link http://www.opensource-excellence.com}
* @author        Created on 01-Jul-2012
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
if (!function_exists('add_action')) { die("Direct Access Not Allowed"); }
class oseWPFirewall {
		private $ip= null;
		private $url= null;
		private $referer= null;
		private $target= null;
		private $logtime= null;
		private $wpsettings = array(); 
		private $admin_email = null;
		private $blog_name = null;
		
		function __construct($settings,$admin_email, $blog_name) {
			$this -> getBasicInfo();
			$this -> wpsettings = $settings; 
			$this->admin_email = $admin_email;
			$this->blog_name = $blog_name;
		}
		private function getBasicInfo() {
			$this->url= 'http://'.str_replace("?".$_SERVER['QUERY_STRING'], "", $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			if(isset($_SERVER['HTTP_REFERER'])) {
				$this->referer= $_SERVER['HTTP_REFERER'];
			} else {
				$this->referer= "N/A";
			}
			$this->ip= self :: getRealIP();
			$this->logtime = date("Y-m-d h:i:s");
			
		}
		function scan()
		{
			// Get Whitelisted Variable;
			$whitelistvars= $this -> wpsettings['osefirewall_whitelistvars'];
			$this->whitelistvars= explode(",", $whitelistvars);
			if(is_admin())
			{
				return; // Dont run in admin
			}
			if($this -> wpsettings['osefirewall_blockbl_method'] == true)
			{
				$this -> BlockblMethod();
			}
			if($this -> wpsettings['osefirewall_checkmua'] == true)
			{
				$this -> checkMUA();
			}
			if($this -> wpsettings['osefirewall_checkdfi'] == true)
			{
				$this -> checkDFI();
			}
			if($this -> wpsettings['osefirewall_checkrfi'] == true)
			{
				$this -> checkRFI();
			}
			if($this -> wpsettings['osefirewall_checkdos'] == true)
			{
				$this -> checkDoS();
			}
			if($this -> wpsettings['osefirewall_checkjsinjection'] == true)
			{
				$this -> checkJSInjection();
			}
			if($this -> wpsettings['osefirewall_checksqlinjection'] == true)
			{
				$this -> checkSQLInjection();
			}
			if($this -> wpsettings['osefirewall_query_too_long'] == true)
			{
				$this->checkQuerytooLong();
			}
			return true;
		}
		private function getRealIP() {
			$ip= false;
			if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip= $_SERVER['HTTP_CLIENT_IP'];
			}
			if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ips= explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
				if($ip) {
					array_unshift($ips, $ip);
					$ip= false;
				}
				$this->tvar = phpversion();
				for($i= 0, $total = count($ips); $i < $total; $i++) {
					if(!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])) {
						if(version_compare($this->tvar, "5.0.0", ">=")) {
							if(ip2long($ips[$i]) != false) {
								$ip= $ips[$i];
								break;
							}
						} else {
							if(ip2long($ips[$i]) != -1) {
								$ip= $ips[$i];
								break;
							}
						}
					}
				}
			}
			return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
		}
		private function checkMUA()
		{
			// Some PHP binaries don't set the $_SERVER array under all platforms
			if(!isset($_SERVER))
			{
				return;
			}
			if(!is_array($_SERVER))
			{
				return;
			}
			// Some user agents don't set a UA string at all
			if(!array_key_exists('HTTP_USER_AGENT', $_SERVER))
			{
				return;
			}
			$mua= $_SERVER['HTTP_USER_AGENT'];
			$detected= false;
			if(strstr($mua, '<?'))
			{
				$detected= true;
			}
			$patterns= array('#c0li\.m0de\.0n#', '#libwww-perl#', '#<\?(.*)\?>#', '#curl#', '#^Mozilla\/5\.0$#', '#^Mozilla$#', '#^Java#');
			$patterns[] = "/^(curl|wget|winhttp|HTTrack|clshttp|loader|email|harvest|extract|grab|miner|libwww-perl|acunetix|sqlmap|python|nikto|scan).*/i";
			
			foreach($patterns as $i => $pattern)
			{
				// libwww-perl fix for w3c
				if($i == 1)
				{
					if(preg_match($pattern, $mua) && !preg_match('#^W3C-checklink#', $mua))
					{
						$detected= true;
					}
					continue;
				}
				if(preg_match($pattern, $mua))
				{
					$detected= true;
				}
			}
			unset($patterns);
			if($detected == true)
			{
				self :: redirect(FOUNDMUA);
			}
		}
		// Basic function - Checks Direct Files Inclusion attack
		private function checkDFI()
		{
			$request= array($_GET, $_POST);
			foreach($request as $allVars)
			{
				if(empty($allVars))
				continue;
				if(self :: DFImathched($allVars))
				{
					self :: redirect(FOUNDDFI);
				}
			}
		}
		private function DFImathched($array)
		{
			$result= false;
			if(is_array($array))
			{
				foreach($array as $key => $value)
				{
					if(!in_array($key, $this->whitelistvars))
					{
						continue;
					}
					// If there's a null byte in the key, break
					if(strstr($key, "\u0000"))
					{
						$result= true;
						break;
					}
					// If there's no value, treat the key as a value
					if(empty($value))
					{
						$value= $key;
					}
					// Scan the value
					if(is_array($value))
					{
						$result= self::DFImathched($value);
					}
					else
					{
						// If there's a null byte, break
						if(strstr($value, "\u0000"))
						{
							$result= true;
							break;
						}
						// If the value starts with a /, ../ or [a-z]{1,2}:, block
						if(preg_match('#^(/|\.\.|[a-z]{1,2}:\\\)#i', $value))
						{
							// Fix 2.0.1: Check that the file exists
							$result= @ file_exists($value);
							break;
						}
						if($result)
						{
							break;
						}
					}
				}
			}
			return $result;
		}
		// Basic function - Checks Remote Files Inclusion attack
		private function checkRFI()
		{
			$request= array($_GET, $_POST);
			$regex  = array(); 
			$regex [] = '#(http|ftp){1,1}(s){0,1}://.*#i';
			$regex []  = "/^.*(%00|(?:((?:ht|f)tp(?:s?)|file|webdav)\:\/\/|~\/|\/).*\.\w{2,3}|(?:((?:ht|f)tp(?:s?)|file|webdav)%3a%2f%2f|%7e%2f%2f).*\.\w{2,3}).*/i";
			foreach($request as $allVars)
			{
				if(empty($allVars))
				continue;
				foreach ($regex as $reg)
				{
					if(self :: RFImathched($reg, $allVars))
					{
						self :: redirect(FOUNDRFI);
					}
				}
			}
		}
		private function RFImathched($regex, $array)
		{
			$result= false;
			if(is_array($array))
			{
				foreach($array as $key => $value)
				{
					if(in_array($key, $this->whitelistvars))
					{
						continue;
					}
					if(is_array($value))
					{
						$result= self :: RFImathched($regex, $value);
					}
					else
					{
						$result= preg_match($regex, $value);
					}
					if($result)
					{
						// Can we fetch the file directly?
						$fContents= @ file_get_contents($value);
						if(!empty($fContents))
						{
							$result=(strstr($fContents, '<?php') !== false);
							if($result)
							break;
						}
						else
						{
							$result= false;
						}
					}
				}
			}
			elseif(is_string($array))
			{
				$result= preg_match($regex, $array);
				if($result)
				{
					// Can we fetch the file directly?
					$fContents= @ file_get_contents($array);
					if(!empty($fContents))
					{
						$result=(strstr($fContents, '<?php') !== false);
						if($result)
						{
							break;
						}
					}
					else
					{
						$result= false;
					}
				}
			}
			return $result;
		}
		private function checkDoS()
		{
			// Check if it comes from PayPal
			if(!empty($_POST['txn_type']) || !empty($_POST['txn_id']))
			{
				return;
			}
			if(empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == '-' || !isset($_SERVER['HTTP_USER_AGENT']))
			{
				self :: redirect(FOUNDDOS);
			}
		}
		private function checkTrasversal()
		{
			$trasversal = "\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c";
			if (preg_match("/^.*(".$trasversal.").*/i", $_SERVER['url'], $matched)){
				self :: redirect(FOUNDTrasversal);
			}
		}
		private function BlockblMethod()
		{
			/* Method Blacklist*/
			if (preg_match("/^(TRACE|DELETE|TRACK)/i", $_SERVER['REQUEST_METHOD'], $matched)){
				self :: redirect(FOUNDBL_METHOD);
			}
		}
		private function checkQuerytooLong()
		{
			if (strlen($_SERVER['QUERY_STRING']) > 255){
				self :: redirect(FOUNDQUERY_LONGER_THAN_255CHAR);
			}
		}
		private function checkJSInjection()
		{
			$request= array($_GET, $_POST);
			foreach($request as $allVars)
			{
				foreach($allVars as $element => $value)
				{
					if(empty($value))
					{
						continue;
					}
					if(!is_string($value))
					{
						continue;
					}
					if(preg_match("#<[^>]*\w*\"?[^>]*>#is", $value))
					{
						self :: redirect(FOUNDJSInjection);
					}
				}
			}
			return false;
		}
		private function checkSQLInjection()
		{
			$request= array($_GET, $_POST);
			$dbprefix= 'wp_';
			$option= $_GET['option'];
			foreach($request as $allVars)
			{
				foreach($allVars as $element => $value)
				{
					$commonSQLInjWords= array('union', 'union select', 'insert', 'from', 'where', 'concat', 'into', 'cast', 'truncate', 'select', 'delete', 'having');
					if(empty($value))
					{
						continue;
					}
					if(!is_string($value))
					{
						continue;
					}
					// First scanning
					if(preg_match('#[\d\W](union select|union join|union distinct)[\d\W]#is', $value))
					{
						self :: redirect();
					}
					// Check for the database name and an SQL command in the value
					if(preg_match('#[\d\W]('.implode('|', $commonSQLInjWords).')[\d\W]#is', $value) && preg_match('#'.$dbprefix.'(\w+)#s', $value) && $option != 'com_search')
					{
						self :: redirect(FOUNDSQLInjection);
					}
				}
			}
			return false;
		}
		function redirect($attack_type)
		{
			$this->send_email($attack_type);
			/* Set alert */
			$alert  = "<br><center>";
			$alert .= "<h2>".OSE_WORDPRESS_FIREWALL."</h2>";
			$alert .= "<img src='" . plugin_dir_url( __FILE__ ) . "../assets/firewall.png' /><br>";
			$alert .= "<b><font color=\"red\">".BLOCK_MESSAGE."</font></b><br><br>";
			$alert .= "</center>";
			switch( $this -> wpsettings['osefirewall_blockpage'] ) {
				default:
				case "osefirewall_logo":
				case "":
					die( $alert );
					break;
				case "osefirewall_blank":
					die( "" );
					break;
				case "osefirewall_403error":
					header('HTTP/1.1 403 Forbidden');
					echo("<html>
						   <head>
							<title>403 Forbidden</title>
							</head>
						   <body>
							<p>".BLOCK_MESSAGE."</p>
							</body>
						 </html>");
					exit;
					break;
			}
		}
		/* Block request and send email */
		function send_email($attack_type){
			$email = isset( $this -> wpsettings['osefirewall_email'] ) ? $this -> wpsettings['osefirewall_email'] : $this->admin_email;
			/* Compose email */
			$subject = OSE_WORDPRESS_FIREWALL." - ".$blog_name;
			$body = "== Attack Details ==\n\n";
			$body .= "TYPE: $attack_type\n";
			$body .= "ACTION: Blocked\n";
			/* Info User Log */
			$body = "LOGTIME: ".$time."\n";
			$body .= "\nFROM IP: http://whois.domaintools.com/".$this->ip."\n";
			$body .= "URI: ".$this->target."\n";
			$body .= "METHOD: ".$_SERVER['REQUEST_METHOD']."\n";
			$body .= "USERAGENT: ".$_SERVER['HTTP_USER_AGENT']."\n";
			$body .= "REFERRER: ".$this->referer."\n";
			$body .= "\n";
			mail($email, $subject, $body);
		}
}		
