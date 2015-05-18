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
    tinymce.init({
        selector: "textarea.tinymce",
        menubar: false,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code ",
            "insertdatetime table contextmenu paste"
        ],
        height: '500',
        toolbar: "bold italic strikethrough bullist numlist blockquote hr alignleft aligncenter alignright alignjustify link unlink code image media | fullscreen"
    });

    $("#emailEditorForm").submit(function () {
        tinyMCE.triggerSave();
        var postdata = $("#emailEditorForm").serialize();
        postdata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: postdata,
            success: function (data) {
                showDialogue(
                    O_EMAIL_TEMP_SAVE,
                    O_SUCCESS, O_OK);
                document.getElementById('emailEditorForm').style.display = "none";
                document.getElementById('adminBody').style.display = "block";
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
});

function emailEditor() {
    jQuery(document).ready(function ($) {

        document.getElementById('emailEditorForm').style.display = "block";
        document.getElementById('adminBody').style.display = "none";

    })
}
function addAdmin() {
    jQuery(document).ready(function ($) {
        document.getElementById('emailEditorForm').style.display = "none";
        document.getElementById('adminBody').style.display = "block";
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
    document.getElementById('emailEditorForm').style.display = "none";
    document.getElementById('adminBody').style.display = "block";
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
                    message: O_DELETE_CONFIRM_DESC,
                    title: O_CONFIRM,
                    buttons: {
                        success: {
                            label: O_YES,
                            callback: function () {
                                deleteAdminAjax(id);
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
                showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
            }
        })
}
