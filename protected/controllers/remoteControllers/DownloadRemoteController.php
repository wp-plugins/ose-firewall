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
require_once (OSE_FWRECONTROLLERS. ODS. 'BaseRemoteController.php');
require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'ajax' . ODS . 'oseAjax.php');
class DownloadRemoteController extends BaseRemoteController{
	public function __construct($id,$module=null)
	{
		parent::__construct($id,$module=null);
		$this -> getModel () ;
		$this -> model -> isDBReady();
	}
	
	public function actionDownload() 
	{
		oseFirewall::loadFiles ();
		$type= oRequest::getVar('type');
		$version= oRequest::getVar('version');
		$downloadKey = oRequest::getVar('downloadKey');
		$result = $this->model->download($type, $downloadKey);
		if ($result == true)
		{
			$this->model->updateVersion ($type, $version); 
			$receiveEmail = $this->model->getEmailConfig (); 
			$return = array(
				'success' => true,
				'status' => 'SUCCESS',
				'result' => oLang::_get("ADVRULESET_INSTALL_SUCCESS"),
				'cont' => false,
				'receiveEmail' => (int)$receiveEmail 
			);
			$tmp = oseJSON::encode($return);
			print_r($tmp); exit; 
		}   
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("ADVRULESET_INSTALL_FAILED"), false);
		}
	}
	
	public function actionUpdate() 
	{
		oseFirewall::loadFiles ();
		$type= oRequest::getVar('type');
		$version= oRequest::getVar('version');
		$downloadKey = oRequest::getVar('downloadKey');
		$result = $this->model->update($type, $downloadKey);
		if ($result == true)
		{
			$this->model->updateVersion ($type, $version); 
			$receiveEmail = $this->model->getEmailConfig (); 
			$return = array(
				'success' => true,
				'status' => 'SUCCESS',
				'result' => oLang::_get("ADVRULESET_INSTALL_SUCCESS"),
				'cont' => false,
				'receiveEmail' => (int)$receiveEmail 
			);
			$tmp = oseJSON::encode($return);
			print_r($tmp); exit;
		}   
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("ADVRULESET_INSTALL_FAILED"), false);
		}
	}
	public function actionVsscan ($step) {
		$result = $this->model->vsscan($step);
		if ($result == true)
		{
			// Update vsscanning date; 
			//$this->model->updateVersion ($type, $version); 
			$receiveEmail = $this->model->getEmailConfig (); 
			$return = array(
				'success' => true,
				'status' => 'SUCCESS',
				'result' => oLang::_get("VSSCANNING_SUCCESS"),
				'cont' => false,
				'receiveEmail' => (int)$receiveEmail 
			);
			$tmp = oseJSON::encode($return);
			print_r($tmp); exit;
		}
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("VSSCANNING_FAILED"), false);
		}
	}
}
?>