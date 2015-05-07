var controller = "scanreport";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    var scanreportDataTable = $('#scanreportTable').dataTable({
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
            {"data": "checked"},
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
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value=""></option><option value="0 ">No action</option><option value="1">Cleaned</option><option value="2">Quarantined</option></select></label>');
    statusFilter.appendTo($("#scanreportTable_filter")).on('change', function () {
        var val = $('#statusFilter');
        scanreportDataTable.api().column(3)
            .search(val.val(), false, false)
            .draw();
        if (val.val() == "2") {
            document.getElementById("delete-button").style.display = 'inline';
        }
        else {
            document.getElementById("delete-button").style.display = 'none';
        }
    });
    $('#filecontentModal').on('hidden.bs.modal', function () {
        $('#scanreportTable').dataTable().api().ajax.reload();
    });
});

function batchquarantine() {
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
                    action: 'batchqt',
                    task: 'batchqt',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == 1) {
                        showDialogue("Quarantine success", "Success", "OK");
                        $('#scanreportTable').dataTable().api().ajax.reload();
                    } else {
                        showDialogue("Quarantine failed, please try again", "Notice", "OK");
                    }
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            hideLoading();
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
                        showDialogue("Clean success", "Success", "OK");
                        $('#scanreportTable').dataTable().api().ajax.reload();
                    } else {
                        showDialogue("Clean failed, please try again", "Notice", "OK");
                    }
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            hideLoading();
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
                        $('#scanreportTable').dataTable().api().ajax.reload();
                    } else {
                        showDialogue("Restore failed, please try again", "Notice", "OK");
                    }
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            hideLoading();
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
function viewFiledetail(id, status) {
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
                if (status == 0) {
                    var buttons =
                        "<button type='button' class='btn btn-primary' onclick='bkcleanvs(" + id + ", 1" + ")'>Clean</button>" +
                        "<button type='button' class='btn btn-primary' onclick='quarantinevs(" + id + ", 2" + ")'>Quarantine</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
                } else if (status == 1) {
                    var buttons =
                        "<button type='button' class='btn btn-primary' onclick='restorevs(" + id + ", 0" + ")'>Restore</button>" +
                        "<button type='button' class='btn btn-primary' onclick='quarantinevs(" + id + ", 2" + ")'>Quarantine</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
                }
                else {
                    var buttons =
                        "<button type='button' class='btn btn-primary' onclick='restorevs(" + id + ", 0" + ")'>Restore</button>" +
                        "<button type='button' class='btn btn-primary' onclick='confirmdeletevs(" + id + ")'>Delete</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
                }
                $('#codeareaDiv').html(result1);
                $('#buttonDiv').html(buttons);
            }
        });

    });
}
function restorevs(id, status) {
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
                if (data.data == "success") {
                    showDialogue("Restore success", "Success", "OK");
                    viewFiledetail(id, status);
                }
                else {
                    showDialogue("Restore failed, please try again", "Notice", "OK");
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
                }
                else {
                    showDialogue("Delete failed, please try again", "Notice", "OK");
                }
            }
        })
    })
}
function quarantinevs(id, status) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'quarantinevs',
                task: 'quarantinevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showDialogue("Quarantine success", "Success", "OK");
                    viewFiledetail(id, status);
                } else {
                    showDialogue("Quarantine failed, please try again", "Notice", "OK");
                }
            }
        })
    })
}
function bkcleanvs(id, status) {
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
                if (data.data == "success") {
                    showDialogue("Clean success", "Success", "OK");
                    //  $('#filecontentModal').modal('hide');
                    //   $('#scanreportTable').dataTable().api().ajax.reload();
                    viewFiledetail(id, status);
                } else {
                    showDialogue("Clean failed, please try again", "Notice", "OK");
                    //var content = data.data;
                    //$('#codeareaDiv').html(content);
                }
            }
        })
    })
}
function confirmdeletevs(id, status) {
    bootbox
        .dialog({
            message: "This will delete virus compeletely and can not be restored later, press yes to proceed",
            title: "Notice",
            buttons: {
                success: {
                    label: "Yes",
                    callback: function () {
                        deletevs(id, status);
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
