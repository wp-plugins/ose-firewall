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
class CentroraOEM {
	private $customer_id = 0;
	private $newInstance = null;
	public function __construct () {
		$config = self::getConfiguration('oem');
		if (!empty($config['data']['customer_id'])) {
			require_once (dirname(__FILE__).ODS.$config['data']['customer_id'].'.php') ;
			$className = 'CentroraOEM'.$config['data']['customer_id'];
			$this->customer_id = $config['data']['customer_id'];
			$this->newInstance =  new $className($this->customer_id);
		}
		else {
			if (self::isOEMDataExists() )
			{
				$customer_id = self::getCustomerIDFromFile ();
				if (!empty($customer_id)) {
					require_once (dirname(__FILE__).ODS.$customer_id.'.php') ;
					$className = 'CentroraOEM'.$customer_id;
					$this->customer_id = $customer_id;
					$this->newInstance =  new $className($this->customer_id);
				}
			}
		}
	}
	private static function isOEMDataExists() {
		if (defined('JPATH_ADMINISTRATOR')) {
			return class_exists('SConfig') && file_exists(JPATH_ADMINISTRATOR.'/templates/oem.data');
		}
		else 
		{
			return false;
		}
	}
	private static function getCustomerIDFromFile (){
		if (defined('JPATH_ADMINISTRATOR')) {
			return file_get_contents(JPATH_ADMINISTRATOR.'/templates/oem.data');
		}
		else {
			return false;
		}
	}
	public function getTopBarURL () {
		if (empty($this->newInstance))
		{
			$urls = '<li><a href="//www.centrora.com/store/index.php?route=affiliate/login" title="Affiliate"><i class="glyphicon glyphicon-magnet"></i> <span class="hidden-xs hidden-sm hidden-md">Affiliate</span> </a></li>
						<li><a href="https://www.centrora.com/store/index.php?route=account/login" title="My Account"><i class="glyphicon glyphicon-user"></i> <span class="hidden-xs hidden-sm hidden-md">My Account</span> </a></li>
						<li><a href="https://www.centrora.com/support-center/" id="support-center" title="Support"><i class="glyphicon glyphicon-cd"></i> <span class="hidden-xs hidden-sm hidden-md">Support</span></a></li>
						<li><a href="http://www.centrora.com/" title="Subscription"><i class="glyphicon glyphicon-share-alt"></i> <span class="hidden-xs hidden-sm hidden-md">Subscription</span></a></li>
						<li><a href="http://www.centrora.com/tutorial/" title="Tutorial"><i class="glyphicon glyphicon-book"></i> <span class="hidden-xs hidden-sm hidden-md">Tutorial</span></a></li>
						<li><a href="http://www.centrora.com/cleaning" title="Malware Removal"><i class="glyphicon glyphicon-screenshot"></i> <span class="hidden-xs hidden-sm hidden-md">Malware Removal</span></a></li>';
			return $urls;
		}
		else
		{
			return $this->newInstance->getTopBarURL();
		}
	}
	public function addLogo () {
		return $this->newInstance->addLogo();
	}
	public function showOEMName () { 
		if (!empty($this->newInstance))
		{
			return $this->newInstance-> showOEMName () ;
		}
        return false;
	}
	public static function hasOEMCustomer () {
		$config = self::getConfiguration('oem');
		if (!empty($config['data']['customer_id'])) {
            return $config;
		}
		else if (self::isOEMDataExists() )
		{
			$config['data']['customer_id'] = self::getCustomerIDFromFile ();
			return $config;
		}		
        return false;
	}
	public function requiresPasscode () {
		if (!empty($this->newInstance)) {
			return $this->newInstance->requiresPasscode () ;
		}
		else {
			return false;
		}
	}
    public function showNews (){
        if (!empty($this->newInstance)) {
            return $this->newInstance->showNews () ;
        }
        else {
            return true;
        }
    }
	protected static function loadFirewallStat () {
		if (OSE_CMS == 'joomla')
		{
			oseFirewall::callLibClass('firewallstat', 'firewallstatJoomla');
		}
		else
		{
			oseFirewall::callLibClass('firewallstat', 'firewallstatWordpress');
		}
	}
	public static function getConfiguration($type)
	{
		self::loadFirewallStat () ;
		$oseFirewallStat = new oseFirewallStat();
		$results = $oseFirewallStat->getConfiguration($type);
		return $results;
	}
	public function loadCSS () {
		$config = $this->getConfiguration('oem');
		if (!empty($config['data']['customer_id'])) {
			oseFirewall::loadCSSFile ('OEMCss', 'oem/'.$config['data']['customer_id'].'/custom.css', false);
		}
		else if (self::isOEMDataExists() )
		{
			$customer_id = self::getCustomerIDFromFile ();
			oseFirewall::loadCSSFile ('OEMCss', 'oem/'.$customer_id.'/custom.css', false);
		}
	}

