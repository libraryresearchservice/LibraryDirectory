<?php

/**
 *	Capture raw analytic data
 */
App::after(function($request, $response) {
	if ( !isBot() ) {
		Analytic::insert(array(
			'ip' 			=> sprintf('%u', ip2long($request->server('REMOTE_ADDR'))), 
			'uri' 			=> $_SERVER['REQUEST_URI'], 
			'user_agent'	=> $request->server('HTTP_USER_AGENT'),
			'method'		=> $request->server('REQUEST_METHOD')
		));
	}
});

/**
 *	Custom error handler for storing error messages in DB
 */
App::before(function($request, $response) {
	Log::listen(function($level, $message, $context) use ($request, $response) {
		ErrorLog::insert(array(
			'message'		=> $message->getMessage(), 
			'line' 			=> $message->getLine(),
			'file'			=> $message->getFile(),
			'url'			=> $_SERVER['REQUEST_URI'],
			'user_id' 		=> Auth::user() ? Auth::user()->id : 0, 
			'ip' 			=> $request->server('REMOTE_ADDR')
		));
	});
});

/**
 *	Check if user is an admin
 */
Route::filter('authAdmin', function() {
	if ( !userIsAdmin() ) {
		return Redirect::to('/');
	}
});

/**
 *	Check if user has edit priv
 */
Route::filter('authCanEdit', function() {
	if ( !userCanEdit() ) {
		return Redirect::to('/');
	}
});

/**
 *	Save POST data (except at login)
 */
Route::filter('capturePostData', function() {
	if ( Request::segment(1) != 'login' ) {
		$input = Input::all();
		foreach ( $input as $k => $v ) {
			if ( stristr($k, 'password') !== false ) {
				unset($input[$k]);
			}
		}
		PostLog::insert(array(
			'user_id'	=> Auth::user() ? Auth::user()->id : 0,
			'post_data'	=> json_encode($input, JSON_PRETTY_PRINT),
			'ip'		=> Request::server('REMOTE_ADDR')
		));
	}
});
Route::when('*', 'capturePostData', array('post'));