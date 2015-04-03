<?php namespace Nrs\Librarydirectory\Repositories;

use Lrs\LaravelCrosstab\LaravelCrosstab as Crosstab;

/**
 *	Wrapper for the Crosstab class
 */
class CrosstabRepository {
	
	public $model;
	public $config = array();
	
	/**
	 *	Pass undefined method calls to the Crosstab object
	 */
	public function __call($name, $arguments) {
		return call_user_func_array(array($this->model, $name), $arguments);
	}
		
	public function __construct() {
		$this->model = new Crosstab;
		$this->model->config($this->defaultConfig());
	}
	
	/**
	 *	Basic configuration settings for the Crosstab object.  These can be overridden at any time.
	 */
	public function defaultConfig() {
		$config = array(
			'a'	=> array(
				'city'	=> array(
					'header-format'	=> false,
					'id'			=> 'libraries.city',
					'join'			=> false,
					'key'			=> 'city',
					'name'			=> 'libraries.city',
					'title'			=> 'City'
				),
				'county'	=> array(
					'header-format'	=> false,
					'id'			=> 'libraries.county',
					'join'			=> false,
					'key'			=> 'county',
					'name'			=> 'libraries.county',
					'title'			=> 'County'
				),
				'library-type'	=> array(
					'header-format'	=> false,
					'id'			=> 'library_types.name',
					'join'			=> function($q) {
						return $q->join('library_types', 'library_types.id', '=', 'libraries.library_type');	
					},
					'key'			=> 'library-type',
					'name'			=> 'library_types.name',
					'title'			=> 'Library Type'
				),
				'organization'	=> array(
					'header-format'	=> false,
					'id'			=> 'organizations.name',
					'join'			=> function($q) {
						return $q->join('organizations', 'organizations.id', '=', 'libraries.organization_id');	
					},
					'key'			=> 'organization',
					'name'			=> 'organizations.name',
					'title'			=> 'Organization'
				)
			)
		);
		$config['b'] = $config['a'];
		return $config;
	}
		
}