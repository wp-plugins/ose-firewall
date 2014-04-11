<?
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once (OSE_FWRECONTROLLERS. ODS. 'BaseRemoteController.php');
class DashboardRemoteController extends BaseRemoteController {
	public function actionCreateTables() {
		$this ->model->actionCreateTables();
	}
}
?>	