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
/**
 * Clamd - A ClamAV plugin for CakePHP
 * Copyright (C) 2009 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package clamd
 * @copyright Copyright (C) 2009 Stichting Lone Wolves
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Interface to a ClamAV daemon
 */
class Clamd
{
	/**#@+
	 * Scan result status
	 */
	const OK          = 1;
	const FOUND       = 2;
	const ERROR       = 4;
	/**#@-*/

	/** @var string Object description */
	public $description = 'Interface to a ClamAV daemon';

	/** @var array Default clamd configuration */
	private $_baseConfig = array(
		'host'		=> '127.0.0.1',
		'port'		=> '3310',
		'timeout'	=> 60
	);

	/** @var array The configuration settings */
	private $config = array();

	/** @var resource Reference to the connection to clamd */
	private $connection = null;

	/** @var boolean The state of the connection */
	private $connected = false;

	/** @var array The last error number and string */
	private $lastError = array();
	
	/** @var boolean The status of the clamd */
	private $status = false;
	/**
	 * Constructor
	 *
	 * @param array $config Clamd configuration, which will be merged with the base configuration
	 */
	public function __construct()
	{
		$config = $this -> getClamConfig (); 
		$this->config = array_merge($this->_baseConfig, $config);
	}
	
	public function getClamConfig () {
		$config = $this -> getClamConfigDB () ;
		if (isset($config['clamav_activation']))
		{
			if ($config['clamav_activation'] == 'socket')
			{
				return array('host' => $config['clamavsocket'], 'port' => '0');
			}
			else
			{
				return array('host' => $config['clamavtcpip'], 'port' => $config['clamavtcpport']);
			}
		}
		else
		{
			return array(); 			
		}
	}
	private function getClamConfigDB () {
		$db = oseFirewall :: getDBO ();  
		$query= "SELECT * FROM `#__ose_secConfig` WHERE `key` LIKE '%clamav%' AND `type` = 'vsscan'";
		$db->setQuery($query);
		$results = $db->loadObjectlist();
		$config  = array (); 
		foreach ($results as $result)
		{
			$config[$result->key] = $result ->value;
			if ($result->key == 'enable_clamav')
			{
				$this->status = $result ->value; 
			}
		}
		return $config; 
	}
	public function getConfigStatus () {
		return $this->status; 
	}
	/**
	 * Connect to clamd
	 *
	 * @return boolean Success
	 */
	public function connect()
	{
		if ($this->connection != null) {
			$this->disconnect();
		}
		$this->connection = @fsockopen($this->config['host'], $this->config['port'], $errNum, $errStr, $this->config['timeout']);
		if (!empty($errNum) || !empty($errStr)) {
			$this->setLastError($errStr, $errNum);
			return;
		}
		return $this->connected = is_resource($this->connection);
	}
	
	public function getConnected () {
		return $this->connected; 
	}

	/**
	 * Disconnect from spamd
	 *
	 * @return boolean Success
	 */
	public function disconnect()
	{
		if (!is_resource($this->connection)) {
			$this->connected = false;
			return true;
		}

		$this->connected = !fclose($this->connection);
		if (!$this->connected) {
			$this->connection = null;
		}

		return !$this->connected;
	}

	/**
	 * Get the last error as a string.
	 *
	 * @return string Last error
	 */
	public function lastError()
	{
		if (!empty($this->lastError)) {
			return $this->lastError['num'].': '.$this->lastError['str'];
		} else {
			return null;
		}
	}

	/**
	 * Clear the last error
	 */
	public function clearLastError()
	{
		$this->lastError = array();
	}

	/**
	 * Set the last error.
	 *
	 * @param integer $errNum Error code
	 * @param string $errStr Error string
	 */
	public function setLastError($errNum, $errStr)
	{
		$this->lastError = array('num' => $errNum, 'str' => $errStr);
	}

	/**
	 * Write to the spamd socket
	 *
	 * @param string $data The data to write to the socket
	 * @return boolean Success
	 */
	function write($data) {
		if (!$this->connected) {
			if (!$this->connect()) {
				return false;
			}
		}

		return @fwrite($this->connection, $data, strlen($data));
	}

