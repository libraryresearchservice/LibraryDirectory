<?php namespace Nrs\Librarydirectory\Repositories;

/**
 *	Laravel's Eloquent ORM provides a nifty API for working with MySQL, but more often
 *	than not you'll find that you need an additional layer between Elouent and your 
 *	controllers and/or views. This class is that layer.  Extend it for each model 
 *	where you might want/need a lot of model specific SQL.
 */

class AbstractRepository {

	protected $class;
	protected $columns = array();
	protected $defaults = true;
	protected $filter = true;
	protected $filterable = array();
	protected $model;
	protected $sortable = array();
	protected $table;
	
	public function __call($name, $arguments) {
		$retrieval = in_array($name, array('all', 'find', 'findOrFail', 'first', 'get', 'lists', 'paginate'));
		if ( $retrieval ) {
			$this->setupQuery();
		}
		$this->model = call_user_func_array(array($this->model, $name), $arguments);
		if ( $retrieval ) {
			return $this->model;
		} else {
			return $this;
		}
	}
	
	public function __construct() {
		$this->resetModel();
		$this->table = $this->model->getTable();
	}
	
	public function __get($name) {
		return $this->model->{$name};
	}
	
	public function columns(array $array = array(), $append = true) {
		if ( !$append ) {
			$this->columns = array();	
		}
		$this->columns = array_merge($this->columns, $array);
		return $this->columns;	
	}
	
	public function defaults($bool) {
		$this->defaults = (bool) $bool;
		return $this;
	}
	
	public function filter($bool) {
		$this->filter = (bool) $bool;
		return $this;
	}
	
	public function filterable(array $array = array()) {
		$this->filterable = array_merge($this->filterable, $array);
		return $this;
	}
	
	public function model($model = false) {
		if ( $model ) {
			$this->model = $model;	
		}
		return $this->model;
	}
	
	public function newInstance() {
		return new $this->class;	
	}
	
	public function resetModel() {
		$this->model = $this->newInstance();
		return $this;	
	}
	
	public function setupQuery() {

	}
	
	public function sortable(array $array = array()) {
		$this->sortable = array_merge($this->sortable, $array);
		return $this->sortable;
	}
		
}