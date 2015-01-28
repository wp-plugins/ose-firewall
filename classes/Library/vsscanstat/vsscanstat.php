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
class oseVsscanStat {
	private $filestable = '#__osefirewall_files';
	private $logstable = '#__osefirewall_logs';
	private $malwaretable = '#__osefirewall_malware';
	protected $db = null;
	protected $where = array ();
	protected $orderBy = ' ';
	protected $limitStm = ' ';
	public function __construct()
	{
		$this->setDBO ();
		oseFirewall::loadRequest();
		oseFirewall::loadFiles(); 
		oseFirewall::callLibClass('convertviews', 'convertviews');
	}
	protected function setDBO () {
		$this->db = oseFirewall::getDBO();
	}
	public function getTypeList() {
		$query = "SELECT * FROM `#__osefirewall_vstypes`";
		$this->db->setQuery($query); 
		$result = $this->db->loadObjectList();
		$this->db->closeDBO ();
		return $result; 
	}
	public function getMalwareMap () {
		$columns = oRequest::getVar('columns', null);
		$limit = oRequest::getInt('length', 15);
		$start = oRequest::getInt('start', 0);
		$type_id = oRequest::getInt('type_id', 0);
		$search = oRequest::getVar('search', null);
		$orderArr = oRequest::getVar('order', null);
		$sortby = null;
		$orderDir = 'asc';
		if (!empty($columns[7]['search']['value']))
		{
			$status = $columns[7]['search']['value'];
		}
		else
		{
			$status = null;
		}
		if (!empty($orderArr[0]['column']))
		{
			$sortby = $columns[$orderArr[0]['column']]['data'];
			$orderDir = $orderArr[0]['dir'];
		}
		$return = $this->getMalwareMapDB($search['value'], $type_id, $start, $limit, $sortby, $orderDir);
		$return['data'] = $this->convertMalwareMap($return['data']);
		return $return;
	}
	private function convertMalwareMap ($results) {
		$return = array();
		$i=0;  
		foreach ($results as $result)
		{
			$return[$i] = $result;
			$return[$i] ->checkbox = '';
			$return[$i] ->view = "<a href='#' title = 'View detail' onClick= 'viewFiledetail(".$result->file_id.")' ><i class='im-dashboard'></i></a>";
			$i++;
		}
		return $return;
	}
	protected function getWhereName ($search) {
		$this->where[] = "`f`.`filename` LIKE ".$this->db->quoteValue($search.'%', true) ;
	}
	protected function getWhereStatus ($status) {
		$this->where[] = "`v`.`type_id` = ".(int)$type_id;
	}
	protected function getOrderBy ($sortby, $orderDir) {
		if (empty($sortby))
		{
			$this->orderBy= " ORDER BY `f`.`filename` DESC ";
		}
		else
		{
			$this->orderBy= " ORDER BY ".addslashes($sortby).' '.addslashes($orderDir);
		}
	}
	protected function getLimitStm ($start, $limit) {
		if (!empty($limit))
		{
			$this->limitStm = " LIMIT ".(int)$start.", ".(int)$limit;
		}
	}
	private function getAllRecords ($where) {
		$attrList = array("*");
		$sql = convertViews::convertDetMalware($attrList);
		$query = $sql.$where.$this->orderBy." ".$this->limitStm;
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		return $results;
	}
	private function getAllCounts($where) {
		$return = array();
		// Get total count
		$attrList = array("COUNT(`f`.`id`) AS count");
		$sql = convertViews::convertDetMalware($attrList);
		$this->db->setQuery($sql);
		$result = $this->db->loadObject();
		$return['recordsTotal'] = $result->count;
		// Get filter count
		$this->db->setQuery($sql.$where);
		$result = $this->db->loadObject();
		$return['recordsFiltered'] = $result->count;
		return $return;
	}
	public function getMalwareMapDB ($search, $type_id, $start, $limit, $sortby, $orderDir) {
		$return = array ();
		if (!empty($search)) {$this->getWhereName ($search);}
		if (!empty($type_id)) {$this->getWhereStatus ($type_id);}
		$this->getOrderBy ($sortby, $orderDir);
		if (!empty($limit)) {$this->getLimitStm ($start, $limit);}
		$where = $this->db->implodeWhere($this->where);
		// Get Records Query;
		$return['data'] = $this->getAllRecords ($where);
		$counts = $this->getAllCounts($where);
		$return['recordsTotal'] = $counts['recordsTotal'];
		$return['recordsFiltered'] = $counts['recordsFiltered'];
		return $return;
	}
	public function getMalwareTotal () {
		oseFirewall::callLibClass('convertviews','convertviews');
		$attrList = array("COUNT(`file_id`) as `count`");
		$sql = convertViews::convertDetMalware($attrList);
		$query = $sql;
		$this->db->setQuery($query);
		$result = (object) ($this->db->loadResult());  
		$this->db->closeDBO ();
		return $result->count;
	}
	public function getFileContent($id)
	{
		$filename = $this->getFilePath ($id); 
		$pattern = $this->getPattern ($id);
		$fileType = $this->getFileType($filename);
		if (!in_array($fileType, array('php, javascript')))
		{
			$fileType = 'htmlmixed';
		}
		$content = '<textarea class="'.$fileType.'" style="width:100%;" name="codearea" id="codearea" rows="15" cols="120" wrap="off" >';
		if (!empty($filename))
		{
			$fileContent = oseFile::read($filename);
			$fileContent = htmlspecialchars($fileContent,ENT_QUOTES,'ISO-8859-1' );
			$fileContent = preg_replace_callback(
							'/'.$pattern.'/ims',
							function ($matches) {
								return "\n\n//**************************** MALWARE FOUND ****************************\n\n\t".$matches[0]."\n\n//**************************** MALWARE FOUND ****************************\n\n";
							},
							$fileContent
			);
			$content .= ($fileContent);
		}
		else
		{
			$content .='Cannot read the file'; 
		}
		$content .= '</textarea>';
		return $content; 
	}
	private function getFilePath ($id) 
	{
		$query = "SELECT `filename` FROM `#__osefirewall_files` WHERE `id` =".(int)$id;
		$this->db->setQuery($query);
		$result = (object) $this->db->loadResult();
		$this->db->closeDBO ();
		return $result ->filename; 
	}
	private function getPattern ($file_id)
	{
		$query = "SELECT patterns FROM `#__osefirewall_vspatterns` WHERE `id` = ".
				 "(SELECT `pattern_id` FROM `#__osefirewall_malware` WHERE `file_id` = ".(int)$file_id.")";
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		$this->db->closeDBO ();
		return $result ->patterns;
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