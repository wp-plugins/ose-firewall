var controller = "scanreport";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    $('#scanreportTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                d.option = option;
                d.controller = controller;
                d.action = 'getMalwareMap';
                d.task = 'getMalwareMap';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
            {"data": "file_id"},
            {"data": "filename"},
            {"data": "patterns"},
            {"data": "pattern_id"},
            {"data": "confidence"},
            {"data": "view"},
            {
                "data": null,
                "defaultContent": " ",
                "orderable": false,
                "searchable": false
            }
        ]
    });
    $('#checkbox').prop('checked', false);
    $('#scanreportTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#checkbox').click(function () {
        if ($('#checkbox').is(':checked')) {
            $('#scanreportTable tr').addClass('selected');
        } else {
            $('#scanreportTable tr').removeClass('selected');
        }
    });
});
function batchbk() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchbk',
                    task: 'batchbk',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == "success") {
                        showDialogue("Backup success", "Success", "OK");
                    } else {
                        showDialogue("Backup failed, please try again", "Notice", "OK");
                    }
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            showDialogue("Please select files first!", "Notice!", 'OK');
        }
    })
}
function batchbkcl() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchbkcl',
                    task: 'batchbkcl',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == "success") {
                        showDialogue("Backup and Clean success", "Success", "OK");
                    } else {
                        showDialogue("Backup and Clean failed, please try again", "Notice", "OK");
                    }
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            showDialogue("Please select files first!", "Notice!", 'OK');
        }
    })
}
function batchrs() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchrs',
                    task: 'batchrs',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == "success") {
                        showDialogue("Restore success", "Success", "OK");
                    } else {
                        showDialogue("Restore failed, please try again", "Notice", "OK");
                    }
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            showDialogue("Please select files first!", "Notice!", 'OK');
        }
    })
}
function confirmbatchdl() {
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        if (ids.length > 0) {
            bootbox
                .dialog({
                    message: "Are you sure to delete selected files, this operation can not backward, press yes to proceed",
                    title: "Confirm",
                    buttons: {
                        success: {
                            label: "Yes",
                            callback: function () {
                                batchdl();
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
            showDialogue("Please select files first!", "Notice!", 'OK');
        }
    })
}
function batchdl() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;

        for (index = 0; index < ids.length; ++index) {
            multiids[index] = (ids[index]['file_id']);
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'batchdl',
                task: 'batchdl',
                id: multiids,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showDialogue("Delete success", "Success", "OK");
                } else {
                    showDialogue("Delete failed, please try again", "Notice", "OK");
                }
                $('#checkbox').prop('checked', false);
                $('#scanreportTable').dataTable().api().ajax.reload();
            }
        });
    })
}
function viewFiledetail(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $('#filecontentModal').modal();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'viewfile',
                task: 'viewfile',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                var newtext = data.data;
                var newtext = "<pre>" + newtext + "</pre>"
                var re = /&lt;span class=&#039;bg-warning&#039;&gt;/img;
                var subst = '<span class=\'bg-warning\'>';
                var result = newtext.replace(re, subst);

                var re1 = /&lt;\/span&gt;/img;
                var subst1 = '</span>';
                var result1 = result.replace(re1, subst1);

                var buttons =
                    "<button type='button' class='btn btn-primary' onclick='virusbackup(" + id + ")'>Back Up</button>" +
                    "<button type='button' class='btn btn-primary' onclick='bkcleanvs(" + id + ")'>BackUp and Clean</button>" +
                    "<button type='button' class='btn btn-primary' onclick='restorevs(" + id + ")'>Restore</button>" +
                    "<button type='button' class='btn btn-primary' onclick='confirmdeletevs(" + id + ")'>Delete</button>" +
                    "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";

                $('#codeareaDiv').html(result1);
                $('#buttonDiv').html(buttons);
//	           var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('codearea'), {
//	        	   lineNumbers: true,
//	               matchBrackets: true,
//	               mode: "application/x-httpd-php",
//	               indentUnit: 4,
//	               indentWithTabs: true
//	           });
            }
        });

    });
}
function restorevs(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'restorevs',
                task: 'restorevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == "fail") {
                    showDialogue("Restore failed, please try again", "Notice", "OK");
                }
                else {
                    showDialogue("Restore success", "Success", "OK");
                    viewFiledetail(id);
                }
            }
        })
    })
}
function deletevs(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'deletevs',
                task: 'deletevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showDialogue("Delete success", "Success", "OK");
                    $('#filecontentModal').modal('hide');
                    $('#scanreportTable').dataTable().api().ajax.reload();
                }
                else {
                    showDialogue("Delete failed, please try again", "Notice", "OK");
                }
            }
        })
    })
}
function bkcleanvs(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'bkcleanvs',
                task: 'bkcleanvs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == "fail") {
                    showDialogue("Backup failed, please try again", "Notice", "OK");
                } else {
                    showDialogue("Backup success", "Success", "OK");
                    var content = data.data;
                    $('#codeareaDiv').html(content);
                }
            }
        })
    })
}

function virusbackup(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'backupvs',
                task: 'backupvs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == "success") {
                    showDialogue("Backup success", "Success", "OK");
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
function confirmdeletevs(id) {
    bootbox
        .dialog({
            message: "This will delete virus in your database and can not be restored later, press yes to proceed",
            title: "Notice",
            buttons: {
                success: {
                    label: "Yes",
                    callback: function () {
                        deletevs(id);
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
}
