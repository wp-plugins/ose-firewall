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
 * @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC')) {
    die('Direct Access Not Allowed');
}

class oseFirewallUploadManager
{
    protected $db = null;
    protected $where = array();
    protected $orderBy = ' ';
    protected $limitStm = ' ';

    public function __construct()
    {
        $this->setDBO();
        oseFirewall::callLibClass('convertviews', 'convertviews');
        oseFirewall::loadRequest();
    }

    protected function setDBO()
    {
        $this->db = oseFirewall::getDBO();
    }

    public function getExtLists()
    {
        $columns = oRequest::getVar('columns', null);
        $limit = oRequest::getInt('length', 15);
        $start = oRequest::getInt('start', 0);
        $search = oRequest::getVar('search', null);
        $orderArr = oRequest::getVar('order', null);
        $sortby = null;
        $orderDir = 'asc';
        if (!empty($columns[3]['search']['value'])) {
            $status = $columns[3]['search']['value'];
        } else {
            $status = null;
        }
        if (!empty($columns[2]['search']['value'])) {
            $type = $columns[2]['search']['value'];
        } else {
            $type = null;
        }
        if (!empty($orderArr[0]['column'])) {
            $sortby = $columns[$orderArr[0]['column']]['data'];
            $orderDir = $orderArr[0]['dir'];
        }
        $return = $this->getExtListsDB($search['value'], $status, $type, $start, $limit, $sortby, $orderDir);
        $return['data'] = $this->convertExtListsMap($return['data']);
        return $return;
    }

    private function getExtListsDB($search, $status, $type, $start, $limit, $sortby, $orderDir)
    {
        $return = array();
        if (!empty($search)) {
            $this->getWhereName($search);
        }
        if (!empty($status)) {
            $this->getWhereStatus($status);
        }
        if (!empty($type)) {
            $this->getWhereType($type);
        }
        $this->getOrderBy($sortby, $orderDir);
        if (!empty($limit)) {
            $this->getLimitStm($start, $limit);
        }
        $where = $this->db->implodeWhere($this->where);
        $return['data'] = $this->getAllRecords($where);
        $count = $this->getAllCounts($where);
        $return['recordsTotal'] = $count['recordsTotal'];
        $return['recordsFiltered'] = $count['recordsFiltered'];

        return $return;
    }

    protected function getWhereName($search)
    {
        $this->where[] = "`ext_name` LIKE " . $this->db->quoteValue($search . '%', true) . " OR `ext_type` LIKE " . $this->db->quoteValue($search . '%', true);
    }

    protected function getWhereStatus($status)
    {
        if ($status == 2) {
            $this->where[] = "`ext_status` = " . (int)0;
        } else {
            $this->where[] = "`ext_status` = " . $status;

        }
    }

    protected function getWhereType($type)
    {
        $this->where[] = "`ext_type` = '" . $type . "'";
    }

    protected function getOrderBy($sortby, $orderDir)
    {
        if (empty($sortby)) {
            $this->orderBy = "";
        } else {
            $this->orderBy = " ORDER BY " . addslashes($sortby) . ' ' . addslashes($orderDir);
        }
    }

    protected function getLimitStm($start, $limit)
    {
        if (!empty($limit)) {
            $this->limitStm = " LIMIT " . (int)$start . ", " . (int)$limit;
        }
    }

    private function getAllRecords($where)
    {
        $sql = 'SELECT `ext_id`,`ext_name`,`ext_type`,`ext_status` FROM `#__osefirewall_fileuploadext`';
        $query = $sql . $where . $this->orderBy . " " . $this->limitStm;
        $this->db->setQuery($query);
        $results = $this->db->loadObjectList();
        return $results;
    }

    private function getAllCounts($where)
    {
        $return = array();
        // Get total count
        $attrList = array("COUNT(`ext_id`) AS count");
        $sql = 'SELECT COUNT(`ext_id`) AS count FROM `#__osefirewall_fileuploadext`';
        $this->db->setQuery($sql);
        $result = $this->db->loadObject();
        $return['recordsTotal'] = $result->count;
        // Get filter count
        $this->db->setQuery($sql . $where);
        $result = $this->db->loadObject();
        $return['recordsFiltered'] = $result->count;
        return $return;
    }

    private function convertExtListsMap($results)
    {
        $i = 0;
        $return = array();
        foreach ($results as $result) {
            $return[$i] = $result;
            $return[$i]->ext_status = $this->getStatusIcon($result->ext_id, $result->ext_status);
            $i++;
        }
        return $return;
    }

    private function getStatusIcon($id, $status)
    {
        if ($status == 1) {
            $status = '<a id="' . $id . '" href="javascript:void(0);" onclick="changeStatus(0,' . $id . ')"><div class="fa fa-check color-green"></div></a>';
        } else {
            $status = '<a id="' . $id . '" href="javascript:void(0);" onclick="changeStatus(1,' . $id . ')"><div class="fa fa-times color-red"></div></a>';
        }
        return $status;
    }

    public function changeStatus($status, $id)
    {
        $statusArray = array(
            'ext_status' => $status,
        );
        $db = oseFirewall::getDBO();
        $id = $db->addData('update', '#__osefirewall_fileuploadext', 'ext_id', $id, $statusArray);
        $db->closeDBO();
        return $id;
    }

    public function saveExt($name, $type, $status)
    {
        if ($status == 2) {
            $status = 0;
        }
        $Array = array(
            'ext_name' => $name,
            'ext_type' => $type,
            'ext_status' => $status,
        );
        $db = oseFirewall::getDBO();
        $id = $db->addData('insert', '#__osefirewall_fileuploadext', '', '', $Array);
        $db->closeDBO();
        return $id;
    }

