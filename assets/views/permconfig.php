<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$this->model->getNounce ();
if (true) {// @todo switch to true to check for subscription status

?>
<div id="oseappcontainer">
    <div class="container">
        <?php
        $this->model->showLogo ();
        $this->model->showHeader ();
        ?>

        <!-- Edit Perm Form Modal -->
        <div class="modal fade" id="editpermModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('PERMCONFIG_SHORT'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <p class="mb15"><?php oLang::_('PERMCONFIGFORM_DESC');?></p>
                        <form id = 'edit-perm-form' class="form-horizontal group-border stripped" role="form" name="editpermform">
                            <div id="SelectedItemsList" class="form-group"></div>
                            <table id="chmodtbl" class="table display">
                                <tbody>
                                <tr>
                                    <td><b>Mode</b></td>
                                    <td></td>
                                    <td>Owner</td>
                                    <td>Group</td>
                                    <td>Public</td>
                                </tr>
                                <tr>
                                    <td>Read</td>
                                    <td></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="4" id="ur"></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="4" id="gr"></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="4" id="wr"></td>
                                </tr>
                                <tr>
                                    <td>Write</td>
                                    <td></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="2" id="uw"></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="2" id="gw"></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="2" id="ww"></td>
                                </tr>
                                <tr>
                                    <td>Execute</td>
                                    <td></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="1" id="ux"></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="1" id="gx"></td>
                                    <td><input type="checkbox" onclick="calcperm();" value="1" id="wx"></td>
                                </tr>
                                <tr>
                                    <td>Permission</td>
                                    <td>
                                        <div class="input-group-addon">0</div>
                                    </td>
                                    <td><input type="text" readonly="readonly" id ="u" class="form-control"></td>
                                    <td><input type="text" readonly="readonly" id ="g" class="form-control"></td>
                                    <td><input type="text" readonly="readonly" id ="w" class="form-control"></td>
                                </tr>
                                </tbody>
                            </table>
                            <span class="mb15"> <?php oLang::_('PERMCONFIGFORM_NB');?> </span>

                            <!--                                @todo disabled untill ver4.0.1-->
                            <!--<input type="checkbox" onchange="disableradios()" value="recur" id="recur"> Recurse into subdirectories

                            <div class="radio" style="padding-left:2em">
                              <label for="recurall">
                                <input type="radio" name="recuroption" id="recurall" value="recurall"> Apply to all Files and Folders
                              </label>
                            </div>
                            <div class="radio" style="padding-left:2em">
                              <label for="recurfiles">
                                <input type="radio" name="recuroption" id="recurfiles" value="recurfiles"> Apply to Files only
                              </label>
                            </div>
                            <div class="radio" style="padding-left:2em">
                              <label for="recurfolders">
                                <input type="radio" name="recuroption" id="recurfolders" value="recurfolders"> Apply to Folders only
                              </label>
                            </div>	-->
                            <br>
                            <input type="hidden" name="chmodpaths" value="">
                            <input type="hidden" name="chmodbinary" value="">
                            <input type="hidden" name="option" value="com_ose_firewall">
                            <input type="hidden" name="controller" value="permconfig">
                            <input type="hidden" name="action" value="editperms">
                            <input type="hidden" name="task" value="editperms">
                            <?php echo '<input type="hidden" name="centnounce" value ="'.oseFirewall::loadNounce().'">';?>
                            <input style="display: none;">
                            <div class="form-group">
                                <div class="col-sm-offset-8">
                                    <button type="submit" class="btn btn-default" id='ChangePermBut'><?php oLang::_('PERMCONFIG_CHANGE');?></button>
                                </div>
                            </div>
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
                    <div class="col-lg-12 sortable-layout">
                        <!-- col-lg-12 start here -->
                        <div class="panel panel-primary plain toggle">
                            <!-- Start .panel -->
                            <div class="panel-heading white-bg">
                                <h4 class="panel-title"><?php oLang::_('PERMCONFIG_SHORT'); ?></h4>
                            </div>
                            <div align="left" style="display:inline-block; width: 80%" id="selected_file">Current Folder:
                                ROOT
                            </div>
                            <div align="right" style="display:inline-block; width: 15%;" id="buttondiv" class="panel-controls-buttons">
                                <button data-target="#editpermModal" data-toggle="modal"
                                        class="btn btn-success btn-sm mr5 mb10" type="button"
                                        onclick="getselecteditemslist ()"><?php oLang::_('PERMCONFIG_EDITOR'); ?></button>
                            </div>
                            <div style="width: 85%; display: table;">
                                <div style="display: table-row">

                                    <div class="panel-body" style="display: table-cell;" id="FileTreeDisplay"></div>
                                    <div class="panel-body" style="display: table-cell;">
                                        <table class="table display" id="permconfigTable" style="display: table-cell;">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th><?php oLang::_('PERMCONFIG_NAME'); ?></th>
                                                <th><?php oLang::_('PERMCONFIG_TYPE'); ?></th>
                                                <th><?php oLang::_('PERMCONFIG_OWNER'); ?></th>
                                                <th><?php oLang::_('PERMCONFIG_PERM'); ?></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End .panel -->
                    </div>
                </div>
            </div>
        </div>
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
                            $image = OSE_FWURL.'/public/images/screenshot-5.png';
                            include_once dirname(__FILE__).'/calltoaction.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php	$this->model->showFooterJs();
        }
        ?>
    </div>
</div>