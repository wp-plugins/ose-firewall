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
class RulesetsModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function getCHeader() {
		return oLang :: _get('MANAGERULESETS_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('MANAGERULESETS_DESC');
	}
	public function loadLocalScript() {
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraSEOTinyMCE', 'plugins/tinymce/tinymce.min.js', false);
		oseFirewall::loadJSFile ('CentroraManageIPs', 'rulesets.js', false);
	}
	public function getRulesets() {
		$return = array(); 
		$oseFirewallStat = new oseFirewallStat();
		if(oseFirewall::isDBReady()){
			$return = $oseFirewallStat->getRulesets();
		}else{
			$return = $this->getEmptyReturn ();
		}
		$_SESSION['rulesetdraw'] = (isset($_SESSION['rulesetdraw']))?$_SESSION['rulesetdraw']+1:1;
		$return['draw']=$_SESSION['rulesetdraw'];
		return $return; 
	}
	public function changeRuleStatus($ids, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($ids as $id)
		{
			$result = $oseFirewallStat->changeRulesetStatus($id, $status);
		}
		return $result; 	
	}
	public function getStatistics(){
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getBasicRulesStatistic();
		
	}
}