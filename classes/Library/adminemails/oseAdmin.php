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
    die ('Direct Access Not Allowed');
}

class oseAdminManager
{
    private $adminTable = '#__osefirewall_adminemails';
    private $domainTable = '#__osefirewall_domains';
    private $configTable = '#__ose_secConfig';
    public $columns = array(
        array(
            'db' => 'A_id',
            'dt' => 0
        ),
        array(
            'db' => 'A_name',
            'dt' => 1
        ),
        array(
            'db' => 'A_email',
            'dt' => 2
        ),
        array(
            'db' => 'A_status',
            'dt' => 3
        ),
        array(
            'db' => 'D_id',
            'dt' => 4
        )
    );

    public function __construct()
    {
        $this->setDBO();
        oseFirewall::loadRequest();
        oseFirewall::loadFiles();
        oseFirewall::loadDateClass();
        if (OSE_CMS == 'joomla') {
            $this->createGroup();
        }
    }

    protected function setDBO()
    {
        $this->db = oseFirewall::getDBO();
    }

    public function getAdminList()
    {
        $data = $this->getAdminDB();
        $number = $this->getAdminTotal();
        $post_draw = oRequest::getInt('draw');
        $result = array(
            "draw" => $post_draw,
            "recordsTotal" => $number,
            "recordsFiltered" => $number,
            "data" => $data
        );
        return $result;
    }

    public function getLimit()
    {
        $limit = '';
        $post_start = oRequest::getInt('start');
        $post_length = oRequest::getInt('length');
        if (isset ($post_start) && $post_length != -1) {
            $limit = "LIMIT " . $post_start . ", " . $post_length;
        }
        return $limit;
    }

    public function getOrder()
    {
        $order = '';
        $post_order = oRequest::getVar('order');
        $post_columns = oRequest::getVar('columns');
        if (isset ($post_order) && count($post_order)) {
            $orderBy = array();
            for ($i = 0, $ien = count($post_order); $i < $ien; $i++) {
                $columnIdx = intval($post_order [$i] ['column']);
                $requestColumn = $post_columns [$columnIdx];
                $column = $this->columns [$i];
                if ($requestColumn ['orderable'] == 'true') {
                    $dir = $post_order [$i] ['dir'] === 'asc' ? 'ASC' : 'DESC';
                    $orderBy [$i] = '`' . $column ['db'] . '` ' . $dir;
                }
            }
            $order = 'ORDER BY ' . implode(', ', $orderBy);
        }
        return $order;
    }

