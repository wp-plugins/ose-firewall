<?php
oseFirewall::checkDBReady();
$this->model->getNounce();
?>
<div id="oseappcontainer">
    <div class="container">
        <?php
        $this->model->showLogo();
        $this->model->showHeader();
        ?>
        <div class="content-inner">
            <div class="row ">
                <div class="col-lg-12 sortable-layout">
                    <!-- col-lg-12 start here -->
                    <div class="panel panel-primary plain ">
                        <!-- Start .panel -->
                        <div class="panel-heading white-bg"></div>
                        <div class="panel-controls">

                        </div>
                        <div class="panel-controls-buttons"></div>
						<div class="row">
							<div class="col-bg-12">
								<form id='passcodeForm' role="form">
		                            <label class="col-sm-12 control-label"><?php oLang::_('PASSCODE'); ?></label>
		                           <div class="form-group">
		                                <div class="col-sm-6">
		                                    <input type="password" name="passcode">
		                                </div>
		                                <div class="col-sm-6">
		                                    <div>
		                                        <button type="submit"
		                                                class="btn btn-success"><?php oLang::_('VERIFY'); ?></button>
		                                        <button type="button" onclick="changePasscodeModal()"
                                                        class="btn btn-success"><?php oLang::_('CHANGE_PASSCODE'); ?></button>
		                                    </div>
		                                </div>
		                            </div>
		                            <input type="hidden" name="option" value="com_ose_firewall">
	                            <input type="hidden" name="controller" value="passcode">
	                            <input type="hidden" name="action" value="verify">
	                            <input type="hidden" name="task" value="verify">
	                        </form>
							</div>
						
						</div>
                        
                    </div>
                    <!-- End .panel -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Modal -->
<div class="modal fade" id="changePasscodeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('CHANGE_PASSCODE'); ?></h4>
            </div>
            <div class="modal-body">
                <form id='changePasscode-form' class="form-horizontal group-border stripped" role="form">
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><?php oLang::_('OLD_PASSCODE'); ?></label>

                        <div class="col-sm-5">
                            <input type="password" name="old-passcode" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><?php oLang::_('NEW_PASSCODE'); ?></label>

                        <div class="col-sm-5">
                            <input type="password" name="new-passcode" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><?php oLang::_('CONFIRM_PASSCODE'); ?></label>

                        <div class="col-sm-5">
                            <input type="password" name="confirm-passcode" value="" class="form-control" required>
                        </div>
                    </div>
            </div>
            <input type="hidden" name="option" value="com_ose_firewall">
            <input type="hidden" name="controller" value="passcode">
            <input type="hidden" name="action" value="changePasscode">
            <input type="hidden" name="task" value="changePasscode">
        </div>
        <div class="modal-footer">
            <div class="form-group">
                <div id="buttonDiv">
                    <button type="submit" class="btn btn-success"><?php oLang::_('SAVE'); ?></button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
</div>

<!-- /.modal -->

