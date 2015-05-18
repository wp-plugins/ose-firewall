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
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
	die('Direct Access Not Allowed');
}
class SubscriptionController extends LoginController {
	public function action_getSubscription () {
		if (isset($_REQUEST['mobiledevice']))
		{
			$mobiledevice = $this->model->getInt('mobiledevice', 0);
		}
		else
		{
			$mobiledevice = 0;
		}
		$results = $this ->model->getSubscriptions();
		$this->model->returnJSON($results, $mobiledevice);   
	}
	public function action_LinkSubscription() {
		$this->model->loadRequest();
		$profileID = $this->model->getVar('profileID', null);
		$results = $this->model->linkSubscription($profileID);
	}
	public function action_getToken () {
		$this->model->getToken();
	}
	public function action_updateProfileID () {
		$profileID = $this->model->getVar('profileID', null);
		$profileStatus = $this->model->getVar('profileStatus', null);
		if (!empty($profileID))
		{
			$this->model->updateProfileID($profileID, $profileStatus);
		}
	}
	public function action_logout () {
		$result = $this ->model->logout();
		$return = array ();
		$return['success'] = $result;
        $return['status'] = $this->model->getLang("SUCCESS");
        $return['message'] = $this->model->getLang("SUCCESS_LOGOUT");
		$this->model->returnJSON($return, false);
	}
	public function action_activateCode() {
		$code = $this->model->getVar('code', null);
		if (!empty($code))
		{
			$return = $this->model->activateCode($code);
		}
		else
		{
			$return = array ();
			$return['success'] = true;
            $return['status'] = $this->model->getLang("ERROR");
            $return['message'] = $this->model->getLang("ACTIVATION_CODE_EMPTY");
		}
		$this->model->returnJSON($return, false);
	}
	public function action_addOrder () {
		$subscriptionPlan = $this->model->getInt('subscriptionPlan', null);
		$payment_method = $this->model->getVar('payment_method', null);
		$country_id = $this->model->getVar('country_id', null);
		$firstname = $this->model->getVar('firstname', null);
		$lastname = $this->model->getVar('lastname', null);
		if (!empty($subscriptionPlan) && !empty($payment_method) && !empty($country_id))
		{
			$return = $this->model->addOrder($subscriptionPlan, $payment_method, $country_id, $firstname, $lastname);
		}
		else
		{
			$return = array ();
			$return['success'] = true;
            $return['status'] = $this->model->getLang("ERROR");
            $return['message'] = $this->model->getLang("SUBSCRIPTION_PLAN_EMPTY");
		}
		$this->model->returnJSON($return, false);
	}
	public function action_getPaymentAddress() {
		$address = $this->model->getPaymentAddress();
		print_r($address);exit;
	}
}