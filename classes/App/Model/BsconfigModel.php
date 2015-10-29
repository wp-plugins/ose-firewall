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
require_once('BaseModel.php');
class BsconfigModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function getCHeader() {
        return oLang::_get('FIREWALL_CONFIGURATION');
	}
	public function getCDescription() {
        return oLang::_get('FIREWALL_CONFIGURATION_DESC');
	}
	public function loadLocalScript() {
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraSEOTinyMCE', 'plugins/tinymce/tinymce.min.js', false);
		oseFirewall::loadJSFile ('CentroraManageIPs', 'rulesets.js', false);
	}

    public function login_page_input()
    {
		if (OSE_CMS == 'wordpress') {
	        if (get_option('permalink_structure')) {
	
	            echo '<code>' . trailingslashit(home_url()) . '</code> <input id="loginSlug" type="text" name="loginSlug" value="' . $this->new_login_slug() . '">' . ($this->use_trailing_slashes() ? ' <code>/</code>' : '');
	
	        } else {
	
	            echo '<code>' . trailingslashit(home_url()) . '?</code> <input id="loginSlug" type="text" name="loginSlug" value="' . $this->new_login_slug() . '">';
	        }
		}
    }

    private function use_trailing_slashes()
    {

        return ('/' === substr(get_option('permalink_structure'), -1, 1));

    }

    private function new_login_slug()
    {
        $confArray = $this->getConfiguration('scan');
        if (!empty($confArray['data']['loginSlug'])) {
            return $confArray['data']['loginSlug'];
        }
        return;
    }

    public function backend_secure_key()
    {
        echo '<code>' . JURI:: root() . 'administrator/index.php?</code> <input id="secureKey" type="text" name="secureKey" value="' . $this->new_secure_key() . '">';
    }

    private function new_secure_key()
    {
        $confArray = $this->getConfiguration('scan');
        if (!empty($confArray['data']['secureKey'])) {
            return $confArray['data']['secureKey'];
        }
        return;
    }

    public function checktotp()
    {
        $query = "SELECT `enabled` From `#__extensions` WHERE `name` = 'plg_twofactorauth_totp';";
        $this->db->setQuery($query);
        $results = $this->db->loadObject();
        if (empty($results)) {
            $Array = array(
                'type' => 'plugin',
                'name' => 'plg_twofactorauth_totp',
                'enabled' => 0,
                'element' => 'totp',
                'folder' => 'twofactorauth',
                'client_id' => 0,
                'access' => 1,
                'protected' => 0,
                'manifest_cache' => '{"name":"plg_twofactorauth_totp","type":"plugin","creationDate":"August 2013","author":"Joomla! Project","copyright":"Copyright (C) 2005 - 2015 Open Source Matters. All rights reserved.","authorEmail":"admin@joomla.org","authorUrl":"www.joomla.org","version":"3.2.0","description":"PLG_TWOFACTORAUTH_TOTP_XML_DESCRIPTION","group":"","filename":"totp"}',
                'ordering' => 0,
                'state' => 0
            );
            $id = $this->db->addData('insert', '#__extensions', '', '', $Array);
            return 0;
        } else {
            return ($results == 1) ? true : false;
        }
    }
    public function getmaxFailures()
    {
        $confArray = $this->getConfiguration('bf');
        $limit = $confArray['data']['loginSec_maxFailures'];
        if (!empty($limit)) {
            $tub = '<option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="500">500</option>';
            $re = "/\"".$limit."\"/";
            $subst = "\"".$limit."\" selected";

            $result = preg_replace($re, $subst, $tub, 1);
            echo $result;

        } else {
            $tub = '<option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="20" selected >20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="500">500</option>';
            echo $tub;
        }
    }
    public function getTimeFrame()
    {
        $confArray = $this->getConfiguration('bf');
        $limit = $confArray['data']['loginSec_countFailMins'];
        if (!empty($limit)) {
            $tub = ' <option value="5" selected >5 minutes</option>
                   <option value="10">10 minutes</option>
                   <option value="30">30 minutes</option>
                   <option value="60">1 hour</option>
                   <option value="120">2 hours</option>
                   <option value="360">6 hours</option>
                   <option value="720">12 hours</option>
                   <option value="1440">1 day</option>';
            $re = "/\"".$limit."\"/";
            $subst = "\"".$limit."\" selected";

            $result = preg_replace($re, $subst, $tub, 1);
            echo $result;

        } else {
            $tub = '<option value="5" selected >5 minutes</option>
                   <option value="10">10 minutes</option>
                   <option value="30">30 minutes</option>
                   <option value="60">1 hour</option>
                   <option value="120">2 hours</option>
                   <option value="360">6 hours</option>
                   <option value="720">12 hours</option>
                   <option value="1440">1 day</option>';
            echo $tub;
        }
    }

    public function clear_blacklist_url()
    {
        $key = $this->getCronKey();
        if (empty($key)) {
            $key = $this->getRandomKey();
        }
        if (OSE_CMS == 'wordpress') {
            echo '<code>' . trailingslashit(home_url()) . 'index.php?clearIPKey=</code> <input id="clearCronKey" type="text" name="clearCronKey" readonly="readonly" value="' . $key . '">';
        } else {
            echo '<code>' . JURI:: root() . '/index.php?clearIPKey=</code> <input id="clearCronKey" type="text" name="clearCronKey" readonly="readonly" value="' . $key . '">';
        }
    }

    private function getRandomKey()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; // allowed characters in Base32
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }
        return $secret;
    }

    private function getCronKey()
    {
        $confArray = $this->getConfiguration('advscan');
        if (!empty($confArray['data']['clearCronKey'])) {
            return $confArray['data']['clearCronKey'];
        }
        return;
    }
}