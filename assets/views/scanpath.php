<!-- Form Modal -->
                <div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('PATH'); ?></h4>
                            </div>
                            <div class="modal-body">
								<form id = 'scan-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="scanPath" class="col-sm-2 control-label"><?php oLang::_('PATH');?></label>
										<div class="col-sm-10">
				                               <input type="text" name="scanPath" value="" class="form-control">
										</div>
									</div>
										<input type="hidden" name="option" value="com_ose_firewall">
									 	<input type="hidden" name="controller" value="vsscan"> 
									    <input type="hidden" name="action" value="vsscan">
									    <input type="hidden" name="task" value="vsscan">
									    <input type="hidden" name="step" value="-3">
									<div class="form-group">
										<div class="col-sm-offset-10 ">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('SCAN');?></button>
										</div>
									</div>
								</form>
                              </div>
                        </div>
                    </div>
                </div>
                
	<!-- /.modal -->