var controller ='dashboard';
		    		
jQuery(document).ready(function($){
    var colours = $('body').data('appStart').getColors();
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
    var chartColours = [colours.linechart1, colours.linechart2, colours.linechart3, colours.linechart4, colours.linechart5, colours.linechart6, colours.linechart7];
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
                fill: false,
        		lineWidth: 2,
        		steps: false
            }
        },
        grid: {
			show: true,
		    aboveData: false,
            color: colours.black,
            labelMargin: 10,
            axisMargin: 0,
		    borderWidth: 0,
		    borderColor:null,
            minBorderMargin: 20,
		    clickable: true, 
		    hoverable: true,
		    autoHighlight: false,
            mouseActiveRadius: 20,
            margin: {
                top: 8,
                bottom: 20,
                left: 20
            }
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
        yaxis: {min: 0, max: 100},
        xaxis: { show: true}
    };


    retrieveCountryData();
    setInterval(function(){retrieveCountryData()}, 30000);
    retrieveTrafficData(options);
    setInterval(function(){retrieveTrafficData(options)}, 10000);
    retrieveHackingTraffic();
    setInterval(function(){$('#IPsTable').dataTable().api().ajax.reload();}, 5000);
    retrieveScanningResult();
    setInterval(function () {
        $('#scanRecentResultTable').dataTable().api().ajax.reload();
    }, 5000);
    retrieveBackupResult();
    setInterval(function () {
        $('#backupTable').dataTable().api().ajax.reload();
    }, 5000);
    checkWebBrowsingStatus();
});
function retrieveCountryData() {
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
function retrieveTrafficData(options) {
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
                var arr0 = [];
                $.each(data[0], function (i, item) {
                    arr0[i] = [parseInt(item.hour), parseInt(item.count)];
	        	});
                var arr1 = [];
                $.each(data[1], function (i, item) {
                    arr1[i] = [parseInt(item.hour), parseInt(item.count)];
                });
                var arr2 = [];
                $.each(data[2], function (i, item) {
                    arr2[i] = [parseInt(item.hour), parseInt(item.count)];
                });

                var plot = $.plot($("#traffic-overview"),
                    [{data: arr0, label: "blacklist", lines: {show: true}}
                        , {data: arr1, label: "monitor", lines: {show: true}}
                        , {data: arr2, label: "whitelist", lines: {show: true}}]
                    , options);

                var xaxisLabel = $("<div class='axisLabel xaxisLabel'></div>").text("Attack History (hours)").appendTo($('#traffic-overview'));

                var yaxisLabel = $("<div class='axisLabel yaxisLabel'></div>").text("Number of attacking IPs (times)").appendTo($('#traffic-overview'));

                yaxisLabel.css("margin-top", yaxisLabel.width() / 2 - 20);
            }
	      });
	});
}

function retrieveHackingTraffic() {
	jQuery(document).ready(function($){
		var manageIPsDataTable = $('#IPsTable').dataTable( {
		 	bFilter: false, bInfo: false, bPaginate: false, "bLengthChange": false,  bProcessing: false, iDisplayLength: 5, "order": [[ 0, "desc" ]],
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
function retrieveScanningResult() {
    jQuery(document).ready(function ($) {
        var scanRecentResultTable = $('#scanRecentResultTable').dataTable({
            bFilter: false,
            bInfo: false,
            bPaginate: false,
            "bLengthChange": false,
            bProcessing: false,
            iDisplayLength: 5,
            "order": [[0, "desc"]],
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: "POST",
                data: function (d) {
                    d.option = option;
                    d.controller = controller;
                    d.action = 'getMalwareMap';
                    d.task = 'getMalwareMap';
                    d.type = 'home';
                    d.centnounce = $('#centnounce').val();
                }
            },
            columns: [
                {"data": "file_id"},
                {"data": "filename"},
                {"data": "checked"},
                {"data": "confidence"},
            ]
        });
    });
}
function retrieveBackupResult() {
    jQuery(document).ready(function ($) {
        var backupTable = $('#backupTable').dataTable({
            bFilter: false,
            bInfo: false,
            bPaginate: false,
            "bLengthChange": false,
            bProcessing: false,
            iDisplayLength: 5,
            "order": [[0, "desc"]],
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: "POST",
                data: function (d) {
                    d.option = option;
                    d.controller = controller;
                    d.action = 'getBackupList';
                    d.task = 'getBackupList';
                    d.centnounce = $('#centnounce').val();
                }
            },
            columns: [
                {"data": "ID"},
                {"data": "time"},
                {"data": "fileName"},
                {"data": "fileType"}
            ]
        });
    });
}
function checkWebBrowsingStatus() {
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