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
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH') && !defined('_JEXEC'))
{
	die('Direct Access Not Allowed');
}
class oseUsers
{
	private $cms = null;
	private $db = null;
	private static $cmsStatic = null;
	public function __construct($app)
	{
		$this->app = $app;
		$this->setCMS();
		$this->setDB();
	}
	private function setCMS()
	{
		if (defined('_JEXEC'))
		{
			$this->cms = 'joomla';
		}
		else
			if (defined('WPLANG') || defined('WPINC'))
			{
				$this->cms = 'wordpress';
			}
	}
	private static function setCMSStatic()
	{
		if (defined('_JEXEC'))
		{
			self::$cmsStatic = 'joomla';
		}
		else
			if (defined('WPLANG') || defined('WPINC'))
			{
				self::$cmsStatic = 'wordpress';
			}
	}
	private function setDB()
	{
		switch ($this->cms)
		{
		case 'joomla':
			$this->db = oseJoomla::getDBO();
			break;
		case 'wordpress':
			$this->db = oseWordpress::getDBO();
			break;
		}
	}
	public function getAdminUsers()
	{
		if ($this->cms == 'joomla')
		{
			$admins = $this->getJoomlaAdmin();
		}
		else
		{
			$admins = $this->getWordpressAdmin();
		}
		return $admins;
	}
	public function getAllUsers()
	{
		if ($this->cms == 'joomla')
		{
			$admins = $this->getJoomlaUsers();
		}
		else
		{
			$admins = $this->getWordpressUsers();
		}
		return $admins;
	}
	private function getJoomlaAdmin()
	{
		$groupids = $this->getJoomlaAdminGroups();
		$where = array();
		$where[] = ' `group_id` IN ('.implode(',', $groupids).') ';
		$where = $this->db->implodeWhere($where);
		$query = 'SELECT `id`, `name` FROM `#__users` AS user LEFT JOIN `#__user_usergroup_map` as map ON user.id = map.user_id '.$where;
		$this->db->setQuery($query);
		return $this->db->loadObjectList();
	}
	private function getWordpressAdmin()
	{
		$adminids = $this->get_super_admins();
		$return = array();
		for ($i = 0; $i < COUNT($adminids); $i++)
		{
			$user = get_user_by('id', $adminids[$i]);
			$return[$i]['id'] = $user->ID;
			$return[$i]['name'] = $user->display_name;
		}
		return $return;
	}
	public function get_super_admins()
	{
		$query = "SELECT * FROM `#__usermeta` where `meta_value` LIKE '%administrator%'";
		$this->db->setQuery($query);
		$objList = $this->db->loadObjectList();
		$return = array();
		foreach ($objList as $obj)
		{
			if (preg_match("/[wp|\w+]\_*capabilities/", $obj->meta_key))
			{
				$return[] = $obj->user_id;
			}
		}
		return $return;
	}
	private function getWordpressUsers()
	{
		$where = '';
		oseFramework::loadRequest();
		$query = oRequest::getVar('query', null);
		if (!empty($query))
		{
			$where = ' WHERE `user_nicename` LIKE "%'.$this->db->quoteValue($query).'%"';
		}
		$query = "SELECT `ID`, `user_nicename` as `name` FROM `#__users` ".$where;
		$this->db->setQuery($query);
		return $this->db->loadObjectList();
	}
	public function getJoomlaAdminGroups()
	{
		$groups = $this->getJoomlaUserGroupsDB();
		$admin_groups = array();
		foreach ($groups as $group_id)
		{
			if (JAccess::checkGroup($group_id, 'core.login.admin'))
			{
				$admin_groups[] = $group_id;
			}
			elseif (JAccess::checkGroup($group_id, 'core.admin'))
			{
				$admin_groups[] = $group_id;
			}
		}
		$admin_groups = array_unique($admin_groups);
		return $admin_groups;
	}
	private function getJoomlaUserGroupsDB()
	{
		$this->db->setQuery("SELECT `id` FROM `#__usergroups`");
		$groups = $this->db->loadObjectList();
		$return = array();
		foreach ($groups as $group)
		{
			$return[] = $group->id;
		}
		return $return;
	}
	public function getJoomlaUserGroups()
	{
		$user = JFactory::getUser();
		$user_groups = JAccess::getGroupsByUser($user->id);
		return $user_groups;
	}
	public function registerUser($userInfo)
	{
		return wp_insert_user($userInfo);
	}
	public static function isLoggedin()
	{
		if (class_exists('oseWordpress'))
		{
			return is_user_logged_in();
		}
		else
		{
			$user = JFactory::getUser();
			return ($user->guest == true) ? false : true;
		}
	}
	public static function getUserID()
	{
		self::setCMSStatic();
		if (self::$cmsStatic == 'joomla')
		{
			$user = JFactory::getUser();
			return $user->id;
		}
		else
		{
			$current_user = wp_get_current_user();
			return $current_user->ID;
		}
	}
	public function isAdmin()
	{
		self::setCMSStatic();
		if (self::$cmsStatic == 'joomla')
		{
			$current_user = JFactory::getUser();
			$adminids = $this->getJoomlaAdminGroups();
			$result = false;
			foreach ($current_user->groups as $group)
			{
				if (in_array($group, $adminids))
				{
					$result = true;
					break;
				}
			}
			return $result;
		}
		else
		{
			if (!function_exists('wp_get_current_user')) {
				$this->loadUserClass ();
			}
			$current_user = wp_get_current_user();
			$adminids = $this->get_super_admins ();
			return (in_array($current_user->ID, $adminids));
		}
	}
	protected function loadUserClass () {
		require_once(ABSPATH."wp-includes/pluggable.php");
		require_once(ABSPATH."wp-includes/functions.php");
	}
	public static function getUserLogin()
	{
		self::setCMSStatic();
		if (self::$cmsStatic == 'joomla')
		{
			$current_user = JFactory::getUser();
			return $current_user->username;
		}
		else
		{
			$current_user = wp_get_current_user();
			return $current_user->user_login;
		}
	}
	public static function getUserEmail()
	{
		self::setCMSStatic();
		if (self::$cmsStatic == 'joomla')
		{
			$current_user = JFactory::getUser();
			return $current_user->email;
		}
		else
		{
			$current_user = wp_get_current_user();
			return $current_user->user_email;
		}
	}
}