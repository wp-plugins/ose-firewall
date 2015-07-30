<?php
namespace App\Controller;
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

class PasscodeController extends \App\Base
{
    public function action_verify()
    {
        $this->model->loadRequest();
        $passcode = $this->model->getVar('passcode', null);
        $oemNum = $this->model->getConfiguration('oem');
        if (!empty ($oemNum['data']['oem_passcode'])) {
            $oemPass = $oemNum['data']['oem_passcode'];
        } else {
            $defaultNum = $oemNum['data']['customer_id'];

            $this->model->saveConfigurationNoExit('oem', array('oem_passcode' => base64_encode($defaultNum), 'passcode_status' => 1));
            $oemPass = base64_encode($defaultNum);
        }
        if ($passcode == base64_decode($oemPass)) {

            $_SESSION['passcode'] = $passcode;
            $results = array(
                'page' => (OSE_CMS=='joomla')?'index.php?option=com_ose_firewall':'admin.php?page=ose_fw_vsscan',
                'status' => true
            );
            $this->model->returnJSON($results);
        } else {
            $results = array(
                'status' => false
            );
            $this->model->returnJSON($results);
        }
    }

    public function action_changePasscode()
    {
        $this->model->loadRequest();
        $old_passcode = $this->model->getVar('old-passcode', null);
        $new_passcode = $this->model->getVar('new-passcode', null);
        $confirm_passcode = $this->model->getVar('confirm-passcode', null);
        $oemNum = $this->model->getConfiguration('oem');
        if (!empty ($oemNum['data']['oem_passcode'])) {
            $old_pass = $oemNum['data']['oem_passcode'];
        } else {
            $defaultNum = $oemNum['data']['customer_id'];
            $old_pass = base64_encode($defaultNum);
        }
        if (empty($old_passcode) || empty($new_passcode) || empty($confirm_passcode)) {
            $error = "Please fill out all the form";
            $this->model->returnJSON($error);
        } elseif ($new_passcode != $confirm_passcode) {
            $error = "New passcode are not identical, try again";
            $this->model->returnJSON($error);
        } elseif ($old_passcode != base64_decode($old_pass)) {
            $error = "Wrong old passcode";
            $this->model->returnJSON($error);
        } else {
            $this->model->saveConfigurationNoExit('oem', array('oem_passcode' => base64_encode($new_passcode)));
            $result['result'] = "Passcode updated successfully";
            $result['status'] = "SUCCESS";
            $this->model->returnJSON($result);
        }
    }
}



