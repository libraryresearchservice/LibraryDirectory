// Custom marker since Google's is ugly	
var image = '/img/marker5.png';
var infowindow = new google.maps.InfoWindow({
	content: '..'
});
// If coordinates are not set by server, default zoom to Colorado's geographic center
if ( !latitude ) {
	latitude = 39.0000;
}
if ( !longitude ) {
	longitude = -105.5000;
}
var limit = 500;
var map;
var markers = [];
function setCoordinates(position) {
	if ( position ) {
		latitude = position.coords.latitude;
		longitude = position.coords.longitude;
		console.log(latitude);
		console.log(longitude);
		initializeDirectoryMap();
	}
}
function showError(error) {
	radius = 7;
	//initializeDirectoryMap();
	console.log(error);
}
function calculateZoom() {
	if ( radius > 0 ) {
		if ( radius <= 5 ) {
			return 11;	
		} else if ( radius <= 10 ) {
			return 10;	
		} else if ( radius <= 20 ) {
			return 9;	
		} else if ( radius <= 70 ) {
			return 8;
		}
	}
	return 7;	
}
function initializeDirectoryMap() {
	var zoom = calculateZoom();
	var defaultMapOptions = {
		zoom: zoom,
		center: new google.maps.LatLng(latitude,longitude),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById('map-canvas'), defaultMapOptions);
  	var directRequest = window.location.search.slice(1);
	// Append visitor's coordinates to API request
	if ( nearMe && latitude && longitude ) {
		directRequest += '&latitude=' + latitude + '&longitude=' + longitude + '&radius=' + radius;
	}
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
	$.getJSON( '/api/geo?' + qString, function( json ) {
		var latlngbounds = new google.maps.LatLngBounds();
		// Add markers to map and array	
		if ( json.length > 0 ) {
			for ( i = 0; i < json.length; i++ ) {
				var id = json[ i ].id;
				var location = new google.maps.LatLng(json[ i ].latitude, json[ i ].longitude);
				addMarker(location, json[ i ]);
				latlngbounds.extend(location);
			}
			map.fitBounds(latlngbounds);
		}
		var dataViewUrl = window.location.href.replace(/\/map/, '');
		var switchToDataView = '<a class="btn btn-default" href="' + dataViewUrl + '">Switch to Data View</a>';
		//$('#map-total').html('<strong>' + json.length + '</strong> results');
		$('#map-total').html(switchToDataView);
	});
}
// Add a marker to the map and push to the array.
function addMarker(location, jsonObj) {
	if ( jsonObj.name == null ) {
		var libraryName = '';	
	} else {
		var libraryName = jsonObj.name;	
	}
	var libraryType = jsonObj.library_type;
	var image = '/img/marker' + libraryType + '.png';
	var marker = new google.maps.Marker({
		map: map,
		icon: image,
		position: location,
		id: jsonObj.id,
		title: jsonObj.libinst,
		html: '<div class="info-window"><h3>' + jsonObj.organization_name + '</h3><h4>' + libraryName + '</h4><p>' + jsonObj.address_1 + '<br />' + jsonObj.city + ', ' + jsonObj.state + ' ' + jsonObj.zip + '</p><p><a href="/library/view/' + jsonObj.id + '" target="_blank">View Data</a></p></div>'
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
	for ( var i = 0; i < markers.length; i++ ) {
		if ( markers[i]['id'] == id ) {
			markers[i].setMap(null);	
		}
	}	
}
// Sets the map on all markers in the array.
function setAllMap(map) {
	for ( var i = 0; i < markers.length; i++ ) {
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
	if ( nearMe ) {
		// Get browser coordinates
		navigator.geolocation.getCurrentPosition(setCoordinates, showError);
	} else {
		// Default to blank Colorado map, or show zip-based results
		initializeDirectoryMap();
	}
});