    public function loadJS()
    {
        $config = $this->getConfiguration('oem');
        if (!empty($config['data']['customer_id'])) {
            oseFirewall::loadJSFile('oemJS', 'oem/' . $config['data']['customer_id'] . '/custom.js', false);
        }
        else if (self::isOEMDataExists() )
		{
			$customer_id = self::getCustomerIDFromFile ();
        	oseFirewall::loadCSSFile ('OEMCss', 'oem/'.$customer_id.'/custom.js', false);
        }
    }
	public static function showProducts () {
		$products = self::getProducts();
		$i = 0; 
		foreach ($products as $product) {
			if ($i % 6 == 0 )
			{
				echo '<div class="row product-list">';
			}
				echo '<div class="product-title">'.$product['title'].'</div>';
			
			if ($i % 6 == 5 )
			{
				echo '</div>';
			}
			$i++;
		}
	}
	public static function getProducts () {
		$products = array (); 
		$products[]= array ("url"=>"http://gabemedia.dk/", "title"=>"Malware cleaning");
		$products[]= array ("url"=>"http://gabemedia.dk/", "title"=>"Malware cleaning");
		$products[]= array ("url"=>"http://gabemedia.dk/", "title"=>"Malware cleaning");
		$products[]= array ("url"=>"http://gabemedia.dk/", "title"=>"Malware cleaning");
		$products[]= array ("url"=>"http://gabemedia.dk/", "title"=>"Malware cleaning");
		$products[]= array ("url"=>"http://gabemedia.dk/", "title"=>"Malware cleaning");
		return $products;
	}
	public function defineVendorName () {
		if (!empty($this->newInstance)) {
			$this->newInstance->defineVendorName();
		}
		else {
			if (!(defined('OSE_WORDPRESS_FIREWALL'))) define('OSE_WORDPRESS_FIREWALL', 'Centrora Security™');
            if (!(defined('OSE_WORDPRESS_FIREWALL_SHORT'))) define('OSE_WORDPRESS_FIREWALL_SHORT', 'Centrora');
            if (!(defined('OSE_OEM_URL_MAIN'))) define('OSE_OEM_URL_MAIN', 'https://www.centrora.com/');
            if (!(defined('OSE_OEM_URL_HELPDESK'))) define('OSE_OEM_URL_HELPDESK', 'https://www.centrora.com/support/');
            if (!(defined('OSE_OEM_URL_MALWARE_REMOVAL'))) define('OSE_OEM_URL_MALWARE_REMOVAL', 'https://www.centrora.com/malware-removal/');
            if (!(defined('OSE_OEM_URL_ADVFW_TUT'))) define('OSE_OEM_URL_ADVFW_TUT', 'http://www.centrora.com/centrora-joomla-component-tutorial/firewall-settings-3/');
            if (!(defined('OSE_OEM_URL_PREMIUM_TUT'))) define('OSE_OEM_URL_PREMIUM_TUT', 'https://www.centrora.com/store/activating-premium-service');
            if (!(defined('OSE_OEM_URL_AFFILIATE'))) define('OSE_OEM_URL_AFFILIATE', 'http://www.centrora.com/affiliate-partners/');
            if (!(defined('OSE_OEM_URL_SUBSCRIBE'))) define('OSE_OEM_URL_SUBSCRIBE', 'http://www.centrora.com/store/centrora-subscriptions');
            if (!(defined('OSE_OEM_LANG_TAG'))) define('OSE_OEM_LANG_TAG','');
		}
	}
	public function getFavicon () {
		if (!empty($this->newInstance)) {
			return trim(OSE_FWPUBLICURL,'/').'/css/oem/'.$this->customer_id.'/imgs/favicon.ico';
		}
		else {
			return OSE_FWURL.'/public/images/favicon.ico';
		}
	}
	public function getHomeLink() {
		if (!empty($this->newInstance)) {
			return $this->newInstance->getHomeLink();
		}
		else {
			return '<li><a href="http://www.centrora.com" title="Home">Quick links:&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-home"></i> <span class="hidden-xs hidden-sm hidden-md">'.OSE_WORDPRESS_FIREWALL.'</span> </a></li>';
		}
	}
}
