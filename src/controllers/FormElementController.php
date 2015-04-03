<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\FormElement;
use Nrs\Librarydirectory\Models\FormGroup;
use Nrs\Librarydirectory\Models\Geo;
use Nrs\Librarydirectory\Models\Library;
use Nrs\Librarydirectory\Models\Staff;

class FormElementController extends BaseController {
	
	protected $elements;
	protected $groups;
	
	public function __construct(FormElement $elements, FormGroup $groups) {
		parent::__construct();
		$this->elements = $elements;
		$this->groups = $groups;
	}
	
	public function getCreate() {
		$elements = $this->elements->get();
		$formNames = array();
		foreach ( $elements as $v ) {
			$formNames[] = $v->form_name;	
		}
		$formNames = array_unique($formNames);
		$groups = $this->groups->get();
		return View::make('librarydirectory::form-element.edit', array(
			'columns' => array(),
			'element' => $this->elements,
			'formNames'	=> $formNames,
			'groups'  => $groups,
			'id' => false
		));
	}
	
	public function getEdit($id = false) {
		
		$element = false;
		if ( $id && $element = $this->elements->find($id) ) {
			if ( $element->form_name == 'library' ) {
				$table = 'libraries';
			} else if ( $element->form_name == 'organization' ) {
				$table = 'organizations';
			} else if ( $element->form_name == 'public-edit' ) {
				$table = 'public_edits';	
			} else {
				$table = $element->form_name.'s';
			}
			$columns = Schema::connection($this->defaultDbConfig)->getColumnListing($table);
			$elements = $this->elements->get();
			$formNames = array();
			foreach ( $elements as $v ) {
				$formNames[] = $v->form_name;	
			}
			$formNames = array_unique($formNames);
			$groups = $this->groups->where('form_name', $element->form_name)->orderBy('group_order')->get();
			return View::make('librarydirectory::form-element.edit', array(
				'columns' => $columns,
				'element' => $element,
				'formNames'	=> $formNames,
				'groups'  => $groups,
				'id' => $id,
				'table'	=> $table
			));
		}
		return View::make('librarydirectory::form-element.notFound', array('id' => $id));	
	}
	
	public function getIndex() {
		$elements = $this->elements->filterColumns(filterFormElementsSettings());
		if ( !Input::get('orderby', false) ) {
			$elements = $elements->orderBy('form_name')->orderBy('name');
		}
		$elements = $elements->get();
		$formNames = array();
		foreach ( $elements as $v ) {
			$formNames[] = $v->form_name;	
		}
		$formNames = array_unique($formNames);
		return View::make('librarydirectory::form-element.all', array(
				'elements' 	=> $elements, 
				'formNames'	=> $formNames,
				'sortable' 	=> formElementSortables()
			)
		);
	}
	
	public function postSave() {
		$delete = Input::get('delete', false);
		$columns = array_fill_keys(Schema::connection($this->defaultDbConfig)->getColumnListing('form_elements'), false);
		$inputs = array_intersect_key(array_merge($columns, Input::all()), $columns);
		foreach ( $inputs as $k => $v ) {
			if ( $v === false ) {
				unset($inputs[$k]);	
			}
		}
		if ( $inputs['id'] ) {
			$this->elements->where('id', $inputs['id'])->update($inputs);
			return Redirect::to('admin/form-element/edit/'.$inputs['id'])->with('success', 'Success! Form element updated.');
		} else {
			if (  $inputs['form_name'] == 'library' ) {
				$table = 'libraries';
			} else if (  $inputs['form_name'] == 'organization' ) {
				$table = 'organizations';
			} else {
				$table = 'libraries';
			}
			$columns = Schema::connection($this->defaultDbConfig)->getColumnListing($table);
			if ( in_array($inputs['code'], $columns) ) {
				return Redirect::to('admin/form-element')->with('fail', 'Oops! A form element with that column name already exists.');
			}
			Schema::table($table, function($t) use ($inputs) {
				if ( $inputs['data_type'] == 'text' ) {
					$t->string($inputs['code'], 255);
				} else if ( $inputs['data_type'] == 'int' ) {
					$t->integer($inputs['code'])->unsigned();
				} else if ( $inputs['data_type'] == 'bool' ) {
					$t->boolean($inputs['code']);
				} else {
					$t->string($inputs['code'], 100);	
				}
			});
			$this->elements->insert($inputs);
			return Redirect::to('admin/form-element')->with('success', 'Success! Form element created.');
		}
	}
	
	public function getView($id = false) {
		
	}
	
}