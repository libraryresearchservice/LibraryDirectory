<?php
/**
 *	Sitemap
 */
Route::get('/sitemap_index.xml', function() {
	$content = View::make('librarydirectory::sitemap_index');	
	return Response::make($content, '200')->header('Content-Type', 'text/xml');
});
Route::get('/library_sitemap.xml', function() {
	$libRepo = new LibraryRepository;
	$libraries = $libRepo->select('libraries.id')->get();
	$content = View::make('librarydirectory::library_sitemap', array('libraries' => $libraries));	
	return Response::make($content, '200')->header('Content-Type', 'text/xml');
});
Route::get('/page_sitemap.xml', function() {
	$content = View::make('librarydirectory::page_sitemap');	
	return Response::make($content, '200')->header('Content-Type', 'text/xml');
});
/**
 *	Logging in/out
 */
Route::get('/login', function() {
    return View::make('librarydirectory::login');
});

Route::post('/login', function() {
    $auth = Auth::attempt( array('username' => Input::get('username'), 'password' => Input::get('password')) );
	if ( $auth ) {
		return Redirect::to('/');
	}
	return Redirect::to('login')->with('fail', 'Oops! The username and/or password you supplied are incorrect');
});

Route::get('/logout', function() {
    Auth::logout();
	Session::flush();
	return Redirect::to('/');
});

/**
 *	Controllers are the easiest way to organize routes.  The request URI is 
 *	matched to methods in the controller classes.
 */
Route::group(array('before' => 'auth'), function() {
	/**
	 *	Editors can view and edit organization entries
	 */
	Route::group(array('before' => 'authCanEdit'), function() {
		Route::controller('admin/organization', 'OrganizationController');
	});
	/**
	 *	Admin access
	 */
	Route::group(array('before' => 'authAdmin'), function() {
		Route::controller('admin/analytics', 'AnalyticsController');
		Route::controller('admin/contact-type', 'ContactTypeController');
		Route::controller('admin/form-element', 'FormElementController');
		Route::controller('admin/library-type', 'LibraryTypeController');
		Route::controller('admin/public-edits', 'PublicEditsController');
		Route::controller('admin/user', 'UserController');
		Route::controller('admin', 'AdminController');
	});
	Route::controller('stat', 'StatController');
});
Route::controller('api/v1', 'ApiController');
Route::controller('api', 'ApiController');
Route::controller('contact', 'ContactController');
Route::controller('library', 'LibraryController');
Route::controller('map', 'MapController');
Route::controller('/', 'HomeController');

/**
 *	404
 */
App::missing(function($exception) {
    return View::make('librarydirectory::pageNotFound', array(), 404);
});