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
require_once(dirname(__FILE__).ODS.'abstract.php');
class oseJoomla extends oseFramework
{
	private $debugMode = false;
	private static $option = '';
	public function __construct($debug)
	{
		$this->setDebugMode ($debug);
	}
	public function isAdmin()
	{
		$user = JFactory::getUser();
		return $user->get('isRoot');
	}
	public function isBackend()
	{
		$app = JFactory::getApplication();
		return $app->isAdmin();
	}
	public static function isBackendStatic()
	{
		$app = JFactory::getApplication();
		return $app->isAdmin();
	}
	public function loadBackendFunctions()
	{
	}
	public static function isDBReady()
	{
	}
	public static function getLocale()
	{
		if (class_exists('JFactory', false)) {
	        $getlang = JFactory::getLanguage();
			return (string)$getlang->get('tag');
		}
		else {
			return 'en-GB';
		}
	}
	public static function loadallJs($cs)
	{
		$cs->registerCoreScript('extjsneptune');
		$cs->registerCoreScript('extjs');
		$cs->registerCoreScript('oseelements');
		$cs->registerCoreScript('osefunctions');
		$cs->registerCoreScript('ItemSelector');
		$cs->registerCoreScript('MultiSelect');
	}
	public static function loadGridAssets()
	{
		$baseUrl = Yii::app()->baseUrl;
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('SearchField');
		$cs->registerCoreScript('SlidingPager');
	}
	public static function loadFormAssets()
	{
		$baseUrl = Yii::app()->baseUrl;
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('tinymce');
		$cs->registerCoreScript('TinyMCE');
	}
	public static function getDBO()
	{
		global $osedbo;
		if (!isset($osedbo))
		{
			require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'db'.ODS.'joomla.php');
			$osedbo = new oseDB2Joomla();
		}	
		return $osedbo;
	}
	public static function loadJSON()
	{
		require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'json'.ODS.'oseJSON.php');
	}
	public static function loadInstaller()
	{
	}
	public static function getAjaxScript()
	{
	}
	public static function getSiteURL()
	{
		return JURI::root();
	}
	public static function getConfig()
	{
		if (class_exists("SConfig"))
		{
			$config = new SConfig();
		}
		else
			if (class_exists("JConfig"))
			{
				$config = new JConfig();
			}
			else
			{
				$config = JFactory::getConfig();
			}
		$copy = new stdClass();
		$copy->host = $config->host;
		$copy->db = $config->db;
		$copy->user = $config->user;
		$copy->password = $config->password;
		$copy->prefix = $config->dbprefix;
		$copy->mailfrom = $config->mailfrom;
		return $copy;
	}
}
?>