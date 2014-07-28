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
 * Built-in client script packages.
 *
 * Please see {@link CClientScript::packages} for explanation of the structure
 * of the returned array.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

return array(
	'jquery'=>array(
		'js'=>array(YII_DEBUG ? 'jquery.js' : 'jquery.min.js'),
	),
	'extjs'=>array(
		'js'=>array('ext-all.js')
	),
	'extjsneptune'=>array(
		'js'=>array('ext-theme-neptune.js'),
		'depends'=>array('extjs')
	),
	'oseelements'=>array(
		'js'=>array('ux/elements.js'),
		'depends'=>array('extjs')
	),
	'osefunctions'=>array(
		'js'=>array('ux/functions.js'),
		'depends'=>array('extjs')
	),
	'ItemSelector'=>array(
		'js'=>array('ux/ItemSelector.js'),
		'depends'=>array('extjs')
	),
	'MultiSelect'=>array(
		'js'=>array('src/ux/form/MultiSelect.js'),
		'depends'=>array('extjs')
	),
	'SearchField'=>array(
		'js'=>array('ux/SearchField.js'),
		'depends'=>array('extjs')
	),
	'SlidingPager'=>array(
		'js'=>array('ux/SlidingPager.js'),
		'depends'=>array('extjs')
	),
	'TinyMCE'=>array(
		'js'=>array('ux/Tiny.js'),
		'depends'=>array('extjs')
	),
	'tinymce'=>array(
		'js'=>array('ux/tinymce/tiny_mce.js'),
		'depends'=>array('extjs')
	),
	'yii'=>array(
		'js'=>array('jquery.yii.js'),
		'depends'=>array('jquery'),
	),
	'yiitab'=>array(
		'js'=>array('jquery.yiitab.js'),
		'depends'=>array('jquery'),
	),
	'yiiactiveform'=>array(
		'js'=>array('jquery.yiiactiveform.js'),
		'depends'=>array('jquery'),
	),
	'jquery.ui'=>array(
		'js'=>array('jui/js/jquery-ui.min.js'),
		'depends'=>array('jquery'),
	),
	'bgiframe'=>array(
		'js'=>array('jquery.bgiframe.js'),
		'depends'=>array('jquery'),
	),
	'ajaxqueue'=>array(
		'js'=>array('jquery.ajaxqueue.js'),
		'depends'=>array('jquery'),
	),
	'autocomplete'=>array(
		'js'=>array('jquery.autocomplete.js'),
		'depends'=>array('jquery', 'bgiframe', 'ajaxqueue'),
	),
	'maskedinput'=>array(
		'js'=>array(YII_DEBUG ? 'jquery.maskedinput.js' : 'jquery.maskedinput.min.js'),
		'depends'=>array('jquery'),
	),
	'cookie'=>array(
		'js'=>array('jquery.cookie.js'),
		'depends'=>array('jquery'),
	),
	'treeview'=>array(
		'js'=>array('jquery.treeview.js', 'jquery.treeview.edit.js', 'jquery.treeview.async.js'),
		'depends'=>array('jquery', 'cookie'),
	),
	'multifile'=>array(
		'js'=>array('jquery.multifile.js'),
		'depends'=>array('jquery'),
	),
	'rating'=>array(
		'js'=>array('jquery.rating.js'),
		'depends'=>array('jquery', 'metadata'),
	),
	'metadata'=>array(
		'js'=>array('jquery.metadata.js'),
		'depends'=>array('jquery'),
	),
	'bbq'=>array(
		'js'=>array(YII_DEBUG ? 'jquery.ba-bbq.js' : 'jquery.ba-bbq.min.js'),
		'depends'=>array('jquery'),
	),
	'history'=>array(
		'js'=>array('jquery.history.js'),
		'depends'=>array('jquery'),
	),
	'punycode'=>array(
		'js'=>array(YII_DEBUG ? 'punycode.js' : 'punycode.min.js'),
	),
);
