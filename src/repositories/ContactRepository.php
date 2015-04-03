<?php namespace Nrs\Librarydirectory\Repositories;

use Illuminate\Database\Query\Expression as Raw;
use Illuminate\Support\Facades\Auth;
use Nrs\Librarydirectory\Interfaces\AbstractRepositoryInterface;

class ContactRepository extends AbstractRepository implements AbstractRepositoryInterface {

	protected $class = 'Nrs\Librarydirectory\Models\Contact';
	protected $contactTypes = true;
	protected $library = false;
	protected $org = true;
	protected $restrict = true;

	public function __construct() {
		parent::__construct();
		$this->columns = contactUnaliasedColumns();
		$this->filterable = filterContactSettings();
		if ( Auth::check() ) {
			$this->restrict = false;	
		}
		$this->sortable = array();
	}

	public function joinContactTypes($bool) {
		$this->contactTypes = (bool) $bool;
		return $this;
	}
	
	public function joinLibraries($bool) {
		$this->library = (bool) $bool;
		return $this;
	}
		
	public function joinOrganizations($bool) {
		$this->org = (bool) $bool;
		return $this;
	}
	public function setupQuery() {
		$this->model = $this->model->select($this->columns);
		if ( $this->contactTypes ) {
			$this->model = $this->model
						  	 	->leftJoin('contact_type_contact', 'contacts.id', '=', 'contact_type_contact.contact_id')
								->leftJoin('contact_types', 'contact_type_contact.contact_type_id', '=', 'contact_types.id')
								->groupBy('contacts.id')
								->addSelect(array(
									'contact_types.name AS contact_type_name',
									new Raw("GROUP_CONCAT(contact_types.id) AS contact_type_ids"),
									new Raw("GROUP_CONCAT(contact_types.name ORDER BY contact_types.name SEPARATOR '; ') AS contact_type_names")
								));
		}
		if ( $this->org ) {
			$this->model = $this->model->join('organizations', 'organizations.id', '=', 'contacts.organization_id')->addSelect(organizationColumns());	
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