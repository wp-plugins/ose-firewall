<?php
oseFirewall::checkDBReady ();
$this->model->getNounce ();
$emailTmp = $this->model->getConfiguration ( 'emailTemp' );
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
						<!-- Start .panel -->
						<div class="panel-heading white-bg"></div>
						<div class="panel-controls"></div>
                        <div class="panel-body">
                            <ul class="nav nav-tabs" data-tabs="tabs">
                                <li class="active"><a data-toggle="tab"
                                                      href="#admin-manager"><?php oLang::_('ADMIN_MANAGER'); ?></a></li>
                                <li><a data-toggle="tab"
                                       href="#security-manager"><?php oLang::_('SECURITY_MANAGER'); ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <!-- basic firewall rules-->
                                <div class="panel-heading white-bg"></div>
                                <div class="tab-pane active" id="admin-manager">
						<div class="panel-controls-buttons">
							<button class="btn btn-sm mr5 mb10" type="button" onClick="emailEditor()">
								<i class="text-primary glyphicon glyphicon-envelope"></i> <?php oLang::_('EMAIL_EDIT'); ?></button>
							<button class="btn btn-sm mr5 mb10" type="button" onClick="addAdmin()">
								<i class="text-primary glyphicon glyphicon-user"></i> <?php oLang::_('ADD_ADMIN'); ?></button>
							<button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="deleteAdmin()"><?php oLang::_('O_BACKUP_DELETEBACKUPFILE'); ?></button>
						</div>
						<form id='emailEditorForm' class="form-horizontal group-border stripped" role="form" style="display: none">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-2"></div>
									<div class="col-sm-8">
										<span class="bs-label label-danger"><?php oLang::_('O_DONT_BRACE'); ?></span>
									</div>	
									<div class="col-sm-2"></div>
								</div>
								<div class="row">
									<div class="col-sm-2"></div>
									<div class="col-sm-8">
										<textarea name="emailEditor" id="emailEditor" class="form-control tinymce"><?php echo (empty($emailTmp['data']['emailTemplate'])) ? $this->model->readEmailTemp() : stripslashes($emailTmp['data']['emailTemplate']); ?></textarea>
										<input type="hidden" name="option" value="com_ose_firewall"> <input type="hidden" name="controller" value="adminemails"> <input type="hidden" name="action" value="saveEmailEditor"> <input
											type="hidden" name="task" value="saveEmailEditor"
										>
									</div>
									<div class="col-sm-2"></div>
								</div>
								<div class="row">
									<div class="col-sm-2"></div>
									<div class="col-sm-8">
										<div class="buttons pull-right">
											<button type="submit" class="btn btn-sm"><i class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?></button>
											<button type="button" onclick="restoreDefault()" class="btn btn-sm"><i class="glyphicon glyphicon-repeat"></i> <?php oLang::_('RESTORE_EMAIL'); ?></button>
										</div>
									</div>
									<div class="col-sm-2"></div>
								</div>	
							</div>
						</form>
						<div class="panel-body" id="adminBody">
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
                                <div class="tab-pane" id="security-manager">
                                    <div class="panel-controls-buttons">
                                        <button class="btn btn-sm mr5 mb10" type="button" onClick="addSecManager()">
                                            <i class="text-primary glyphicon glyphicon-user"></i> <?php oLang::_('ADD_SECURITY_MANAGER'); ?>
                                        </button>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table display" id="secManagerTable">
                                            <thead>
                                            <tr>
                                                <th><?php oLang::_('O_ID'); ?></th>
                                                <th><?php oLang::_('SECURITY_NAME'); ?></th>
                                                <th><?php oLang::_('SECURITY_EMAIL'); ?></th>
                                                <th><?php oLang::_('SECURITY_STATUS'); ?></th>
                                                <th><?php oLang::_('SECURITY_CONTACT'); ?></th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th><?php oLang::_('O_ID'); ?></th>
                                                <th><?php oLang::_('SECURITY_NAME'); ?></th>
                                                <th><?php oLang::_('SECURITY_EMAIL'); ?></th>
                                                <th><?php oLang::_('SECURITY_STATUS'); ?></th>
                                                <th><?php oLang::_('SECURITY_CONTACT'); ?></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
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
include_once (dirname ( __FILE__ ) . '/adminemailsmodal.php');
include_once(dirname(__FILE__) . '/secManagermodal.php');

?>