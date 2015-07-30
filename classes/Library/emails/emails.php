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

class oseFirewallemails
{

    public function __construct()
    {

    }

    public function sendemail($type, $content)
    {
        $config_var = $this->getConfigVars();
        oseFirewall::loadEmails();
        $oseEmail = new oseEmail('firewall');
        $email = $this->getEmailByType($type);
        $email = $this->convertEmail($email, $content);
        $webmaster = oseFirewall::getConfiguration('scan');
        if (OSE_CMS == 'wordpress') {
            $current_user = wp_get_current_user();
            $receiptient = array();
            $receiptient[0]->name = 'Administrator';
            $receiptient[0]->email = $webmaster['data']['adminEmail'];

            $receiptient[1]->name = $current_user->user_login;
            $receiptient[1]->email = $current_user->user_email;
        } else {
            $current_user = JFactory::getUser();
            $receiptient = array();
            $receiptient[0]->name = 'Administrator';
            $receiptient[0]->email = $webmaster['data']['adminEmail'];

            $receiptient[1]->name = $current_user->username;
            $receiptient[1]->email = $current_user->email;
        }

        $oseEmail->sendMailTo($email, $config_var, $receiptient);
        $oseEmail->closeDBO();
    }

    protected function getEmailByType($type)
    {
        $email = new stdClass();
        switch ($type) {
            case 'loginSlug':
                $email->subject = 'Centrora Security Alert For a Login Url change';
                break;
            case 'secureKey':
                $email->subject = 'Centrora Security Alert For a secure key change';
                break;
        }
        $emailTmp = oseFirewall::getConfiguration('emailTemp');
        if (empty($emailTmp['data']['emailTemplate'])) {
            $email->body = file_get_contents(dirname(__FILE__) . ODS . 'loginSlugEmail.tpl');
        } else {
            $email->body = stripslashes($emailTmp['data']['emailTemplate']);
        }
        return $email;
    }

    protected function convertEmail($email, $content)
    {
        $email->subject = $email->subject . " for [" . $_SERVER['HTTP_HOST'] . "]";
        $email->body = str_replace('{name}', 'Administrator', $email->body);
        $email->body = str_replace('{header}', $email->subject, $email->body);
        $email->body = str_replace('{content}', $content, $email->body);
        return $email;
    }

    protected function getConfigVars()
    {
        return oseFirewall::getConfigVars();
    }
}
