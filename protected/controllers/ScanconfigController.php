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
class ScanconfigController extends BaseController {
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
	public function actionSaveConfigScan () {
		oseFirewall::loadRequest (); 
		$type = oRequest :: getVar('type', null);
		if (empty($type)) {return;}
		$data = array();
		$data['secretword'] = oRequest :: getVar('secretword', null);
		$data['devMode'] = oRequest :: getInt('devMode', 0);
		$data['blockIP'] = oRequest :: getInt('blockIP', 0);
		$data['threshold'] = oRequest :: getInt('threshold', 20);
		$data['slient_max_att'] = oRequest :: getInt('slient_max_att', 10);
		$data['allowExts'] = oRequest :: getVar('allowExts', null);
		$data['scanFileVirus'] = oRequest :: getInt('scanFileVirus', 0);
		$data['showBadge'] = oRequest :: getInt('showBadge', 0);
		$data['badgeCSS'] = oRequest :: getVar('badgeCSS', null);
		$this->model ->saveConfiguration($type, $data);
	}
}
?>	