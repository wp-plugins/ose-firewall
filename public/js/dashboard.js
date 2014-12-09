var controller ='dashboard';
		    		
jQuery(document).ready(function($){
	var objColors = $('body').data('appStart').getColors();
	var colours = {
		white : objColors.white,
		dark : objColors.dark,
		red : objColors.red,
		blue : objColors.blue,
		green : objColors.green,
		yellow : objColors.yellow,
		brown : objColors.brown,
		orange : objColors.orange,
		purple : objColors.purple,
		pink : objColors.pink,
		lime : objColors.lime,
		magenta : objColors.magenta,
		teal : objColors.teal,
		textcolor : '#5a5e63',
		gray : objColors.gray
	}
	var seriesData = {};
	$('#world-map').vectorMap({
		map : 'world_mill_en',
		zoomButtons : false,
		scaleColors : [ colours.yellow, colours.red ],
		series: {
		      regions: [{
		        scale: [colours.yellow, colours.red],
		        normalizeFunction: 'linear',
		        attribute: 'fill',
		        values: seriesData
		      }]
		},
		normalizeFunction : 'polynomial',
		hoverOpacity : 0.7,
		hoverColor : false,
		focusOn : {
			x : 0.5,
			y : 0.5,
			scale : 1.0
		},
		zoomMin : 1,
		zoomOnScroll: false,
		markerStyle : {
			initial : {
				fill : colours.red,
				stroke : colours.red
			}
		},
		backgroundColor : colours.white,
		regionStyle : {
			initial : {
				fill : colours.yellow,
				"fill-opacity" : 1,
				stroke : colours.yellow,
				"stroke-width" : 0,
				"stroke-opacity" : 0
			},
			hover : {
				"fill-opacity" : 0.8
			},
			selected : {
				fill : 'yellow'
			}
		}
	});
	var chartColours = ['#3fc3a8', '#ed7a53', '#9FC569', '#bbdce3', '#9a3b1b', '#5a8022', '#2c7282'];
	var totalPoints = 24;
    // Update interval
    var updateInterval = 200;
    // setup plot
    var options = {
        series: { 
        	grow: {active:false}, //disable auto grow
        	shadowSize: 0, // drawing is faster without shadows
        	lines: {
        		show: true,
        		fill: true,
        		lineWidth: 2,
        		steps: false
            }
        },
        grid: {
			show: true,
		    aboveData: false,
		    color: "#3f3f3f" ,
		    labelMargin: 5,
		    axisMargin: 0, 
		    borderWidth: 0,
		    borderColor:null,
		    minBorderMargin: 5 ,
		    clickable: true, 
		    hoverable: true,
		    autoHighlight: false,
		    mouseActiveRadius: 20
		}, 
		colors: chartColours,
        tooltip: true, //activate tooltip
		tooltipOpts: {
			content: "Value is : %y.0",
			shifts: {
				x: -30,
				y: -50
			}
		},	
        yaxis: { min: 0, max: 100 },
        xaxis: { show: true}
    };
    retrieveCountryData();
    setInterval(function(){retrieveCountryData()}, 30000);
    retrieveTrafficData(options);
    setInterval(function(){retrieveTrafficData(options)}, 10000);
    retrieveHackingTraffic();
    setInterval(function(){$('#IPsTable').dataTable().api().ajax.reload();}, 5000);
    checkWebBrowsingStatus();
});

function retrieveCountryData()
{
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'getCountryStat',
		    		task:'getCountryStat',
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	var map = $('#world-map').vectorMap('get', 'mapObject');
	            map.series.regions[0].setValues(data);
	        }
	      });
	});
}

function retrieveTrafficData(options)
{
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'getTrafficData',
		    		task:'getTrafficData',
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	var arr = [];
	        	$.each(data, function(i, item) {
	        		arr[i] = [parseInt(item.hour), parseInt(item.count)];
	        	});
	            var plot = $.plot($("#traffic-overview"), [ arr], options);
	        }
	      });
	});
}

function retrieveHackingTraffic()
{
	jQuery(document).ready(function($){
		var manageIPsDataTable = $('#IPsTable').dataTable( {
		 	bFilter: false, bInfo: false, bPaginate: false,
		 	processing: true,
	        serverSide: true,
	        ajax: {
	            url: url,
	            type: "POST",
	            data: function ( d ) {
	                d.option = option;
	                d.controller = 'manageips';
	                d.action = 'getLatestTraffic';
	                d.task = 'getLatestTraffic';
	                d.centnounce = $('#centnounce').val();
	            }
	        },
	        columns: [
	                { "data": "datetime"},
	                { "data": "ip32_start" },
	                { "data": "score" },
	                { "data": "status" }
	               ]
	    });
	});	
}

function checkWebBrowsingStatus()
{
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'checkWebBrowsingStatus',
		    		task:'checkWebBrowsingStatus',
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	
	        }
	      });
	});	
}