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
class AboutModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
	}
	public function loadLocalScript() {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		//$cs->registerScriptFile($baseUrl . '/public/js/backup.js', CClientScript::POS_END);
	}
	public function getCHeader() {
		return oLang :: _get('ABOUT');
	}
	public function getCDescription() {
		return oLang :: _get('OSE_WORDPRESS_FIREWALL_UPDATE_DESC');
	}
	public function showBtnList(){
		$html = '<div id = "menu-btn-list">';
		$html .= '<table id="hor-minimalist-b" summary="">';
		$html .= '<tbody>';
		$html .= $this->getAllBtns ();
		$html .= $this->getConfButtons ();
		$html .= '</tbody>';
		$html .= '</table></div>';
		echo $html; 	
	}
	private function getAllBtns () {
		$html = '<tr><td class="btns"><button class = "dashboard-btn" onClick = "window.location = \''.$this->getURL('vsscan').'\'">Virus Scanner</button></td><td>'.VIRUS_SCANNER_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "dashboard-btn" onClick = "window.location = \''.$this->getURL('vsreport').'\'">Scan Report</a></td><td>'.SCAN_REPORT_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "dashboard-btn" onClick = "window.location = \''.$this->getURL('manageips').'\'">IP Management</a></td><td>'.IPMANAGEMENT_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "dashboard-btn" onClick = "window.location = \''.$this->getURL('rulesets').'\'">Firewall Settings</a></td><td>'.FIREWALL_SETTING_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "dashboard-btn" onClick = "window.location = \''.$this->getURL('variables').'\'">Variables</a></td><td>'.VARIABLES_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "dashboard-btn" onClick = "window.location = \''.$this->getURL('configuration').'\'">Configuration</a></td><td>'.CONFIGURATION_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "dashboard-btn" onClick = "window.location = \''.$this->getURL('backup').'\'">Back Up</a></td><td>'.BACK_UP_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "dashboard-btn" onClick = "window.location = \''.$this->getURL('countryblock').'\'">Country Block</a></td><td>'.COUNTRY_BLOCK_INTRO.'</td></tr>';
		return $html; 
	}
	private function getConfButtons () {
		$confModel = new ConfigurationModel (); 
		return $confModel->getAllBtns () ;
	}
	public function getURL($view) {
		if (class_exists('JFactory', false)) {
			return OSE_ADMINURL.'&view='.$view; 
		}
		else
		{
			return OSE_ADMINURL.'?page=ose_fw_'.$view;
		}
	}
}