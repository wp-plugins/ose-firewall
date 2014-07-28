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
 * CActiveRecordBehavior class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CActiveRecordBehavior is the base class for behaviors that can be attached to {@link CActiveRecord}.
 * Compared with {@link CModelBehavior}, CActiveRecordBehavior attaches to more events
 * that are only defined by {@link CActiveRecord}.
 *
 * @property CActiveRecord $owner The owner AR that this behavior is attached to.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.db.ar
 */
class CActiveRecordBehavior extends CModelBehavior
{
	/**
	 * Declares events and the corresponding event handler methods.
	 * If you override this method, make sure you merge the parent result to the return value.
	 * @return array events (array keys) and the corresponding event handler methods (array values).
	 * @see CBehavior::events
	 */
	public function events()
	{
		return array_merge(parent::events(), array(
			'onBeforeSave'=>'beforeSave',
			'onAfterSave'=>'afterSave',
			'onBeforeDelete'=>'beforeDelete',
			'onAfterDelete'=>'afterDelete',
			'onBeforeFind'=>'beforeFind',
			'onAfterFind'=>'afterFind',
		));
	}

	/**
	 * Responds to {@link CActiveRecord::onBeforeSave} event.
	 * Override this method and make it public if you want to handle the corresponding
	 * event of the {@link CBehavior::owner owner}.
	 * You may set {@link CModelEvent::isValid} to be false to quit the saving process.
	 * @param CModelEvent $event event parameter
	 */
	protected function beforeSave($event)
	{
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterSave} event.
	 * Override this method and make it public if you want to handle the corresponding event
	 * of the {@link CBehavior::owner owner}.
	 * @param CModelEvent $event event parameter
	 */
	protected function afterSave($event)
	{
	}

	/**
	 * Responds to {@link CActiveRecord::onBeforeDelete} event.
	 * Override this method and make it public if you want to handle the corresponding event
	 * of the {@link CBehavior::owner owner}.
	 * You may set {@link CModelEvent::isValid} to be false to quit the deletion process.
	 * @param CEvent $event event parameter
	 */
	protected function beforeDelete($event)
	{
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterDelete} event.
	 * Override this method and make it public if you want to handle the corresponding event
	 * of the {@link CBehavior::owner owner}.
	 * @param CEvent $event event parameter
	 */
	protected function afterDelete($event)
	{
	}

	/**
	 * Responds to {@link CActiveRecord::onBeforeFind} event.
	 * Override this method and make it public if you want to handle the corresponding event
	 * of the {@link CBehavior::owner owner}.
	 * @param CEvent $event event parameter
	 */
	protected function beforeFind($event)
	{
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterFind} event.
	 * Override this method and make it public if you want to handle the corresponding event
	 * of the {@link CBehavior::owner owner}.
	 * @param CEvent $event event parameter
	 */
	protected function afterFind($event)
	{
	}
}
