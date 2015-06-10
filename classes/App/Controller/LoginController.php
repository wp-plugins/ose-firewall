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
class LoginController extends \App\Base {
	public function action_Validate () {
		$this->model->loadRequest();
		$website= $this->model->getVar('website', null);
		$email= $this->model->getVar('email', null);
		$password= $this->model->getVar('password', null);
        $pattern = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-zA-Z]{2,6}(?:\.[a-zA-Z]{2})?)$/";
        $token = $this->getToken();
		if (empty($website) || empty($email) || empty($password))
		{
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("PLEASE_ENTER_REQUIRED_INFO"), false);
		} elseif (preg_match($pattern, $email)) {
            $result = $this->model->validate($website, $email, $password, $token);
            print_r($result);
            exit;
        }
		{
            $this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("PLEASE_ENTER_CORRECT_EMAIL"), false);
        }
	}
	public function action_Createaccount () {
		$this->model->loadRequest();
		$firstname= $this->model->getVar('firstname', null);
		$lastname= $this->model->getVar('lastname', null);
		$email= $this->model->getVar('email', null);
		$password= $this->model->getVar('password', null);
		$password2= $this->model->getVar('password2', null);
        $pattern = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-zA-Z]{2,6}(?:\.[a-zA-Z]{2})?)$/";

        $token = $this->getToken();
		if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($password2))
		{
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("PLEASE_ENTER_REQUIRED_INFO"), false);
		}
		else if ($password != $password2) {
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("PASSWORD_DONOT_MATCH"), false);
		} elseif (preg_match($pattern, $email)) {
            $result = $this->model->createAccount($firstname, $lastname, $email, $password, $token);
            print_r($result);
            exit;
        }
		else
		{
            $this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("PLEASE_ENTER_CORRECT_EMAIL"), false);
        }
	}
	protected function getToken() {
		$token = array();
		foreach ($_POST as $key=>$value)
		{
			if (strlen($key) == 16)
			$token[$key]=$value;
		}
		return $token;
	}
	public function action_updateKey () {
		$this->model->loadRequest();
		$key= $this->model->getVar('key', null);
		$verified= $this->model->getVar('verified', 0);
		$result = $this->model->updateKey($key, $verified);
		print_r($result);exit;
	}
	public function action_verifyKey () {
		$this->model->loadRequest();

        $result = $this->model->verifyKey();
		print_r($result);exit;
	}
	public function action_getNumbOfWebsite() {
		$this->model->loadRequest();
		$result = $this->model->getNumbOfWebsite();
		print_r($result);exit;
	}

    public function action_addOEM()
    {
        $this->model->loadRequest();
        $oem = $this->model->getVar('oem', null);
        $result = $this->model->addOEM($oem);
        print_r($result);
        exit;
    }
}