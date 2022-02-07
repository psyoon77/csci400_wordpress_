<!DOCTYPE html>
<html>
	<head>
		<title>
			Roman Sacred Buildings 
		</title>
		<meta charset="utf-8" /> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" integrity="sha512-M2wvCLH6DSRazYeZRIm1JnYyh22purTM+FDB5CsyxtQJYeKq83arPe5wgbNmcFXGqiSH2XR8dT/fJISVA1r/zQ==" crossorigin=""/> <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"
  integrity="sha512-lInM/apFSqyy1o6s89K4iQUKg6ppXEgsVxT35HbzUupEVRh2Eu9Wdl4tHj7dZO0s1uvplcYGmt3498TtHq+log=="
  crossorigin=""></script>
        <style>
			#map {
				width: 95%;
				height: 550px;
				margin: auto;
				border: 2px black solid;
				}
			body {
				font-family: Helvetica,sans-serif; 
				background: #EEEEEE;
				}
			table {
				 margin: auto; 
				 }
			td {
				vertical-align: top;		
			}
			li {
				font-size: small;
			}
			td > form {
				margin-left: 50pt;
			}
		</style>
	</head>
	<body>
		<table style="border-collapse: collapse;">
			<tr>
				<td width="25%">
					<h1>
						Roman Sacred Buildings 
					</h1>
					<h4>
						Pick a variable by which to map the structures in the database: 
					</h4>
					<form id="mapType" style="display:inline">
						<select id="mymenu" size="1">
							<option value="nothing" selected="selected">
								none 
							</option>
							<option value="sex">
								sex of deity 
							</option>
							<option value="deitytype">
								type of deity 
							</option>
<!-- 
							<option value="type">
								type of structure 
							</option>
 -->
							<option value="century">
								century BC of construction 
							</option>
							<option value="orientations">
								orientation 
							</option>
							<option value="heatmap">
								heatmap 
							</option>
						</select>
					</form>
					<br> 
					<p align="center" style="font-size:small">
						<a id="rome" href="javascript:rome();">
							Zoom to Rome 
						</a>
						or 
						<a id="all" href="javascript:showAll();">
							Zoom out 
						</a>
					</p>
					<hr width="80%"> 
				</td>
				<td rowspan="2">
					<div id='map'>
					</div>
				</td>
			</tr>
			<tr>
				<td id="side" style="overflow: auto;">
				</td>
			</tr>
			<tr style="border-top: solid grey 1px; td padding-top: 20px;">
				<td colspan=2 style="fontsize:small;">
<?php 
		include 'scripts/make_menues.php';
		makeMenu('first', '1');
		makeOperatorMenu('firstOp');
		echo '<input name=" firsttxt" type="text" maxlength="512" id="firstTxt" class="searchField" /> +
		'; makeMenu('second', '2');		
		makeOperatorMenu('secondOp');
 echo '
		<input name="secondTxt" type="text" maxlength="512" id="secondTxt" class="searchField" /> +
		'; makeMenu('third', '3'); 		
		makeOperatorMenu('thirdOp');
echo '
		<input name="thirdTxt" type="text" maxlength="512" id="thirdTxt" class="searchField" />
		'; 
?>
					<button id="process" onclick="readInput();">
						Process 
					</button>
					<button id="clear" onclick="clearFilter();">
						Clear 
					</button>
				</td>
			</tr>
		</table>

		<script type="text/javascript" src="scripts/function_check_run.js">
		</script>
		<script type="text/javascript" src="scripts/set_cookie.js">
		</script>
		<script type="text/javascript" src="scripts/function_clear_layers.js">
		</script>
		<script type="text/javascript" src="scripts/function_clear_controls.js">
		</script>
		<script type="text/javascript" src="scripts/function_layer_by_sex.js">
		</script>
		<script type="text/javascript" src="scripts/function_layer_by_type.js">
		</script>
		<script type="text/javascript" src="scripts/function_layer_by_deitytype.js">
		</script>
		<script type="text/javascript" src="scripts/function_layer_by_century.js">
		</script>
		<script type="text/javascript" src="scripts/function_layer_by_orientation.js">
		</script>
		<script type="text/javascript" src="scripts/function_layer_by_heat.js">
		</script>
		<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js">
		</script>
		<script type="text/javascript" src="scripts/leaflet.rotatedMarker.js">
		</script>
		<script type="text/javascript" src="scripts/leaflet-heat.js">
		</script>
		<script type="text/javascript" src="scripts/load_icons.js">
		</script>
		<script type="text/javascript" src="http://maps.stamen.com/js/tile.stamen.js?v1.3.0">
		</script>
		<script type="text/javascript" src="scripts/load_basemaps.js">
		</script>
<!-- Load jQuery library using plain JavaScript
<script src="jquery.min.js"></script> -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
		</script>
		<script type="text/javascript" src="scripts/load_navigation.js">
		</script>
		<script type="text/javascript" src="scripts/get_citations.js">
		</script>
		<script type="text/javascript" src="scripts/on_each_feature.js">
		</script>
		<script type="text/javascript" src="scripts/function_clear_filter.js">
		</script>

		<!-- php script that loads a variable 'biblio' into js -->
		<?php include 'scripts/get_biblio.php' ?>

