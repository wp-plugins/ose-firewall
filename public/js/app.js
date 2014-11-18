var url = ajaxurl; 
var option = "com_ose_firewall";

// make console.log safe to use
window.console||(console={log:function(){}});

//Internet Explorer 10 in Windows 8 and Windows Phone 8 fix
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
  var msViewportStyle = document.createElement('style')
  msViewportStyle.appendChild(
    document.createTextNode(
      '@-ms-viewport{width:auto!important}'
    )
  )
  document.querySelector('head').appendChild(msViewportStyle)
}

var matched, browser;

jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}

jQuery.browser = browser;

//Android stock browser
var nua = navigator.userAgent
var isAndroid = (nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1 && nua.indexOf('Chrome') === -1)
if (isAndroid) {
  $('select.form-control').removeClass('form-control').css('width', '100%')
}

function showDialogue (message, title, buttonLabel, className) {
	bootbox.dialog({
			message: message,
			title: title,
			className: className,
			buttons: {
				success: {
				  label: buttonLabel,
				  className: "btn-primary btn-alt"
				}
			}
	});
}

function encodeAllIDs(selections)
{
	var i=0;
	ids = new Array();
	for (i = 0; i < selections.length; i++)
	{
      ids [i] = parseInt(selections[i].id);
	}
	ids = JSON.stringify(ids);
	return ids; 
}

function showLoading (text = 'Please wait...') {
	jQuery(document).ready(function($){
		$('body').waitMe({
	        effect : 'facebook',
	        text : text,
	        bg : 'rgba(255,255,255,0.7)',
	        color : '#1BBC9B'
	    });
	});
}

function hideLoading () {
jQuery(document).ready(function($){
	setTimeout(function() 
	{
	  $('body').waitMe("hide");
	}, 800);
});
}

function redirectTut (url) {
	window.open(url, '_blank');
}

function AppChangeItemStatus(id, status, table, task)
{
	jQuery(document).ready(function($){
		showLoading ();
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:task,
		    		task:task,
		    		id:id,
		    		status:status,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	           showLoading(data.result);  
	           hideLoading ();
	           $(table).dataTable().api().ajax.reload();
	        }
	      });
	});
}

function AppChangeBatchItemStatus (action, table) {
	jQuery(document).ready(function($){
		showLoading ();
		ids= encodeAllIDs($(table).dataTable().api().rows('.selected').data());
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:action,
		    		task:action,
		    		ids:ids,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	           hideLoading ();
	           showDialogue (data.result, data.status, 'OK');
	           $(table).dataTable().api().ajax.reload();
	        }
	      });
	});
}

function AppRemoveItems (action) {
	bootbox.dialog({
		message: O_DELETE_CONFIRM_DESC,
		title: O_DELETE_CONFIRM,
		buttons: {
			success: {
				label: "Yes",
				className: "btn-success",
				callback: function(result) {
					changeBatchItemStatus(action);
				}
			},
			main: {
				label: "No",
				className: "btn-danger",
				callback: function(result) {
					this.close();
				}
			}
		}
	}); 
}

function AppRemoveAllItems (task, table) {
	bootbox.dialog({
		message: O_DELETE_CONFIRM_DESC,
		title: O_DELETE_CONFIRM,
		buttons: {
			success: {
				label: "Yes",
				className: "btn-success",
				callback: function(result) {
					showLoading ();
					jQuery(document).ready(function($){
						$.ajax({
					        type: "POST",
					        url: url,
					        dataType: 'json',
						    data: {
						    		option:option, 
						    		controller:controller,
						    		action:task,
						    		task:task,
						    		centnounce:$('#centnounce').val()
						    },
					        success: function(data)
					        {
					        	hideLoading ();
					        	showDialogue (data.result, data.status, 'OK');
					            $(table).dataTable().api().ajax.reload();
					        }
					      });
					});
				}
			},
			main: {
				label: "No",
				className: "btn-danger",
				callback: function(result) {
					this.close();
				}
			}
		}
	}); 
}

function AppRunAction (action, table) {
	jQuery(document).ready(function($){
		showLoading ();
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:action,
		    		task:action,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	           hideLoading ();
	           showDialogue (data.result, data.status, 'OK');
	           $(table).dataTable().api().ajax.reload();
	        }
	      });
	});
}

