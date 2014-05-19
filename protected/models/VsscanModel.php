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
class VsscanModel extends BaseModel {
	public function __construct() {	
	}
	public function loadLocalScript() {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScript('oseAVScan', $this -> getAVScanScript(), CClientScript::POS_BEGIN); 
		$cs->registerScriptFile($baseUrl . '/public/js/vsscan.js', CClientScript::POS_END);
	}
	private function getAVScanScript () {
		return "var path = \"".OSE_DEFAULT_SCANPATH."\";";
	}
	public function getCHeader() {
		return oLang :: _get('ANTIVIRUS_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('ANTIVIRUS_DESC');
	}
	public function initDatabase($step, $path) {
		oseFirewall::callLibClass('vsscanner','vsscanner'); 
		$scanner = new virusScanner ();
		$scanner -> initDatabase($step, $path);
	}
	public function getTotalFiles()
	{
		oseFirewall::callLibClass('vsscanner','vsscanner');
		$scanner = new virusScanner ();	
		return oLang::_get('SCAN_READY');
	}
	private function assembleArray($result, $status, $msg, $continue, $id)
	{
		$return = array(
			'success' => (boolean) $result,
			'status' => $status,
			'result' => $msg,
			'cont' => (boolean) $continue,
			'id' => (int) $id
		);
		return $return;
	}
	public function vsScan($step) {
		$result= array();
		$statusQuery= null;
		$resultQuery= null;
		$infectedNum= 0;
		oseFirewall::callLibClass('vsscanner','vsscanner'); 
		$scanner = new virusScanner ($type);
		$results = $scanner -> vsScan ($step);
		if($results == false)
		{
			$results = $this->assembleArray (false, 'ERROR', FILE_VSSCAN_FAILED_INCORRECT_PERMISSIONS, $continue = false, $id = null);
			oseAjax::returnJSON($results);
		}
		return $results;  
	}
	public function getTotalInfected()
	{
		oseFirewall::callLibClass('vsscanner','vsscanner'); 
		$scanner = new virusScanner ();
		$totalNum = $scanner->getNumInfectedFiles();
		if ($totalNum)
		{
			return oLang::_get('OSE_THERE_ARE').' '.oLang::_get('OSE_INTOTAL').' '.$totalNum.' '.oLang::_get('OSE_FILES').' '.oLang::_get('OSE_INFECTED_FILES');
		} 
		else
		{
			return oLang::_get('YOUR_SYSTEM_IS_CLEAN');
		}
	}
	
	public function isDBReady(){
		$return = array ();
		$return['ready'] = oseFirewall :: isDBReady();
		$return['type'] = 'base';
		return $return['ready'];
	}
}