<?php

/**
 *	The Filterable package provides a convenient interface for automatically
 *	filtering Eloquent models via querystrings and/or callbacks. All of the 
 *	following functions are used in combination with Filterable.
 */

use Nrs\Librarydirectory\Models\Zipcode;

/**
 *	Filter libraries by city
 */
function filterByCity($city = false) {
	return function($q) use ($city) {
		if ( $city ) {
			return $q->where(function($q) use ($city) {
				$q->where('libraries.city', 'LIKE', '%'.$city.'%');
			});
		}
	};
}

/**
 *	Filter libraries by county
 */
function filterByCounty($county = false) {
	return function($q) use ($county) {
		if ( $county ) {
			return $q->where(function($q) use ($county) {
				$q->where('libraries.county', 'LIKE', '%'.$county.'%');
			});
		}
	};
}

/**
 *	Filter libraries by distance from zipcode
 */
function filterByDistance($zipcode = false, $radius = false, $lat = false, $long = false) {
	return function($q) use ($zipcode, $radius, $lat, $long) {
		$lat = (float) $lat;
		$long = (float) $long;
		$radius = (int) $radius;
		$zip = false;
		$zipcode = (int) $zipcode;
		if ( $zipcode && $radius && ( !$lat || !$long ) ) {
			if ( $zip = Zipcode::select('latitude', 'longitude')->find($zipcode) ) {
				$lat = $zip->latitude;
				$long = $zip->longitude;	
			} else {
				return false;	
			}
		}
		if ( $radius && $lat && $long ) {
			$sql = "( (3958*3.1415926 
						* sqrt( 
							( libraries.latitude - '".$lat."' ) * ( libraries.latitude - '".$lat."') 
							+ cos(libraries.latitude/57.29578) 
							* cos( '".$lat."'/57.29578 ) 
							* ( libraries.longitude - '".$long."' ) 
							* ( libraries.longitude - '".$long."' ) 
						) / 180
					) <= ".$radius.' ) ';
			return $q->whereRaw($sql);
		}
		if ( $zipcode ) {
			return $q->whereRaw('SUBSTRING(`zip`, 1, 5) = '.$zipcode);
		}
	};
}

function filterByNearMe($latitude, $longitude) {
	if ( Input::get('near-me') ) {
		return filterByDistance(false, 10, $latitude, $longitude);
	}
}

/**
 *	Filter contacts by city
 */
function filterContactByCity($city = false) {
	return function($q) use ($city) {
		if ( $city ) {
			return $q->where(function($q) use ($city) {
				$q->where('contacts.city', 'LIKE', '%'.$city.'%');
			});
		}
	};
}

/**
 *	Filter contacts by type of contact
 */
function filterContactByContactType($contactType = false, $bool = 'any') {
	if ( is_array($contactType) ) {
		return function($q) use ($bool, $contactType) {
			return $q->whereExists(function($q) use ($bool, $contactType) {
				$q->select($q->raw(1))->from('contact_type_contact')
				  ->whereRaw('`contact_type_contact`.`contact_id` = `contacts`.`id`')
				  ->whereIn('contact_type_id', $contactType);
				if ( $bool == 'all' ) {
					$q->groupBy('contact_type_contact.contact_id')
					  ->having($q->raw('COUNT(`contact_type_contact`.`contact_id`)'), '=', sizeof($contactType));	
				}
			});
		};
	}
}

/**
 *	Filter contacts by county
 */
function filterContactByCounty($county = false) {
	return function($q) use ($county) {
		if ( $county ) {
			return $q->where(function($q) use ($county) {
				$q->where('contacts.county', 'LIKE', '%'.$county.'%');
			});
		}
	};
}

/**
 *	Specify sort order of contacts
 */
function filterContactByDefaultSort($orderby = false, $order = false) {
	return function($q) use ($orderby, $order) {
		if ( !$orderby ) {
			return $q->orderBy('contacts.name', $order)
					 ->orderBy('organizations.name');	
		}
		if ( $orderby == 'title' ) {
			return $q->orderBy('contacts.title', $order);
		} else if ( $orderby == 'type' ) {
			return $q->orderBy('contact_types.name', $order);
		}
	};
}

/**
 *	Filter contacts by keyword
 */
function filterContactByKeyword($keyword = false) {
	return function($q) use ($keyword) {
		if ( $keyword && $keyword != '' ) {
			return $q->where(function($q) use ($keyword) {
				$q->where('contacts.name', 'LIKE', '%'.$keyword.'%')
				  ->orWhere('contacts.title', 'LIKE', '%'.$keyword.'%')
				  ->orWhere('contacts.city', 'LIKE', '%'.$keyword.'%')
				  ->orWhere('organizations.name', 'LIKE', '%'.$keyword.'%');
				 if ( is_numeric($keyword) ) {
					 $q->orWhere('contacts.zip', $keyword);
				 }
			});
		}
	};
}

/**
 *	Filterable settings for contacts
 */
function filterContactSettings() {
	return array(
		'id'				=> 'contacts.id',
		'organization'		=> 'organizations.name',
		'library'			=> 'libraries.name',
		'city'				=> filterContactByCity(Input::get('city', false)),
		'library-type'		=> 'libraries.library_type',
		'contact-type'		=> filterContactByContactType(Input::get('contact-type', false), Input::get('contact-type-bool', 'any')),
		'library_id'		=> 'libraries.id',
		'organization_id'	=> 'organizations.id',
		'zipcode'			=> 'contacts.zip',
		'keyword'			=> filterContactByKeyword(Input::get('keyword', false)),
		'orderby'			=> filterContactByDefaultSort(
			Input::get('orderby', false), 
			Input::get('order', 'asc')
		)
	);	
}

