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

class oseAjax
{
	public static function runAction()
	{
		oseFramework::runYiiApp();
		self::secureCheck();
		Yii::app()->runController($_REQUEST['controller'].'/'.$_REQUEST['task']);
	}
	private static function secureCheck()
	{
		oseFramework::loadUsers();
		$oseUsers = new oseUsers('oseFirewall');
		$isadmin = $oseUsers->isAdmin();
		if ($isadmin)
		{
			return;
		}
		else
		{
			$centnounce = (isset($_REQUEST['centnounce'])) ? $_REQUEST['centnounce'] : '';
			if (!self::verifyNounce($centnounce))
			{
				die('Sorry, our software is CSRF proof.');
			}
		}
	}
	private static function verifyNounce($centnounce)
	{
		if (OSE_CMS == 'joomla')
		{
			return true;
		}
		else
		{
			return wp_verify_nonce($centnounce, 'centnounce');
		}
	}
	public static function addActions($func)
	{
		if (class_exists('oseWordpress'))
		{
			add_action('wp_ajax_'.$func, 'oseAjax::runAction');
		}
		else
		{
			if (isset($_REQUEST['controller']) && isset($_REQUEST['task']))
			{
				self::runAction();
			}
		}
	}
	public static function loadActions($actions)
	{
		foreach ($actions as $action)
		{
			self::addActions($action);
		}
	}
	public static function aJaxReturn($result, $status, $msg, $continue = false, $id = null)
	{
		oseFramework::loadJSON();
		oseFramework::loadRequest();
		$return = array(
			'success' => (boolean) $result,
			'status' => $status,
			'result' => $msg,
			'cont' => (boolean) $continue,
			'id' => (int) $id
		);
		$tmp = oseJSON::encode($return);
		$callback = oRequest::getVar('callback', null);
		if ($callback == null)
		{
			print_r($tmp);
		}
		else
		{
			header("Content-Type: text/javascript");
			$return = $callback.'('.$tmp.');';
			print_r($return);
		}
		exit;
	}
	public static function returnJSON($var, $mobiledevice = false)
	{
		oseFramework::loadJSON();
		oseFramework::loadRequest();
		$callback = oRequest::getVar('callback', null);
		if ($callback == null)
		{
			print_r(oseJSON::encode($var));
		}
		else
		{
			header("Content-Type: text/javascript");
			$return = $callback.'('.oseJSON::encode($var).');';
			print_r($return);
		}
		exit;
	}
	public static function throwAjaxRecursive($result, $status, $msg, $continue, $step)
	{
		$return = array(
			'success' => (boolean) $result,
			'status' => $status,
			'result' => $msg,
			'cont' => (boolean) $continue,
			'step' => (int) $step,
		);
		$tmp = oseJSON::encode($return);
		print_r($tmp);
		exit;
	}
}
