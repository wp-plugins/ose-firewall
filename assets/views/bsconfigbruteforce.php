<div class="tab-pane" id="bruteforce">
    <form id='bfconfiguraton-form' class="form-horizontal group-border stripped" role="form">
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('BRUTE_FORCE_STATUS'); ?>
                <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                   data-content="<?php oLang::_('BRUTE_FORCE_STATUS_HELP'); ?>"></i>
            </label>

            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value=1 name="bf_status" class="onoffswitch-checkbox"
                           onchange="showbfconfig()"
                           id="bf_status"
                        <?php echo (!empty($bfConfArray['data']['bf_status']) && $bfConfArray['data']['bf_status'] == true) ? 'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="bf_status">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-12" id="bf-config" style="display: none">
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('BRUTEFORCE_MAX_ATT'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                       data-content="<?php oLang::_('BRUTEFORCE_MAX_ATT_HELP'); ?>"></i>
                </label>

                <div class="col-sm-8">
                    <select id="loginSec_maxFailures" name="loginSec_maxFailures">
                        <?php $this->model->getmaxFailures() ?>

                    </select>
                </div>
            </div>
            <?php if (OSE_CMS == 'wordpress') { ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('BRUTEFORCE_TIME'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                       data-content="<?php oLang::_('BRUTEFORCE_TIME_HELP'); ?>"></i>
                </label>

                <div class="col-sm-8">
                    <select id="loginSec_countFailMins" name="loginSec_countFailMins">
                        <?php $this->model->getTimeFrame() ?>

                    </select>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php if (OSE_CMS == 'joomla') { ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('JOOMLA_TWOFACTORAUTH'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                       data-content="<?php oLang::_('JOOMLA_TWOFACTORAUTH_HELP'); ?>"></i>
                </label>

                <div class="col-sm-8">
                    <div class="onoffswitch">
                        <input type="checkbox" value=1 name="totp" class="onoffswitch-checkbox" id="totp"
                            <?php echo ($this->model->checktotp()) ? 'checked="checked"' : '' ?>>
                        <label class="onoffswitch-label" for="totp">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (OSE_CMS == 'wordpress') { ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('O_GOOGLE_2_VERIFICATION'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                       data-content="<?php oLang::_('O_GOOGLE_2_VERIFICATION_HELP'); ?>"></i>
                </label>

                <div class="col-sm-8">
                    <div class="onoffswitch">
                        <input type="checkbox" value=1 name="googleVerification" class="onoffswitch-checkbox"
                               id="googleVerificationSwitch" onchange="showGDialog()"
                            <?php echo (!empty($bfConfArray['data']['googleVerification']) && $bfConfArray['data']['googleVerification'] == true) ?
                                'checked="checked"' : '' ?>>
                        <label class="onoffswitch-label" for="googleVerificationSwitch">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
        <?php } ?>
        <input type="hidden" name="option" value="com_ose_firewall">
        <input type="hidden" name="controller" value="scanconfig">
        <input type="hidden" name="action" value="saveConfigScan">
        <input type="hidden" name="task" value="saveConfigScan">
        <input type="hidden" name="type" value="bf">

        <div class="form-group">
            <div class="col-sm-offset-10 ">
                <button type="submit" class="btn" id='save-button'><i
                        class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?></button>
            </div>
        </div>
    </form>
</div>