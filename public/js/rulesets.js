var controller = "rulesets";

Ext.ns('oseATH', 'oseantihackerRulesets');
function changeItemStatus(id, status) {
	Ext.Msg.confirm(O_CHANGE_FIREWALL_STATUS, O_CHANGE_FIREWALL_STATUS_DESC, function(btn, text){
		if (btn == 'yes'){
			oseChangeItemStatus(url, option, controller, 'changeRuleStatus', id, status, oseantihackerRulesets.store);
		}
	});
}
oseantihackerRulesets.attacktypefields = new Array('id', 'name');
oseantihackerRulesets.attacktypeStore = oseGetStore('attacktypeStore', oseantihackerRulesets.attacktypefields, url, option, controller, 'getAttackTypes');
oseantihackerRulesets.statusOption = oseGetRuleStatusOptions();
oseantihackerRulesets.fields = new Array('id', 'rule', 'attacktype', 'action');
oseantihackerRulesets.store = oseGetStore('rulesets', oseantihackerRulesets.fields, url, option, controller, 'getRulesets');
oseantihackerRulesets.form = Ext.create('Ext.form.Panel', {
	bodyStyle : 'padding: 10px; padding-left: 20px',
	autoScroll : true,
	autoWidth : true,
	border : false,
	labelAlign : 'left',
	labelWidth : 150,
	buttons : [ {
		text : 'Save',
		handler : function() {
			oseFormSubmit(oseantihackerRulesets.form, url, option, controller, 'addruleset', oseantihackerRulesets.store,
					'Please wait, this will take a few seconds ...');
		}
	} ],
	items : [ oseGetNormalTextField('filter', O_FILTER, 100, 350, null), oseGetNormalTextField('impactfield', O_IMPACT, 100, 200, null),
			oseGetNormalTextArea('descriptionfield', O_DESCRIPTION, 100, 350),
			oseGetNormalMultiSelect('attacktype_select', O_ATTACKTYPE, 100, 350, oseantihackerRulesets.attacktypeStore, 'id', 'name'),
			oseGetCombo('statusfield', O_STATUS, oseGetRuleStatusOptions(), 350, 100, 100, 1) ]
});

oseantihackerRulesets.panel = new Ext.grid.GridPanel({
	id : 'oseantihackerRulesets',
	name : 'oseantihackerRulesets',
	selType : 'rowmodel',
	columns : [ {
		id : 'id',
		header : O_ID,
		hidden : false,
		dataIndex : 'id',
		width : "4.5%",
		sortable : true
	}, {
		id : 'rule',
		header : O_RULE,
		hidden : false,
		dataIndex : 'rule',
		width : '60%',
		sortable : true
	}, {
		id : 'attacktype',
		header : O_ATTACKTYPE,
		hidden : false,
		dataIndex : 'attacktype',
		width : '30%',
		sortable : true
	}, {
		id : 'action',
		header : O_STATUS,
		hidden : false,
		dataIndex : 'action',
		width : "4.5",
		sortable : true
	} ],
	sortInfo : {
		field : 'id',
		direction : "ASC"
	},
	store : oseantihackerRulesets.store,
	renderTo : 'oseantihackerRulesets',
	height : 500,
	tbar : new Ext.Toolbar({
		items : [ '->', oseGetStatusFilter(oseantihackerRulesets) ]
	}),
	bbar : oseGetPaginator(oseantihackerRulesets)
});