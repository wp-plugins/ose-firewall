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

/**
 * CGoogleApi class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CGoogleApi provides helper methods to easily access the {@link https://developers.google.com/loader/ Google API loader}.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.helpers
 */
class CGoogleApi
{
	/**
	* @var string Protocol relative url to the Google API loader which allows easy access
	* to most of the Google AJAX APIs
	*/
	public static $bootstrapUrl='//www.google.com/jsapi';

	/**
	 * Renders the jsapi script file.
	 * @param string $apiKey the API key. Null if you do not have a key.
	 * @return string the script tag that loads Google jsapi.
	 */
	public static function init($apiKey=null)
	{
		if($apiKey===null)
			return CHtml::scriptFile(self::$bootstrapUrl);
		else
			return CHtml::scriptFile(self::$bootstrapUrl.'?key='.$apiKey);
	}

	/**
	 * Loads the specified API module.
	 * Note that you should call {@link init} first.
	 * @param string $name the module name
	 * @param string $version the module version
	 * @param array $options additional js options that are to be passed to the load() function.
	 * @return string the js code for loading the module. You can use {@link CHtml::script()}
	 * to enclose it in a script tag.
	 */
	public static function load($name,$version='1',$options=array())
	{
		if(empty($options))
			return "google.load(\"{$name}\",\"{$version}\");";
		else
			return "google.load(\"{$name}\",\"{$version}\",".CJavaScript::encode($options).");";
	}

	/**
	 * Registers the specified API module.
	 * This is similar to {@link load} except that it registers the loading code
	 * with {@link CClientScript} instead of returning it.
	 * This method also registers the jsapi script needed by the loading call.
	 * @param string $name the module name
	 * @param string $version the module version
	 * @param array $options additional js options that are to be passed to the load() function.
	 * @param string $apiKey the API key. Null if you do not have a key.
	 */
	public static function register($name,$version='1',$options=array(),$apiKey=null)
	{
		$cs=Yii::app()->getClientScript();
		$url=$apiKey===null?self::$bootstrapUrl:self::$bootstrapUrl.'?key='.$apiKey;
		$cs->registerScriptFile($url,CClientScript::POS_HEAD);

		$js=self::load($name,$version,$options);
		$cs->registerScript($name,$js,CClientScript::POS_HEAD);
	}
}