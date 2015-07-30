<div class="tab-pane active" id="firewall">
    <form id = 'configuraton-form' class="form-horizontal group-border stripped" role="form">
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('FIREWALL'); ?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('FIREWALL_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value = 0 name="devMode" class="onoffswitch-checkbox" id="devMode"
                        <?php echo (!empty($confArray['data']['devMode']) && $confArray['data']['devMode'] == true) ? '' : 'checked="checked"'?>>
                    <label class="onoffswitch-label" for="devMode">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_WEBMASTER_EMAIL');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_WEBMASTER_EMAIL_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="adminEmail" value="<?php echo (empty($confArray['data']['adminEmail']))?'info@yourwebsite.com':$confArray['data']['adminEmail']?>" class="form-control">
            </div>
        </div>
        <?php if (!empty($oemConfArray['data']['customer_id'])) { ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php oLang::_('PASSCODE_ENTRY'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                       data-content="<?php oLang::_('PASSCODE_ENTRY_HELP'); ?>"></i>
                </label>

                <div class="col-sm-8">
                    <div class="onoffswitch">
                        <input type="checkbox" value=1 name="passcode_status" class="onoffswitch-checkbox"
                               id="passcode_status"
                            <?php echo (!empty($oemConfArray['data']['passcode_status']) && $oemConfArray['data']['passcode_status'] == true) ? 'checked="checked"' : '' ?>>
                        <label class="onoffswitch-label" for="passcode_status">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_RECEIVE_EMAIL');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_RECEIVE_EMAIL_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value = 1 name="receiveEmail" class="onoffswitch-checkbox" id="receiveEmail"
                        <?php echo (!empty($confArray['data']['receiveEmail']) && $confArray['data']['receiveEmail'] == true) ? 'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="receiveEmail">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>
        <?php if (!class_exists('SConfig')) {?>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_STRONG_PASSWORD'); ?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_STRONG_PASSWORD_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value = 1 id="strongPassword" name="strongPassword" class="onoffswitch-checkbox" id="strongPassword"
                        <?php echo (!empty($confArray['data']['strongPassword']) && $confArray['data']['strongPassword'] == true) ? 'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="strongPassword">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>
        <?php } ?>
        <!--        --><?php //  if (OSE_CMS == 'wordpress') { ?>
        <!--        <div class="form-group">-->
        <!--            <label class="col-sm-4 control-label">--><?php //oLang::_('O_LOGIN_PAGE_SETTING'); ?>
        <!--                <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"-->
        <!--                   data-content="--><?php //oLang::_('O_LOGIN_PAGE_HELP'); ?><!--"></i>-->
        <!--            </label>-->
        <!---->
        <!--            <div class="col-sm-8">-->
        <!--                --><?php //$this->model->login_page_input(); ?>
        <!--            </div>-->
        <!--        </div>-->
        <!--        --><?php //} else { ?>
        <!--            <div class="form-group">-->
        <!--                <label class="col-sm-4 control-label">--><?php //oLang::_('O_BACKEND_SECURE_KEY'); ?>
        <!--                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"-->
        <!--                       data-content="--><?php //oLang::_('O_BACKEND_SECURE_KEY_HELP'); ?><!--"></i>-->
        <!--                </label>-->
        <!---->
        <!--                <div class="col-sm-8">-->
        <!--                    --><?php //$this->model->backend_secure_key(); ?>
        <!--                </div>-->
        <!--            </div>-->
        <!--        --><?php //} ?>

        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_FRONTEND_BLOCKING_MODE');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_FRONTEND_BLOCKING_MODE_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <label class="radio-inline">
                    <input type="radio" id= "blockIPban" onclick="toggleDisabled(1)" name="blockIP" value="1" <?php echo (!empty($confArray['data']['blockIP']) && $confArray['data']['blockIP']==true)?'checked="checked"':''?>>
                    <?php oLang::_('O_FRONTEND_BLOCKING_MODE_BAN');?>
                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover" data-title="Ban Page"
                       data-content="<?php oLang::_('O_FRONTEND_BLOCKING_MODE_BAN_HELP');?>"></i>
                </label>
                <label class="radio-inline">
                    <input type="radio" id= "blockIP403" onclick="toggleDisabled(0)" name="blockIP" value="0" <?php echo (empty($confArray['data']['blockIP']))?'checked="checked"':''?>>
                    <?php oLang::_('O_FRONTEND_BLOCKING_MODE_403');?>
                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover" data-title="403 Blocking"
                       data-content="<?php oLang::_('O_FRONTEND_BLOCKING_MODE_403_HELP');?>"></i>
                </label>
            </div>
        </div>
        <div id = "customBanpageDiv" class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_CUSTOM_BAN_PAGE');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_CUSTOM_BAN_PAGE_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <textarea name="customBanpage" id="customBanpage" class="form-control tinymce"><?php echo (empty($confArray['data']['customBanpage']))?'':$confArray['data']['customBanpage']?></textarea>
            </div>
        </div>
        <div id = "customBanURLDiv" class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_CUSTOM_BAN_PAGE_URL');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_CUSTOM_BAN_PAGE_URL_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="customBanURL" value="<?php echo (empty($confArray['data']['customBanURL']))?'':$confArray['data']['customBanURL']?>" class="form-control">
            </div>
        </div>
        <!--<div class="form-group">
            <label class="col-sm-4 control-label"><?php /*oLang::_('O_ALLOWED_FILE_TYPES');*/?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php /*oLang::_('O_ALLOWED_FILE_TYPES_HELP');*/?>"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="allowExts" value="<?php /*echo (empty($confArray['data']['allowExts']))?'jpg, png, doc':$confArray['data']['allowExts']*/?>" class="form-control">
            </div>
        </div>-->
        <?php if (OSE_CMS == 'wordpress') {?>
<!--        @todo Split google verification for unban admin-->
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_GOOGLE_2_VERIFICATION');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_GOOGLE_2_VERIFICATION_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value = 1 name="googleVerification" class="onoffswitch-checkbox" id="googleVerificationSwitch" onchange="showGDialog()"
                        <?php echo (!empty($confArray['data']['googleVerification']) && $confArray['data']['googleVerification'] == true) ?
                            'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="googleVerificationSwitch">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
            <div class="col-sm-12" id="hidden-QRcode" style="display: none">
                <label class="col-sm-4 pt20 control-label"> <?php oLang::_('O_GOOGLE_2_SECRET'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                       data-content="<?php oLang::_('O_GOOGLE_2_SECRET_HELP');?>"></i>
                </label>
                <div id="shhsecret" class="col-sm-8 pt20">
                    <?php $googleAuth = $this->model->showGoogleSecret(); echo $googleAuth['secret'];?>
                </div><div class="col-sm-12"></div>
                <label class="col-sm-4 control-label pt25">	<?php oLang::_('O_GOOGLE_2_QRCODE'); ?>
                    <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                       data-content="<?php oLang::_('O_GOOGLE_2_QRCODE_HELP');?>"></i>
                </label>
                <div id='shhqrcode' class="col-sm-8 pt5"><?php	echo $googleAuth['QRcode'];	?> </div>
            </div>
        </div>
        <?php }?>
        <input type="hidden" name="option" value="com_ose_firewall">
        <input type="hidden" name="controller" value="scanconfig">
        <input type="hidden" name="action" value="saveConfigScan">
        <input type="hidden" name="task" value="saveConfigScan">
        <input type="hidden" name="type" value="scan">
        <div class="form-group">
            <div class="col-sm-offset-10 ">
                <button type="submit" class="btn" id='save-button'><i class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE');?></button>
            </div>
        </div>
    </form>
</div>