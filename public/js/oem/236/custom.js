jQuery(document).ready(function ($) {
    $('body').appStart({
        //main color scheme for template
        //be sure to be same as colors on main.css or custom-variables.less

        colors: {
            white: '#fff',
            dark: '#89bb30',
            red: '#89bb30',
            blue: '#89bb30',
            green: '#89bb30',
            yellow: '#F39C12',
            orange: '#89bb30',
            purple: '#89bb30',
            pink: '#f78db8',
            lime: '#89bb30',
            mageta: '#e65097',
            cream: "#f2f7f9",
            teal: '#89bb30',
            black: '#000',
            brown: '#EB974E',
            gray: '#d7d8d9',
            textcolor: '#5a5e63',
            graydarker: '#95A5A6',
            graydark: '#D2D7D3',
            graylight: '#EEEEEE',
            graylighter: '#F2F1EF',
            linechart1: '#89bb30',
            linechart2: '#9A12B3',
            linechart3: '#1E8BC3',
            linechart4: '#89bb30',
            linechart5: '#89bb30',
            linechart6: '#89bb30',
            linechart7: '#89bb30',
            piedark: '#d7d8d9',
            piered: '#d7d8d9',
            piegreen: '#d7d8d9',
            pieblue: '#d7d8d9',
            pieteal: '#d7d8d9',
            piepurple: '#d7d8d9',
            pieorange: '#d7d8d9',
            pielime: '#d7d8d9'
        },
        sizes: {
            piesize: 180,
            pielinewidth: 40
        }
    });
    $("#changePasscode-form").submit(function () {
        var postdata = $("#changePasscode-form").serialize();
        postdata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: postdata,
            success: function (data) {
                if (data.status == 'SUCCESS') {
                    alert(data.result);
                    $('#changePasscodeModal').modal('hide');
                } else {
                    alert(data);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
});
function changePasscodeModal() {
    jQuery(document).ready(function ($) {
        $('#changePasscodeModal').modal();
    })
}


