<?php
/**
 * @version     6.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Open Source Excellence CPU
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 30-Sep-2010
 * @author        Updated on 30-Mar-2013 
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @copyright Copyright (C) 2008 - 2010- ... Open Source Excellence
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
 *
*/
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH')) {
	die('Direct Access Not Allowed');
}
class oseEmail {
	private $table = '#__ose_app_email';
	private $view = '#__ose_app_adminemailmap';
	private $app = null;
	private $db = null;
	private $cms = null;
	public function __construct($app) {
		$this->app = $app;
		$this->setCMS();
		$this->setDB();
	}
	private function setCMS()
	{
		if (defined('_JEXEC')) {
			$this->cms = 'joomla';
		} else if (defined('WPLANG')) {
			$this->cms = 'wordpress';
		}
	}
	private function setDB()
	{
		switch ($this->cms) {
			case 'joomla':
				$this->db = oseJoomla::getDBO ();
			break;
			case 'wordpress':
				$this->db = oseWordpress::getDBO ();
			break;
		}
	}
	public function getEmailList() {
		oseWordpress :: loadRequest();
		$limit = oRequest :: getInt('limit', 25);
		$start = oRequest :: getInt('start', 0);
		$page = oRequest :: getInt('page', 1);
		$search = oRequest :: getVar('search', null);
		$start = $limit * ($page -1);
		return $this->convertEmail($this->getEmailListDB ($search, $start, $limit));
	}
	private function getEmailListDB ($search, $start, $limit) {
		$query = " SELECT `id`, `subject` FROM `{$this->table}` " 
				." WHERE `app` = " . $this->db->quoteValue($this->app)
				." LIMIT ". $start. ", ". $limit;
		$this->db->setQuery($query);
		return $this->db->loadObjectList();
	}
	private function convertEmail ($results) {
		$i = 0;
		foreach ($results as $result) {
			$results[$i]->view = $this->getViewIcon ($results[$i]->id);
			$i++;
		}
		return $results;
	}
	private function getViewIcon ($id) {
		return "<a href='#' onClick= 'viewEmailDetail(".urlencode($id).", url, option, controller, \"getEmail\")' ><div class='ose-grid-info'></div></a>";
	}
	public function getEmailListTotal() {
		$query = " SELECT COUNT(`id`) as `count` FROM `{$this->table}` WHERE `app` = " . $this->db->quoteValue($this->app);
		$this->db->setQuery($query);
		$result = (object)($this->db->loadResult()); 
		return $result->count;
	}
	public function getAdminEmailList() {
		oseFramework :: loadRequest();
		$limit = oRequest :: getInt('limit', 25);
		$start = oRequest :: getInt('start', 0);
		$page = oRequest :: getInt('page', 1);
		$search = oRequest :: getVar('search', null);
		$start = $limit * ($page -1);
		$list = $this->getAdminEmailListDB($search, $start, $limit);
		return $list;
	}
	public function getAdminEmailListDB($search, $start, $limit) {
		$where = array ();
		if (!empty ($search)) {
			$where[] = "`subject` LIKE " . $this->db->quoteValue('%' . $search . '%', true);
		}
		$where[] = "`app` = " . $this->db->quoteValue($this->app);
		$where = $this->db->implodeWhere($where);
		$query = " SELECT * FROM `{$this->view}`" .		$where;
		$this->db->setQuery($query);
		return $this->db->loadObjectList();
	}
	public function getEmailParams($id) {
		$result = $this->getEmailParamsDB($id);
		$params = oseJSON :: decode($result);
		$return = array ();
		$i = 0;
		foreach ($params as $key => $value) {
			$return[$i]['key'] = $key;
			$return[$i]['value'] = $value;
			$i++;
		}
		return $return;
	}
	private function getEmailParamsDB($id) {
		$where = array ();
		$where[] = " `id` = " . (int) $id;
		$where = $this->db->implodeWhere($where);
		$query = " SELECT `params` FROM `#__ose_app_email` " . $where;
		$this->db->setQuery($query);
		$result = $this->db->loadResult();
		return (!empty ($result['params'])) ? $result['params'] : false;
	}
	public function getEmail($id) {
		$query = " SELECT `id`, `subject` as emailSubject, `body` as emailBody, `type` as emailType  FROM `{$this->table}` WHERE `id` = " . (int) $id;
		$this->db->setQuery($query);
		$item = $this->db->loadObject();
		return $item;
	}
	public function saveemail($id, $emailType, $emailBody, $emailSubject) {
		$varValues = array (
			'type' => $emailType,
			'subject' => $emailSubject,
			'body' => $emailBody
		);
		$id = $this->db->addData('update', '#__ose_app_email', 'id', (int) $id, $varValues);
		if ($id == true) {
			return $id;
		} else {
			return false;
		}
	}
	public function addemail($emailType, $emailBody, $emailSubject) {
		$varValues = array (
				'id' => 'DEFAULT',
				'app' => $this->app,
				'type' => $emailType,
				'subject' => $emailSubject,
				'body' => $emailBody
		);
		$id = $this->db->addData('insert', '#__ose_app_email', '', '', $varValues);
		if (!empty($id)) {
			return $id;
		} else {
			return false;
		}
	}
	public function addadminemailmap($userid, $emailid) {
		$admin_id = $this->addadminid($userid);
		$varValues = array (
			'admin_id' => (int) $admin_id,
			'email_id' => (int) $emailid
		);
		$id = $this->db->addData('insert', '#__ose_app_adminrecemail', '', '', $varValues);
		return $id;
	}
	private function addadminid($userid) {
		$varValues = array (
			'id' => 'DEFAULT',
			'user_id' => (int) $userid
		);
		$id = $this->db->addData('insert', '#__ose_app_admin', '', '', $varValues);
		return $id;
	}
	public function deleteadminemailmap($admin_id, $email_id) {
		return $this->db->deleteRecord(array (
			'admin_id' => $admin_id,
			'email_id' => $email_id
		), '#__ose_app_adminrecemail');
	}
	public function getEmailByType($type) {
		$query = " SELECT `id`, `subject` as emailSubject, `body` as emailBody, `type` as emailType  FROM `{$this->table}` WHERE `type` = " . $this->db->quoteValue($type, true) . " LIMIT 1";
		$this->db->setQuery($query);
		$item = $this->db->loadObject();
		return $item;
	}
	public function sendMail($email, $config_var) {
		$receiptients = $this->getReceiptients($email->id);
		require_once (OSE_FRAMEWORKDIR .DS . 'oseframework'. DS . 'emails' . DS . 'oseEmailHelper.php');
		require_once (OSE_FRAMEWORKDIR .DS . 'oseframework'. DS . 'emails' . DS . 'phpmailer' . DS . 'phpmailer.php');
		require_once (OSE_FRAMEWORKDIR .DS . 'oseframework'. DS . 'emails' . DS . 'phpmailer' . DS . 'smtp.php');
		if (empty ($receiptients)) {
			return false;
		}
		foreach ($receiptients as $receiptient) {
			$email->body = str_replace('[user]', $receiptient->name, $email->body);
			$mailer = new PHPMailer();
			$mailer->From = $config_var->mailfrom;
			$mailer->FromName = $config_var->fromname;
			if ($config_var->mailer == 'smtp') {
				$mailer->useSMTP($config_var->smtpauth, $config_var->smtphost, $config_var->smtpuser, $config_var->smtppass, $config_var->smtpsecure, $config_var->smtpport);
			}
			$this->addRecipient($mailer, $receiptient->email);
			$mailer->Subject = OSEMailHelper :: cleanLine($email->subject);
			$mailer->Body = OSEMailHelper :: cleanText($email->body);
			$mailer->IsHTML(true);
			$mailer->Send();
		}
		return true;
	}
	private function addRecipient(& $mailer, $recipient) {
		// If the recipient is an array, add each recipient... otherwise just add the one
		if (is_array($recipient)) {
			foreach ($recipient as $to) {
				$to = OSEMailHelper :: cleanLine($to);
				$mailer->AddAddress($to);
			}
		} else {
			$recipient = OSEMailHelper :: cleanLine($recipient);
			$mailer->AddAddress($recipient);
		}
	}
	private function getReceiptients($emailid) {
		$query = "SELECT `name`, `email` FROM `#__ose_app_adminemailmap` where `email_id` = " . (int) $emailid;
		$this->db->setQuery($query);
		$items = $this->db->loadObjectList();
		return $items;
	}
}