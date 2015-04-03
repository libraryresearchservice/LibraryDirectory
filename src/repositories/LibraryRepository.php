<?php namespace Nrs\Librarydirectory\Repositories;

use Illuminate\Support\Facades\Auth;
use Nrs\Librarydirectory\Interfaces\AbstractRepositoryInterface;

class LibraryRepository extends AbstractRepository implements AbstractRepositoryInterface {

	protected $api= false;
	protected $class = 'Nrs\Librarydirectory\Models\Library';
	protected $libraryType = true;
	protected $org = true;
	protected $restrict = true;

	public function __construct() {
		parent::__construct();
		$this->columns = array($this->table.'.*');
		$this->filterable = filterLibrarySettings();
		if ( Auth::check() ) {
			$this->restrict = false;	
		}
		$this->sortable = librarySortables();
	}

	public function api($bool) {
		$this->api = (bool) $bool;	
		return $this;
	}
		
	public function deleteInvalidCoordinates() {
		$this->model->where('latitude', '>', 41)->orWhere('latitude', '<', 37)->update(array(
			'latitude' => NULL,
			'longitude' => NULL
		));
		$this->model->where('longitude', '>', -102)->orWhere('longitude', '<', -110)->update(array(
			'latitude' => NULL,
			'longitude' => NULL
		));
	}

	public function joinLibraryType($bool) {
		$this->libraryType = (bool) $bool;	
		return $this;
	}
	
	public function joinOrganizations($bool) {
		$this->org = (bool) $bool;	
		return $this;
	}
	
	public function setupQuery() {
		$this->model = $this->model->select($this->columns);
		if ( $this->libraryType ) {
			$this->model = $this->model->joinLibraryType()->addSelect(array('library_types.name AS library_type_name'));
		}
		if ( $this->org ) {
			if ( $this->api ) {
				$this->model = $this->model->joinOrganizationAPI();
			} else {
				$this->model = $this->model->joinOrganization();
			}
		}
		if ( $this->defaults ) {
			$this->model = $this->model->defaultWheres();	
		}
		if ( $this->filter ) {
			$this->model = $this->model->filterColumns($this->filterable);
		}
		if ( $this->restrict ) {
			$this->model = $this->model->restrict();	
		}
	}

}