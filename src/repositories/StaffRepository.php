<?php namespace Nrs\Librarydirectory\repositories;

class StaffRepository extends AbstractRepository {

	protected $class = 'Nrs\Librarydirectory\models\Staff';
	protected $lib = true;
	protected $org = true;

	public function __construct() {
		parent::__construct();
		$this->columns = staffColumns();
		$this->filterable = filterStaffSettings();
		$this->sortable = staffSortables();
	}

	public function joinLibraries($bool) {
		$this->lib = (bool) $bool;	
	}
	
	public function joinOrganizations($bool) {
		$this->org = (bool) $bool;	
	}
	
	public function setupQuery() {
		$this->model = $this->model->select($this->columns);
		if ( $this->lib ) {
			$this->model = $this->model->joinLibrary();	
		}
		if ( $this->org ) {
			$this->model = $this->model->joinOrganization();	
		}
		if ( $this->filter ) {
			$this->model = $this->model->filterColumns($this->filterable);
		}
	}

}