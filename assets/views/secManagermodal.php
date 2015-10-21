<div class="modal fade" id="addSecManagerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_SECURITY_MANAGER'); ?></h4>
            </div>
            <div class="modal-body">
                <form id='secManager-form' class="form-horizontal group-border stripped" role="form">
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php oLang::_('SECURITY_NAME'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" name="secManager-name" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php oLang::_('SECURITY_USERNAME'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" name="secManager-username" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php oLang::_('SECURITY_EMAIL'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" name="secManager-email" value="" class="form-control"
                                   pattern="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-zA-Z]{2,6}(?:\.[a-zA-Z]{2})?)$"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php oLang::_('SECURITY_PASSWORD'); ?></label>

                        <div class="col-sm-8">
                            <input type="password" name="secManager-password" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php oLang::_('SECURITY_PASSWORD2'); ?></label>

                        <div class="col-sm-8">
                            <input type="password" name="secManager-password2" value="" class="form-control" required>
                        </div>
                    </div>
                    <input type="hidden" name="option" value="com_ose_firewall"> <input type="hidden" name="controller"
                                                                                        value="adminemails"> <input
                        type="hidden" name="action" value="saveSecManager"> <input type="hidden"
                                                                                   name="task" value="saveSecManager">

            </div>
            <div class="modal-footer">
                <label id="sec-warning-label" class="col-sm-6 control-label" style="display: none"><i
                        id="sec-warning-message" class="fa fa-exclamation-triangle" style="display: none"></i></label>

                <div id="buttonDiv">
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm"><i
                                class="text-primary glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?></button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
