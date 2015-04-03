<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\Zipcode;

class MapController extends BaseController {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 *	Our Map view does not directly interact with the database (except to get zip positions).
	 *	Instead, JQuery does a GET request to the API and then 
	 *	JQuery/JavaScript manually draw the map.
	 */	
	public function getIndex() {	
		$latitude = false;
		$longitude = false;
		$nearMe = Input::get('near-me', false);
		$radius = (int) Input::get('radius', false);
		if ( $nearMe ) {
			$radius = 5;
		}
		$zip = Input::get('zipcode-near', false);
		if ( $zip && $zipData = Zipcode::select('latitude', 'longitude')->find($zip) ) {
			$latitude = $zipData->latitude;
			$longitude = $zipData->longitude;
		}
		return View::make('librarydirectory::map.map', array(
			'keyword'	=> $this->keyword, 
			'latitude' 	=> $latitude, 
			'longitude' => $longitude,
			'nearMe'	=> $nearMe,
			'radius'	=> $radius
		));	
	}
		
}