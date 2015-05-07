<!-- Form Modal -->
                <div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('SCANPATH'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <label style="vertical-align: top;"><?php oLang::_('FILETREENAVIGATOR'); ?></label>
                                <div class="panel-body" id="FileTreeDisplay"></div>
                            </div>
                            <div class="modal-footer">
                                <div class="panel-body">
                                    <form id = 'scan-form' class="form-horizontal group-border stripped" role="form">
                                        <div class="form-group">
                                            <label for="scanPath" class="col-sm-1 control-label"><?php oLang::_('PATH');?></label>
                                            <div class="col-sm-11">
                                                <input type="text" name="scanPath" id="selected_file" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <input type="hidden" name="option" value="com_ose_firewall">
                                        <input type="hidden" name="controller" value="vsscan">
                                        <input type="hidden" name="action" value="vsscan">
                                        <input type="hidden" name="task" value="vsscan">
                                        <input type="hidden" name="step" value="-3">
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-primary" id='save-button'><?php oLang::_('SCAN_SPECIFIC_FOLDER');?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
	<!-- /.modal -->