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
require_once('LoginModel.php');
class SubscriptionModel extends LoginModel {
	public function loadLocalScript() {
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraDashboard', 'subscription.js', false);
	}
	public function getCHeader() {
		return oLang :: _get('SUBSCRIPTION_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('SUBSCRIPTION_DESC');
	}
	public function getSubscriptions() {
		$panel = new panel ();
		return $panel->getSubscriptions();
	}
	public function linkSubscription($profileID) {
		$panel = new panel ();
		return $panel->linkSubscription($profileID);
	}
	public function updateProfileID($profileID, $profileStatus) {
		$this->saveConfiguration('panel', array('profileID'=>$profileID, 'profileStatus'=>$profileStatus));
	}
	public function logout () {
		$panel = new panel ();
		return $panel->logout();
	}
	public function activateCode ($code) {
		$panel = new panel ();
		return $panel->activateCode($code);
	}
	public function getAllSubscriptionPlansHTML () {
		$plans = $this->getAllSubscriptionPlans();
		$html = '<select id="subscriptionPlan" name="subscriptionPlan" class="form-control">'; 
		foreach ($plans as $plan) {
			$html .= '<option value="'.$plan->product_id.'">'.$plan->title.' at $'.$plan->price.'</option>'; 
		}
		$html .= '</select>';
		return $html;
	}
	protected function getAllSubscriptionPlans () {
		$titles = $this->getPlanTitles();
		$type= $this->getProductType ();
		$planInfo = $this->getAllSubscriptionPrices ($type);
		$return = array (); 
		for ($i=0; $i<4; $i++) {
			$return[$i] = new stdClass();
			$return[$i]->title = $titles[$i];
			$return[$i]->price = $planInfo["price"][$i];
			$return[$i]->product_id = $planInfo["produceIds"][$i];
		}
		return $return;
	}
	protected function getPlanTitles () {
		$titles[]='Monthly Subscription';
		$titles[]='Quaterly Subscription';
		$titles[]='Semi-Annual Subscription';
		$titles[]='Annual Subscription';
		return $titles; 
	}
	protected function getAllSubscriptionPrices ($type) {
		if ($type != 'st') {
			$price[] = 5.88;
			$price[] = 13.88;
			$price[] = 18.88;
			$price[] = 28.88;
			$productIds [] = 51;
			$productIds [] = 52;
			$productIds [] = 53;
			$productIds [] = 50;
		}
		else
		{
			$price[] = 29.99;
			$price[] = 68.88;
			$price[] = 128.88;
			$price[] = 168.88;
			$productIds [] = 59;
			$productIds [] = 60;
			$productIds [] = 61;
			$productIds [] = 54;
		}
		return array("price"=>$price, "produceIds"=>$productIds);
	}
	public function addOrder($subscriptionPlan, $payment_method, $country_id, $firstname, $lastname) {
		$panel = new panel ();
		$trackingCode = $this->getTrackingCode();
		return $panel->addOrder($subscriptionPlan, $payment_method, $country_id, $firstname, $lastname, $trackingCode);
	}

    public function goSubscribeUrl()
    {
        $config = $this->getConfiguration('panel');
        $trackingCode = (!empty($config['data']['trackingCode'])) ? $config['data']['trackingCode'] : null;
        if (class_exists('SConfig')) {
            $product = 'st';
            $redirect = 'http://www.centrora.com/store/centrora-subscriptions/suite-annual';
        } else {
            $product = 'pl';
            $redirect = 'http://www.centrora.com/store/centrora-subscriptions';
        }
        if (!empty($trackingCode)) {
            $redirect += '?tracking=' + $trackingCode;
        }
        echo $redirect;
    }
	public function getPaymentAddress () {
		$panel = new panel ();
		$return= $panel->getPaymentAddress();
		return $return;
	}
	public function getTrackingCode() {
		$config = $this->getConfiguration('panel');
		return (!empty($config['data']['trackingCode']))?$config['data']['trackingCode']:null;
	}
}