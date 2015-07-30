<div class="tab-pane" id="adfirewall">
    <?php
    $status = oseFirewall::checkSubscriptionStatus (false);
    if ($status == true)
    {
    ?>
        <form id='adconfiguraton-form' class="form-horizontal group-border stripped"
              role="form">
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('O_ADRULESETS'); ?>
<!--                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"-->
<!--                       data-content="--><?php //oLang::_('O_ADRULESETS_HELP');?><!--"></i>-->
                </label>
                <div class="col-sm-8">
                    <div class="onoffswitch">
                        <input type="checkbox" value = 1 name="adRules" class="onoffswitch-checkbox" id="adRules"
                            <?php echo (!empty($adconfArray['data']['adRules']) && $adconfArray['data']['adRules'] == true) ? 'checked="checked"' : '' ?>>
                        <label class="onoffswitch-label" for="adRules">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('O_SILENTLY_FILTER_ATTACK'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                       data-content="<?php oLang::_('O_SILENTLY_FILTER_ATTACK_HELP');?>"></i>
                </label>
                <div class="col-sm-8">
                    <div class="onoffswitch">
                        <input type="checkbox" value = 1 name="silentMode" class="onoffswitch-checkbox" id="silentMode"
                            <?php echo (!empty($adconfArray['data']['silentMode']) && $adconfArray['data']['silentMode'] == true) ? 'checked="checked"' : '' ?>>
                        <label class="onoffswitch-label" for="silentMode">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('SILENT_MODE_BLOCK_MAX_ATTEMPTS'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                       data-content="<?php oLang::_('SILENT_MODE_BLOCK_MAX_ATTEMPTS_HELP');?>"></i>
                </label>
                <div class="col-sm-1">
                    <input type="number" name="slient_max_att"
                           value="<?php echo (empty($adconfArray['data']['slient_max_att'])) ? 10 : $adconfArray['data']['slient_max_att'] ?>"
                           class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('ATTACK_BLOCKING_THRESHOLD'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                       data-content="<?php oLang::_('ATTACK_BLOCKING_THRESHOLD_HELP');?>"></i>
                </label>
                <div class="col-sm-1">
                    <input type="number" name="threshold"
                           value="<?php echo (empty($adconfArray['data']['threshold'])) ? 35 : $adconfArray['data']['threshold'] ?>"
                           class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('COUNTRYBLOCK'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                       data-content="<?php oLang::_('COUNTRYBLOCK_HELP');?>"></i>
                </label>
                <div class="col-sm-8">
                    <div class="onoffswitch">
                        <input type="checkbox" value = 1 name="blockCountry" class="onoffswitch-checkbox" id="blockCountry"
                            <?php echo (!empty($adconfArray['data']['blockCountry']) && $adconfArray['data']['blockCountry'] == true) ? 'checked="checked"' : '' ?>>
                        <label class="onoffswitch-label" for="blockCountry">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="option" value="com_ose_firewall">
            <input type="hidden" name="controller" value="scanconfig">
            <input type="hidden" name="action" value="saveConfigScan">
            <input type="hidden" name="task" value="saveConfigScan">
            <input type="hidden" name="type" value="advscan">

            <div class="form-group">
                <div class="col-sm-offset-10 ">
                    <button type="submit" class="btn" id='save-button'><i class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?></button>
                </div>
            </div>
        </form>
    <?php
}
else {
    ?>
    <div id = "oseappcontainer" >
        <div class="container">
            <div class="row">
                <div class="panel panel-primary">
                    <?php
                    //@todo change image
                    $image = OSE_FWURL.'/public/images/premium/adfirewallconfig.png';
                    include_once dirname(__FILE__).'/calltoaction.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $this->model->showFooterJs();
}
?>
</div>
