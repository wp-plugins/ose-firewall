<?php
define("api_base_url", "https://apis.live.net/v5.0/");

class onedriveModelBup
{

    const CLIENT_ID = '00000000401562C1';
    const CLIENT_SECRET = 'vjF9j1m-acd7EbVmxGybd629dOnuI1Nr';
    const SESSION_ID = '_onedrive_accessToken';
    const SESSION_EXP = '_onedrive_accessToken_expires';
    const AUTH_URL = 'https://login.live.com/oauth20_authorize.srf';
    const TOKEN_URL = 'https://login.live.com/oauth20_token.srf';
    const REDIRECT_URI = 'http://www.centrora.com/backupRedirect/index.php';
    private $configTable = '#__ose_secConfig';

    public function __construct()
    {
        oseFirewall::loadFiles();
    }

    /**
     * Checks whether the current user is authenticated to the OneDrive.
     * @return bool
     */
    public function isAuthenticated()
    {
        if (isset($_SESSION[self::SESSION_ID]) || false !== ($token = $this->readToken())) {
            if (empty($_SESSION[self::SESSION_ID]))
                $_SESSION[self::SESSION_ID] = $token;
            if (!isset($_SESSION[self::SESSION_EXP]))
                $_SESSION[self::SESSION_EXP] = $this->getRefreshTokenExpireTime();
            return true;
        }
        return false;
    }

    public function refreshAccessToken()
    {
        if (!isset($_SESSION[self::SESSION_EXP])) {
            return;
        }

        $timestamp = time();
        $expiresIn = (int)$_SESSION[self::SESSION_EXP];

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
            CURLOPT_URL => self::TOKEN_URL,
            CURLOPT_USERAGENT => 'Centrora cURL Request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query(array(
                'client_id' => self::CLIENT_ID,
                'redirect_uri' => self::REDIRECT_URI,
                'client_secret' => self::CLIENT_SECRET,
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

        $_SESSION[self::SESSION_ID] = $body->access_token;
        $_SESSION[self::SESSION_EXP] = time() + (int)$body->expires_in;

        $this->saveToken($_SESSION[self::SESSION_ID]);

        if (property_exists($body, 'refresh_token')) {
            $this->saveRefreshToken($body->refresh_token);
            $this->saveRefreshTokenExpireTime($_SESSION[self::SESSION_EXP]);
        }

        return true;
    }

    /**
     * Returns the authorization URL.
     * @return string
     */
    public function getAuthorizationUrl()
    {
//        $slug = 'ose_fw_authentication';
//        $queryString = !empty($_SERVER['QUERY_STRING']) ? 'admin.php?' . $_SERVER['QUERY_STRING'] : '';
//        $redirectURI = !empty($queryString) ? $queryString : 'admin.php?page=' . $slug;
        $redirectURI = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        // $redirectURI = str_replace("world","Peter","Hello world!");
        $query = array(
            'client_id' => self::CLIENT_ID,
            // 'redirect_uri'  => urlencode(admin_url($redirectURI)),
            'redirect_uri' => self::REDIRECT_URI,
            'response_type' => 'code',
            'scope' => array(
                'wl.skydrive_update',
                'wl.skydrive',
                'wl.signin',
                'onedrive.appfolder',
                'onedrive.readwrite',
                'wl.offline_access'
            ),
            'state' => urlencode($redirectURI),
        );
        return self::AUTH_URL . '?' . $this->buildQuery($query);
    }

    /**
     * Authorize user with the authorization code.
     * @param  string $code Authorization code.
     * @return bool
     */
    public function authorize($code)
    {
        // Get cURL resource
        $curl = curl_init();
// Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::TOKEN_URL,
            CURLOPT_USERAGENT => 'Centrora cURL Request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query(array(
                'client_id' => self::CLIENT_ID,
                'redirect_uri' => self::REDIRECT_URI,
                'client_secret' => self::CLIENT_SECRET,
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

        $_SESSION[self::SESSION_ID] = $body->access_token;
        $_SESSION[self::SESSION_EXP] = time() + (int)$body->expires_in;


        if (property_exists($body, 'refresh_token')) {
            $this->saveToken($_SESSION[self::SESSION_ID]);
            $this->saveRefreshToken($body->refresh_token);
            $this->saveRefreshTokenExpireTime($_SESSION[self::SESSION_EXP]);
        }
    }

    public function logout()
    {
        if (isset($_SESSION[self::SESSION_ID])) {
            unset ($_SESSION[self::SESSION_ID]);
        }
        if (isset($_SESSION[self::SESSION_EXP])) {
            unset ($_SESSION[self::SESSION_EXP]);
        }

        $this->removeToken();
        $this->deleteRefreshToken();
        $this->deleteRefreshTokenExpireTime();
    }

