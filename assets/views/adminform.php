<!-- Form Modal -->
                <div class="modal fade" id="adminFormModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('CHANGE_ADMINFORM'); ?></h4>
                            </div>
                            <div class="modal-body">
								<form id = 'admin-configuraton-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="pageTitle" class="col-sm-6 control-label"><?php oLang::_('CHANGE_ADMINFORM');?></label>
										<div class="col-sm-6">
				                               <input type="text" name="username" value="" class="form-control">
										</div>
									</div>
										<input type="hidden" name="option" value="com_ose_firewall"> 
										<input type="hidden" name="controller" value="audit"> 
										<input type="hidden" name="action" value="changeusername">
										<input type="hidden" name="task" value="changeusername">
									<div class="form-group">
										<div class="col-sm-offset-10">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('CHANGE');?></button>
										</div>
									</div>
								</form>
                              </div>
                        </div>
                    </div>
                </div>
                
	<!-- /.modal -->