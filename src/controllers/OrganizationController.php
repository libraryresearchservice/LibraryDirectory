<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\FormElement;
use Nrs\Librarydirectory\Models\Organization;

class OrganizationController extends BaseController {

	protected $org;

	public function __construct(Organization $org) {
		parent::__construct();
		$this->org = $org;
	}

	public function getCreate() {
		$orgElements = FormElement::where('form_name', 'organization')->orderBy('element_order')->get()->toArray();
		if ( Request::ajax() ) {
			$view = 'librarydirectory::organization.editAjax';	
		} else {
			$view = 'librarydirectory::organization.edit';	
		}
		return View::make($view, array(
			'org' => $this->org,
			'orgElements'	=> $orgElements)
		);
	}

	public function getEdit($id = false) {
		if ( $id && $org = $this->org->find($id) ) {
			$orgElements = FormElement::where('form_name', 'organization')->orderBy('element_order')->get()->toArray();
			if ( Request::ajax() ) {
				$view = 'librarydirectory::organization.editAjax';	
			} else {
				$view = 'librarydirectory::organization.edit';	
			}
			return View::make($view, array(
				'org' 			=> $org,
				'orgElements'	=> $orgElements)
			);
		}
		return View::make('librarydirectory::organization.notFound', array('id' => $id));	
	}

	public function getIndex() {
		$orgs = $this->org
					 ->filterColumns(filterOrganizationSettings())
					 ->paginate(50);
		$this->appendQuerystringToPagination($orgs, array('page'));
		$sortable = array('name' => 'Name','alt_name' => 'Alternate Name');
		return View::make('librarydirectory::organization.all', array(
			'data' 			=> $orgs,
			'sortable' 		=> $sortable,
			'querystring'	=> $this->querystring
		));
	}

	public function getView($id = false) {
		$orgElements = FormElement::where('form_name', 'organization')->orderBy('element_order')->get()->toArray();
		if ( $id && $org = $this->org->find($id) ) {
			return View::make('librarydirectory::organization.single', array(
				'org' 			=> $org,
				'orgElements'	=> $orgElements)
			);
		}
		return View::make('librarydirectory::organization.notFound', array('id' => $id));	
	}
	
	public function postSave() {
		$delete = Input::get('delete', false);
		$inputs = Input::all();
		$columns = Schema::connection($this->defaultDbConfig)->getColumnListing('organizations');
		$columns = array_combine($columns, $columns);
		$inputs = array_intersect_key($inputs, $columns);
		if ( $delete && $inputs['id'] ) {
			$this->org->where('id', $inputs['id'])->delete();
			return Redirect::to('admin/organization')->with('success', 'Success! Organization deleted.');
		}
		$validator = Validator::make($inputs, array('name' => 'required'));
		if ( $validator->fails() ) {
			if ( $inputs['id'] ) {
				return Redirect::to('admin/organization/edit/'.$inputs['id'])->withErrors($validator);
			} else {
				return Redirect::to('admin/organization/create')->withErrors($validator);
			}
		}
		if ( $inputs['id'] ) {
			$this->org->where('id', $inputs['id'])->update($inputs);
			return Redirect::to('admin/organization?id[]='.$inputs['id'])->with('success', 'Success! Organization updated.');
		} else {
			$org = $this->org;
			foreach ( $inputs as $k => $v ) {
				if ( $v ) {
					$org->{$k} = $v;	
				}
			}
			$org->save();
			return Redirect::to('admin/organization?id[]='.$org->id)->with('success', 'Success! Organization created.');
		}
	}

}