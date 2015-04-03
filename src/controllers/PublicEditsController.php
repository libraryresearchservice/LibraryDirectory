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

class PublicEditsController extends BaseController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getDelete($id) {
		$this->publicEdit->where('edit_id', $id)->delete();
		return Redirect::to('/admin/public-edits')->with('success', 'Success! Public edit data deleted.');	
	}
	
	public function getIndex() {
		/**
		 *	1.	Get public edits
		 *	2.	Get data for publicly edited libraries
		 *	3.	Remove data that is unchanged
		 */

		$edits = $this->publicEdit
					  ->with('library')
					  ->orderBy('created_at', 'desc')
					  ->get();
		return View::Make('librarydirectory::public-edits.all', array(
			'edits'		=> $edits
		));	
	}
	
}