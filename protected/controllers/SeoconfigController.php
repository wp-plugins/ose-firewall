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
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once (OSE_FWCONTROLLERS. DS. 'BaseController.php'); 
class SeoconfigController extends BaseController {
	public $layout = '//layouts/forms';
	public function actionGetConfiguration () {
		oseFirewall::loadRequest (); 
		$type = oRequest ::getVar('type', null); 
		if (empty($type))
		{
			return; 
		}
		$results = $this -> model ->getConfiguration($type);
		oseAjax::returnJSON($results); 
	}
	public function actionSaveConfigSEO () {
		oseFirewall::loadRequest (); 
		$type = oRequest :: getVar('type', null);
		if (empty($type)) {return;}
		$data = array();
		$data['pageTitle'] = oRequest :: getVar('pageTitle', null);
		$data['metaKeywords'] = oRequest :: getVar('metaKeywords', null);
		$data['metaDescription'] = oRequest :: getVar('metaDescription', null);
		$data['metaGenerator'] = oRequest :: getVar('metaGenerator', null);
		$data['adminEmail'] = oRequest :: getVar('adminEmail', null);
		$data['customBanpage'] = oRequest :: getHTML('customBanpage', null);	
		$data['scanGoogleBots'] = oRequest :: getInt('scanGoogleBots', 0);	
		$data['scanMsnBots'] = oRequest :: getInt('scanMsnBots', 0);	
		$data['scanYahooBots'] = oRequest :: getInt('scanYahooBots', 0);
		$model= $this->getModel('Configuration');
		$model ->saveConfiguration($type, $data);
	}
}
?>	