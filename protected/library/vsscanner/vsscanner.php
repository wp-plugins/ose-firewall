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
defined('OSEFWDIR') or die;
class virusScanner {
	private $db = null;
	private $filestable = '#__osefirewall_files';
	private $logstable = '#__osefirewall_logs';
	private $malwaretable = '#__osefirewall_malware';
	private $file_ext = '';
	private $config = '';
	private $maxfilesize = 0;
	private $patterns = '';
	public function __construct()
	{
		$this->db= oseFirewall::getDBO();
		$this->setConfiguration();
		$this->setFileExts();
		$this->setMaxFileSize();
	}
	private function setConfiguration() {
		$model = new ConfigurationModel(); 
		$config = $model -> getConfiguration('vsscan');
		$this->config = (object)$config['data'];
	}
	public function setFileExts()
	{
		$this->file_ext = explode(',', trim($this->config->file_ext));
	}
	private function setMaxFileSize () {
		if ($this->maxfilesize>0)
		{
			$this->maxfilesize = $this->maxfilesize * 1024 * 1024; 
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
			$return['summary'] = OSE_ADDED.' '.$return ['folder'].' '.OSE_FOLDERS.' '.OSE_AND.' '.$return ['file'].' '.OSE_FILES.' '.IN_THE_LAST_SCANNED.' '.O_CONTINUE;
		}
		else
		{
			$total = $this->CountFiles(); 
			$return['summary'] = OSE_ADDED.' '.OSE_INTOTAL.' '.$total.' '.OSE_FILES.'.';
		}	
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
			$this->insertInDB($filename, $type, $fileext);
		}	
	}
	private function getfromDB($filename, $type, $fileext) {
		$query = "SELECT COUNT(`id`) as count " 
				."FROM ".$this->db->quoteTable($this->filestable)
			    ." WHERE `filename` = ".$this->db->quoteValue($filename)
			    ." AND `type` = ".$this->db->quoteValue($type)
			    ." AND `ext` = ".$this->db->quoteValue($fileext);
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		return $result->count;
	}
	public function insertInDB($filename, $type, $fileext) {
		$varValues = array(
						'id' => 'DEFAULT',
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
	private function setPatterns() {
		$query = "SELECT `id`,`patterns` FROM `#__osefirewall_vspatterns`";
		$this->db->setQuery($query);
		$this->patterns = $this->db->loadObjectList();
	}
	private function updateAllFileStatus($status = 0)
	{
		$query = "UPDATE `".$this->filestable."` SET `checked` = ". (int)$status; 
		$this->db->setQuery($query);
		$result = $this->db->query();
		return $result;
	}
	public function vsScan($step) {
		if ($step<0)
		{	
			$return = $this ->updateAllFileStatus(0);
		}
		oseFirewall::loadFiles(); 
		$scan_files = $this->getFiles(300, 0);
		if (empty($scan_files))
		{
			return $this->returnCompleteMsg();
		}
		else
		{
			$this->setPatterns (); 
			$return=array();
			$return['summary'] = null;
			$return['found'] = 0;
			$start_date = time();
			$last_file = null; 
			foreach($scan_files as $i => $scan_file) {
				$since_start = $this->timeDifference($start_date, time());
				if ($since_start>=2)
				{
					break;		
				}
				if(oseFile::exists($scan_file->filename)==false) {
					$this->updateFile($scan_file->id, 'checked', 1); 
					continue;
				}
				if (filesize($scan_file->filename)>2048000)
				{
					$this->updateFile($scan_file->id, 'checked', 1); 
					continue;
				}
				else
				{
					//$statusQuery .= $scan_file->filename.'<br/>';
					$scanResult= $this->scanFile($scan_file);
					//$statusQuery .= $scanResult;
				}
				$last_file = $scan_file->filename;
				$this->updateFile($scan_file->id, 'checked', 1); 
			}
			return $this->returnAjaxMsg($last_file);
		}
	}
	private function returnCompleteMsg ($last_file=null) {
		$return['completed'] = 1;
		$return['summary'] = ($return['completed']*100). '% ' .oLang::_get('COMPLETED');
		$return['progress'] = '';
		$return['last_file'] = '';
		$return['status']='Completed';
		$return['cont'] = false;
		return $return;  
	}	
	private function returnAjaxMsg ($last_file=null) {
		oseFirewall::loadLanguage();
		$return = array (); 
		$infectedNum= $this->getNumInfectedFiles();
		// Start to make result report
		if($infectedNum < 1) {
			$return['status']= oLang :: _get('WEBSITE_CLEAN');
		} else {
			$return['status']= oLang:: _get('OSE_THERE_ARE'). ' '. $infectedNum. ' '. oLang :: _get('OSE_INFECTED_FILES');
		}
		$memory_usage = round(memory_get_usage(true)/(1028*1024), 2);
		$cpuload = sys_getloadavg();
		$completed = $this->getCompleted();
		$total = $this->getTotal();
		$progress = 1-($completed/$total);
		$return['completed'] = round($progress, 3);
		$return['summary'] = ($return['completed']*100). '% ' .oLang::_get('COMPLETED');
		$return['progress'] = $statusQuery= "<b>Progress: ".($completed)." files remaining.</b>. Memory Usage: ".$memory_usage."MB, CPU load:".$cpuload[0]."<br/><br/>".$statusQuery;
		$return['last_file'] = oLang::_get('LAST_SCANNED_FILE').' '.$last_file;
		$return['cont'] = ($completed == $total)?false:true;
		return $return;  
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
	private function scanFile($fileObj)
	{
		if (empty($fileObj->filename))
		{
			return false; 
		}
		oseFirewall::loadFiles(); 
		$virus_found= false;
		$content = oseFile::read ($fileObj->filename);
		$matches = array ();
		$i=0; 
		foreach($this->patterns as $key => $pattern)
		{
			$i++;
			preg_match('/'.trim($pattern->patterns).'/im', $content, $matches);
			if(!empty($matches))
			{
				$virus_found= true;
				$this->logMalware($fileObj->id, $pattern->id);
				break;
			}
		}
		usleep(100);
		return $virus_found;
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
			$id = $db->addData ('insert', '#__osefirewall_malware', '', '', $varValues);
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
		return $result->count; 
	}	
	public function getNumInfectedFiles ()
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT COUNT(`file_id`) AS `count` FROM `".$this->malwaretable."`";
		$db->setQuery($query);
		$result = (object)($db->loadResult()); 
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
}
?>
