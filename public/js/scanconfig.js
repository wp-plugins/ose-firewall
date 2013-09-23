var controller = "scanconfig";

Ext.ns('oseATH','oseConfScan');
oseConfScan.Option = oseGetYesORNoOptions(); 
oseConfScan.banOption = new Array(
							new Array(1, O_BAN_IP_AND_SHOW_BAN_PAGE_TO_STOP_AN_ATTACK), 
					    	new Array(0, O_SHOW_A_403_ERROR_PAGE_AND_STOP_THE_ATTACK)
);
oseConfScan.Form = new Ext.FormPanel({
        ref: 'form',
        labelAlign: 'top',
        frame:false,
        bodyStyle:'padding:10px',
        autoScroll: true,
        width: '100%',
        height: 350,
        renderTo: 'ConfigScan',
        items: [
        		oseGetDisplayField(O_SECRET_WORD_DESC),
        		//oseGetNormalTextField ('secretword', O_SECRET_WORD, 320, 600),
        		oseGetDisplayField(O_ANTI_HACKING_SCANNING_OPTIONS),
				oseGetCombo('devMode', O_DEVELOPMENT_MODE, oseConfScan.Option, 600, 320, 100, 0),
				oseGetCombo('debugMode', O_DEBUG_MODE, oseConfScan.Option, 600, 320, 100, 0),
				oseGetCombo('blockIP', O_FRONTEND_BLOCKING_MODE, oseConfScan.banOption, 700, 320, 400, 0),
		],
        buttons: [{
            text: 'Save',
            handler: function (){
            	oseConfFormSubmit(oseConfScan.Form, url, option, controller, 'saveConfigScan', 'scan', O_PLEASE_WAIT);
            }
        }
		],
	    listeners: oseGetConfListener(url, option, controller, 'getConfiguration', 'scan')
    });
