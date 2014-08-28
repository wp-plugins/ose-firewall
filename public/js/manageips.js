var controller = "manageips";

Ext.ns('oseATH','oseATHIPMANAGER');
function changeItemStatus(id, status)
{
	Ext.Msg.confirm(O_CHANGE_IP_STATUS, O_CHANGE_IP_STATUS_DESC, function(btn, text){
		if (btn == 'yes'){
			oseChangeItemStatus(url, option, controller, 'changeIPStatus', id, status , oseATHIPMANAGER.store);
		}
	});
}
function viewIPdetail(id)
{
	oseATHIPMANAGER.win = oseGetWIn('attackdetail', 'Attack_information', 1024, 500); 
	oseAjaxWinRequestWithID(url, option, controller, 'viewAttack',id, oseATHIPMANAGER.win); 
}
oseATHIPMANAGER.blurListener = oseGetIPBlurListener(); 
oseATHIPMANAGER.statusOption = new Array(
		   new Array(1, 'Blacklisted'), 
		   new Array(2, 'Monitored'),
		   new Array(3, 'Whitelisted')
);
oseATHIPMANAGER.sortOption = new Array(
		   new Array('id', 'ID'), 
		   new Array('name', 'IP'),
		   new Array('datetime', 'Date'),
		   new Array('score', 'Score'),
		   new Array('country_code', 'Country'),
		   new Array('visits', 'Visits')
);
oseATHIPMANAGER.orderOption = new Array(
		   new Array('asc', 'Ascending'), 
		   new Array('desc', 'Descending')
);

function reloadStore () {
	oseATHIPMANAGER.store.pageSize = Ext.getCmp('pageSize').value;
	oseATHIPMANAGER.store.load({
		   params:{
				sortby:Ext.getCmp('sortby').value,
				order:Ext.getCmp('order').value,
				limit:Ext.getCmp('pageSize').value
		   }
	});
}
oseATHIPMANAGER.comboSortby = oseGetCombo('sortby', 'Sort By', oseATHIPMANAGER.sortOption, 150, 50, 100, 'datetime');
oseATHIPMANAGER.comboOrder = oseGetCombo('order', '', oseATHIPMANAGER.orderOption, 100, 50, 100, 'desc');
oseATHIPMANAGER.pageSize = 
{   
		xtype:'numberfield',
        fieldLabel: 'Items / Page',
        name: 'pageSize',
        id: 'pageSize',
        labelWidth: 80,
        width: 150,
        value: 15
}
oseATHIPMANAGER.fields = new Array('country_code', 'id', 'score', 'name', 'ip32_start', 'ip32_end', 'iptype', 'status', 'host', 'datetime', 'view', 'visits');
oseATHIPMANAGER.store = new Ext.data.JsonStore({
	  storeId: 'attacksum',
	  fields: oseATHIPMANAGER.fields,
      pageSize:15,
	  proxy: {
        type: 'ajax',
        url: url,
        extraParams: {
    	  option: option, 
    	  controller:controller, 
    	  task:'getACLIPMap', 
    	  action:'getACLIPMap',
    	  centnounce: Ext.get('centnounce').getValue()
      	},
        reader: {
            type: 'json',
            root: 'results',
            idProperty: 'id',
            totalProperty: 'total'
        },
        method: 'POST'
   	 },
});

oseATHIPMANAGER.form = Ext.create('Ext.form.Panel', {
		bodyStyle: 'padding: 10px; padding-left: 20px'
		,autoScroll: true
		,autoWidth: true
	    ,border: false
	    ,labelAlign: 'left'
	    ,labelWidth: 150
	    ,buttons: [
	    {
			text: 'Save'
			,handler: function(){
				if (oseCheckIPValidity()==false) { return false; }
				oseFormSubmit(oseATHIPMANAGER.form, url, option, controller, 'addips', oseATHIPMANAGER.store, 'Please wait, this will take a few seconds ...');
			}
		}
		]
	    ,items:[
    		oseGetNormalTextField('title', O_IP_RULE, 100, 350, null),
	        {
			   	xtype:'combo'
				,fieldLabel: O_IP_TYPE
				,hiddenName: 'ip_type'
				,id: 'ip_type'
				,name: 'ip_type'
				,typeAhead: true
				,triggerAction: 'all'
				,lazyRender:false
				,width: 300
				,mode: 'local'
				,store: Ext.create('Ext.data.ArrayStore', {
					    fields: [
					       'value',
					       'text'
					    ],
					    data: [
					      	['ip', 'IP'],
					      	['ips', "IP "+ O_RANGE]
						]
					})
					,valueField: 'value'
					,displayField: 'text'
					,listeners:{
						select:{
							fn:function(combo, value) {
								var ip_endCmp = Ext.getCmp('ip_end');
								var comValue = combo.getValue(); 
								if (comValue == 'ip')
								{
									ip_endCmp.setDisabled(true);
								}
								else
								{
									ip_endCmp.setDisabled(false);
								}	
						    }
						}
					}						

			},
			oseGetIPTextField('ip_start', O_START_IP, 100, 350),
	        oseGetIPTextField('ip_end', O_END_IP, 100, 350),
	        oseGetCombo('ip_status', 'Status', oseATHIPMANAGER.statusOption, 350, 100, 100, 1)
	        ]
});

