var controller = "cronjobs";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    function setTimeDropDown (){
        var CurrentTimezoneOffset = -(new Date().getTimezoneOffset() / 60); // get user tzoffset
        var UserSverOffset = 10 - CurrentTimezoneOffset; // get time difference(hrs) with centrora servers
        $('#vscanusertime').text('GMT: ' + CurrentTimezoneOffset);
        $('#backupusertime').text('GMT: ' + CurrentTimezoneOffset);
        for (var hr = 0; hr < 24; hr++) {
            var olddate = new Date(2000, 6, 15, hr, 00, 0, 0); // create a temp calculate date of Jun 15/2000, hr:00:00am
            var subbed = new Date(olddate - UserSverOffset * 60 * 60 * 1000); // subtract 10 hours
            var newtime = subbed.getHours() + (subbed.getMinutes() / 60)
            var concat0 = (hr < 10) ? "0" : "";
            var option = $('<option></option>').attr("value", newtime).text(concat0 + hr + ':00');
            $("#vscancusthours").append(option.clone());
            $("#backupcusthours").append(option);
        }
        $("#vscancusthours").val(+$("#vscansvrusertime").val())
        $("#backupcusthours").val(+$("#backupsvrusertime").val()) // select hour on dropdown set in centrora server
    }
    function setvisualdisabled (){
        $('#vscanweekdays').attr('disabled', !vscanonoffswitch.checked);
        $('#vscancusthours').attr('disabled', !vscanonoffswitch.checked);
        $('#backupweekdays').attr('disabled', !backuponoffswitch.checked);
        $('#backupcusthours').attr('disabled', !backuponoffswitch.checked);
        $('#cloudbackuptype').attr('disabled', !backuponoffswitch.checked);
    }
    function iconload(){
        var classname;
        switch ($('#cloudbackuptype').val()) {
            case "1":
                classname = "fa fa-desktop";
                break;
            case "2":
                classname = "fa fa-dropbox";
                break;
            case "3":
                classname = "fa fa-windows";
                break;
            default:
                classname = "";
        }
        $('#cloudbackupicon').removeClass().addClass(classname);
    }
    setTimeDropDown ();
    setvisualdisabled ();
    iconload();
    $('#vscanenabled').val(this.checked ? 1 : 0);
    //enable/disable inputs based on toggle switch
    $('#vscanonoffswitch').on('change', function(){
        $('#vscanenabled').val(this.checked ? 1 : 0);
        setvisualdisabled ();
    });
    $('#backupenabled').val(this.checked ? 1 : 0);
    //enable/disable inputs based on toggle switch
    $('#backuponoffswitch').on('change', function(){
        $('#backupenabled').val(this.checked ? 1 : 0);
        setvisualdisabled ();
    });
    $('#cloudbackuptype').change(function () {
        iconload();
    });
    $('#cronjobs-form').submit(function () {
        //enable disabled inputs for submit
        $('input, select').attr('disabled', false);
        showLoading();
        // submit the form
        $(this).ajaxSubmit({
            url: url,
            type: "POST",
            success: function (data) {
                data = jQuery.parseJSON(data);
                if (data.success == true) {
                    if (data.status == 'Error') {
                        hideLoading();
                        showDialogue(data.message, data.status, 'OK');
                    }
                    else {
                        showLoading(data.message);
                        hideLoading();
                    }
                }
                else {
                    hideLoading();
                    showDialogue(data.result, data.status, 'OK');
                }
                setvisualdisabled ();
            }
        });
        return false;
    });
    $('#backup-cronjobs-form').submit(function () {
        //enable disabled inputs for submit
        $('input, select').attr('disabled', false);
        showLoading();
        // submit the backup-cronjobs-form
        $(this).ajaxSubmit({
            url: url,
            type: "POST",
            success: function (data) {
                data = jQuery.parseJSON(data);
                if (data.success == true) {
                    if (data.status == 'Error') {
                        hideLoading();
                        showDialogue(data.message, data.status, 'OK');
                    }
                    else {
                        showLoading(data.message);
                        hideLoading();
                    }
                }
                else {
                    hideLoading();
                    showDialogue(data.result, data.status, 'OK');
                }
                setvisualdisabled ();
            }
        });
        return false;
    });
});


