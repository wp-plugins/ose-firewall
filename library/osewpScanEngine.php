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
class osewpScanEngine {
	var $db = '';
	var $table = '';
	var $setting = array();
	var $ext = '';
	var $maxfilesize = '';
	var $patterns = '';
	public function __construct()
	{
		global $wpdb;
		$this->db= $wpdb;
		$this->table= $wpdb->base_prefix.'osefw_files';
		$this->setting = (array) get_option('ose_wp_firewall_avsetting');
		$this->ext = $this->getFileExts();;
		$this->maxfilesize = $this->setting['maxfilesize'];
		if ($this->maxfilesize>0)
		{
			$this->maxfilesize = $this->maxfilesize * 1024 * 1024; 
		}
	}
	public function getFileExts()
	{
		$tmp = explode(',', trim($this->setting['file_ext']));
		return $tmp; 
	}
	public function startScan() {
		$init = (int) $_POST['init'];
		if ($init==true)
		{	
			$return = $this ->getReturn(ABSPATH);
		}
		else
		{
			$dirs = $this->getFolder(10);
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
					$this->deletepathDB($dir->filename);
					unset($tmp);
				}
			}	
		}
		if ($return ['cont']==true)
		{		
			$return['summary'] = OSE_INIT.' '.$return ['folder'].' '.OSE_FOLDERS.' '.OSE_AND.' '.$return ['file'].' '.OSE_FILES.' ';
		}
		else
		{
			$total = $this->CountFiles(); 
			$return['summary'] = OSE_INIT.' '.OSE_INTOTAL.' '.$total.' '.OSE_FILES.'.';
		}	
		osewpUtils::jsonReturn($return);
	}
	public function startVSScan()
	{
		$init = (int) $_POST['init'];
		if ($init==true)
		{
			$return = $this ->updateAllFileStatus(0);
		}
		// Load Definitions;
		$this->loadDefitions();
		$this->scanFiles(5);
	}
	private function loadDefitions()
	{
		// check user type and load different definitions;
		require_once(OSEFWLIBRARY.'/definitions/osevsdef_free.bin');
		$oseDef = new oseVirusDefinitions();
		$this->patterns = $oseDef->patterns;  
	}
	private function scanFiles($limit)
	{
		$files = $this->getFiles($limit, 0);
		$return=array();
		$return['summary'] = '';
		$return['found'] = '';
		foreach ($files as $file)
		{
			$scanresult = $this->scanFile($file->id, $file->filename);
			$return['summary'].= '<div>'.$file->filename.'</div>';
			if ($scanresult==true)
			{
				$return['found'].= '<div class="vsfound">'.$file->filename.'</div>';
			}	
		}	
		$files = $this->getFiles(1, 0);
		{
			if (!empty($files))
			{
				$return['cont']=1;
			}	
		}
		osewpUtils::jsonReturn($return);
	}
	private function scanFile($id, $filename)
	{
		$data= file($filename);
		$data= implode("\r\n", $data);
		$virus_found= false;
		foreach($this->patterns as $key => $pattern)
		{
				if(preg_match($pattern[1], $data))
				{
					$virus_found= true;
					$this->updateFile($id, 'patterns', $pattern, '%s');
					break;
				}
		}
		$this->updateFile($id, 'checked', 1, '%d');
		return $virus_found; 
	}
	private function updateFile($id, $field, $value, $valuetype)
	{	
		$result = $this->db->query(
				$this->db->prepare(
						"UPDATE `".$this->table."` SET `{$field}` = {$valuetype} WHERE id = %d",
						$value, $id
				)
		);
		return $result;
	}
	private function updateAllFileStatus($status = 0)
	{
		$result = $this->db->query(
				$this->db->prepare(
						"UPDATE `".$this->table."` SET `checked` = %d",
						$status
				)
		);
		return $result;
	}
	public function deletepathDB($path)
	{
		$result = $this->db->query(
				$this->db->prepare(
						"DELETE FROM `".$this->table."` WHERE `type` = %s AND `filename` = %s",
						'd', $path
				)
		);
		return $result; 
	}
	public function getReturn($path)
	{
		$return = $this->getFolderFiles($path);
		$return['cont']= $this->isFolderLeft();
		return $return; 
	}
	public function clearDB()
	{
		//;
	}
	private function getFolder($limit)
	{
		$query = "SELECT filename FROM `".$this->table."`"
				." WHERE `type` = 'd' LIMIT ".(int)$limit;
		$result = $this->db->get_results($query);
		return $result;
	} 
	private function getFiles($limit, $status)
	{
		$query = "SELECT id, filename FROM `".$this->table."`"
				." WHERE `type` = 'f' "
				." AND `checked` = ".(int)	$status	
				." LIMIT ".(int)$limit;
		$result = $this->db->get_results($query);
		return $result;
	}
	public function getFolderFiles($folder) {
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
					if (in_array($fileext, $this->ext))
					{
						if ($this->maxfilesize>0)
						{
							if(filesize($dir) < $this->maxfilesize)
							{
								$arr['file'] ++;
								$this->insertData($dir, 'f');
							}
						}	
						else
						{
							$arr['file'] ++;
							$this->insertData($dir, 'f');
						}	
					}	
				}	
			}
		}
		@closedir($handle);
		return $arr;
	}
	public function getExt($file)
	{
		$dot = strrpos($file, '.') + 1;
		return substr($file, $dot);
	}
	public function insertData($filename,$type)
	{
		$result = $this->getfromDB($filename, $type);
		if (empty($result))
		{
			$this->insertInDB($filename, $type);
		}	
	}
	public function insertInDB($filename, $type) {
		$this->db->insert($this->table,
				array(
						'id' => NULL,
						'filename' => $filename,
						'type' => $type,
						'checked' => 0,
						'patterns' => ''
				),
				array ('%d','%s', '%s', '%d', '%s'));
		return $this->db->insert_id;
	}
	public function getfromDB($filename, $type) {
		$query = "SELECT COUNT(`id`) as count FROM `".$this->table."`"
			  ." WHERE `filename` = ".$this->Quote($filename)
			  ." AND `type` = ".$this->Quote($type);
		$result = $this->db->get_results($query);
		return $result[0]->count;
	}
	public function isFolderLeft() {
		$query = "SELECT COUNT(`id`) as count FROM `".$this->table."`"
				." WHERE `type` = 'd'";
		$result = $this->db->get_results($query);
		return ($result[0]->count>0)?true:false;
	}
	private function CountFiles() {
		$query = "SELECT COUNT(`id`) as count FROM `".$this->table."`"
				." WHERE `type` = 'f'";
		$result = $this->db->get_results($query);
		return $result[0]->count;
	}
	private function CountInfectedFiles() {
		$query = "SELECT COUNT(`id`) as count FROM `".$this->table."`"
				." WHERE `type` = 'f' AND patterns != ''";
		$result = $this->db->get_results($query);
		return $result[0]->count;
	}
	private function Quote($var)
	{
		return "'".$this->db->_escape($var)."'";
	}
	public function totalFiles()
	{
		$return = array(); 
		$return['summary'] =  OSE_THERE_ARE.' '.OSE_INTOTAL.' '.$this->CountFiles().' '.OSE_FILES.' '.OSE_IN_DB; 
		osewpUtils::jsonReturn($return);
	}
	public function totalinfectedFiles()
	{
		$return = array();
		$return['summary'] =  OSE_THERE_ARE.' '.OSE_INTOTAL.' '.$this->CountInfectedFiles().' '.OSE_INFECTED_FILES.' '.OSE_IN_DB;
		osewpUtils::jsonReturn($return);
	}
	
}
?>
