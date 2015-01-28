<!-- Form Modal -->
                <div class="modal fade" id="accountFormModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('CREATE_AN_ACCOUNT'); ?></h4>
                            </div>
                            <div class="modal-body">
								<form id = 'new-account-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('FIRSTNAME');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="firstname" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('LASTNAME');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="lastname" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('EMAIL');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="email" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('PASSWORD');?></label>
										<div class="col-sm-8">
				                               <input type="password" name="password" id="password" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('PASSWORD_CONFIRM');?></label>
										<div class="col-sm-8">
				                               <input type="password" name="password2" id="password2" value="" class="form-control">
										</div>
									</div>
										<input type="hidden" name="option" value="com_ose_firewall"> 
										<input type="hidden" name="controller" value="login"> 
										<input type="hidden" name="action" value="createaccount">
										<input type="hidden" name="task" value="createaccount">
										<?php echo $this->model->getToken();?>
									<div class="form-group">
										<div class="col-sm-offset-10">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('CREATE');?></button>
										</div>
									</div>
								</form>
                              </div>
                        </div>
                    </div>
                </div>
                
	<!-- /.modal -->