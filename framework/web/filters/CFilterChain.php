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
 * CFilterChain class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CFilterChain represents a list of filters being applied to an action.
 *
 * CFilterChain executes the filter list by {@link run()}.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.filters
 * @since 1.0
 */
class CFilterChain extends CList
{
	/**
	 * @var CController the controller who executes the action.
	 */
	public $controller;
	/**
	 * @var CAction the action being filtered by this chain.
	 */
	public $action;
	/**
	 * @var integer the index of the filter that is to be executed when calling {@link run()}.
	 */
	public $filterIndex=0;


	/**
	 * Constructor.
	 * @param CController $controller the controller who executes the action.
	 * @param CAction $action the action being filtered by this chain.
	 */
	public function __construct($controller,$action)
	{
		$this->controller=$controller;
		$this->action=$action;
	}

	/**
	 * CFilterChain factory method.
	 * This method creates a CFilterChain instance.
	 * @param CController $controller the controller who executes the action.
	 * @param CAction $action the action being filtered by this chain.
	 * @param array $filters list of filters to be applied to the action.
	 * @return CFilterChain
	 */
	public static function create($controller,$action,$filters)
	{
		$chain=new CFilterChain($controller,$action);

		$actionID=$action->getId();
		foreach($filters as $filter)
		{
			if(is_string($filter))  // filterName [+|- action1 action2]
			{
				if(($pos=strpos($filter,'+'))!==false || ($pos=strpos($filter,'-'))!==false)
				{
					$matched=preg_match("/\b{$actionID}\b/i",substr($filter,$pos+1))>0;
					if(($filter[$pos]==='+')===$matched)
						$filter=CInlineFilter::create($controller,trim(substr($filter,0,$pos)));
				}
				else
					$filter=CInlineFilter::create($controller,$filter);
			}
			elseif(is_array($filter))  // array('path.to.class [+|- action1, action2]','param1'=>'value1',...)
			{
				if(!isset($filter[0]))
					throw new CException(Yii::t('yii','The first element in a filter configuration must be the filter class.'));
				$filterClass=$filter[0];
				unset($filter[0]);
				if(($pos=strpos($filterClass,'+'))!==false || ($pos=strpos($filterClass,'-'))!==false)
				{
					$matched=preg_match("/\b{$actionID}\b/i",substr($filterClass,$pos+1))>0;
					if(($filterClass[$pos]==='+')===$matched)
						$filterClass=trim(substr($filterClass,0,$pos));
					else
						continue;
				}
				$filter['class']=$filterClass;
				$filter=Yii::createComponent($filter);
			}

			if(is_object($filter))
			{
				$filter->init();
				$chain->add($filter);
			}
		}
		return $chain;
	}

	/**
	 * Inserts an item at the specified position.
	 * This method overrides the parent implementation by adding
	 * additional check for the item to be added. In particular,
	 * only objects implementing {@link IFilter} can be added to the list.
	 * @param integer $index the specified position.
	 * @param mixed $item new item
	 * @throws CException If the index specified exceeds the bound or the list is read-only, or the item is not an {@link IFilter} instance.
	 */
	public function insertAt($index,$item)
	{
		if($item instanceof IFilter)
			parent::insertAt($index,$item);
		else
			throw new CException(Yii::t('yii','CFilterChain can only take objects implementing the IFilter interface.'));
	}

	/**
	 * Executes the filter indexed at {@link filterIndex}.
	 * After this method is called, {@link filterIndex} will be automatically incremented by one.
	 * This method is usually invoked in filters so that the filtering process
	 * can continue and the action can be executed.
	 */
	public function run()
	{
		if($this->offsetExists($this->filterIndex))
		{
			$filter=$this->itemAt($this->filterIndex++);
			Yii::trace('Running filter '.($filter instanceof CInlineFilter ? get_class($this->controller).'.filter'.$filter->name.'()':get_class($filter).'.filter()'),'system.web.filters.CFilterChain');
			$filter->filter($this);
		}
		else
			$this->controller->runAction($this->action);
	}
}