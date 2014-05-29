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
class BaseModel extends CFormModel {
	protected $db = null;
	protected $cent_nounce = "";
	public function __construct() {
		
	}
	public function getCHeader() {}
	public function getCDescription() {}
	public function showLogo () {
		oseFirewall :: showLogo();
	}
	protected function loadDatabase () {
		$this->db = oseFirewall::getDBO();
	}
	protected function loadLibrary () {
		oseFirewall::callLibClass('firewallstat', 'firewallstat');
		oseFirewall::callLibClass('ipmanager', 'ipmanager');
	}
	protected function loadJSLauguage ($cs, $baseUrl) {
		$lang = oseFirewall::getLocale ();
		if (file_exists (OSE_FWLANGUAGE.DS . $lang.'.js'))
		{
			$cs->registerScriptFile($baseUrl . '/public/messages/'.$lang.'.js', CClientScript::POS_HEAD);
		}
		else
		{
			$cs->registerScriptFile($baseUrl . '/public/messages/en_US.js', CClientScript::POS_HEAD);
		}	
	}
	public function getNounce () {
		echo '<input type="hidden" id="centnounce" value ="'.oseFirewall::loadNounce().'" />';
	}
	public function showHeader () { 
		$html = '<div class="oseseparator"> &nbsp; </div>';
		$html .= '<div class="content-description"><p>'. $this->getCHeader().': '.$this->getCDescription ();
		$html .= '</p></div>';
		echo $html; 
	}
	public function throwAjaxReturn ($result, $status, $msg, $continue) {
		oseAjax :: aJaxReturn($result, $status, $msg, $continue); 
	}
	public function throwAjaxRecursive ($result, $status, $msg, $continue, $step) {
		oseAjax :: throwAjaxRecursive($result, $status, $msg, $continue, $step); 
	}
	protected function transMessage ($success, $msg)
	{
		$style = ($success==true)?'ajax-success':'ajax-failed';
		return '<div class="'.$style.'" >'.$msg.'</div>';
	}
	protected function addPages($url, $action) {
		$query = 'SELECT `id`, `visits` FROM `#__osefirewall_pages` WHERE `page_url` = ' . $this->db->quoteValue($url);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		if (empty ($results)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'page_url' => $url,
				'action' => $action,
				'visits' => 1
			);
			$id = $this->db->addData('insert', '#__osefirewall_pages', null, null, $varValues);
		} else {
			$varValues = array (
				'visits' => $results->visits + 1
			);
			$id = $this->db->addData('update', '#__osefirewall_pages', 'id', $results->id, $varValues);
		}
		return $id;
	}
	protected function addReferer($referer=null) {
		$query = 'SELECT `id` FROM `#__osefirewall_referers` WHERE `referer_url` = ' . $this->db->quoteValue($referer);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		if (empty ($results)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'referer_url' => $referer
			);
			$id = $this->db->addData('insert', '#__osefirewall_referers', null, null, $varValues);
		} else {
			$id = $results->id;
		}
		return $id;
	}
	public function isDBReady(){
		$return = array ();
		$return['ready'] = oseFirewall :: isDBReady();
		$return['type'] = 'base';
		return $return['ready'];
	}
}	