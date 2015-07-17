/**
 * Created by Kang on 27/04/15.
 */
jQuery(document).ready(function ($) {
    //------------- Init our plugin -------------//
    $('body').appStart({
        //main color scheme for template
        //be sure to be same as colors on main.css or custom-variables.less
        colors: {
            white: '#fff',
            dark: '#2C3E50',
            red: '#e74c3c',
            blue: '#1E8BC3',
            green: '#3FC380',
            yellow: '#F39C12',
            orange: '#E87E04',
            purple: '#9A12B3',
            pink: '#f78db8',
            lime: '#a8db43',
            mageta: '#e65097',
            teal: '#1BBC9B',
            cream: "#2ECC71",
            black: '#000',
            brown: '#EB974E',
            gray: '#ECF0F1',
            graydarker: '#95A5A6',
            graydark: '#D2D7D3',
            graylight: '#EEEEEE',
            graylighter: '#F2F1EF',
            linechart1: '#3fc3a8',
            linechart2: '#ed7a53',
            linechart3: '#9FC569',
            linechart4: '#bbdce3',
            linechart5: '#9a3b1b',
            linechart6: '#5a8022',
            linechart7: '#2c7282',
            piedark: '#ECF0F1',
            piered: '#fbccbf',
            piegreen: '#b1f8b1',
            pieblue: '#d2e4fb',
            pieteal: '#c3e5e5',
            piepurple: '#dec1f5',
            pieorange: '#f9d7af',
            pielime: '#cfed93',
            royalblue: '#0047ab'
        },
        sizes: {
            piesize: 100,
            pielinewidth: 20
        },
        header: {
            fixed: true //fixed header
        },
        panels: {
            refreshIcon: 'im-spinner12',//refresh icon for panels
            toggleIcon: 'im-minus',//toggle icon for panels
            collapseIcon: 'im-plus',//colapse icon for panels
            closeIcon: 'im-close', //close icon
            showControlsOnHover: false,//Show controls only on hover.
            loadingEffect: 'facebook',//loading effect for panels. bounce, none, rotateplane, stretch, orbit, roundBounce, win8, win8_linear, ios, facebook, rotation.
            rememberSortablePosition: true //remember panel position
        }
    });
    removejscssfile("template.css", "css");
    //$('link[rel=stylesheet][href~="templates/isis/css/template.css"]').remove();
});