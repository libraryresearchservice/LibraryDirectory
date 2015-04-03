<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\LibraryType;

class LibraryTypeController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('auth');
		parent::__construct();
	}

	public function getCreate() {
		$data = new LibraryType;
		$parents = LibraryType::where('parent_id', 0)->orderBy('type_order')->get();
		return View::make('librarydirectory::library-type.edit', array(
				'data' 		=> $data,
				'parents'	=> $parents
			)
		);
	}
		
	public function getIndex() {
		$data = array();
		$data = LibraryType::orderBy('parent_id')->orderBy('type_order')->get();
		return View::make('librarydirectory::library-type.all', array(
				'data' 	=> $data
			)
		);
	}
	
	public function getEdit($id = false) {
		if ( $id && $data = LibraryType::find($id) ) {
			$parents = array();
			$parents = LibraryType::where('parent_id', 0)->orderBy('type_order')->get();
			return View::make('librarydirectory::library-type.edit', array(
					'data' 		=> $data,
					'parents'	=> $parents
				)
			);
		}
		return View::make('librarydirectory::library-type.notFound', array('id' => $id));

	}
	
	public function postSave() {
		$delete = Input::get('delete', false);
		$columns = array_fill_keys(Schema::connection($this->defaultDbConfig)->getColumnListing('library_types'), false);
		$inputs = array_intersect_key(array_merge($columns, Input::all()), $columns);
		Cache::forget('libraryTypes');
		if ( $delete && LibraryType::destroy($inputs['id']) ) {
			return Redirect::to('admin/library-type')->with('success', 'Success! Library type deleted.');	
		}
		foreach ( $inputs as $k => $v ) {
			if ( $v === false ) {
				unset($inputs[$k]);	
			}
		}
		if ( $inputs['id'] ) {
			LibraryType::where('id', $inputs['id'])->update($inputs);
			return Redirect::to('admin/library-type/edit/'.$inputs['id'])->with('success', 'Success! Library type updated.');
		} else {
			LibraryType::insert($inputs);
			return Redirect::to('admin/library-type')->with('success', 'Success! Library type created.');
		}	
	}
	
}