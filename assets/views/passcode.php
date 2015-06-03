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
                    <div class="panel panel-primary plain toggle panelClose panelRefresh">
                        <!-- Start .panel -->
                        <div class="panel-heading white-bg">
                            <h4 class="panel-title"><?php oLang::_('PASSCODE_CONTROL'); ?></h4>
                        </div>
                        <div class="panel-controls">

                        </div>
                        <form id='passcodeForm' role="form">
                            <label class="col-sm-12 control-label"><?php oLang::_('PASSCODE'); ?></label>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <input type="text" name="passcode">
                                </div>
                                <div class="col-sm-2">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success"><?php oLang::_('VERIFY'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="option" value="com_ose_firewall">
                            <input type="hidden" name="controller" value="passcode">
                            <input type="hidden" name="action" value="verify">
                            <input type="hidden" name="task" value="verify">
                        </form>

                    </div>
                    <!-- End .panel -->
                </div>
            </div>
        </div>
    </div>
</div>
