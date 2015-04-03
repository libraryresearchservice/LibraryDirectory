<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\FormElement;
use Nrs\Librarydirectory\Models\FormGroup;
use Nrs\Librarydirectory\Models\Spam;

class LibraryController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('authCanEdit', array('except' => array('getView', 'getIndex', 'getPublicEdit', 'postPublicSave', 'getPublicEditSpam')));
		parent::__construct();
	}
	
	public function getCreate() {
		$groupsAndElements = FormGroup::has('elements')->with(array('elements' => function($q) {
			$q->orderBy('element_order');	
		}))->where('form_name', 'library')->orderBy('group_order')->get()->toArray();
		return View::make('librarydirectory::library.edit', array(
			'library' 			=> $this->libRepo->model(),
			'groupsAndElements' => $groupsAndElements,
			'public'			=> false,
			'publicEdit'		=> $this->publicEdit
		));
	}
	
	public function getEdit($id = false) {
		$library = false;
		// Grab the library's data
		if ( $id && $library = $this->libRepo->find($id) ) {
			// We'll use groups and elements to build the create/edit forms
			$groupsAndElements = FormGroup::has('elements')->with(array('elements' => function($q) {
				$q->orderBy('element_order');	
			}))->where('form_name', 'library')->orderBy('group_order')->get()->toArray();
			// Show a public edit?
			$isPublicEdit = isPublicEdit();
			// Grab elements that are part of the public edit form
			$publicElements = array();
			foreach ( $groupsAndElements as $v ) {
				foreach ( $v['elements'] as $k2 => $v2 ) {
					if ( $v2['is_public'] == 1 ) {
						$publicElements[$v2['code']] = $v2['code'];
					}
				}
			}
			/**
			  Filter the public edit record. The goal is to only show:
			  a) the public edit value, if it is different from the current record
			  b) an indication that the public edit removes a value
			*/
			$publicEdit = false;
			if ( $isPublicEdit ) {
				$edits = array();
				if ( $publicEdit = $this->publicEdit->find($isPublicEdit) ) {
					foreach ( $publicEdit->toArray() as $k => $v ) {
						if ( in_array($k, $publicElements) ) {
							if ( $v == $library->{$k} ) {
								// User changed value
								$publicEdit->setAttribute($k, NULL);
							} else if ( $v == '' && $library->{$k} != '' ) {
								// User deleted value
								$publicEdit->setAttribute($k, '- DELETED BY USER -');	
							}
						}
					}
				}
			}
			return View::make('librarydirectory::library.edit', array(
				'groupsAndElements' => $groupsAndElements,
				'isPublicEdit'		=> $isPublicEdit,
				'library' 			=> $library,
				'public'			=> false,
				'publicEdit'		=> $publicEdit,
				'publicElements'	=> $publicElements)
			);
		}
		return View::make('librarydirectory::notFound', array('id' => $id));	
	}
	
	public function getIndex() {
		$libraries = $this->libRepo->paginate(50);
		$this->appendQuerystringToPagination($libraries, array('page'));
		return View::make('librarydirectory::library.all', array(
			'data' 			=> $libraries,
			'sortable' 		=> $this->libRepo->sortable(),
			'querystring'	=> $this->querystring
		));
	}

	/**
	 *	Display a form that allows users to submit corrections to directory data.
	 */
	public function getPublicEdit($id = false) {
		$library = false;
		if ( $id ) {
			$library = $this->libRepo->find($id);
		} else {
			$library = $this->libRepo->model();	
		}
		$groupsAndElements = FormGroup::whereHas('elements', function($q) {
			$q->where('is_public', 1);	
		})->with(array('elements' => function($q) {
			$q->where('is_public', 1)->orderBy('element_order');	
		}))->where('form_name', 'public-edit')->orderBy('group_order')->get()->toArray();
		$publicElements = array();
		foreach ( $groupsAndElements as $v ) {
			foreach ( $v['elements'] as $k2 => $v2 ) {
				if ( $v2['is_public'] == 1 ) {
					$publicElements[$v2['code']] = $v2['code'];
				}
			}
		}
		return View::make('librarydirectory::library.edit', array(
			'groupsAndElements' => $groupsAndElements,
			'isPublicEdit'		=> isPublicEdit(),
			'library' 			=> $library,
			'public'			=> true,
			'publicElements'	=> $publicElements)
		);
		return View::make('librarydirectory::notFound', array('id' => $id));	
	}
	
	/**
	 *	Public edit submissions marked as SPAM (e.g. javascript disabled) are redirected
	 *	to a special page.
	 */
	public function getPublicEditSpam() {
		return Response::view('librarydirectory::library.spamEdit', array(), 404);
	}

	public function getView($id = false) {
		if ( $id && $library = $this->libRepo->with('contacts')->find($id) ) {
			$contactGroupsAndElements = array();
			$contactGroupsAndElements = FormGroup::where('form_name', 'contact')->orderBy('group_order')->whereHas('elements', function($q) {
				$q->where('is_hidden', '0')->where('is_active', 1);
			})->with(array('elements' => function($q) {
				$q->where('is_hidden', '0')->where('is_active', 1)->orderBy('element_order');	
			}))->get()->toArray();
			$q = FormGroup::where('form_name', 'library')->orderBy('group_order');
			if ( Auth::check() && Input::get('view') == 'expanded' ) {
				$q = $q->whereHas('elements', function($q) {
					$q->where('is_hidden', '0')->where('is_active', 1);
				})->with(array('elements' => function($q) {
					$q->where('is_hidden', '0')->where('is_active', 1)->orderBy('element_order');	
				}));
			} else {
				$q = $q->whereHas('elements', function($q) {
					$q->where('is_hidden', '0')->where('is_active', 1)->where('is_public', 1);	
				})->with(array('elements' => function($q) {
					$q->where('is_hidden', '0')->where('is_active', 1)->where('is_public', 1)->orderBy('element_order');	
				}));
			}
			$libraryGroupsAndElements = $q->get()->toArray();
			return View::make('librarydirectory::library.single', array(
				'contactGroupsAndElements' => $contactGroupsAndElements,
				'library' => $library,
				'libraryGroupsAndElements' => $libraryGroupsAndElements)
			);
		}
		return View::make('librarydirectory::library.notFound', array('id' => $id));	
	}
	
	public function postPublicSave() {
		$inputs = Input::all();
		$elements = array();
		// Check for Javascript generated form element and make sure it maches our simple daily token
		// Also make sure that our hidden form element has no value
		if ( !isset($inputs['lulz']) || $inputs['lulz'] != simpleAntiSpamToken() || $inputs['bronco'] != '' ) {
			Spam::insert(array('ip' => Request::server('REMOTE_ADDR')));
			return Redirect::to('library/public-edit-spam');
		}
		// Grab the form elements that the public can edit
		foreach ( FormElement::where('form_name', 'public-edit')->where('is_active', 1)->where('is_public', 1)->get()->toArray() as $v ) {
			$elements[$v['code']] = $v;	
		}		
		// Strip out any form data that the public cannot edit (in case some stupid bot tried injecting crap)
		foreach ( $inputs as $k => $v ) {
			if ( !isset($elements[$k]) ) {
				unset($inputs[$k]);
			}
		}
		// Strip out any form data that are not associated with a table column
		$columns = array_merge(Schema::connection($this->defaultDbConfig)->getColumnListing('libraries'),
			Schema::connection($this->defaultDbConfig)->getColumnListing('public_edits')
		);
		$columns = array_combine($columns, $columns);
		$inputs = array_intersect_key($inputs, $columns);
		$id = $inputs['id'];
		$old = false;
		if ( $id > 0 && $old = $this->libRepo->filter(false)->find($inputs['id']) ) {
			foreach ( $inputs as $k => $v ) {
				$original = $old->getOriginal($k);
				if ( $v == $original ) {
					//unset($inputs[$k]);	
				}
			}
		}
		if ( sizeof($inputs) > 0 ) {
			$publicEdit = &$this->publicEdit;
			$publicEdit->id = $id;
			foreach ( $inputs as $k => $v ) {
				if ( $v ) {
					$publicEdit->{$k} = $v;	
				}
			}
			$publicEdit->editor_ip = Request::server('REMOTE_ADDR');
			$publicEdit->save();
		}
		if ( $old ) {
			 $location = 'library/view/'.$id;	
		} else {
			$location = '/';	
		}
		return Redirect::to($location)->with('success', 'Success! Your changes will be reviewed by State Library staff and, if approved, may take 2-3 business days to appear.');
	}
	
	public function postSave() {
		$delete = Input::get('delete', false);
		$deletePublicEdit = false;
		$publicEditId = false;
		$inputs = Input::all();
		if ( isset($inputs['delete-public-edit']) ) {
			$deletePublicEdit = $inputs['delete-public-edit'];
			unset($inputs['delete-public-edit']);
		}
		if ( isset($inputs['public-edit-id']) ) {
			$publicEditId = $inputs['public-edit-id'];
			unset($inputs['public-edit-id']);
		}
		$columns = Schema::connection($this->defaultDbConfig)->getColumnListing('libraries');
		$columns = array_combine($columns, $columns);
		$inputs = array_intersect_key($inputs, $columns);
		if ( $delete && $inputs['id'] ) {
			$this->libRepo->where('id', $inputs['id'])->delete();
			$this->publicEdit->where('id', $inputs['id'])->delete();
			return Redirect::to('library')->with('success', 'Success! Library deleted.');
		}
		$old = $this->libRepo->filter(false)->find($inputs['id']);
		$validator = Validator::make($inputs, array('name' => 'required'));
		if ( $validator->fails() ) {
			if ( $inputs['id'] ) {
				return Redirect::to('library/edit/'.$inputs['id'])->withErrors($validator);
			} else {
				return Redirect::to('library/create')->withErrors($validator);
			}
		}
		/*
			Basic validation of latitude and longitude to make sure they are within the acceptable
			range for Colorado
		*/
		if ( !$old || ( $inputs['latitude'] != $old->latitude || $inputs['longitude'] != $old->longitude ) ) {
			if ( is_double($inputs['latitude']) && is_double($inputs['longitude']) ) {
				$valid = validateColoradoCoordinates($inputs['latitude'], $inputs['longitude']);
				if ( !$valid ) {
					$inputs['latitude'] = NULL;
					$inputs['longitude'] = NULL;
					Session::flash('fail', 'The coordinates you provided are outside Colorado and were not been saved');
				}
			}
		}
		$validator = Validator::make($inputs, array('name' => 'required'));
		if ( $validator->fails() ) {
			if ( $inputs['id'] ) {
				return Redirect::to('library/edit/'.$inputs['id'])->withErrors($validator);
			} else {
				return Redirect::to('library/create')->withErrors($validator);
			}
		}
		if ( $inputs['id'] ) {
			$library = &$old;
			$action = 'updated';
		} else {
			$library = $this->libRepo->resetModel()->model();
			$action = 'created';
		}
		foreach ( $inputs as $k => $v ) {
			if ( $v === false ) {
				$v = NULL;
			}
			$library->{$k} = $v;
		}
		$library->save();
		if ( $deletePublicEdit ) {
			$this->publicEdit->where('edit_id', $publicEditId)->delete();
		}
		return Redirect::to('library/view/'.$library->id)->with('success', 'Success! Library '.$action.'.');
	}

}