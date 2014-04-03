var uninstallController = "uninstall";
var task = "createTables";
var uninstallTask = "uninstallTables";

function oseCheckIPValidity()
{
	var ip_startCmp = Ext.getCmp('ip_start');
	var ip_endCmp = Ext.getCmp('ip_end');
	var ip_type = Ext.getCmp('ip_type');
	if (ip_type.getValue() == 'ips')
	{
		if (oseValidateIPAddress(ip_startCmp.getValue()) == false && oseValidateIPAddress(ip_endCmp.getValue()) == false)
		{
			return false;
		}
	}
	else
	{
		if (oseValidateIPAddress(ip_startCmp.getValue()) == false)
		{
			return false;
		}
	}
}

function uninstallDB(){
	Ext.Msg.confirm('Uninstall Centrora Security', 'Are you sure you want to uninstall Centrora Security?', function(btn, text){
		if (btn == 'yes')
		{		
			Ext.Ajax.request({
				url : url,
				params : {
					option : option,
					controller: uninstallController,
					task: uninstallTask,
					action: uninstallTask
				},
				method: 'POST',
				success: function ( response, options ) {
					var msg  = Ext.decode(response.responseText);
					if (msg.status=='SUCCESS')
					{
						Ext.MessageBox.show({
			                title: 'SUCCESS',
			                msg: msg.result,
			                buttons: Ext.MessageBox.OK
			           });
						location.reload(); 
					}
					else
					{	
						Ext.MessageBox.show({
			                title: 'FAILED',
			                msg: msg.result,
			                buttons: Ext.MessageBox.OK
			           });
					}
				}
			});	
		}
	});
}

function oseValidateIPAddress(ipaddr) {
    ipaddr = ipaddr.replace( /\s/g, "")
    var re = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;
    if (re.test(ipaddr)) {
        var parts = ipaddr.split(".");
        if (parseInt(parseFloat(parts[0])) == 0) {
            return false;
        }
        if (parseInt(parseFloat(parts[3])) == 0) {
            return false;
        }
        //if any part is greater than 255
        for (var i=0; i< parts.length; i++)
        {
            if (parseInt(parseFloat(parts[i])) > 255){
                return false;
            }
        }
        return true;
    } else {
    	Ext.Msg.alert('Warning', 'The IP '+ ipaddr + ' is not a valid IP address');
    	return false;
    }
}

function oseValidateIPs(ip_start, ip_end)
{
    	if (oseValidateIPAddress(ip_start)==true && oseValidateIPAddress(ip_end)==true)
    	{
    		return true;
    	}
    	else
    	{
    		return false; 
    	}
}

function oseAjaxSuccessReload(message, type, store, reload )
{
	if (type=='alert')
	{	
		Ext.Msg.alert(message.status, message.result);
	}
	else
	{
		Ext.Msg.show({title: message.status, msg: message.result});
	}	
	if (reload == true)
	{
		store.reload();
	}
}

function oseEncodingIDs(selections)
{
	var i=0;
	ids = new Array();
	for (i = 0; i < selections.length; i++)
	{
      ids [i] = parseInt(selections[i].data.id);
	}
	ids = Ext.encode(ids);
	return ids; 
}

function oseChangeItemStatus(url, option, controller, task, id, status, store)
{
	Ext.Ajax.request({
				url : url,
				params : {
					option : option,
					controller: controller,
					task: task,
					action: task,
					id: id,
					status: status
				},
				method: 'POST',
				success: function ( response, options ) {
					oseAjaxSuccessReload(Ext.decode(response.responseText),  'show',  store, true);
				}
	});
}

function oseDeleteItem(url, option, controller, task, id, store)
{
	Ext.Ajax.request({
				url : url,
				params : {
					option : option,
					controller: controller,
					task: task,
					action: task,
					id: id,
				},
				method: 'POST',
				success: function ( response, options ) {
					oseAjaxSuccessReload(Ext.decode(response.responseText),  'show',  store, true);
				}
	});
}

function oseFormSubmit(form, url, option, controller, task, store, waitMsg) 
{
	form.getForm().submit({
		clientValidation: true,
		url : url,
		method: 'post',
		params:{
			option : option, 
			controller: controller, 
			task: task,
			action: task
		},
		waitMsg: waitMsg,
		success: function(response, options){
			oseAjaxSuccessReload(options.result, 'alert', store, true);
		},
		failure:function(response, options){
			oseAjaxSuccessReload(options.result, 'alert', store, true);
		} 
		
	});

}

