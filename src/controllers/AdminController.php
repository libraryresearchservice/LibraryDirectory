<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\FormElement;
use Nrs\Librarydirectory\Models\FormGroup;
use Nrs\Librarydirectory\Models\Library;
use Nrs\Librarydirectory\Models\Staff;

class AdminController extends BaseController {
	
	protected $elements;
	protected $library;
	protected $staff;
	
	public function __construct(FormElement $elements, Library $library) {
		parent::__construct();
		$this->elements = $elements;
		$this->library = $library;
	}

	public function getColumns() {
		$columns = array();
		$sortable = array(
			'libraries'	=> 'Libraries',
			'staff'		=> 'Staff'
		);
		$table = Input::get('orderby', 'libraries');
		$tables = array('libraries', 'staff');
		foreach ( Schema::connection($this->defaultDbConfig)->getColumnListing($table) as $v ) {
			$columns[$v] = $v;
		}
		$order = Input::get('order', 'asc');
		if ( $order == 'asc' ) {
			sort($columns);	
		} else {
			rsort($columns);
		}
		return View::make('librarydirectory::admin.columns', array(
				'columns' 	=> $columns, 
				'sortable'	=> $sortable,
				'table' 	=> $table, 
				'tables' 	=> $tables
			)
		);
	}
	
	public function getUpdateGeolocationData() {
		return View::make('librarydirectory::admin.geolocation');
	}

	public function getIndex() {
		return View::make('librarydirectory::admin');
	}
	
	/**
	 *	Display the maximum value for each column in a table
	 */
	public function getSuperfluousColumns() {
		$columns = array();
		foreach ( Schema::connection($this->defaultDbConfig)->getColumnListing('libraries') as $v ) {
			$columns[$v] = $v;
		}
		$max = array();
		foreach ( $columns as $v ) {
			$thisMax = $this->db->table('libraries')->max($v);
			$max[$v] = $thisMax;	
		}
	}
	
	public function postUpdateGeolocationData() {
		$options = array(
			'id' => Input::get('id', false),
			'overwrite' => Input::get('overwrite', array()),
			'skip' => Input::get('skip', false),
			'take' => Input::get('take', false)
		);
		$l = Library::select('libid', 'libinst', 'libranch', 'address1', 'city', 'state', 'zip', 'latitude', 'longitude')
					->where('state', 'CO')
					->where('address1', '!=', '')
					->where('city', '!=', '')
					->orderBy('libid');
		if ( $options['overwrite'] === false ) {			
			$l = $l->where('latitude', 0)->where('longitude', 0);
		}
		if ( $options['skip'] !== false && $options['take'] !== false ) {
			$l = $l->skip($options['skip'])->take($options['take']);	
		} else {
			$l = $l->take(2500);	
		}
		$l = $l->get()->toArray();		
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=%s,+%s,+%s&key=AIzaSyD6WBYfpkNZJN1jC9IwfOb_fxDMOyfgZSk';
		$i = 1;
		$updated = array();
		foreach ( $l as $v ) {
			$thisUrl = str_replace(' ', '+', sprintf($url, $v['address1'], $v['city'], $v['state']));
			$updated[$v['libid']] = $v['libinst'].' / '.$v['libranch'];
			/*
			$data = false;
			$data = curlDownload($thisUrl);
			$data = json_decode($data, true);
			if ( is_array($data) && isset($data['results']) && sizeof($data['results']) > 0 ) {
				$address = array_shift($data['results']);
				if ( isset($address['geometry']['location']['lat']) &&isset($address['geometry']['location']['lng']) ) {
					Library::where('libid', $v['libid'])->update(array(
						'latitude' 	=> $address['geometry']['location']['lat'],
						'longitude'	=> $address['geometry']['location']['lng']
					));
					$updated[$v['libid']] = $v['libinst'].' / '.$v['libranch'];
				}
			}
			*/
			// Google Geocoder API limit is 2,500 per day and 10 per second, so make sure we don't violate!
			if ( $i % 10 === 0 ) {
				ini_set('max_execution_time', 60);
				sleep(1);
			}
			if ( $i === 2500 ) {
				break;	
			}
			$i++;
		}
		return Response::json($updated);
	}

	public function missingMethod($parameters = array()) {
		return Response::view('librarydirectory::pageNotFound', array(), 404);
	}
		
}