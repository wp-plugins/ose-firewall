var controller = "scanconfig";
var uninstallController = "uninstall";
var uninstallTask = "uninstallTables";
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
        autoScroll: false,
        width: '100%',
        height: 480,
        renderTo: 'ConfigScan',
        items: [
        		//oseGetNormalTextField ('secretword', O_SECRET_WORD, 320, 600),
        		oseGetDisplayField(O_ANTI_HACKING_SCANNING_OPTIONS),
				oseGetCombo('devMode', O_DEVELOPMENT_MODE, oseConfScan.Option, 600, 320, 100, 0),
				oseGetCombo('debugMode', O_DEBUG_MODE, oseConfScan.Option, 600, 320, 100, 0),
				oseGetCombo('blockCountry', O_COUNTRY_BLOCKING, oseConfScan.Option, 600, 320, 100, 0),
				oseGetCombo('adRules', O_ADRULESETS, oseConfScan.Option, 600, 320, 100, 0),
				oseGetCombo('googleVerification', O_GOOGLE_2_VERIFICATION, oseConfScan.Option, 600, 320, 100, 0),
				oseGetCombo('blockIP', O_FRONTEND_BLOCKING_MODE, oseConfScan.banOption, 700, 320, 400, 0),
				oseGetDisplayField(O_ALLOWED_FILE_TYPES),
				{
		           xtype:'textfield',
		           labelAlign: 'left',
		           fieldLabel: '',
		           name: 'allowExts',
		           id: 'allowExts',
		           anchor:'98%',
		           emptyText: 'doc,docx,jpg,png,pdf'
		   		},
		   		oseGetDisplayField('Note: Centrora 3.X API is removed since version 3.2.0')
		   		//oseGetCombo('scanClamav', O_SCAN_CLAMAV, oseConfScan.Option, 600, 320, 100, 0)
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