    /**
     * Returns the files from the Backup By Supsystic folder.
     * @return array|null
     */
    public function getUserFiles()
    {
        $this->refreshAccessToken();
        $root = $this->getDomainObject();

        if ($root) {
            return $this->getFolderObjects($root->id);
        }

        return null;
    }

    public function getBackupFolderObject()
    {
        $rootObjects = $this->getSkyDriveObjects();

        if (null === $rootObjects) {
            return null;
        }
        foreach ($rootObjects as $object) {
            if ($object->type == 'folder' && $object->name == 'centrora') {
                return $object;
            }
        }

        return $this->createFolder('centrora');
    }
    public function getDomainObject()
    {
        $currentDomain = $_SERVER['HTTP_HOST'];
        $currentDomain = preg_replace('/[:\/;*<>|?]/', '', $currentDomain);
        $root = $this->getBackupFolderObject();


        $domains = $this->getFolderObjects($root->id);

        foreach ($domains as $domain) {

            if ($domain->type == 'folder' && $domain->name == $currentDomain) {
                return $domain;
            }
        }

        return $this->createFolder($currentDomain, null, $root->id);
    }

    public function getFileBackupFolderID ($parentfolder, $folder_id)
    {
        $domains = $this->getFolderObjects($folder_id->id);

        foreach ($domains as $domain) {

            if ($domain->type == 'folder' && $domain->name == $parentfolder) {
                return $domain;
            }
        }

        return $this->createFolder($parentfolder, null, $folder_id->id);

    }

