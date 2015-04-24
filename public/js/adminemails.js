var controller = "adminemails";
var option = "com_ose_firewall";
jQuery(document).ready(function ($) {

    $('#adminTable').dataTable({

        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                d.option = option;
                d.controller = controller;
                d.action = 'getAdminList';
                d.task = 'getAdminList';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [{
            "data": "ID"
        }, {
            "data": "Name"
        }, {
            "data": "Email"
        }, {
            "data": "Status"
        }, {
            "data": "Domain"
        }, {
            "data": null,
            "defaultContent": " ",
            "orderable": false,
            "searchable": false
        }]
    });
    $('#checkbox').prop('checked', false);

    $('#adminTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#checkbox').click(function () {
        if ($('#checkbox').is(':checked')) {
            $('#adminTable tr').addClass('selected');
        } else {
            $('#adminTable tr').removeClass('selected');
        }
    });
});
function addAdmin() {
    jQuery(document).ready(function ($) {
        $('#addAdminModal').modal();
    })
}
function addDomain() {
    jQuery(document).ready(function ($) {
        $('#addAdminModal').modal('hide');
        $('#addDomainModal').modal();
    });
}
function getDomain() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getDomain',
                task: 'getDomain',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                var i = 0;
                var text;
                while (i < data.length) {
                    text += data[i];
                    i++;
                }
                $('#admin-domain').html(text);
            }
        })
    });
}
function changeStatus(status, id) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'changeStatus',
                task: 'changeStatus',
                status: status,
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {

                if (status == 0) {
                    document.getElementById(id).onclick = function () {
                        changeStatus(1, id);
                    };
                    document.getElementById(id).innerHTML = '<div class="fa fa-times">';
                } else {
                    document.getElementById(id).onclick = function () {
                        changeStatus(0, id);
                    };
                    document.getElementById(id).innerHTML = '<div class="fa fa-check">';
                }
            }
        })
    });
}
function deleteAdminAjax(id) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'deleteAdmin',
                task: 'deleteAdmin',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                $('#adminTable').dataTable().api().ajax.reload();
            }
        });
    })
}
function deleteAdmin() {
    jQuery(document).ready(
        function ($) {
            ids = $('#adminTable').dataTable().api().rows('.selected').data();
            id = [];
            index = 0;
            for (index = 0; index < ids.length; ++index) {
                id[index] = (ids[index]['ID']);
            }
            if (ids.length > 0) {
                bootbox.dialog({
                    message: "Are you sure to delete selected administrators, press yes to proceed",
                    title: "Confirm",
                    buttons: {
                        success: {
                            label: "Yes",
                            callback: function () {
                                deleteAdminAjax(id);
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
                showDialogue(
                    "Please select administrators first!",
                    "Notice!", 'OK');
            }
        })
}
