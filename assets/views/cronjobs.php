<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$this->model->getNounce ();
$urls = oseFirewall::getDashboardURLs ();
$vscansettings = $this->model->getCronSettings (1);
$backupsettings = $this->model->getCronSettings (2);

if ($status == true)
{
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<div class="row">
			<div class="col-md-12">
				<div class="bs-component">
                    <div class="panel-body panelRefresh">
                        <ul class="nav nav-tabs" role="tablist" data-tabs="tabs">
                            <li class="active"><a data-toggle="tab" href="#vscannercron"><?php oLang::_('SCHEDULE_SCANNING'); ?></a></li>
                            <li><a data-toggle="tab" href="#backupcron"><?php oLang::_('SCHEDULE_BACKUP'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="vscannercron">
                                <div class="panel-heading">
                                    <br/><p class="mb15"><?php oLang::_('CRONJOBS_LONG'); ?></p>
                                </div>
                                <form id = 'cronjobs-form' class="form-horizontal group-border stripped" role="form">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><?php oLang::_('HOURS'); ?></h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-xs-8">
                                                        <select class="form-control" id="vscancusthours" name="custhours" size="1" ></select>
                                                    </div>
                                                    <label id="vscanusertime"></label>
                                                    <input id="vscansvrusertime" style="display: none" value="<?php echo $vscansettings['hour'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><?php oLang::_('WEEKDAYS'); ?></h3>
                                                </div>
                                                <div class="panel-body">
                                                    <select class="form-control" name="custweekdays[]" size="7" multiple="" id="vscanweekdays">
                                                        <option value="0" <?php echo ($vscansettings[0] == true)?" selected ":""; ?>>Sunday</option>
                                                        <option value="1" <?php echo ($vscansettings[1] == true)?" selected ":""; ?>>Monday</option>
                                                        <option value="2" <?php echo ($vscansettings[2] == true)?" selected ":""; ?>>Tueday</option>
                                                        <option value="3" <?php echo ($vscansettings[3] == true)?" selected ":""; ?>>Wednesday</option>
                                                        <option value="4" <?php echo ($vscansettings[4] == true)?" selected ":""; ?>>Thursday</option>
                                                        <option value="5" <?php echo ($vscansettings[5] == true)?" selected ":""; ?>>Friday</option>
                                                        <option value="6" <?php echo ($vscansettings[6] == true)?" selected ":""; ?>>Saturday</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <button class="btn btn-primary" type="submit"><?php oLang::_('SAVE_SETTINGS'); ?></button>
                                                </div>
                                                <div class="col-xs-8 row">
                                                    <div class="control-label col-xs-8"><?php oLang::_('SCHEDULE_SCANNING'); ?>:</div>
                                                    <div class="col-xs-4">
                                                        <div class="onoffswitch">
                                                            <input type="checkbox" class="onoffswitch-checkbox"
                                                                   <?php echo ($vscansettings['enabled'] == 1
                                                                       && isset($vscansettings['enabled']))?" checked ":""; ?>id="vscanonoffswitch" >
                                                            <label class="onoffswitch-label" for="vscanonoffswitch">
                                                                <span class="onoffswitch-inner"></span>
                                                                <span class="onoffswitch-switch"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-8">
                                                <label><?php oLang::_('SAVE_SETTING_DESC'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="option" value="com_ose_firewall">
                                    <input type="hidden" name="controller" value="cronjobs">
                                    <input type="hidden" name="action" value="saveCronConfig">
                                    <input type="hidden" name="task" value="saveCronConfig">
                                    <input type="hidden" name="schedule_type" value="1">
                                    <input type="hidden" name="cloudbackuptype" value="1">
                                    <input type="hidden" name="enabled" value="<?php echo ($vscansettings['enabled'] == 1
                                        && isset($vscansettings['enabled']))? 1 : 0 ; ?>"id="vscanenabled"> <!--also set in js for myonoffswitch-->
                                </form>
                            </div>
                        <div class="tab-pane" id="backupcron">
                            <div class="panel-heading">
                                <br/><p class="mb15"><?php oLang::_('CRONJOBSBACKUP_LONG'); ?></p>
                            </div>
                            <form id = 'backup-cronjobs-form' class="form-horizontal group-border stripped" role="form">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <div class="panel panel-dark">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?php oLang::_('HOURS'); ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <select class="form-control" id="backupcusthours" name="custhours" size="1" ></select>
                                                    </div>
                                                    <label id="backupusertime"></label>
                                                    <input id="backupsvrusertime" style="display: none" value="<?php echo $backupsettings['hour'] ?>">
                                                </div>
                                            </div>
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?php oLang::_('WEEKDAYS'); ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <select class="form-control" name="custweekdays[]" size="7" multiple="" id="backupweekdays">
                                                    <option value="0" <?php echo ($backupsettings[0] == true)?" selected ":""; ?>>Sunday</option>
                                                    <option value="1" <?php echo ($backupsettings[1] == true)?" selected ":""; ?>>Monday</option>
                                                    <option value="2" <?php echo ($backupsettings[2] == true)?" selected ":""; ?>>Tueday</option>
                                                    <option value="3" <?php echo ($backupsettings[3] == true)?" selected ":""; ?>>Wednesday</option>
                                                    <option value="4" <?php echo ($backupsettings[4] == true)?" selected ":""; ?>>Thursday</option>
                                                    <option value="5" <?php echo ($backupsettings[5] == true)?" selected ":""; ?>>Friday</option>
                                                    <option value="6" <?php echo ($backupsettings[6] == true)?" selected ":""; ?>>Saturday</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel panel-dark">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?php oLang::_('CLOUD_BACKUP_TYPE'); ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <select class="form-control" id="cloudbackuptype" name="cloudbackuptype" size="1" >
                                                            <?php
                                                            if ($this->model->checkCloudAuthentication(1)){
                                                                echo '<option value="1" '.(($backupsettings['cloudbt'] == 1)?" selected ":"").'>'
                                                                    . $this->model->getLang('NONE').'</option>';
                                                            }
                                                            if ($this->model->checkCloudAuthentication(2)){
                                                                echo '<option value="2" '.(($backupsettings['cloudbt'] == 2)?" selected ":"").'>'
                                                                    . $this->model->getLang('O_BACKUP_DROPBOX') .'</option>';
                                                            }
                                                            if ($this->model->checkCloudAuthentication(3)){
                                                                echo '<option value="3" '.(($backupsettings['cloudbt'] == 3)?" selected ":"").'>'
                                                                    . $this->model->getLang('O_BACKUP_ONEDRIVE') .'</option>';
                                                            }
                                                            if ($this->model->checkCloudAuthentication(4)) {
                                                                echo '<option value="4" ' . (($backupsettings['cloudbt'] == 4) ? " selected " : "") . '>'
                                                                    . $this->model->getLang('O_BACKUP_GOOGLEDRIVE') . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <label id="cloudbackupicon"></label>
                                                    <div class="col-xs-8">
                                                        <label><?php oLang::_('CLOUD_SETTING_REMINDER'); ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <button class="btn btn-primary" type="submit"><?php oLang::_('SAVE_SETTINGS'); ?></button>
                                            </div>
                                            <div class="col-xs-8 row">
                                                <div style="text-align: right;" class="control-label col-xs-8"><?php oLang::_('SCHEDULE_BACKUP'); ?>:</div>
                                                <div class="col-xs-4">
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" class="onoffswitch-checkbox"
                                                               <?php echo ($backupsettings['enabled'] == 1
                                                                   && isset($backupsettings['enabled']))?" checked ":""; ?> id="backuponoffswitch" >
                                                        <label class="onoffswitch-label" for="backuponoffswitch">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-8">
                                        <label><?php oLang::_('SAVE_SETTING_DESC'); ?></label>
                                    </div>
                                </div>
                                <input type="hidden" name="option" value="com_ose_firewall">
                                <input type="hidden" name="controller" value="cronjobs">
                                <input type="hidden" name="action" value="saveCronConfig">
                                <input type="hidden" name="task" value="saveCronConfig">
                                <input type="hidden" name="schedule_type" value="2">
                                <input type="hidden" name="enabled" value = "<?php echo ($backupsettings['enabled'] == 1
                                    && isset($backupsettings['enabled']))? 1 : 0; ?>" id="backupenabled"> <!--also set in js for myonoffswitch-->
                            </form>
                        </div>
                    </div>
				    </div>
                </div>
			</div>
		</div>
	</div>
	<div id='fb-root'></div>
<?php 
}
else {
?>
<div id = "oseappcontainer" >
  <div class="container">
	<?php 
		$this ->model->showLogo ();
	?>
	<div class="row">
		<div class="panel panel-primary">
			<?php 
				$image = OSE_FWURL.'/public/images/screenshot-9.png';
				include_once dirname(__FILE__).'/calltoaction.php';
			?>
		</div>
	</div>
  </div>
</div>
<?php 
	$this->model->showFooterJs();
}
// \PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>