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
class AdvancedpatternsModel extends BaseModel
{
	public function __construct()
	{
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function getCHeader()
	{
		return oLang::_get('MANAGE_AD_RULESETS_TITLE');
	}
	public function getCDescription()
	{
		return oLang::_get('MANAGE_AD_RULESETS_DESC');
	}
	public function loadLocalScript()
	{
		$baseUrl = Yii::app()->baseUrl;
		$cs = Yii::app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScriptFile($baseUrl.'/public/js/advancerulesets.js', CClientScript::POS_END);
	}
	public function getRulesets()
	{
		$return = array();
		$exists = $this->checkRuleExists ();
		if ($exists == false)
		{
			$return['id'] = 1;
			$return['results']['id'] = 0;
			$return['results']['name'] = 'N/A';
			$return['total'] = 0;
		}
		else
		{
			$oseFirewallStat = new oseFirewallStat();
			if (oseFirewall::isDBReady())
			{
				$return['id'] = 1;
				$return['results'] = $oseFirewallStat->getAdvancePatterns();
				if (empty($return['results']))
				{
					$return['results']['id'] = 0;
					$return['results']['name'] = 'N/A';
				}
				$return['total'] = $oseFirewallStat->getAdvancePatternsTotal();
			}
			else
			{
				$return['id'] = 1;
				$return['results']['id'] = 0;
				$return['results']['name'] = 'N/A';
				$return['total'] = 0;
			}
		}
		return $return;
	}
	private function checkRuleExists()
	{
		$query = "SHOW TABLES LIKE '#__osefirewall_advancepatterns';";
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		return (empty($results)) ? false : true;
	}
	public function changeRuleStatus($ids, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($ids as $id)
		{
			$result = $oseFirewallStat->changeAdvanceRulesetStatus($id, $status);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	
	public function getVersion(){
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getAdvancePatternsVersion();
	}
}
