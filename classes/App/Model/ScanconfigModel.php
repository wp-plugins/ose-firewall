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
require_once('ConfigurationModel.php');
class ScanconfigModel extends ConfigurationModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function getCHeader() {
		return oLang :: _get('SCAN_CONFIGURATION_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('SCAN_CONFIGURATION_DESC');
	}
	public function loadLocalscript () {
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraScanConfig', 'scanconfig.js', false);
	}

    public function checkPassword()
    {
        $query = "SELECT `params` From `#__extensions` WHERE `name` = 'com_users';";
        $this->db->setQuery($query);
        $results = $this->db->loadObject();
        return $results->params;
    }

    public function savePassword($mpl, $pmi, $pms, $pucm)
    {
        $query = "SELECT `params` From `#__extensions` WHERE `name` = 'com_users';";
        $this->db->setQuery($query);
        $results = $this->db->loadObject();
        $results = json_decode($results->params);
        $results->minimum_length = $mpl;
        $results->minimum_integers = $pmi;
        $results->minimum_symbols = $pms;
        $results->minimum_uppercase = $pucm;
        $results = json_encode($results);
        $Array = array(
            'params' => $results
        );
        $id = $this->db->addData('update', '#__extensions', 'name', 'com_users', $Array);
        return $id;
    }

    public function getLoginUrl($url, $scheme = null)
    {

        if (get_option('permalink_structure')) {

            return $this->user_trailingslashit(home_url('/', $scheme) . $url);

        } else {

            return home_url('/', $scheme) . '?' . $url;

        }
    }

    private function user_trailingslashit($string)
    {

        return $this->use_trailing_slashes()
            ? trailingslashit($string)
            : untrailingslashit($string);

    }

    private function use_trailing_slashes()
    {
        return ('/' === substr(get_option('permalink_structure'), -1, 1));
    }

    public function sendEmail($type, $content)
    {
        oseFirewall::callLibClass('emails', 'emails');
        $emailManager = new oseFirewallemails ();
        $return = $emailManager->sendemail($type, $content);
        return $return;
    }

    public function updatetotp($status)
    {
        $Array = array(
            'enabled' => $status,
        );
        $id = $this->db->addData('update', '#__extensions', 'name', 'plg_twofactorauth_totp', $Array);
        return $id;
    }

}