    public function migrate($migrateData)
    {
        $ArrayData = explode(",", trim($migrateData));
        $db = oseFirewall::getDBO();
        foreach ($ArrayData as $single) {
            $Array = array(
                'ext_status' => 1,
            );
            $id = $db->addData('update', '#__osefirewall_fileuploadext', 'ext_name', trim($single), $Array);
        }
        $db->closeDBO();
        return $id;
    }

    public function logViolatedFileIP($scanResult)
    {
        $extData = $this->lookupFileExtByName($scanResult['file_type']);
        $filetypeid = empty($extData['ext_id'])? 0 : $extData['ext_id']; //set 0 for ext that don't exist in ext table

        $array = array(
            'ip_id' => $scanResult['ip_id'],
            'file_name' => $scanResult['file_name'],
            'file_type_id' => $filetypeid,
            'validation_status' => $scanResult['validation_status'],
            'vs_scan_status' => $scanResult['vs_scan_status'],
            'datetime' => $scanResult['datetime']
        );
        $db = oseFirewall::getDBO();
        $id = $db->addData('insert', '#__osefirewall_fileuploadlog', '', '', $array);
        $db->closeDBO();
    }

    private function lookupFileExtByName ($name)
    {
        $query = "SELECT * FROM `#__osefirewall_fileuploadext` WHERE `ext_name` = '" . $name . "'";
        $this->db->setQuery($query);
        $results = $this->db->loadResult();
        return $results;
    }

    public function getLog()
    {
        $columns = oRequest::getVar('columns', null);
        $limit = oRequest::getInt('length', 15);
        $start = oRequest::getInt('start', 0);
        $search = oRequest::getVar('search', null);
        $orderArr = oRequest::getVar('order', null);
        $sortby = null;
        $orderDir = 'asc';

        if (!empty($orderArr[0]['column'])) {
            $sortby = $columns[$orderArr[0]['column']]['data'];
            $orderDir = $orderArr[0]['dir'];
        }
        $return = $this->getLogDB($search['value'], $start, $limit, $sortby, $orderDir);
        $return['data'] = $this->convertLogMap($return['data']);
        return $return;
    }

    private function getLogDB($search, $start, $limit, $sortby, $orderDir)
    {
        $return = array();
        if (!empty($search)) {
            $this->getWhereNameLog($search);
        }
        $this->getOrderByLog($sortby, $orderDir);
        if (!empty($limit)) {
            $this->getLimitStm($start, $limit);
        }
        $where = $this->db->implodeWhere($this->where);
        $return['data'] = $this->getAllLogRecords($where);
        $count = $this->getAllLogCounts($where);
        $return['recordsTotal'] = $count['recordsTotal'];
        $return['recordsFiltered'] = $count['recordsFiltered'];
        return $return;
    }

    private function getWhereNameLog($search)
    {
        $this->where[] = "`file_name` LIKE " . $this->db->quoteValue($search . '%', true) . " OR `file_type` LIKE " . $this->db->quoteValue($search . '%', true);
    }

    private function getOrderByLog($sortby, $orderDir)
    {
        if (empty($sortby)) {
            $this->orderBy = " ORDER BY datetime DESC";
        } else {
            $this->orderBy = " ORDER BY " . addslashes($sortby) . ' ' . addslashes($orderDir);
        }
    }

    private function getAllLogRecords($where)
    {
        $sql = "SELECT FUL.`id`,ACL.`name` AS 'ip_name', `file_name`, FUE.`ext_name`,`validation_status`,`vs_scan_status`,FUL.`datetime`
                FROM `#__osefirewall_fileuploadlog` AS FUL
                LEFT JOIN #__osefirewall_acl AS ACL ON ACL.id = FUL.ip_id
                LEFT JOIN #__osefirewall_fileuploadext AS FUE ON FUE.ext_id = FUL.file_type_id";
        $query = $sql . $where . $this->orderBy . " " . $this->limitStm;
        $this->db->setQuery($query);
        $results = $this->db->loadObjectList();
        return $results;
    }

    private function getAllLogCounts($where)
    {
        $return = array();
        // Get total count
        $attrList = array("COUNT(`id`) AS count");
        $sql = 'SELECT COUNT(`id`) AS count FROM `#__osefirewall_fileuploadlog`';
        $this->db->setQuery($sql);
        $result = $this->db->loadObject();
        $return['recordsTotal'] = $result->count;
        // Get filter count
        $this->db->setQuery($sql . $where);
        $result = $this->db->loadObject();
        $return['recordsFiltered'] = $result->count;
        return $return;
    }

    private function convertLogMap($results)
    {
        $i = 0;
        $return = array();
        foreach ($results as $result) {
            $return[$i] = $result;
            $return[$i]->validation_status = $this->getLogStatusIcon($result->validation_status);
            $i++;
        }
        return $return;
    }

    private function getLogStatusIcon($status)
    {
        switch ( $status ){
            case 0 :
                $status = '<div class="color-green"><i class="glyphicon glyphicon-check"></i> '.oLang:: _get('FILE_UPLOAD_LOG').'</div>';
                break;
            case 1 :
                $status = '<div class="color-orange"><i class="glyphicon glyphicon-bullhorn"></i> '.oLang:: _get('BLOCKED_UPLOAD_LOG').'</div>';
                break;
            case 2 :
                $status = '<div class="color-red"><i class="glyphicon glyphicon-alert"></i> '.oLang:: _get('INCONSISTENT_FILE').'</div>';
                break;
        }
        return $status;
    }
}