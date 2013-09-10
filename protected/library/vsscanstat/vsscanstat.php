<?php
/**
* @version     1.0 +
* @package       Open Source Excellence Security Suite
* @subpackage    Open Source Excellence WordPress Firewall
* @author        Open Source Excellence {@link http://www.opensource-excellence.com}
* @author        Created on 01-Jul-2012
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
defined('OSEFWDIR') or die;
class oseVsscanStat {
	private $db = null;
	private $filestable = '#__osefirewall_files';
	private $logstable = '#__osefirewall_logs';
	private $malwaretable = '#__osefirewall_malware';
	public function __construct()
	{
		oseFirewall::loadRequest();
		$this->db= oseFirewall::getDBO();
	}
	public function getTypeList() {
		$db = oseFirewall::getDBO ();
		$query = "SELECT * FROM `#__osefirewall_vstypes`";
		$db->setQuery($query); 
		return $db->loadObjectList();
	}
	public function getMalwareMap () {
		$limit = oRequest::getInt('limit', 25);
		$start = oRequest::getInt('start', 0);
		$page = oRequest::getInt('page', 1);
		$search = oRequest::getVar('search', null);
		$type_id = oRequest::getInt('type_id', null);
		$start = $limit * ($page-1);    
		$results = $this->getMalwareMapDB($search, $type_id, $start, $limit);
		return $this->convertMalwareMap ($results) ;
	}
	private function convertMalwareMap ($results) {
		$return = array();
		$i=0;  
		foreach ($results as $result)
		{
			$results[$i] ->view='<img src = "'.OSE_FWURL.'/public/images/icon-info.png" onClick = "viewFiledetail('.$result->file_id.')"/ class ="viewbutton">';
			$i++;
		}
		return $results;
	}
	public function getMalwareMapDB ($search, $type_id, $start, $limit) {
		$db = oseFirewall::getDBO ();
		$where = array(); 
		if (!empty($search))
		{
			$where[] = "`filename` LIKE ".$db->quoteValue($search.'%', true) ;
		}
		if (!empty($type_id))
		{
			$where[] = "`type_id` = ".(int)$type_id;
		}
		$where = $db->implodeWhere($where);
		$query = "SELECT * FROM `#__osefirewall_detmalware`" .$where
				 ." ORDER BY `filename` DESC LIMIT ".$start.", ".$limit;
		$db->setQuery($query); 
		return $db->loadObjectList();
	}
	public function getMalwareTotal () {
		$db = oseFirewall::getDBO ();
		$query = "SELECT COUNT(file_id) as `count` FROM `#__osefirewall_detmalware`";
		$db->setQuery($query);
		$result = (object) ($db->loadResult());  
		return $result->count;
	}
	public function getFileContent($id)
	{
		$filename = $this->getFilePath ($id); 
		$fileType = $this->getFileType($filename);
		if (!in_array($fileType, array('php, javascript')))
		{
			$fileType = 'htmlmixed';
		}
		$content = '<div class="'.$fileType.'" style="width:100%;" name="codearea" id="codearea" rows="25" cols="120" wrap="off" >';
		if (!empty($filename))
		{
			$content .= nl2br(htmlspecialchars(file_get_contents($filename))); 
		}
		else
		{
			$content .='Cannot read the file'; 
		}
		$content .= '</div>';
		return $content; 
	}
	private function getFilePath ($id) 
	{
		$db = oseFirewall::getDBO ();
		$query = "SELECT `filename` FROM `#__osefirewall_files` WHERE `id` =".(int)$id;
		$db->setQuery($query);
		$result = (object) $db->loadResult();
		return $result ->filename; 
	}
	private function getFileType($filepath)
	{
		$s_info = pathinfo( $filepath );
		$s_extension = str_replace('.', '', $s_info['extension'] );
		switch (strtolower($s_extension)) {
			case 'txt':
			case 'ini':
				$cp_lang = 'text'; break;
			case 'cs':
				$cp_lang = 'csharp'; break;
			case 'css':
				$cp_lang = 'css'; break;
			case 'html':
			case 'htm':
			case 'xml':
			case 'xhtml':
				$cp_lang = 'html'; break;
			case 'java':
				$cp_lang = 'java'; break;
			case 'js':
				$cp_lang = 'javascript'; break;
			case 'pl':
				$cp_lang = 'perl'; break;
			case 'ruby':
				$cp_lang = 'ruby'; break;
			case 'sql':
				$cp_lang = 'sql'; break;
			case 'vb':
			case 'vbs':
				$cp_lang = 'vbscript'; break;
			case 'php':
				$cp_lang = 'php'; break;
			default:
				$cp_lang = 'generic';
		}
		return $cp_lang; 
	}
}	