var controller = "manageips";

Ext.ns('oseATH','oseATHIPMANAGER');
function changeItemStatus(id, status)
{
	oseChangeItemStatus(url, option, controller, 'changeIPStatus', id, status , oseATHIPMANAGER.store);
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
oseATHIPMANAGER.fields = new Array('country_code', 'id', 'score', 'name', 'ip32_start', 'ip32_end', 'iptype', 'status', 'host', 'datetime', 'view', 'visits');
oseATHIPMANAGER.store = oseGetStore('attacksum', oseATHIPMANAGER.fields, url, option, controller, 'getACLIPMap');
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

oseATHIPMANAGER.panel = Ext.create('Ext.grid.Panel', {
		id: 'oseATHIPMANAGERPanel',
		name: 'oseATHIPMANAGERPanel',
	    store: oseATHIPMANAGER.store,
	    selType: 'rowmodel',
	    multiSelect: true,
	    columns: [
            {id: 'country_code', header: '',  hidden:false, dataIndex: 'country_code', width: 30, sortable: true}
            ,{id: 'id', header: O_ID,  hidden:false, dataIndex: 'id', width: 40, sortable: true}
            ,{id: 'datetime', header: O_DATE,  hidden:false, dataIndex: 'datetime',width: 130, sortable: true}
            ,{id: 'name', header: O_IP_RULE_TITLE,  hidden:false, dataIndex: 'name', width: 130,  sortable: true}
            ,{id: 'score', header: O_RISK_SCORE,  hidden:false, dataIndex: 'score', width: 80, sortable: true}
            ,{id: 'ip32_start', header: O_START_IP,  hidden:false, dataIndex: 'ip32_start', width: 130, sortable: true}
            ,{id: 'ip32_end', header: O_END_IP,  hidden:false, dataIndex: 'ip32_end', width: 130, sortable: true}
            ,{id: 'iptype', header: O_IP_TYPE,  hidden:false, dataIndex: 'iptype', sortable: true, 
            	renderer : function(val) {
                   if(val==0) {
                   	  return O_SINGLE_IP;
	               }
	               else
	               {
	               	  return 'IP'+ O_RANGE;
	               }
                }
            }
            ,{
            	id: 'status', header: O_STATUS,  hidden:false, dataIndex: 'status', sortable: true
             }
            ,{id: 'host', header: O_HOST,  hidden:false, dataIndex: 'host', width: '12%', sortable: true}
            ,{id: 'visits', header: O_VISITS,  hidden:false, width: 40, dataIndex: 'visits', width: 60, sortable: false}
            ,{id: 'view', header: O_VIEWDETAIL,  hidden:false, width: 40, dataIndex: 'view', width: 80, sortable: false}
	    ],
	    sortInfo:{field: 'datetime', direction: "DESC"},
	    height: 735,
	    width: '100%',
	    renderTo: 'oseantihackerIPManager',
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
				        '->',
				        oseGetStatusFilter(oseATHIPMANAGER)
				        ,'-',
				        oseGetSearchField (oseATHIPMANAGER)
				    ]
		}),
		bbar: oseGetPaginator(oseATHIPMANAGER)
});