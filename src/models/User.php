<?php namespace Nrs\Librarydirectory\Models;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array(
		'id', 'username', 'name', 
		'email', 'office', 'url', 
		'password', 'level', 'rlss', 
		'created_by', 'updated_by'
	);

	protected $table = 'users';

	protected $hidden = array('password', 'remember_token');
	
	public function followers() {
		return $this->belongsToMany('User', 'follower_user', 'followed_id', 'follower_id');	
	}

}
