<?php

/**
 *	Asynchronous POST request
 *	http://www.isaacsukin.com/news/2012/11/asynchronous-requests-php
 */
function asynchPost($url, $data = array(), $debug = false) {
	$parts = parse_url($url);
	$host = $parts['host'];
	if (isset($parts['port'])) {
		$port = $parts['port'];
	} else {
		$port = $parts['scheme'] == 'https' ? 443 : 80;
	}
	if ($port == 443) {
		$host = 'ssl://' . $host;
	}
	$data_params = array();
	foreach ($data as $k => $v) {
		$data_params[] = urlencode($k) . '=' . urlencode($v);
	}
	$data_str = implode('&', $data_params);
	$fp = fsockopen($host, $port, $errno, $errstr, 3);
	if ( !$fp ) {
		return "Error $errno: $errstr";
	} else {
		$out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
		$out .= "Host: " . $parts['host'] . "\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		if (!empty($data_str)) {
			$out .= "Content-Length: " . strlen($data_str) . "\r\n";
		}
		$out .= "Connection: Close\r\n\r\n";
		if ( !empty($data_str) ) {
			$out .= $data_str;
		}
		fwrite($fp, $out);
		if ( $debug ) {
			$contents = '';
			while (!feof($fp)) {
				$contents .= fgets($fp, 128);
			}
		}
		fclose($fp);
		if ( !$debug ) {
			return TRUE;
		} else {
			return array('url' => $parts, 'hostname' => $host, 'port' => $port, 'request' => $out, 'result' => $contents);
		}
	}
}

/**
 *	Download using CURL.
 */
function curlDownload($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, "http://www.lrs.org");
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

/**
 *	Pretty print/dump a variable.
 */
function debug($v) {
	echo '<pre>';
	print_r($v);
	echo '</pre>';	
}

/**
 *	Get annual report data using LRSi Public API.
 */
function getLrsiData($year, $code, $type = 'public') {
	$group = $type == 'public' ? 'alladdresses' : 'allid';
	$d = curlDownload('http://www.lrs.org/'.$type.'/data/api/?y[]='.$year.'&g[]='.$group.'&l[]='.$code);
	$j = json_decode($d, true);
	if ( is_array($j) && sizeof($j) == 1 ) {
		foreach ( $j as $k => $v ) {
			if ( is_array($v) ) {
				return array_shift($v);
			}
		}
	}
	return false;
}

/**
 *	Evaluate user agent and determine if visitor is a bot.
 */
function isBot() {
	$crawlers = 'bot|crawl|slurp|spider|Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby';
	$userAgent = Request::server('HTTP_USER_AGENT');
	if ( $userAgent ) {
		return preg_match('/'.$crawlers.'/', $userAgent) > 0;
	}
	return false;
}

/**
 *	Determine if public edit data is requested along with a create/edit form
 */
function isPublicEdit() {
	$p = Request::get('with-public');
	if ( $p && is_numeric($p) ) {
		return Request::get('with-public');
	}
	return false;
}

/**
 *	Remove coordinates that fall outside of Colorado's boundaries.
 */
function nullifyInvalidCoordinates($db) {
	// UPDATE libraries SET latitude = NULL, longitude = NULL WHERE ( latitude <= 37 OR latitude >= 42 ) OR ( longitude  <= -110 OR longitude >= -101 )	
	return Library::where(function($q) {
		$q->where('latitude', '<=', 27)->orWhere('latitude', '>=', 42);	
	})->orWhere(function($q) {
		$q->where('longitude', '<=', -110)->orWhere('longitude', '>=', -101);	
	})->update(array(
		'latitude' 	=> NULL,
		'longitude'	=> NULL
	));
}

function querystringIsMappable() {
	return Input::get('library-type') 
		|| Input::get('keyword') 
		|| Input::get('city') 
		|| Input::get('county') 
		|| Input::get('zipcode-near');
}

function trimQuerystring() {
	parse_str(Request::server('QUERY_STRING'), $q);
	foreach ( $q as $k => $v ) {
		if ( !is_array($v) && $v == '' ) {
			unset($q[$k]);	
		}
	}
	return http_build_query($q);
}