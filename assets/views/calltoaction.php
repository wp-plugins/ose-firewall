<div class="col-md-12">
	<div class="panel panel-danger plain toggle" id="jst_4">
		<!-- Start .panel -->
		<div class="panel-heading">
            <h2 class="text-danger"><?php oLang::_('CALL_TO_ACTION_TITLE'); ?></h2>
			<div class="panel-controls"></div>
		</div>
		<div class="panel-body">
			<p>
				<div class="row">
					<div class="col-md-6"><img src ="<?php echo $image; ?>" width="100%"/></div>
					<div class="col-md-6">
						<p class="text-center">
							<span class="bs-label label-danger"><?php oLang::_('CALL_TO_ACTION_P'); ?></span>
			            </p>
			            <p class="text-center">
			            	<?php oLang::_('CALL_TO_ACTION_P2'); ?>
			            </p>
						<p class="text-left">
							<ul >
			                <?php oLang::_('CALL_TO_ACTION_UL'); ?>
							</ul>
						</p>
					</div>
				</div>
			</p>
			
			<p class="text-left">

            <h2 class="text-danger"><?php oLang::_('CALL_TO_ACTION_TITLE2'); ?></h2>
			</p>
			<p class="text-left">
                <?php oLang::_('CALL_TO_ACTION_DESC2'); ?>
                <button class="btn btn-primary btn-xs mr5"
                        onClick="location.href='<?php echo OSE_OEM_URL_AFFILIATE; ?>'"><?php oLang::_('O_READMORE'); ?>
                </button>
			</p>
			<p class="text-left">

            <h2 class="text-danger"><?php oLang::_('CALL_TO_ACTION_TITLE3'); ?></h2>
            <?php oLang::_('CALL_TO_ACTION_DECS3'); ?><a
                href="<?php echo OSE_OEM_URL_PREMIUM_TUT; ?>" target="_blank"><?php oLang::_('O_OUR_TUTORIAL'); ?></a>
            <?php oLang::_('O_SUBSCRIBE_PLAN'); ?>.
			</p>
			<p>
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<div class="container">
							<button class="btn btn-danger mr5 mb10" type="button" onClick="location.href='<?php oLang::_('OSE_OEM_URL_SUBSCRIBE');?>'">
                                <i class="im-cart6 mr5"></i> <?php oLang::_('SUBSCRIBE_NOW'); ?>
							</button>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
			</p>
		</div>
	</div>
</div>