/**
 *	Filterable settings for form elements
 */
function filterFormElementsSettings() {
	return array(
		'name'			=> 'name',
		'code' 			=> 'code',
		'form_name'		=> 'form_name',
		'is_hidden'		=> 'is_hidden',
		'is_active'		=> 'is_active',
		'element_order'	=> 'element_order'
	);	
}

/**
 *	Sort libraries
 */
function filterLibraryByDefaultSort($orderby = false, $order = false) {
	return function($q) use ($orderby, $order) {
		if ( !$orderby || $orderby == 'organization' ) {
			return $q->orderBy('organizations.name', $order)
					 ->orderBy('libraries.name');	
		}
		if ( $orderby == 'county' ) {
			return $q->orderBy('libraries.county', $order);
		} else if ( $orderby == 'city' ) {
			return $q->orderBy('libraries.city', $order);
		} else if ( $orderby == 'library_type' ) {
			return $q->orderby('library_types.name', $order);	
		}
	};
}

/**
 *	Filter libraries by keyword
 */
function filterLibraryByKeyword($keyword = false) {
	return function($q) use ($keyword) {
		if ( $keyword && $keyword != '' ) {
			return $q->where(function($q) use ($keyword) {
				$q->where('organizations.name', 'LIKE', '%'.$keyword.'%')
				  ->orWhere('organizations.alt_name', 'LIKE', '%'.$keyword.'%')
				  ->orWhere('libraries.name', 'LIKE', '%'.$keyword.'%')
				  ->orWhere('libraries.city', 'LIKE', '%'.$keyword.'%')
				  ->orWhere('libraries.county', 'LIKE', '%'.$keyword.'%');
				if ( is_numeric($keyword) ) {
					$q->orWhere('libraries.zip', $keyword);
				}
			});
		}
	};
}

/**
 *	Filter libraries by type of library
 */
function filterLibraryByType($type = false) {
	return function($q) use ($type) {
		if ( $type && $type != '' ) {
			return $q->where('libraries.library_type', 'LIKE', '%'.$type.'%');	
		}
	};	
}

/**
 *	Filterable settings for libraries
 */
function filterLibrarySettings() {
	return array(
		'id'				=> 'libraries.id',
		'organization'		=> 'organizations.name',
		'library'			=> 'libraries.name',
		'is_swift'			=> 'libraries.is_swift',
		'city'				=> filterByCity(Input::get('city', false)),
		'county'			=> filterByCounty(Input::get('county', false)),
		'library-type'		=> 'libraries.library_type',
		'organization_id'	=> 'organizations.id',
		'zipcode'			=> 'libraries.zip',
		'near-me'			=> filterByNearMe(Input::get('latitude', 39.0000), Input::get('longitude', -105.5000)),
		'zipcode-near'		=> filterByDistance(
			Input::get('zipcode-near', false), 
			Input::get('radius', false), 
			Input::get('latitude', false), 
			Input::get('longitude', false)
		),
		'keyword'			=> filterLibraryByKeyword(Input::get('keyword', false)),
		'orderby'			=> filterLibraryByDefaultSort(
			Input::get('orderby', false), 
			Input::get('order', 'asc')
		)
	);	
}

/**
 *	Filterable settings for maps
 */
function filterMapSettings() {
	return array(
		'id'			=> 'libraries.id',
		'organization'	=> 'organizations.name',
		'library'		=> 'libraries.name',
		'city'			=> filterByCity(Input::get('city', false)),
		'county'		=> filterByCounty(Input::get('county', false)),
		'library-type'	=> 'libraries.library_type',
		'zipcode'		=> 'libraries.zip',
		'zipcode-near'	=> filterByDistance(
			Input::get('zipcode-near', false), 
			Input::get('radius', false)
		),
		'keyword'		=> filterLibraryByKeyword(Input::get('keyword', false)),
		'orderby'		=> filterLibraryByDefaultSort(Input::get('orderby', false), Input::get('order', 'asc'))
	);	
}

/**
 *	Sort organizations
 */
function filterOrganizationByDefaultSort($orderby = false, $order = false) {
	return function($q) use ($orderby, $order) {
		if ( !$orderby || $orderby == 'name' ) {
			return $q->orderBy('organizations.name', $order);	
		}
	};
}

/**
 *	Filter organizations by keyword
 */
function filterOrganizationByKeyword($keyword = false) {
	return function($q) use ($keyword) {
		if ( $keyword ) {
			return $q->where(function($q) use ($keyword) {
				$q->where('organizations.name', 'LIKE', '%'.$keyword.'%')
				  ->orWhere('organizations.alt_name', 'LIKE', '%'.$keyword.'%');
			});
		}
	};
}

/**
 *	Filterable settings for organizations
 */
function filterOrganizationSettings() {
	return array(
		'id'			=> 'organizations.id',
		'name'			=> filterOrganizationByKeyword(Input::get('keyword', false)),
		'alt_name'		=> 'organizations.alt_name',
		'orderby'		=> filterOrganizationByDefaultSort(
			Input::get('orderby', false), 
			Input::get('order', 'asc')
		)
	);	
}

/**
 *	Filterable settings for users
 */
function filterUserSettings() {
	return array(
		'name'		=> 'users.name',
		'username'	=> 'users.username',
		'level'		=> 'users.level'
	);	
}