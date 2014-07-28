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
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
 die('Direct Access Not Allowed');
}
class BaseController extends Controller {
	protected $model = null;
	public function __construct($id,$module=null)
	{
		parent::__construct($id,$module=null);
		$this -> getModel () ;
	}
	/**
	 * Declares class-based actions.
	 */
	public function actions() {
		return array (
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array (
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
				
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array (
				'class' => 'CViewAction',
				
			),
			
		);
	}
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
		if ($error = Yii :: app()->errorHandler->error) {
			if (Yii :: app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	public function getModel () {
		$modelName = str_replace ('Controller', 'Model', get_class($this)) ;
		$this->model = new $modelName();    
		return $this->model;   
	}
	
	public function actionIndex() {
		// renders the view file 'protected/views/dashboard/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}
}	