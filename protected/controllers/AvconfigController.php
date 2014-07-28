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
require_once (OSE_FWCONTROLLERS. ODS. 'BaseController.php'); 
class AvconfigController extends BaseController {
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
	public function actionSaveConfAV () {
		oseFirewall::loadRequest (); 
		$type = oRequest :: getVar('type', null);
		if (empty($type)) {return;}
		$data = array();
		$file_exts = oRequest::getVar('file_ext','htm,html,shtm,shtml,css,js,php,php3,php4,php5,inc,phtml,jpg,jpeg,gif,png,bmp,c,sh,pl,perl,cgi,txt');
        $file_exts = str_replace(array("[","]","\""," "), "", $file_exts);
		$data['file_ext'] = $file_exts;
		$data['maxfilesize'] = oRequest::getInt('maxfilesize', 0);
		$data['enable_clamav'] = oRequest::getInt('enable_clamav', 0);
		$data['clamav_activation'] = oRequest :: getVar('clamav_activation', 'socket');
		$data['clamavsocket'] = oRequest::getVar('clamavsocket','unix:///tmp/clamd.socket');
		$data['clamavtcpip'] = oRequest :: getVar('clamavtcpip', '127.0.0.1');
		$data['clamavtcpport'] = oRequest :: getVar('clamavtcpport', '3310');
		$this->model ->saveConfiguration($type, $data);
	}
}
?>	