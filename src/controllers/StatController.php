<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Repositories\CrosstabRepository;

class StatController extends BaseController {
	
	public $crosstab;
	
	public function __construct() {
		parent::__construct();
		$this->crosstab = new CrosstabRepository;
	}

	public function getIndex() {
		
		$axis = array(
			Input::get('axisa', 'library-type'),
			Input::get('axisb', false)
		);
		return View::make('librarydirectory::stats', array(
			'crosstab'	=> $this->crosstab->db($this->db->table('libraries'))->axis($axis)->get(),
			'table'		=> $this->crosstab->getTableMatrix()
		));
	}
	
}