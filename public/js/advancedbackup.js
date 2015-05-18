var controller = "advancedbackup";

var option = "com_ose_firewall";

jQuery(document).ready(function ($) {

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
        columns: [{
            "data": "ID"
        }, {
            "data": "time"
        }, {
            "data": "fileName"
        }, {
            "data": "fileType"
        }, {
            "data": null,
            "defaultContent": "<div class='clickdropbox'><i class='fa fa-dropbox'></i></div>",
            "orderable": false,
            "searchable": false
        }, {
            "data": null,
            "defaultContent": " ",
            "orderable": false,
            "searchable": false
        }]
    });
    $('#advancedbackupTable tbody').on('click', 'div.clickdropbox', function () {
        var data = $('#advancedbackupTable').dataTable().api().row($(this).parents('tr')).data();
        var id = data["ID"];
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'dropbox_upload',
                task: 'dropbox_upload',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data == true) {
                    showDialogue(O_UPLOAD_DROPBOX, O_SUCCESS, O_OK);
                    //sendemail(id);
                }
                else {
                    showDialogue(O_UPLOAD_ERROR + "<pre>" + data + "</pre>", O_FAIL, O_OK);
                }
            }
        })
    });
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
                    showDialogue(O_BACKUP_DELE_DESC,
                        O_SUCCESS, O_OK);
                } else {
                    showDialogue(O_DELE_FAIL_DESC, O_FAIL,
                        O_OK);
                }
                $('#advancedbackupTable').dataTable().api().ajax.reload();
            }
        });
    })
}
function deletebackup() {
    jQuery(document).ready(function ($) {
        ids = $('#advancedbackupTable').dataTable().api().rows('.selected').data();
        if (ids.length > 0) {
            bootbox
                .dialog({
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
    showLoading();
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
            success: function (data) {
                hideLoading();
                if (data.data == true) {
                    showDialogue(O_BACKUP_SUCCESS, O_SUCCESS, O_OK);
                    $('#advancedbackupTable').dataTable().api().ajax.reload();
                } else {
                    showDialogue(O_BACKUP_FAIL, O_FAIL, O_OK);
                }
            }
        })
    })
}
