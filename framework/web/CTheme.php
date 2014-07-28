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
 * CTheme class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CTheme represents an application theme.
 *
 * @property string $name Theme name.
 * @property string $baseUrl The relative URL to the theme folder (without ending slash).
 * @property string $basePath The file path to the theme folder.
 * @property string $viewPath The path for controller views. Defaults to 'ThemeRoot/views'.
 * @property string $systemViewPath The path for system views. Defaults to 'ThemeRoot/views/system'.
 * @property string $skinPath The path for widget skins. Defaults to 'ThemeRoot/views/skins'.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web
 * @since 1.0
 */
class CTheme extends CComponent
{
	private $_name;
	private $_basePath;
	private $_baseUrl;

	/**
	 * Constructor.
	 * @param string $name name of the theme
	 * @param string $basePath base theme path
	 * @param string $baseUrl base theme URL
	 */
	public function __construct($name,$basePath,$baseUrl)
	{
		$this->_name=$name;
		$this->_baseUrl=$baseUrl;
		$this->_basePath=$basePath;
	}

	/**
	 * @return string theme name
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @return string the relative URL to the theme folder (without ending slash)
	 */
	public function getBaseUrl()
	{
		return $this->_baseUrl;
	}

	/**
	 * @return string the file path to the theme folder
	 */
	public function getBasePath()
	{
		return $this->_basePath;
	}

	/**
	 * @return string the path for controller views. Defaults to 'ThemeRoot/views'.
	 */
	public function getViewPath()
	{
		return $this->_basePath.DIRECTORY_SEPARATOR.'views';
	}

	/**
	 * @return string the path for system views. Defaults to 'ThemeRoot/views/system'.
	 */
	public function getSystemViewPath()
	{
		return $this->getViewPath().DIRECTORY_SEPARATOR.'system';
	}

	/**
	 * @return string the path for widget skins. Defaults to 'ThemeRoot/views/skins'.
	 * @since 1.1
	 */
	public function getSkinPath()
	{
		return $this->getViewPath().DIRECTORY_SEPARATOR.'skins';
	}

	/**
	 * Finds the view file for the specified controller's view.
	 * @param CController $controller the controller
	 * @param string $viewName the view name
	 * @return string the view file path. False if the file does not exist.
	 */
	public function getViewFile($controller,$viewName)
	{
		$moduleViewPath=$this->getViewPath();
		if(($module=$controller->getModule())!==null)
			$moduleViewPath.='/'.$module->getId();
		return $controller->resolveViewFile($viewName,$this->getViewPath().'/'.$controller->getUniqueId(),$this->getViewPath(),$moduleViewPath);
	}

	/**
	 * Finds the layout file for the specified controller's layout.
	 * @param CController $controller the controller
	 * @param string $layoutName the layout name
	 * @return string the layout file path. False if the file does not exist.
	 */
	public function getLayoutFile($controller,$layoutName)
	{
		$moduleViewPath=$basePath=$this->getViewPath();
		$module=$controller->getModule();
		if(empty($layoutName))
		{
			while($module!==null)
			{
				if($module->layout===false)
					return false;
				if(!empty($module->layout))
					break;
				$module=$module->getParentModule();
			}
			if($module===null)
				$layoutName=Yii::app()->layout;
			else
			{
				$layoutName=$module->layout;
				$moduleViewPath.='/'.$module->getId();
			}
		}
		elseif($module!==null)
			$moduleViewPath.='/'.$module->getId();

		return $controller->resolveViewFile($layoutName,$moduleViewPath.'/layouts',$basePath,$moduleViewPath);
	}
}
