<?php namespace Nrs\Librarydirectory\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ContactType extends BaseModel {
	
	use SoftDeletingTrait;
	
	protected $table = 'contact_types';
	
}