<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$this->model->getNounce ();
$confArray = $this->model->getConfiguration('advscan');
if ($status == true)
{
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<div class="content-inner">
	<div class="row ">
                        <div class="col-lg-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain toggle panelClose panelRefresh">
                                <!-- Start .panel -->
                                <div class="panel-heading white-bg">
                                    <h4 class="panel-title"><?php oLang::_('O_ADVANCED_RULE'); ?></h4>
                                </div>
                                <div class="panel-controls">
                                </div>
                                <div class="panel-controls-buttons">
                                    <button class="btn btn-success btn-sm mr5 mb10" type="button"
                                            onClick="downloadRequest('ath')"><?php oLang::_('O_UPDATE_SIGNATURE'); ?></button>
										<button data-target="#configModal" data-toggle="modal" class="btn btn-success btn-sm mr5 mb10" type="button" ><?php oLang::_('CONFIGURATION');?></button>
							    </div>
                                <div class="panel-body">
                                    <table class="table display" id="AdvrulesetsTable">
                                        <thead>
                                            <tr>
												<th><?php oLang::_('O_ID'); ?></th>
												<th><?php oLang::_('O_RULE'); ?></th>
												<th><?php oLang::_('O_ATTACKTYPE'); ?></th>
												<th><?php oLang::_('O_IMPACT'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
												<th><?php oLang::_('O_ID'); ?></th>
												<th><?php oLang::_('O_RULE'); ?></th>
												<th><?php oLang::_('O_ATTACKTYPE'); ?></th>
												<th><?php oLang::_('O_IMPACT'); ?></th>
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
										<label for="adRules" class="col-sm-8 control-label"><?php oLang::_('O_ADRULESETS');?></label>
										<div class="col-sm-4">
												<label class="radio-inline">
				                                     <input type="radio" name="adRules" value="1" <?php echo (!empty($confArray['data']['adRules']) && $confArray['data']['adRules']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="adRules" value="0" <?php echo (empty($confArray['data']['adRules']))?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="silentMode" class="col-sm-8 control-label"><?php oLang::_('O_SILENTLY_FILTER_ATTACK');?></label>
										<div class="col-sm-4">
												<label class="radio-inline">
				                                     <input type="radio" name="silentMode" value="1" <?php echo (!empty($confArray['data']['silentMode']) && $confArray['data']['silentMode']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="silentMode" value="0" <?php echo (empty($confArray['data']['silentMode']))?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="threshold" class="col-sm-8 control-label"><?php oLang::_('ATTACK_BLOCKING_THRESHOLD');?></label>
										<div class="col-sm-4">
				                               <input type="text" name="threshold" value="<?php echo (empty($confArray['data']['threshold']))?35:$confArray['data']['threshold']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="slient_max_att" class="col-sm-8 control-label"><?php oLang::_('SILENT_MODE_BLOCK_MAX_ATTEMPTS');?></label>
										<div class="col-sm-4">
				                               <input type="text" name="slient_max_att" value="<?php echo (empty($confArray['data']['slient_max_att']))?10:$confArray['data']['slient_max_att']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="blockCountry" class="col-sm-8 control-label"><?php oLang::_('COUNTRYBLOCK');?></label>
										<div class="col-sm-4">
												<label class="radio-inline">
				                                     <input type="radio" name="blockCountry" value="1" <?php echo (!empty($confArray['data']['blockCountry']) && $confArray['data']['blockCountry']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="blockCountry" value="0" <?php echo (empty($confArray['data']['blockCountry']))?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
										<input type="hidden" name="option" value="com_ose_firewall">
									 	<input type="hidden" name="controller" value="scanconfig"> 
									    <input type="hidden" name="action" value="saveConfigScan">
									    <input type="hidden" name="task" value="saveConfigScan">
									    <input type="hidden" name="type" value="advscan"> 
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
	?>
	<div class="row">
		<div class="panel panel-primary">
			<?php 
				$image = OSE_FWURL.'/public/images/screenshot-5.png';
				include_once dirname(__FILE__).'/calltoaction.php';
			?>
		</div>
	</div>
  </div>
</div>
<?php 
	$this->model->showFooterJs();
}
?>