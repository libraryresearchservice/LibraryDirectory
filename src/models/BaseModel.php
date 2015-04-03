<?php namespace Nrs\Librarydirectory\Models;

use Heroicpixels\Filterable\Filterable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;

class BaseModel extends Filterable {
   
    /**
	 *	This method is used by Eloquent to connect to the package's DB
	 *	whenever an Eloquent model is created.
	 */
	public function getConnection() {
		$db = Config::get('librarydirectory::database.default');
        return static::resolveConnection($db);
    }
}