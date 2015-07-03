var controller = "advancedbackup";

var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    var dropboxlink = '';
    var dropboxauth = $("#dropboxauth").val();
    var onedrivelink = '';
    var onedriveauth = $("#onedriveauth").val();
    var googledrivelink = '';
    var googledriveauth = $("#googledriveauth").val();
    if (dropboxauth == 1) {
        dropboxlink = "<div class='clickdropbox'><a href='javascript:void(0)' title='Dropbox' class='fa fa-dropbox'></a></div> ";
    }
    if (googledriveauth == 1) {
        googledrivelink = "<div class='clickgoogledrive'><a href='javascript:void(0)' title='GoogleDrive' class='fa fa-google'></a></div>";
    }
    if (onedriveauth == 1) {
        onedrivelink = "<div class='clickonedrive'><a href='javascript:void(0)' title='OneDrive' class='fa fa-windows'></a></div>";
    } else if (onedriveauth == 0 && dropboxauth == 0) {
        dropboxlink = O_AUTH_CLOUD;
    }

    $('#advancedbackupTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                d.option = option;
                d.controller = controller;
                d.action = 'getBackupList';
                d.task = 'getBackupList';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
            {"data": "ID"},
            {"data": "time"},
            {"data": "fileName"},
            {"data": "fileType"},
            {
                "data": null,
                "defaultContent": dropboxlink + onedrivelink + googledrivelink,
                "orderable": false, "searchable": false
            },
            {"data": null, "defaultContent": " ", "orderable": false, "searchable": false}
        ],
        order: [0, 'desc']
    });
    $('#advancedbackupTable tbody').on('click', 'div.clickdropbox', function () {
        var data = $('#advancedbackupTable').dataTable().api().row($(this).parents('tr')).data();
        var id = data["ID"];
        showLoading(O_PREP_FILES);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getDropboxUploads',
                task: 'getDropboxUploads',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.error === null){
                    multiDropboxUpload (data.numFiles, data.varArray);
                }else{
                    hideLoading();
                    showDialogue(O_UPLOAD_ERROR + "<br /><pre>" + data.error + "</pre>", O_ERROR, O_OK);
                }
            },
            error: function (request, textStatus, thrownError) {
                hideLoading();
                showDialogue(O_UPLOAD_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>", O_ERROR, O_OK);
            }
        })
    });

    function multiDropboxUpload (numFiles, varArray){
        for (index = 0; index < numFiles; ++index) {
            dropboxUpload(varArray[index]['path'], varArray[index]['folder'], numFiles );
        }
    }

    var DBxindex = 0;
    var DBxfiles = 'Uploaded: ';

    function dropboxUpload(path, folder, numFiles){
        var filename = path.replace(/^.*[\\\/]/, '');
        showLoading('Uploading: ' + filename + '(' + numFiles + ')');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'dropboxUpload',
                task: 'dropboxUpload',
                path: path,
                folder: folder,
                centnounce: $('#centnounce').val()
            },
            curfile:filename,
            success: function (data) {
                if (data.code == 200 ) {
                    DBxfiles += this.curfile +"<br />";
                    ++DBxindex;
                    if (numFiles == DBxindex) {
                        hideLoading();
                        showDialogue(O_UPLOAD_DROPBOX + "<br />" + DBxfiles, O_SUCCESS, O_OK);
                        DBxindex = 0; DBxfiles = '';
                    }
                }
                else {
                    hideLoading();
                    ++DBxindex;
                    window.stop();
                    showDialogue(O_UPLOAD_ERROR + "<pre>" + "File: " + this.curfile + "<br />"+ data + "</pre>", O_FAIL, O_OK);
                    DBxindex = 0; DBxfiles = '';

                }
            },
            error: function (request, textStatus, thrownError) {
                hideLoading();
                showDialogue(O_UPLOAD_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>", O_ERROR, O_OK);
            }
        });
    }

    $('#advancedbackupTable tbody').on('click', 'div.clickonedrive', function () {
        showLoading(O_PREP_FILES);
        var data = $('#advancedbackupTable').dataTable().api().row($(this).parents('tr')).data();
        var id = data["ID"];
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getOneDriveUploads',
                task: 'getOneDriveUploads',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                multiOneDriveUpload (data.numFiles, data.varArray);
            },
            error: function (request, textStatus, thrownError) {
                hideLoading();
                showDialogue(O_UPLOAD_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>", O_ERROR, O_OK);
            }
        })
    });

    function multiOneDriveUpload (numFiles, varArray){
        for (index = 0; index < numFiles; ++index) {
            oneDriveUpload(varArray[index]['path'], varArray[index]['folderID'], numFiles );
        }
    }

    var ODindex = 0;
    var ODfiles = 'Uploaded: ';

    function oneDriveUpload(path, folderID, numFiles){
        var filename = path.replace(/^.*[\\\/]/, '');
        showLoading('Uploading: ' + filename + '(' + numFiles + ')');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'oneDriveUpload',
                task: 'oneDriveUpload',
                path: path,
                folderID: folderID,
                centnounce: $('#centnounce').val()
            },
            curfile:filename,
            success: function (data) {
                if (data == true || data === null) {
                    ODfiles += this.curfile +"<br />";
                    ++ODindex;
                    if (numFiles == ODindex) {
                        hideLoading();
                        showDialogue(O_UPLOAD_ONEDRIVE + "<br />" + ODfiles, O_SUCCESS, O_OK);
                        ODindex = 0; ODfiles = '';
                    }
                }
                else {
                    hideLoading();
                    ++ODindex;
                    window.stop();
                    showDialogue(O_UPLOAD_ERROR + "<pre>" + "File: " + this.curfile + "<br />"+
                        data['error']['message'] + "</pre>", O_FAIL, O_OK);
                    ODindex = 0; ODfiles = '';

                }
            },
            error: function (request, textStatus, thrownError) {
                hideLoading();
                //showDialogue(O_UPLOAD_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>", O_ERROR, O_OK);
            }
        });
    }
    $('#advancedbackupTable tbody').on('click', 'div.clickgoogledrive', function () {
        showLoading(O_PREP_FILES);
        var data = $('#advancedbackupTable').dataTable().api().row($(this).parents('tr')).data();
        var id = data["ID"];
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getGoogleDriveUploads',
                task: 'getGoogleDriveUploads',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                multiGoogleDriveUpload(data.numFiles, data.varArray);
            },
            error: function (request, textStatus, thrownError) {
                hideLoading();
                showDialogue(O_UPLOAD_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>", O_ERROR, O_OK);
            }
        })
    });

    function multiGoogleDriveUpload(numFiles, varArray) {
        for (index = 0; index < numFiles; ++index) {
            googleDriveUpload(varArray[index]['path'], varArray[index]['folderID'], numFiles);
        }
    }

    var GDindex = 0;
    var GDfiles = 'Uploaded: ';

    function googleDriveUpload(path, folderID, numFiles) {
        var filename = path.replace(/^.*[\\\/]/, '');
        showLoading('Uploading: ' + filename + '(' + numFiles + ')');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'googledrive_upload',
                task: 'googledrive_upload',
                path: path,
                folderID: folderID,
                centnounce: $('#centnounce').val()
            },
            curfile: filename,
            success: function (data) {
                if (data == true || data === null) {
                    GDfiles += this.curfile +"<br />";
                    ++GDindex;
                    if (numFiles == GDindex) {
                        hideLoading();
                        showDialogue(O_UPLOAD_GOOGLEDRIVE + "<br />" + ODfiles, O_SUCCESS, O_OK);
                        GDindex = 0; GDfiles = '';
                    }
                }
                else {
                    hideLoading();
                    ++GDindex;
                    window.stop();
                    showDialogue(O_UPLOAD_ERROR + "<pre>" + "File: " + this.curfile + "<br />"+
                        data['error']['message'] + "</pre>", O_FAIL, O_OK);
                    GDindex = 0; GDfiles = '';
                }
            },
            error: function (request, textStatus, thrownError) {
                hideLoading();
                //showDialogue(O_UPLOAD_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>", O_ERROR, O_OK);
            }
        })
    }

    $('#checkbox').prop('checked', false);
    $('#advancedbackupTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#checkbox').click(function () {
        if ($('#checkbox').is(':checked')) {
            $('#advancedbackupTable tr').addClass('selected');
        } else {
            $('#advancedbackupTable tr').removeClass('selected');
        }
    });

    $("a.panel-refresh").click(function () {
        $('#advancedbackupTable').dataTable().api().ajax.reload();
    });
});
function sendemail(id) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'sendemail',
                task: 'sendemail',
                type: 'dropbox',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data == true) {
                    showDialogue(O_CONFIRM_EMAIL_NOTICE, O_SUCCESS, O_OK);
                }
                else {
                    showDialogue(O_SEND_EMAIL_ERROR, O_FAIL, O_OK);
                }
            },
            error: function (request, textStatus, thrownError) {
                hideLoading();
                showDialogue(O_SEND_EMAIL_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>",
                    O_ERROR, O_OK);
            }
        })
    })
}
function ajaxdeletebackup() {
    jQuery(document).ready(function ($) {
        ids = $('#advancedbackupTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        for (index = 0; index < ids.length; ++index) {
            multiids[index] = (ids[index]['ID']);
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {
                option: option,
                controller: controller,
                action: 'deleteBackup',
                task: 'deleteBackup',
                id: multiids,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data == true) {
                    showDialogue(O_BACKUP_DELE_DESC, O_SUCCESS, O_OK);
                } else {
                    showDialogue(O_DELE_FAIL_DESC, O_FAIL, O_OK);
                }
                $('#advancedbackupTable').dataTable().api().ajax.reload();
            },
            error: function (request, textStatus, thrownError) {
                hideLoading();
                showDialogue(O_DELE_FAIL_DESC + thrownError + "<br /><pre>" + request.responseText + "</pre>",
                    O_ERROR, O_OK);
            }
        });
    })
}
function deletebackup() {
    jQuery(document).ready(function ($) {
        ids = $('#advancedbackupTable').dataTable().api().rows('.selected').data();
        if (ids.length > 0) {
            bootbox.dialog({
                message: O_DELETE_CONFIRM_DESC,
                title: O_CONFIRM,
                buttons: {
                    success: {
                        label: O_YES,
                        callback: function () {
                            ajaxdeletebackup();
                        }
                    },
                    main: {
                        label: O_NO,
                        callback: function () {
                            this.close();
                        }
                    }
                }
            });
        } else {
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_NO);
        }
    })
}
function backup(backup_type, backup_to) {
    showLoading('Please wait...');
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'backup',
                task: 'backup',
                backup_type: backup_type,
                backup_to: backup_to,
                centnounce: $('#centnounce').val()
            },
            success : function(data) {
                if (data.data == false) {
                    hideLoading();
                    showDialogue(O_BACKUP_FAIL, O_FAIL, O_OK);
                }else if (data.conti == 1) {

                    contbackup(data.sourcePath, data.outZipPath, data.serializefile);

                }else /*if (typeof data.data == "number" && data.conti == 0 )*/ {
                    hideLoading();
                    showDialogue(O_BACKUP_SUCCESS, O_SUCCESS, O_OK);
                    $('#advancedbackupTable').dataTable().api().ajax.reload();
                }
            },
            error : function(request, textStatus, thrownError){
                hideLoading();
                showDialogue(O_BACKUP_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>", O_ERROR, O_OK);
            }
        })
    })
}

function contbackup(sourcePath, outZipPath, serializefile){
    showLoading('Archiving files, Please wait...');
    jQuery(document).ready(function($) {
        $.ajax({
            type : "POST",
            url : url,
            dataType : 'json',
            data : {
                option : option,
                controller : controller,
                action : 'contBackup',
                task : 'contBackup',
                sourcePath : sourcePath,
                outZipPath : outZipPath,
                serializefile : serializefile,
                centnounce : $('#centnounce').val()
            },
            success : function(data) {
                if (data.data == false) {
                    hideLoading();
                    showDialogue(O_BACKUP_FAIL, O_FAIL, O_OK);
                }else if (data.conti == 1) {
                    contbackup(data.sourcePath, data.outZipPath, data.serializefile);

                }else if (data.conti == 0 ) {
                    hideLoading();
                    showDialogue(O_BACKUP_SUCCESS, O_SUCCESS, O_OK);
                    $('#advancedbackupTable').dataTable().api().ajax.reload();
                }
            },
            error : function(request, textStatus, thrownError){
                hideLoading();
                showDialogue(O_BACKUP_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>",
                    O_ERROR, O_OK);
            }
        })
    })
}