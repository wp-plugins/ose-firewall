function oseGetWIn(id, title, width, height)
{
	var win = Ext.create('Ext.window.Window', {
		id: id,
		name: id,
		title: title,
        width: width,
        height: height,
        closeAction:'destroy',
        closable: true,
        autoScroll:true
	}); 
	return win; 
}
function oseGetIPBlurListener()
{
	var blur = {
		blur:{
			fn:function(field, value) {
				var fieldValue = field.getValue();
				if (oseValidateIPAddress(fieldValue)==false)
				{
					field.setValue(''); 
				}
		    }
		}
	}
	return blur; 
}
function oseGetNormalTextField(name, fieldlabel, labelWidth, width)
{
	var textfield = {   
			xtype:'textfield',
	        fieldLabel: fieldlabel,
	        name: name,
	        id: name,
	        labelWidth: labelWidth,
	        width: width
    }
	return textfield; 
}

function oseGetNormalNumberField(name, fieldlabel, labelWidth, width)
{
	var numberfield = {   
			xtype:'numberfield',
	        fieldLabel: fieldlabel,
	        name: name,
	        id: name,
	        labelWidth: labelWidth,
	        width: width
    }
	return numberfield; 
}


function oseGetNormalNumberField(name, fieldlabel, labelWidth, width)
{
	var numberfield = {   
			xtype:'numberfield',
	        fieldLabel: fieldlabel,
	        name: name,
	        id: name,
	        labelWidth: labelWidth,
	        width: width
    }
	return numberfield; 
}

function oseGetNormalDateField(name, fieldlabel, labelWidth, width, current)
{
	if (current == true)
	{	
		var textfield = {   
				xtype:'datefield',
		        fieldLabel: fieldlabel,
		        name: name,
		        id: name,
		        labelWidth: labelWidth,
		        width: width,
		        maxValue: new Date() 
	    }
	}
	else
	{
		var textfield = {   
				xtype:'datefield',
		        fieldLabel: fieldlabel,
		        name: name,
		        id: name,
		        labelWidth: labelWidth,
		        width: width,
		        value: new Date() 
	    }
	}	
	return textfield; 
}

function oseGetIPTextField (name, fieldlabel, labelWidth, width)
{
	var IPBlurListener = oseGetIPBlurListener(); 
	var iptextfield = {   
			xtype:'textfield',
	        fieldLabel: fieldlabel,
	        name: name,
	        id: name,
	        labelWidth: labelWidth,
	        width: width,
            listeners:IPBlurListener
    };
	return iptextfield; 
}

function oseGetNormalPassword(name, fieldlabel, labelWidth, width, allowBlank)
{
	var textfield = {   
			xtype:'textfield',
	        fieldLabel: fieldlabel,
	        inputType: 'password',
	        name: name,
	        id: name,
	        labelWidth: labelWidth,
	        width: width,
	        allowBlank: allowBlank,
	        msgTarget : 'side'
    }
	return textfield; 
}

function oseGetCombo(name, fieldlabel, data, width,  labelWidth, ListWidth, defaultValue)
{
	var combo = new Ext.form.ComboBox({
		hiddenName: name,
		id: name,
		name: name,
		editable : false,
		fieldLabel: fieldlabel,
	    typeAhead: true,
	    triggerAction: 'all',
	    labelWidth: labelWidth,
	    lazyRender:true,
	    width: width,
	    listConfig: { 'width' : ListWidth },
	    mode: 'local',
	    store: new Ext.data.ArrayStore({
	        id: 0,
	        fields: ['value','displayText'],
	        data: data
	    }),
	    valueField: 'value',
	    displayField: 'displayText',
	    listeners:{
			render: function(combo){
				if (combo.getValue()==null)
				{
					combo.setValue(defaultValue);
				}
			 }
	}
  });
  return combo; 
}

function oseGetStore(name, fields, url, option, controller, task)
{
	var store = new Ext.data.JsonStore({
		  storeId: name,
		  fields:fields,
	      autoLoad:{},
	      pageSize:15,
		  proxy: {
	        type: 'ajax',
	        url: url,
	        extraParams: {option: option, controller:controller, task:task, action:task},
	        reader: {
	            type: 'json',
	            root: 'results',
	            idProperty: 'id',
	            totalProperty: 'total'
	        },
	        method: 'POST'
	   	 },
	});
	return store; 
}

