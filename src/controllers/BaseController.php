<?php namespace Nrs\Librarydirectory\Controllers;

/**
 *	A base controller is a convenient place to put properties/methods that might be needed
 *	and/or used by most controllers, e.g. the database connection, repositories, etc.
 */

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Nrs\Librarydirectory\Models\PublicEdit;
use Nrs\Librarydirectory\Models\QuickSort;
use Nrs\Librarydirectory\Models\SearchForm;
use Nrs\Librarydirectory\Repositories\ContactRepository;
use Nrs\Librarydirectory\Repositories\LibraryRepository;

class BaseController extends Controller {
	
	protected $contactRepo;
	protected $db;
	protected $defaultDbConfig;
	protected $haveEdits = false;
	protected $keyword;
	protected $libRepo;
	protected $publicEdit;
	protected $quickSort;
	protected $searchForm;
	protected $querystring;
	
	public function __construct() {
		// Provide an instance of our directory specific database connection
		$this->defaultDbConfig = Config::get('librarydirectory::database.default');
		$this->db = DB::connection($this->defaultDbConfig);
		// Repositories
		$this->contactRepo = new ContactRepository;
		$this->libRepo = new LibraryRepository;
		// Allow for users to submit corrections to directory entries
		$this->publicEdit = new PublicEdit;
		// Models used to create search forms
		$this->quickSort = new QuickSort;
		$this->searchForm = new SearchForm;
		// Querystring data are used by search forms and are also passed to paginated results
		parse_str($_SERVER['QUERY_STRING'], $this->querystring);
		// If logged in, see if public edits are available
		if ( Auth::check() ) {
			$this->haveEdits = (bool) $this->publicEdit->count();	
		}
		// Share properties with all views
		View::share('haveEdits', $this->haveEdits);
		View::share('quickSort', $this->quickSort);
		View::share('searchForm', $this->searchForm);
	}
	
	/**
	 *	Add array elements from $this->querystring to Paginator object
	 */
	public function appendQuerystringToPagination($model, array $ignore = array()) {
		if ( is_a($model, 'Illuminate\Pagination\Paginator') ) {
			foreach ( $this->querystring as $k => $v ) {
				if ( $v !== '' && !in_array($k, $ignore) ) {
					$model->appends(array($k => $v));	
				}
			}
		}
		return $model;
	}
	
}