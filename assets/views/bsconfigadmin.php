<div class="tab-pane" id="admin">
    <form id='adminconfiguraton-form' class="form-horizontal group-border stripped" role="form">
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_WEBMASTER_EMAIL'); ?>
                <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                   data-content="<?php oLang::_('O_WEBMASTER_EMAIL_HELP'); ?>"></i>
            </label>

            <div class="col-sm-8">
                <input type="text" name="adminEmail"
                       value="<?php echo (empty($adminConfArray['data']['adminEmail'])) ? 'info@yourwebsite.com' : $adminConfArray['data']['adminEmail'] ?>"
                       class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_RECEIVE_EMAIL'); ?>
                <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                   data-content="<?php oLang::_('O_RECEIVE_EMAIL_HELP'); ?>"></i>
            </label>

            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value=1 name="receiveEmail" class="onoffswitch-checkbox" id="receiveEmail"
                        <?php echo (!empty($adminConfArray['data']['receiveEmail']) && $adminConfArray['data']['receiveEmail'] == true) ? 'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="receiveEmail">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('CENTRORA_GOOGLE_AUTH'); ?>
                <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                   data-content="<?php oLang::_('CENTRORA_GOOGLE_AUTH_HELP'); ?>"></i>
            </label>

            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value=1 name="centroraGA" class="onoffswitch-checkbox" id="centroraGASwitch"
                           onchange="showSecret()"
                        <?php echo (!empty($adminConfArray['data']['centroraGA']) && $adminConfArray['data']['centroraGA'] == true) ?
                            'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="centroraGASwitch">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
            <div class="col-sm-12" id="hidden-QRcode" style="display: none">
                <label class="col-sm-4 pt20 control-label"> <?php oLang::_('O_GOOGLE_2_SECRET'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                       data-content="<?php oLang::_('O_GOOGLE_2_SECRET_HELP'); ?>"></i>
                </label>

                <div id="shhsecret" class="col-sm-8 pt20">
                    <?php $googleAuth = $this->model->showGoogleSecret();
                    echo $googleAuth['secret']; ?>
                </div>
                <div class="col-sm-12"></div>
                <label class="col-sm-4 control-label pt25">    <?php oLang::_('O_GOOGLE_2_QRCODE'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                       data-content="<?php oLang::_('O_GOOGLE_2_QRCODE_HELP'); ?>"></i>
                </label>

                <div id='shhqrcode' class="col-sm-8 pt5"><?php echo $googleAuth['QRcode']; ?> </div>
            </div>
        </div>
        <input type="hidden" name="option" value="com_ose_firewall">
        <input type="hidden" name="controller" value="scanconfig">
        <input type="hidden" name="action" value="saveConfigScan">
        <input type="hidden" name="task" value="saveConfigScan">
        <input type="hidden" name="type" value="admin">

        <div class="form-group">
            <div class="col-sm-offset-10 ">
                <button type="submit" class="btn" id='save-button'><i
                        class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?></button>
            </div>
        </div>
    </form>
</div>