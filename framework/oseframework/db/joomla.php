<?php
/**
* @version     2.0 +
* @package       Open Source Excellence Security Suite
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
require_once (dirname(__FILE__).DS.'oseDB2.php'); 

class oseDB2Joomla extends oseDB2 {
	public function __construct () {
		$this->dbo = $this-> getConnection ();
		$this->setPrefix (); 
	}
	protected function setPrefix () {
		$config = JFactory::getConfig();
		$this -> prefix = $config->get('dbprefix');
	}	
	public function getConnection ()
	{
		$config = JFactory::getConfig();
		$host = explode(':', $config->get('host')); 
		if (!empty($host[1]))
		{
			$connection=new CDbConnection('mysql:host='.$host[0].';port='.$host[1].';dbname='.$config->get('db'),$config->get('user'),$config->get('password'));
		}
		else
		{
			$connection=new CDbConnection('mysql:host='.$host[0].';dbname='.$config->get('db'),$config->get('user'),$config->get('password'));
		}
		return $connection;
	}
}