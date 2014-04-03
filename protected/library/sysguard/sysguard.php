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
if (!defined('_OSEEXEC') && !defined('OSE_ADMINPATH')) {
	die('Direct Access Not Allowed');
}
class oseSysguard {
	function __construct() {
		$oseCPU = new oseCPU();
		$oseCPU->callLibClass('files', 'oseFile');
	}
	private function checkValue($key, $value) {
		if (empty ($value)) {
			oseCPU :: ajaxResponse('ERROR', 'The value for ' . $key . ' cannot be empty', false);
		}
	}
	public function customizePHPsetting($frontend = false) {
		$return = array ();
		$phpRuntime = $this->getPHPEnv();
		if ($frontend == true) {
			$directoryName = 'protected directory';
			$autoPrependFile = '"' . JPATH_ADMINISTRATOR . ODS . 'scan.php' . '"';
			$allow_url_fopen = 'off';
		} else {
			$directoryName = 'administrator directory';
			if ($phpRuntime == 'mod') {
				$autoPrependFile = 'none';
			} else {
				$autoPrependFile = '';
			}
			$allow_url_fopen = 'on';
		}
		if ($phpRuntime == 'mod') {
			$return['htaccess'] = "#File: .htaccess in your {$directoryName}<br/>" 
								  ."#Parameters added by OSE Security™<br/>" 
								  ."php_value auto_prepend_file {$autoPrependFile} <br/>" 
								  ."php_flag register_globals off <br/>" 
								  ."php_flag safe_mode off <br/>" 
								  ."php_flag allow_url_fopen " . $allow_url_fopen . " <br/>" 
								  ."php_flag display_errors off <br/>"
								  ."php_value session.save_path '/tmp' <br/>"  
								  ."php_value disable_functions \"exec,passthru,shell_exec,system,proc_open,curl_multi_exec,show_source,eval\" <br/>";
		} else {
			$return['phpini'] = ";File: php.ini in your {$directoryName}<br/>" 
								.";Parameters added by OSE Security™ <br/>" 
								."auto_prepend_file= {$autoPrependFile} <br/>" 
								."register_globals=off <br/>" 
								."safe_mode=off <br/>" 
								."allow_url_fopen=" . $allow_url_fopen . " <br/>" 
								."display_errors=off <br/>"
								."session.save_path='/tmp' <br/>"  
								."disable_functions=\"exec,passthru,shell_exec,system,proc_open,curl_multi_exec,show_source\" <br/>";
		}
		return $return;
	}
	private function getPHPEnv() {
		ob_start();
		phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
		$phpinfo = ob_get_contents();
		ob_end_clean();
		preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
		$output = preg_replace('#<table#', '<table class="adminlist" align="center"', $output[1][0]);
		$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
		$output = preg_replace('#border="0" cellpadding="3" width="600"#', 'border="0" cellspacing="1" cellpadding="4" width="95%"', $output);
		$output = preg_replace('#<hr />#', '', $output);
		$output = str_replace('<div class="center">', '', $output);
		$output = str_replace('</div>', '', $output);
		preg_match('#<tr><td\s+class="e">Server\s+API\s+</td><td\s+class="v">.*</td></tr>#', $output, $match);
		preg_match("/(?-i:CGI|FastCGI)/ms", $match[0], $match2);
		if (!empty ($match2)) {
			return "cgi";
		} else {
			return "mod";
		}
	}
	public function getBasicInfo() {
		$return['frontPath'] = realpath($_SERVER['DOCUMENT_ROOT'] . ODS) . ODS;
		$return['backPath'] = OSEFIREWALL_ADMIN_PATH . ODS;
		//$htpassfile =  realpath(dirname($_SERVER['DOCUMENT_ROOT'].ODS)).ODS.'osehtpasswd'.ODS.'osehtpasswd';
		$htpassfile = OSEFIREWALL_ADMIN_PATH . ODS . '.htpasswd';
		if (file_exists($htpassfile)) {
			$content = oseFile :: read($htpassfile);
			$content = explode(":", $content);
			$return['authUser'] = $content[0];
			$return['authPass'] = "";
		} else {
			$return['authUser'] = "";
			$return['authPass'] = "";
		}
		return $return;
	}
	private function getEncryptPass($authUser, $authPass) {
		$encryptedPassword = crypt($authPass, base64_encode($authPass));
		return $authUser . ":" . $encryptedPassword;
	}
	private function createEncryptPass($authUser, $authPass, $backPath) {
		$htpassfile = $backPath . ODS . '.htpasswd';
		$content = $this->getEncryptPass($authUser, $authPass);
		if (!is_writable(dirname($htpassfile))) {
			oseCPU :: ajaxResponse('ERROR', 'htpassword cannot be written to the folder: ' . dirname($htpassfile) . ", please see this <a href='http://wiki.opensource-excellence.com/index.php?title=How_to_setup_a_.htpassword_in_your_control_panel%3F&action=edit&redlink=1' target='_blank'>WIKI</a>on how to setup a .htpassword in your control panel", true);
		}
		elseif (oseFile :: write($htpassfile, $content)) {
			return true;
		} else {
			oseCPU :: ajaxResponse('ERROR', JText :: _("Failed creating .htpassword file."));
		}
	}
	private function gethtaccessContent($filepath) {
		return "AuthUserFile \"" . $filepath . "\" \n" .		"AuthName \"Administrator only\"\n" .		"AuthType Basic \n" .		"require valid-user\n";
	}
	public function createHTPass($authUser, $authPass, $backPath) {
		$this->checkValue('.htpassword Username', $authUser);
		$this->checkValue('.htpassword Password', $authPass);
		$this->checkValue('Backend Path', $backPath);
		$htpassfile = $backPath . ODS . '.htpasswd';
		if (!file_exists($htpassfile)) {
			$this->createEncryptPass($authUser, $authPass, $backPath);
		} else {
			oseCPU :: ajaxResponse('ERROR', JText :: _("The .htpassword file already exists, your action will override the existing setting."), false);
		}
		$htaccessFile = oseFile :: clean($backPath) . ODS . ".htaccess";
		$filepath = oseFile :: clean($htpassfile);
		$htaccessContent = $this->gethtaccessContent($filepath);
		if ((!file_exists($htaccessFile)) || (is_writable(dirname($htaccessFile)))) {
			if (oseFile :: write($htaccessFile, $htaccessContent)) {
				$backPath = dirname($htaccessFile);
				oseCPU :: ajaxResponse('Done', 'htpassword successfully created', true);
			} else {
				oseCPU :: ajaxResponse('ERROR', 'Failed creating htpassword', false);
			}
		} else {
			oseCPU :: ajaxResponse('ERROR', 'htpassword cannot be written to the folder: ' . dirname($htpassfile) . ", please see this <a href='http://wiki.opensource-excellence.com/index.php?title=How_to_setup_a_.htpassword_in_your_control_panel%3F&action=edit&redlink=1' target='_blank'>WIKI</a>on how to setup a .htpassword in your control panel", true);
		}
	}
	private function getlocalPHPINIcontent() {
		// try to get path using phpinfo
		ob_start();
		phpinfo(INFO_GENERAL);
		$phpinfo = ob_get_contents();
		ob_end_clean();
		preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
		$output = preg_replace('#<table#', '<table class="adminlist" align="center"', $output[1][0]);
		$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
		$output = preg_replace('#border="0" cellpadding="3" width="600"#', 'border="0" cellspacing="1" cellpadding="4" width="95%"', $output);
		$output = preg_replace('#<hr />#', '', $output);
		$output = str_replace('<div class="center">', '', $output);
		$output = str_replace('</div>', '', $output);
		preg_match('#<tr><td\s+class="e">Loaded\s+Configuration\s+File\s+</td><td\s+class="v">.*</td></tr>#', $output, $match);
		$loaded_php = str_replace('<tr><td class="e">Loaded Configuration File </td><td class="v">', "", $match[0]);
		$loaded_php = str_replace('</td></tr>', "", $loaded_php);
		// Get Content//
		if (file_exists($loaded_php)) {
			$phpini = file_get_contents($loaded_php);
		} else {
			$phpini = "";
		}
		return $phpini;
	}
}