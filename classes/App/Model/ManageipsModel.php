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
class ManageipsModel extends BaseModel
{
	public function __construct()
	{
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function loadLocalScript()
	{
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraManageIPs', 'manageips.js', false);
	}
	public function getCHeader()
	{
		return oLang::_get('MANAGEIPS_TITLE');
	}
	public function getCDescription()
	{
		return oLang::_get('MANAGEIPS_DESC');
	}
	public function getACLIPMap()
	{
		$return = array();
		$oseFirewallStat = new oseFirewallStat();
		if (oseFirewall::isDBReady())
		{
			$return = $oseFirewallStat->getACLIPMap();
		}
		else
		{
			$return = $this->getEmptyReturn ();
		}
		$return['draw']=$this->getInt('draw');
		return $return;
	}
	public function getLatestTraffic()
	{
		$return = array();
		$oseFirewallStat = new oseFirewallStat();
		if (oseFirewall::isDBReady())
		{
			$return = $oseFirewallStat->getLatestTraffic();
		}
		else
		{
			$return = $this->getEmptyReturn ();
		}
		$return['draw']=$this->getInt('draw');
		return $return;
	}
	public function addACLRule($name, $ip_start, $ip_end, $ip_type, $ip_status)
	{
		$url = 'http://'.str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$page_id = $this->addPages($url, 0);
		$referer_id = $this->addReferer();
		$varValues = array(
			'id' => '',
			'name' => $name,
			'datetime' => date('Y-m-d h:i:s'),
			'score' => 0,
			'status' => (int) $ip_status,
			'referers_id' => $referer_id,
			'pages_id' => $page_id
		);
		$aclid = $this->db->addData('insert', '#__osefirewall_acl', null, null, $varValues);
		if (!empty($aclid) && is_int($aclid))
		{
			$ipmanager = new oseFirewallIpManager($this->db);
			$ipmanager->setIPRange($ip_start, $ip_end);
			$ipmanager->addIP($ip_type, $aclid);
			return true;
		}
	}
	public function removeACLRule($aclids)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($aclids as $aclid)
		{
			$result = $oseFirewallStat->removeACLRule($aclid);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	public function removeAllACLRule () {
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->removeAllACLRule();
		return $result;
	}
	public function changeACLStatus($aclids, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($aclids as $aclid)
		{
			$result = $oseFirewallStat->changeACLStatus($aclid, $status);
		}
		return $result;
	}
	public function updateHost($aclids)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($aclids as $aclid)
		{
			$result = $oseFirewallStat->updateHost($aclid);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	public function getAttackDetail($aclid)
	{
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getAttackDetail($aclid);
	}
	public function getStatistics()
	{
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getACLIPStatistic();
	}
	public function importcsv ($file) {
        $row = 2;
		$result = true; 
		if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    	if ($row == 1)
		    	{
		    		if ($this->array_equal($data, $this->headerArray(), true)==false)
		    		{
		    			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The CSV file format is incorrect. Please follow the instruction to create the CSV file."), false);	
		    		}
		    	}
		    	else 
		    	{
		    		$result = $this->addACLRule($data[0], $data[1], $data[2], $data[3], $data[4]);
		    	}
		        $row++;
		    }
		    fclose($handle);
		}
		return $result; 
	}
	private function array_equal($a, $b, $strict=false) {
	    if (count($a) !== count($b)) {
	        return false;
	    }
	    sort($a);
	    sort($b);
	    return ($strict && $a === $b) || $a == $b;
	}

    public function exportcsv()
    {
        oseFirewall::loadFiles();
        $time = date("Y-m-d");
        $filename = "ip-export-" . $time . ".csv";
        $url = EXPORT_DOWNLOAD_URL . urlencode($filename) . "&centnounce=" . urlencode(oseFirewall::loadNounce());
        $exportButton = '<a href="' . $url . '"  id="export-ip-button" target="_blank"><div>' . oLang::_("GENERATE_CSV_NOW") . '</div><i class="fa fa-file-excel-o fa-2x"></i></a>';
        print_r($exportButton);

    }

    public function downloadCSV($filename)
    {
        $oseFirewallStat = new oseFirewallStat();
        $oseFirewallStat->downloadcsv($filename);
    }
 }