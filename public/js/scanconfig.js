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
        height: 900,
        renderTo: 'ConfigScan',
        items: [
        		//oseGetNormalTextField ('secretword', O_SECRET_WORD, 320, 600),
        		oseGetDisplayField(O_ANTI_HACKING_SCANNING_OPTIONS),
				oseGetCombo('devMode', O_DEVELOPMENT_MODE, oseConfScan.Option, 600, 320, 100, 0),
				oseGetCombo('debugMode', O_DEBUG_MODE, oseConfScan.Option, 600, 320, 100, 0),
				{
        			html: '<table height="30" width="600"><tr><td width="325">'+O_COUNTRY_BLOCKING+'</td><td width="280">(See <a href ="http://www.centrora.com/centrora-tutorial/country-blocking/" target="_blank">Tutorial Here</a>)</td></tr></table>',
        			height: 40
				},
				oseGetCombo('adRules', O_ADRULESETS, oseConfScan.Option, 600, 320, 100, 0),
				{
        			html: '<table height="30" width="600"><tr><td width="325">'+O_ADRULESETS+'</td><td width="280">(See <a href ="http://www.centrora.com/centrora-tutorial/enabling-advance-firewall-setting/" target="_blank">Tutorial Here</a>)</td></tr></table>',
        			height: 40
				},
				//oseGetCombo('adVsPatterns', O_ADVS_PATTERNS, oseConfScan.Option, 600, 320, 100, 0),
				{
        			html: '<table height="30" width="600"><tr><td width="325">'+O_GOOGLE_2_VERIFICATION+'</td><td width="280">(See <a href ="http://www.centrora.com/plugin-tutorial/google-2-step-verification/" target="_blank">Tutorial Here</a>)</td></tr></table>',
        			height: 40
				},
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
        		oseGetDisplayField(O_APIKEY),
				{
		           xtype:'textarea',
		           labelAlign: 'left',
		           fieldLabel: '',
		           name: 'privateAPIKey',
		           id: 'privateAPIKey',
		           anchor:'98%',
		           height: 400,
		           emptyText: 'Enter your Centrora private API Key'
		   		}
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
