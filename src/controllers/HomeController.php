<?php namespace Nrs\Librarydirectory\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\FormElement;
use Nrs\Librarydirectory\Models\Library;
use Nrs\Librarydirectory\Models\User;

class HomeController extends BaseController {
	
	public function __construct() {
		parent::__construct();
	}

	public function getAbout() {
		return View::make('librarydirectory::new');	
	}
		
	/**
	 *	Fetch paginated list of libraries
	 */
	public function getIndex() {
		$libraries = $this->libRepo;
		$libraries = $libraries->paginate(50);
		$this->appendQuerystringToPagination($libraries, array('page'));
		return View::make('librarydirectory::library.all', array(
			'data' 			=> $libraries,
			'sortable' 		=> $this->libRepo->sortable(),
			'querystring'	=> $this->querystring
		));
	}

	public function getDoh() {
		return View::make('librarydirectory::doh');
	}
		
	public function getNew() {
		return View::make('librarydirectory::new');	
	}
	
	public function postDoh() {
		$email = Input::get('email', false);
		if ( $email && $user = User::where('email', $email)->first() ) {
			$tempPassword = substr(sha1(time().'oneNationUnderPugs'.rand()), 0, 20);
			$user->password = Hash::make($tempPassword);
			$user->remember_token = '';
			$user->save();
			$subject = 'Library Directory Password Reset';
			$body = "Your Library Directory (".url().") password has been reset to:\n\r";
			$body .= $tempPassword."\n\r";
			$body .= "Your username is:\n\r";
			$body .= $user->username."\n\r";
			$body .= 'Please change your password immediately after logging in.';
			mail($user->email, $subject, $body);
			$msg = Session::flash('success', 'Success! A new password was emailed to "'.$user->email.'."');
			$this->db->table('user_password_resets')->insert(array(
				'user_id'	=> $user->id
			));
		} else {
			Session::flash('fail', 'Oops. We could not find an account associated with email "'.$email.'."');
			return Redirect::to('doh');	
		}
		return Redirect::to('login');
	}
	
	public function getTest() {
		return View::Make('librarydirectory::test', array('con' => $this->defaultDbConfig, 'db' => $this->db));
	
	}

	public function missingMethod($parameters = array()) {
		return Response::view('librarydirectory::pageNotFound', array(), 404);
	}
	
}