<?php
oseFirewall::checkDBReady ();
$this->model->getNounce ();
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<!-- Add Variable Form Modal -->
                <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_A_VARIABLE'); ?></h4>
                            </div>
                            <div class="modal-body">
                              <form id = 'add-variable-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">                            
                                   	<div class="form-group">
                                           <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('O_VARIABLE_TYPE');?></label>
                                           <div class="col-sm-8">
                                               <select class="form-control" id="requesttype" name ="requesttype">
                                                    <option value="POST">POST</option>
                                                    <option value="GET">GET</option>
                                                    <option value="COOKIE">COOKIE</option>
                                                </select>
                                           </div>
                                    </div>
                                   	<div class="form-group">
                                           <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('O_VARIABLE_NAME');?></label>
                                           <div class="col-sm-8">
                                               <input type="text" placeholder="<?php oLang::_('O_VARIABLE_NAME');?>" id="variablefield" name="variablefield" class="form-control">
                                           </div>
                                    </div>
                                    <div class="form-group">
                                           <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('O_STATUS');?></label>
                                           <div class="col-sm-8">
                                               <select class="form-control" id="statusfield" name ="statusfield">
                                                    <option value="1">Active</option>
                                                    <option value="2">Filtered</option>
                                                    <option value="3">Whitelisted</option>
                                               </select>
                                           </div>
                                    </div>
                                    <div class="form-group">
                                           <label class="col-sm-9 control-label" for="textfield"></label>
                                           <div class="col-sm-3">
                                               <button type="submit" class="btn btn-primary" id='add-variable-button'><?php oLang::_('ADD_A_VARIABLE');?></button>
                                           </div>
                                    </div>
                                	<input type="hidden" name="controller" value="variables"> 
								    <input type="hidden" name="action" value="addvariables">
								    <input type="hidden" name="task" value="addvariables">
								    <input type="hidden" name="option" value="com_ose_firewall">
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->
	<div class="content-inner">
	<div class="row ">
                        <div class="col-lg-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain toggle panelClose panelRefresh">
                                <!-- Start .panel -->
                                <div class="panel-heading white-bg">
                                    <h4 class="panel-title">Variables Table</h4>
                                </div>
                                <div class="panel-controls"></div>
                                <div class="panel-controls-buttons">
                                	<button data-target="#formModal" data-toggle="modal" class="btn btn-primary btn-sm mr5 mb10"><?php oLang::_('ADD_A_VARIABLE'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('scanvar')"><?php oLang::_('SCAN_VARIABLE'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('filtervar')"><?php oLang::_('FILTER_VARIABLE'); ?></button>
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick="changeBatchItemStatus('ignorevar')"><?php oLang::_('IGNORE_VARIABLE'); ?></button>
                                	<?php 
                                		if (OSE_CMS=='joomla') {
                                	?>
                                	<button class="btn btn-yellow btn-sm mr5 mb10" type="button" onClick="loadData('loadJoomlarules')"><?php oLang::_('LOAD_JOOMLA_DATA'); ?></button>
                                	<button class="btn btn-yellow btn-sm mr5 mb10" type="button" onClick="loadData('loadJSocialrules')"><?php oLang::_('LOAD_JSOCIAL_DATA'); ?></button>
                                	<?php 
                                		}
                                		else
                                	{
                                	?>
                                	<button class="btn btn-yellow btn-sm mr5 mb10" type="button" onClick="loadData('loadWordpressrules')"><?php oLang::_('LOAD_WORDPRESS_DATA'); ?></button>
                                	<?php 
                                		}
                                	?>
                                	<button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="removeItems()"><?php oLang::_('O_DELETE_ITEMS'); ?></button>
                                	<button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="removeAllItems()"><?php oLang::_('O_DELETE__ALLITEMS'); ?></button>
                                	
                                </div>
                                <div class="panel-body">
                                    <table class="table display" id="variablesTable">
                                        <thead>
                                            <tr>
												<th><?php oLang::_('O_ID'); ?></th>
												<th><?php oLang::_('O_VARIABLES'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th><?php oLang::_('O_STATUS_EXP'); ?></th>
												<th><input type="checkbox" name="checkedAll" id="checkedAll"></input></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
												<th><?php oLang::_('O_ID'); ?></th>
												<th><?php oLang::_('O_VARIABLES'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th><?php oLang::_('O_STATUS_EXP'); ?></th>
                                            	<th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- End .panel -->
                        </div>
	   </div>
	   </div>
	</div>
</div>