    /**
     * Returns the object with the Microsoft SkyDrive data.
     * @return stdClass|null
     */
    public function getSkydrive()
    {
        $response = wp_remote_get(
            $this->buildUrl('me/skydrive?access_token={token}', array(
                'token' => $this->getAccessToken()
            ))
        );

        if ($this->hasError($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);

        if ($this->isJsonEncoded($response)) {
            $body = json_decode($body);
        }

        return $body;
    }

    /**
     * Returns the list of the files on the user's SkyDrive.
     * @return stdClass[]|null
     */
    public function getSkyDriveObjects()
    {
        // if (null === $skydrive = $this->getSkydrive()) {
        //     return null;
        // }

        // return $this->getFolderObjects($skydrive->id);
        return $this->getFolderObjects('me/skydrive');
    }

    /**
     * Returns the list of the objects inside the folder.
     * @param  string $folderId
     * @return stdClass[]|null
     */
    public function getFolderObjects($folderId)
    {
        $url = $this->buildUrl('{folder_id}/files?access_token={token}', array(
            'folder_id' => $folderId,
            'token' => $this->getAccessToken(),
        ));

        $response = $this->curl_get($url);

        return $response->data;
    }

    /**
     * Creates a new folder in the SkyDrive root directory.
     * @param  string $name Folder name
     * @param  string $description Folder descriotion
     * @return stdClass|null
     */
//    public function createFolder($parent = 'me/skydrive')
//    {
//        $access_token = $this->getAccessToken();
//        $uri = $this->buildUrl($parent);
//        $inputarray = array(
//            "name" => "centrora"
//        );
//        $this->curl_post($uri, $inputarray, $access_token);
//    }
    public function createFolder($name, $description = null, $parent = 'me/skydrive')
    {
        $url = $this->buildUrl($parent);
        $access_token = $this->getAccessToken();
        $inputarray = array(
            'description' => $description,
            "name" => $name
        );
        $response = $this->curl_post($url, $inputarray, $access_token);

        return $response;
    }

    protected function curl_post($uri, $inputarray, $access_token)
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
            curl_close($ch);
            $output = json_decode($output);
            return $output;
        } catch (Exception $e) {
        }
    }

    public function oneDriveDB($id)
    {
        $db = oseFirewall::getDBO();
        $flag = oseFirewall::getConfiguration('onedrive');
        if (empty($flag['data']['onedrive'])) {
            $contentArray = array(
                'key' => 'onedrive',
                'value' => $id,
                'type' => 'onedrive',
            );
            $db->addData('insert', $this->configTable, '', '', $contentArray);
            $db->closeDBO();
        } else {
            $contentArray = array(
                'value' => $id,
            );
            $db->addData('update', $this->configTable, 'key', 'onedrive', $contentArray);
            $db->closeDBO();
        }
    }

    public function upload($file, $folder_id)
    {
        $response = $this->put_file($file, $folder_id);
        if (property_exists($response, 'id')) {
            return true;
        } else {
            return $response;
        }
    }

    public function download($fileId, $returnDataString = false)
    {
        $this->refreshAccessToken();
        $skydrive = new skydriveBup($this->getAccessToken());
        if (!$this->isAuthenticated()) {
            $this->pushError(__('Authorization required.', BUP_LANG_CODE));

            return false;
        }

        try {
            $data = $skydrive->download($fileId);

            if (!is_array($data)) {
                $this->pushError(__('Enexpected error.', BUP_LANG_CODE));

                return false;
            }

            foreach ($data as $file) {
                $filename = $this->getBackupsPath()
                    . '/'
                    . $file['properties']['name'];

                if ($returnDataString)
                    return $file['data'];

                if (!file_put_contents($filename, $file['data'])) {
                    $this->pushError(__('Failed to save downloaded file.', BUP_LANG_CODE));

                    return false;
                }
            }

            return true;
        } catch (Exception $e) {
            $this->pushError($e->getMessage());

            return false;
        }
    }

    public function getFileProps($fileId)
    {
        $response = wp_remote_get(
            $this->buildUrl('{file_id}?access_token={token}', array(
                'file_id' => $fileId,
                'token' => $this->getAccessToken(),
            ))
        );

        if (!$this->hasError($response)) {
            return $this->getBody($response);
        }

        return false;
    }

    /**
     * Removes the object (folder or photo) from the OneDrive.
     * @param  string $objectId The identifier of the folder or photo.
     * @return bool
     */
    public function deleteObject($objectId)
    {
        $url = $this->buildUrl('{object_id}?access_token={token}', array(
            'object_id' => $objectId,
            'token' => $this->getAccessToken(),
        ));

        $response = wp_remote_get($url, array(
            'method' => 'DELETE',
        ));

        if ($this->hasError($response, 204)) {
            return false;
        }

        return true;
    }

    /**
     * Returns the response body.
     * @param  array $response Response array
     * @return stdClass|null
     */
    public function getBody($response)
    {
        $body = wp_remote_retrieve_body($response);

        if ($this->isJsonEncoded($response)) {
            $body = json_decode($body);
        }

        return $body;
    }

    /**
     * Returns the access token.
     * @return string|null
     */
    public function getAccessToken()
    {
        if (!isset($_SESSION[self::SESSION_ID])) {
            return $_SESSION[self::SESSION_ID] = $this->readToken();
        }

        return $_SESSION[self::SESSION_ID];
    }

    public function getBackupsPath()
    {
        return frameBup::_()->getModule('warehouse')->getPath()
        . DIRECTORY_SEPARATOR;
    }

    public function isLocalFileExists($filename)
    {
        $filepath = $this->getBackupsPath();

        if (file_exists($filepath . $filename)) {
            return true;
        }

        return false;
    }

    /**
     * Builds the request URL.
     * @param  string $pattern URL string with the optional context.
     * @param  array $parameters An array of the URL parameters (context).
     * @return string
     */
    protected function buildUrl($pattern, array $parameters = array())
    {
        $baseUrl = 'https://apis.live.net/v5.0/';
        $replace = array();

        foreach ($parameters as $parameter => $value) {
            $replace['{' . $parameter . '}'] = $value;
        }

        $query = @strtr($pattern, $replace);

        return $baseUrl . ltrim($query, '/');
    }

    /**
     * Checks whether the response has errors.
     * @param  array $response Response array.
     * @param  int $successCode Expected success HTTP status code.
     * @return bool
     */


    /**
     * Compare status codes.
     * @param  int $expected Expected status code.
     * @param  mixed $response The response
     * @return bool
     */
    protected function isStatusCode($expected, $response)
    {
        if (is_wp_error($response)) {
            return false;
        }

        $actual = wp_remote_retrieve_response_code($response);

        return ((int)$expected === (int)$actual);
    }

    /**
     * Checks whether the response is JSON encoded
     * @param  mixed $response
     * @return bool
     */
    protected function isJsonEncoded($response)
    {
        $headers = wp_remote_retrieve_headers($response);

        if (!isset($headers['content-type'])) {
            return false;
        }

        if (!preg_match('/json/ui', $headers['content-type'])) {
            return false;
        }

        return true;
    }

    /**
     * Thank you, Microsoft. I hate your stupid encoding requirments.
     * Use this function instead of http_build_query.
     * @param  array $data An array of the query string data.
     * @return string
     */
    protected function buildQuery(array $data)
    {
        $queryString = '';

        foreach ($data as $param => $value) {
            if ($param == 'redirec_uri' || $param == 'state') {
                $value = $this->encodeUrl($value);
            }

            if ($param == 'scope') {
                $value = $this->encodeScope($value);
            }

            if ($param == 'client_secret') {
                $value = $this->encodeSecret($value);
            }

            $queryString .= $param . '=' . $value . '&';
        }

        return rtrim($queryString, '&');
    }

    protected function encodeUrl($url)
    {
        $replace = array(
            '/' => '%2F',
            ':' => '%3A',
            ' ' => '%20',
        );


        // Disable notice. PHP 5.5 bug.
        // http://php.net//manual/ru/function.strtr.php#112930
        return @strtr($url, $replace);
    }

    protected function encodeScope($scope)
    {
        if (is_array($scope)) {
            $scope = implode(' ', $scope);
        }

        // Disable notice. PHP 5.5 bug.
        // http://php.net//manual/ru/function.strtr.php#112930
        return @strtr($scope, array(
            ' ' => '%20',
        ));
    }

    protected function encodeSecret($secret)
    {
        // Disable notice. PHP 5.5 bug.
        // http://php.net//manual/ru/function.strtr.php#112930
        return @strtr($secret, array(
            '+' => '%2B',
        ));
    }

    public function removeToken()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveAccessToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveAccessToken.json";
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
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveAccessToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveAccessToken.json";
        }
        $this->removeToken();

        oseFile::write($filePath, $token);

    }

    protected function saveFolderid($id)
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveFolderid.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveFolderid.json";
        }

        oseFile::write($filePath, $id);

    }

    protected function readFolderid()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveFolderid.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveFolderid.json";
        }

        $folderid = oseFile::read($filePath);

        return $folderid;
    }

    /**
     * Reads the token
     */
    protected function readToken()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveAccessToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveAccessToken.json";
        }

        $access_token = oseFile::read($filePath);

        return $access_token;
    }

    protected function deleteRefreshToken()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveRefreshToken.json";
        }

        oseFile::delete($filePath);
    }

    protected function deleteRefreshTokenExpireTime()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveExpireTimeRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveExpireTimeRefreshToken.json";
        }

        oseFile::delete($filePath);
    }

    protected function getRefreshToken()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveRefreshToken.json";
        }
        $refresh_token = oseFile::read($filePath);

        return $refresh_token;

    }

    protected function getRefreshTokenExpireTime()
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveExpireTimeRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveExpireTimeRefreshToken.json";
        }
        $refreshTokenExpireTime = oseFile::read($filePath);

        return $refreshTokenExpireTime;

    }

    protected function saveRefreshToken($refreshToken)
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveRefreshToken.json";
        }
        $this->deleteRefreshToken();

        oseFile::write($filePath, $refreshToken);

    }

    protected function saveRefreshTokenExpireTime($refreshTokenExpireTime)
    {
        if (OSE_CMS == "wordpress") {
            $filePath = OSE_BACKUPPATH . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveExpireTimeRefreshToken.json";
        } else {
            $filePath = OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "onedrive" . ODS . "onedriveExpireTimeRefreshToken.json";
        }
        $this->deleteRefreshTokenExpireTime();

        oseFile::write($filePath, $refreshTokenExpireTime);
    }

    protected function put_file($file, $folder_id)
    {
        $access_token = $this->getAccessToken();

        $r2s = api_base_url . $folder_id . "/files/" . basename($file) . "?access_token=" . $access_token;
        $response = $this->curl_put($r2s, $file);
        return $response;
    }

    protected function curl_put($uri, $fp)
    {
        $output = "";
        try {
            $pointer = fopen($fp, 'r+');
            $stat = fstat($pointer);
            $pointersize = $stat['size'];
            $ch = curl_init($uri);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_INFILE, $pointer);
            curl_setopt($ch, CURLOPT_INFILESIZE, (int)$pointersize);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

            //HTTP response code 100 workaround
            //see http://www.php.net/manual/en/function.curl-setopt.php#82418
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

            $output = curl_exec($ch);

            //$sAverageSpeedDownload = curl_getInfo( $ch, CURLINFO_SPEED_DOWNLOAD );
            //$output['ulspd'] = curl_getInfo( $ch, CURLINFO_SPEED_UPLOAD );

        } catch (Exception $e) {

        }
        return json_decode($output);


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

        if (empty($error)) {
            if ($json_decode_output == "true") {
                $output = json_decode($output);
                return $output;
            } else {
                return $output;
            }
        } else {
            return Array('error' => 'HTTP status code not expected - got ', 'description' => $httpCode);
        }
    }
}
