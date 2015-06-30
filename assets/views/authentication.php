<?php
oseFirewall::checkDBReady();
$status = oseFirewall::checkSubscriptionStatus(false);
$this->model->getNounce();
if ($status == true) {
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
                            <h4 class="panel-title"><?php oLang::_('O_AUTHENTICATION'); ?></h4>
                        </div>
                        <div class="panel-controls">
                        </div>
                        <div class="panel-controls-buttons">

                        </div>
                        <div class="panel-body row">
                            <div class="col-xs-2">
                                <?php
                                $dropboxflag = $this->model->dropBoxVerify();
                                if ($dropboxflag) { ?>
                                    <button id="onedriveLogout" class="btn btn-warning"
                                            onclick="dropbox_logout()"><i class="fa fa-dropbox"></i>&nbsp;<?php oLang::_('O_DROPBOX_LOGOUT'); ?></button>
                                <?php } else { ?>
                                    <button id="dropbox_authorize" class="btn-primary btn"
                                        onclick="initial_dropboxauth()"><i class="fa fa-dropbox"></i>&nbsp;<?php oLang::_('O_AUTHENTICATION_DROPBOX'); ?></button>
                                <?php } ?>
                                
                            </div>
                            <div class="col-xs-2">
                                <?php
                                if ($flag = $this->model->oneDriveVerify()) {
                                    ?>
                                    <button id="onedriveLogout" class="btn btn-warning"
                                            onclick="onedrive_logout()"><i class="fa fa-windows"></i>&nbsp;<?php oLang::_('O_ONEDRIVE_LOGOUT'); ?></button>

                                <?php } elseif (!empty($_GET['code'])) {
                                    $this->model->oauthOneDrive();
                                    ?>
                                    <button id="onedriveLogout" class="btn btn-warning"
                                            onclick="onedrive_logout()"><i class="fa fa-windows"></i>&nbsp;<?php oLang::_('O_ONEDRIVE_LOGOUT'); ?></button>
                                <?php } else { ?>
                                    <a href="<?php $this->model->oauthOneDrive(); ?>"
                                       class="btn-primary btn"><i class="fa fa-windows"></i>&nbsp;<?php oLang::_('O_AUTHENTICATION_ONEDRIVE'); ?> </a>
                                <?php }
                                ?>
                               
                            </div>

                            <!--  google drive oauth button-->
                            <div class="col-xs-2">
                                <?php
                                if ($flag = $this->model->googleDriveVerify()) {
                                    ?>
                                    <button id="googledriveLogout" class="btn btn-warning"
                                            onclick="googledrive_logout()"><i class="fa fa-google"></i>&nbsp;<?php oLang::_('O_GOOGLEDRIVE_LOGOUT'); ?></button>

                                <?php } elseif (!empty($_GET['googlecode'])) {
                                    $this->model->oauthGoogleDrive();
                                    ?>
                                    <button id="onedriveLogout" class="btn btn-warning"
                                            onclick="googledrive_logout()"><i class="fa fa-google"></i>&nbsp;<?php oLang::_('O_GOOGLEDRIVE_LOGOUT'); ?></button>
                                <?php } else { ?>
                                    <a href="<?php $this->model->oauthGoogleDrive(); ?>"
                                       class="btn-primary btn"><i class="fa fa-google"></i>&nbsp;<?php oLang::_('O_AUTHENTICATION_GOOGLEDRIVE'); ?> </a>
                                <?php }
                                ?>
                            </div>


                        </div>
                    </div>
                    <!-- End .panel -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
    ?>
    <div id="oseappcontainer">
        <div class="container">
            <?php
            $this->model->showLogo();
            $this->model->showHeader();
            ?>
            <div class="row">
                <?php
                $image = OSE_FWURL . '/public/images/screenshot-10.png';
                include_once dirname(__FILE__) . '/calltoaction.php';
                ?>
            </div>
        </div>
    </div>
    <?php
    $this->model->showFooterJs();
}
?>