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
	die('Direct Access Not Allowed');
}
require_once(OSE_FWFRAMEWORK.ODS.'firewallstat'.ODS.'firewallstatBase.php');
class oseFirewallStat extends oseFirewallStatBase
{
	public function isUserAdminExist()
	{
		if (username_exists('admin') == true)
		{
			$user = get_user_by('login', 'admin');
			return $user->ID;
		}
		else
		{
			return false;
		}
	}
	public function isWPUpToDate () {
	    global $wp_version;
	    $updates = get_core_updates();
	    if(!is_array($updates) || empty($updates) || $updates[0]->response == 'latest'){
	        $current = true;
	    } else {
	        $current = false;
	    }
	    if(strcmp($wp_version, "3.7") < 0)
	    {
	        $current = false;
	    }
	    return $current; 
	}
	public function changeusername ($username) {
		$user = get_user_by('login', 'admin');
		$db = oseFirewall::getDBO ();
		$varValues = array (
				'user_login' => $username
		);
		$result = $db->addData('update', '#__users', 'ID', (int)$user->ID, $varValues);
		$db->closeDBO ();
		return $result;
	}
}
