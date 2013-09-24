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
  
if (!defined('OSE_FRAMEWORK')) { 
	define ('OSE_FRAMEWORK', true) ; 
}

abstract class oseFramework 
{
	private $debugMode = false; 
	/* 
	 * checks if the file is currently in the backend 
	 * @params: n/a
	 */
	abstract protected function isAdmin();
	abstract protected function isBackend ();
    /* 
	 * loadds all backend functions necesssary to run wordpress 
	 * @params: n/a
	 */
    abstract protected function loadBackendFunctions();
    public function setDebugMode ($debug)
    {
    	$this->debugMode = $debug; 
    }
    public function getDebugMode ()
    {
    	return $this->debugMode; 
    }
    private function initYiiConfiguration () 
    {
    	// remove the following lines when in production mode
		defined('YII_DEBUG') or define('YII_DEBUG',$this->debugMode);
		// specify how many levels of call stack should be shown in each log message
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    }
    public static function runYiiApp() 
    {
    	require_once(OSE_FRAMEWORKDIR.DS.'yii.php');
    	$config=OSEAPPDIR.DS.'protected'.DS.'config'.DS.'main.php';
		Yii::createWebApplication($config);
    }
    public function initSystem () 
    {
    	$this ->initYiiConfiguration ();
    }
    public static function loadFiles () {
    	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS.'files'.DS . 'oseFile.php');
    }
    public static function loadRequest () {
    	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS.'request'.DS . 'oseRequest.php');
    }
    public static function loadEmails () {
    	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS.'emails'.DS . 'oseEmail.php');
    }
    public static function loadUsers () {
    	require_once (OSE_FRAMEWORKDIR . DS . 'oseframework' . DS.'users'.DS . 'oseUsers.php');
    }
    public static function loadBackendCSS ($cs,$baseUrl) {
    	$cs->registerCssFile($baseUrl . '/public/css/backendv6.css');
		$cs->registerCssFile($baseUrl . '/public/css/bootmetro-icons.min.css');
		$cs->registerCssFile($baseUrl . '/public/css/ext-debug.css');
		$cs->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300');
    }
	public static function loadFrontendCSS ($cs,$baseUrl) {
    	$cs->registerCssFile($baseUrl . '/public/css/frontend.css');
    	$cs->registerCssFile($baseUrl . '/public/css/bootmetro-icons.min.css');
    	$cs->registerCssFile($baseUrl . '/public/css/ext-debug.css');
    }   
}
?>