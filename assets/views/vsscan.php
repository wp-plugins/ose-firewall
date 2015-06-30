<?php 
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$confArray = $this->model->getConfiguration('vsscan');
$this->model->getNounce ();
if (isset($confArray['data']['vsScanExt']) && !isset($confArray['data']['file_ext']))
{
	$confArray['data']['file_ext'] = $confArray['data']['vsScanExt'];
}
if ($status == true)
{	
?>
<!-- Configuration Form Modal -->
                <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('CONFIGURATION'); ?></h4>
                            </div>
                            <div class="modal-body">
                              <form id = 'configuraton-form' class="form-horizontal group-border stripped" role="form" method="POST">                            
                                	<div class="form-group">
										<label for="file_ext" class="col-sm-4 control-label"><?php oLang::_('O_SCANNED_FILE_EXTENSIONS');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="file_ext" value="<?php echo (isset($confArray['data']['file_ext']) && empty($confArray['data']['file_ext']))?'htm,html,shtm,shtml,css,js,php,php3,php4,php5,inc,phtml,jpg,jpeg,gif,png,bmp,c,sh,pl,perl,cgi,txt':$confArray['data']['file_ext']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="maxfilesize" class="col-sm-4 control-label"><?php oLang::_('MAX_FILE_SIZE');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="maxfilesize" value="<?php echo (isset($confArray['data']['maxfilesize']) && empty($confArray['data']['maxfilesize']))?'3':$confArray['data']['maxfilesize']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="maxdbconn" class="col-sm-4 control-label"><?php oLang::_('MAX_DB_CONN');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="maxdbconn" value="<?php echo (isset($confArray['data']['maxdbconn']) && empty($confArray['data']['maxdbconn']))?'3':$confArray['data']['maxdbconn']?>" class="form-control">
										</div>
									</div>
									<input type="hidden" name="option" value="com_ose_firewall"> 
                                	<input type="hidden" name="controller" value="avconfig"> 
								    <input type="hidden" name="action" value="saveConfAV">
								    <input type="hidden" name="task" value="saveConfAV">
								    <input type="hidden" name="type" value="vsscan">
								    <div class="form-group">
									<div class="col-sm-offset-10">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('SAVE');?></button>
									</div>
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
	<!-- /.modal -->
	
<div id = "oseappcontainer" >
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary plain toggle panelMove panelClose panelRefresh">
                                <!-- Start .panel -->
                                <div class="panel-heading">
                                    <h4 class="panel-title">Virus Scanning Status</h4>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                  	<div class="pie-charts">
                                        <div class="easy-pie-chart" data-percent="0" id='easy-pie-chart-1'><span id='pie-1'>0%</span></div>
                                        <div class="label">
                                        		<?php oLang::_('O_SHELL_CODES'); ?>
                                        		<div id ="shell-result">
                                        		</div>
                                        </div>
                                    </div>
                                    <div class="pie-charts red-pie">
                                        <div class="easy-pie-chart-red" data-percent="0" id='easy-pie-chart-2'><span id='pie-2'>0%</span></div>
                                        <div class="label"><?php oLang::_('O_BASE64_CODES'); ?></div>
                                    </div>
                                    <div class="pie-charts green-pie">
                                        <div class="easy-pie-chart-green" data-percent="0" id='easy-pie-chart-3'><span id='pie-3'>0%</span></div>
                                        <div class="label"><?php oLang::_('O_JS_INJECTION_CODES'); ?></div>
                                    </div>
                                    <div class="pie-charts blue-pie">
                                        <div class="easy-pie-chart-blue" data-percent="0" id='easy-pie-chart-4'><span id='pie-4'>0%</span></div>
                                        <div class="label"><?php oLang::_('O_PHP_INJECTION_CODES'); ?></div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="pie-charts teal-pie">
                                        <div class="easy-pie-chart-teal" data-percent="0" id='easy-pie-chart-5'><span id='pie-5'>0%</span></div>
                                        <div class="label"><?php oLang::_('O_IFRAME_INJECTION_CODES'); ?></div>
                                    </div>
                                    <div class="pie-charts purple-pie">
                                        <div class="easy-pie-chart-purple" data-percent="0" id='easy-pie-chart-6'><span id='pie-6'>0%</span></div>
                                        <div class="label"><?php oLang::_('O_SPAMMING_MAILER_CODES'); ?></div>
                                    </div>
                                    <div class="pie-charts orange-pie">
                                        <div class="easy-pie-chart-orange" data-percent="0" id='easy-pie-chart-7'><span id='pie-7'>0%</span></div>
                                        <div class="label"><?php oLang::_('O_EXEC_MAILICIOUS_CODES'); ?></div>
                                    </div>
                                    <div class="pie-charts lime-pie">
                                        <div class="easy-pie-chart-lime" data-percent="0" id='easy-pie-chart-8'><span id='pie-8'>0%</span></div>
                                        <div class="label"><?php oLang::_('O_OTHER_MAILICIOUS_CODES'); ?></div>
                                    </div>
                                   </div>
                                    
                                    <div class="row">
	                                    <div class="col-md-12">
											<div class="col-lg-6 col-md-12 sortable-layout">
									                            <!-- col-lg-6 start here -->
									                            <div class="panel panel-default plain toggle panelMove panelClose panelRefresh">
									                                <!-- Start .panel -->
									                                <div class="panel-heading">
									                                    <h4 class="panel-title">CPU Load</h4>
									                                </div>
									                                <div class="panel-body">
									                                    <div id="line-chart-cpu" style="width: 100%; height:250px;"></div>
									                                </div>
									                            </div>
									                            <!-- End .panel -->
											</div>
											<div class="col-lg-6 col-md-12 sortable-layout">
									                            <!-- col-lg-6 start here -->
									                            <div class="panel panel-default plain toggle panelMove panelClose panelRefresh">
									                                <!-- Start .panel -->
									                                <div class="panel-heading">
									                                    <h4 class="panel-title">Memory Usage</h4>
									                                </div>
									                                <div class="panel-body">
									                                    <div id="line-chart-memory" style="width: 100%; height:250px;"></div>
									                                </div>
									                            </div>
									                            <!-- End .panel -->
											</div>		
										</div>
                                    </div>
                                </div>
                          </div>
		</div>
		<div id = "scan-window" class="col-md-12"> 
		
		<div class="panel panel-primary plain">
              <!-- Start .panel -->
              <div class="panel-heading">
              </div>
              <div class="panel-controls-buttons">
              <button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="downloadRequest('bsavs')"><?php oLang::_('O_UPDATE_VIRUS_SIGNATURE'); ?></button>
              <button data-target="#configModal" data-toggle="modal" class="btn btn-success btn-sm mr5 mb10" type="button" ><?php oLang::_('CONFIGURATION');?></button>
			  </div>
              <div class="panel-body">
                <div class="row">
                	<div id='scan_progress' class="alert alert-info fade in">
                    	  <i class="im-info alert-icon s24"></i>
                          <strong>Status: </strong> <span id="p4text"></span>
                          <div id='last_file' class='col-md-12'>&nbsp;</div>
                    </div>
                </div>
			    <div class="row">
		        	<div id = "scanbuttons" >
						<button data-target="#scanModal" data-toggle="modal" id="customscan" class='btn btn-primary mr5 mb10'><?php oLang::_('SCAN_SPECIFIC_FOLDER') ?></button>	
						<button id="vsstop" class='btn btn-primary mr5 mb10'><?php oLang::_('STOP_VIRUSSCAN') ?></button>
					    <button id="vscont" class='btn btn-primary mr5 mb10'><?php oLang::_('O_CONTINUE_SCAN') ?></button>
					    <button id="vsscan" class='btn btn-primary mr5 mb10'><?php oLang::_('START_NEW_VIRUSSCAN') ?></button>
					    <button id="vsscanSing" class='btn btn-primary mr5 mb10'><?php oLang::_('START_NEW_SING_VIRUSSCAN') ?></button>
		            </div>
			    </div>
			 </div>    
		  </div>    
	    </div>	
 	</div>
 </div>
</div>
<div id='fb-root'></div>
<?php 
//\PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>
<?php 
include_once(dirname(__FILE__).'/scanpath.php');
}
else {
?>
<div id = "oseappcontainer" >
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
	?>
	<div class="row">
		<?php 
				$image = OSE_FWURL.'/public/images/screenshot-6.png';
				include_once dirname(__FILE__).'/calltoaction.php';
			?>
	</div>
  </div>
</div>
<?php 
	$this->model->showFooterJs();
}
?>