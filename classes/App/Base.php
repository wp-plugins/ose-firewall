<?php
namespace App;
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Centrora Security Firewall
 * @subpackage    Open Source Excellence WordPress Firewall
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 01-Jun-2013
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
 die('Direct Access Not Allowed');
}
/**
 * Base controller
 *
 * @property-read \App\Pixie $pixie Pixie dependency container
 */
class Base extends \PHPixie\Controller {
	protected $view;
	protected $model;
	public function __construct($pixie) {
		$this->getModel();
		$this->pixie = $pixie;
		$this->response = $pixie->response ();
	}
	public function before() {
		$this->view = $this->pixie->view ( 'main' );
		$this->getModelforView ();
	}
	public function after() {
		$this->response->body = $this->view->render ();
		$this->response->send_body ();
	}
	public function getModel() {
		$modelName = str_replace ( 'Controller', 'Model', get_class ( $this ) );
		$modelName = str_replace ( 'App\Model\\', '', $modelName );
		require_once(OSE_FWMODEL.ODS.$modelName.'.php');
		$this->model = new $modelName ();
	}
	public function getModelforView() {
		$this->view->model = $this->model;
	}
	public function action_index() {
		$controller = str_replace ( 'App\\Controller\\', '', get_class ( $this ) );
		$controller = str_replace ( 'Controller', '', $controller );
		$this->view->subview = strtolower($controller);
	}
    public function action_check()
    {
//        require_once(OSE_FWMODEL.ODS.'BaseModel.php');
//        $basemodel = new BaseModel();
        $result = $this->model->checkJoomlaSession();
        print_r($result);
        exit;
    }
}