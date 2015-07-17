<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$this->model->getNounce ();

if (OSE_CMS == 'wordpress') {
    $loginurl =  "admin.php?page=ose_fw_login";
}
elseif (OSE_CMS == 'joomla') {
    $loginurl =  "index.php?option=com_ose_firewall&view=login";
}

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
                    <form id='edit-perm-form' class="form-horizontal group-border stripped" role="form" name="editpermform">
                        <div class="modal-body">
                            <p class="mb15"><?php oLang::_('PERMCONFIGFORM_DESC'); ?></p>
                            <div id="SelectedItemsList" class="form-group"></div>
                            <table id="chmodtbl" class="table display table-condensed">
                                <tbody>
                                <tr>
                                    <td align="center"><b>Mode</b></td>
                                    <td></td>
                                    <td align="center">Owner</td>
                                    <td align="center">Group</td>
                                    <td align="center">Public</td>
                                </tr>
                                <tr>
                                    <td align="right">Read</td>
                                    <td></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="4" id="ur"></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="4" id="gr"></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="4" id="wr"></td>
                                </tr>
                                <tr>
                                    <td align="right">Write</td>
                                    <td></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="2" id="uw"></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="2" id="gw"></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="2" id="ww"></td>
                                </tr>
                                <tr>
                                    <td align="right">Execute</td>
                                    <td align="center"></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="1" id="ux"></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="1" id="gx"></td>
                                    <td align="center"><input type="checkbox" onclick="calcperm();" value="1" id="wx"></td>
                                </tr>
                                <tr>
                                    <td align="right">Permission</td>
                                    <td><input class="form-control" size="2" type="text" readonly="readonly" value="0"></td>
                                    <td><input style="text-align: center;" type="text" readonly="readonly" id="u" class="form-control"></td>
                                    <td><input style="text-align: center;" type="text" readonly="readonly" id="g" class="form-control"></td>
                                    <td><input style="text-align: center;" type="text" readonly="readonly" id="w" class="form-control"></td>
                                </tr>
                                </tbody>
                            </table>
                            <span class="mb15"> <?php oLang::_('PERMCONFIGFORM_NB'); ?> </span>
                            <?php //Check subscription
                            if ($status == true) { ?>

                                <input type="checkbox" onchange="disableradios()" value="recur"
                                       id="recur"> <?php oLang::_('RECURSE_INTO'); ?>

                                <div class="radio" style="padding-left:2em">
                                    <label for="recurall">
                                        <input type="radio" name="recuroption" id="recurall" value="recurall">
                                        <?php oLang::_('APPLY_TO_ALL'); ?>
                                    </label>
                                </div>
                                <div class="radio" style="padding-left:2em">
                                    <label for="recurfiles">
                                        <input type="radio" name="recuroption" id="recurfiles"
                                               value="recurfiles"> <?php oLang::_('APPLY_TO_FILES'); ?>
                                    </label>
                                </div>
                                <div class="radio" style="padding-left:2em">
                                    <label for="recurfolders">
                                        <input type="radio" name="recuroption" id="recurfolders" value="recurfolders">
                                        <?php oLang::_('APPLY_TO_FOLDERS'); ?>
                                    </label>
                                </div>
                            <?php } else {/*if not subscribed show call to subscribe*/ ?>
                                <div class="permpicpremium">
                                    <div class="text">
                                        <button type="button" class="btn btn-success btn-xs"
                                                onclick="redirectTut('<?php echo $loginurl ?>')"> Click here
                                        </button>
                                        <?php oLang::_('CLICK_TO_ACTIVATE'); ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <br>
                            <input type="hidden" name="chmodpaths" value="">
                            <input type="hidden" name="chmodbinary" value="">
                            <input type="hidden" name="option" value="com_ose_firewall">
                            <input type="hidden" name="controller" value="permconfig">
                            <input type="hidden" name="action" value="editperms">
                            <input type="hidden" name="task" value="editperms">
                            <?php echo '<input type="hidden" name="centnounce" value ="' . oseFirewall::loadNounce() . '">'; ?>
                            <input style="display: none;">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn"
                                    id='ChangePermBut'><?php oLang::_('PERMCONFIG_CHANGE'); ?></button>
                        </div>
                    </form>
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
                        <div class="panel panel-primary plain">
                            <!-- Start .panel -->
                            <div class="panel-heading white-bg">
                            </div>
                            <div align="left" style="display:inline-block; width: 60%" id="selected_file">Current Folder: ROOT</div>
                            <div align="right" style="display:inline-block; width: 39%;" id="buttondiv" class="panel-controls-buttons">
                                <button data-target="#editpermModal" data-toggle="modal" class="btn btn-sm mr5 mb10" type="button"
                                        onclick="getselecteditemslist ()"><i class="text-primary glyphicon glyphicon-cog"></i> <?php oLang::_('PERMCONFIG_EDITOR'); ?></button>
                                <?php //Check subscription
                                if ($status == true) {?>
                                    <button class="btn btn-sm mr5 mb10" type="button" onClick="oneClickPermFix()"><i class="text-success glyphicon glyphicon-wrench"></i> <?php oLang::_('PERMCONFIG_ONECLICKPERMFIX'); ?></button>
                                <?php } else {/*if not subscribed show call to subscribe*/?>

                                    <button class="btn btn-warning btn-sm mr5 mb10" type="button" onclick="callToSubscribe('<?php echo $loginurl?>')"><?php oLang::_('PERMCONFIG_ONECLICKPERMFIX'); ?></button>
                                <?php } ?>
                            </div>
                            <div style="width: 100%; display: table;">
                                <div style="display: table-row">
                                    <div class="panel-body" style="display: table-cell;vertical-align: top;padding-top: 70px; width: 200px;">
                                        <label style="vertical-align: top;"><?php oLang::_('FILETREENAVIGATOR'); ?></label>
                                        <div id="FileTreeDisplay"></div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table display" id="permconfigTable" cellspacing="0" width="100%">
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
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th><?php oLang::_('PERMCONFIG_NAME'); ?></th>
                                                    <th><?php oLang::_('PERMCONFIG_TYPE'); ?></th>
                                                    <th><?php oLang::_('PERMCONFIG_OWNER'); ?></th>
                                                    <th><?php oLang::_('PERMCONFIG_PERM'); ?></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
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
    </div>
</div>