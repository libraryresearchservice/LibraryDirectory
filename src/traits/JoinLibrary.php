<?php namespace Nrs\Librarydirectory\Traits;

trait JoinLibrary {

	public function scopeJoinLibrary($q) {
		return $q->join('libraries', 'libraries.id', '=', 'contacts.library_id')->addSelect(libraryColumns());
	}
			
}