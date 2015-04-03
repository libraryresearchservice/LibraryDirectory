<?php namespace Nrs\Librarydirectory;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 *	Registers the package with Laravel.  Note that we are manually
 *	registering our classes here (register()) instead of in
 *	app/config/app.php.
 */

class LibrarydirectoryServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
		$this->package('nrs/librarydirectory');
		$this->setConnection();
		include __DIR__.'/../../routes.php';
		include __DIR__.'/../../filters.php';
	}
	
	/**
	 *	Always use our package specific database connection.
	 */
	public function setConnection() {
		$con = Config::get('librarydirectory::database.default');
		if ( $con && $con !== 'default' ) {
			$config = Config::get('librarydirectory::database.connections.'.$con);
		} else {
			$con = Config::get('database.default');
			$config = Config::get('database.connections.'.$con);
		}
		Config::set('database.connections.'.$con, $config);
	}

	/**
	 * Register the service provider.
	 *
	 */
	public function register() {
		/**
		 *	Aliases are normally (and usually should be) defined in app/config/app.php, but
		 *	we'll keep things simple and define them here so that the package can be nearly 100%
		 *	self-sufficient.
		 */
		// Models
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('Analytic', 'Nrs\Librarydirectory\Models\Analytic');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('Contact', 'Nrs\Librarydirectory\Models\Contact');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('ContactType', 'Nrs\Librarydirectory\Models\ContactType');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('County', 'Nrs\Librarydirectory\Models\County');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('ErrorLog', 'Nrs\Librarydirectory\Models\ErrorLog');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('FormElement', 'Nrs\Librarydirectory\Models\FormElement');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('FormGroup', 'Nrs\Librarydirectory\Models\FormGroup');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('Library', 'Nrs\Librarydirectory\Models\Library');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('LibraryType', 'Nrs\Librarydirectory\Models\LibraryType');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('Organization', 'Nrs\Librarydirectory\Models\Organization');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('PostLog', 'Nrs\Librarydirectory\Models\PostLog');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('PublicEdit', 'Nrs\Librarydirectory\Models\PublicEdit');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('QuickSort', 'Nrs\Librarydirectory\Models\QuickSort');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('SearchForm', 'Nrs\Librarydirectory\Models\SearchForm');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('Spam', 'Nrs\Librarydirectory\Models\Spam');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('State', 'Nrs\Librarydirectory\Models\State');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('User', 'Nrs\Librarydirectory\Models\User');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('Zipcode', 'Nrs\Librarydirectory\Models\Zipcode');
		// Controlers
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('AdminController', 'Nrs\Librarydirectory\Controllers\AdminController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('AnalyticsController', 'Nrs\Librarydirectory\Controllers\AnalyticsController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('ApiController', 'Nrs\Librarydirectory\Controllers\ApiController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('BaseController', 'Nrs\Librarydirectory\Controllers\BaseController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('ContactController', 'Nrs\Librarydirectory\Controllers\ContactController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('ContactTypeController', 'Nrs\Librarydirectory\Controllers\ContactTypeController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('FormElementController', 'Nrs\Librarydirectory\Controllers\FormElementController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('HomeController', 'Nrs\Librarydirectory\Controllers\HomeController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('LibraryController', 'Nrs\Librarydirectory\Controllers\LibraryController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('LibraryTypeController', 'Nrs\Librarydirectory\Controllers\LibraryTypeController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('MapController', 'Nrs\Librarydirectory\Controllers\MapController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('OrganizationController', 'Nrs\Librarydirectory\Controllers\OrganizationController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('PublicEditsController', 'Nrs\Librarydirectory\Controllers\PublicEditsController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('StatController', 'Nrs\Librarydirectory\Controllers\StatController');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('UserController', 'Nrs\Librarydirectory\Controllers\UserController');
		// Repositories
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('ContactRepository', 'Nrs\Librarydirectory\Repositories\ContactRepository');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('CrosstabRepository', 'Nrs\Librarydirectory\Repositories\CrosstabRepository');
		\Illuminate\Foundation\AliasLoader::getInstance()->alias('LibraryRepository', 'Nrs\Librarydirectory\Repositories\LibraryRepository');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 */
	public function provides() {
		return array();
	}

}
