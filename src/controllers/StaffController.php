<?php namespace Nrs\Librarydirectory\controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\models\FormElement;

class StaffController extends BaseController {
	
	public function __construct() {
		$this->beforeFilter('authCanEdit', array('except' => array('getIndex', 'getView')));
		parent::__construct();
	}
	
	public function getCreate($libid = false) {
		if ( $libid && $library = $this->libRepo->find($libid) ) {
			$staffElements = FormElement::where('form_name', 'staff')->orderBy('element_order')->get()->toArray();
			if ( Request::ajax() ) {
				$view = 'librarydirectory::staff.editAjax';	
			} else {
				$view = 'librarydirectory::staff.edit';	
			}
			$staff = $this->staffRepo->model();
			$staff->libid = (int) $libid;
			$staff->organization_name = $library->organization_name;
			$staff->libranch = $library->libranch;
			return View::make($view, array(
				'staff'			=> $staff,
				'staffElements'	=> $staffElements)
			);
		}
	}

	public function getEdit($id = false) {
		$staff = false;
		if ( $id && $staff = $this->staffRepo->find($id) ) {
			$staffElements = FormElement::where('form_name', 'staff')->orderBy('element_order')->get()->toArray();
			if ( Request::ajax() ) {
				$view = 'librarydirectory::staff.editAjax';	
			} else {
				$view = 'librarydirectory::staff.edit';	
			}
			return View::make($view, array(
				'staff' => $staff,
				'staffElements'	=> $staffElements)
			);
		}
		return View::make('librarydirectory::staffNotFound', array('id' => $id));	
	}
	
	public function getIndex() {
		$staff = $this->staffRepo;
		if ( Input::get('near-me', false) !== false ) {
			$ipInfo = json_decode(curlDownload('http://ipinfo.io/'.Request::server('REMOTE_ADDR').'/json'), true);
			if ( is_array($ipInfo) && isset($ipInfo['loc']) ) {
				$loc = explode(',', $ipInfo['loc']);
				$lat = $loc[0];
				$long = $loc[1];
				$staff = $this->staffRepo->filterColumns(array(
					'near-me' => filterByDistance(false, 10, $lat, $long)
				));
			}
		}
		$staff = $staff->paginate(50);
		$this->appendQuerystringToPagination($staff, array('page'));
		return View::make('librarydirectory::staff.all', array(
			'data' 			=> $staff,
			'sortable' 		=> $this->staffRepo->sortable(),
			'querystring'	=> $this->querystring
		));
	}

	public function getView($id = false) {
		$staff = false;
		$staffElements = FormElement::where('form_name', 'staff')->orderBy('element_order')->get()->toArray();
		if ( $id && $staff = $this->staffRepo->find($id) ) {
			return View::make('librarydirectory::staff.single', array(
				'staff'			=> $staff,
				'staffElements'	=> $staffElements)
			);
		}
		return View::make('librarydirectory::staff.notFound', array('id' => $id));	
	}
	
	public function postSave() {
		$delete = Input::get('delete', false);
		$inputs = Input::all();
		$return = Input::get('return-to', false);
		$columns = Schema::connection($this->defaultDbConfig)->getColumnListing('staff');
		$columns = array_combine($columns, $columns);
		$inputs = array_intersect_key($inputs, $columns);
		if ( $delete && $inputs['staffid'] && $inputs['libid'] ) {
			$this->staffRepo->where('staffid', $inputs['staffid'])->delete();
			if ( $return == 'library' ) {
				return Redirect::to('library/view/'.$inputs['libid'])->with('success', 'Success! Staff deleted.');
			} else {
				return Redirect::to('/directory/staff/?id[]='.$inputs['libid'])->with('success', 'Success! Staff deleted.');
			}
			
		}
		$validator = Validator::make($inputs, array('pername' => 'required'));
		if ( $validator->fails() ) {
			if ( $inputs['staffid'] ) {
				if ( $return == 'library' ) {
					return Redirect::to('library/view/'.$inputs['libid'])->withErrors($validator);
				} else {
					return Redirect::to('/directory/staff/?id[]='.$inputs['staffid'])->withErrors($validator);
				}
			} else {
				return Redirect::to('/directory/staff')->withErrors($validator);
			}
		}
		
		if ( $inputs['staffid'] ) {
			$this->staffRepo->where('staffid', $inputs['staffid'])->update($inputs);
			if ( $return == 'library' ) {
				return Redirect::to('library/view/'.$inputs['libid'])->with('success', 'Success! Staff updated.');
			} else {
				return Redirect::to('/directory/staff/?id[]='.$inputs['staffid'])->with('success', 'Success! Staff updated.');
			}
		} else {
			$staff = $this->staffRepo->model();
			foreach ( $inputs as $k => $v ) {
				if ( $v ) {
					$staff->{$k} = $v;	
				}
			}
			$staff->save();
			if ( $return == 'library' ) {
				return Redirect::to('library/view/'.$staff->libid)->with('success', 'Success! Staff created.');
			} else {
				return Redirect::to('/directory/staff/?id[]='.$staff->id)->with('success', 'Success! Staff created.');
			}
		}
	}

}