<?php namespace Nrs\Librarydirectory\Repositories;

use Nrs\Librarydirectory\Interfaces\AbstractRepositoryInterface;

class UserRepository extends AbstractRepository implements AbstractRepositoryInterface {

	protected $class = 'Nrs\Librarydirectory\Models\User';

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