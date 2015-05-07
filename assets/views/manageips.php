<?php
oseFirewall::checkDBReady ();
$this->model->getNounce ();
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<!-- Import Form Modal -->
                <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_IMPORT_IP_CSV'); ?></h4>
                            </div>
                            <div class="modal-body">
                              <form id = 'import-ip-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">                            
                                	<div class="col-lg-9 col-md-9">
                                     	<input id="csvfile" type="file" name="csvfile" >
                                    </div>
                                	<div class="col-lg-3 col-md-3">
                                     	<button type="submit" class="btn btn-primary btn-sm" id='import-ip-button'><?php oLang::_('O_IMPORT_NOW');?></button>
                                	</div>
                                	<input type="hidden" name="option" value="com_ose_firewall">
                                	<input type="hidden" name="controller" value="manageips"> 
								    <input type="hidden" name="action" value="importcsv">
								    <input type="hidden" name="task" value="importcsv">
                              </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->
	
	
	<!-- Export Form Modal -->
                <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_EXPORT_IP_CSV'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class="col-lg-8 col-md-7">
                                    <?php $this->model->exportcsv(); ?>
                                	</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->
	
	<!-- Export Form Modal -->
                <div class="modal fade" id="addIPModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_IPS'); ?></h4>
                            </div>
                            <div class="modal-body">
                              	<p class="mb15">
									<?php oLang::_('IPFORM_DESC'); ?>
								</p>
								<form id = 'add-ip-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="title" class="col-sm-2 control-label"><?php oLang::_('O_IP_RULE');?></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="title" name="title" placeholder="<?php oLang::_('O_IP_RULE');?>">
										</div>
									</div>
									<div class="form-group">
										<label for="ip_type" class="col-sm-2 control-label"><?php oLang::_('O_IP_TYPE');?></label>
										<div class="col-sm-10">
				                                <label class="radio-inline">
                                                    <input id="single_ip" type="radio" name="ip_type" value="ip"
                                                           onchange="changeView()"
                                                           checked="checked"><?php oLang::_('O_SINGLE_IP'); ?>
				                                </label>
				                                <label class="radio-inline">
                                                    <input id="range_ip" type="radio" name="ip_type"
                                                           onchange="changeView()"
                                                           value="ips"><?php oLang::_('O_RANGE'); ?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="ip_start" class="col-sm-2 control-label"><?php oLang::_('O_START_IP');?></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="ip_start" name="ip_start">
										</div>
									</div>
                                    <div id="hidden_ip_end" class="form-group" style="display: none">
                                        <label for="ip_end"
                                               class="col-sm-2 control-label"><?php oLang::_('O_END_IP'); ?></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="ip_end" name="ip_end">
										</div>
									</div>
									<div class="form-group">
										<label for="ip_status" class="col-sm-2 control-label"><?php oLang::_('O_IP_TYPE');?></label>
										<div class="col-sm-10">
				                                <label class="radio-inline">
				                                     <input type="radio" name="ip_status" value="1" checked="checked"><?php oLang::_('O_STATUS_BLACKLIST_DESC');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="ip_status" value="2" ><?php oLang::_('O_STATUS_MONITORED_DESC');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="ip_status" value="3" ><?php oLang::_('O_STATUS_WHITELIST_DESC');?>
				                                </label>            
										</div>
									</div>
									 	<input type="hidden" name="option" value="com_ose_firewall"> 
									 	<input type="hidden" name="controller" value="manageips"> 
									    <input type="hidden" name="action" value="addips">
									    <input type="hidden" name="task" value="addips">
				    				<div class="form-group">
										<div class="col-sm-offset-10">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('SAVE');?></button>
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
                                    <h4 class="panel-title">IP Table</h4>
                                    
                                </div>
                                <div class="panel-controls"></div>
                                <div class="panel-controls-buttons">
                                	<button data-target="#addIPModal" data-toggle="modal" class="btn btn-success btn-sm mr5 mb10" type="button"><?php oLang::_('ADD_IPS'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('blacklistIP')"><?php oLang::_('O_BLACKLIST_IP'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('whitelistIP')"><?php oLang::_('O_WHITELIST_IP'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('monitorIP')"><?php oLang::_('O_MONITORLIST_IP'); ?></button>
                                	<button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="removeItems()"><?php oLang::_('O_DELETE_ITEMS'); ?></button>
                                	<button class="btn btn-yellow btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('updateHost')"><?php oLang::_('O_UPDATE_HOST'); ?></button>
                                	<button data-target="#importModal" data-toggle="modal" class="btn btn-primary btn-sm mr5 mb10"><?php oLang::_('O_IMPORT_IP_CSV'); ?></button>
                                	<button data-target="#exportModal" data-toggle="modal" class="btn btn-primary btn-sm mr5 mb10"><?php oLang::_('O_EXPORT_IP_CSV'); ?></button>
                                	<button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="removeAllItems()"><?php oLang::_('O_DELETE__ALLITEMS'); ?></button>
                                </div>
                                <div class="panel-body">
                                    <table class="table display" id="manageIPsTable">
                                        <thead>
                                            <tr>
                                                <th></th>	
												<th><?php oLang::_('O_ID'); ?></th>
												<th><?php oLang::_('O_DATE'); ?></th>
												<th><?php oLang::_('O_IP_RULE_TITLE'); ?></th>
												<th><?php oLang::_('O_RISK_SCORE'); ?></th>
												<th><?php oLang::_('O_START_IP'); ?></th>
                                                <th><?php oLang::_('O_VARIABLE'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th><?php oLang::_('O_VISITS'); ?></th>
												<th><?php oLang::_('O_VIEWDETAIL'); ?></th>
                                                <th><input type="checkbox" name="checkedAll" id="checkedAll"></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
												<th><?php oLang::_('O_ID'); ?></th>
												<th><?php oLang::_('O_DATE'); ?></th>
												<th><?php oLang::_('O_IP_RULE_TITLE'); ?></th>
												<th><?php oLang::_('O_RISK_SCORE'); ?></th>
												<th><?php oLang::_('O_START_IP'); ?></th>
                                                <th><?php oLang::_('O_VARIABLE'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th><?php oLang::_('O_VISITS'); ?></th>
												<th><?php oLang::_('O_VIEWDETAIL'); ?></th>
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
