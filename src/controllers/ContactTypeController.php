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
use Nrs\Librarydirectory\Models\ContactType;

class ContactTypeController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('auth');
		parent::__construct();
	}

	public function getCreate() {
		$data = new ContactType;
		return View::make('librarydirectory::contact-type.edit', array(
				'data' 	=> $data
			)
		);
	}
	
	public function getIndex() {
		$data = array();
		$data = ContactType::orderBy('type_order')->get();
		return View::make('librarydirectory::contact-type.all', array(
				'data' 	=> $data
			)
		);
	}
	
	public function getEdit($id = false) {
		if ( $id && $data = ContactType::find($id) ) {
			return View::make('librarydirectory::contact-type.edit', array(
					'data' 	=> $data
				)
			);
		}
		return View::make('librarydirectory::contact-type.notFound', array('id' => $id));
	}
	
	public function postSave() {
		$delete = Input::get('delete', false);
		$columns = array_fill_keys(Schema::connection($this->defaultDbConfig)->getColumnListing('contact_types'), false);
		$inputs = array_intersect_key(array_merge($columns, Input::all()), $columns);
		Cache::forget('contactTypes');
		if ( $delete && ContactType::destroy($inputs['id']) ) {
			return Redirect::to('admin/contact-type')->with('success', 'Success! Contact type deleted.');	
		}
		foreach ( $inputs as $k => $v ) {
			if ( $v === false ) {
				unset($inputs[$k]);	
			}
		}
		if ( $inputs['id'] ) {
			ContactType::where('id', $inputs['id'])->update($inputs);
			return Redirect::to('admin/contact-type/edit/'.$inputs['id'])->with('success', 'Success! Contact type updated.');
		} else {
			ContactType::insert($inputs);
			return Redirect::to('admin/contact-type')->with('success', 'Success! Contact type created.');
		}	
	}
		
}