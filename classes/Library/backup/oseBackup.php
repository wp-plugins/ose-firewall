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
if (! defined ( 'OSE_FRAMEWORK' ) && ! defined ( 'OSEFWDIR' ) && ! defined ( '_JEXEC' )) {
	die ( 'Direct Access Not Allowed' );
}
class oseBackupManager {
    const REQUEST_TOKEN_METHOD = 'oauth/request_token';
    const API_URL = 'https://api.dropbox.com/1/';
    const CONSUMER_KEY = 'ub6h6xc37diailz';
    const CONSUMER_SECRET = 'ra0tr51rah59vjf';
    private
        $dropbox,
        $request_token,
        $access_token,
        $oauth_state,
        $oauth,
        $account_info_cache,
        $directory_cache = array();
    private $backup_prefix = null;
	private $backup_type = null;
	private $pathDB = 2;
	private $pathFile = 1;
	private $filestable = '#__osefirewall_bkfiles';
	private $backuptable = '#__osefirewall_backup';
	private $backupPathTable = '#__osefirewall_backupath';
    private $configTable = '#__ose_secConfig';
    public $columns = array(
                        array('db' => 'id', 'dt' => 0),
                        array('db' => 'fileBackupPath', 'dt' => 1 ),
                        array('db' => 'dbBackupPath', 'dt' => 2 ),
                        array('db' => 'date', 'dt' => 3 ),
                        array('db' => 'type', 'dt' => 4 )
                    );
	public function __construct() {
		set_time_limit ( 60 );
		$this->setDBO ();
        $this->optimizePHP();
		oseFirewall::loadRequest ();
		oseFirewall::loadFiles ();
		$this->fileBackupName = "";
		oseFirewall::loadDateClass ();
        $this->alterBackupTable();
        $exits = $this->folderExits();
        if ($exits == false) {
            $this->createBackupFolder();
        }
	}

