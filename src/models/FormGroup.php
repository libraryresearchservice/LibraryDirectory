<?php namespace Nrs\Librarydirectory\Models;

class FormGroup extends BaseModel {

	protected $table = 'form_groups';
	
	public function elements() {
		return $this->hasMany('FormElement', 'group_id');	
	}

}