<div class="tab-pane" id="seo">
    <form id = 'seo-configuraton-form' class="form-horizontal group-border stripped" role="form">
        <div class="form-group">
            <label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('O_SEO_PAGE_TITLE');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_SEO_PAGE_TITLE_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="pageTitle" value="<?php echo (empty($seoConfArray['data']['pageTitle']))?'Your Web Page Title':$seoConfArray['data']['pageTitle']?>" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="metaKeywords" class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_KEY');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_SEO_META_KEY_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="metaKeywords" value="<?php echo (empty($seoConfArray['data']['metaKeywords']))?'SEO Meta Keywords':$seoConfArray['data']['metaKeywords']?>" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_DESC');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_SEO_META_DESC_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="metaDescription" value="<?php echo (empty($seoConfArray['data']['metaDescription']))?'SEO Meta Description':$seoConfArray['data']['metaDescription']?>" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_GENERATOR');?>
                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                   data-content="<?php oLang::_('O_SEO_META_GENERATOR_HELP');?>"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="metaGenerator" value="<?php echo (empty($seoConfArray['data']['metaGenerator']))?'SEO Meta Generator':$seoConfArray['data']['metaGenerator']?>" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_SCAN_GOOGLE_BOTS');?></label>
            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value = 1 name="scanGoogleBots" class="onoffswitch-checkbox" id="scanGoogleBots"
                        <?php echo (!empty($seoConfArray['data']['scanGoogleBots']) && $seoConfArray['data']['scanGoogleBots'] == true) ? 'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="scanGoogleBots">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_SCAN_YAHOO_BOTS');?></label>
            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value = 1 name="scanYahooBots" class="onoffswitch-checkbox" id="scanYahooBots"
                        <?php echo (!empty($seoConfArray['data']['scanYahooBots']) && $seoConfArray['data']['scanYahooBots'] == true) ? 'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="scanYahooBots">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php oLang::_('O_SCAN_MSN_BOTS');?></label>
            <div class="col-sm-8">
                <div class="onoffswitch">
                    <input type="checkbox" value = 1 name="scanMsnBots" class="onoffswitch-checkbox" id="scanMsnBots"
                        <?php echo (!empty($seoConfArray['data']['scanMsnBots']) && $seoConfArray['data']['scanMsnBots'] == true) ? 'checked="checked"' : '' ?>>
                    <label class="onoffswitch-label" for="scanMsnBots">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>
        <input type="hidden" name="option" value="com_ose_firewall">
        <input type="hidden" name="controller" value="seoconfig">
        <input type="hidden" name="action" value="saveConfigSEO">
        <input type="hidden" name="task" value="saveConfigSEO">
        <input type="hidden" name="type" value="seo">

        <div class="form-group">
            <div class="col-sm-offset-10">
                <button type="submit" class="btn" id='save-button'><i class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE');?></button>
            </div>
        </div>
    </form>
</div>