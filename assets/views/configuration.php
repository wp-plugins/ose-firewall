<?php
$this->model->getNounce ();
?>
<!-- Add Variable Form Modal -->
                <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('INSTALLDB'); ?></h4>
                            </div>
                            <div class="modal-body">
                              <form id = 'dbinstall-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">                            
                                   	<div class="form-group">
                                           <div class="col-sm-12">
                                                <div id = "progressbar" class="progress-circular-blue" data-dimension="100" data-text="0%" data-width="12" data-percent="0"></div>
                                                <div class="download-message" id='message-box'>Ready</div>
                                           </div>
                                    </div>
                                    <div class="form-group">
                                           <label class="col-sm-9 control-label" for="textfield"></label>
                                           <div class="col-sm-3">
                                               <button type="submit" class="btn btn-primary" id='add-variable-button'><?php oLang::_('INSTALLNOW');?></button>
                                           </div>
                                    </div>
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->

<!-- Add Variable Form Modal -->
                <div class="modal fade" id="formModal2" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('UNINSTALLDB'); ?></h4>
                            </div>
                            <div class="modal-body">
                              <form id = 'dbuninstall-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">                            
                                   	<div class="form-group">
                                           <div class="col-sm-12">
                                                <div id = "progressbar" class="progress-circular-blue" data-dimension="100" data-text="0%" data-width="12" data-percent="0"></div>
                                                <div class="download-message" id='message-box2'>Ready</div>
                                           </div>
                                    </div>
                                    <div class="form-group">
                                           <label class="col-sm-8 control-label" for="textfield"></label>
                                           <div class="col-sm-4">
                                               <button type="submit" class="btn btn-primary" id='add-variable-button'><?php oLang::_('UNINSTALLNOW');?></button>
                                           </div>
                                    </div>
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->                
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<div class="content-inner">
	<?php 
	  			if (!oseFirewall::isDBReady())
	  			{
	  		?>
	  		<div class="alert alert-danger fade in">
                 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                 <i class="im-cancel alert-icon s24"></i>
                 <strong><?php oLang::_('DBNOTREADY'); ?></strong>.  After that, you can proceed to the Configuration page to change settings.
            </div>
            <?php 
	  			}
            ?>
	<div class="row ">
                        <div class="col-lg-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain">
                                <!-- Start .panel -->
                                <div class="panel-heading white-bg">
                                   
                                </div>
                                <div class="panel-controls"></div>
                                <div class="panel-body">
                                    	<section class="ose-options">
										<?php
											$this->model->showConfigBtnList(); 
										?>
										</section>
                                </div>
                            </div>
                            <!-- End .panel -->
                        </div>
	   </div>
	   </div>
	</div>
</div>