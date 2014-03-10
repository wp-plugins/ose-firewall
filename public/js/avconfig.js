var controller = "avconfig";

Ext.ns('oseATH','oseConfAV');
oseConfAV.Option = oseGetYesORNoOptions(); 

oseConfAV.Form = new Ext.FormPanel({
	    ref: 'form',
	    labelAlign: 'top',
	    frame:false,
	    bodyStyle:'padding:10px',
	    autoScroll: true,
	    width: '100%',
	    height: 350,
	    renderTo: 'ConfAntiVirus',
        items: [
            oseGetDisplayField(O_SCANNED_FILE_EXTENSIONS_DESC),
			oseGetNormalTextArea('file_ext', O_SCANNED_FILE_EXTENSIONS, 300, 600),
			{
                xtype:'textfield',
            	labelWidth: 300,
				fieldLabel: O_DO_NOT_SCAN_BIGGER_THAN,
                name: 'maxfilesize',
                id: 'maxfilesize',
                anchor:'40%',
                regex: /[0-9]/i,
                value: 2
            },
            oseGetDisplayField(O_CLAMAV_DESC),
            oseGetCombo('enable_clamav', O_ENABLE_CLAMAV_SCANNING, oseConfAV.Option, 600, 300, 100, 0)
            ,{
                xtype:'textfield',
                labelWidth: 300,
                width: 600,
                fieldLabel: O_CLAMAV_SOCKET_LOCATION,
                name: 'clamavsocket',
                id: 'clamavsocket',
                value: "unix:///tmp/clamd.socket" 
            }
		],
		buttons: [{
            text: 'Save',
            handler: function (){
            	oseConfFormSubmit(oseConfAV.Form, url, option, controller, 'saveConfAV', 'vsscan', O_PLEASE_WAIT);
            }
        }],
	  	listeners: oseGetConfListener(url, option, controller, 'getConfiguration', 'vsscan')
    });
