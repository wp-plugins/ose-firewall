<?php
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
require_once(OSE_FWRECONTROLLERS.ODS.'BaseRemoteController.php');
require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
class VariablesRemoteController extends BaseRemoteController
{
	public $layout = '//layouts/grids';
	public function actionGetVariables() {
		oseFirewall::loadRequest ();
		if (isset($_REQUEST['mobiledevice']))
		{
			$mobiledevice = oRequest :: getInt('mobiledevice', 0);
		}
		else
		{
			$mobiledevice = 0;
		}
		$results = $this ->model->getVariables();
		oseAjax::returnJSON($results, $mobiledevice);   		 
	}
	
	public function actionChangeVarStatus()
	{
		oseFirewall::loadRequest (); 
		$id= oRequest ::getInt('id', null);
		$status= oRequest ::getInt('status', null);
		if (empty($id) || (!in_array($status, array(1,2,3))))
		{
			return; 
		}
		$result = $this ->model -> changeVarStatus(array($id), $status);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable rule has been changed successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(true, 'ERROR', oLang::_get("The Variable rule was changed unsuccessfully."), false);
		}
	}

	public function actionAddvariables()
	{
		oseFirewall::loadRequest (); 
		$requesttype= oRequest :: getVar('requesttype',null);
		$variable= oRequest :: getVar('variablefield',null);
		$status= oRequest :: getInt('statusfield',null);
		if (empty($variable) || (!in_array($status, array(1,2,3))))
		{
			return; 
		}
		$variable = $requesttype.'.'.$variable;
		$result = $this ->model -> addvariables($variable, $status);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable rule has been changed successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable rule was changed unsuccessfully."), false);
		}
	}
	
	public function actionDeletevariable()
	{
		oseFirewall::loadRequest (); 
		$ids= oRequest :: getVar('ids',null);
		$ids = oseJSON :: decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this ->model -> deletevariable($ids);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been changed successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was changed unsuccessfully."), false);
		}
	}
	
	public function actionLoadWordpressrules()
	{
		$result = $this ->model -> loadWordpressrules();
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	
	public function actionLoadJoomlarules()
	{
		$result = $this ->model -> loadJoomlarules();
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	
	
	
	public function actionRefreshStat(){
		oseFirewall::loadRequest ();
		$return = array();
		$return['results'] = $this ->model->getStatistics();
		oseAjax::returnJSON($return);
	}
	
	public function actionLoadJSocialrules()
	{
		$result = $this ->model -> loadJSocialrules();
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	
	public function actionClearvariables()
	{
		$result = $this ->model -> clearvariables();
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	
	public function actionBlacklistVar()
	{
		oseFirewall::loadRequest (); 
		$ids= oRequest :: getVar('ids',null);
		$ids = oseJSON :: decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this ->model -> blacklistvariables($ids);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	
	public function actionFilterVar()
	{
		oseFirewall::loadRequest (); 
		$ids= oRequest :: getVar('ids',null);
		$ids = oseJSON :: decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this ->model -> filtervariables($ids);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	
	public function actionWhitelistVar()
	{
		oseFirewall::loadRequest (); 
		$ids= oRequest :: getVar('ids',null);
		$ids = oseJSON :: decode($ids);
		if (empty($ids))
		{
			return; 
		}
		$result = $this ->model -> whitelistvariables($ids);
		if ($result==true)
		{
				oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get("The Variable has been restored successfully."), true);
		} 
		else
		{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get("The Variable was restored unsuccessfully."), false);
		}
	}
	

	


}
?>