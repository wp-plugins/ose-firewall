<?php

/**
 * Class gdriveModelBup
 * Google Drive model
 * This class is part of the Google Drive module for Backup by Supsystic
 *
 * @package BackupBySupsystic\Modules\GDrive
 * @version 1.3
 */
class gdriveModelBup
{

    private $gdrive_client_id = '749526762988-ukf6lkjabt69q2vub3i6iqr979acoitr.apps.googleusercontent.com';

    private $gdrive_client_secret = 'EQP-1pyir-3Bp8et0joSH0RL';

    const GDRIVE_SESS_NAME = 'gdrive_token';
    const GDRIVE_SESS_TIME = 'gdrive_time';

    private $scope = 'https://www.googleapis.com/auth/drive';

    private $redirect_url = 'http://www.centrora.com/backupRedirect/googledrive/index.php';

    private $_folderName = 'centrora';

    private $_currentDomain = '';

    public function __construct()
    {
        oseFirewall::loadFiles();
    }

    public function getCredential($key)
    {
        $key = ($key == 'clientId' ? self::CLIENT_ID_INDEX : self::CLIENT_SECRET_INDEX);
        $keys = frameBup::_()->getModule('gdrive')->options;
        foreach ($keys as $k) {
            if ($k['code'] == $key)
                return $k['value'];
        }
        return false;
    }

    /**
     * Returns client's credentials
     *
     * @since  1.1
     * @return array
     */
    public function getCredentials()
    {
        return array(
            'clientId' => $this->getCredential('clientId'),
            'clientSecret' => $this->getCredential('clientSecret'),
        );
    }

    /**
     * Reset authentication
     * @since 1.3
     * @return void
     */
    public function resetCredentials()
    {
        if (isset($_SESSION[self::GDRIVE_SESS_NAME])) {
            unset ($_SESSION[self::GDRIVE_SESS_NAME]);
        }
        $this->removeToken();
    }

    /**
     * Is authenticated user?
     *
     * @since  1.0
     * @return boolean
     */
    public function isAuthenticated()
    {
        if (isset($_SESSION[self::GDRIVE_SESS_NAME]) || false !== ($token = $this->readToken())) {
            return true;
        }

        return false;
    }

    /**
     * Initialize Google Client
     *
     * @since  1.1
     * @return \Google_Client
     */
    public function getClient()
    {
        $gDriveAuthUrl = '';
        $keys = frameBup::_()->getModule('gdrive')->options;
        foreach ($keys as $k) {
            if ($k['code'] == 'gdrive_auth_url') {
                $gDriveAuthUrl = $k['value'];
            }
        }
        $credentials = $this->getCredentials();
        $client = new Google_Client(getGoogleClientApiConfig());

        $client->setClientId($credentials['clientId']);
        $client->setClientSecret($credentials['clientSecret']);
        $client->setRedirectUri($gDriveAuthUrl . '/complete/');
        $client->setScopes($this->_scopes);

        /* For offline access */
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        if (null !== $token = $this->readToken()) {
            $client->setAccessToken($token);
            return $client;
        }

        if (isset($_SESSION[self::GDRIVE_SESS_NAME])) {
            $token = $this->addRefreshTokenToJSONToken($_SESSION[self::GDRIVE_SESS_NAME]);
            $client->setAccessToken($token);
        }

        return $client;
    }

    /**
     * Authenticate client
     *
     * @since  1.1
     * @return boolean
     */
    public function authenticate($code)
    {
        try {
            // Get cURL resource
            $curl = curl_init();
// Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/token',
                CURLOPT_USERAGENT => 'Centrora cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => http_build_query(array(
                    'client_id' => $this->gdrive_client_id,
                    'redirect_uri' => $this->redirect_url,
                    'client_secret' => $this->gdrive_client_secret,
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                ))
            ));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
// Send the request & save response to $resp
            $response = curl_exec($curl);
