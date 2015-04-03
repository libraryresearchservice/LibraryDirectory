<?php namespace Nrs\Librarydirectory\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class LibraryType extends BaseModel {
	
	use SoftDeletingTrait;
	
	protected $table = 'library_types';
	
	public function scopeChildren($q) {
		$q->where('parent_id', '>', 0);
	}
	
	public function scopeParents($q) {
		$q->where('parent_id', 0);
	}
	
}