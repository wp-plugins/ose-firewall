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
class VariablesModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function getCHeader() {
		return oLang :: _get('VARIABLES_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('VARIABLES_DESC');
	}
	public function loadLocalScript() {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScriptFile($baseUrl . '/public/js/variables.js', CClientScript::POS_END);
	}
	public function getVariables()
	{
		$return = array(); 
		$oseFirewallStat = new oseFirewallStat();
		$return['id']=1; 
		$return['results']= $oseFirewallStat->getVariables();
		if (empty($return['results']))
		{
			$return['results']['id'] = 0;
			$return['results']['keyname'] = 'N/A';
		}
		$return['total']= $oseFirewallStat->getVariablesTotal();
		return $return; 
	}
	public function changeVarStatus($ids, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		
		foreach ($ids as $id)
		{
			$result = $oseFirewallStat->changeVarStatus($id, $status);
			if ($result == false)
			{
				return false;
			}
		}
		return true; 	
	}
	public function addvariables($variable, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->addvariables($variable, $status);
		return $result;
	}
	public function deletevariable($ids)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($ids as $id)
		{
			$result = $oseFirewallStat->deletevariable($id);
			if ($result == false)
			{
				return false;
			}
		}
		return true; 
	}
	public function loadJoomlarules()
	{
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->loadDefaultRules('joomla');
		if ($result == false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	public function loadWordpressrules()
	{
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->loadDefaultRules('wordpress');
		if ($result == false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	public function loadJSocialrules()
	{
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->loadDefaultRules('jomsocial');
		if ($result == false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	public function clearvariables()
	{
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->clearvariables();
		if ($result == false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}	