<!-- 		<script> -->
		<script type="text/javascript">
			// Set a variable to indicate start-up
			// Cookies last a day, so they won't do.
			var allPoints = [];
			
			// Create menu to select the way to format the data
			var selectmenu = document.getElementById("mymenu")
				selectmenu.onchange = function () {
				    var chosenoption = this.options[this.selectedIndex]
				    switch (chosenoption.value) {
				    case "sex":
				        layer_by_sex();
				        break;
// 				    case "type":
// 				        layer_by_type();
// 				        break;
				    case "century":
				        layer_by_century();
				        break;
				    case "orientations":
				        layer_by_orientation();
				        break;
					case "deitytype":
						layer_by_deitytype();
						break;
				    case "heatmap":
				        layer_by_heat();
				        break;
				    case "nothing":
				        clearLayers();
				        clearControls();
 				        map.addLayer(osm);
				    }
				}

			// Create map without layers since the user will do that.
			
			// Define the bounds for the zoomed out map to show all points
			// Could make this a calculated value based on range of points
			var mapCenter = [37.57659, 23.81455],
			    mapZoom = 15.5;
				bounds = [[30.50,10.05],[44.04,37.55]];
			
			var map = L.map('map', {
// 			    center: mapCenter,
// 			    zoom: mapZoom,
			    minZoom: 3,
			    zoomSnap: .5,
			    zoomDelta: .5,
			    layers: [osm]
			});

			// Zoom out
			all();
			
			L.control.scale({
			    position: 'bottomright'
			}).addTo(map);
			
			// Clear the cookie on startup
			var sqlquery = '';
			setCookie('sqlquery', 'dummy', '-1');

			// Load navigation instructions
			loadNavigation();
			
			// Center map on Rome at decent zoom
			function rome() {
				mapZoom = Math.min(map.getMaxZoom(), 14);
				map.flyTo([41.893, 12.48], mapZoom)
			};
			
			// Zoom out at start
			function all() {
				map.fitBounds(bounds);
			};
			
			// Zoom out
			function showAll() {
				map.flyToBounds(bounds, {
					animate: true, duration: 3
				});
			};

			// Read the inputted search
			function readInput() {
				conn = '';
				searchInput = '';
				firstInputMenu = document.getElementById("first").value;
				firstOpText = document.getElementById("firstOp").value;
				firstInputText = test_input(document.getElementById("firstTxt").value);
				secondInputMenu = document.getElementById("second").value;
				secondOpText = document.getElementById("secondOp").value;
				secondInputText = test_input(document.getElementById("secondTxt").value);
				thirdInputMenu = document.getElementById("third").value;
				thirdOpText = document.getElementById("thirdOp").value;
				thirdInputText = test_input(document.getElementById("thirdTxt").value);
				if ( firstInputMenu != '' ) {
				searchInput = firstInputMenu + opTrans(firstOpText, firstInputText);
				conn = ' AND ';
				}
				if ( secondInputMenu != '' ) {
				searchInput = searchInput + conn + secondInputMenu + opTrans(secondOpText, secondInputText);
				conn = ' AND ';
				}
				if ( thirdInputMenu != '' ) {
				searchInput = searchInput + conn + thirdInputMenu + opTrans(thirdOpText, thirdInputText);
				}
				if ( searchInput != '' ) {
// 					document.getElementById("side").innerHTML = 'Inputted query:' + searchInput;
					setCookie('sqlquery', searchInput, '1');
				}
				if ( firstInputMenu + secondInputMenu + thirdInputMenu == '' ) {
					alert('You need to select a field to search on.')
				}
			};
			
			// Translate operator into sql
				function opTrans(option, str) {
				    translation = "";
				    endStr = "'";
				    switch (option) {
				    case "contains":
				   	    translation=" LIKE '%";
				        endStr = "%'";
				    	break;
				    case "doesNotContain":
				   	    translation=" NOT LIKE '%";
				        endStr = "%'";
				        break;
				    case "beginsWith":
				   	    translation=" LIKE '";
				        endStr = "%'";
				        break;
				    case "is":
				   	    translation=" = '";
				        break;
				    case "isNot":
				   	    translation=" != '";
				        break;
				    }
				    return (translation + str + endStr);
				}

			// Clean up the inputted string
			function test_input(data) {
			    data = data.trim();
			    data=data.replace(/[^a-z\d '"%=]+/ig,'')
			    return data;
			};
			
			// Listen for return in text fields
			document.getElementById("firstTxt")
			    .addEventListener("keyup", function(event) {
			    event.preventDefault();
			    if (event.keyCode == 13) {
			        document.getElementById("process").click();
			    }
			});
			document.getElementById("secondTxt")
			    .addEventListener("keyup", function(event) {
			    event.preventDefault();
			    if (event.keyCode == 13) {
			        document.getElementById("process").click();
			    }
			});
			document.getElementById("thirdTxt")
			    .addEventListener("keyup", function(event) {
			    event.preventDefault();
			    if (event.keyCode == 13) {
			        document.getElementById("process").click();
			    }
			});
		</script>
	</body>
</html>
