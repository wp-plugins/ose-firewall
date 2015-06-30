var controller = "authentication";
var option = "com_ose_firewall";

//onedrive authentication
function onedrive_logout() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'onedrive_logout',
                task: 'onedrive_logout',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (cms == 'wordpress') {
                    window.location = 'admin.php?page=ose_fw_authentication';
                    window.location.reload;
                } else {
                    window.location = 'index.php?option=com_ose_firewall&view=authentication';
                    window.location.reload;
                }
            }
        })
    })
}

// dropbox authentication
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
                //document.getElementById('dropbox_authorize').innerHTML = O_DROPBOX_LOGOUT;
                //document.getElementById('dropbox_authorize').onclick = function () {
                //    dropbox_logout();
                //};
                if (data == "wordpress") {
                window.location = 'admin.php?page=ose_fw_authentication';
                window.location.reload;
                } else {
                    window.location = 'index.php?option=com_ose_firewall&view=authentication';
                    window.location.reload;
                }
            }
        })
    })
}
function dropbox_logout() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'dropbox_logout',
                task: 'dropbox_logout',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                //     document.getElementById('dropbox_authorize').innerHTML = O_DROPBOX_AUTHETICATION;
                if (cms == 'wordpress') {
                    window.location = 'admin.php?page=ose_fw_authentication';
                    window.location.reload;
                } else {
                    window.location = 'index.php?option=com_ose_firewall&view=authentication';
                    window.location.reload;
                }
            }
        })
    })
}
function initial_dropboxauth (){
    jQuery(document).ready(function ($) {
        showLoading('Getting Request Token....');
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
                hideLoading();
                bootbox.dialog({
                    message: O_DROPBOX_AUTHO_DESC2,
                    title: O_DROPBOX_AUTHO,
                    buttons: {
                        main: {
                            label: 'OK',
                            className: "btn-primary btn-alt",
                            callback: function () {
                                showLoadingStatus('Getting Access Token...');
                                oauth_step2(data);
                            }
                        }
                    }
                });

            }
        })
    });
}
function oauth_step2(url) {
    window.open(url);
    hideLoading();
    //document.getElementById('dropbox_authorize').innerHTML = "Continue";
    bootbox.dialog({
        message: O_DROPBOX_AUTHO_DESC3,
        title: O_DROPBOX_AUTHO,
        buttons: {
            main: {
                label: 'OK',
                className: "btn-primary btn-alt",
                callback: function () {
                    showLoadingStatus('Final Authentication...');
                    dropbox_oauth();
                }
            }
        }
    });
}

// Google authentication

function googledrive_logout() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'googledrive_logout',
                task: 'googledrive_logout',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (cms == 'wordpress') {
                    window.location = 'admin.php?page=ose_fw_authentication';
                    window.location.reload;
                } else {
                    window.location = 'index.php?option=com_ose_firewall&view=authentication';
                    window.location.reload;
                }
            }
        })
    })
}