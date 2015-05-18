<!-- Form Modal -->
<div class="modal fade" id="addDomainModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_DOMAIN'); ?></h4>
            </div>
            <div class="modal-body">
                <form id='domains-form' class="form-horizontal group-border stripped" role="form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php oLang::_('ADD_DOMAIN'); ?></label>

                        <div class="col-sm-5">
                            <input title="For example, www.domain.com" type="text" name="domain-address" value=""
                                   class="form-control"
                                   pattern="^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$"
                                   required>
                        </div>
                    </div>
                    <input type="hidden" name="option" value="com_ose_firewall">
                    <input type="hidden" name="controller" value="adminemails">
                    <input type="hidden" name="action" value="saveDomain">
                    <input type="hidden" name="task" value="saveDomain">
            </div>
            <div class="modal-footer">
                <label id="domain-warning-label" class="col-sm-6 control-label" style="display: none"><i
                        id="domain-warning-message" class="fa fa-exclamation-triangle"></i></label>

                <div class="form-group">
                    <div id="buttonDiv">
                        <button type="submit" class="btn btn-default"><?php oLang::_('SAVE'); ?></button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- /.modal -->

<div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_ADMIN'); ?></h4>
            </div>
            <div class="modal-body">
                <form id='adminemails-form' class="form-horizontal group-border stripped" role="form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php oLang::_('ADD_ADMIN_NAME'); ?></label>

                        <div class="col-sm-5">
                            <input type="text" name="admin-name" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php oLang::_('ADD_ADMIN_EMAIL'); ?></label>

                        <div class="col-sm-5">
                            <input type="text" name="admin-email" value="" class="form-control"
                                   pattern="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-zA-Z]{2,6}(?:\.[a-zA-Z]{2})?)$"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status"
                               class="col-sm-3 control-label"><?php oLang::_('ADD_ADMIN_STATUS'); ?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name='admin-status' id='status'>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="website"
                               class="col-sm-3 control-label"><?php oLang::_('ADD_ADMIN_DOMAIN'); ?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name='admin-domain' id='admin-domain'>
                                <?php print_r($this->model->getDomain()) ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="option" value="com_ose_firewall">
                    <input type="hidden" name="controller" value="adminemails">
                    <input type="hidden" name="action" value="saveAdmin">
                    <input type="hidden" name="task" value="saveAdmin">

            </div>
            <div class="modal-footer">
                <label id="admin-warning-label" class="col-sm-6 control-label" style="display: none"><i
                        id="admin-warning-message" class="fa fa-exclamation-triangle"></i></label>

                <div id="buttonDiv">
                    <div class="form-group">
                        <button type='button' class='btn btn-primary'
                                onclick='addDomain()'><?php oLang::_('ADD_DOMAIN'); ?></button>
                        <button type="submit" class="btn btn-success"><?php oLang::_('SAVE'); ?></button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

