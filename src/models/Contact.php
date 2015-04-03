<?php namespace Nrs\Librarydirectory\Models;

use Nrs\Librarydirectory\Traits\DirectoryEntryGetters;
use Nrs\Librarydirectory\Traits\DirectoryEntrySetters;
use Nrs\Librarydirectory\Traits\FormatTelephoneNumber;
use Nrs\Librarydirectory\Traits\JoinLibrary;
use Nrs\Librarydirectory\Traits\JoinOrganization;

class Contact extends BaseModel {
	
	use DirectoryEntryGetters, DirectoryEntrySetters, FormatTelephoneNumber;
	
	public function joinOrganization() {
		return $q->join('organizations', 'organizations.id', '=', 'contacts.organization_id')->addSelect(organizationColumns());	
	}

	public function library() {
		return $this->belongsTo('Library');
	}
	
	public function organization() {
		return $this->belongsTo('Organization');
	}

	public function scopeDefaultWheres($q) {

	}
	
	public function types() {
		return $this->belongsToMany('ContactType', 'contact_type_contact', 'contact_id', 'contact_type_id');	
	}
	
}