var controller ='news';

function toggleChangelist(listnum){
    jQuery(document).ready(function($){
        var changelist = $('#changelist' + listnum);
        var showmenu = $('#btnshowmenu' + listnum);
        if( changelist.hasClass('collapsed') ) {
            changelist.slideDown({duration: 300});
            changelist.removeClass('collapsed').addClass('expanded');
            showmenu.attr('title', 'Hide Changelog');
        } else if (changelist.hasClass('expanded') ){
            changelist.slideUp({duration: 300});
            changelist.removeClass('expanded').addClass('collapsed');
            showmenu.attr('title', 'Show Changelog');
        }
    });
}

