<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$this->model->getNounce ();
$confArray = $this->model->getConfiguration('country');
$status = true;
if ($status == true)
{
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<!-- Add Variable Form Modal -->
                <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('DOWNLOAD_COUNTRY'); ?></h4>
                            </div>
                            <div class="modal-body">
                              <form id = 'download-geoip-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">                            
                                   	<div class="form-group">
                                           <div class="col-sm-12">
                                                <div id = "progressbar" class="progress-circular-blue" data-dimension="100" data-text="0%" data-width="12" data-percent="0"></div>
                                                <div class="download-message" id='message-box'>Ready</div>
                                           </div>
                                    </div>
                                    <div class="form-group">
                                           <label class="col-sm-9 control-label" for="textfield"></label>
                                           <div class="col-sm-3">
                                               <button type="submit" class="btn btn-primary" id='add-variable-button'><?php oLang::_('DOWNLOAD_NOW');?></button>
                                           </div>
                                    </div>
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->
	<div class="content-inner">
	<div class="row ">
                        <div class="col-lg-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain toggle panelClose panelRefresh">
                                <!-- Start .panel -->
                                <div class="panel-heading white-bg">
                                    <h4 class="panel-title">Country Table</h4>
                                </div>
                                <div class="panel-controls"></div>
                                <div class="panel-controls-buttons">
                                	<button data-target="#formModal" data-toggle="modal" class="btn btn-primary btn-sm mr5 mb10"><?php oLang::_('DOWNLOAD_COUNTRY'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('blacklistCountry')"><?php oLang::_('O_BLACKLIST_COUNTRY'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('monitorCountry')"><?php oLang::_('O_MONITOR_COUNTRY'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('whitelistCountry')"><?php oLang::_('O_WHITELIST_COUNTRY'); ?></button>
                                	<button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="removeAllItems()"><?php oLang::_('O_DELETE__ALLITEMS'); ?></button>
                                </div>
                                <div class="panel-body">
                                    <table class="table display" id="countryTable">
                                        <thead>
                                            <tr>
												<th><?php oLang::_('O_ID'); ?></th>
												<th></th>
												<th><?php oLang::_('O_COUNTRY'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th><input type="checkbox" name="checkedAll" id="checkedAll"></input></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
												<th><?php oLang::_('O_ID'); ?></th>
												<th></th>
												<th><?php oLang::_('O_COUNTRY'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
                                            	<th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- End .panel -->
                        </div>
	   </div>
	   </div>
	</div>
</div>

<!-- Form Modal -->
                <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('SCAN_CONFIGURATION_TITLE'); ?></h4>
                            </div>
                            <div class="modal-body">
								<form id = 'configuraton-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="blockCountry" class="col-sm-4 control-label"><?php oLang::_('O_COUNTRY_BLOCKING');?></label>
										<div class="col-sm-8">
												<label class="radio-inline">
				                                     <input type="radio" name="blockCountry" value="1" <?php echo (isset($confArray['data']['blockCountry']) && $confArray['data']['blockCountry']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="blockCountry" value="0" <?php echo (isset($confArray['data']['blockCountry']) && $confArray['data']['blockCountry']==false)?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
										<input type="hidden" name="option" value="com_ose_firewall">
									 	<input type="hidden" name="controller" value="scanconfig"> 
									    <input type="hidden" name="action" value="saveConfigScan">
									    <input type="hidden" name="task" value="saveConfigScan">
									    <input type="hidden" name="type" value="country"> 
									<div class="form-group">
										<div class="col-sm-offset-10 ">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('SAVE');?></button>
										</div>
									</div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
                
	<!-- /.modal -->
<?php
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
				$image = OSE_FWURL.'/public/images/screenshot-8.png';
				include_once dirname(__FILE__).'/calltoaction.php';
			?>
	</div>
  </div>
</div>
<?php 
	$this->model->showFooterJs();
}
?>