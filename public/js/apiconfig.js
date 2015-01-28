var controller = "apiconfig";
Ext.ns('oseATH','oseConfAPI');
oseConfAPI.Form = new Ext.FormPanel({
        ref: 'form',
        labelAlign: 'top',
        frame:false,
        bodyStyle:'padding:10px',
        autoScroll: false,
        width: '100%',
        height: 425,
        renderTo: 'ConfigAPI',
        items: [
        		oseGetDisplayField(O_APIKEY),
				{
		           xtype:'textarea',
		           labelAlign: 'left',
		           fieldLabel: '',
		           name: 'privateAPIKey',
		           id: 'privateAPIKey',
		           anchor:'98%',
		           height: 300,
		           emptyText: 'Enter your Centrora private API Key'
		   		}
		],
        buttons: [{
            text: 'Save',
            handler: function (){
            	oseConfFormSubmit(oseConfAPI.Form, url, option, controller, 'saveConfigScan', 'scan', O_PLEASE_WAIT);
            }
        }
		],
	    listeners: oseGetConfListener(url, option, controller, 'getConfiguration', 'scan')
    });