oseATHIPMANAGER.cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
    clicksToEdit: 1
});

oseATHIPMANAGER.importForm = Ext.create('Ext.form.Panel', {
    bodyPadding: 10,
    frame: false,
    items: [
	      {
	        xtype: 'filefield',
	        name: 'csvfile',
	        fieldLabel: 'CSV File',
	        labelWidth: 100,
	        msgTarget: 'side',
	        allowBlank: false,
	        anchor: '100%',
	        buttonText: 'Select CSV File...'
	      },
	      {
	    	  html: '<br/>Please create the CSV file with the following headers: title, ip_start, ip_end, ip_type, ip_status. <br/><br/> Explanations:<br/><br/>'+
	    	  		'<ul>'+
	    	  		'<li>title: the title of the rule for this IP / IP Range<li>'+
	    	  		'<li>ip_start: the start IP in the IP Range<li>'+
	    	  		'<li>ip_end: the end IP in the IP Range<li>'+
	    	  		'<li>ip_type: the type of this record, \'0\' refers to one single IP, whereas \'1\' refers to IP ranges<li>'+
	    	  		'<li>ip_status: the status of the IP, \'1\' for blocked IP, \'3\' for whitelisted IP, \'2\' for monitored IP <li>'+
	    	  		'</ul>'
	      }
    ],
    buttons: [{
        text: 'Upload',
        handler: function() {
           oseFormSubmit(oseATHIPMANAGER.importForm, url, option, controller, 'importcsv', oseATHIPMANAGER.store, 'Uploading your file...') 
        }
    }]
});

oseATHIPMANAGER.ImportWinButton = oseGetAddWinButton('importIP', O_IMPORT_IP_CSV, O_IMPORT_IP_CSV, oseATHIPMANAGER.importForm, 600);

