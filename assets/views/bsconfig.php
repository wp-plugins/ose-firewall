<?php
oseFirewall::checkDBReady ();
$this->model->getNounce ();
$confArray = $this->model->getConfiguration ( 'scan' );
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
                   <div class="panel panel-primary plain toggle panelClose panelRefresh">
					<div class="panel-heading white-bg">
                         <h4 class="panel-title"></h4>
                    </div>
                    <div class="panel-body">
						<ul class="nav nav-tabs" data-tabs="tabs">
						    <li class="active"><a data-toggle="tab" href="#firewall"><?php oLang::_('SCAN_CONFIGURATION_TITLE'); ?></a></li>
						    <li><a data-toggle="tab" href="#seo"><?php oLang::_('SEO_CONFIGURATION'); ?></a></li>
						</ul>
						<div class="tab-content">
						    <div class="tab-pane active" id="firewall">
						        <form id = 'configuraton-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="adminEmail" class="col-sm-4 control-label"><?php oLang::_('O_WEBMASTER_EMAIL');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="adminEmail" value="<?php echo (empty($confArray['data']['adminEmail']))?'info@yourwebsite.com':$confArray['data']['adminEmail']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="receiveEmail" class="col-sm-4 control-label"><?php oLang::_('O_RECEIVE_EMAIL');?></label>
										<div class="col-sm-8">
												<label class="radio-inline">
				                                     <input type="radio" name="receiveEmail" value="1" <?php echo (!empty($confArray['data']['receiveEmail']) && $confArray['data']['receiveEmail']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="receiveEmail" value="0" <?php echo (empty($confArray['data']['receiveEmail']))?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="devMode" class="col-sm-4 control-label"><?php oLang::_('O_DEVELOPMENT_MODE');?></label>
										<div class="col-sm-8">
												<label class="radio-inline">
				                                     <input type="radio" name="devMode" value="1" <?php echo (!empty($confArray['data']['devMode']) && $confArray['data']['devMode']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="devMode" value="0" <?php echo (empty($confArray['data']['devMode']))?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="blockIP" class="col-sm-4 control-label"><?php oLang::_('O_FRONTEND_BLOCKING_MODE');?></label>
										<div class="col-sm-8">
												<label class="radio-inline">
				                                     <input type="radio" name="blockIP" value="1" <?php echo (!empty($confArray['data']['blockIP']) && $confArray['data']['blockIP']==true)?'checked="checked"':''?>><?php oLang::_('O_BAN_IP_AND_SHOW_BAN_PAGE_TO_STOP_AN_ATTACK');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="blockIP" value="0" <?php echo (empty($confArray['data']['blockIP']))?'checked="checked"':''?>><?php oLang::_('O_SHOW_A_403_ERROR_PAGE_AND_STOP_THE_ATTACK');?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="allowExts" class="col-sm-4 control-label"><?php oLang::_('O_ALLOWED_FILE_TYPES');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="allowExts" value="<?php echo (empty($confArray['data']['allowExts']))?'jpg, png, doc':$confArray['data']['allowExts']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="googleVerification" class="col-sm-4 control-label"><?php oLang::_('O_GOOGLE_2_VERIFICATION');?></label>
										<div class="col-sm-8">
												<label class="radio-inline">
                                                    <input type="radio" id="showQR" name="googleVerification" value="1"
                                                           onchange="showSecret()"<?php echo (!empty($confArray['data']['googleVerification']) && $confArray['data']['googleVerification'] == true) ? 'checked="checked"' : '' ?>><?php oLang::_('ON'); ?>
				                                </label>
				                                <label class="radio-inline">
                                                    <input type="radio" id="HideQR" name="googleVerification" value="0"
                                                           onchange="showSecret()"<?php echo (empty($confArray['data']['googleVerification'])) ? 'checked="checked"' : '' ?>><?php oLang::_('OFF'); ?>
				                                </label>
										</div>
									</div>
                                    <!--                                    <div class="form-group" id="hidden-QRcode" style="display: none">-->
                                    <!--                                        <label for="googleSecret"-->
                                    <!--                                               class="col-sm-4 control-label">-->
                                    <?php //oLang::_('O_GOOGLE_2_SECRET'); ?><!--</label>-->
                                    <!---->
                                    <!--                                        <div id="shhsecret" class="col-sm-6">-->
                                    <!--                                            --><?php //$googleAuth = $this->model->showGoogleSecret();
                                    //                                            echo $googleAuth['secret'];
                                    //                                            ?>
                                    <!--                                        </div>-->
                                    <!--                                        <label for="googleQRcode"-->
                                    <!--                                               class="col-sm-4 control-label">-->
                                    <?php //oLang::_('O_GOOGLE_2_QRCODE'); ?><!--</label>-->
                                    <!--                                        <div id='shhqrcode' class="col-sm-6">-->
                                    <!--                                            --><?php
                                    //                                            echo $googleAuth['QRcode'];
                                    //                                            ?>
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->
										<input type="hidden" name="option" value="com_ose_firewall">
									 	<input type="hidden" name="controller" value="scanconfig"> 
									    <input type="hidden" name="action" value="saveConfigScan">
									    <input type="hidden" name="task" value="saveConfigScan">
									    <input type="hidden" name="type" value="scan"> 
									<div class="form-group">
										<div class="col-sm-offset-10 ">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('SAVE');?></button>
										</div>
									</div>
								</form>
						    </div>
						    <div class="tab-pane" id="seo">
						        <form id = 'seo-configuraton-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('O_SEO_PAGE_TITLE');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="pageTitle" value="<?php echo (empty($seoConfArray['data']['pageTitle']))?'Your Web Page Title':$seoConfArray['data']['pageTitle']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="metaKeywords" class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_KEY');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="metaKeywords" value="<?php echo (empty($seoConfArray['data']['metaKeywords']))?'SEO Meta Keywords':$seoConfArray['data']['metaKeywords']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="metaDescription" class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_DESC');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="metaDescription" value="<?php echo (empty($seoConfArray['data']['metaDescription']))?'SEO Meta Description':$seoConfArray['data']['metaDescription']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="metaGenerator" class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_GENERATOR');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="metaGenerator" value="<?php echo (empty($seoConfArray['data']['metaGenerator']))?'SEO Meta Generator':$seoConfArray['data']['metaGenerator']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="customBanpage" class="col-sm-4 control-label"><?php oLang::_('O_CUSTOM_BAN_PAGE');?></label>
										<div class="col-sm-8">
											<textarea name="customBanpage" id="customBanpage" class="form-control tinymce"><?php echo (empty($seoConfArray['data']['customBanpage']))?35:$seoConfArray['data']['customBanpage']?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="customBanURL" class="col-sm-4 control-label"><?php oLang::_('O_CUSTOM_BAN_PAGE_URL');?> <i class="fa fa-info-circle" rel="tooltip" title="When this function is enabled, the attacker will be redirected to the URL as defined which replaces the Custom Ban Page as defined above." id="customURLtip"></i></label>
										<div class="col-sm-8">
											 <input type="text" name="customBanURL" id="customBanURL" value="<?php echo (empty($seoConfArray['data']['customBanURL']))?'':$seoConfArray['data']['customBanURL']?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="scanGoogleBots" class="col-sm-4 control-label"><?php oLang::_('O_SCAN_GOOGLE_BOTS');?></label>
										<div class="col-sm-8">
												<label class="radio-inline">
				                                     <input type="radio" name="scanGoogleBots" value="1" <?php echo (!empty($seoConfArray['data']['scanGoogleBots']) && $seoConfArray['data']['scanGoogleBots']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="scanGoogleBots" value="0" <?php echo (empty($seoConfArray['data']['scanGoogleBots']))?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="scanYahooBots" class="col-sm-4 control-label"><?php oLang::_('O_SCAN_YAHOO_BOTS');?></label>
										<div class="col-sm-8">
												<label class="radio-inline">
				                                     <input type="radio" name="scanYahooBots" value="1" <?php echo (!empty($seoConfArray['data']['scanYahooBots']) && $seoConfArray['data']['scanYahooBots']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="scanYahooBots" value="0" <?php echo (empty($seoConfArray['data']['scanYahooBots']))?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="scanMSNBots" class="col-sm-4 control-label"><?php oLang::_('O_SCAN_MSN_BOTS');?></label>
										<div class="col-sm-8">
												<label class="radio-inline">
				                                     <input type="radio" name="scanMSNBots" value="1" <?php echo (!empty($seoConfArray['data']['scanMSNBots']) && $seoConfArray['data']['scanMSNBots']==true)?'checked="checked"':''?>><?php oLang::_('ON');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="scanMSNBots" value="0" <?php echo (empty($seoConfArray['data']['scanMSNBots']))?'checked="checked"':''?>><?php oLang::_('OFF');?>
				                                </label>
										</div>
									</div>
									 	<input type="hidden" name="option" value="com_ose_firewall"> 
									 	<input type="hidden" name="controller" value="seoconfig"> 
									    <input type="hidden" name="action" value="saveConfigSEO">
									    <input type="hidden" name="task" value="saveConfigSEO">
									    <input type="hidden" name="type" value="seo"> 
									
									<div class="form-group">
										<div class="col-sm-offset-10">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('SAVE');?></button>
										</div>
									</div>
								</form>
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