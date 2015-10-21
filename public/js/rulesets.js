var url = ajaxurl; 
var controller = "rulesets";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
    tinymce.init({
        selector: "textarea.tinymce",
        menubar : false,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code ",
            "insertdatetime table contextmenu paste"
        ],
        height: 200,
        toolbar: "undo redo | bold italic blockquote hr| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

    if (window.location.hash == '#migrate') {
        document.getElementById('hehe').className = 'inactive';
        document.getElementById('haha').className = 'active';
        $('#tabs').tabs({
            active: 2
        });
    }
    var rulesetsDataTable = $('#rulesetsTable').dataTable( {
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function ( d ) {
                d.option = option;
                d.controller = "rulesets";
                d.action = 'getRulesets';
                d.task = 'getRulesets';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
                { "data": "id" },
                { "data": "rule" },
                { "data": "attacktype" },
                { "data": "action" },
                { "data": "checkbox", sortable: false }
        ]
    });
    $('#rulesetsTable tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#save-button-fw').on( 'click', function () {
        tinyMCE.triggerSave();
    });
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value="-1"></option><option value="1">Active</option><option value="0">InActive</option></select></label>');
    statusFilter.appendTo($("#rulesetsTable_filter")).on( 'change', function () {
        var val = $('#statusFilter');
         rulesetsDataTable.api().column(3)
            .search( val.val(), false, false )
            .draw();
    });

    if ($("centroraGASwitch").is(':checked')) {
            $("hidden-QRcode").attr("style", "display: block;");
        } else {
            $("hidden-QRcode").attr("style", "display: none;");
        }


    if($('#blockIP403').is(':checked')) {
        $("#customBanpageDiv").attr("style", "display: none;");
        $("#customBanURLDiv").attr("style", "display: none;");

    }else if($('#blockIPban').is(':checked')) {
        $("#customBanpageDiv").attr("style", "display: block;");
        $("#customBanURLDiv").attr("style", "display: block;");
    }
    if ($("bf_status").is(':checked')) {
        $("#bf-config").attr("style", "display: inline;");
    }

    $('#strongPassword').change(function () {
        if($(this).is(":checked") && cms == 'joomla') {
            checkPassword();
        }
    });

});

function changeItemStatus(id, status)
{
    AppChangeItemStatusRuleset(id, status, '#rulesetsTable', 'changeRuleStatus', 'rulesets');
}

function changeItemStatusAd(id, status) {
    AppChangeItemStatusRuleset(id, status, '#AdvrulesetsTable', 'changeRuleStatus', 'advancerulesets');
}
function toggleDisabled(enable){
    jQuery(document).ready(function ($) {
        if (enable == 1){
            $( "[id^=customBan]" ).slideDown({ duration: 300 });
            $("#customBanpage").attr("style", "display: none;");
        }else{
            $( "[id^=customBan]" ).slideUp({ duration: 300});
        }
    });
}

function checkPassword() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'scanconfig',
                action: 'checkPassword',
                task: 'checkPassword',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                var data = JSON.parse(data);
                document.getElementById("mpl").value = data.minimum_length;
                document.getElementById("pmi").value = data.minimum_integers;
                document.getElementById("pms").value = data.minimum_symbols;
                document.getElementById("pucm").value = data.minimum_uppercase;
                if (data.minimum_length < 8 && data.minimum_integers < 2 && data.minimum_symbols < 1 && data.minimum_uppercase < 1) {
                    document.getElementById("password-warning-message").innerHTML = O_PASSWORD_STRENGTH_WEAK;
                } else {
                    document.getElementById("password-warning-message").innerHTML = O_PASSWORD_STRENGTH_STRONG;
                }
                $('#strongPasswordModal').modal();
            }
        });
    });
}
function defaultJoomla() {
    document.getElementById("mpl").value = 4;
    document.getElementById("pmi").value = 0;
    document.getElementById("pms").value = 0;
    document.getElementById("pucm").value = 0;
    document.getElementById("password-warning-message").innerHTML = O_PASSWORD_STRENGTH_WEAK;
}
function defaultPassword() {
    document.getElementById("mpl").value = 8;
    document.getElementById("pmi").value = 2;
    document.getElementById("pms").value = 1;
    document.getElementById("pucm").value = 1;
    document.getElementById("password-warning-message").innerHTML = O_PASSWORD_STRENGTH_STRONG;
}
function showGDialog (){
    if (document.getElementById("googleVerificationSwitch").checked == true) {
        showDialogue(O_GDIALOG_MSG, O_GDIALOG_TITLE, O_OK, '');
    }
}
function showbfconfig() {
    jQuery(document).ready(function ($) {
        if (document.getElementById("bf_status").checked == true) {
            $("#bf-config").slideDown({duration: 300});
        } else {
            $("#bf-config").slideUp({duration: 300});
        }
    })
}
function showSecret() {
    jQuery(document).ready(function ($) {
        if (document.getElementById("centroraGASwitch").checked == true) {
            $("#hidden-QRcode").slideDown({ duration: 300 });
            // showGoogleSecret();
        } else {
            $("#hidden-QRcode").slideUp({ duration: 300 });
        }
    })
}
function showGoogleSecret() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'scanconfig',
                action: 'showGoogleSecret',
                task: 'showGoogleSecret',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                document.getElementById('shhsecret').innerHTML = data.secret;
                document.getElementById('shhqrcode').innerHTML = data.QRcode;
            }
        });
    });
}

//******************** Advanced rule datatable **********************


jQuery(document).ready(function ($) {
    var adrulesetsDataTable = $('#AdvrulesetsTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                d.option = option;
                d.controller = "advancerulesets";
                d.action = 'getRulesets';
                d.task = 'getRulesets';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
            {"data": "id"},
            {"data": "description"},
            {"data": "attacktype"},
            {"data": "impact"},
            {"data": "action"},
            {"data": "checkbox", sortable: false}
        ]
    });
    $('#AdvrulesetsTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    var adstatusFilter = $('<label>Status: <select name="adstatusFilter" id="adstatusFilter"><option value="-1"></option><option value="1">Active</option><option value="0">InActive</option></select></label>');
    adstatusFilter.appendTo($("#AdvrulesetsTable_filter")).on('change', function () {
        var val = $('#adstatusFilter');
        adrulesetsDataTable.api().column(4)
            .search(val.val(), false, false)
            .draw();
    });
});


function downloadRequest(type) {
    jQuery(document).ready(function ($) {
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'downloadRequest',
                task: 'downloadRequest',
                type: type,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.status == 'Error') {
                    showLoading(data.message);
                    hideLoading(2500);
                } else {
                    downloadSQL(type, data.downloadKey, data.version);
                }
            }
        });
    });
}
function downloadSQL(type, downloadKey, version) {
    jQuery(document).ready(function ($) {
        showLoading('Signature is being updated, please wait...');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'downloadSQL',
                task: 'downloadSQL',
                type: type,
                downloadKey: downloadKey,
                version: version,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                showLoading(data.result);
                hideLoading();
                $('#AdvrulesetsTable').dataTable().api().ajax.reload();
            }
        });
    });
}
