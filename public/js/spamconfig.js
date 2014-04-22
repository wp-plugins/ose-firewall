var controller = "spamconfig";

Ext.ns('oseATH','oseConfAddon');
oseConfAddon.Option = oseGetYesORNoOptions();
oseConfAddon.Form = new Ext.FormPanel({
        ref: 'form',
        labelAlign: 'top',
        frame:false,
        bodyStyle:'padding:10px',
        autoScroll: true,
        renderTo: 'ConfAddons',
        width: '100%',
        height: 300,
        items: [
				oseGetCombo('sfspam', O_ENABLE_SFSPAM, oseConfAddon.Option, 350, 200, 100, 0),
				oseGetNormalTextField('sfs_confidence', O_SFS_CONFIDENCE_LEVEL, 200, 350)
		],
        buttons: [{
            text: 'Save',
            handler: function (){
            	oseConfFormSubmit(oseConfAddon.Form, url, option, controller, 'saveConfAddon', 'addons', O_PLEASE_WAIT);
            }
        }
        ],
        listeners: oseGetConfListener(url, option, controller, 'getConfiguration', 'addons')
});
