<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
	<title>Library Directory Map Demo</title>
    <!-- jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<!-- font awesome -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <!-- jquery ui style -->
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
	body,html {
		font-family: Arial, Helvetica, sans-serif;
		height: 100%;
		margin: 0;
		overflow: hidden;
		padding: 0;
		width: 100%;	
	}
	#content {
	
	}
	#ajax-results {
		background-color: #fff;
		border-radius: 6px;
		color: #444;
		font-size: 12px;
		max-height: 400px;
		overflow: auto;	
	}
	#ajax-results ul {

	}
	#ajax-results ul li {
		list-style-type: none;	
	}
	#filter {
		background-color:rgba(255,255,255,.7);
		border: 1px solid #ccc;
		border-radius: 6px;
		padding: 10px 16px 10px 16px;
		position: absolute;
		top: 40px;
		right: 30px;
		z-index: 2000;
	}
	#filter input[type="text"] {
		background-color: #fff;
		border: 1px solid #ccc;
		padding: 6px;
	}
	#filter input[type="submit"] {
		background-color: #ddd;
		border: 1px solid #aaa;
		border-radius: 6px;
		height: 30px;
	}
	#filter input[type="submit"]:hover {
		background-color: #eee;
	}
	#filter-title {
		color: #444;
		font-size: 13px;
		font-weight: bold;
		height: 20px;
		line-height: 20px;
	}
	#map-wrap, #map-canvas {
		height: 100%;
		width: 100%;
	}
	#legend {
		background-color: #fff;
		border: 1px solid #ccc;
		border-radius: 6px;
		display: none;
		margin-right: 10px;
		opacity: 0.9;
		padding: 10px;
	}
	.info-window {
		min-height: 120px;
		min-width: 200px;
	}
	</style>
</head>

<body>
    <div id="filter">
    	<form id="keyword-form">
        	<div id="filter-title">Colorado Library Directory Demo</div>
            <input type="text" id="keyword-filter" name="keyword-filter" placeholder="Search by keyword" size="30" /> &nbsp;<input id="map-submit" type="submit" value="Search" />
            <div id="ajax-results">
            
            </div>
        </form>
    </div>
    <div id="map-wrap">
        <div id="map-canvas"></div>
        <div id="legend"></div>
    </div>
    
	<!-- Google maps -->
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <!-- jquery ui -->
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>    
	<script type="text/javascript">
		String.prototype.toProperCase = function () {	
			return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		};
		var base = '<?php echo url() ?>';
		// Default to filling screen with map of Colorado
		var centerOfColorado = new google.maps.LatLng(39.0000,-105.5000);
		// Custom marker since Google's is ugly	
		var image = 'img/marker4.png';
		var infowindow = new google.maps.InfoWindow({
			content: '..'
		});
		// For testing/debugging, allow for showing all Geo entities
		var limit = <?php echo Input::get('limit', false) !== false ? '1000' : '100' ?>;
		var map;
		var markers = [];
		function initializeDirectoryMap() {
			// Initialize map
			// Zoom to Colorado's geographic center
			var defaultMapOptions = {
				zoom: 7,
				center: centerOfColorado,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			map = new google.maps.Map(document.getElementById('map-canvas'), defaultMapOptions);
			var directRequest = window.location.search.slice(1);
			if ( directRequest != '' ) {
				libraryDirectoryMap(directRequest);
			}
		}
		function libraryDirectoryMap(qString) {
			if ( qString == undefined || qString == '' ) {
				// Default to getting, well, default API data
				qString = window.location.search.slice(1);
			}
			qString += '&limit=' + limit;
			// Loop JSON data returned by API
			$.getJSON( base + '/api/geo?' + qString, function( json ) {
				// Add markers to map and array	
				for ( i = 0; i < json.length; i++ ) {
					var id = json[ i ].libid;
					var location = new google.maps.LatLng(json[ i ].mat_lat, json[ i ].mat_lon);
					addMarker(location, json[ i ]);
				}
			});
		}
		// Add a marker to the map and push to the array.
		function addMarker(location, jsonObj) {
			var marker = new google.maps.Marker({
				map: map,
				icon: image,
				position: location,
				id: jsonObj.libid,
				title: jsonObj.libinst,
				html: '<div class="info-window"><h3>' + jsonObj.libinst + '</h3><h4>' + jsonObj.libranch + '</h4><p>' + jsonObj.address1 + '<br />' + jsonObj.city + ', ' + jsonObj.state + ' ' + jsonObj.zip + '</p></div>'
			});
			// Make each marker clickable
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.setContent(this.html);
				infowindow.setOptions({maxWidth: 800});
				infowindow.open(map, this);
			});
			markers.push(marker);
		}
		function removeSingleMarker(id) {
			for (var i = 0; i < markers.length; i++) {
				if ( markers[i]['id'] == id ) {
					markers[i].setMap(null);	
				}
			}	
		}
		// Sets the map on all markers in the array.
		function setAllMap(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
				if ( map != null ) {
					google.maps.event.addListener(markers[i], 'click', function() {
						infowindow.setContent(this.html);
						infowindow.setOptions({maxWidth: 800});
						infowindow.open(map, this);
					});
				}
			}
		}
		// Removes the markers from the map, but keeps them in the array.
		function clearMarkers() {
			setAllMap(null);
		}
		
		// Shows any markers currently in the array.
		function showMarkers() {
			setAllMap(map);
		}
		
		// Deletes all markers in the array by removing references to them.
		function deleteMarkers() {
			clearMarkers();
			markers = [];
		}
        $(document).ready(function() {		
			$('#map-submit').hide();
			initializeDirectoryMap();
			$('#keyword-filter').focus(function () {
				$(this).animate({
					width: '400px'
				}, 500);
			});
			$('#keyword-filter').click(function() {
				$('#ajax-results').show();
			});
			$('#map-canvas').click(function() {
				$('#ajax-results').hide();
			});
			$('#keyword-filter').keyup(function() {
				var keyword = $('#keyword-filter').val();
				if ( keyword.length > 2 ) {
					$('#ajax-results').css('margin-top', '10px');
					$.getJSON( base + '/api/geo?keyword=' + keyword, function( data ) {
						var items = [];
						for ( i = 0; i < data.length; i++ ) {
							items.push( '<li id="' + data[ i ].libid + '"><label><input type="checkbox" class="map-item" name="id[]" value="' + data[ i ].libid + '">' + data[ i ].libranch + '<label></li>' );
						}
						// Remove the dynamic list of checkboxes
						$('#ajax-results').html(' ');
						// Populate the dynamic list of checkboxes
						$( '<ul/>', {
							'class': 'map-item-list',
							html: items.join( '' )
						}).appendTo('#ajax-results');
						// Only add the on() call once, or else duplicate ajax calls will be made to the API
						if ( $('.map-item-list').length == 1 ) {
							$('.map-item').on('click', function() {
								if( $(this).is(':checked') ) {
									var queryString = 'id[]=' + $(this).val();
									libraryDirectoryMap(queryString);
								} else {
									removeSingleMarker($(this).val());
								}
							});
						}
					});
				} else if ( keyword.length == 0 ) {
					$('#ajax-results').html(' ');
				} else {
					$('#ajax-results').css('margin-top', '0');	
				}
			});
        });
    </script> 
    
</body>
</html>