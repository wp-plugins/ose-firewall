<!-- Form Modal -->
                <div class="modal fade" id="affiliateFormModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_TRACKING_CODE'); ?></h4>
                            </div>
                            <div class="modal-body">
								<form id = 'affiliate-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('ADD_TRACKING_CODE');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="trackingCode" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-8"></div>
										<div class="col-sm-4" >
											<a class="btn btn-primary btn-xs fx-button mr5" href="http://www.centrora.com/store/affiliate-program#addTrackingCode" target="_blank">Tutorial</a>
										</div>
									</div>
										<input type="hidden" name="option" value="com_ose_firewall"> 
										<input type="hidden" name="controller" value="audit"> 
										<input type="hidden" name="action" value="saveTrackingCode">
										<input type="hidden" name="task" value="saveTrackingCode">
									<div class="form-group">
										<div class="col-sm-offset-10">
											<button type="submit" class="btn btn-default" ><?php oLang::_('SAVE');?></button>
										</div>
									</div>
								</form>
                              </div>
                        </div>
                    </div>
                </div>
                
	<!-- /.modal -->