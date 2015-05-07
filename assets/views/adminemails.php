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
                                <h4 class="panel-title">Administrator Management</h4>
                            </div>
                            <div class="panel-controls">
                            </div>
                            <div class="panel-controls-buttons">
                                <button class="btn btn-success btn-sm mr5 mb10" type="button"
                                        onClick="addAdmin()"><?php oLang::_('ADD_ADMIN'); ?></button>
                                <button class="btn btn-danger btn-sm mr5 mb10" type="button"
                                        onClick="deleteAdmin()"><?php oLang::_('O_BACKUP_DELETEBACKUPFILE'); ?></button>
                            </div>
                            <div class="panel-body">
                                <table class="table display" id="adminTable">
                                    <thead>
                                    <tr>
                                        <th><?php oLang::_('ADD_ADMIN_ID'); ?></th>
                                        <th><?php oLang::_('ADD_ADMIN_NAME'); ?></th>
                                        <th><?php oLang::_('ADD_ADMIN_EMAIL'); ?></th>
                                        <th><?php oLang::_('ADD_ADMIN_STATUS'); ?></th>
                                        <th><?php oLang::_('TABLE_DOMAIN'); ?></th>
                                        <th><input id='checkbox' type='checkbox'></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th><?php oLang::_('ADD_ADMIN_ID'); ?></th>
                                        <th><?php oLang::_('ADD_ADMIN_NAME'); ?></th>
                                        <th><?php oLang::_('ADD_ADMIN_EMAIL'); ?></th>
                                        <th><?php oLang::_('ADD_ADMIN_STATUS'); ?></th>
                                        <th><?php oLang::_('TABLE_DOMAIN'); ?></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- End .panel -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include_once(dirname(__FILE__) . '/adminemailsmodal.php');
?>