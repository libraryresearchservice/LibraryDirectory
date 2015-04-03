<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\User;

class UserController extends BaseController {

	protected $user;
	
	public function __construct(User $user) {
		parent::__construct();
		$this->user = $user;
	}
	
	public function getCreate() {
		return View::make('librarydirectory::user.edit', array('user' => $this->user));
	}
	
	public function getEdit($id = false) {
		if ( $id && $user = $this->user->find($id) ) {
			return View::make('librarydirectory::user.edit', array('user' => $user));
		}
		return View::make('librarydirectory::user.notFound', array('id' => $id));
	}
	
	public function getIndex() {
		$users = $this->user->filterColumns(filterUserSettings());
		if ( !userIsAdmin() ) {
			$users = $users->where('id', Auth::user()->id);	
		}
		if ( !Input::get('orderby', false) ) {
			$users = $users->orderBy('name');
		}
		$users = $users->get();
		return View::make('librarydirectory::user.all', array(
			'data'		=> $users,
			'sortable'	=> userSortables()
		));
	}

	public function postSave() {
		$delete = Input::get('delete', false);
		$fillable = array(
			'id',
			'username',
			'name',
			'email',
			'office',
			'url',
			'password',
			'password_confirm',
			'level',
			'edit_notifications'
		);
		$fillable = array_fill_keys($fillable, false);
		$inputs = array_merge($fillable, Input::all());
		if ( $delete ) {
			$this->user->where('id', $inputs['id'])->delete();
			return Redirect::to('admin/user/')->with('success', 'Success! User deleted.');
		}
		$validator = Validator::make($inputs, array(
				'name' => 'required',
				'username'	=> 'required',
				'password' => 'min:4|same:password_confirm'
			)
		);
		if ( $validator->fails() ) {
			if ( $inputs['id'] ) {
				return Redirect::to('admin/user/edit/'.$inputs['id'])->withErrors($validator);
			} else {
				return Redirect::to('admin/user/create')->withErrors($validator);
			}
		}
		unset($inputs['password_confirm']);
		if ( $inputs['password'] != '' ) {
			$inputs['password'] = Hash::make($inputs['password']);
		} else {
			unset($inputs['password']);	
		}
		foreach ( $inputs as $k => $v ) {
			if ( $v === false ) {
				unset($inputs[$k]);	
			}
		}
		if ( isset($inputs['id']) && $inputs['id'] && $user = $this->user->find($inputs['id']) ) {
			$inputs['updated_at'] = date('Y-m-d h:i:s', time());
			$inputs['updated_by'] = Auth::user()->id;
			$this->user->where('id', $inputs['id'])->update($inputs);
			return Redirect::to('admin/user/edit/'.$inputs['id'])->with('success', 'Success! User updated.');
		} else if ( !isset($inputs['id']) || !$inputs['id'] ) {
			$inputs['created_at'] = date('Y-m-d h:i:s', time());
			$inputs['created_by'] = Auth::user()->id;
			$user = $this->user->create($inputs);
			return Redirect::to('admin/user/edit/'.$user->id)->with('success', 'Success! User created.');
		}
		return View::make('librarydirectory::user.notFound', array('id' => $inputs['id']));
	}
	
}