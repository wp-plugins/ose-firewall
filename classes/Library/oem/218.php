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

class CentroraOEM218 {
	public function __construct ($customer_id) {
		$this->customer_id = $customer_id;
		$this->setLanguage();
	}
	public function setLanguage () {
		
	}
	public function showOEMName () {
        return '<div class="vendorname"> <!--WEBandWIRE <span>PagePROTECT</span>--> </div>';
	}
	public function getTopBarURL () {
		$urls = '<li><a href="https://webandwire.de/" title="My Account"><i class="glyphicon glyphicon-user"></i> <span class="hidden-xs hidden-sm hidden-md">My Account</span> </a></li>
				 <li><a href="https://webandwire.de/index.php/helpdesk" id="support-center" title="Support"><i class="glyphicon glyphicon-cd"></i> <span class="hidden-xs hidden-sm hidden-md">Help Desk</span></a></li>
				 <li><a href="https://webandwire.de/" title="Malware Removal"><i class="glyphicon glyphicon-screenshot"></i> <span class="hidden-xs hidden-sm hidden-md">Malware Removal</span></a></li>';
		return $urls;
	}
	public function addLogo () {
		return '<div class="logo"><img src="'.rtrim(OSE_FWPUBLICURL, '/').'/css/oem/'.$this->customer_id.'/imgs/logo-header.png" width="350px" alt ="ME Security"/></div>'.$this->showOEMName ();
	}
	public function defineVendorName () {
		if (!(defined('OSE_WORDPRESS_FIREWALL'))) define('OSE_WORDPRESS_FIREWALL', 'WEBandWIRE PagePROTECT');
        if (!(defined('OSE_WORDPRESS_FIREWALL_SHORT'))) define('OSE_WORDPRESS_FIREWALL_SHORT', 'WEBandWIRE');
        if (!(defined('OSE_OEM_URL_MAIN'))) define('OSE_OEM_URL_MAIN', 'http://webandwire.de/');
        if (!(defined('OSE_OEM_URL_HELPDESK'))) define('OSE_OEM_URL_HELPDESK', 'http://webandwire.de/');
        if (!(defined('OSE_OEM_URL_MALWARE_REMOVAL'))) define('OSE_OEM_URL_MALWARE_REMOVAL', 'http://webandwire.de/');
        if (!(defined('OSE_OEM_URL_ADVFW_TUT'))) define('OSE_OEM_URL_ADVFW_TUT', 'http://webandwire.de/');
        if (!(defined('OSE_OEM_URL_PREMIUM_TUT'))) define('OSE_OEM_URL_PREMIUM_TUT', 'http://webandwire.de');
        if (!(defined('OSE_OEM_URL_AFFILIATE'))) define('OSE_OEM_URL_AFFILIATE', 'http://webandwire.de');
        if (!(defined('OSE_OEM_URL_SUBSCRIBE'))) define('OSE_OEM_URL_SUBSCRIBE', 'http://webandwire.de');
        if (!(defined('OSE_OEM_LANG_TAG'))) define('OSE_OEM_LANG_TAG','de_DE');
	}
	public function requiresPasscode () {
		$config = oseFirewall::getConfiguration('oem'); 
		if (!empty($config) && !empty($config['data'])) {
			return $config['data']['passcode_status'];
		} 
		else 
		{
			return true;
		}
	}
    public function showNews (){
        return false;
    }
    public function showFooter () {
    	return '<div class="footer-bottom">
		    <div class="container">
		      <p class="pull-center">
		        PagePROTECT is a portfolio of WEBandWIRE Internet- und EDV-Dienstleistungen. &copy;  <?php echo date("Y"); ?> <a
					href="https://webandwire.de/" target="_blank">WEBandWIRE Internet- und EDV-Dienstleistungen</a>. All Rights Reserved. <br /> Credits
				to: <a href="http://www.centrora.com" target="_blank">Centrora Security!&#0174;</a>
		      </p>
		    </div>
		  </div>';
    }
    public function getHomeLink() {
    	return '<li><a href="http://www.webandwire.de" title="Home">Quick links:&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-home"></i> <span class="hidden-xs hidden-sm hidden-md">'.OSE_WORDPRESS_FIREWALL_SHORT.'</span> </a></li>';
    }
}