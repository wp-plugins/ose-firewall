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
                            <h4 class="panel-title">Authentication</h4>
                        </div>
                        <div class="panel-controls">
                        </div>
                        <div class="panel-controls-buttons">

                        </div>
                        <div class="panel-body">

                            <button id="dropbox_authorize" class="button-primary"
                                    onclick="dropbox_oauth()"><?php oLang::_('O_AUTHENTICATION_DROPBOX'); ?></button>
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