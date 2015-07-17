<?php
oseFirewall::checkDBReady ();
$this->model->getNounce ();
$confArray = $this->model->getConfiguration ( 'scan' );
$adconfArray = $this->model->getConfiguration('advscan');
$seoConfArray = $this->model->getConfiguration ( 'seo' );
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
					<div class="panel-heading white-bg"></div>
                    <div class="panel-body">
                        <div id="tabs">
                            <ul class="nav nav-tabs" data-tabs="tabs">
                                <li id="hehe" class="active"><a data-toggle="tab" href="#firewall"><?php oLang::_('SCAN_CONFIGURATION_TITLE'); ?></a>
                                </li>
                                <li><a data-toggle="tab" href="#seo"><?php oLang::_('SEO_CONFIGURATION'); ?>
                                        <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover" data-content="<?php oLang::_('SEO_CONFIGURATION_HELP');?>"></i>
                                    </a>
                                </li>
                                <li id="haha"><a data-toggle="tab" href="#adfirewall"><?php oLang::_('ADVANCED_FIREWALL_SETTINGS'); ?></a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <?php
                                include_once (dirname ( __FILE__ ) . '/bsconfigfirewall.php');
                                include_once (dirname ( __FILE__ ) . '/bsconfigseo.php');
                                include_once (dirname ( __FILE__ ) . '/bsconfigadfirewall.php');
                                ?>
                            </div>
                        </div>
                    </div>
				   </div>	
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once (dirname ( __FILE__ ) . '/scanconfig.php');
?>

<!-- /.modal -->

<div class="modal fade" id="strongPasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_STRONG_PASSWORD_SETTING'); ?></h4>
            </div>
            <form id='strongPassword-form' class="form-horizontal group-border stripped" role="form">
                <div class="modal-body">
                        <div class="form-group">
                        <label class="col-sm-6 control-label"><?php oLang::_('MPL'); ?></label>
                        <div class="col-sm-5">
                            <input type="text" id="mpl" name="mpl" value="" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label"><?php oLang::_('PMI'); ?></label>
                        <div class="col-sm-5">
                            <input type="text" id="pmi" name="pmi" value="" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-6 control-label"><?php oLang::_('PMS'); ?></label>

                        <div class="col-sm-5">
                            <input type="text" id="pms" name="pms" value="" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="website" class="col-sm-6 control-label"><?php oLang::_('PUCM'); ?></label>
                        <div class="col-sm-5">
                            <input type="text" id="pucm" name="pucm" value="" class="form-control" readonly>
                        </div>
                    </div>
                    <input type="hidden" name="option" value="com_ose_firewall">
                    <input type="hidden" name="controller" value="scanconfig">
                    <input type="hidden" name="action" value="savePassword">
                    <input type="hidden" name="task" value="savePassword">
                </div>
                <div class="modal-footer">
                    <label id="password-warning-label" class="col-sm-12 control-label">
                        <i id="password-warning-message" class="fa fa-exclamation-triangle"></i>
                    </label>
                    <div id="buttonDiv">
                        <div class="form-group">
                            <button type='button' class='btn btn-primary btn-sm mr5 mb10' onclick='defaultJoomla()'><?php oLang::_('RECOMMOND_JOOMLA'); ?></button>
                            <button type='button' class='btn btn-primary btn-sm mr5 mb10' onclick='defaultPassword()'><?php oLang::_('RECOMMOND_PASSWORD'); ?></button>
                            <button type="submit" class="btn btn-success btn-sm mr5 mb10"><?php oLang::_('SAVE'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>