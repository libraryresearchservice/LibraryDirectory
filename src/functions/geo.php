<?php

/**
 *	Get latitude and longitude using Google Geocode API.
 */
function googleGeocodeCoordinates($address, $raw = false) {
	$url = 'http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false&region=United+States';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	if ( $response = @curl_exec($ch) ) {
		curl_close($ch);
		$response_a = json_decode($response);
		if ( isset($response_a->results[0]) ) {
			if ( $raw ) {
				return $response_a->results[0];	
			}
			return array(
				'lat' 	=> $response_a->results[0]->geometry->location->lat,
				'long'	=> $response_a->results[0]->geometry->location->lng
			);
		}
	}
	return false;
}

/**
 *	Update a library's latitude and longitude.  It is 
 *	necessary to do this anytime a library's address changes.
 */
function updateLibraryCoordinates($id, $address) {
	$geo = googleGeocodeCoordinates($address);
	$success = false;
	$update = array('latitude' => NULL, 'longitude' => NULL);
	if ( $geo ) {
		if ( validateColoradoCoordinates($geo['lat'], $geo['long']) ) {
			$update = array(
				'latitude'	=> $geo['lat'],
				'longitude'	=> $geo['long']
			);
			$success = true;
		}
	}
	Library::where('id', $k)->update($update);
	return $success;
}

/**
 *	Determine if given latitude and longitude are (roughly) 
 *	within Colorado's boundaries.
 */
function validateColoradoCoordinates($lat, $long) {
	return ( is_numeric($lat) && is_numeric($long) ) && ( $lat >= 37 && $lat <= 41 ) && ( $long >= -109 && $long <= -102 );
}