function oseGetAjaxCombo(name, fieldlabel, store, width,  labelWidth, ListWidth, valueField, displayField)
{
	var combo = new Ext.form.ComboBox({
		hiddenName: name,
		id: name,
		name: name,
		fieldLabel: fieldlabel,
	    typeAhead: true,
	    triggerAction: 'all',
	    labelWidth: labelWidth,
	    lazyRender:true,
	    width: width,
	    listConfig: { 'width' : ListWidth },
	    mode: 'local',
	    store: store,
	    valueField: valueField,
	    displayField: displayField,
	    autoload:{}
  });
  return combo; 
}

function oseGetAddWinButton(id, text, winTitle, winForm, width)
{
	var addwin = {
        id: id,
        text: text,
        type: 'button',
        handler: function(){
        	var win = new Ext.Window({
    			title: winTitle
    			,id: id+'win'
    			,modal: true
    			,width: width
    			,border: false
    			,autoHeight: true
    			,closeAction:'hide'
    			,items: [
    				winForm
    			]
        		,closable: true
           	});	
        	win.show().alignTo(Ext.getBody(),'t-t', [0, 50]);
        }
    }	
	return addwin ; 
}

function oseGetStatusFilter(ns)
{
	var statusField = 
	{
       	xtype:'combo',
        hiddenName: 'statusfilter',
        id: 'statusfilter',
        width:150,
	    typeAhead: true,
	    triggerAction: 'all',
	    lazyRender:false,
	    emptyText:'Status',
	    mode: 'local',
	    store: new Ext.data.ArrayStore({
	        id: 0,
	        fields: [
	            'value',
	            'text'
	        ],
	        data: ns.statusOption
	    }),
	    valueField: 'value',
	    displayField: 'text',

	    listeners: {
	        beforequery: function(qe){
	        	delete qe.combo.lastQuery;
	        },
	        select: function(c,records,i)	{
	        	ns.store.reload({
    				params:{status: records[0].get('value')}
    			});
    		}
        }
    }
	return statusField; 
}

function oseGetSearchField (ns)
{
	var field = new Ext.ux.form.SearchField({
        store: ns.store,
        paramName: 'search',
        emptyText: 'Search'
    })	
	return field; 
}

function oseGetPaginator(ns)
{
	var pagingtoolbar = {
        xtype: 'pagingtoolbar',
        pageSize: 15,
        store: ns.store,
        displayInfo: true,
        plugins: new Ext.ux.SlidingPager()
	}
	return pagingtoolbar; 
}

function oseGetIPChartElement(xfield, yfield, title, markerType, tips)
{
	var element = {
        type: 'line',
        axis: 'left',
        xField: xfield,
        yField: yfield,
        title: title,
        markerConfig: {
            type: markerType,
            size: 4,
            radius: 4
        },
        tips: {
            trackMouse: true,
            width: 160,
            height: 25,
            renderer: function(storeItem, item) {
                this.setTitle(storeItem.get('type0') + tips + storeItem.get('date'));
            }
        },
    }	
	return element; 
}

function oseGetNormalTextArea (name, title, labelWidth, width)
{
	var textArea = {
       	itemId:name,
       	name: name,
       	id: name,
       	xtype:'textarea',
      	fieldLabel:title,
        allowBlank:false,
        msgTarget: 'side',
        width: width,
        labelWidth: labelWidth
	}	
	return textArea;
}

function oseGetNormalMultiSelect(name, title, labelWidth, width, store, valueField, displayField)
{
	var multiselect = {
        fieldLabel: title,
        name: name,
        id: name,
        itemId: name,
       	xtype: 'multiselect',
        allowBlank: false,
        store: store,
        valueField: valueField,
        displayField: displayField
    }
	return multiselect; 
}

function oseGetRuleStatusOptions()
{
	return new Array(
			   new Array(1, 'Active'), 
			   new Array(0, 'Inactive')
	);
}

function oseGetVarStatusOptions()
{
	return new Array(
			   new Array(1, 'Active'), 
			   new Array(2, 'Filtered'),
			   new Array(3, 'Whitelisted')
	);
}

function oseGetYesORNoOptions()
{
	var option = new Array(new Array(1, 'Enable'), new Array(0, 'Disable'));
	return option; 
}

