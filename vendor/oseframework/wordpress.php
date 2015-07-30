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
class oseWordPress extends oseFramework
{
	private $debugMode = false;
	private static $option = '';
	public function __construct($debug)
	{
		$this->setDebugMode ($debug);
	}
	public function isAdmin()
	{
		return is_admin();
	}
	public function isBackend()
	{
		return is_admin();
	}
	public function isAdminAjax() {
		if (preg_match("/admin\-ajax\.php/", $_SERVER['PHP_SELF']))
		{
			if (!empty($_REQUEST['option']) && $_REQUEST['option'] == 'com_ose_firewall')
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	public static function isBackendStatic()
	{
		return is_admin();
	}
	public function loadBackendFunctions()
	{
	}
	public static function isDBReady()
	{
	}
	public static function getLocale()
	{
		return get_locale();
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
			require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'db'.ODS.'wordpress.php');
			$osedbo = new oseDB2Wordpress();
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
	public static function getSiteURL()
	{
		return get_site_url();
	}
	public static function getConfig()
	{
		global $wpdb;
		$copy = new stdClass();
		$copy->host = $wpdb->dbhost;
		$copy->db = $wpdb->dbname;
		$copy->user = $wpdb->dbuser;
		$copy->password = $wpdb->dbpassword;
		$copy->prefix = $wpdb->base_prefix;
		if (isset($wpdb->use_mysqli))
		{	
			$copy->use_mysqli = $wpdb->use_mysqli;
		}
		else 
		{
			$copy->use_mysqli = false;
		}
		$copy->dbtype = ($copy->use_mysqli==true)?'mysqli':'mysql';
		return $copy;
	}
}
?>