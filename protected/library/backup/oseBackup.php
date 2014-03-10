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
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH'))
{
	die("Direct Access Not Allowed");
}
class oseBackupManager
{
	private $backup_prefix = null;
	private $backup_type = null;
	private $pathDB = 2;
	private $pathFile = 1;
	private $filestable = '#__osefirewall_bkfiles';
	private $backuptable = '#__osefirewall_backup';
	private $backupPathTable = '#__osefirewall_backupath';
	public function __construct($db, $backup_type = null)
	{
		$this->db = $db;
		$this->backup_prefix = $backup_prefix;
		$this->backup_type = (int) $backup_type;
		$this->fileBackupName = "";
		oseFirewall::loadDateClass();
	}
	
	private function isCurrentUser()
	{
		oseFirewall::loadUsers();
		$oUser = new oseUsers("ose_firewall");
		$users = $oUser->get_super_admins();
		$current_user = wp_get_current_user();
		$isCurrentUser = in_array($current_user->ID, $users);
		if ($isCurrentUser == false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	private function saveBackUpPath($path, $type)
	{
		$result = $this->getBackUpPath($type);
		$result = $result["path"];
		if(empty($result)){
			$array = array(
					'path' => $path
				);
			$this->updateBackUpFilePath($array, $type);
		}
	}

	private function getBackUpPath($type)
	{
		$query = "SELECT `path`, `time` FROM ".$this->db->QuoteTable($this->backupPathTable)." WHERE `id` = ".(int) $type;
		$this->db->setQuery($query);
		$this->db->query();
		$results = $this->db->loadResult();
		return $results;
	}
	
	public function cleanBackUpFilePath()
	{
		for($i = 1; $i <3 ; $i ++){
			$array = array(
					'path' => null,
					'time' => '',
					'fileNum' => 0,
					'fileTotal' => 0
				);
			$this->updateBackUpFilePath($array, $i);
		}
	}
	
	private function checkDBReady($dbName)
	{
		$query = "SELECT COUNT(`id`) as `count` FROM ".$this->db->QuoteTable($dbName);
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		return ($result['count'] > 0) ? true : false;
	}
	
	private function updateBackUpFilePath($array, $type)
	{
		$numItems = count($array);
		$i = 0;
		$dbReady = $this->checkDBReady($this->backupPathTable);
		if(!$dbReady)
		{
			$query = "INSERT INTO ".$this->db->QuoteTable($this->backupPathTable)." VALUES(1,NULL,'0000-00-00',0,0)";
			$this->db->setQuery($query);
			$this->db->query();
			$query = "INSERT INTO ".$this->db->QuoteTable($this->backupPathTable)." VALUES(2,NULL,'0000-00-00',0,0)";
			$this->db->setQuery($query);
			$this->db->query();
		}
		$query = "UPDATE ".$this->db->QuoteTable($this->backupPathTable)." SET ";
		foreach($array as $k => $v)
		{
			$query .= $this->db->quoteKey($k);
			$query .= " = ". $this->db->quoteValue($v);
			if(++$i < $numItems) {
				$query .= ", ";
			}
		}
		$query.= " WHERE id = ". (int)$type;
		$this->db->setQuery($query);
		$this->db->query();
	}
	
	public function downloadBackupFiles($file, $name = '', $mime_type = '')
	{
		//Check the file premission
		if ($this->isCurrentUser() == true)
		{
			if (!is_readable($file))
				die('File not found or inaccessible!');
			$path_prefix = OSE_FWDATA.ODS."backup".ODS;
			$file_name = str_replace($path_prefix, "", $file);
			$size = filesize($file);
			/* Figure out the MIME type | Check in array */
			$known_mime_types = array(
				"pdf" => "application/pdf",
				"txt" => "text/plain",
				"html" => "text/html",
				"htm" => "text/html",
				"exe" => "application/octet-stream",
				"zip" => "application/zip",
				"doc" => "application/msword",
				"xls" => "application/vnd.ms-excel",
				"ppt" => "application/vnd.ms-powerpoint",
				"gif" => "image/gif",
				"png" => "image/png",
				"jpeg" => "image/jpg",
				"jpg" => "image/jpg",
				"php" => "text/plain",
				"gzip" => "application/x-gzip",
				"gz" => "application/x-gzip"
			);
			if ($mime_type == '')
			{
				$file_extension = strtolower(substr(strrchr($file, "."), 1));
				if (array_key_exists($file_extension, $known_mime_types))
				{
					$mime_type = $known_mime_types[$file_extension];
				}
				else
				{
					$mime_type = "application/force-download";
				}
			}
			//turn off output buffering to decrease cpu usage
			@ob_end_clean();
			// required for IE, otherwise Content-Disposition may be ignored
			if (ini_get('zlib.output_compression'))
				ini_set('zlib.output_compression', 'Off');
			
			$expireTime = $this->getExpireTime ();	
			header('Content-Type: '.$mime_type);
			header('Content-Disposition: attachment; filename="'.$file_name.'"');
			header("Content-Transfer-Encoding: binary");
			header('Accept-Ranges: bytes');
			/* The three lines below basically make the download non-cacheable */
			header("Cache-control: private");
			header('Pragma: private');
			header("Expires: ".$expireTime);
			// multipart-download and download resuming support
			if (isset($_SERVER['HTTP_RANGE']))
			{
				list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
				list($range) = explode(",", $range, 2);
				list($range, $range_end) = explode("-", $range);
				$range = intval($range);
				if (!$range_end)
				{
					$range_end = $size - 1;
				}
				else
				{
					$range_end = intval($range_end);
				}
				/*
				 ------------------------------------------------------------------------------------------------------
				 //This application is developed by www.webinfopedia.com
				 //visit www.webinfopedia.com for PHP,Mysql,html5 and Designing tutorials for FREE!!!
				 ------------------------------------------------------------------------------------------------------
				 */
				$new_length = $range_end - $range + 1;
				header("HTTP/1.1 206 Partial Content");
				header("Content-Length: $new_length");
				header("Content-Range: bytes $range-$range_end/$size");
			}
			else
			{
				$new_length = $size;
				header("Content-Length: ".$size);
			}
			/* Will output the file itself */
			$chunksize = 1 * (1024 * 1024); //you may want to change this
			$bytes_send = 0;
			if ($file = fopen($file, 'r'))
			{
				if (isset($_SERVER['HTTP_RANGE']))
					fseek($file, $range);
				while (!feof($file) && (!connection_aborted()) && ($bytes_send < $new_length))
				{
					$buffer = fread($file, $chunksize);
					print($buffer); //echo($buffer); // can also possible
					flush();
					$bytes_send += strlen($buffer);
				}
				fclose($file);
			}
			else
				//If no permissiion
				die('Error - can not open file.');
			//die
			die();
		}
		else
		{
			print("<SCRIPT type='text/javascript'>");
			print("alert('You do not have permission to download the file.');");
			print("window.location = '".OSE_WPURL."';");
			print("</SCRIPT>");
		}
	}
	private function getExpireTime () {
		$oseDatetime = new oseDatetime();
		$oseDatetime->setFormat("D, d m Y H:i:s ");
		$timeZone = $oseDatetime->getTimeZonePub ();
		$time = $oseDatetime->getDateTime()." ".$timeZone;
		return $time; 	
	}
	public function backupDB()
	{
		$dbBackupResult = $this->backupDatabase ($this->backup_type);
		$result = null;
		if ($dbBackupResult == false && $this->backup_type != 3)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	private function assembleArray($result, $status, $msg, $continue, $id)
	{
		$return = array(
			'success' => (boolean) $result,
			'status' => $status,
			'result' => $msg,
			'cont' => (boolean) $continue,
			'id' => (int) $id
		);
		return $return;
	}
	public function getBackupList()
	{
		$limit = oRequest::getInt('limit', 15);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		if (isset($_REQUEST['status']))
		{
			$status = oRequest::getInt('status', null);
		}
		else
		{
			$status = null;
		}
		$start = $limit * ($page - 1);
		return $this->convertVariables($this->getBackupDB($search, $start, $limit, $status));
	}
	public function getBackupTotal()
	{
		$db = oseFirewall::getDBO();
		$result = $db->getTotalNumber('id', '#__osefirewall_backup');
		return $result;
	}
	public function removeBackUp($id)
	{
		oseFirewall::loadFiles();
		$db = oseFirewall::getDBO();
		$query = "SELECT * FROM `#__osefirewall_backup` WHERE `id` = ".(int) $id;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$results = $results[0];
		if (!empty($results->dbBackupPath))
		{
			$result = osefile::delete($results->dbBackupPath.".gz");

			if ($result == false)
			{
				return false;
			}
		}
		
		if (!empty($results->fileBackupPath))
		{
			$result = osefile::delete($results->fileBackupPath);
			if ($result == false)
			{
				return false;
			}
		}
		if (!empty($id))
		{
			$result = $this->deleteBackupID($id);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	private function deleteBackupID($id)
	{
		$db = oseFirewall::getDBO();
		return $db->deleteRecord(array('id' => $id), '#__osefirewall_backup');
	}
	public function getBackupDB($search, $start, $limit, $status)
	{
		if (!empty($status))
		{
			$where[] = "`type` = ".(int) $status;
		}
		$db = oseFirewall::getDBO();
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_backup` ". $where ."ORDER BY date DESC LIMIT ".$start.", ".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results;
	}
	public function getBackupDBByID($id)
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT * FROM `#__osefirewall_backup` WHERE `id` = $id ";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results[0];
	}
	private function convertVariables($results)
	{
		for ($i = 0; $i < COUNT($results); $i++)
		{
			if ($results[$i]->dbBackupPath != null)
			{
				$results[$i]->dbBackupPath = "<a href = '".DB_BACKUP_DOWNLOAD_URL."".urlencode($results[$i]->id)."'>download<i class='fa fa-cloud-download
				'></i></a>";
			}
			if ($results[$i]->fileBackupPath != null)
			{
				$results[$i]->fileBackupPath = "<a href = '".FILE_BACKUP_DOWNLOAD_URL."".urlencode($results[$i]->id)."'>download<i class='fa fa-cloud-download
				'></i></a>";
			}
			if ($results[$i]->type != null)
			{
				$results[$i]->type = $this->getBackUpType($results[$i]->type);
			}
			$results[$i]->server = $this->getServerType($results[$i]->server);
			$results[$i]->delete = $this->getDeleteIcon($results[$i]->id);
		}
		return $results;
	}
	private function getBackUpType($type)
	{
		switch ($type)
		{
		case 1:
			return "File ONLY";
			break;
		case 2:
			return "Database ONLY";
			break;
		case 3:
			return "File & Database";
			break;
		}
	}
	private function getServerType($type)
	{
		switch ($type)
		{
		case 1:
			return "Local";
			break;
		case 2:
			return "Dropbox";
			break;
		case 3:
			return "Google Driver";
			break;
		}
	}
	private function getDeleteIcon($id)
	{
		return "<a href='#' onClick= 'deleteItem(".urlencode($id).")' ><div class='ose-grid-delete'></div></a>";
	}
	public function insertbkDB()
	{
		$dbPath = null;
		$filePath = null;
		$time = null;
		if ($this->backup_type == 1)
		{
			$result = $this->getBackUpPath($this->pathFile);
			$filePath = $result['path'];
			$time = $result['time'];
		}
		else if ($this->backup_type == 2)
		{
			$result = $this->getBackUpPath($this->pathDB);
			$dbPath = $result['path'];
			$time = $result['time'];
		}
		else 
		{
			$result = $this->getBackUpPath($this->pathDB);
			$dbPath = $result['path'];
			$time = $result['time'];
			
			$result = $this->getBackUpPath($this->pathFile);
			$filePath = $result['path'];
		}
		$varValues = array(
			0 => array(
				'date' => $time,
				'type' => $this->backup_type,
				'dbBackupPath' => $dbPath,
				'fileBackupPath' => $filePath
			)
		);
		$db = oseFirewall::getDBO();
		$query = $this->getInsertTable('#__osefirewall_backup', $varValues);
		$db->setQuery($query);
		$results = $db->query();
		$this->cleanBackUpFilePath();
		return $results;
	}

	private function backupDatabase()
	{
		$backupFile = $this->setDBFilename ();
		$tables = $this->getTablesList();
		$sql = "";
		// Get All Create Table Queries
		foreach ($tables as $key => $table)
		{
			$createTableQuery = $this->getCreateTable($table);
			if (!empty($createTableQuery))
			{
				$sql .= $createTableQuery;
				$allRows = $this->getAllRows ($table);
				if (!empty($allRows))
				{
					$sql .= $this->getInsertTable ($table, $allRows);
				}
			}
		}
		// Get All Create View Queries
		foreach ($tables as $key => $table)
		{
			$createViewQuery = $this->getCreateView($table);
			if (!empty($createViewQuery))
			{
				$sql .= $createViewQuery;
			}
		}
		// Get All Alter Table Queries
		foreach ($tables as $key => $table)
		{
			$alterTableQuery = $this->getAlterTable($table);
			if (!empty($alterTableQuery))
			{
				$sql .= $alterTableQuery;
			}
		}
		$handle = @gzopen($backupFile.'.gz', 'wb9');
		if (!empty($handle))
		{
			gzwrite($handle, $sql);
			gzclose($handle);
			return true;
		}
		else
		{
			return false;
		}
	}
	private function setDBFilename()
	{
		$oseDatetime = new oseDatetime();
		$oseDatetime->setFormat("Ymd_His");
		$time = $oseDatetime->getDateTime();
		$fileName = OSE_FWDATA.ODS."backup".ODS."dbbackup-".$time.".sql";
		$this->saveBackUpPath($fileName, $this->pathDB);
		$this->setDBTime($this->pathDB);
		return $fileName;
	}
	private function setFilesFilename()
	{
		$oseDatetime = new oseDatetime();
		$oseDatetime->setFormat("Ymd_His");
		$time = $oseDatetime->getDateTime();
		$fileName = OSE_FWDATA.ODS."backup".ODS."filesbackup-".$time.".zip";
		$this->saveBackUpPath($fileName, $this->pathFile);
		$this->setDBTime($this->pathFile);
		return $fileName;
	}
	private function setDBTime($type)
	{
		$oseDatetime = new oseDatetime();
		$oseDatetime->setFormat("Y-m-d H:i:s");
		$time = $oseDatetime->getDateTime();
		$array = array(
			'time' => $time
		);
		$this->updateBackUpFilePath($array, $type);
	}
	private function getAllRows($table)
	{
		$query = 'SELECT * FROM '.$this->db->quoteKey($table);
		$this->db->setQuery($query);
		$results = $this->db->loadResultList();
		return $results;
	}
	private function getCreateTable($table)
	{
		$query = $this->getCreateTableFromDB ($table);
		$viewPattern = $this->getViewPattern();
		$constraintPattern = $this->getConstraintPattern();
		if (preg_match($viewPattern, $query, $matches) > 0)
		{
			return null;
		}
		else
		{
			if (preg_match($constraintPattern, $query, $matches) > 0)
			{
				$query = preg_replace($constraintPattern, "", $query);
			}
			$return = "--\n";
			$return .= "-- Table structure for ".$this->db->QuoteKey($table)."\n";
			$return .= "--\n\n";
			$return .= $query;
			$return .= ";\n\n";
			return $return;
		}
	}
	private function getCreateView($table)
	{
		$query = $this->getCreateTableFromDB ($table);
		$viewPattern = $this->getViewPattern();
		if (preg_match($viewPattern, $query, $matches) > 0)
		{
			$query = preg_replace($viewPattern, "CREATE VIEW", $query);
			$return = "--\n";
			$return .= "-- View structure for ".$this->db->QuoteKey($table)."\n";
			$return .= "--\n\n";
			$return .= $query;
			$return .= ";\n\n";
			return $return;
		}
		else
		{
			return null;
		}
	}
	private function getAlterTable($table)
	{
		$query = $this->getCreateTableFromDB ($table);
		$constraintPattern = $this->getConstraintPattern();
		if (preg_match_all($constraintPattern, $query, $matches) > 0)
		{
			$return = "--\n";
			$return .= "-- Alter table structure for ".$this->db->QuoteKey($table)."\n";
			$return .= "--\n\n";
			foreach ($matches as $match)
			{
				foreach ($match as $m)
				{
					$m = str_replace(array(",", ";", "\n"), "", $m);
					$return .= "ALTER TABLE ".$this->db->QuoteKey($table)." ADD ".$m.";\n";
				}
			}
			$return .= "\n\n";
			return $return;
		}
		else
		{
			return null;
		}
	}
	private function getInsertTable($table, $allRows)
	{
		$sql = "--\n";
		$sql .= "-- Dumping data for table ".$this->db->QuoteKey($table)."\n";
		$sql .= "--\n\n";
		$sql .= "INSERT INTO ".$this->db->QuoteKey($table);
		$sql .= $this->getColumns ($allRows[0]);
		$sql .= $this->getValues ($allRows);
		$sql .= "\n\n";
		return $sql;
	}
	private function getColumns($row)
	{
		$k = array();
		$i = 0;
		foreach ($row as $key => $value)
		{
			$k[$i] = $this->db->QuoteKey($key);
			$i++;
		}
		$return = " (".implode(", ", $k).") ";
		return $return;
	}

	private function countFile($path){
	    $size = 0;
	    $ignore = array('.','..','cgi-bin','.DS_Store');
	    $files = scandir($path);
	    foreach($files as $t) {
	        if(in_array($t, $ignore)) continue;
	        if (is_dir(rtrim($path, '/') . '/' . $t)) {
	            $size += $this->countFile(rtrim($path, '/') . '/' . $t);
	        } else {
	            $size++;
	        }   
	    }
	    return $size;
	}
	private function getValues($rows) {
		$varray = array (); 
		foreach ($rows as $row)
		{
			$v = array();
			$i = 0;
			foreach ($row as $key => $value)
			{
				if (is_null($value))
				{
					$v[$i] = 'NULL';
				}
				else
				{
					if (is_numeric($value))
					{
						$v[$i] = (int)$value;
					}
					else
					{
						$v[$i] = $this->db->QuoteValue($value);
					}
				}
				$i++;
			}
			$varray[] = "(".implode(", ", $v).")";
		}
		$return = " VALUES \n".implode(",\n", $varray).";";
		return $return;
	}
	private function getViewPattern()
	{
		return "/CREATE\s*ALGORITHM\=UNDEFINED\s*[\w|\=|\`|\@|\s]*.*?VIEW/ims";
	}
	private function getConstraintPattern()
	{
		return "/\,[CONSTRAINT|\s|\`|\w]+FOREIGN\s*KEY[\s|\`|\w|\(|\)]+ON\s*[UPDATE|DELETE]+\s*[RESTRICT|NO\s*ACTION|CASCADE|SET\s*NULL]+/ims";
	}
	private function getCreateTableFromDB($table)
	{
		$sql = 'SHOW CREATE TABLE '.$this->db->quoteKey($table);
		$this->db->setQuery($sql);
		$result = $this->db->loadResult();
		$tmp = array_values($result);
		return $tmp[1];
	}
	private function getTablesList()
	{
		return $this->db->getTableList ();
	}
	
	private function getBackUpFileNum()
	{
		$query = "SELECT `fileNum`, `fileTotal` FROM ".$this->db->QuoteTable($this->backupPathTable)." WHERE `id` = ".(int)$this->pathFile;
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		return $result;
	}
	
	public function backupFiles($step)
	{
		$fileName = null;
		$result = $this->getBackUpFileNum();
		$fileNum = $result['fileNum'];
		$fileTotal = $result['fileTotal'];
		if ($step < 0)
		{
			$this->clearTable();
			$this->setFilesFilename();
			$fileName = $this->getBackUpPath($this->pathFile);
			$fileName = $fileName['path'];
			$fileTotal = $this->countFile(OSE_DEFAULT_SCANPATH);
			$array = array(
				'fileTotal' => $fileTotal
			);
			$this->updateBackUpFilePath($array, $this->pathFile);
			$zip = new ZipArchive();
			$zip->open($fileName, ZIPARCHIVE::CREATE);
			$return = $this->getReturn(OSE_DEFAULT_SCANPATH, $zip);
			$return['fileNum'] = 0;
		    $return ['fileTotal'] = $fileTotal;
			$zip->close();
		}
		else
		{
			$fileName = $this->getBackUpPath($this->pathFile);
			$fileName = $fileName['path'];
			$zip = new ZipArchive();
			$zip->open($fileName, ZIPARCHIVE::CHECKCONS);
			$dirs = $this->getFolder(5);
			if (empty($dirs))
			{
				$return['cont']= false;
				$return['folders']= 0;
				$return['file']= 0;
				$return['fileNum'] = 0;
				$return ['fileTotal'] = 0;
			}
			else
			{
				$return = array();
				$return['folder'] = 0;
				$return['file'] = 0;
				foreach ($dirs as $dir)
				{
					$tmp = $this->getReturn($dir->filename, $zip);
					$return['folder'] += $tmp['folder'];
					$return['file'] += $tmp['file'];
					$return['cont'] = $tmp['cont'];
					$return['lastscanned'] = LAST_SCANNED.$dir->filename;
					$return ['fileNum'] = $fileNum;
					$return ['fileTotal'] = $fileTotal;
					$this->deletepathDB($dir->filename);
					unset($tmp);
				}
			}
		}
		if ($return['cont'] == true)
		{
			$return['summary'] = $fileNum.' '.BACKUP_FILES.' ';
		}
		else
		{
			$return['summary'] = OSE_ADDED.' '.OSE_INTOTAL.' '.$fileTotal.' '.OSE_FILES.'.';
		}
		return $return;
	}
	public function countFiles()
	{
		$query = "SELECT COUNT(`id`) as count FROM `".$this->filestable."`"." WHERE `type` = 'f'";
		$this->db->setQuery($query);
		$result = (object) $this->db->loadResult();
		return $result->count;
	}
	private function clearTable()
	{
		$query = "TRUNCATE TABLE ".$this->db->quoteTable($this->filestable);
		$this->db->setQuery($query);
		$result = $this->db->query();
		return $result;
	}
	public function getReturn($path, $zip)
	{
		$return = $this->getFolderFiles($path, $zip);
		$return['cont'] = $this->isFolderLeft();
		return $return;
	}
	private function getFolder($limit)
	{
		$query = "SELECT `filename` FROM `".$this->filestable."`"." WHERE `type` = 'd' LIMIT ".(int) $limit;
		$this->db->setQuery($query);
		$result = $this->db->loadObjectList();
		return $result;
	}
	private function deletepathDB($path)
	{
		$query = "DELETE FROM `".$this->filestable."` WHERE `type` = 'd' AND `filename` = ".$this->db->quoteValue ($path);
		$this->db->setQuery($query);
		return $this->db->query();
	}
	private function getFolderFiles($folder, $zip)
	{
		// Initialize variables
		$arr = array();
		$arr['folder'] = 0;
		$arr['file'] = 0;
		$false = false;
		if (!is_dir($folder))
			return $false;
		$handle = @opendir($folder);
		// If directory is not accessible, just return FALSE
		if ($handle === FALSE)
		{
			return $false;
		}
		$fileNum = $this->getBackUpFileNum();
		$fileNum = $fileNum ['fileNum'];
		while ((($file = @readdir($handle)) !== false))
		{
			if (($file != '.') && ($file != '..'))
			{
				$ds = ($folder == '') || ($folder == '/') || (@substr($folder, -1) == '/') || (@substr($folder, -1) == DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR;
				$dir = $folder.$ds.$file;
				$isDir = is_dir($dir);
				if ($isDir)
				{
					if ($dir != OSE_FWDATA.ODS.'backup' && !preg_match("/\/\.svn\//", $dir))
					{
						$arr['folder']++;
						$this->insertData($dir, 'd');
						$dir = str_replace(OSE_DEFAULT_SCANPATH, "", $dir);
						if (!empty($dir))
						{
							$zip->addEmptyDir($dir);
						}
					}
				}
				else
				{
					$fileext = $this->getExt($dir);
					$filesize = filesize($dir);
					$arr['file']++;
					$localfile = str_replace(OSE_DEFAULT_SCANPATH, "", $dir);
					$fileNum++;
					$zip->addFile($dir, $localfile);
				}
			}
		}
		$array = array(
			'fileNum' => $fileNum
		);
		$this->updateBackUpFilePath($array, $this->pathFile);
		@closedir($handle);
		return $arr;
	}
	private function getExt($file)
	{
		$dot = strrpos($file, '.') + 1;
		return substr($file, $dot);
	}
	private function insertData($filename, $type, $fileext = '')
	{
		$result = $this->getfromDB($filename, $type, $fileext);
		if (empty($result))
		{
			$this->insertInDB($filename, $type, $fileext);
		}
	}
	private function getfromDB($filename, $type, $fileext)
	{
		$query = "SELECT COUNT(`id`) as count "."FROM ".$this->db->quoteTable($this->filestable)." WHERE `filename` = ".$this->db->quoteValue($filename)." AND `type` = ".$this->db->quoteValue($type)." AND `ext` = ".$this->db->quoteValue($fileext);
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		return $result->count;
	}
	public function insertInDB($filename, $type, $fileext)
	{
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
	private function isFolderLeft()
	{
		$query = "SELECT COUNT(`id`) as count FROM ".$this->db->quoteTable($this->filestable)." WHERE `type` = 'd'";
		$this->db->setQuery($query);
		$result = (object) $this->db->loadObject();
		return $result->count;
	}
}