function checkphpConfig () {
jQuery(document).ready(function($){
	showLoading();
    $.ajax({
           type: "POST",
           url: url,
           data: $("#php-configuraton-form").serialize(), // serializes the form's elements.
           success: function(data)
           {
        	   hideLoading();
        	   data = jQuery.parseJSON(data);
       		   showDialogue (data.config, 'Recommended Setting', 'OK','phpconfig');
           }
         });
    return false; // avoid to execute the actual submit of the form.
});   
}

//doc ready function
jQuery(document).ready(function($){
 	//------------- Init our plugin -------------//
 	$('body').appStart({
        //main color scheme for template
        //be sure to be same as colors on main.css or custom-variables.less
        colors : {
            white: '#fff',
            dark: '#2C3E50',
            red: '#EF4836',
            blue: '#1E8BC3',
            green: '#3FC380',
            yellow: '#F39C12',
            orange: '#E87E04',
            purple: '#9A12B3',
            pink: '#f78db8',
            lime: '#a8db43',
            mageta: '#e65097',
            teal: '#1BBC9B',
            black: '#000',
            brown: '#EB974E',
            gray: '#ECF0F1',
            graydarker: '#95A5A6',
            graydark: '#D2D7D3',
            graylight: '#EEEEEE',
            graylighter: '#F2F1EF'
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
 	$('link[rel=stylesheet][href~="templates/isis/css/template.css"]').remove();
});	

jQuery(document).ready(function($){
	$("#configuraton-form").submit(function() {
		showLoading();
		var data = $("#configuraton-form").serialize();
		data += '&centnounce='+$('#centnounce').val();
        $.ajax({
               type: "POST",
               url: url,
               data: data, // serializes the form's elements.
               success: function(data)
               {
            	   data = jQuery.parseJSON(data);
            	   if (data.status == 'SUCCESS')
            	   {
            		   showLoading(data.result);
            		   hideLoading();
                   }
            	   else
            	   {
            		   showDialogue (data.result, data.status, 'OK');   
            	   }
               }
             });
        return false; // avoid to execute the actual submit of the form.
    });
	
	$("#seo-configuraton-form").submit(function() {
		showLoading();
		var data = $("#seo-configuraton-form").serialize();
		data += '&centnounce='+$('#centnounce').val();
        $.ajax({
               type: "POST",
               url: url,
               data: data, // serializes the form's elements.
               success: function(data)
               {
            	   data = jQuery.parseJSON(data);
            	   if (data.status == 'SUCCESS')
            	   {
            		   showLoading(data.result);
            		   hideLoading();
                   }
            	   else
            	   {
            		   showDialogue (data.result, data.status, 'OK');   
            	   }
               }
             });
        return false; // avoid to execute the actual submit of the form.
    });
	
	$("#admin-configuraton-form").submit(function() {
		showLoading();
		var data = $("#admin-configuraton-form").serialize();
		data += '&centnounce='+$('#centnounce').val();
        $.ajax({
               type: "POST",
               url: url,
               data: data, // serializes the form's elements.
               success: function(data)
               {
            	   data = jQuery.parseJSON(data);
	         	   if (data.status == 'SUCCESS')
	         	   {
	         		   showLoading(data.result);
	         		   hideLoading();
	         		   setTimeout(function(){
		           		   window.location.reload(1);
		           		}, 5000);
	                }
	         	   else
	         	   {
	         		   showDialogue (data.result, data.status, 'OK');   
	         	   }
	            }
             });
        return false; // avoid to execute the actual submit of the form.
    });
	
	$("#scan-form").submit(function() {
		showLoading();
		var data = $("#scan-form").serialize();
		data += '&centnounce='+$('#centnounce').val();
        $.ajax({
               type: "POST",
               url: url,
               data: data, // serializes the form's elements.
               success: function(data)
               {
            	   hideLoading();
            	   data = jQuery.parseJSON(data);
           		   if (data.cont)
           		   {
           			scanAntivirus (-1, 'vsscan', [],[]);
           		   }	   
               }
             });
        return false; // avoid to execute the actual submit of the form.
    });
});