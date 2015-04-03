<?php namespace Nrs\Librarydirectory\Models;

class Organization extends BaseModel {
	
	public function libraries() {
		return $this->hasMany('Library');
	}
			
}