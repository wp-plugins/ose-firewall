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
class ClamavModel extends BaseModel {
	private $clamd = '';
	private $config = array();
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
		return oLang :: _get('CLAMAV_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('CLAMAV_DESC');
	}
	public function isDBReady(){
		$return = array ();
		$return['ready'] = oseFirewall :: isDBReady();
		$return['type'] = 'base';
		return $return['ready'];
	}
	public function getClamdStatus () {
		$this->getClamd ();
		$return = new stdClass();
		if ($this->clamd-> getConfigStatus ()  == false)
		{
			$return->status = false;
			$return->status_desc = oLang::_get("CLAMAV_NOT_ENABLED");
		}
		else
		{
			$this->clamd->connect();
			if ($this->clamd->getConnected () == true)
			{
				$return->status = true;
				$return->status_desc = oLang::_get("CLAMAV_CONNECT_SUCCESS");
				$return->version = $this->clamd->getVersion();
				$return->stat = $this->clamd->getStat();
				$return->stat = $this->reGenStat($return->stat);
			}
			else
			{
				$return->status = false;
				$return->status_desc = oLang::_get("CLAMAV_CANNOT_CONNECT");
			}
		}
		return $return;
	}
	private function reGenStat($stat)
	{
		$tmp = explode("\n", $stat);
		$tmp2 = array();
		foreach ($tmp as $tp)
		{
			if (!empty($tp))
			{
				$tp = explode(":", $tp);
				if (isset($tp[1]))
				{
					$tmp2[$tp[0]] = $tp[1];
				}
			}
		}
		return $tmp2;
	}
	private function getClamd () {
		oseFirewall::callLibClass('clamd', 'clamd');
		$this->clamd = new Clamd();
	}
}