function oseGetTinyMCEEditor(name, fieldlabel,  labelWidth, width, height)
{
	var editor = {
        fieldLabel: fieldlabel,
        name: name,
        itemId: name,
        id: name,
        xtype:'tinymce_textarea',
        labelWidth: labelWidth,
        width: width,
        height:height,
        fieldStyle: 'font-family: Courier New; font-size: 12px;',
        noWysiwyg: false,
        tinyMCEConfig:  {
	        theme: "advanced",
	        skin: 'default',
	        plugins: "pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
	        theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|",
	        theme_advanced_buttons4: "",
	        theme_advanced_toolbar_location: "top",
	        theme_advanced_toolbar_align: "left",
	        theme_advanced_statusbar_location: "bottom",
	        theme_advanced_resizing: false,
	        extended_valid_elements: "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	    }
  	}
	return editor;
}

function oseGetBackButton(url)
{
	var back = {
			text: 'Back',
			handler: function (){
				window.location=url;
			}  
    }
	return back; 
}

function oseGetConfListener(url, option, controller, task, type)
{
	var listener = {
		render: function(p){
			p.getForm().load(
			{
				url: url,
				params : {
							option : option,
							controller: controller,
							task: task,
							action: task,
							type: type					
				}
			});
		}
	}	
	return listener; 
}

function oseGetDisplayField(value)
{
	var field = {
	    xtype:'displayfield',
	    value: value,
	    hideLabel: true
	}	
	return field; 
}

function oseGetFirewallAlertOptions()
{
	var options = new Array(
			new Array('blacklisted', 'Alert for blacklisted entries'), 
			new Array('filtered', 'Alert for filtered entries'),
			new Array('403blocked', 'Alert for 403 blocked entries')
	);
	return options; 
}

function oseGetEmailEditForm(url, option, controller, task, store, options, renderTask, emailID)
{
	var typeCombo = oseGetCombo('emailType', O_EMAIL_TYPE, options, 450, 150, 100, null);
	var subject = oseGetNormalTextField('emailSubject', O_EMAIL_SUBJECT, 150, 450);
	var body = oseGetTinyMCEEditor('emailBody', O_EMAIL_BODY, 150, 550, 450);
	var form = Ext.create('Ext.form.Panel', {
		id: 'ose-email-form'
		,formId:'ose-email-form'
		,region: 'east'
		,border: false
        ,labelWidth: 80
        ,labelAlign: 'left'
 		,height: 600
 		,width: 850
 		,bodyStyle: 'padding: 10px'
        ,items: [
		        {ref:'id',xtype:'hidden', name: 'id', value:''},
		        typeCombo,
		        subject,
		        body
        ],
        buttons: [{
            text: 'Save',
            handler: function(){
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
            		waitMsg: O_PLEASE_WAIT,
            		success: function(response, options){
            			if (options.result.id!='') {
            				oseLoadForm (form, url, option, controller, renderTask, options.result.id);
            			}
            			oseAjaxSuccessReload(options.result, 'alert', store, true);
            		},
            		failure:function(response, options){
            			oseAjaxSuccessReload(options.result, 'alert', store, true);
            		} 
            		
            	});
            	
            	
            }
        },
        oseGetCloseButton () ],
        listeners: {
			render: function(p){
				oseLoadForm (p.getForm(), url, option, controller, renderTask, emailID)
			}
		}
    });
	return form; 
}

function oseGetEmailParamsPanel(url, controller, id, store)
{
	var bbar = oseGetPaginator(store); 
	var store = new Ext.data.JsonStore({
		  storeId: 'emailParamStore',
		  fields:[ 'key', 'value'],
	      autoLoad:{},
		  proxy: {
	        type: 'ajax',
	        url: url,
	        extraParams: {controller:controller, task:'getEmailParams', action	:'getEmailParams', id: id},
	        reader: {
	            type: 'json',
	            root: 'results',
	            idProperty: 'id',
	            totalProperty: 'total'
	        },
	   	 },
	});
	var grid = Ext.create('Ext.grid.Panel', {
		id: 'oseEmailParamsPanel',
		name: 'oseEmailParamsPanel',
		title: 'Email Parameters', 
		store: store,
		border: true,
		selType: 'rowmodel',
		region: 'west', 
		columns: [
		          {id: 'key', header: 'Variable', dataIndex: 'key', sortable: true, width: '20%'}
				  ,{id: 'value', header: 'Description', dataIndex: 'value', sortable: true, width: '79%'}
	    ],
	    sortInfo:{field: 'id', direction: "DESC"},
	    height: 400,
	    width: 400,
     	bbar: bbar
	});
	return grid;
}

function oseGetProgressbar(id, text) 
{
	var bar = Ext.create('Ext.ProgressBar', {
	    id:id,
	    text:text
	});	
	return bar; 
}

function oseGetNormalStatusOptions()
{
	return new Array(
			   new Array(1, 'Active'), 
			   new Array(0, 'Inactive')
	);
}

function oseGetHiddenTextField(name)
{
	var hiddenfield = {   
			xtype:'hiddenfield',
	        name: name,
	        id: name
    }
	return hiddenfield; 
}

function oseGetCloseButton () {
	var button =  {
			text: 'Close'
			,handler: function(){
				location.reload(); 
			}
	}
	return button; 
}