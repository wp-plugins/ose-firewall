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
if (!defined('OSEFWDIR')) {
	die("Direct Access Not Allowed");
}
if (!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}	
class oseJSON {
	function generateQueryWhere() {
		$db = oseDB::instance();
		$filters = JRequest::getVar('filter', null);
		// GridFilters sends filters as an Array if not json encoded
		if (is_array($filters)) {
			$encoded = false;
		} else {
			$encoded = true;
			$filters = json_decode($filters);
		}
		$where = array();
		// loop through filters sent by client
		if (is_array($filters)) {
			for ($i = 0; $i < count($filters); $i++) {
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
			}
			switch ($filterType) {
			case 'string':
				$where[] = $field . " LIKE '%" . $db->Quote($value) . "%'";
				break;
			case 'list':
				if (strstr($value, ',')) {
					$fi = explode(',', $value);
					for ($q = 0; $q < count($fi); $q++) {
						$fi[$q] = $db->Quote($fi[$q]);
					}
					$value = implode(',', $fi);
					$where[] = $field . " IN (" . $value . ")";
				} else {
					$where[] = "{$field} = " . $db->Quote($value);
				}
				break;
			}
		}
		return $where;
	}
	public static function encode($arr) {
		if (version_compare(PHP_VERSION, "5.2", "<")) {
			if (file_exists(dirname(__FILE__) . DS . "Services/JSON.php")) {
				require_once(dirname(__FILE__) . DS . "Services/JSON.php"); //if php<5.2 need JSON class
			}
			$json = new Services_JSON(); //instantiate new json object
			$data = $json->encode($arr); //encode the data in json format
		} else {
			$data = json_encode($arr); //encode the data in json format
		}
		return $data;
	}
	public static function decode($json, $assoc = false) {
		if (version_compare(PHP_VERSION, "5.2", "<")) {
			if (file_exists(dirname(__FILE__) . DS . "Services/JSON.php")) {
				require_once(dirname(__FILE__) . DS . "Services/JSON.php"); //if php<5.2 need JSON class
			}
			$Services_json = new Services_JSON(); //instantiate new json object
			$data = $Services_json->decode($json, $assoc); //encode the data in json format
		} else {
			$data = json_decode($json, $assoc); //encode the data in json format
		}
		return $data;
	}
}
?>