// Close request to clear up some resources
            curl_close($curl);

            $body = json_decode($response);
            $_SESSION[self::GDRIVE_SESS_NAME] = $body->access_token;
            $_SESSION[self::GDRIVE_SESS_TIME] = time() + (int)$body->expires_in;
            $this->saveToken($_SESSION[self::GDRIVE_SESS_NAME]);
            $this->saveRefreshTokenExpireTime($_SESSION[self::GDRIVE_SESS_TIME]);
            if (property_exists($body, 'refresh_token')) {
                $this->saveRefreshToken($body->refresh_token);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function refreshAccessToken()
    {
        $timestamp = time();
        $expiresIn = (int)$_SESSION[self::GDRIVE_SESS_TIME];

        if ($timestamp < $expiresIn) {
            return;
        }

        if (null === $refreshToken = $this->getRefreshToken()) {
            return;
        }
// Get cURL resource
        $curl = curl_init();
// Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/token',
            CURLOPT_USERAGENT => 'Centrora cURL Request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query(array(
                'client_id' => $this->gdrive_client_id,
                'client_secret' => $this->gdrive_client_secret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token'
            ))
        ));

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
// Send the request & save response to $resp
        $response = curl_exec($curl);
// Close request to clear up some resources
        curl_close($curl);

        $body = json_decode($response);
        $_SESSION[self::GDRIVE_SESS_NAME] = $body->access_token;
        $_SESSION[self::GDRIVE_SESS_TIME] = time() + (int)$body->expires_in;

        $this->saveToken($_SESSION[self::GDRIVE_SESS_NAME]);
        $this->saveRefreshTokenExpireTime($_SESSION[self::GDRIVE_SESS_TIME]);

        if (property_exists($body, 'refresh_token')) {
            $this->saveRefreshToken($body->refresh_token);
        }

        return true;
    }

    /**
     * Generate and return authentication URL for user
     *
     * @since  1.3
     * @return string
     */
    public function getAuthenticationURL()
    {
        $url = 'https://accounts.google.com/o/oauth2/auth?';
        $state = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $redirectURI = urlencode($this->redirect_url);
        $scope = urlencode($this->scope);
        $state = urlencode($state);
        $client_id = $this->gdrive_client_id;
        $oauthurl = 'scope=' . $scope . '&state=' . $state . '&redirect_uri=' . $redirectURI . '&client_id=' . $client_id . '&response_type=code&access_type=offline&approval_prompt=force';
        return $url . $oauthurl;
    }

    /**
     * Returns mime type by file extension
     *
     * @since  1.1
     * @param  string $filename
     * @return string
     */
    public function getMimetype($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'sql':
            case 'txt':
                return 'text/plain';
            case 'zip':
                return 'application/zip';
            case 'gz':
                return 'application/gzip';
            default:
                return 'application/zip';
        }
    }


    /**
     * Upload files to Google Drive
     *
     * @since  1.1
     * @param  array $files
     * @return integer
     */
    public function upload($files, $folder_id)
    {
        if ($this->isAuthenticated() === false) {
            return 401;
        }
        $url = 'https://www.googleapis.com/upload/drive/v2/files?uploadType=multipart';
        $access_token = $this->readToken();
        $mimeTpye = $this->getMimetype($files);
        $inputarray = '';
        $inputarray .= "--foo_bar_baz\r\n";
        $inputarray .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
        $inputarray .= "{\r\n";
        $inputarray .= "\"title\": \"" . basename($files) . "\",\r\n";
        $inputarray .= "\"parents\": [{\"id\":\"" . $folder_id . "\"}]\r\n";
        $inputarray .= "}\r\n\r\n";
        $inputarray .= "--foo_bar_baz\r\n";
        $inputarray .= "Content-Type:" . $mimeTpye . "\r\n\r\n";
        $inputarray .= file_get_contents($files) . "\r\n";
        $inputarray .= "--foo_bar_baz--";

        $response = $this->curl_upload_post($url, $inputarray, $access_token);
        if (property_exists($response, 'id')) {
            return true;
        } else {
            return $response;
        }
    }

    public function curl_upload_post($url, $inputarray, $access_token)
    {
        $contentLength = strlen($inputarray);
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: multipart/related; boundary="foo_bar_baz"',
                'Authorization: Bearer ' . $access_token,
                'Content-Length: ' . $contentLength,
            ));
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $inputarray);
            $output = curl_exec($ch);
            curl_close($ch);
            $output = json_decode($output);
            return $output;
        } catch (Exception $e) {
        }
    }

    public function createFolder($title, $parents = 'root')
    {
        $url = 'https://www.googleapis.com/drive/v2/files';
        $access_token = $this->readToken();
        $inputarray = array(
            'parents' => array(array(
                'id' => $parents
            )),
            "title" => $title,
            "mimeType" => "application/vnd.google-apps.folder",
        );
        $response = $this->curl_post($url, $inputarray, $access_token);

        return $response;
    }

    public function curl_post($uri, $inputarray, $access_token)
    {
        $trimmed = json_encode($inputarray);
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token,
            ));
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $trimmed);
            $output = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            $response = json_decode($output);
            return $response;
        } catch (Exception $e) {
        }
    }


    protected function curl_get($uri, $json_decode_output = "true")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        $output = json_decode($output);
