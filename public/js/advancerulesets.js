var controller = "advancerulesets";
Ext.ns('oseATH', 'oseadantihackerRulesets');

function changeItemStatus(id, status) {
	Ext.Msg.confirm(O_CHANGE_FIREWALL_STATUS, O_CHANGE_FIREWALL_STATUS_DESC, function(btn, text){
		if (btn == 'yes'){
			oseChangeItemStatus(url, option, controller, 'changeRuleStatus', id, status, oseadantihackerRulesets.store);
		}
	});
}

function installDB () {
	var win = oseGetWIn('advRulesinstaller', 'Checking Subscription Status', 900, 650); 
	win.show(); 
	win.update('Checking Subscription Status');
	getRules (win);
}

function getRules (win) {
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: 'checkAPI',
			action: 'checkAPI'
		},
		method: 'POST',
		success: function ( response, options ) {
			var msg  = Ext.decode(response.responseText);
			if (msg.paid==false)
			{
				win.update(msg.message  + '<br/>' + msg.refund + '<br/>' + msg.form + '<br/>' + msg.form2  + '<br/>' + msg.form3);
			}
			else
			{
				win.update(msg.message);
			}
		}
	});	
}

oseadantihackerRulesets.attacktypefields = new Array('id', 'name');
oseadantihackerRulesets.attacktypeStore = oseGetStore('attacktypeStore', oseadantihackerRulesets.attacktypefields, url, option, controller, 'getAttackTypes');
oseadantihackerRulesets.statusOption = oseGetRuleStatusOptions();
oseadantihackerRulesets.fields = new Array('id', 'description', 'attacktype', 'action');
oseadantihackerRulesets.store = oseGetStore('rulesets', oseadantihackerRulesets.fields, url, option, controller, 'getRulesets');
oseadantihackerRulesets.form = Ext.create('Ext.form.Panel', {
	bodyStyle : 'padding: 10px; padding-left: 20px',
	autoScroll : true,
	autoWidth : true,
	border : false,
	labelAlign : 'left',
	labelWidth : 150,
	buttons : [ {
		text : 'Save',
		handler : function() {
			oseFormSubmit(oseadantihackerRulesets.form, url, option, controller, 'addruleset', oseadantihackerRulesets.store,
					'Please wait, this will take a few seconds ...');
		}
	} ],
	items : [ oseGetNormalTextField('filter', O_FILTER, 100, 350, null), oseGetNormalTextField('impactfield', O_IMPACT, 100, 200, null),
			oseGetNormalTextArea('descriptionfield', O_DESCRIPTION, 100, 350),
			oseGetNormalMultiSelect('attacktype_select', O_ATTACKTYPE, 100, 350, oseadantihackerRulesets.attacktypeStore, 'id', 'name'),
			oseGetCombo('statusfield', O_STATUS, oseGetRuleStatusOptions(), 350, 100, 100, 1) ]
});

oseadantihackerRulesets.panel = new Ext.grid.GridPanel({
	id : 'oseadantihackerRulesets',
	name : 'oseadantihackerRulesets',
	selType : 'rowmodel',
	columns : [ {
		id : 'id',
		header : O_ID,
		hidden : false,
		dataIndex : 'id',
		width : '4.5%',
		sortable : true
	}, {
		id : 'rule',
		header : O_RULE,
		hidden : false,
		dataIndex : 'description',
		width : '55%',
		sortable : true
	}, {
		id : 'attacktype',
		header : O_ATTACKTYPE,
		hidden : false,
		dataIndex : 'attacktype',
		width : '30%',
		sortable : true
	}, {
		id : 'impact',
		header : O_IMPACT,
		hidden : false,
		dataIndex : 'impact',
		width : '4.5%',
		sortable : true
	}, {
		id : 'action',
		header : O_STATUS,
		hidden : false,
		dataIndex : 'action',
		width : '4.5%',
		sortable : true
	} ],
	sortInfo : {
		field : 'id',
		direction : "ASC"
	},
	store : oseadantihackerRulesets.store,
	renderTo : 'oseadantihackerRulesets',
	height : 500,
	tbar : new Ext.Toolbar({
		items : [ 
					{
						xtype:'displayfield',
					    value: O_LATEST_SIGNATURE,
					    hideLabel: true
					},
		            {
				        	id: 'getRules',
				            text: O_GET_RULES,
				            handler: function(){
		            			installDB();
				            }
				    },
				    '->', 
				    oseGetStatusFilter(oseadantihackerRulesets) 
				 ]
	}),
	bbar : oseGetPaginator(oseadantihackerRulesets)
});