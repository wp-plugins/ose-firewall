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
class ManageipsModel extends BaseModel
{
	public function __construct()
	{
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function loadLocalScript()
	{
		$baseUrl = Yii::app()->baseUrl;
		$cs = Yii::app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScriptFile($baseUrl.'/public/js/manageips.js', CClientScript::POS_END);
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
			$return['id'] = 1;
			$return['results'] = $oseFirewallStat->getACLIPMap();
			if (empty($return['results']))
			{
				$return['results']['id'] = 0;
				$return['results']['name'] = 'N/A';
			}
			$return['total'] = $oseFirewallStat->getACLIPTotal();
		}
		else
		{
			$return['id'] = 1;
			$return['results']['id'] = 0;
			$return['results']['name'] = 'N/A';
			$return['total'] = 0;
		}
		return $return;
	}
	public function addACLRule($name, $ip_start, $ip_end, $ip_type, $ip_status)
	{
		$url = 'http://'.str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$page_id = $this->addPages($url, 0);
		$referer_id = $this->addReferer();
		$varValues = array(
			'id' => 'DEFAULT',
			'name' => $name,
			'datetime' => date('Y-m-d h:i:s'),
			'score' => 0,
			'status' => (int) $ip_status,
			'referers_id' => $referer_id,
			'pages_id' => $page_id
		);
		$aclid = $this->db->addData('insert', '#__osefirewall_acl', null, null, $varValues);
		if (!empty($aclid))
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
			$result = $oseFirewallStat->remvoeACLRule($aclid);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
	}
	public function changeACLStatus($aclids, $status)
	{
		$oseFirewallStat = new oseFirewallStat();
		foreach ($aclids as $aclid)
		{
			$result = $oseFirewallStat->changeACLStatus($aclid, $status);
			if ($result == false)
			{
				return false;
			}
		}
		return true;
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
		$row = 1;
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
	private function headerArray() {
		return array ('name','ip_start','ip_end','ip_type','ip_status');
	}
	private function array_equal($a, $b, $strict=false) {
	    if (count($a) !== count($b)) {
	        return false;
	    }
	    sort($a);
	    sort($b);
	    return ($strict && $a === $b) || $a == $b;
	}
	public function exportcsv () {
		oseFirewall::loadFiles();
		$time= date("Y-m-d");
		$filename = "ip-export-".$time.".csv";
		$filePath= OSE_FWDATA.ODS."tmp".ODS.$filename;
		$fileContent = $this->getOutputData ();
		$result = oseFile::write($filePath, $fileContent);
		$url = "<div class='download-icon'><a href = '".EXPORT_DOWNLOAD_URL.urlencode($filename)."&centnounce=".urlencode(oseFirewall::loadNounce())."' target='_blank' >Download File</a></div>";
		$return = array(
			'success' => (boolean) true,
			'status' => 'SUCCESS',
			'result' => 'The CSV File is ready, here is the download link: <br/>'.$url
		);  
		$return = oseJSON::encode($return);
		print_r($return);exit;
	}
	private function getOutputData () {
		$output = implode(",", $this->headerArray())."\n";
		$oseFirewallStat = new oseFirewallStat();
		$results = $oseFirewallStat->getACLIPMap();
		foreach ($results as $data)
		{
			$output .= $this->getTmpOutput ($data)."\n";
		}
		return $output;
	}
	private function getTmpOutput ($data) {
		$tmp = array ();
		$tmp[] = $data->name;
		$tmp[] = $data->ip32_start; 
		$tmp[] = $data->ip32_end;
		$tmp[] = $data->iptype;
		$tmp[] = $data->statusraw;
		$return = implode(",", $tmp);
		return $return;
	}
	public function downloadcsv ($filename) {
		oseFirewall::loadFiles();
		$oseFile = new oseFile();
		$path = OSE_FWDATA.ODS."tmp".ODS.$filename; 
		if ($oseFile::exists($path) == false)
		{
			print("<SCRIPT type='text/javascript'>");
			print("alert('The file does not exist or has been deleted, please re-generate the file.');");
			print("window.location = '".OSE_WPURL."';");
			print("</SCRIPT>");
		}
		else
		{
			ob_clean();
			$content = $oseFile::read($path);
			$oseFile::delete($path);
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Length: ".strlen($content));
			// Output to browser with appropriate mime type, you choose ;)
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$filename");
			print_r($content);
			exit;
		}
	}
 }