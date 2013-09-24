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
require_once (dirname(__FILE__).DS.'abstract.php'); 
class oseJoomla extends oseFramework 
{
	private $debugMode = false;
	private static $option = '';
	public function __construct ($debug) {
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
		return $app ->isAdmin();
	}
    public function loadBackendFunctions(){}
    public static function isDBReady(){}
    public static function getLocale () {
    	$lang = JFactory::getLanguage();
    	return $lang->getDefault();
    }
    public static function loadallJs ($cs) {
		$cs->registerCoreScript('extjsneptune');
		$cs->registerCoreScript('extjs');
		$cs->registerCoreScript('oseelements');
		$cs->registerCoreScript('osefunctions');
		$cs->registerCoreScript('ItemSelector');
		$cs->registerCoreScript('MultiSelect');
    }
	public static function loadGridAssets () {
    	$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$cs->registerCoreScript('SearchField');
		$cs->registerCoreScript('SlidingPager');
    }
    public static function loadFormAssets () {
    	$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$cs->registerCoreScript('tinymce');
		$cs->registerCoreScript('TinyMCE');
    }
    public static function getDBO () {
    	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS . 'db'. DS .'joomla.php');
    	$db = new oseDB2Joomla(); 
    	return $db; 
    }
    public static function loadJSON () {
    	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS.'json'.DS . 'oseJSON.php');
    }
    
    public static function loadInstaller () { }
    public static function getAjaxScript() { }
    public static function getSiteURL () {
    	return JURI::root();
    }
}
?>