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
$this->model->loadRequest();


$rubbish = oRequest::getVar('rubbish');
if (!empty($rubbish)) {
    echo "<script>window.close();</script>";
}
//session_destroy();
oseFirewall::callLibClass('oem', 'oem');
$oem = new CentroraOEM();
$oemCustomer = $oem->requiresPasscode();
if ($oemCustomer) {
    $flag = get_class($this->model);
    $strip = strtolower(str_replace('Model', '', $flag));

    $filter = $this->model->oempasscode();

    if ($filter == true) {
        $this->model->loadLocalscript();
        include(dirname(__FILE__) . '/' . $subview . '.php');
    } else {

        if ($flag == 'DashboardModel' || $flag == 'PasscodeModel') {
            $this->model->loadLocalscript();
            include(dirname(__FILE__) . '/' . $subview . '.php');
        } else {
            if (OSE_CMS == 'wordpress') {
                $_SESSION['previouspage'] = 'ose_fw_' . $strip;
                oseFirewall::passcode();
            } else {
                $wp_session = JFactory::getSession();
            }

        }
    }
} else {
    $this->model->loadLocalscript();
    include(dirname(__FILE__) . '/' . $subview . '.php');
    include_once(dirname(__FILE__).'/affiliateform.php');
};
?>