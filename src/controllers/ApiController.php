<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\FormElement;
use Nrs\Librarydirectory\Models\FormGroup;
use Nrs\Librarydirectory\Models\PublicEdit;

class ApiController extends BaseController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getContact($format = false) {
		if ( !Auth::check() ) {
			return Redirect::to('/');	
		}
		$columns = array();
		$data = $this->contactRepo;
		$elements = array(
			'id' => 'ID'
		);
		foreach ( FormElement::where('is_hidden', '0')->where('form_name', 'contact')->get() as $v ) {
			$elements[$v->code] = $v->name;
		}
		if ( Input::get('limit', false) ) {
			$data = $data->take(Input::get('limit', false));	
		}
		if ( $format == 'csv' ) {
			$data = $data->get()->toArray();
			// Create an empty container that has the correct element order
			$empty = array_fill_keys(array_keys($elements), false);
			ob_start();
			$export = fopen('php://output','w');
			fputcsv($export, $elements);
			foreach ( $data as $v ) {
				// Ensure that row is ordered the same as elements
				$matched = array_intersect_key(array_merge($empty, $v), $empty);
				fputcsv($export, $matched);	
			}
			$output = ob_get_contents();
			ob_end_clean();
			$headers = array('Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="colorado_library_directory_contacts.csv"');
			return Response::make(rtrim($output, '\n'), 200, $headers);
		}
		$data = $data->get();
		return Response::json($data);
	}
	
	public function getDemo() {
		return View::make('librarydirectory::api.demo');
	}

	public function getGeo($format = 'json') {
		$data = $this->libRepo->where('latitude', '!=', '')->where('longitude', '!=', '')->get()->toArray();
		return Response::json($data);
	}

	public function getIndex() {
		return View::make('librarydirectory::api.index');
	}
	
	public function getLibrary($format = false) {
		$callback = Input::get('callback', false);
		if ( $callback == '?' ) {
			$callback = false;
		}
		$columns = array();
		$data = $this->libRepo->joinLibraryType(true);
		$elements = array(
			'organization_name' => 'Organization Name',
			'id'				=> 'ID'
		);
		$columns['libraries.id'] = 'libraries.id';
		foreach ( FormElement::where('is_hidden', '0')->where('form_name', 'library')->get() as $v ) {
			$elements[$v->code] = $v->name;
			if ( $v->is_api == 1 ) {
				$columns['libraries.'.$v->code] = 'libraries.'.$v->code;
			}
		}
		if ( !Auth::check() ) {
			$data->api(true);
			$data->columns($columns, false);
		}
		if ( Input::get('limit', false) ) {
			$data = $data->take(Input::get('limit', false));	
		}
		if ( $format == 'csv' ) {
			$data = $data->get()->toArray();
			// Create an empty container that has the correct element order
			$empty = array_fill_keys(array_keys($elements), false);
			ob_start();
			$export = fopen('php://output','w');
			fputcsv($export, $elements);
			foreach ( $data as $v ) {
				// Ensure that row is ordered the same as elements
				$matched = array_intersect_key(array_merge($empty, $v), $empty);
				fputcsv($export, $matched);	
			}
			$output = ob_get_contents();
			ob_end_clean();
			$headers = array('Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="colorado_library_directory.csv"');
			return Response::make(rtrim($output, '\n'), 200, $headers);
		}
		$data = $data->get();
		$response = Response::json($data);
		if ( $callback ) {
			$response = $response->setCallback($callback);	
		}
		return $response;
	}
	
	public function getPublicEdit() {
		$data = array('public-edit' => 0);
		if ( PublicEdit::first() ) {
			$data['public-edit'] = 1;	
		}
		return Response::json($data);			
	}
	
	public function missingMethod($parameters = array()) {
		return Redirect::to('api');
	}

}