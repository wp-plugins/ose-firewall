<!-- Form Modal -->
                <div class="modal fade" id="phpconfigFormModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('PHP_CONFIGURATION'); ?></h4>
                            </div>
                            <div class="modal-body">
								<form id = 'php-configuraton-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<div class="col-sm-12">
                                            <div id="message-box"><?php oLang::_('PHP_CHECK_STATUS'); ?></div>
										</div>
									</div>
										<input type="hidden" name="token" value="">
										<input type="hidden" name="option" value="com_ose_firewall"> 
										<input type="hidden" name="controller" value="audit">
										<input type="hidden" name="action" value="getPHPConfig">
										<input type="hidden" name="task" value="getPHPConfig">
								</form>
                              </div>
                        </div>
                    </div>
                </div>
                
	<!-- /.modal -->