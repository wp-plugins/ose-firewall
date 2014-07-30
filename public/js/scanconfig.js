var controller = "scanconfig";
var uninstallController = "uninstall";
var uninstallTask = "uninstallTables";
Ext.ns('oseATH','oseConfScan');
oseConfScan.Option = oseGetYesORNoOptions(); 
oseConfScan.banOption = new Array(
							new Array(1, O_BAN_IP_AND_SHOW_BAN_PAGE_TO_STOP_AN_ATTACK), 
					    	new Array(0, O_SHOW_A_403_ERROR_PAGE_AND_STOP_THE_ATTACK)
);

oseConfScan.auditOption = new Array(
		new Array(1, 'Daily'), 
    	new Array(0, 'Never')
);

oseConfScan.Form = new Ext.FormPanel({
        ref: 'form',
        labelAlign: 'top',
        frame:false,
        bodyStyle:'padding:10px',
        autoScroll: false,
        width: '100%',
        height: 830,
        renderTo: 'ConfigScan',
        items: [
        		oseGetDisplayField(O_ANTI_HACKING_SCANNING_OPTIONS),
				oseGetCombo('devMode', O_DEVELOPMENT_MODE, oseConfScan.Option, 600, 450, 100, 0),
				oseGetCombo('debugMode', O_DEBUG_MODE, oseConfScan.Option, 600, 450, 100, 0),
				oseGetCombo('blockCountry', O_COUNTRY_BLOCKING, oseConfScan.Option, 600, 450, 100, 0),
				oseGetCombo('googleVerification', O_GOOGLE_2_VERIFICATION, oseConfScan.Option, 600, 450, 100, 0),
				oseGetCombo('blockIP', O_FRONTEND_BLOCKING_MODE, oseConfScan.banOption, 750, 450, 400, 0),
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
		   		oseGetDisplayField(O_SYSTEM_FINETUNING),
		   		oseGetCombo('registerGlobalOff', O_DISABLE_REGISTER_GLOBAL, oseConfScan.Option, 600, 450, 100, 0),
		   		oseGetCombo('safeModeOff', O_DISABLE_SAFE_MODE, oseConfScan.Option, 600, 450, 100, 0),
		   		oseGetCombo('urlFopenOff', O_DISABLE_ALLOW_URL_FOPEN, oseConfScan.Option, 600, 450, 100, 0),
		   		oseGetCombo('displayErrorsOff', O_DISABLE_DISPLAY_ERRORS, oseConfScan.Option, 600, 450, 100, 0),
		   		oseGetCombo('phpFunctionsOff', O_DISABLE_PHP_FUNCTIONS, oseConfScan.Option, 600, 450, 100, 0),
		   		oseGetDisplayField(O_SCHEDULE_AUDITING),
		   		oseGetCombo('auditReport', AUDIT_FREQ, oseConfScan.auditOption, 600, 450, 400, 1),
		   		//oseGetCombo('scanClamav', O_SCAN_CLAMAV, oseConfScan.Option, 600, 320, 100, 0)
        		oseGetDisplayField(O_ADV_ANTI_HACKING_SCANNING_OPTIONS),
				oseGetCombo('adRules', O_ADRULESETS, oseConfScan.Option, 600, 450, 100, 0),
				oseGetCombo('silentMode', O_SILENTLY_FILTER_ATTACK, oseConfScan.Option, 600, 450, 100, 0),
				{
					fieldLabel: ATTACK_BLOCKING_THRESHOLD,
					xtype: 'slider',
				    width: 800,
				    minValue: 0,
				    maxValue: 100,
				    name: 'threshold',
				    id: 'threshold',
				    hiddenName: 'threshold',
				    isFormField: true,
				    plugins: new Ext.slider.Tip(),
				    labelWidth: 450
				},
				oseGetNormalTextField('slient_max_att', SILENT_MODE_BLOCK_MAX_ATTEMPTS, 450, 600),
				oseGetCombo('receiveEmail', O_RECEIVE_EMAIL, oseConfScan.Option, 600, 450, 100, 1)
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
