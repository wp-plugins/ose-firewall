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
class virusScanner {
	private $db = null;
	private $filestable = '#__osefirewall_files';
	private $logstable = '#__osefirewall_logs';
	private $malwaretable = '#__osefirewall_malware';
	private $file_ext = '';
	private $config = '';
	private $maxfilesize = 0;
	private $patterns = '';
	private $type = 0; 
	private $clamd = null;
	private $vsInfo = array(); 
	public function __construct()
	{
		oseFirewall::loadLanguage();
		$this->db= oseFirewall::getDBO();
		$this->setConfiguration();
		$this->setFileExts();
		$this->setMaxFileSize();
		$this->optimizePHP (); 
		$this->setClamd(); 
		oseFirewall::loadFiles(); 
	}
	private function setClamd () {
		if ($this->config->enable_clamav == 1) 
		{
			oseFirewall::callLibClass('clamd', 'clamd');
			$this->clamd = new Clamd();
		}
	}
	private function setConfiguration() {
		if (!isset($_SESSION['oseConfig']))
		{
			$config = oseFirewall::getConfiguration('vsscan');
			$this->config = $_SESSION['oseConfig'] = (object)$config['data'];
		}
		else 
		{
			$this->config = $_SESSION['oseConfig'];
		}
	}
	public function setFileExts()
	{
		if (!isset($this->config->file_ext))
		{
			$this->config->file_ext = "htm,html,shtm,shtml,css,js,php,php3,php4,php5,inc,phtml,jpg,jpeg,gif,png,bmp,c,sh,pl,perl,cgi,txt";
		}
		$this->file_ext = explode(',', trim($this->config->file_ext));
	}
	private function setMaxFileSize () {
		if ($this->maxfilesize>0)
		{
			$this->maxfilesize = $this->maxfilesize * 1024 * 1024; 
		}
	}
	private function optimizePHP () {
		if (function_exists('ini_set'))
		{
			ini_set('max_execution_time', 300);
			ini_set('memory_limit', '1024M');
			ini_set("pcre.recursion_limit", "524");
		}	
	}
	public function initDatabase($step, $directory) {
		if ($step<0)
		{	
			$this->clearTable(); 
			$return = $this ->getReturn($directory);
		}
		else
		{
			$dirs = $this->getFolder(5);
			if (empty($dirs))
			{
				$return['cont']= false;
				$return['folders']= 0;
				$return['file']= 0;
			}	
			else
			{
				$return=array();
				$return ['folder'] =0;
				$return ['file'] =0;
				foreach ($dirs as $dir)
				{
					$tmp = $this ->getReturn($dir->filename);
					$return ['folder'] += $tmp['folder'];
					$return ['file'] += $tmp['file'];
					$return ['cont'] = $tmp['cont'];
					$return ['lastscanned'] = LAST_SCANNED. $dir->filename; 
					$this->deletepathDB($dir->filename);
					unset($tmp);
				}
			}	
		}
		if ($return ['cont']==true)
		{		
			$return['summary'] = OSE_SCANNING.' '.$return ['folder'].' '.OSE_FOLDERS.' '.OSE_AND.' '.$return ['file'].' '.OSE_FILES.' ';
		}
		else
		{
			$total = $this->CountFiles(); 
			$return['summary'] = OSE_ADDED.' '.OSE_INTOTAL.' '.$total.' '.OSE_FILES.'.';
		}	
		$this->db -> closeDBO(); 
		oseAjax::returnJSON($return);
	}
	private function clearTable () {
		$query = "TRUNCATE TABLE ".$this->db->quoteTable($this->filestable);
		$this->db->setQuery($query);
		$result = $this->db->query();
		return $result;
	}
	public function getReturn($path)
	{
		$return = $this->getFolderFiles($path);
		$return['cont']= $this->isFolderLeft();
		return $return; 
	}
	private function getFolderFiles($folder) {
		// Initialize variables
		$arr = array();
		$arr['folder'] = 0;
		$arr['file'] = 0;
		$false = false;
		if (!is_dir($folder))
			return $false;
		$handle = @opendir($folder);
		// If directory is not accessible, just return FALSE
		if ($handle === FALSE) {
			return $false;
		}
		while ((($file = @readdir($handle)) !== false)) {
			if (($file != '.') && ($file != '..')) {
				$ds = ($folder == '') || ($folder == '/') || (@substr($folder, -1) == '/') || (@substr($folder, -1) == DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR;
				$dir = $folder . $ds . $file;
				$isDir = is_dir($dir);
				if ($isDir) {
					$arr['folder'] ++;
					$this->insertData($dir, 'd');
				}
				else
				{
					$fileext = $this->getExt($dir);
					$filesize= filesize($dir);
					if (in_array($fileext, $this->file_ext))
					{
						if (!empty($this->maxfilesize))
						{
							if(filesize($dir) < $this->maxfilesize)
							{
								$arr['file'] ++;
								$this->insertData($dir, 'f', $fileext);
							}
						}	
						else
						{
							$arr['file'] ++;
							$this->insertData($dir, 'f', $fileext);
						}	
					}	
				}	
			}
		}
		@closedir($handle);
		return $arr;
	}
	private function insertData($filename,$type, $fileext='')
	{
		$result = $this->getfromDB($filename, $type, $fileext);
		if (empty($result))
		{
			return $this->insertInDB($filename, $type, $fileext);
		}	
		else
		{
			return $result -> id; 
		}
	}
	private function getfromDB($filename, $type, $fileext) {
		$query = "SELECT `id` " 
				."FROM ".$this->db->quoteTable($this->filestable)
			    ." WHERE `filename` = ".$this->db->quoteValue($filename)
			    ." AND `type` = ".$this->db->quoteValue($type)
			    ." AND `ext` = ".$this->db->quoteValue($fileext);
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		return $result;
	}
	public function insertInDB($filename, $type, $fileext) {
		$varValues = array(
						'filename' => $filename,
						'type' => $type,
						'checked' => 0,
						'patterns' => '',
						'ext' => $fileext
					);
		$id = $this->db->addData ('insert', $this->filestable, '', '', $varValues);
		return $id;
	}
	private function isFolderLeft() {
		$query = "SELECT COUNT(`id`) as count FROM ".$this->db->quoteTable($this->filestable)
				." WHERE `type` = 'd'";
		$this->db->setQuery($query);
		$result = (object)$this->db->loadObject();
		return $result->count;
	}
	private function getExt($file)
	{
		$dot = strrpos($file, '.') + 1;
		return substr($file, $dot);
	}
	private function getFolder($limit)
	{
		$query = "SELECT `filename` FROM `".$this->filestable."`"
				." WHERE `type` = 'd' LIMIT ".(int)$limit;
		$this->db->setQuery($query);
		$result = $this->db->loadObjectList();
		return $result;
	} 
	private function deletepathDB($path)
	{
		$query = "DELETE FROM `".$this->filestable."` WHERE `type` = 'd' AND `filename` = " .$this->db->quoteValue ($path); 
		$this->db->setQuery($query);
		return $this->db->query(); 
	}
	public function countFiles() {
		$query = "SELECT COUNT(`id`) as count FROM `".$this->filestable."`"
				." WHERE `type` = 'f'";
		$this->db->setQuery($query);
		$result = (object)$this->db->loadResult(); 
		return $result->count;
	}
	private function getFiles($limit, $status)
	{
		$query = "SELECT `id`, `filename` FROM `".$this->filestable."`"
				." WHERE `type` = 'f' "
				." AND `checked` = ".(int)	$status	
				." LIMIT ".(int)$limit;
		$this->db->setQuery($query);
		$result = $this->db->loadObjectList();
		return (!empty($result))?$result:false;
	}
	private function setPatterns($type) {
		$query = "SELECT `id`,`patterns` FROM `#__osefirewall_vspatterns` WHERE `type_id` = ".(int)$type;
		$this->db->setQuery($query);
		$_SESSION['patterns'] = $this->db->loadObjectList();
		//$_SESSION['patterns'][]= (object) array('id'=>'20', 'patterns'=>'[$]([A-Z0-9a-z_])\w+[\s\=]+[\'|\w]+[$&+,:;=?@#|\'<>.^*()%!-~\s]+\"[\(abcdeos46_]+\w+\'\)+\;');
		//$_SESSION['patterns'][]= (object) array('id'=>'21', 'patterns'=>"\<\?php\s+[$]([A-Z0-9a-z_])\w+[\s\=]+[\'|\w]+[$&+,:;=?@#|'<>.^*()%!-~\s]+\;\?\>");
		//$_SESSION['patterns'][]= (object) array('id'=>'22', 'patterns'=>"base64\_decode");
		//$_SESSION['patterns'][]= (object) array('id'=>'23', 'patterns'=>"eval\(");
		$_SESSION['patterns'][]= (object) array('id'=>'23', 'patterns'=>"\<\?php\s*(\$|\w|\s|\=)*.*HTTP_USER_AGENT.*keywordsRegex.*exit\(\)\;\s*\}\s*\?\>");
		$_SESSION['patterns'][]= (object) array('id'=>'24', 'patterns'=>"\<\?php\s*(\$|\w|\s|\=|\"|\.)*\;eval\(.*\)\;\?>");
	}
	private function updateAllFileStatus($status = 0)
	{
		$query = "UPDATE `".$this->filestable."` SET `checked` = ". (int)$status; 
		$this->db->setQuery($query);
		$result = $this->db->query();
		return $result;
	}
	public function vsScan($step) {
		if ($step==-2)
		{ 
			$this->cleanMalwareData (); 
			$_SESSION['completed'] = 0;
			$_SESSION['start_time'] = time();  
			$result = $this->showCountFilesMsg ();
		}
		else if ($step==-1)
		{
			$result = $this->showScanningStatusMsg();
		}
		$this->db -> closeDBO(); 
		return $result;
	}
	public function vsScanInd($type, $remote=false) {
		oseFirewall::loadJSON();
		$conn = $this->getCurrentConnection();
		if ($conn > 20)
		{	
			$result = $this->getHoldingStatus ($type);
		}
		else {
			ini_set("display_errors", "on");
			oseFirewall::loadFiles();
			$this->setPatterns ($type);
			$result = $this->showScanningResultMsg ($type, $remote);
		}
		$this->db -> closeDBO();
		return $result;
	}
	private function getHoldingStatus ($type) {
		$this->vsInfo = $this->getVsFiles($type);
		$timeUsed = $this->timeDifference($_SESSION['start_time'], time());
		$completed = $this->vsInfo['completed'];
		$left = count($this->vsInfo['fileset']);
		$total = $this->vsInfo['completed'] + $left;
		$progress = ($completed/$total);
		$return['completed'] = 'Queue';
		$return['summary'] = (round($progress, 3)*100). '% ' .oLang::_get('COMPLETED');
		$return['progress'] = "<b>Progress: ".($left)." files remaining.</b>. Time Used: ".$timeUsed." seconds<br/><br/>";
		$return['last_file'] = oLang::_get('LAST_SCANNED_FILE').' '.$last_file;
		$return['cont'] = ($left > 0 )?true:false;
		$return['cpuload'] = $this->getCPULoad();;
		$return['memory'] = $this->getMemoryUsed();
		return $return;
	}
	
	private function getCurrentConnection () {
		$query = "SHOW STATUS WHERE `variable_name` = 'Threads_connected'";
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		return $result['Value'];
	}
	private function showScanningResultMsg ($type, $remote) {
		$return=array();
		$return['summary'] = null;
		$return['found'] = 0;
		$start_time = time();
		$result = $this->scanFileLoop ($start_time, $type, $remote);
		if($result == false)
		{
			return false;
		}
		$last_file = $_SESSION['last_scanned'];
		return $this->returnAjaxMsg($last_file, $type);
	}
	private function scanFileLoop ($start_time, $type, $remote) {
		ini_set('display_errors', 'on');
		$this->vsInfo = $this->getVsFiles($type);
		while (count($this->vsInfo['fileset'])>0)
		{
			$since_start = $this->timeDifference($start_time, time());
			if ($since_start>=2)
			{
				$result = $this->saveVsFiles($this->vsInfo['fileset'], $this->vsInfo['completed'], $type);
				if($result == false)
				{
					return false;
				}
				else
				{
					$return = $this->returnAjaxMsg($_SESSION['last_scanned'], $type);
					if ($remote == false)
					{
						print_r(oseJSON::encode($return)); exit;
					}
					else
					{
						return $return;
					}		
				}
				break;
			}
			$_SESSION['last_scanned'] = array_pop($this->vsInfo['fileset']);
			$this->vsInfo['completed'] ++;
			if(oseFile::exists($_SESSION['last_scanned'] )==false) {
				continue;
			}
			if($this->ignoredFiles($_SESSION['last_scanned'] )==true) {
				continue;
			}
			if (filesize($_SESSION['last_scanned'] )>2048000)
			{
				continue;
			}
			else
			{
				$this->scanFile($_SESSION['last_scanned']);
			}
		}
		if (count($this->vsInfo['fileset']) == 0)
		{
			$this->clearFile ($type);
			return $this->returnCompleteMsg($last_file, $type);
		}
		return true; 
	}
	private function ignoredFiles ($file) {
		if (preg_match('/mootree\.gif/ims', $file))
		{
			return true;
		}
		else
		{
			return false; 
		}
	}
	private function clearFileFromArray ($index) {
		unset($_SESSION['oseFileArray'][$index]);
	}
	private function showScanningStatusMsg () {
		$this->saveVsFilesLoop();
		$return['completed'] = 0;
		$return['summary'] = 'Finish creating all scanning objects';
		$return['progress'] = 'Finish creating all scanning objects';
		$return['last_file'] = '';
		$return['cont'] = true;
		$return['cpuload'] = $this->getCPULoad();;
		$return['memory'] = $this->getMemoryUsed();;
		$return['showCountFiles'] = false;
		return $return; 
	}
	private function showCountFilesMsg () {
		$first  = new DateTime(date('Y-m-d h:i:s'));
		if (!empty($_POST['scanPath']))
		{
			$scanPath = $_POST['scanPath'];
		}	
		else
		{
			$scanPath = oseFirewall::getScanPath();
		}
		$fileCount = $this->getNumberofFiles($scanPath);
		$timeUsed = $this->getTimeUsed ($first);  
		$memUsed = $this->getMemoryUsed (); 
		$return['completed'] = 0;
		$return['summary'] = 'There are in total of '.$fileCount.' files in your website (time used '.$timeUsed.'), the scanning will start shortly';
		$return['progress'] = 'Found '.$fileCount.' number of files';
		$return['last_file'] = '';
		$return['cont'] = true;
		$return['cpuload'] = $this->getCPULoad();;
		$return['memory'] = $memUsed;
		$return['showCountFiles'] = true;
		return $return; 
	} 
	private function getMemoryUsed () {
		$newMemoryUsage = round(memory_get_usage(true)/(1028*1024), 2);
		return $newMemoryUsage; 
	}
	private function getTimeUsed ($first) {
		$second = new DateTime(date('Y-m-d h:i:s'));
		$diff = $first->diff( $second );
		$timeUsed = $diff->format( '%H:%I:%S' );
		return $timeUsed; 
	}
	private function getNumberofFiles ($path) {
		$x = 0; 
		$oseFileArray = array (); 
		if (!empty($path)) {
			$dir_iterator = new RecursiveDirectoryIterator($path);
			$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
			foreach ($iterator as $fullFileName => $fileSPLObject)
			{
				if ($fullFileName != $path."/.." && $fileSPLObject->isFile() && in_array($this->getFileExtension($fullFileName), $this->file_ext)) {
					$x++;
					$oseFileArray[] = $fullFileName; 
				}
			}
		}
		$this->saveVsFiles($oseFileArray, $_SESSION['completed']);
		return $x; 
	}
	private function getFileExtension ($path) {
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		return $ext;
	}
	private function returnCompleteMsg ($last_file=null, $type) {
		$return['completed'] = 100;
		$return['summary'] = ($return['completed']). '% ' .oLang::_get('COMPLETED');
		$return['progress'] = '';
		$return['last_file'] = '';
		$return['status']='Completed';
		$return['cont'] = false;
		$return['type'] = $type; 
		$infectedNum= $this->getNumInfectedFiles();
		if($infectedNum < 1) {
				$return['result']= oLang :: _get('WEBSITE_CLEAN');
		} else {
				$return['result']= '<a href = "http://www.centrora.com/scan-report/">'. oLang:: _get('OSE_THERE_ARE'). ' '. $infectedNum. ' '. oLang :: _get('OSE_INFECTED_FILES') .'</a>';
		}
		return $return;  
	}
	private function returnAjaxMsg ($last_file=null, $type) {
		if (count($this->vsInfo['fileset']) == 0)
		{
			$this->clearFile ($type);
			return $this->returnCompleteMsg($last_file, $type);
		}
		else
		{
			$return = array (); 
			$infectedNum= $this->getNumInfectedFiles();
			// Start to make result report
			if($infectedNum < 1) {
				$return['status']= oLang :: _get('WEBSITE_CLEAN');
			} else {
				$return['status']= oLang:: _get('OSE_THERE_ARE'). ' '. $infectedNum. ' '. oLang :: _get('OSE_INFECTED_FILES');
			}
			$timeUsed = $this->timeDifference($_SESSION['start_time'], time());
			$completed = $this->vsInfo['completed'];
			$left = count($this->vsInfo['fileset']);
			$total = $this->vsInfo['completed'] + $left;
			$progress = ($completed/$total);
			$return['completed'] = round($progress, 3)*100;
			$return['summary'] = ($return['completed']). '% ' .oLang::_get('COMPLETED');
			$return['progress'] = "<b>Progress: ".($left)." files remaining.</b>. Time Used: ".$timeUsed." seconds<br/><br/>";
			$return['last_file'] = oLang::_get('LAST_SCANNED_FILE').' '.$last_file;
			$return['cont'] = ($left > 0 )?true:false;
			$return['cpuload'] = $this->getCPULoad();;
			$return['memory'] = $this->getMemoryUsed();
			$return['type'] = $type;
			return $return;  
		}
	}
	private function getCPULoad () {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			$cpuload = 'N/A in Windows';
			return $cpuload;
		}
		else
		{
			$cpuload = sys_getloadavg();
			return $cpuload[0];
		} 
	}
	private function getCompleted() {
		$query= "SELECT COUNT(`id`) as `count` FROM `#__osefirewall_files` WHERE `checked` = 0 ";
		$this->db->setQuery($query);
		$result = (object) ($this->db->loadResult());
		return $result->count;
	}
	private function getTotal() {
		$query= "SELECT COUNT(`id`) as `count` FROM  `#__osefirewall_files` ";
		$this->db->setQuery($query);
		$result = (object) ($this->db->loadResult());
		return $result->count;
	}
	private function updateFile($id, $field, $value){	
		$query = " UPDATE `".$this->filestable."` SET `{$field}` = ".$this->db->quoteValue($value)
				." WHERE id = " .(int)$id;
		$this->db->setQuery ($query);
		$result = $this->db->query(); 
		return $result;
	}
	private function timeDifference($timeStart, $timeEnd){
		return $timeEnd- $timeStart; 
	}
	private function scanFile($scan_file)
	{
		if (empty($scan_file))
		{
			return false; 
		}
		oseFirewall::loadFiles(); 
		$virus_found= false;
		$content = oseFile::read ($scan_file);
		$matches = array ();
		$i=0; 
		foreach($_SESSION['patterns'] as $key => $pattern)
		{
			$i++;
			$array = preg_split('/'.trim($pattern->patterns).'/im', $content, 2);
			if(count($array)>1)
			{
				$virus_found= true;
				$file_id = $this->insertData($scan_file,'f', ''); 
				$this->logMalware($file_id, $pattern->id);
				break;
			}
		}
		usleep(100);
		return $virus_found;
	}
	private function scanWithClamAV2 () {
		$this->config->enable_clamav = false;
		if ($this->config->enable_clamav == 1 && $virus_found == false)
		{
			$results = $this->clamd->scan($scan_file);
			if ($results['status'] == 2)
			{
				$virus_found= true;
				$file_id = $this->insertData($scan_file,'f', '');
				$pattern_id = $this->logClamVirus ($results['msg']);
				$this->logMalware($file_id, $pattern_id);
			}
		}
	}
	private function logClamVirus ($msg)
	{
		$detectedMal = $this->getClamMessage($msg);
		if (empty($detectedMal))
		{
			$db = oseFirewall::getDBO ();
			$varValues = array(
						'patterns' => $msg,
						'type_id' => 9,
						'confidence' => 100,
					);
			$id = $db->addData ('insert', '#__osefirewall_vspatterns', '', '', $varValues);
			return $id;
		}
		else
		{
			return $varObject->id;
		}
	}
	private function getClamMessage ($msg) {
		$db = oseFirewall::getDBO ();
		$query = "SELECT COUNT(`id`) as `count` FROM `#__osefirewall_vspatterns` ".
				 " WHERE `patterns` = ".$db->QuoteValue($msg);
		$db->setQuery($query);
		$result = (object)($db->loadResult()); 
		$db -> closeDBO(); 
		return $result->count; 
	}
	private function logMalware ($file_id, $pattern_id)
	{
		$detectedMal = $this->getDectectedMal($file_id, $pattern_id);
		if (empty($detectedMal))
		{
			$db = oseFirewall::getDBO ();
			$varValues = array(
						'file_id' => (int)$file_id,
						'pattern_id' => (int)$pattern_id
					);
			$id = $db->addData ('insert', $this->malwaretable, '', '', $varValues);
			return $id;
		}
		else
		{
			return $varObject->id;
		}
	}
	private function getDectectedMal($file_id, $pattern_id)
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT COUNT(`file_id`) as `count` FROM `".$this->malwaretable."`".
				 " WHERE `file_id` = ".(int)$file_id;
				 " AND `pattern_id` = ".(int)$pattern_id;
		$db->setQuery($query);
		$result = (object)($db->loadResult()); 
		$db -> closeDBO(); 
		return $result->count; 
	}	
	public function getNumInfectedFiles ()
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT COUNT(`file_id`) AS `count` FROM `".$this->malwaretable."`";
		$db->setQuery($query);
		$result = (object)($db->loadResult()); 
		$db -> closeDBO(); 
		return $result->count; 
	}
	private function logScanning($status)
	{
		$result = $this->getScanninglog();
		if (!empty($result))
		{
			$this->updateScanninglog($result->id, $status);
		}
		else
		{
			$this->insertScanninglog($status);
		}	
	}
	public function getScanninglog()
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT * FROM `".$this-> logstable."`"
				." WHERE `comp` = 'avs'";
		
		$db->setQuery($query);
		$result = $db->loadobject();
		$db -> closeDBO(); 
		return $result;
		
	}
	private function insertScanninglog($status)
	{
		$this->db->insert($this->logtable,
				array(
						'id' => NULL,
						'date' => date('Y-m-d h:i:s'),
						'comp' => 'avs',
						'status' => $status
				),
				array ('%d','%s', '%s', '%s'));
		return $this->db->insert_id;
	}
	private function updateScanninglog($id, $status)
	{
		$result = $this->db->query(
				$this->db->prepare(
						"UPDATE `".$this->logtable."` SET `status` = '%s', `date` = '%s'  WHERE id = %d",
						$status, date('Y-m-d h:i:s'), $id
			)
		);
		return $result;
	}
	private function cleanMalwareData () {
		$query = "TRUNCATE TABLE `". $this->malwaretable."`;"; 
		$this->db->setQuery ($query);
		$result = $this->db->query(); 
		return $result;
	}
	private function saveVsFiles($fileset, $completed, $type=null)
	{
		$fileContentArray = array("completed" => $completed,
								  "fileset" => $fileset);
		if (empty($type))
		{
			$filePath = OSE_FWDATA.ODS."vsscanPath".ODS."path.json";			
		}
		else
		{
			$filePath = OSE_FWDATA.ODS."vsscanPath".ODS."path_".$type.".json";			
		}
		$fileContent = serialize($fileContentArray);
		$result = oseFile::write($filePath, $fileContent);
		return $result;
	}
	private function clearFile ($type) {
		$filePath = OSE_FWDATA.ODS."vsscanPath".ODS."path_".$type.".json";
		unlink($filePath);
	}
	private function saveVsFilesLoop()
	{
		$result = true;
		$fileContent = oseFile::read(OSE_FWDATA.ODS."vsscanPath".ODS."path.json");
		for($i=1; $i<=8; $i++)
		{
			$filePath = OSE_FWDATA.ODS."vsscanPath".ODS."path_".$i.".json";
			if (!file_exists($filePath))
			{
				$result=oseFile::write($filePath, $fileContent);
			}
		}	
		return $result;
	}
	private function getVsFiles($type)
	{
		$filePath = OSE_FWDATA.ODS."vsscanPath".ODS."path_".$type.".json";
		$fileContent = oseFile::read($filePath);
		return unserialize($fileContent);
	}
	public function scheduleScanning ($step, $type) {
		if ($step == 0) 
		{
			return;
		}
		oseFirewall::loadRequest();
		$key = oRequest::getVar('key', NULL);
		if (!empty($key))
		{
			if ($step < 0)
			{	
				$result = $this->vsscan($step);
				$result ['step'] = $step ++;
			}
			else
			{
				$type = ($type == 0)?1:$type;
				$result = $this->vsScanInd($type, true);
				$result ['step'] = $step;
			}
			$url = $this->getCrawbackURL ($key, $result->completed, $result ['step'], $type);
			$this->sendRequestVS($url);
		}
		exit;	
	}
	private function getWebsiteStatus () {
		$infected = $this->getNumInfectedFiles();
		return ($infected>0)?1:0;
	}
	private function getCrawbackURL ($key, $completed, $step, $type) {
		$webkey= $this->getWebKey ();
		if ($completed==1)
		{
			$status = $this->getWebsiteStatus ();
			return "http://www.centrora.com/accountApi/cronjobs/completeVSScan?webkey=".$webkey."&key=".$key."&completed=1&status=".(int)$status;
		} 
		else 
		{
			$filePath = $this->getScanFilePath ($type);
			if (!file_exists($filePath)) {
				if ($type<=8) {
					$type++; 
				}
			}
			$filePath = $this->getScanFilePath ($type);
			if ($type >= 8 && !file_exists($filePath)) {
				$status = $this->getWebsiteStatus ();
				return "http://www.centrora.com/accountApi/cronjobs/completeVSScan?webkey=".$webkey."&key=".$key."&completed=1&status=".(int)$status;
			}
			else
			{
				return "http://www.centrora.com/accountApi/cronjobs/continueVSScan?webkey=".$webkey."&key=".$key."&completed=0&step=".$step."&type=".$type;
			}
		}
	}
	protected function getScanFilePath ($type) {
		return OSE_FWDATA.ODS."vsscanPath".ODS."path_".$type.".json";;
	}
	protected function getWebKey () {
		$dbo = oseFirewall::getDBO();
		$query = "SELECT * FROM `#__ose_secConfig` WHERE `key` = 'webkey'";
		$dbo->setQuery($query);
		$webkey = $dbo->loadObject()->value;
		return $webkey;
	}
	private function sendRequestVS($url)
	{
		$User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
		$request_headers = array();
		$request_headers[] = 'User-Agent: '. $User_Agent;
		$request_headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_HTTPHEADER => $request_headers, 
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'Centrora Security Download Request Agent',
			CURLOPT_TIMEOUT => 5
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		return $resp;
	}
}
?>
