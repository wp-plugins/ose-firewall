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
require_once('BaseModel.php');
class ConfigurationModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function getCHeader() {
		return oLang :: _get('CONFIGURATION_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('CONFIGURATION_DESC');
	}
	public function loadLocalScript()
	{
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraInstaller', 'installer.js', false);
	}
	public function showConfigBtnList(){
		$html = '<div id = "Config-Btn-List" class="form-horizontal group-border stripped">';
		$html .= $this->getAllBtns ();
		$html .= '</div>';
		echo $html; 	
	}
	public function getAllBtns () {
		$html = '<div class="form-group"><div class="col-sm-3"><button class = "btn ml10" id ="install-button" name ="install-button" data-target="#formModal" data-toggle="modal"><i class="text-success glyphicon glyphicon-cog"></i> '.INSTALLDB.'</button></div><div class="col-sm-9">'.INSTALLDB_INTRO.'</div></div>';
		$html .= '<div class="form-group"><div class="col-sm-3"><button class = "btn ml10" data-target="#formModal2" data-toggle="modal"><i class="text-danger glyphicon glyphicon-trash"></i> '.UNINSTALLDB.'</button></div><div class="col-sm-9">'.UNINSTALLDB_INTRO.'</div></div>';
		return $html;  
	}
	public function getURL($view) {
		if (class_exists('JFactory')) {
			return OSE_ADMINURL.'&view='.$view; 
		}
		else
		{
			return OSE_ADMINURL.'?page=ose_fw_'.$view;
		}
	}
}	