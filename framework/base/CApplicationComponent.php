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
 * This file contains the base application component class.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CApplicationComponent is the base class for application component classes.
 *
 * CApplicationComponent implements the basic methods required by {@link IApplicationComponent}.
 *
 * When developing an application component, try to put application component initialization code in
 * the {@link init()} method instead of the constructor. This has the advantage that
 * the application component can be customized through application configuration.
 *
 * @property boolean $isInitialized Whether this application component has been initialized (ie, {@link init()} is invoked).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.base
 * @since 1.0
 */
abstract class CApplicationComponent extends CComponent implements IApplicationComponent
{
	/**
	 * @var array the behaviors that should be attached to this component.
	 * The behaviors will be attached to the component when {@link init} is called.
	 * Please refer to {@link CModel::behaviors} on how to specify the value of this property.
	 */
	public $behaviors=array();

	private $_initialized=false;

	/**
	 * Initializes the application component.
	 * This method is required by {@link IApplicationComponent} and is invoked by application.
	 * If you override this method, make sure to call the parent implementation
	 * so that the application component can be marked as initialized.
	 */
	public function init()
	{
		$this->attachBehaviors($this->behaviors);
		$this->_initialized=true;
	}

	/**
	 * Checks if this application component bas been initialized.
	 * @return boolean whether this application component has been initialized (ie, {@link init()} is invoked).
	 */
	public function getIsInitialized()
	{
		return $this->_initialized;
	}
}
