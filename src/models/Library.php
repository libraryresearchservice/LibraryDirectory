<?php namespace Nrs\Librarydirectory\Models;

use Nrs\Librarydirectory\Traits\DirectoryEntryGetters;
use Nrs\Librarydirectory\Traits\DirectoryEntrySetters;
use Nrs\Librarydirectory\Traits\FormatTelephoneNumber;
use Nrs\Librarydirectory\Traits\JoinOrganization;

class Library extends BaseModel {
	
	use DirectoryEntryGetters, DirectoryEntrySetters, FormatTelephoneNumber, JoinOrganization;

	protected $table = 'libraries';
	
	/**
	 *	A library has many contacts via their shared organization
	 */
	public function contacts() {
		return $this->hasMany('Contact', 'organization_id', 'organization_id')->orderBy('contacts.name');	
	}
	
	/**
	 *	Required by repository
	 */
	public function scopeDefaultWheres($q) {

	}
	
	public function scopeJoinLibraryType($q) {
		return $q->leftJoin('library_types', 'libraries.library_type', '=', 'library_types.id');
	}
	
	/**
	 *	Limit to publicly visible entries
	 */
	public function scopeRestrict($q) {
		return $q->where('libraries.is_publicly_visible', 1);	
	}
	
	public function organization() {
		return $this->belongsTo('Organization', 'organization_id', 'id');	
	}
		
}