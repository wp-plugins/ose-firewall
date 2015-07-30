<?php
oseFirewall::checkDBReady();
$this->model->getNounce();
$this->model->migrate();
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
                        <div class="panel-controls"></div>
                        <div class="panel-heading white-bg"></div>
                        <div class="panel-body">
                            <ul class="nav nav-tabs" data-tabs="tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#extview">
                                        <?php oLang::_('FILE_EXTENSION_LIST'); ?>
                                        <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                                           data-content="<?php oLang::_('O_ALLOWED_FILE_TYPES_HELP');?>"></i>
                                    </a>
                                </li>
                                <li><a data-toggle="tab" href="#uploadlog"><?php oLang::_('FILE_EXTENSION_LOG'); ?></a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- file extension view-->
                                <div class="panel-heading white-bg"></div>
                                <div class="tab-pane active" id="extview">
                                    <div class="panel-controls-buttons">
                                        <button class="btn btn-sm mr5 mb10" type="button" onClick="addExt()">
                                            <i class="text-primary glyphicon glyphicon-plus-sign"></i> <?php oLang::_('ADD_EXT'); ?>
                                        </button>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table display" id="extensionListTable">
                                            <thead>
                                            <tr>
                                                <th><?php oLang::_('O_EXTENSION_ID'); ?></th>
                                                <th><?php oLang::_('O_EXTENSION_NAME'); ?></th>
                                                <th><?php oLang::_('O_EXTENSION_TYPE'); ?></th>
                                                <th><?php oLang::_('O_EXTENSION_STATUS'); ?></th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th><?php oLang::_('O_EXTENSION_ID'); ?></th>
                                                <th><?php oLang::_('O_EXTENSION_NAME'); ?></th>
                                                <th><?php oLang::_('O_EXTENSION_TYPE'); ?></th>
                                                <th><?php oLang::_('O_EXTENSION_STATUS'); ?></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <!-- file extension log-->
                                <div class="tab-pane" id="uploadlog">
                                    <?php
                                    $status = oseFirewall::checkSubscriptionStatus(false);
                                    if ($status == true) {
                                        ?>
                                        <div class="panel-controls-buttons">

                                        </div>
                                        <div class="panel-body">
                                            <table class="table display" id="uploadLogTable">
                                                <thead>
                                                <tr>
                                                    <th><?php oLang::_('O_ID'); ?></th>
                                                    <th><?php oLang::_('O_START_IP'); ?></th>
                                                    <th><?php oLang::_('O_FILENAME'); ?></th>
                                                    <th><?php oLang::_('O_FILETYPE'); ?></th>
                                                    <th><?php oLang::_('O_IP_STATUS'); ?></th>
<!--                                                    <th>--><?php //oLang::_('O_VSSCAN_STATUS'); ?><!--</th>-->
                                                    <th><?php oLang::_('O_DATE'); ?></th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th><?php oLang::_('O_ID'); ?></th>
                                                    <th><?php oLang::_('O_START_IP'); ?></th>
                                                    <th><?php oLang::_('O_FILENAME'); ?></th>
                                                    <th><?php oLang::_('O_FILETYPE'); ?></th>
                                                    <th><?php oLang::_('O_IP_STATUS'); ?></th>
<!--                                                    <th>--><?php //oLang::_('O_VSSCAN_STATUS'); ?><!--</th>-->
                                                    <th><?php oLang::_('O_DATE'); ?></th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    <?php
                                    } else {
                                        ?>
                                        <div id="oseappcontainer">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="panel panel-primary">
                                                        <?php
                                                        //@todo change image
                                                        $image = OSE_FWURL . '/public/images/premium/adfirewallrules.png';
                                                        include_once dirname(__FILE__) . '/calltoaction.php';
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- End .panel -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.modal -->
                <div class="modal fade" id="addExtModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_EXT'); ?></h4>
                            </div>
                            <form id='addext-form' class="form-horizontal group-border stripped" role="form">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label
                                            class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_NAME'); ?></label>

                                        <div class="col-sm-8">
                                            <input type="text" name="ext-name" value="" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ext-type"
                                               class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_TYPE'); ?></label>

                                        <div class="col-sm-8">
                                            <select class="form-control" name='ext-type' id='ext-type'>
                                                <?php print_r($this->model->getExtType()) ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ext-status"
                                               class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_STATUS'); ?></label>

                                        <div class="col-sm-8">
                                            <select class="form-control" name='ext-status' id='ext-status'>
                                                <option value='1'>Allowed</option>
                                                <option value='2'>Forbidden</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="option" value="com_ose_firewall">
                                    <input type="hidden" name="controller" value="upload">
                                    <input type="hidden" name="action" value="saveExt">
                                    <input type="hidden" name="task" value="saveExt">
                                </div>
                                <div class="modal-footer">
                                    <label id="ext-warning-label" class="col-sm-6 control-label" style="display: none"><i
                                            id="ext-warning-message" class="fa fa-exclamation-triangle"></i></label>

                                    <div id="buttonDiv">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm"><i
                                                    class="text-primary glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>