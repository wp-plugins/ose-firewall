<?php
/**
* @version     2.0 +
* @package       Open Source Excellence Security Suite
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

defined('OSE_FRAMEWORK') or die;

class oRequest {
	public static function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0) {
		// Ensure hash and type are uppercase
		$hash = strtoupper($hash);
		if ($hash === 'METHOD') {
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}
		$type = strtoupper($type);
		$sig = $hash . $type . $mask;
		// Get the input hash
		switch ($hash) {
			case 'GET' :
				$input = & $_GET;
				break;
			case 'POST' :
				$input = & $_POST;
				break;
			case 'FILES' :
				$input = & $_FILES;
				break;
			case 'COOKIE' :
				$input = & $_COOKIE;
				break;
			case 'ENV' :
				$input = & $_ENV;
				break;
			case 'SERVER' :
				$input = & $_SERVER;
				break;
			default :
				$input = & $_REQUEST;
				$hash = 'REQUEST';
				break;
		}
		if (isset ($GLOBALS['_JREQUEST'][$name]['SET.' . $hash]) && ($GLOBALS['_JREQUEST'][$name]['SET.' . $hash] === true)) {
			// Get the variable from the input hash
			$var = (isset ($input[$name]) && $input[$name] !== null) ? $input[$name] : $default;
			$var = self :: _cleanVar($var, $mask, $type);
		}
		elseif (!isset ($GLOBALS['_JREQUEST'][$name][$sig])) {
			if (isset ($input[$name]) && $input[$name] !== null) {
				// Get the variable from the input hash and clean it
				$var = self :: _cleanVar($input[$name], $mask, $type);
				// Handle magic quotes compatibility
				if (get_magic_quotes_gpc() && ($var != $default) && ($hash != 'FILES')) {
					$var = self :: _stripSlashesRecursive($var);
				}
				$GLOBALS['_JREQUEST'][$name][$sig] = $var;
			}
			elseif ($default !== null) {
				// Clean the default value
				$var = self :: _cleanVar($default, $mask, $type);
			} else {
				$var = $default;
			}
		} else {
			$var = $GLOBALS['_JREQUEST'][$name][$sig];
		}
		return $var;
	}

	/**
	 * Fetches and returns a given filtered variable. The integer
	 * filter will allow only digits and the - sign to be returned. This is currently
	 * only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @param   string  $name     Variable name.
	 * @param   string  $default  Default value if the variable does not exist.
	 * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
	 *
	 * @return  integer  Requested variable.
	 *
	 * @since   11.1
	 *
	 * @deprecated   12.1
	 */
	public static function getInt($name, $default = 0, $hash = 'default')
	{
		if (is_numeric($_REQUEST[$name]))
		{
			return intval(self::getVar($name, $default, $hash, 'int'));
		}
		else
		{
			return null; 
		}
	}

	/**
	 * Fetches and returns a given filtered variable. The unsigned integer
	 * filter will allow only digits to be returned. This is currently
	 * only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @param   string  $name     Variable name.
	 * @param   string  $default  Default value if the variable does not exist.
	 * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
	 *
	 * @return  integer  Requested variable.
	 *
	 * @deprecated  12.1
	 * @since       11.1
	 */
	public static function getUInt($name, $default = 0, $hash = 'default')
	{
		return self::getVar($name, $default, $hash, 'uint');
	}

	/**
	 * Fetches and returns a given filtered variable.  The float
	 * filter only allows digits and periods.  This is currently
	 * only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @param   string  $name     Variable name.
	 * @param   string  $default  Default value if the variable does not exist.
	 * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
	 *
	 * @return  float  Requested variable.
	 *
	 * @since   11.1
	 *
	 * @deprecated   12.1
	 */
	public static function getFloat($name, $default = 0.0, $hash = 'default')
	{
		return self::getVar($name, $default, $hash, 'float');
	}

	/**
	 * Fetches and returns a given filtered variable. The bool
	 * filter will only return true/false bool values. This is
	 * currently only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @param   string  $name     Variable name.
	 * @param   string  $default  Default value if the variable does not exist.
	 * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
	 *
	 * @return  boolean  Requested variable.
	 *
	 * @deprecated  12.1
	 * @since       11.1
	 */
	public static function getBool($name, $default = false, $hash = 'default')
	{
		return self::getVar($name, $default, $hash, 'bool');
	}

	/**
	 * Fetches and returns a given filtered variable. The word
	 * filter only allows the characters [A-Za-z_]. This is currently
	 * only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @param   string  $name     Variable name.
	 * @param   string  $default  Default value if the variable does not exist.
	 * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
	 *
	 * @return  string  Requested variable.
	 *
	 * @since   11.1
	 *
	 * @deprecated   12.1
	 */
	public static function getWord($name, $default = '', $hash = 'default')
	{
		return self::getVar($name, $default, $hash, 'word');
	}

	/**
	 * Cmd (Word and Integer0 filter
	 *
	 * Fetches and returns a given filtered variable. The cmd
	 * filter only allows the characters [A-Za-z0-9.-_]. This is
	 * currently only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @param   string  $name     Variable name
	 * @param   string  $default  Default value if the variable does not exist
	 * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 *
	 * @return  string  Requested variable
	 *
	 * @deprecated  12.1
	 * @since       11.1
	 */
	public static function getCmd($name, $default = '', $hash = 'default')
	{
		return self::getVar($name, $default, $hash, 'cmd');
	}
	
	public static function getHTML($name, $default = '', $hash = 'default', $mask = 4)
	{
		return self::getVar($name, $default, $hash, 'string', $mask);
	}

	/**
	 * Fetches and returns a given filtered variable. The string
	 * filter deletes 'bad' HTML code, if not overridden by the mask.
	 * This is currently only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @param   string   $name     Variable name
	 * @param   string   $default  Default value if the variable does not exist
	 * @param   string   $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @param   integer  $mask     Filter mask for the variable
	 *
	 * @return  string   Requested variable
	 *
	 * @since   11.1
	 *
	 * @deprecated   12.1
	 */
	public static function getString($name, $default = '', $hash = 'default', $mask = 0)
	{
		// Cast to string, in case JREQUEST_ALLOWRAW was specified for mask
		return (string) self::getVar($name, $default, $hash, 'string', $mask);
	}
	
	/**
	 * Fetches and returns a request array.
	 *
	 * The default behaviour is fetching variables depending on the
	 * current request method: GET and HEAD will result in returning
	 * $_GET, POST and PUT will result in returning $_POST.
	 *
	 * You can force the source by setting the $hash parameter:
	 *
	 * post     $_POST
	 * get      $_GET
	 * files    $_FILES
	 * cookie   $_COOKIE
	 * env      $_ENV
	 * server   $_SERVER
	 * method   via current $_SERVER['REQUEST_METHOD']
	 * default  $_REQUEST
	 *
	 * @param   string   $hash  to get (POST, GET, FILES, METHOD).
	 * @param   integer  $mask  Filter mask for the variable.
	 *
	 * @return  mixed    Request hash.
	 *
	 * @deprecated  12.1   User JInput::get
	 * @see         JInput
	 * @since       11.1
	 */
	public static function get($hash = 'default', $mask = 0)
	{
		$hash = strtoupper($hash);

		if ($hash === 'METHOD')
		{
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}

		switch ($hash)
		{
			case 'GET':
				$input = $_GET;
				break;

			case 'POST':
				$input = $_POST;
				break;

			case 'FILES':
				$input = $_FILES;
				break;

			case 'COOKIE':
				$input = $_COOKIE;
				break;

			case 'ENV':
				$input = &$_ENV;
				break;

			case 'SERVER':
				$input = &$_SERVER;
				break;

			default:
				$input = $_REQUEST;
				break;
		}

		$result = self::_cleanVar($input, $mask);

		// Handle magic quotes compatibility
		if (get_magic_quotes_gpc() && ($hash != 'FILES'))
		{
			$result = self::_stripSlashesRecursive($result);
		}

		return $result;
	}
	
	/**
	 * Clean up an input variable.
	 *
	 * @param   mixed    $var   The input variable.
	 * @param   integer  $mask  Filter bit mask.
	 *                           1 = no trim: If this flag is cleared and the input is a string, the string will have leading and trailing
	 *                               whitespace trimmed.
	 *                           2 = allow_raw: If set, no more filtering is performed, higher bits are ignored.
	 *                           4 = allow_html: HTML is allowed, but passed through a safe HTML filter first. If set, no more filtering
	 *                               is performed. If no bits other than the 1 bit is set, a strict filter is applied.
	 * @param   string   $type  The variable type {@see JFilterInput::clean()}.
	 *
	 * @return  mixed  Same as $var
	 *
	 * @deprecated  12.1
	 * @since       11.1
	 */
	static function _cleanVar($var, $mask = 0, $type = null)
	{
		// If the no trim flag is not set, trim the variable
		if (!($mask & 1) && is_string($var))
		{
			$var = trim($var);
		}

		// Now we handle input filtering
		if ($mask & 2)
		{
			// If the allow raw flag is set, do not modify the variable
			$var = $var;
		}
		elseif ($mask & 4)
		{
			$purifier = new CHtmlPurifier();
			$purifier->options = array(
			    'Attr.AllowedFrameTargets'=> array('_blank','_self','_parent','_top'), 
			    'HTML.Allowed'=> 'p,a[href|rel|target|title],img[src],span[style],strong,em,ul,ol,li,b,i,br,div',
			);
			$var = $purifier->purify($var);
		}
		else
		{
			// Since no allow flags were set, we will apply the most strict filter to the variable
			// $tags, $attr, $tag_method, $attr_method, $xss_auto use defaults.
			$purifier = new CHtmlPurifier();
			$var = $purifier->purify($var);
		}
		return $var;
	}

	/**
	 * Cleans the request from script injection.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 *
	 * @deprecated   12.1
	 */
	public static function clean()
	{
		// Only run this if register globals is on.
		// Remove this code when PHP 5.4 becomes the minimum requirement.
		if (!(bool) ini_get('register_globals'))
		{
			return;
		}

		$REQUEST = $_REQUEST;
		$GET = $_GET;
		$POST = $_POST;
		$COOKIE = $_COOKIE;
		$FILES = $_FILES;
		$ENV = $_ENV;
		$SERVER = $_SERVER;

		if (isset($_SESSION))
		{
			$SESSION = $_SESSION;
		}

		foreach ($GLOBALS as $key => $value)
		{
			if ($key != 'GLOBALS')
			{
				unset($GLOBALS[$key]);
			}
		}
		$_REQUEST = $REQUEST;
		$_GET = $GET;
		$_POST = $POST;
		$_COOKIE = $COOKIE;
		$_FILES = $FILES;
		$_ENV = $ENV;
		$_SERVER = $SERVER;

		if (isset($SESSION))
		{
			$_SESSION = $SESSION;
		}

		// Make sure the request hash is clean on file inclusion
		$GLOBALS['_JREQUEST'] = array();
	}	
	/**
	 * Strips slashes recursively on an array.
	 *
	 * @param   array  $value  Array or (nested arrays) of strings.
	 *
	 * @return  array  The input array with stripslashes applied to it.
	 *
	 * @deprecated  12.1
	 * @since       11.1
	 */
	protected static function _stripSlashesRecursive($value)
	{
		$value = is_array($value) ? array_map(array('oRequest', '_stripSlashesRecursive'), $value) : stripslashes($value);
		return $value;
	}
}
?>