    /**
     * Alter "#__osefirewall_backup" table date column to include time
     */
    public function folderExits()
    {
        if (OSE_CMS == "wordpress") {
            if (file_exists(OSE_BACKUPPATH . ODS . 'CentroraBackup')) {
                return true;
            } else {
                return false;
            }
        } else {
            if (file_exists(OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup')) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function createBackupFolder()
    {
        if (OSE_CMS == "wordpress") {
            mkdir(OSE_BACKUPPATH . ODS . 'CentroraBackup');
        } else {
            mkdir(OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup');
        }
    }
    private function alterBackupTable() {
        $query = " SHOW FIELDS FROM $this->backuptable WHERE Field ='date' ";
        $this->db->setQuery($query);
        $results = $this->db->loadResult();

        if ($results['Type'] == 'date') {
            $query = "ALTER TABLE `#__osefirewall_backup` CHANGE `date` `date` DATETIME NOT NULL ";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
	protected function setDBO() {
		$this->db = oseFirewall::getDBO ();
	}

    public function oauth($type, $reload = null){
        require_once dirname(__FILE__) . '/oauthCurl.php';
        if ($type == "dropbox") {
            $return = $this->dropbox_oauth_start($reload);
        }
        return $return;
    }

    public function dropbox_oauth_start($reload){
        $return = "";
        $this->oauth = new oauthCurl(self::CONSUMER_KEY, self::CONSUMER_SECRET);
        $this->oauth_state = $this->get_option('oauth_state');

        $this->request_token = $this->get_token('request');
        if ($reload == 'yes') {
            $this->request_token = $this->oauth->getRequestToken();
            $this->oauth->setToken($this->request_token);
            $this->oauth_state = 'request';
            $this->save_tokens();
            $return = $this->get_authorize_url();
        } else {
            if ($this->oauth_state == 'request') {
                try {
                    $this->oauth->setToken($this->request_token);
                    $this->access_token = $this->oauth->getAccessToken();
                    $this->oauth_state = 'access';
                    $this->oauth->setToken($this->access_token);
                    $this->save_tokens();
                    return OSE_CMS;
                } catch (Exception $e) {
                }
            } else {
                //If we don't have an acess token then lets setup a new request
                $this->request_token = $this->oauth->getRequestToken();
                $this->oauth->setToken($this->request_token);
                $this->oauth_state = 'request';
                $this->save_tokens();
                $return = $this->get_authorize_url();
            }
        }
        return $return;
    }

    public function get_authorize_url(){
        return $this->oauth->getAuthoriseUrl();
    }

    public function set_option($name, $value){
        //Short circut if not changed
        if ($this->get_option($name) === $value) {
            return $this;
        }
        $query = "SELECT `key` FROM `#__ose_secConfig` WHERE `key` LIKE '" . $name . "'";
        $this->db->setQuery($query);

        $flag = $this->db->loadResult();

        if (empty ($flag)) {

            $query = "INSERT INTO `#__ose_secConfig`(`key`,`value`,`type`) VALUES ('" . $name . "','" . $value . "','dropbox')";

            $this->db->setQuery($query);

            $this->db->query();
        } else {
            $query = "UPDATE `#__ose_secConfig` SET `type` = 'dropbox' `value`='" . $value . "' WHERE `key` LIKE '" . $name . "'";

            $this->db->setQuery($query);

            $this->db->query();
        }
        return $this;
    }

    public function get_option($name){
        $query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` LIKE '" . $name . "'";
        $this->db->setQuery($query);
        $results = $this->db->loadResult();

        return $results['value'];
    }

    private function save_tokens(){
        $this->set_option('oauth_state', $this->oauth_state);
        if ($this->request_token) {
            $this->set_option('request_token', $this->request_token->oauth_token);
            $this->set_option('request_token_secret', $this->request_token->oauth_token_secret);
        } else {
            $this->set_option('request_token', null);
            $this->set_option('request_token_secret', null);
        }

        if ($this->access_token) {
            $this->set_option('access_token', $this->access_token->oauth_token);
            $this->set_option('access_token_secret', $this->access_token->oauth_token_secret);
        } else {
            $this->set_option('access_token', null);
            $this->set_option('access_token_secret', null);
        }

        return $this;
    }

    private function get_token($type){
        $token = $this->get_option("{$type}_token");
        $token_secret = $this->get_option("{$type}_token_secret");

        $ret = new stdClass;
        $ret->oauth_token = null;
        $ret->oauth_token_secret = null;

        if ($token && $token_secret) {
            $ret = new stdClass;
            $ret->oauth_token = $token;
            $ret->oauth_token_secret = $token_secret;
        }

        return $ret;
    }

    public function is_authorized(){

        $flag = $this->newcheck();

        return $flag;

    }

    public function newcheck(){
        $access_token = $this->get_option("access_token");
        $access_token_secret = $this->get_option("access_token_secret");
        if (isset($access_token) && $access_token && isset($access_token_secret) && $access_token_secret) {
            return "ok";
        } else {
            return "fail";
        }
    }

    public function get_account_info(){
        require_once dirname(__FILE__) . '/oauthCurl.php';
        $this->oauth = new oauthCurl(self::CONSUMER_KEY, self::CONSUMER_SECRET);
        $this->access_token = $this->get_token('access');

        $this->oauth->setToken($this->access_token);
        try {
            if (!isset($this->account_info_cache)) {
                $this->dropbox = new Dropbox_API($this->oauth);
                $response = $this->dropbox->accountInfo();
                $this->account_info_cache = $response['body'];
            }
            return $this->account_info_cache;
        } catch (Exception $e) {

        }
    }

    public function dropbox_upload($id){
        require_once dirname(__FILE__) . '/oauthCurl.php';
        $this->oauth = new oauthCurl(self::CONSUMER_KEY, self::CONSUMER_SECRET);
        $this->oauth_state = $this->get_option('oauth_state');
        $this->access_token = $this->get_token('access');
        $exits = false;
        try {
            if ($this->oauth_state == 'access') {
                $this->oauth->setToken($this->access_token);
                $this->dropbox = new Dropbox_API($this->oauth);


                $currentDomain = $_SERVER['HTTP_HOST'];
                $currentDomain = preg_replace('/[:\/;*<>|?]/', '', $currentDomain);


                $check = $this->dropbox->metaData();
                $checkdeep = $check['body']->contents;
                foreach ($checkdeep as $key => $single) {
                    if ($single->path == '/' . $currentDomain) {
                        $exits = true;
                    }
                }
                if ($exits == false) {
                    $this->dropbox->create($currentDomain);
                }


                $file = $this->getBackupDBByID($id);
                $filename = basename($file);
                $stream = fopen($file, 'r');
                $this->dropbox->putStream($stream, $filename, $currentDomain);
                return true;
            } elseif ($this->oauth_state == 'request') {
                return "Account not authenticated";
            } else {
                return $this->oauth_state;
            }
        } catch (Exception $e) {
            return $e;
        }

    }
	public function backup($backup_type, $backup_to) {
        try {
            if ($backup_type == 1) {
                $backupResult = $this->backupFiles ( $backup_type, $backup_to );
                if ($backup_type == 3 || $backup_type == 1) {
                    if ($backupResult == true) {
                        $result = $this->insertbkDB($backup_type, $backup_to);
                    }
                    else {
                        $result = false;
                    }
                }
            }
            else {
                $backupResult = $this->backupDB ( $backup_type, $backup_to );
                if ($backupResult == false) {
                    if ($backup_to == 1) {
                        $result = false;
                    }
                    else if ($backup_to == 2) {
                        $result = false;
                    }
                }
                else if ($backupResult == true && $backup_type != 3) {
                    $result = $this->insertbkDB($backup_type, $backup_to);
                }
                else {
                    $result = true;
                }
            }
            return $result;
        } catch (Exception $e) {
            return $e;
        }
	}
	private static function isCurrentUser() {
		oseFirewall::loadUsers ();
		$oUser = new oseUsers ( "ose_firewall" );
		return ( boolean ) $oUser->isAdmin ();
	}
	private function saveBackUpPath($path, $type) {
		$result = $this->getBackUpPath ( $type );
		$result = $result ["path"];
		if (empty ( $result )) {
			$array = array (
					'path' => $path
			);
			$this->updateBackUpFilePath ( $array, $type );
		}
	}
	private function getBackUpPath($backup_type) {
		$query = "SELECT `path`, `time` FROM `#__osefirewall_backupath` WHERE `id` = " . ( int ) $backup_type;
		$this->db->setQuery ( $query );
		$results = $this->db->loadResult ();
		return $results;
	}
	public function cleanBackUpFilePath() {
		for($i = 1; $i < 3; $i ++) {
			$array = array (
					'path' => null,
					'time' => '',
					'fileNum' => 0,
					'fileTotal' => 0
			);
			$this->updateBackUpFilePath ( $array, $i );
		}
		$this->db->closeDBO ();
	}
	private function checkDBReady($dbName) {
		$query = "SELECT COUNT(`id`) as `count` FROM " . $this->db->QuoteTable ( $dbName );
		$this->db->setQuery ( $query );
		$result = $this->db->loadResult ();
		return ($result ['count'] > 0) ? true : false;
	}
	private function updateBackUpFilePath($array, $type) {
		$numItems = count ( $array );
		$i = 0;
		$dbReady = $this->checkDBReady ( $this->backupPathTable );
		if (! $dbReady) {
			$query = "INSERT INTO " . $this->db->QuoteTable ( $this->backupPathTable ) . " VALUES (1,NULL,'0000-00-00',0,0)";
			$this->db->setQuery ( $query );
			$this->db->query ();
			$query = "INSERT INTO " . $this->db->QuoteTable ( $this->backupPathTable ) . " VALUES (2,NULL,'0000-00-00',0,0)";
			$this->db->setQuery ( $query );
			$this->db->query ();
		}
		$query = "UPDATE " . $this->db->QuoteTable ( $this->backupPathTable ) . " SET ";
		foreach ( $array as $k => $v ) {
			$query .= $this->db->quoteKey ( $k );
			$query .= " = " . $this->db->quoteValue ( $v );
			if (++ $i < $numItems) {
				$query .= ", ";
			}
		}
		$query .= " WHERE id = " . ( int ) $type;
		$this->db->setQuery ( $query );
		$this->db->query ();
	}
	protected static function getMimeType($file_extension) {
		$known_mime_types = array (
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
		if (array_key_exists ( $file_extension, $known_mime_types )) {
			$mime_type = $known_mime_types [$file_extension];
		}
		else {
			$mime_type = "application/force-download";
		}
		return $mime_type;
	}
	protected static function getExtension($file) {
		return strtolower ( substr ( strrchr ( $file, "." ), 1 ) );
	}
	protected static function getDownloadFilename ($filePath) {
        /*if (OSE_CMS == "wordpress") {
            $path_prefix = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS;
        } else {
            $path_prefix = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS;

        }
		return str_replace ( $path_prefix, "", $filePath );*/
        return pathinfo($filePath)['basename'];
	}
	public static function downloadBackupFile () {
		$id = oRequest :: getInt('id', 0);
		if (self::isCurrentUser () == true) {
			$file = self::getBackupDBByID ( $id );
			if (! is_readable ( $file )) {
				die ( 'File not found or inaccessible!' );
			}
			$file_extension = self::getExtension ( $file );
			$file_name = self::getDownloadFilename ( $file );
			$size = filesize ( $file );
			/* Figure out the MIME type | Check in array */
			$mime_type = self::getMimeType($file_extension);
			$expireTime = self::getExpireTime ();

			// Download Now
			self::downloadFileAction ($file, $file_name, $mime_type, $expireTime, $size);

			die ();
		}
		else {
			print ("<SCRIPT type='text/javascript'>") ;
			print ("alert('You do not have permission to download the file.');") ;
			print ("window.location = '" . OSE_WPURL . "';") ;
			print ("</SCRIPT>") ;
		}
    }
	protected function downloadFileAction ($tar_path, $file_name, $mime_type, $expireTime, $size) {
		// turn off output buffering to decrease cpu usage
		// required for IE, otherwise Content-Disposition may be ignored
		if (ini_get ( 'zlib.output_compression' ))
			ini_set ( 'zlib.output_compression', 'Off' );
		header ( 'Content-Type: ' . $mime_type );
		header ( "Content-Transfer-Encoding: binary" );
		header ( 'Content-Disposition: attachment; filename="' . $file_name . '"' );
		header ( 'Accept-Ranges: bytes' );
		/* The three lines below basically make the download non-cacheable */
		header ( "Cache-control: private" );
		header ( 'Pragma: private' );
		header ( "Expires: " . $expireTime );

        ob_clean();
		flush();
		// multipart-download and download resuming support

        $r_fh = fopen($tar_path,'r');
		while(feof($r_fh) === false) {
			$s_part = fread($r_fh,10240);
			echo $s_part;
		}

        fclose($r_fh);

        exit;
	}
	private static function getExpireTime() {
		oseFirewall::loadDateClass();
		$oseDatetime = new oseDatetime ();
		$oseDatetime->setFormat ( "D, d m Y H:i:s " );
		$timeZone = $oseDatetime->getTimeZonePub ();
		$time = $oseDatetime->getDateTime () . " " . $timeZone;
		return $time;
	}
	private function assembleArray($result, $status, $msg, $continue, $id) {
		$return = array (
				'success' => ( boolean ) $result,
				'status' => $status,
				'result' => $msg,
				'cont' => ( boolean ) $continue,
				'id' => ( int ) $id
		);
		return $return;
	}
	public function getBackupList() {
		$data = $this->getBackupDB ();
		$number = $this->getBackupTotal ();
		$post_draw = oRequest::getInt ( 'draw' );
		$result = array (
				"draw" => $post_draw,
				"recordsTotal" => $number,
				"recordsFiltered" => $number,
				"data" => $data
		);
		return $result;
	}
	public function getLimit() {
		$limit = '';
		$post_start = oRequest::getInt ( 'start' );
		$post_length = oRequest::getInt ( 'length' );
		if (isset ( $post_start ) && $post_length != - 1) {
			$limit = "LIMIT " . $post_start . ", " . $post_length;
        } elseif ($post_length == -1) {
            $post_length = 5;
            $limit = "LIMIT " . $post_start . ", " . $post_length;
        }
		return $limit;
	}
	public function getOrder() {
		$order = '';
		$post_order = oRequest::getVar ( 'order' );
		$post_columns = oRequest::getVar ( 'columns' );
		if (isset ( $post_order ) && count ( $post_order )) {
			$orderBy = array ();
			for($i = 0, $ien = count ( $post_order ); $i < $ien; $i ++) {
				$columnIdx = intval ( $post_order [$i] ['column'] );
				$requestColumn = $post_columns [$columnIdx];
				$column = $this->columns [$i];
				if ($requestColumn ['orderable'] == 'true') {
					$dir = $post_order [$i] ['dir'] === 'asc' ? 'ASC' : 'DESC';
					$orderBy [$i] = '`' . $column ['db'] . '` ' . $dir;
				}
			}
			$order = 'ORDER BY ' . implode ( ', ', $orderBy );
		}
		return $order;
	}
	public function getWhere() {
		$where = '';
		$post_search = oRequest::getVar ( 'search' );
		$post_columns = oRequest::getVar ( 'columns' );
		$globalSearch = array ();
		if (isset ( $post_search ) && $post_search ['value'] != '') {
			$str = $post_search ['value'];
			for($i = 0, $ien = count ( $post_columns ); $i < $ien - 1; $i ++) {
				$requestColumn = $post_columns [$i];
				$column = $this->columns [$i];
				if ($requestColumn ['searchable'] == true) {
					$newstr = "'" . '%' . $str . '%' . "'";
					$globalSearch [$i] = "`" . $column ['db'] . "` LIKE " . $newstr;
				}
			}
		}
		// Combine the filters into a single string
		if (count ( $globalSearch )) {
			$where = '(' . implode ( ' OR ', $globalSearch ) . ')';
		}
		if ($where !== '') {
			$where = 'WHERE ' . $where;
		}
		return $where;
	}
	public function getBackupTotal() {
		$db = oseFirewall::getDBO ();
		$result = $db->getTotalNumber ( 'id', '#__osefirewall_backup' );
		$db->closeDBO ();
		return $result;
	}
	protected function clearIDs ($ids) {
		$i=0;
		foreach ($ids as $id) {
			$ids[$i] = (int)$id;
			$i++;
		}
		return $ids;
	}
	protected function getDBListbyIDs($ids) {
		$db = oseFirewall::getDBO ();
		$ids = $this->clearIDs ($ids);
		$range = '(' . implode ( ',', $ids ) . ')';
		$query = "SELECT * FROM `#__osefirewall_backup` WHERE `id` IN $range";
		$db->setQuery ( $query );
		$results = $db->loadObjectList ();
		$db->closeDBO ();
		return $results;
	}
	public function deleteBackUp($ids) {
		oseFirewall::loadFiles ();
		$results = $this->getDBListbyIDs($ids);
		$result = false;
		foreach ( $results as $token ) {
			if (! empty ( $token->dbBackupPath )) {
				$result = osefile::delete ( $token->dbBackupPath );
			}
			if (! empty ( $token->fileBackupPath )) {
				$result = osefile::delete ( $token->fileBackupPath );
			}
			if (! empty ( $token->id )) {
				$result = $this->deleteBackupID ( $token->id );
			}
		}
		return $result;
	}
	private function deleteBackupID($id) {
		$db = oseFirewall::getDBO ();
		$result = $db->deleteRecord ( array (
				'id' => $id
		), '#__osefirewall_backup' );
		$db->closeDBO ();
		return $result;
	}
	public function getBackupDB() {
		$limit = $this->getLimit ();
		$order = $this->getOrder ();
		$where = $this->getWhere ();
		$db = oseFirewall::getDBO ();
		$query = "SELECT * FROM `#__osefirewall_backup` $where $order $limit";
		$db->setQuery ( $query );
		$results = $db->loadObjectList ();
		$db->closeDBO ();
		return $this->convertResultsList($results);
	}
	protected function convertResultsList($results) {
		$return = array ();
		$i = 0;
        $controller = oRequest:: getVar('controller');
        $url = BACKUP_DOWNLOAD_URL;
        if ($controller == "backup") {
            foreach ($results as $file) {
                $local = "<a href='." . $url . $file->id . "' target ='_blank'><div class='fa fa-cloud-download'></div></a>";
                $filePath = (!empty($file->fileBackupPath)) ? $file->fileBackupPath : $file->dbBackupPath;
                $fileType = (!empty($file->fileBackupPath)) ? "<div class='fa fa-file-archive-o'></div>" : "<div class='fa fa-database'></div>";
                $filename = self::getDownloadFilename($filePath);
                $return [$i] = array(
                    "ID" => $file->id,
                    "fileType" => $fileType,
                    "fileName" => $filename,
                    "time" => $file->date,
                    "downloadLink" => $local
                );
                $i++;
            }
        } else {
            foreach ($results as $file) {
                $filePath = (!empty($file->fileBackupPath)) ? $file->fileBackupPath : $file->dbBackupPath;
                $fileType = (!empty($file->fileBackupPath)) ? "<div class='fa fa-file-archive-o'></div>" : "<div class='fa fa-database'></div>";
                $filename = self::getDownloadFilename($filePath);
                $return [$i] = array(
                    "ID" => $file->id,
                    "fileType" => $fileType,
                    "fileName" => $filename,
                    "time" => $file->date,
                );
                $i++;
            }
        }
		return $return;
	}
	public static function getBackupDBByID($id) {
		$db = oseFirewall::getDBO ();
		$query = "SELECT * FROM `#__osefirewall_backup` WHERE `id` = " . ( int ) $id;
		$db->setQuery ( $query );
		$result = $db->loadObject ();
		$db->closeDBO ();
		if (! empty ( $result->fileBackupPath )) {
			return $result->fileBackupPath;
		}
		else {
			return $result->dbBackupPath;
		}
	}

	public function insertbkDB($backup_type, $backup_to) {
		$dbPath = null;
		$filePath = null;
		$time = null;
		if ($backup_type == 1) {
			$result = $this->getBackUpPath ( $backup_type );
			$filePath = $result ['path'];
			$time = $result ['time'];
		}
		else if ($backup_type == 2) {
			$result = $this->getBackUpPath ( $backup_type );
			$dbPath = $result ['path'].'.gz';
			$time = $result ['time'];
		}
		else {
			$result = $this->getBackUpPath ( 1 );
			$dbPath = $result ['path'];
			$time = $result ['time'];
			$result = $this->getBackUpPath ( 2 );
			$filePath = $result ['path'];
		}

        $results = $this->insertInBackupDB ( $time, $backup_to, $dbPath, $filePath );
		$this->cleanBackUpFilePath ();
		return $results;
	}
	private function insertInBackupDB($time, $backup_to, $dbPath, $filePath) {
		$varValues = array (
				0 => array (
						'date' => $time,
						'type' => 0,
						'dbBackupPath' => $dbPath,
						'fileBackupPath' => $filePath,
						'server' => $backup_to
				)
		);
		$query = $this->getInsertTable ( '#__osefirewall_backup', $varValues );
		$this->db->setQuery ( $query );
        $this->db->query();
        $results = $this->db->getlastinert();
        return (int)$results;
	}

    private function optimizePHP()
    {
        if (function_exists('ini_set')) {
            ini_set('max_execution_time', 300);
            ini_set('memory_limit', '1024M');
            ini_set("pcre.recursion_limit", "524");
        }
    }
	private function backupDatabase() {
		$backupFile = $this->setDBFilename ();
		$tables = $this->getTablesList ();
		$sql = "";
		// Get All Create Table Queries
		foreach ( $tables as $key => $table ) {
			$createTableQuery = $this->getCreateTable ( $table );
			if (! empty ( $createTableQuery )) {
				$sql .= $createTableQuery;
				$allRows = $this->getAllRows ( $table );
				if (! empty ( $allRows )) {
					$sql .= $this->getInsertTable ( $table, $allRows );
				}
			}
		}
		// Get All Create View Queries
		foreach ( $tables as $key => $table ) {
			$createViewQuery = $this->getCreateView ( $table );
			if (! empty ( $createViewQuery )) {
				$sql .= $createViewQuery;
			}
		}
		// Get All Alter Table Queries
		foreach ( $tables as $key => $table ) {
			$alterTableQuery = $this->getAlterTable ( $table );
			if (! empty ( $alterTableQuery )) {
				$sql .= $alterTableQuery;
			}
		}
		$handle = @gzopen ( $backupFile . '.gz', 'wb9' );
		if (! empty ( $handle )) {
			gzwrite ( $handle, $sql );
			gzclose ( $handle );
			return $backupFile . '.gz';
		}
		else {
			return false;
		}
	}
	private function setDBFilename() {
		$oseDatetime = new oseDatetime ();
		$oseDatetime->setFormat ( "Ymd_His" );
		$time = $oseDatetime->getDateTime ();
        if (OSE_CMS == "wordpress") {
            $fileName = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "dbbackup-" . $time . ".sql";
        } else {
            $fileName = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "dbbackup-" . $time . ".sql";
        }

		$this->saveBackUpPath ( $fileName, $this->pathDB );
		$this->setDBTime ( $this->pathDB );
		return $fileName;
	}
	private function setFilesFilename() {
		$oseDatetime = new oseDatetime ();
		$oseDatetime->setFormat ( "Ymd_His" );
		$time = $oseDatetime->getDateTime ();
        if (OSE_CMS == 'wordpress') {
            $fileName = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "filesbackup-" . $time . ".zip";
        } else {
            $fileName = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "filesbackup-" . $time . ".zip";
        }

		$this->saveBackUpPath ( $fileName, $this->pathFile );
		$this->setDBTime ( $this->pathFile );
		return $fileName;
	}
	private function setDBTime($type) {
		$oseDatetime = new oseDatetime ();
		$oseDatetime->setFormat ( "Y-m-d H:i:s" );
		$time = $oseDatetime->getDateTime ();
		$array = array (
				'time' => $time
		);
		$this->updateBackUpFilePath ( $array, $type );
	}
	private function getAllRows($table) {
		$query = 'SELECT * FROM ' . $this->db->quoteKey ( $table );
		$this->db->setQuery ( $query );
		$results = $this->db->loadResultList ();
		return $results;
	}
	private function getCreateTable($table) {
		$query = $this->getCreateTableFromDB ( $table );
		$viewPattern = $this->getViewPattern ();
		$constraintPattern = $this->getConstraintPattern ();
		if (preg_match ( $viewPattern, $query, $matches ) > 0) {
			return null;
		}
		else {
			if (preg_match ( $constraintPattern, $query, $matches ) > 0) {
				$query = preg_replace ( $constraintPattern, "", $query );
			}
			$return = "--\n";
			$return .= "-- Table structure for " . $this->db->QuoteKey ( $table ) . "\n";
			$return .= "--\n\n";
			$return .= $query;
			$return .= ";\n\n";
			return $return;
		}
	}
	private function getCreateView($table) {
		$query = $this->getCreateTableFromDB ( $table );
		$viewPattern = $this->getViewPattern ();
		if (preg_match ( $viewPattern, $query, $matches ) > 0) {
			$query = preg_replace ( $viewPattern, "CREATE VIEW", $query );
			$return = "--\n";
			$return .= "-- View structure for " . $this->db->QuoteKey ( $table ) . "\n";
			$return .= "--\n\n";
			$return .= $query;
			$return .= ";\n\n";
			return $return;
		}
		else {
			return null;
		}
	}
	private function getAlterTable($table) {
		$query = $this->getCreateTableFromDB ( $table );
		$constraintPattern = $this->getConstraintPattern ();
		if (preg_match_all ( $constraintPattern, $query, $matches ) > 0) {
			$return = "--\n";
			$return .= "-- Alter table structure for " . $this->db->QuoteKey ( $table ) . "\n";
			$return .= "--\n\n";
			foreach ( $matches as $match ) {
				foreach ( $match as $m ) {
					$m = str_replace ( array (
							",",
							";",
							"\n"
					), "", $m );
					$return .= "ALTER TABLE " . $this->db->QuoteKey ( $table ) . " ADD " . $m . ";\n";
				}
			}
			$return .= "\n\n";
			return $return;
		}
		else {
			return null;
		}
	}
	private function getInsertTable($table, $allRows) {
		$sql = "--\n";
		$sql .= "-- Dumping data for table " . $this->db->QuoteKey ( $table ) . "\n";
		$sql .= "--\n\n";
		$sql .= "INSERT INTO " . $this->db->QuoteKey ( $table );
		$sql .= $this->getColumns ( $allRows [0] );
		$sql .= $this->getValues ( $allRows );
		$sql .= "\n\n";
		return $sql;
	}
	private function getColumns($row) {
		$k = array ();
		$i = 0;
		foreach ( $row as $key => $value ) {
			$k [$i] = $this->db->QuoteKey ( $key );
			$i ++;
		}
		$return = " (" . implode ( ", ", $k ) . ") ";
		return $return;
	}
	private function countFile($path) {
		$size = 0;
		$ignore = array (
				'.',
				'..',
				'cgi-bin',
				'.DS_Store'
		);
		$files = scandir ( $path );
		foreach ( $files as $t ) {
			if (in_array ( $t, $ignore ))
				continue;
			if (is_dir ( rtrim ( $path, '/' ) . '/' . $t )) {
				$size += $this->countFile ( rtrim ( $path, '/' ) . '/' . $t );
			}
			else {
				$size ++;
			}
		}
		return $size;
	}
	private function getValues($rows) {
		$varray = array ();
		foreach ( $rows as $row ) {
			$v = array ();
			$i = 0;
			foreach ( $row as $key => $value ) {
				if (is_null ( $value )) {
					$v [$i] = 'NULL';
				}
				else {
					if (is_numeric ( $value )) {
						$v [$i] = ( int ) $value;
					}
					else {
						$v [$i] = $this->db->QuoteValue ( $value );
					}
				}
				$i ++;
			}
			$varray [] = "(" . implode ( ", ", $v ) . ")";
		}
		$return = " VALUES \n" . implode ( ",\n", $varray ) . ";";
		return $return;
	}
	private function getViewPattern() {
		return "/CREATE\s*ALGORITHM\=UNDEFINED\s*[\w|\=|\`|\@|\s]*.*?VIEW/ims";
	}
	private function getConstraintPattern() {
		return "/\,[CONSTRAINT|\s|\`|\w]+FOREIGN\s*KEY[\s|\`|\w|\(|\)]+ON\s*[UPDATE|DELETE]+\s*[RESTRICT|NO\s*ACTION|CASCADE|SET\s*NULL]+/ims";
	}
	private function getCreateTableFromDB($table) {
		$sql = 'SHOW CREATE TABLE ' . $this->db->quoteKey ( $table );
		$this->db->setQuery ( $sql );
		$result = $this->db->loadResult ();
		$tmp = array_values ( $result );
		return $tmp [1];
	}
	private function getTablesList() {
		return $this->db->getTableList ();
	}
	private function getBackUpFileNum($backup_type) {
		$query = "SELECT `fileNum`, `fileTotal` FROM " . "`#__osefirewall_backupath`" . " WHERE `id` = " . ( int ) $backup_type;
		$this->db->setQuery ( $query );
		$result = $this->db->loadResult ();
		return $result;
	}
	public function backupFiles($backup_type, $backup_to) {
		$fileName = null;
		$this->setFilesFilename ();
		$fileName = $this->getBackUpPath ( $backup_type );
		$fileName = $fileName ['path'];
		$this->zipDir ( OSE_ABSPATH, $fileName );
		return true;
	}
	public function countFiles() {
		$query = "SELECT COUNT(`id`) as count FROM `" . $this->filestable . "`" . " WHERE `type` = 'f'";
		$this->db->setQuery ( $query );
		$result = ( object ) $this->db->loadResult ();
		return $result->count;
	}
	private function clearTable() {
		$query = "TRUNCATE TABLE " . $this->db->quoteTable ( $this->filestable );
		$this->db->setQuery ( $query );
		$result = $this->db->query ();
		return $result;
	}

    public function getReturn($path, $zip, $backup_type)
    {
        $return = $this->getFolderFiles($path, $zip, $backup_type);
		$return ['cont'] = $this->isFolderLeft ();
		return $return;
	}
	private function getFolder($limit) {
		$query = "SELECT `filename` FROM `" . $this->filestable . "`" . " WHERE `type` = 'd' LIMIT " . ( int ) $limit;
		$this->db->setQuery ( $query );
		$result = $this->db->loadObjectList ();
		return $result;
	}
	private function deletepathDB($path) {
		$query = "DELETE FROM `" . $this->filestable . "` WHERE `type` = 'd' AND `filename` = " . $this->db->quoteValue ( $path );
		$this->db->setQuery ( $query );
		return $this->db->query ();
	}
	/**
	 * Zip a folder (include itself).
	 * Usage:
	 * zipDir('/path/to/sourceDir', '/path/to/out.zip');
	 *
	 * @param string $sourcePath
	 *        	Path of directory to be zip.
	 * @param string $outZipPath
	 *        	Path of output zip file.
	 */
	private function zipDir($sourcePath, $outZipPath) {
		$pathInfo = pathInfo ( $sourcePath );
        if (OSE_CMS == "wordpress") {
            $path_prefix = OSE_BACKUPPATH . ODS . 'CentroraBackup';
        } else {
            $path_prefix = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup';
        }
        $excludearray = array(
            'folders' => array(str_replace(ODS . ODS, ODS, OSE_FWDATA . ODS . "backup")
                            , str_replace(ODS . ODS, ODS,$path_prefix)),
            'files' => array(str_replace(ODS . ODS, ODS, OSE_FWDATA . ODS . 'atest.sql'))
        );
		ini_set("display_errors", 'on');
		if (function_exists('ini_set')) {
			$this->enableSystemFunction ();
		}
		if(!file_exists ($outZipPath)) {
            if (function_exists('system')) {
                $this->zipWithSystemZip($pathInfo, $outZipPath, $sourcePath, $excludearray);
            } else {
                $this->zipPHPArchive($pathInfo, $outZipPath, $sourcePath, $excludearray);
            }
        }
	}
	private function enableSystemFunction () {
		$disabled = ini_get('disable_functions');
		$disabledArray = explode(",", $disabled);
		foreach ($disabledArray as $key => $val) {
			if (empty($val) || $val == 'system')
			{
				unset($disabledArray[$key]);
			}
		}
		$disabled = implode(",", $disabledArray);
		ini_set('disable_functions', $disabled);
	}
	private function zipWithSystemZip ($pathInfo, $outZipPath, $sourcePath, $excludearray) {
        $excludefiles = implode(' ', $excludearray['files']);
        $excludefolders = null;
        foreach ($excludearray['folders'] as $tmp) {
            $excludefolders .= $tmp . '**\* ';
        }
        ob_start();
        system('zip -r ' . $outZipPath . ' ' . $sourcePath . ' -x ' . $excludefolders . ' ' . $excludefiles);
        $result = ob_get_contents();
        //Check that Zip is installed otherwise run zipPHPArchive
        If (empty($result)) {
            $this->zipPHPArchive($pathInfo, $outZipPath, $sourcePath, $excludearray);
        }
        ob_end_clean();
	}
	private function zipPHPArchive($pathInfo, $outZipPath, $sourcePath, $excludearray) {
		$parentPath = $pathInfo ['dirname'];
        $dirName = $pathInfo ['basename'];
        $exclusiveLength = strlen ( "$parentPath/" );
		ini_set("memory_limit", "1024M");
		ini_set("max_execution_time", "60");
		ini_set("realpath_cache_size", "1024k");
		$z = new ZipArchive ();
		$z->open ( $outZipPath, ZIPARCHIVE::CREATE );
        $z->addEmptyDir ( $dirName );
        $this->folderToZip ( $sourcePath, $z, $exclusiveLength, $excludearray );
		$z->close();
	}
    private function folderToZip($folder, &$zipFile, $exclusiveLength, $excludearray) {
        $handle = opendir ( $folder );
        while ( false !== ($f = readdir ( $handle )) ) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                $localPath = substr ( $filePath, $exclusiveLength );
                if (is_file ( $filePath ) && (!in_array($filePath, $excludearray['files']))) {
                    $zipFile->addFile ( $filePath, $localPath );
                }
                elseif (is_dir ( $filePath ) && (!in_array($filePath, $excludearray['folders']))) {
                    // Add sub-directory.
                    $zipFile->addEmptyDir ( $localPath );
                    self::folderToZip ( $filePath, $zipFile, $exclusiveLength, $excludearray);
                }
            }
        }
        closedir ( $handle );
    }

    private function getFolderFiles($folder, $zip, $backup_type){
		// Initialize variables
		$arr = array ();
		$arr ['folder'] = 0;
		$arr ['file'] = 0;
		$false = false;
		if (! is_dir ( $folder ))
			return $false;
		$handle = @opendir ( $folder );
		// If directory is not accessible, just return FALSE
		if ($handle === FALSE) {
			return $false;
		}
        $fileNum = $this->getBackUpFileNum($backup_type);
		$fileNum = $fileNum ['fileNum'];
		while ( (($file = @readdir ( $handle )) !== false) ) {
			if (($file != '.') && ($file != '..')) {
				$ds = ($folder == '') || ($folder == '/') || (@substr ( $folder, - 1 ) == '/') || (@substr ( $folder, - 1 ) == DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR;
				$dir = $folder . $ds . $file;
				$isDir = is_dir ( $dir );
				if ($isDir) {
					if ($dir != OSE_FWDATA . ODS . 'backup' && ! preg_match ( "/\/\.svn\//", $dir )) {
						$arr ['folder'] ++;
						$this->insertData ( $dir, 'd' );
						$dir = str_replace ( OSE_DEFAULT_SCANPATH, "", $dir );
						if (! empty ( $dir )) {
							$zip->addEmptyDir ( $dir );
						}
					}
				}
				else {
					$fileext = $this->getExt ( $dir );
					$filesize = filesize ( $dir );
					$arr ['file'] ++;
					$localfile = str_replace ( OSE_DEFAULT_SCANPATH, "", $dir );
					$fileNum ++;
					$zip->addFile ( $dir, $localfile );
				}
			}
		}
		$array = array (
				'fileNum' => $fileNum
		);
		$this->updateBackUpFilePath ( $array, $this->pathFile );
		@closedir ( $handle );
		return $arr;
	}
	private function getExt($file) {
		$dot = strrpos ( $file, '.' ) + 1;
		return substr ( $file, $dot );
	}
	private function insertData($filename, $type, $fileext = '') {
		$result = $this->getfromDB ( $filename, $type, $fileext );
		if (empty ( $result )) {
			$this->insertInDB ( $filename, $type, $fileext );
		}
	}
	private function getfromDB($filename, $type, $fileext) {
		$query = "SELECT COUNT(`id`) as count " . "FROM " . $this->db->quoteTable ( $this->filestable ) . " WHERE `filename` = " . $this->db->quoteValue ( $filename ) . " AND `type` = " . $this->db->quoteValue ( $type ) . " AND `ext` = " . $this->db->quoteValue ( $fileext );
		$this->db->setQuery ( $query );
		$result = $this->db->loadObject ();
		return $result->count;
	}
	public function insertInDB($filename, $type, $fileext) {
		$varValues = array (
				'filename' => $filename,
				'type' => $type,
				'checked' => 0,
				'patterns' => '',
				'ext' => $fileext
		);
		$id = $this->db->addData ( 'insert', $this->filestable, '', '', $varValues );
		return $id;
	}
	private function isFolderLeft() {
		$query = "SELECT COUNT(`id`) as count FROM " . $this->db->quoteTable ( $this->filestable ) . " WHERE `type` = 'd'";
		$this->db->setQuery ( $query );
		$result = ( object ) $this->db->loadObject ();
		return $result->count;
	}
	public function backupDB($backup_type, $backup_to) {
		$this->pathFile = $this->backupDatabase ();
		$this->db->closeDBO ();
		if ($this->pathFile == false && $backup_type != 3) {
			return false;
		}
		else {
			if ($backup_to == 1) {
				return true;
			}
			else if ($backup_to == 2) {
				return $this->dropboxUploadFile ();
			}
		}
	}
    public function sendEmail($id, $type){
        $config_var = $this->getConfigVars();
        oseFirewall::loadEmails();
        $oseEmail = new oseEmail('firewall');
        $email = $this->getEmailByType($type);
        $email = $this->convertEmail($email, $id, $type);
        $receiptient = oseFirewall::getActiveReceivers();
        $result = $oseEmail->sendMailTo($email, $config_var, $receiptient);
        $oseEmail->closeDBO();
        return $result;
    }
    protected function getConfigVars(){
        return oseFirewall::getConfigVars();
    }
    protected function getEmailByType($type){
        $email = new stdClass();
        switch ($type) {
            case 'local':
                $email->subject = 'Centrora backup file was downloaded to your local directory';
                break;
            case 'dropbox':
                $email->subject = 'Centrora backup file was uploaded to your dropbox';
                break;
        }
        $emailTmp = oseFirewall::getConfiguration('emailTemp');

        if (empty($emailTmp['data']['emailTemplate'])) {
            $email->body = file_get_contents(dirname(__FILE__) . ODS . 'email.tpl');
        } else {
            $email->body = stripslashes($emailTmp['data']['emailTemplate']);
        }
        return $email;
    }

    protected function convertEmail($email, $id, $type){
        $backupfile = $this->getBackupDBByID($id);
        $basename = basename($backupfile);
        if ($type == "local") {
            $backuplocation = 'downloaded to local directory';
        } else {
            $backuplocation = "uploaded to dropbox";
        }
        $email->subject = $email->subject . " for [" . $_SERVER['HTTP_HOST'] . "]";
        $content = $basename . " was " . $backuplocation;
        $email->body = str_replace('{content}', $content, $email->body);
        $email->body = str_replace('{name}', 'Administrator', $email->body);
        $email->body = str_replace('{header}', $email->subject, $email->body);
        return $email;
    }

    public function onedrive_upload($id){
        require_once dirname(__FILE__) . '/onedrive/onedrive.php';
        $oneDrive = new onedriveModelBup();
        $file = $this->getBackupDBByID($id);
        $response = $oneDrive->upload($file);
        return $response;
    }

    public function dropbox_logout(){
        $condition = array('type' => 'dropbox' );
        $db = oseFirewall::getDBO();
        $flag = $db->deleteRecordString($condition, $this->configTable);
        $db->closeDBO();
        return $flag;
    }
    private function runfullbackup () {
        $return ['files'] = utf8_encode ( $this->backup ( $backup_type = 1, $backup_to = 1 ) );
        $return ['db'] = utf8_encode ( $this->backup ( $backup_type = 2, $backup_to = 1 ) );

        if ($return ['files'] == false || $return ['db'] == false){
            return false;
        } else {
            return $return;
        }
    }
    public function runFullBackupDropbox(){
        $result = $this->runfullbackup();
        $dropboxautho = $this->is_authorized();
        if ($result == false ){

            $return['error'] = true;
            $return['errorMsg'] = 'Local Backup Failed';

        } else {
            if ($dropboxautho == 'fail'){

                $return['error'] = true;
                $return['errorMsg'] = 'Dropbox Authentication Failed';

            }elseif ($dropboxautho == 'ok') {
                $return['db'] = $this->dropbox_upload($result['db']);
                $return['files'] = $this->dropbox_upload($result['files']);
                if ( $return ['db'] != true || $return ['files'] != true ){
                    $ErrorDB = (isset($return['db']))? 'ErrorDB:'. $return['db'] : "";
                    $ErrorFiles = (isset($return['files']))? 'ErrorFiles:'. $return['files'] : "";
                    $return['errorMsg'] =  $ErrorDB . $ErrorFiles;
                    $return['error'] = true;
                }
            }
        }
        return $return;
    }
    private function runFullBackupOneDrive(){
        $result = $this->runfullbackup();
        oseFirewall::callLibClass('backup/onedrive', 'onedrive');
        $oneDrive = new onedriveModelBup ();
        $oneDriveautho = $oneDrive->isAuthenticated();
        if ($result == false){

            $return['error'] = true;
            $return['errorMsg'] = 'Local Backup Failed';

        } else {
            if ($oneDriveautho == false){

                $return['error'] = true;
                $return['errorMsg'] = 'OneDrive Authentication Failed';

            }else {
                $return['db'] = $this->onedrive_upload($result['db']);
                $return['files'] = $this->onedrive_upload($result['files']);
                if ( $return ['db'] != true || $return ['files'] != true ){
                    $return['error'] = true;
                    $ErrorDB = (isset($return['db']->error->message))? 'ErrorDB:'. $return['db']->error->message : "";
                    $ErrorFiles = (isset($return['files']->error->message))? 'ErrorFiles:'. $return['files']->error->message : "";
                    $return['errorMsg'] =  $ErrorDB . $ErrorFiles;
                }
            }
        }
        return $return;
    }
    public function scheduledBackup ($cloudbackuptype) {
        $statusMsg = null;
        oseFirewall::loadRequest();
        $key = oRequest::getVar('key', NULL);
        if (!empty($key)) {
            switch ($cloudbackuptype) {
                case 1:
                    $result = $this->runfullbackup();
                    if (!$result) {
                        $status = false;
                    } elseif ($result != false){
                        $status = true;
                    }
                    break;
                case 2:
                    $statusMsg = $this->runFullBackupDropbox();
                    if($statusMsg['error'] == true){
                        $status = false;
                    }else {
                        $status = true;
                    }
                    break;
                case 3:
                    $statusMsg = $this->runFullBackupOneDrive();
                    if($statusMsg['error'] == true){
                        $status = false;
                    }else {
                        $status = true;
                    }
                    break;
            }
            $status = ($status == false)? 0 : 1;
            $url = $this->getCrawbackURL( $key, $status, $statusMsg['errorMsg'] );
            $this->db->closeDBO();
            $this->db->closeDBOFinal();
            $this->sendRequestBak($url);
        }
        exit;
    }
    private function sendRequestBak($url){
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
    private function getCrawbackURL ($key, $status, $statusMsg) {
        $webkey= $this->getWebKey ();
        $url = "http://www.centrora.com/accountApi/cronjobs/completeBackup?webkey=".$webkey
            ."&key=".$key."&completed=1&status=".(int)$status . "&statusMsg=". urlencode($statusMsg);
        return $url;
    }
    protected function getWebKey () {
        $dbo = oseFirewall::getDBO();
        $query = "SELECT * FROM `#__ose_secConfig` WHERE `key` = 'webkey'";
        $dbo->setQuery($query);
        $webkey = $dbo->loadObject()->value;
        return $webkey;
    }
}