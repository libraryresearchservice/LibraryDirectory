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

class ContactController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('auth');
		parent::__construct();
	}
	
	public function getCreate() {
		$groupsAndElements = FormGroup::has('elements')->with(array('elements' => function($q) {
			$q->orderBy('element_order');	
		}))->where('form_name', 'contact')->orderBy('group_order')->get()->toArray();
		$contact = $this->contactRepo->resetModel()->model();
		return View::make('librarydirectory::contact.edit', array(
			'contact' 			=> $contact,
			'groupsAndElements' => $groupsAndElements
		));
	}
	
	public function getEdit($id = false) {
		$contact = false;
		if ( $id && $contact = $this->contactRepo->find($id) ) {
			$groupsAndElements = FormGroup::has('elements')->with(array('elements' => function($q) {
				$q->orderBy('element_order');	
			}))->where('form_name', 'contact')->orderBy('group_order')->get()->toArray();
			return View::make('librarydirectory::contact.edit', array(
				'contact' 			=> $contact,
				'groupsAndElements' => $groupsAndElements)
			);
		}
		return View::make('librarydirectory::contactNotFound', array('id' => $id));	
	}
	
	public function getIndex() {
		$contacts = $this->contactRepo->paginate(50);
		$this->appendQuerystringToPagination($contacts, array('page'));
		$this->searchForm->ignore(array(
			'distance', 'city', 'county', 'near-me', 'type'
		));
		$this->searchForm->show(array(
			'contact-type'
		));
		//debug($this->db->getQueryLog());
		//exit;
		return View::make('librarydirectory::contact.all', array(
			'data' 			=> $contacts,
			'querystring'	=> $this->querystring,
			'search'		=> $this->searchForm,
			'sortable'		=> contactSortables()
		));
	}
	
	public function getView($id = false) {
		if ( $id && $contact = $this->contactRepo->find($id) ) {
			$q = FormGroup::where('form_name', 'contact')->orderBy('group_order');
			$q = $q->whereHas('elements', function($q) {
				$q->where('is_hidden', '0')->where('is_active', 1);
			})->with(array('elements' => function($q) {
				$q->where('is_hidden', '0')->where('is_active', 1)->orderBy('element_order');	
			}));
			$groupsAndElements = $q->get()->toArray();
			return View::make('librarydirectory::contact.single', array(
				'contact' => $contact,
				'groupsAndElements' => $groupsAndElements)
			);
		}
		$this->searchForm->action('/contact')->ignore(array(
			'distance', 'city', 'county', 'near-me', 'type'
		));
		return View::make('librarydirectory::contactNotFound', array('id' => $id));
	}
	
	public function postSave() {
		$delete = Input::get('delete', false);
		$inputs = Input::all();
		$contactTypes = Input::get('contact_type', array());
		$columns = Schema::connection($this->defaultDbConfig)->getColumnListing('contacts');
		$columns = array_combine($columns, $columns);
		$inputs = array_intersect_key($inputs, $columns);
		if ( $delete && $inputs['id'] ) {
			$this->contactRepo->where('id', $inputs['id'])->delete();
			$this->db->table('contact_type_contact')->where('contact_id', $inputs['id'])->delete();
			return Redirect::to('contact')->with('success', 'Success! Contact deleted.');
		}
		// Remove column aliases from repo model
		$this->contactRepo->columns(contactUnaliasedColumns(), false);
		// Do not join with library and organization
		$this->contactRepo->resetModel()->joinLibraries(false)->joinOrganizations(false)->filter(false);
		$old = $this->contactRepo->find($inputs['id']);
		$validator = Validator::make($inputs, array('name' => 'required'));
		if ( $validator->fails() ) {
			if ( $inputs['id'] ) {
				return Redirect::to('contact/edit/'.$inputs['id'])->withErrors($validator);
			} else {
				return Redirect::to('contact/create')->withErrors($validator);
			}
		}
		if ( $inputs['id'] ) {
			$contact = &$old;
			$action = 'updated';
			$contact->updated_by = Auth::user()->id;
			$this->db->table('contact_type_contact')->where('contact_id', $inputs['id'])->delete();
		} else {
			$this->contactRepo->columns(contactUnaliasedColumns(), false);
			$this->contactRepo->resetModel()->joinLibraries(false)->joinOrganizations(false)->filter(false);
			$contact = $this->contactRepo->model();
			$action = 'created';
			$contact->created_by = Auth::user()->id;
			$contact->updated_by = Auth::user()->id;
		}
		foreach ( $inputs as $k => $v ) {
			if ( $v === false ) {
				$v = NULL;
			}
			$contact->{$k} = $v;	
		}
		$contact->save();
		$inserts = array();
		foreach ( $contactTypes as $v ) {
			$inserts[] = array(
				'contact_type_id'	=> $v,
				'contact_id'			=> $contact->id
			);
		}
		$this->db->table('contact_type_contact')->insert($inserts);
		return Redirect::to('contact?id='.$contact->id.'?view=contacts')->with('success', 'Success! Contact '.$action.'.');
	}
	
}