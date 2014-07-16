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
	
	public function showConfigBtnList(){
		$html = '<div id = "Config-Btn-List">';
		$html .= '<table id="hor-minimalist-config">';
		$html .= '<tbody>';
		$html .= $this->getAllBtns ();
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';
		echo $html; 	
	}
	public function getAllBtns () {
		$html = '<tr><td class="btns"><button class = "config-btn" onClick = "window.location = \''.$this->getURL('scanconfig').'\'">'.SCAN_CONFIGURATION.'</button></td><td>'.SCANCONFIG_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "config-btn" onClick = "window.location = \''.$this->getURL('avconfig').'\'">'.ANTIVIRUS_CONFIGURATION.'</button></td><td>'.VSCONFIG_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "config-btn" onClick = "window.location = \''.$this->getURL('seoconfig').'\'">'.SEO_CONFIGURATION.'</button></td><td>'.SEOCONFIG_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "config-btn" onClick = "window.location = \''.$this->getURL('spamconfig').'\'">'.ANTISPAM_CONFIGURATION.'</button></td><td>'.ANTISPAMCONFIG_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "config-btn" onClick = "window.location = \''.$this->getURL('emailconfig').'\'">'.EMAIL_CONFIGURATION.'</button></td><td>'.EMAILCONFIG_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "config-btn" onClick = "window.location = \''.$this->getURL('emailadmin').'\'">'.EMAIL_ADMIN.'</button></td><td>'.ADMINEMAILCONFIG_INTRO.'</td></tr>';
		$html .= '<tr><td class="btns"><button class = "config-btn" onClick = "uninstallDB();">'.UNINSTALLDB.'</button></td><td>'.UNINSTALLDB_INTRO.'</td></tr>';
		return $html;  
	}
	public function getConfiguration($type)
	{
		$oseFirewallStat = new oseFirewallStat();
		$results = $oseFirewallStat->getConfiguration($type);
		return $results; 
	}
	public function saveConfiguration($type, $data)
	{
		$this->isConfigurationDBReady($data);
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->saveConfiguration($type, $data);
		$this -> ajaxReturn ($result); 
	}
	private function ajaxReturn ($result)
	{
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get('CONFIG_SAVE_SUCCESS'), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('CONFIG_SAVE_FAILED'), false);
		}
	}
	
	private function isConfigurationDBReady($data)
	{
		require_once(OSE_FWFRAMEWORK.ODS.'oseFirewallBase.php');
		if(isset($data['blockCountry'] ) && $data['blockCountry'] == 1)
		{
			if(oseFirewallBase :: isCountryBlockConfigDBReady() == false)
			{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('CONFIG_SAVECOUNTRYBLOCK_FAILE'), false);
			}
		}
		if(isset($data['adVsPatterns'] ) && $data['adVsPatterns'] == 1){
			if(oseFirewallBase :: isAdvancePatternConfigDBReady() == false)
			{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('CONFIG_ADPATTERNS_FAILE'), false);
			}
		}
		if(isset($data['adRules'] ) && $data['adRules'] == 1)
		{
			if(oseFirewallBase :: isAdvanceSettingConfigDBReady() == false)
			{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('CONFIG_ADRULES_FAILE'), false);
			}
		}
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