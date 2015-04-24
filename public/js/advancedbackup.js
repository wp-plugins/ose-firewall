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
                    //   showDialogue("backup file has been uploaded to your dropbox", "Success", "OK");
                    sendemail(id);
                }
                else {
                    showDialogue(data, "fail", "OK");
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
                console.log(data);
                if (data == true) {
                    showDialogue("Email has been sent to you", "Success", "OK");
                }
                else {
                    showDialogue("System occurs some errors", "fail", "OK");
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
                if (data.length > 0) {
                    showDialogue("There are no spammers in your selected emails!",
                        "Great!", 'OK');
                } else {
                    showDialogue("spammers are deleted successfully!", "Success!",
                        'OK');
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
                    message: "Are you sure to delete selected backup zip file or sql file, press yes to proceed",
                    title: "Confirm",
                    buttons: {
                        success: {
                            label: "Yes",
                            callback: function () {
                                ajaxdeletebackup();
                            }
                        },
                        main: {
                            label: "No",
                            callback: function () {
                                this.close();
                            }
                        }
                    }
                });
        } else {
            showDialogue("Please select backup zip or sql files first!", "Notice!", 'OK');
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
                    showDialogue("Backup success", "Success", "OK");
                    $('#advancedbackupTable').dataTable().api().ajax.reload();
                } else {
                    showDialogue("Backup failed, please try again", "Notice", "OK");
                }
            }
        })
    })
}
function showDialogue(message, title, buttonLabel) {
    bootbox.dialog({
        message: message,
        title: title,
        buttons: {
            success: {
                label: buttonLabel
            }
        }
    });
}