//        if ($output->error->code == 401) {
//           $redirect = $this->getAuthenticationURL();
//            $this->redirectGdrive($redirect);
//        }
        if (empty($error)) {
            return $output;
        } else {
            return Array('error' => 'HTTP status code not expected - got ', 'description' => $httpCode);
        }
    }

    function redirectGdrive($url)
    {
        if (headers_sent()) {
            echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
        } else {
            header('Location: ' . $url);
        }
        exit();
    }

    public function getRootObjects()
    {
        if ($this->isAuthenticated() === false) {
            return false;
        }
        $token = null;
        $list = array();
        $access_token = $this->readToken();
        $cDomain = $this->_getCurrentDomain();
        $config = "trashed=false and mimeType='application/vnd.google-apps.folder' and (title ='" . $this->_folderName . "' or title ='" . $cDomain . "')";
        $config = urlencode($config);
        do {
            if (!empty($token)) {
                $token = urlencode($token);
                $url = 'https://www.googleapis.com/drive/v2/files?q＝' . $config . '&pageToken=' . $token . "&access_token=" . $access_token;
            } else {
                $url = 'https://www.googleapis.com/drive/v2/files?q＝' . $config . "&access_token=" . $access_token;
            }
            try {
                $files = $this->curl_get($url);
            } catch (Exception $e) {
                throw $e;
            }
            $list = array_merge($list, $files->items);
            $token = null;
            if (isset($files->nextPageToken)) {
                $token = $files->nextPageToken;
            }
        } while ($token);
        return $list;
    }

    private function _getCurrentDomain()
    {
        $currentDomain = $_SERVER['HTTP_HOST'];
        $currentDomain = preg_replace('/[:\/;*<>|?]/', '', $currentDomain);
        $this->_currentDomain = $currentDomain;
        return $this->_currentDomain;
    }

    public function getDomain()
    {
        $rootObjects = $this->getRootObjects();
        $currentDomain = $this->_getCurrentDomain();
        $folders = array();
        $root = null;
        if ($rootObjects === false) {
            return false;
        }
        foreach ($rootObjects as $object) {
            if ($object->mimeType === 'application/vnd.google-apps.folder' && $object->labels->trashed == false) {
                $folders[] = $object;
                if ($object->title === $this->_folderName) {
                    $root = $object;
                }
            }
        }

        if ($root === null) {
            $root = $this->createFolder($this->_folderName);
        }

        foreach ($folders as $folder) {
            if ($folder->title === $currentDomain) {
                foreach ($folder->parents as $parent) {
                    if ($parent->id === $root->id) {
                        return $folder;
                    }
                }
            }
        }
        return $this->createFolder($currentDomain, $root->id);
    }

    public function getDomainFiles()
    {
        $pageToken = null;
        $domain = $this->getDomain();
        $child = array();

        if (!$domain) {
            return null;
        }

        $client = $this->getClient();
        $service = new Google_DriveService($client);

        do {
            try {
                $parameters = array();

                if ($pageToken) {
                    $parameters['pageToken'] = $pageToken;
                }

                $children = $service->children->listChildren($domain['id'], $parameters);
                $child = array_merge($child, $children['items']);

                $token = null;
                if (isset($children['nextPageToken'])) {
                    $pageToken = $children['nextPageToken'];
                }
            } catch (Exception $e) {
                $pageToken = null;
                $this->pushError($e->getMessage());

                return array();
            }
        } while ($pageToken);

        // Formatting uploading data files for use their on backups page
        $files = array();
        foreach ($child as $file) {
            $backupInfo = $service->files->get($file['id']);
            $backupInfo = $this->getBackupInfoByFilename($backupInfo['title']);

            if (!empty($backupInfo['ext']) && $backupInfo['ext'] == 'sql') {
                $files[$backupInfo['id']]['gdrive']['sql'] = $service->files->get($file['id']);
                $files[$backupInfo['id']]['gdrive']['sql']['backupInfo'] = $backupInfo;
                $files[$backupInfo['id']]['gdrive']['sql']['backupInfo'] = dispatcherBup::applyFilters('addInfoIfEncryptedDb', $files[$backupInfo['id']]['gdrive']['sql']['backupInfo']);;
            } elseif (!empty($backupInfo['ext']) && $backupInfo['ext'] == 'zip') {
                $files[$backupInfo['id']]['gdrive']['zip'] = $service->files->get($file['id']);
                $files[$backupInfo['id']]['gdrive']['zip']['backupInfo'] = $backupInfo;
            }
        }

        return $files;
    }

    /**
     * Return ALL uploaded to Google Drive files.
     * You need to filter files manually.
     * Note: trashed files - labels => trashed (boolean)
     * Note: filter by description "Backup by Supsystic"
     *
     * @since  1.1
     * @return boolean|array
     */
    public function getUploadedFiles()
    {
        if ($this->isAuthenticated() === false) {
            return false;
        }

        // $client  = $this->getClient();
        // $service = new Google_DriveService($client);
        // $token   = null;
        // $list    = array();
        // $config  = array();
        //
        // do {
        // 	if(!empty($token)) {
        // 		$config['pageToken'] = $token;
        // 	}
        // 	try {
        // 		$files = $service->files->listFiles($config);
        // 	} catch(Exception $e) {
        // 		session_destroy();
        // 		redirectBup($client->createAuthUrl());
        // 	}
        //
        // 	$list = array_merge($list, $files['items']);
        // 	$token = $files['nextPageToken'];
        // } while ($token);
        //
        // return $list;
        //
        return $this->getDomainFiles();
    }

    /**
     * Delete file from Google Drive by the FileID, not by name
     * Method names as 'remove', because 'delete' will overload's modelBup method
     *
     * @param  string $file
     * @return boolean
     */
    public function remove($file)
    {
        if ($this->isAuthenticated() === false) {
            return false;
        }

        $client = $this->getClient();
        $service = new Google_DriveService($client);

        $service->files->delete($file);

        return true;
    }

    /**
     * Check for local backup
     *
     * @since  1.1
     * @param  string $filename
     * @return boolean
     */
    public function isLocalFileExists($filename)
    {
        $filepath = $this->getBackupsPath();

        if (file_exists($filepath . $filename)) {
            return true;
        }

        return false;
    }

    /**
     * Download file from Google Drive
     *
     * @since  1.1
     * @param  string $url
     * @param  string $filename
     * @return boolean|null
     */
    public function download($url = null, $filename = '', $returnDataString = false)
    {

        $client = $this->getClient();
        $service = new Google_DriveService($client);

        if ($url) {
            $request = new Google_HttpRequest($url);
            $httpRequest = Google_Client::$io->authenticatedRequest($request);

            if ($httpRequest->getResponseHttpCode() == 200) {
                $filepath = $this->getBackupsPath() . $filename;
                $content = $httpRequest->getResponseBody();

                if ($returnDataString)
                    return $content;

                if (file_put_contents($filepath, $content) !== false) {
                    return true;
                }

                return false;
            }

            return false;
        }

        return null;
    }

    public function removeToken()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveAccessToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveAccessToken.json";
        }
        oseFile::delete($filePath);
    }

    /**
     * Saves the token
     *
     * @param string $token
     */
    protected function saveToken($token)
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveAccessToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveAccessToken.json";
        }
        $this->removeToken();

        oseFile::write($filePath, $token);

    }

    protected function saveFolderid($id)
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveFolderid.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveFolderid.json";
        }

        oseFile::write($filePath, $id);

    }

    protected function readFolderid()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveFolderid.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveFolderid.json";
        }

        $folderid = oseFile::read($filePath);

        return $folderid;
    }

    /**
     * Reads the token
     */
    public function readToken()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveAccessToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveAccessToken.json";
        }

        $access_token = oseFile::read($filePath);

        return $access_token;
    }

    protected function deleteRefreshToken()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveRefreshToken.json";
        }

        oseFile::delete($filePath);
    }

    protected function deleteRefreshTokenExpireTime()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveExpireTimeRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveExpireTimeRefreshToken.json";
        }

        oseFile::delete($filePath);
    }

    public function getRefreshToken()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveRefreshToken.json";
        }
        $refresh_token = oseFile::read($filePath);

        return $refresh_token;

    }

    protected function getRefreshTokenExpireTime()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveExpireTimeRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveExpireTimeRefreshToken.json";
        }
        $refreshTokenExpireTime = oseFile::read($filePath);

        return $refreshTokenExpireTime;

    }

    protected function saveRefreshToken($refreshToken)
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveRefreshToken.json";
        }
        $this->deleteRefreshToken();

        oseFile::write($filePath, $refreshToken);

    }

    protected function saveRefreshTokenExpireTime($refreshTokenExpireTime)
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveExpireTimeRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "googledrive" . ODS . "googledriveExpireTimeRefreshToken.json";
        }
        $this->deleteRefreshTokenExpireTime();

        oseFile::write($filePath, $refreshTokenExpireTime);
    }

    public function logout()
    {
        if (isset($_SESSION[self::GDRIVE_SESS_NAME])) {
            unset ($_SESSION[self::GDRIVE_SESS_NAME]);
        }
        if (isset($_SESSION[self::GDRIVE_SESS_TIME])) {
            unset ($_SESSION[self::GDRIVE_SESS_TIME]);
        }

        $this->removeToken();
        $this->deleteRefreshToken();
        $this->deleteRefreshTokenExpireTime();
    }

    public function getStrLen($str)
    {
        $strlenVar = strlen($str);
        $d = $ret = 0;
        for ($count = 0; $count < $strlenVar; ++$count) {
            $ordinalValue = ord($str{$ret});
            switch (true) {
                case (($ordinalValue >= 0x20) && ($ordinalValue <= 0x7F)):
                    // characters U-00000000 - U-0000007F (same as ASCII)
                    $ret++;
                    break;

                case (($ordinalValue & 0xE0) == 0xC0):
                    // characters U-00000080 - U-000007FF, mask 110XXXXX
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $ret += 2;
                    break;

                case (($ordinalValue & 0xF0) == 0xE0):
                    // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $ret += 3;
                    break;

                case (($ordinalValue & 0xF8) == 0xF0):
                    // characters U-00010000 - U-001FFFFF, mask 11110XXX
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $ret += 4;
                    break;

                case (($ordinalValue & 0xFC) == 0xF8):
                    // characters U-00200000 - U-03FFFFFF, mask 111110XX
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $ret += 5;
                    break;

                case (($ordinalValue & 0xFE) == 0xFC):
                    // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $ret += 6;
                    break;
                default:
                    $ret++;
            }
        }
        return $ret;
    }
}