	/**
	 * Read from the spamd socket and close the connection
	 *
	 * @return mixed Socket data
	 */
	public function read()
	{
		if (!$this->connected) {
			return false;
		}

		$buffer = '';
		while (!feof($this->connection)) {
			$buffer .= fread($this->connection, 1024);
		}

		$this->disconnect();
		return $buffer;
	}

	/**
	 * Send a command to spamd
	 *
	 * @param string command The command to send
	 * @return boolean Success
	 */
	public function send($command)
	{
		if (in_array($command[0], array('n', 'z'))) {
			$command[0] = 'n';
		} else {
			$command = 'n' . $command;
		}

		if (substr($command, -1) != "\n") {
			$command .= "\n";
		}

		if (!$this->write($command)) {
			$this->disconnect();
			return false;
		}

		return true;
	}

	/**
	 * Execute a command on the spamd socket and wait for a response. The response
	 * will be stripped of session IDs
	 *
	 * @param string $command The command to execute
	 * @return mixed The result or false on failure
	 */
	public function exec($command)
	{
		if (!$this->send($command)) {
			return false;
		}

		return $this->read();
	}

	/**
	 * The PING command
	 *
	 * @return boolean Success
	 */
	public function ping()
	{
		return (trim($this->exec('PING')) == 'PONG');
	}

	/**
	 * Return the correct status constant for a status string
	 *
	 * @param string $code The status code in string form
	 * @result int The status code const
	 */
	protected function status_code($code)
	{
		$code = strtolower($code);
		if ($code == 'ok') {
			return self::OK;
		}
		if ($code == 'found') {
			return self::FOUND;
		}
		return self::ERROR;
	}

	/**
	 * Scan a file or directory recursively.
	 *
	 * The result of the scan is an array of scan results. Each result is an array:
	 *
	 * array(3) {
	 *     ['file'] => '/full/path/to/file'
	 *     ['status'] => self::OK | self::FOUND | self::ERROR
	 *     ['message'] => empty | virus name | error message
	 * }
	 *
	 * Note that if you recursively scan a directory the results will only contain
	 * positive matches and errors. Clamd does not return clean files when scanning
	 * recursively.
	 *
	 * @param string $path full path to the file to scan
	 * @return mixed An array of scan results or false on failure
	 */
	public function rscan($path, $continue = false)
	{
		if (!is_file($path) && !is_dir($path)) {
			echo 'Recursive scan path is not a readable file or directory';
			return false;
		}

		$command = $continue ? 'CONTSCAN' : 'SCAN';
		if (!$response = $this->exec($command . ' ' . $path)) {
			return false;
		}
		$result = array();
		foreach (explode("\n", $response) as $line) {
			if (!preg_match('/^([^:]+):(?: (.*))? (OK|FOUND|ERROR)$/', $line, $match)) {
				continue;
			}

			$result[] = array(
				'file' => $match[1],
				'status' => $this->status_code($match[3]),
				'msg' => $match[2]
			);
		}
		return $result;
	}

	/**
	 * Scan a single file
	 *
	 * @param string $path Full path to the file to scan
	 * @param string &$msg Name of the virus found or the clamd error message
	 * @return int The scan result status (self::OK, self::FOUND or self::ERROR) or false on failure
	 */
	public function scan($path, $msg = '')
	{
		if (!is_file($path)) {
			$this->setLastError(0, 'Scan path is not a readable file');
			return false;
		}
		if (!$result = $this->rscan($path)) {
			return false;
		}
		$result = array_shift($result);
		$msg = $result['msg'];
		return $result;
	}
	public function getVersion() 
	{
		if (!$response = $this->exec('VERSION')) {
			return false;
		}
		else {
			return $response; 	
		}
	}
	public function getStatus()
	{
		if (!$response = $this->exec('PING')) {
			return false;
		}
		else {
			return $response;
		}
	}
	public function getStat()
	{
		if (!$response = $this->exec('STATS')) {
			return false;
		}
		else {
			return $response;
		}
	}
	public function reloadDB()
	{
		if (!$response = $this->exec('RELOAD')) {
			return false;
		}
		else {
			return $response;
		}
	}
}
?>