function oseAjaxTaskRequest(ns, url, option, controller, task, selections)
{
	var ids = oseEncodingIDs(selections);
	oseAjaxTaskRequestWithIDS(ns, url, option, controller, task, ids);
}


function osePanelButtonAction(msgTitle, msgText, panel, ns, url, option, controller, task)
{
	Ext.Msg.confirm(msgTitle,msgText, function(btn, text){
		if (btn == 'yes'){
				var sel = panel.getSelectionModel();
				var selections = sel.selected.items;
				oseAjaxTaskRequest(ns, url, option, controller, task, selections);
	    }
     })
}

function osePanelButtonAjaxAction(msgTitle, msgText, panel, ns, url, option, controller, task)
{
	Ext.Msg.confirm(msgTitle,msgText, function(btn, text){
		if (btn == 'yes'){
				oseAjaxTaskRequestWOIDS(ns, url, option, controller, task);
	    }
     })
}

function oseAjaxTaskRequestWithIDS(ns, url, option, controller, task, ids)
{
	Ext.Ajax.request({
		url : url ,
		params : {
			option : option,
			task:task,
			action:task,
			controller:controller,
			ids: ids
		},
		method: 'POST',
		success: function (response, options)
		{
			oseAjaxSuccessReload(Ext.decode(response.responseText), 'alert', ns.store, true);
		}
	});
}

function oseAjaxTaskRequestWOIDS(ns, url, option, controller, task)
{
	Ext.Ajax.request({
		url : url ,
		params : {
			option : option,
			task:task,
			action:task,
			controller:controller
		},
		method: 'POST',
		success: function (response, options)
		{
			oseAjaxSuccessReload(Ext.decode(response.responseText), 'alert', ns.store, true);
		}
	});
}

function oseAjaxWinRequestWithID(url, option, controller, task,id, win)
{
	Ext.Ajax.request({
		url : url ,
		params : {
			option : option,
			controller:controller,
			task:task,
			action:task,
			id: id
		},
		method: 'POST',
		success: function ( response, options ) {
			oseGetAjaxSuccessWin (Ext.decode(response.responseText), win);
		}
});
}

function oseGetAjaxSuccessWin (msg, win)
{
	if (msg.status!='ERROR')
	{
		win.show();
		win.update(msg.results);
	}
	else
	{
		Ext.Msg.alert(msg.status, msg.result);
	}
}

function oseConfFormSubmit(form, url, option, controller, task, type, waitMsg)
{
	form.getForm().submit({
		url : url ,
		params : {
			option : option,
			controller: controller,
			task: task,
			action: task,
			type: type
		},
		method: 'POST',
		waitMsg: waitMsg,
		success: function(response, options){
			oseAjaxSuccessReload(options.result, 'alert', '', false);
		},
		failure:function(response, options){
			oseAjaxSuccessReload(options.result, 'alert',  '', false);
		} 
		
	});	
}

function osePanelAdminEmailAction(msgTitle, msgText, panel, ns, url, option, controller, task)
{
	Ext.Msg.confirm(msgTitle,msgText, function(btn, text){
		if (btn == 'yes'){
				var sel = panel.getSelectionModel();
				var selections = sel.selected.items;
				var i=0;
				ids = new Array();
				for (i=0; i < selections.length; i++)
				{
					ids [i] = {'id':selections[i].data.id, 'email_id': selections[i].data.email_id};
			      
				}
				ids = Ext.encode(ids);
				oseAjaxTaskRequestWithIDS(ns, url, option, controller, task, ids);
	    }
     })
}

function oseAjax(url, option, controller, task)
{
	Ext.Ajax.request({
				url : url,
				params : {
					option : option,
					controller: controller,
					task: task,
					action: task
				},
				method: 'POST',
				success: function ( response, options ) {
					var message  = Ext.decode(response.responseText); 
					Ext.Msg.alert(message.status, message.result);
				}
	});
}

function oseLoadForm (form, url, option, controller, renderTask, id) {
	form.load({
		url: url,
		params : {
					option : option,
					controller:controller,
					task:renderTask,
					action:renderTask,
					id:id					
		}
	});
}