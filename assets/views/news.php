<?php
oseFirewall::checkDBReady ();
$this->model->getNounce ();
$limit = 5 ;
?>
<div id="oseappcontainer">
    <div class="container">
        <?php
        $this->model->showLogo ();
        $this->model->showHeader ();
        ?>
        <div class="content-inner">
            <div class="row ">
                <div class="col-lg-12 sortable-layout">
                    <!-- col-lg-12 start here -->
                    <div class="panel panel-primary plain">
                        <div class="panel-heading gray-bg"></div>
                        <div class="panel-body">
                            <div id="tabs">
                                <ul class="nav nav-tabs" data-tabs="tabs">
                                    <li class="active"><a data-toggle="tab" href="#latest">Latest</a></li>
                                    <li><a data-toggle="tab" href="#changelog">Changelog</a></li>
<!--                                    <li><a data-toggle="tab" href="#research-lab">Research Lab</a></li>-->
<!--                                    <li><a data-toggle="tab" href="#admin-tools">Admin Tools</a></li>-->
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="latest">
                                        <?php $this->model->getAnyFeed("http://www.centrora.com/category/blog/feed", $limit); ?>
                                    </div>
                                    <div class="tab-pane" id="changelog">
                                        <?php $this->model->getChangelogFeed("http://www.centrora.com/category/changelog/feed/atom/", $limit); ?>
                                    </div>
<!--                                    <div class="tab-pane" id="research-lab">-->
<!--                                        --><?php //$this->model->getAnyFeed("https://www.centrora.com/category/research-lab/feed/", $limit); ?>
<!--                                    </div>-->
<!--                                    <div class="tab-pane" id="admin-tools">-->
<!--                                        --><?php //$this->model->getAnyFeed("https://www.centrora.com/category/admin-tools/feed/", $limit); ?>
<!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>