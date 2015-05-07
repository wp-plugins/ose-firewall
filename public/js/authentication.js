var controller = "authentication";

var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: {
            option: option,
            controller: controller,
            action: 'oauth',
            task: 'oauth',
            type: 'dropbox',
            reload: 'yes',
            centnounce: $('#centnounce').val()
        },
        success: function (data) {

            document.getElementById('dropbox_authorize').onclick = function () {
                oauth_step2(data);
            };

        }
    })
})
function dropbox_oauth() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'oauth',
                task: 'oauth',
                type: 'dropbox',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data == "wordpress") {
                window.location = 'admin.php?page=ose_fw_advancedbackup';
                window.location.reload;
                } else {
                    window.location = 'index.php?option=com_ose_firewall&view=advancedbackup';
                    window.location.reload;
                }
            }
        })
    })
}
function oauth_step2(url) {
    window.open(url);

    document.getElementById('dropbox_authorize').onclick = function () {
        dropbox_oauth();
    };
    document.getElementById('dropbox_authorize').innerHTML = "Continue";
}