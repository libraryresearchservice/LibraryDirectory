<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\Zipcode;

class MapPhpController extends BaseController {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 *	Our Map view does not directly interact with the database.
	 *	Instead, JQuery does a GET request to the API and then 
	 *	JQuery/JavaScript manually draw the map.
	 */	
	public function getIndex() {	
		
		$ip = Request::server('REMOTE_ADDR');
		$lat = false;
		$long = false;
		$nearMe = false;
		$radius = (int) Input::get('radius', false);
		$zip = Input::get('zipcode-near', false);
		if ( $zip && $zipData = Zipcode::select('latitude', 'longitude')->find($zip) ) {
			$zipData = $zipData->toArray();
			$lat = $zipData['latitude'];
			$long = $zipData['longitude'];
		}
		/**
		 * 	Geolocate the visitor using ipinfo.io
		 */
		if ( Input::get('near-me', false) !== false ) {
			$ipInfo = json_decode(curlDownload('http://ipinfo.io/'.$ip.'/json'), true);
			if ( is_array($ipInfo) && isset($ipInfo['loc']) ) {
				$loc = explode(',', $ipInfo['loc']);
				$lat = $loc[0];
				$long = $loc[1];
				if ( !$radius ) {
					$radius = 5;
				}
				$zip = $ipInfo['postal'];
				$nearMe = $zip;	
			}
			if ( Auth::user()->id == 1 ) {

			}
		}
		//if ( !Auth::check() ) {
			$this->searchForm->ignore(array('near-me'));
		//}
		return View::make('librarydirectory::map.mapPhp', array(
			'ip'		=> $ip,
			'keyword'	=> $this->keyword, 
			'lat' 		=> $lat, 
			'long' 		=> $long,
			'nearMe'	=> $nearMe,
			'radius'	=> $radius
		));	
	}
		
}