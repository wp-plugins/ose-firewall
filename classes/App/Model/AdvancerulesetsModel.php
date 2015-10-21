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
class AdvancerulesetsModel extends BaseModel
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
		$version = $this->getRuleVersion (); 
		if (!empty($version))
		{
			return oLang::_get('MANAGE_AD_RULESETS_DESC').". Your signature version is: ".$version.".";
		}
		else
		{
			return oLang::_get('MANAGE_AD_RULESETS_DESC');
		}
	}
	private function getRuleVersion () {
		$oseFirewallStat = new oseFirewallStat();
		if (oseFirewall::isDBReady())
		{
			return $oseFirewallStat->getCurrentSignatureVersion(); 
		}
		else
		{
			return "";
		}
	}
	public function loadLocalScript()
	{
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraManageIPs', 'advancerulesets.js', false);
	}
	public function getRulesets()
	{
		$return = array();
		$oseFirewallStat = new oseFirewallStat();
		$exists = $oseFirewallStat->checkRuleExists ();
		if ($exists == false)
		{
			$return = $this->getEmptyReturn ();
		}
		else
		{
			if (oseFirewall::isDBReady())
			{
				$return = $oseFirewallStat->getAdvanceRulsets();
			}
			else
			{
				$return = $this->getEmptyReturn ();
			}
		}
		$return['draw']=$this->getInt('draw');
		return $return;
	}
	public function changeRuleStatus($ids, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($ids as $id)
		{
			$result = $oseFirewallStat->changeAdvanceRulesetStatus($id, $status);
		}
		return $result;
	}
	public function getVersion(){
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getAdvanceRulesVersion();
	}
	public function checkAPI () {
		oseFirewall::callLibClass('downloader', 'oseDownloader');
		$downloader = new oseDownloader('ath', null);
		$response = $downloader->getRemoteAPIKey();
		return $response; 
	}

    public function downloadRequest($type)
    {
        oseFirewall::callLibClass('panel', 'panel');
        $panel = new panel ();
        return $panel->getSignature($type);
    }

    public function downloadSQL($type, $downloadKey, $version)
    {
        oseFirewall::callLibClass('downloader', 'oseDownloader');
        $downloader = new oseDownloader($type, $downloadKey, $version);
        return $downloader->switchRoad();
    }
}