    public function getWhere()
    {
        $where = '';
        $post_search = oRequest::getVar('search');
        $post_columns = oRequest::getVar('columns');
        $globalSearch = array();
        if (isset ($post_search) && $post_search ['value'] != '') {
            $str = $post_search ['value'];
            for ($i = 0, $ien = count($post_columns); $i < $ien - 1; $i++) {
                $requestColumn = $post_columns [$i];
                $column = $this->columns [$i];
                if ($requestColumn ['searchable'] == true) {
                    $newstr = "'" . '%' . $str . '%' . "'";
                    $globalSearch [$i] = "`" . $column ['db'] . "` LIKE " . $newstr;
                }
            }
        }
        // Combine the filters into a single string
        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }
        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }
        return $where;
    }

    public function getAdminTotal()
    {
        $db = oseFirewall::getDBO();
        $result = $db->getTotalNumber('A_id', $this->adminTable);
        $db->closeDBO();
        return $result;
    }

    public function getAdminDB()
    {
        $limit = $this->getLimit();
        $order = $this->getOrder();
        $where = $this->getWhere();
        $db = oseFirewall::getDBO();
        $query = "SELECT * FROM `" . $this->adminTable . "` $where $order $limit";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $db->closeDBO();
        return $this->convertResultsList($results);
    }
    protected function convertResultsList($results)
    {
        $return = array();
        $i = 0;
        foreach ($results as $file) {
            $db = oseFirewall::getDBO();
            $sql = "SELECT `D_address` FROM `" . $this->domainTable . "` WHERE `D_id` = $file->D_id";
            $db->setQuery($sql);
            $domain = $db->loadObjectList();
            if ($file->A_status == 'active') {
                $status = '<a id="' . $file->A_id . '" href="javascript:void(0);" onclick="changeStatus(0,' . $file->A_id . ')"><div class="fa fa-check color-green"></div></a>';
            } else {
                $status = '<a id="' . $file->A_id . '" href="javascript:void(0);" onclick="changeStatus(1,' . $file->A_id . ')"><div class="fa fa-times color-red"></div></a>';
            }
            $db->closeDBO();
            $return [$i] = array(
                "ID" => $file->A_id,
                "Name" => $file->A_name,
                "Email" => $file->A_email,
                "Status" => $status,
                "Domain" => $domain[0]->D_address
            );
            $i++;
        }
        return $return;
    }

    public function saveDomain($domain)
    {
        $domainArray = $this->getDomainArray($domain);
        $result = $this->saveDomainDB($domainArray);
        return $result;
    }

    public function getDomainArray($domain)
    {
        $domainNoSpace = str_replace(' ', '', $domain);
        $domainArray = array(
            'D_address' => $domainNoSpace
        );
        return $domainArray;
    }

    public function saveDomainDB($domainArray)
    {
        $db = oseFirewall::getDBO();
        $id = $db->addData('insert', $this->domainTable, '', '', $domainArray);
        $db->closeDBO();
        return $id;
    }

    public function getDomain()
    {
        $domain = $this->getDomainDB();
        $domainHtml = $this->convertDomain($domain);
        return $domainHtml;
    }

    public function getDomainDB()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `D_address` FROM `" . $this->domainTable . "`";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $db->closeDBO();
        if (empty($results)) {
            $blank = "<option value=''>Please add domain first</option>";
            return $blank;
            exit;
        } else {
            return $results;
        }
    }

    public function convertDomain($domain)
    {
        $i = 0;
        foreach ($domain as $option) {
            $result[$i] = "<option value='$option->D_address'>" . $option->D_address . "</option>";
            $i++;
        }
        $allDomain = "<option value='all'>Select All</option>";
        array_unshift($result, $allDomain);
        return $result;
    }

    public function saveAdmin($name, $email, $status, $domain)
    {
        $adminArray = $this->getAdminArray($name, $email, $status, $domain);
        $db = oseFirewall::getDBO();
        if ($domain == 'all') {
            foreach ($adminArray as $sole) {
                $id = $db->addData('insert', $this->adminTable, '', '', $sole);
            }
        } else {
            $id = $db->addData('insert', $this->adminTable, '', '', $adminArray);
        }
        $db->closeDBO();
        return $id;
    }
    public function getAdminArray($name, $email, $status, $domain)
    {
        $adminNameNoSpace = str_replace(' ', '', $name);
        $adminEmailNoSpace = str_replace(' ', '', $email);
        if ($domain !== 'all') {
        $domainID = $this->getDomainID($domain);
        $adminArray = array(
            'A_name' => $adminNameNoSpace,
            'A_email' => $adminEmailNoSpace,
            'A_status' => $status,
            'D_id' => $domainID[0]->D_id
        );
        } else {
            $i = 0;
            $domainID = $this->getDomainID();
            foreach ($domainID as $domainSole) {
                $adminArray[$i] = array(
                    'A_name' => $adminNameNoSpace,
                    'A_email' => $adminEmailNoSpace,
                    'A_status' => $status,
                    'D_id' => $domainSole->D_id
                );
                $i++;
            }
        }

        return $adminArray;
    }

    public function getDomainID($domain = null)
    {
        $db = oseFirewall::getDBO();
        if ($domain == null) {
            $query = "SELECT `D_id` FROM `" . $this->domainTable;
            $db->setQuery($query);
            $results = $db->loadObjectList();
        } else {
        $query = "SELECT `D_id` FROM `" . $this->domainTable . "` WHERE `D_address` = '" . $domain . "'";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        }
        $db->closeDBO();
        return $results;
    }

    public function changeStatus($status, $id)
    {
        if ($status == 0) {
            $statusArray = array(
                'A_status' => 'inactive'
            );
        } else {
            $statusArray = array(
                'A_status' => 'active'
            );
        }
        $db = oseFirewall::getDBO();
        $id = $db->addData('update', $this->adminTable, 'A_id', $id, $statusArray);
        $db->closeDBO();
        return $id;
    }

    public function deleteAdmin($id)
    {
        foreach ($id as $newid) {
            $condition = array(
                'A_id' => $newid
            );
            $db = oseFirewall::getDBO();
            $flag = $db->deleteRecord($condition, $this->adminTable);
            $db->closeDBO();
        }
        return $flag;
    }

    public function saveEmailEditor($content)
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `key` FROM `" . $this->configTable . "` WHERE `key` = 'emailTemplate'";
        $db->setQuery($query);
        $flag = $db->loadObjectList();
        if (empty($flag)) {
            $contentArray = array(
                'key' => 'emailTemplate',
                'value' => $content,
                'type' => 'emailTemp',
            );
            $db->addData('insert', $this->configTable, '', '', $contentArray);
            $db->closeDBO();
        } else {
            $contentArray = array(
                'value' => $content,
            );
            $db->addData('update', $this->configTable, 'key', 'emailTemplate', $contentArray);
            $db->closeDBO();
        }
    }

    public function readEmailTemp()
    {
        $content = "";
        if (file_exists(dirname(__FILE__) . ODS . 'email.tpl')) {
            $content = file_get_contents(dirname(__FILE__) . ODS . 'email.tpl');
        }
        return $content;
    }

    public function restoreDefault()
    {
        $condition = array('type' => 'emailTemp');
        $db = oseFirewall::getDBO();
        $flag = $db->deleteRecordString($condition, $this->configTable);
        $db->closeDBO();
        return $flag;
    }

    public function getSecManagers()
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
        $return = $this->getSecManagersDB($search['value'], $start, $limit, $sortby, $orderDir);
        $return['data'] = $this->convertSecManagers($return['data']);
        return $return;
    }

    private function getSecManagersDB($search, $start, $limit, $sortby, $orderDir)
    {
        $return = array();
        if (!empty($search)) {
            $this->getWhereName($search);
        }
        $this->getOrderBy($sortby, $orderDir);
        if (!empty($limit)) {
            $this->getLimitStm($start, $limit);
        }
        $where = $this->db->implodeWhere($this->where);
        // Get Records Query;
        $return['data'] = $this->getAllRecords($where);
        $count = $this->getAllCounts($where);
        $return['recordsTotal'] = $count['recordsTotal'];
        $return['recordsFiltered'] = $count['recordsFiltered'];

        return $return;
    }

    protected function getWhereName($search)
    {
        $this->where[] = "`name` LIKE " . $this->db->quoteValue($search . '%', true) . " OR `email` LIKE " . $this->db->quoteValue($search . '%', true);
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
        if (OSE_CMS == 'wordpress') {
            $sql = 'SELECT `users`.`ID` AS `id`,`users`.`user_login` AS `username`,`users`.`user_email` AS `email`,`users`.`user_status` AS `block` FROM `#__users` AS `users` LEFT JOIN `#__ose_secConfig` `secConfig` ON `users`.`ID` = `secConfig`.`value`';
            if (empty($where)) {
                $where = " WHERE `secConfig`.`key` = 'SecurityManager'";
            } else {
                $where .= " AND `secConfig`.`key` = 'SecurityManager'";
            }
            $query = $sql . $where . $this->orderBy . " " . $this->limitStm;
            $this->db->setQuery($query);
            $results = $this->db->loadObjectList();
        } else {
            $sql = 'SELECT `users`.`id`,`users`.`username`,`users`.`email`,`users`.`block` FROM `#__users` AS `users` LEFT JOIN `#__user_usergroup_map` `usergroupmap` ON `users`.`id` = `usergroupmap`.`user_id`
          LEFT JOIN `#__usergroups` `usergroups` ON `usergroups`.`id` = `usergroupmap`.`group_id`';
            if (empty($where)) {
                $where = " WHERE `usergroups`.`title` = 'Security Manager'";
            } else {
                $where .= " AND `usergroups`.`title` = 'Security Manager'";
            }
            $query = $sql . $where . $this->orderBy . " " . $this->limitStm;
            $this->db->setQuery($query);
            $results = $this->db->loadObjectList();
        }
        return $results;
    }

    private function convertSecManagers($results)
    {
        $i = 0;
        $return = array();
        foreach ($results as $result) {
            $return[$i] = $result;
            if ($return[$i]->block == 0) {
                $return[$i]->block = '<a id="' . $return[$i]->id . '" href="javascript:void(0);" onclick="changeBlock(0,' . $return[$i]->id . ')"><div class="fa fa-check color-green"></div></a>';
            } else {
                $return[$i]->block = '<a id="' . $return[$i]->id . '" href="javascript:void(0);" onclick="changeBlock(1,' . $return[$i]->id . ')"><div class="fa fa-times color-red"></div></a>';
            }
            $return[$i]->contact = $this->getEmailIcon($result->email);
            $i++;
        }
        return $return;
    }

    private function getEmailIcon($email)
    {
        $link = '<a href="mailto:' . $email . '?" target="_top">Send Mail</a>';
        return $link;
    }

    private function getAllCounts($where)
    {
        $return = array();
        // Get total count
        if (OSE_CMS == 'wordpress') {
            $sql = "SELECT COUNT(`users`.`ID`) AS count FROM `#__users`AS `users` LEFT JOIN `#__ose_secConfig` `secConfig` ON `users`.`ID` = `secConfig`.`value` WHERE `secConfig`.`key` = 'SecurityManager' ";

            $query = "SELECT COUNT(`users`.`id`) AS count FROM `#__users`AS `users` LEFT JOIN `#__ose_secConfig` `secConfig` ON `users`.`ID` = `secConfig`.`value`";

            $this->db->setQuery($sql);
            $result = $this->db->loadObject();
            $return['recordsTotal'] = $result->count;
            // Get filter count
            if (empty($where)) {
                $this->db->setQuery($sql);
                $result = $this->db->loadObject();
                $return['recordsFiltered'] = $result->count;
            } else {
                $this->db->setQuery($query . $where . " AND `secConfig`.`key` = 'SecurityManager'");
                $result = $this->db->loadObject();
                $return['recordsFiltered'] = $result->count;
            }
        } else {
            $sql = "SELECT COUNT(`users`.`id`) AS count FROM `#__users`AS `users` LEFT JOIN `#__user_usergroup_map` `usergroupmap` ON `users`.`id` = `usergroupmap`.`user_id`
          LEFT JOIN `#__usergroups` `usergroups` ON `usergroups`.`id` = `usergroupmap`.`group_id` WHERE `usergroups`.`title` = 'Security Manager' ";

            $query = "SELECT COUNT(`users`.`id`) AS count FROM `#__users`AS `users` LEFT JOIN `#__user_usergroup_map` `usergroupmap` ON `users`.`id` = `usergroupmap`.`user_id`
          LEFT JOIN `#__usergroups` `usergroups` ON `usergroups`.`id` = `usergroupmap`.`group_id`";
            $this->db->setQuery($sql);
            $result = $this->db->loadObject();
            $return['recordsTotal'] = $result->count;
            // Get filter count
            if (empty($where)) {
                $this->db->setQuery($sql);
                $result = $this->db->loadObject();
                $return['recordsFiltered'] = $result->count;
            } else {
                $this->db->setQuery($query . $where . " AND `usergroups`.`title` = 'Security Manager'");
                $result = $this->db->loadObject();
                $return['recordsFiltered'] = $result->count;
            }
        }
        return $return;
    }

    private function createGroup()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `title` FROM `#__usergroups` WHERE `title` LIKE 'Security Manager'";
        $db->setQuery($query);
        $flag = $this->db->loadObject();
        if (empty($flag)) {
            require_once(dirname(OSEFWDIR) . ODS . 'com_users' . ODS . 'models' . ODS . 'group.php');
            $userGroup = new UsersModelGroup();
            $Array = array(
                'id' => 0,
                'title' => 'Security Manager',
                'parent_id' => 1,
                'action' => Array(),
                'tags' => '',
            );
            $userGroup->save($Array);
        }
        $query = "SELECT `id` FROM `#__usergroups` WHERE `title` LIKE 'Security Manager'";
        $db->setQuery($query);
        $flag = $this->db->loadObject();
        $this->insertRule($flag->id);
        $db->closeDBO();
    }

    private function insertRule($groupid)
    {
        $query = "SELECT `rules` From `#__assets` WHERE `name` = 'root.1';";
        $this->db->setQuery($query);
        $results = $this->db->loadObject();
        $results = json_decode($results->rules);
        $results->{'core.login.admin'}->$groupid = 1;
        $results = json_encode($results);
        $Array = array(
            'rules' => $results
        );
        $id = $this->db->addData('update', '#__assets', 'name', 'root.1', $Array);
        return $id;
    }

    public function saveSecManager($name, $username, $email, $password)
    {
        if (OSE_CMS == 'wordpress') {

            $userdata = array();
            $userdata = array(
                'first_name' => $name,
                'user_login' => $username,
                'user_pass' => trim($password),  // When creating an user, `user_pass` is expected.
                'user_email' => $email,
                'role' => 'subscriber',
            );
            $user_id = wp_insert_user($userdata);
            //On success
            if (!is_wp_error($user_id)) {
                $Array = array();
                $Array = array(
                    'key' => 'SecurityManager',
                    'value' => $user_id,
                    'type' => 'secManager'
                );
                $db = oseFirewall::getDBO();
                $id = $db->addData('insert', '#__ose_secConfig', '', '', $Array);
                $db->closeDBO();
                if (is_int($id)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            $db = oseFirewall::getDBO();
            $query = "SELECT `id` FROM `#__usergroups` WHERE `title` LIKE 'Security Manager'";
            $db->setQuery($query);
            $flag = $this->db->loadObject();
            require_once(dirname(OSEFWDIR) . ODS . 'com_users' . ODS . 'models' . ODS . 'user.php');
            $user = new UsersModelUser();
            $Array = array(
                'name' => $name,
                'username' => $username,
                'password' => $password,
                'password2' => $password,
                'email' => $email,
                'registerDate' => '',
                'lastvisitDate' => '',
                'lastResetTime' => '',
                'resetCount' => 0,
                'sendEmail' => 0,
                'block' => 0,
                'requireReset' => 0,
                'id' => 0,
                'groups' => Array($flag->id),
                'params' => Array(
                    'admin_style' => '',
                    'admin_language' => '',
                    'language' => '',
                    'editor' => '',
                    'helpsite' => '',
                    'timezone' => '',
                ),
                'tags' => ''
            );
            try {
                $user->save($Array);
                return true;
            } catch (Exception $e) {
                return $e->getMessage();
            }
            $db->closeDBO();
        }
    }

    public function changeBlock($status, $id)
    {
        $statusArray = array();
        $db = oseFirewall::getDBO();
        if (OSE_CMS == 'wordpress') {
            if ($status == 0) {
                $statusArray = array(
                    'user_status' => 1
                );
            } else {
                $statusArray = array(
                    'user_status' => 0
                );
            }
            $id = $db->addData('update', '#__users', 'ID', $id, $statusArray);
        } else {
            if ($status == 0) {
                $statusArray = array(
                    'block' => 1
                );
            } else {
                $statusArray = array(
                    'block' => 0
                );
            }
            $id = $db->addData('update', '#__users', 'id', $id, $statusArray);
        }
        $db->closeDBO();
        return $id;
    }
}