oseATHIPMANAGER.panel = Ext.create('Ext.grid.Panel', {
		id: 'oseATHIPMANAGERPanel',
		name: 'oseATHIPMANAGERPanel',
	    store: oseATHIPMANAGER.store,
	    selType: 'rowmodel',
	    multiSelect: true,
	    columns: [
	        {id: 'country_code', header: '',  hidden:false, dataIndex: 'country_code', width: '3%', sortable: true}
            ,{id: 'id', header: O_ID,  hidden:false, dataIndex: 'id', width: '3%', sortable: true}
            ,{id: 'datetime', header: O_DATE,  hidden:false, dataIndex: 'datetime',width: '10%', sortable: true}
            ,{id: 'name', header: O_IP_RULE_TITLE,  hidden:false, dataIndex: 'name', width: '10%',  sortable: true, editor:{allowBlank:false}}
            ,{id: 'score', header: O_RISK_SCORE,  hidden:false, dataIndex: 'score', width: '8%', sortable: true}
            ,{id: 'ip32_start', header: O_START_IP,  hidden:false, dataIndex: 'ip32_start', width: '10%', sortable: true, editor:{allowBlank:false}}
            ,{id: 'ip32_end', header: O_END_IP,  hidden:false, dataIndex: 'ip32_end', width: '10%', sortable: true, editor:{allowBlank:false}}
            ,{id: 'iptype', header: O_IP_TYPE,  hidden:false, dataIndex: 'iptype', sortable: true, width: '12%',  
            	renderer : function(val) {
                   if(val==0) {
                   	  return O_SINGLE_IP;
	               }
	               else
	               {
	               	  return 'IP '+ O_RANGE;
	               }
                }
            }
            ,{
            	id: 'status', header: O_STATUS,  hidden:false, dataIndex: 'status', sortable: true, width: '5%'
             }
            ,{id: 'host', header: O_HOST,  hidden:false, dataIndex: 'host', width: '15%', sortable: true}
            ,{id: 'visits', header: O_VISITS,  hidden:false, dataIndex: 'visits', width: '8%', sortable: false}
            ,{id: 'view', header: O_VIEWDETAIL,  hidden:false, dataIndex: 'view', width: '5%', sortable: false}
	    ],
	    sortInfo:{field: 'datetime', direction: "DESC"},
	    height: 500,
	    width: '100%',
	    renderTo: 'oseantihackerIPManager',
	    plugins: [oseATHIPMANAGER.cellEditing],
	    tbar: new Ext.Toolbar({
			defaults: {bodyStyle:'border:0px solid transparent;'},
			items: [
				    	oseGetAddWinButton('addIPbutton', O_ADD_AN_IP, O_ADD_AN_IP, oseATHIPMANAGER.form, 600),
				        {
				        	id: 'delSelected',
				            text: O_DELETE_ITEMS,
				            handler: function(){
				             	osePanelButtonAction(O_DELETE_CONFIRM, 
				             						 O_DELETE_CONFIRM_DESC, 
				             						 oseATHIPMANAGER.panel, oseATHIPMANAGER, url, option, controller, 
				             						 'removeips'
				             	);
				            }
				        },
				        {
				        	id: 'blkSelected',
				            text: O_BLACKLIST_IP,
				            handler: function(){
				            	osePanelButtonAction(O_BLACKLIST_CONFIRM, 
				            						 O_BLACKLIST_CONFIRM_DESC, 
				             						 oseATHIPMANAGER.panel, oseATHIPMANAGER, url, option, controller,  
				             						 'blacklistIP'
				             	);
				            }
				        },{
				        	id: 'whtSelected',
				            text: O_WHITELIST_IP,
				            handler: function(){
				            	osePanelButtonAction(O_WHITELIST_CONFIRM, 
				            						 O_WHITELIST_CONFIRM_DESC, 
				             						 oseATHIPMANAGER.panel, oseATHIPMANAGER, url, option, controller, 
				             						 'whitelistIP'
				             	);
				             	
				            }
				        },{
				        	id: 'monSelected',
				            text: O_MONITORLIST_IP,
				            handler: function(){
				            	osePanelButtonAction(O_MONILIST_CONFIRM, 
				            						 O_MONILIST_CONFIRM_DESC, 
				             						 oseATHIPMANAGER.panel, oseATHIPMANAGER, url, option, controller,  
				             						 'monitorIP'
				             	);
				            }
				        },
				        {
				        	id: 'hostSelected',
				            text: O_UPDATE_HOST,
				            handler: function(){
				            	osePanelButtonAction(O_UPDATE_HOST_CONFIRM, 
				            						 O_UPDATE_HOST_CONFIRM_DESC, 
				             						 oseATHIPMANAGER.panel, oseATHIPMANAGER, url, option, controller,  
				             						 'updateHost'
				             	);
				            }
				        },
				        oseATHIPMANAGER.ImportWinButton,
				        {
				        	id: 'exportIP',
				            text: O_EXPORT_IP_CSV,
				            handler: function(){
				            	osePanelButtonAction(O_EXPORT_IP_CONFIRM, 
				            						 O_EXPORT_IP_CONFIRM_DESC, 
				             						 oseATHIPMANAGER.panel, oseATHIPMANAGER, url, option, controller,  
				             						 'exportcsv'
				             	);
				            }
				        },
				        '->',
				        oseGetStatusFilter(oseATHIPMANAGER)
				        ,'-',
				        oseGetSearchField (oseATHIPMANAGER)
				    ]
		}),
		bbar:  ['->',
		        {
			        xtype: 'pagingtoolbar',
			        store: oseATHIPMANAGER.store,
			        displayInfo: true
				},
		        oseATHIPMANAGER.comboSortby, 
		        oseATHIPMANAGER.comboOrder,
		        oseATHIPMANAGER.pageSize
		]
});

reloadStore(); 

Ext.getCmp('sortby').on ( 
	"change", function () {
		reloadStore();
	}
)
Ext.getCmp('order').on ( 
	"change", function () {
		reloadStore();
	}
)
Ext.getCmp('pageSize').on ( 
	"change", function () {
		reloadStore();
	}
)