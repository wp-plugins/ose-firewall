var url = ajaxurl;
var option = 'com_ose_firewall';

function showAutoUpdateDialogue (message, title, buttonLabel, Updateurl, upgradeplugin, activateurl) {
	bootbox.dialog({
		message: message,
		title: title,
		buttons: {
			main: {
				label: buttonLabel,
			  	className: "btn-primary btn-alt",
				callback: function () {
					 showLoadingStatus ('Updating...');
					  runAutoUpdate(Updateurl, upgradeplugin, activateurl);
				}
			}
		}
	});

 return false; // avoid to execute the actual submit of the form.
}

function runAutoUpdate(url, plugin, activateurl) {
	jQuery(document).ready(function($){
		jQuery.ajax ({
	        url: url,
	        type: "POST",
	        data: { 
	        	option : option,
	        	controller:controller, 
	        	plugin: plugin,
	        	action :'upgrade-plugin'	        			
	        },
	        success: function(output) {
	        	if (activateurl != null && document.readyState === "complete"){ //only run this part in the wordpress version where we have to activate the plugin after updating
	        		hideLoadingStatus ();
	        		activateWordpressPlugin(activateurl);
	        	} else {
	        		hideLoadingStatus ();
	        		//location.reload();
	        	}
	        }
		});
	});
}

function activateWordpressPlugin (activateurl){
	jQuery.ajax ({
        url: activateurl,
        type: "POST", 
		success: function (output) {
			showLoadingStatus ('Activating plugin...')
			hideLoadingStatus ();
        	location.reload();
		}
	});
}

function hideLoadingStatus () {
	jQuery(document).ready(function($){
		setTimeout(function() 
		{
		  $('body').waitMe("hide");
		}, 800); 
	});
}

function showLoadingStatus (text) {
	if (text =='')
	{
		text = 'Please wait...';
	}
	jQuery(document).ready(function($){
		$('body').waitMe({
	        effect : 'facebook',
	        text : text,
	        bg : 'rgba(255,255,255,0.7)',
	        color : '#1BBC9B'
	    });
	});
}