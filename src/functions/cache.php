<?php

use Nrs\Librarydirectory\models\ContactType;
use Nrs\Librarydirectory\models\LibraryType;

/**
 *	Various cacheable and/or statically defined arrays.
 */

function contactTypes($id = false, $key = false) {
	// Get cached data if exists or use callback
	$data = Cache::get('contactTypes', function() {
		$data = ContactType::orderBy('type_order')->get();
		$out = array();
		if ( $data ) {
			foreach ( $data as $v ) {
				if ( $v->parent_id == 0 ) {
					$out[$v->id] = $v->toArray();
				}
			}
		}
		Cache::forever('contactTypes', $out);
	});
	// Return array for specified key, or value for specified attribute
	if ( $id || $key ) {
		if ( isset($data[$id]) ) {
			if ( $key ) {
				if ( isset($data[$id][$key]) ) {
					return $data[$id][$key];	
				} else {
					return false;	
				}
			}
			return $data[$id]['name'];
		}
	}
	return $data;
}

/**
 *	Depreciated library codes
 */
function libraryCodes() {
	return array(
		'01' 	=> 'Public',
		'010A' 	=> 'Public - District ',
		'010A' 	=> 'Public - District Headquarters',
		'010A' 	=> 'Public - District Non-Headquarters',
		'02' 	=> 'Academic',
		'020B' 	=> 'Academic - Medical',
		'020C' 	=> 'Academic - Law',
		'020D' 	=> 'Academic - Community College',
		'020E'	=> 'Academic - For-profit',
		'03' 	=> 'School',
		'030A' 	=> 'School - Public',
		'030B' 	=> 'School - Non-Public',
		'030E' 	=> 'School - BOCES',
		'030F'	=> 'School - District',
		'04' 	=> 'Special',
		'040B' 	=> 'Special - Medical',
		'040C' 	=> 'Special - Law',
		'040D' 	=> 'Special - Corporate',
		'040E' 	=> 'Special - US Government',
		'040F' 	=> 'Special - State Government',
		'040G' 	=> 'Special - Technical',
		'040H' 	=> 'Special - Military',
		'08' 	=> 'Trustee',
		'080A' 	=> 'Trustee - Chair of the Board',
		'14' 	=> 'Friends of the Library'
	);	
}

/**
 *	Depreciated library sub-codes
 */
function librarySubTypes($parent = false) {
	// Get cached data if exists or use callback
	return Cache::get('librarySubTypes', function() use ($parent) {
		$out = array();
		foreach ( libraryTypes() as $k => $v ) {
			if ( isset($v['children']) && is_array($v['children']) ) {
				foreach ( $v['children'] as $k1 => $v1 ) {
					if ( $parent == false || $parent == $v1['parent_id'] ) {
						$out[$v1['id']] = $v1;	
					}
				}
			}
		}
		Cache::forever('librarySubTypes', $out);
		return $out;
	});
}

/**
 *	Library codes
 */
function libraryTypes($id = false, $key = false) {
	// Get cached data if exists or use callback
	$data = Cache::get('libraryTypes', function() {
		$data = LibraryType::orderBy('type_order')->get();
		$out = array();
		if ( $data ) {
			foreach ( $data as $v ) {
				if ( $v->parent_id == 0 ) {
					$out[$v->id] = $v->toArray();
					$out[$v->id]['children'] = array();
				}
			}
			foreach ( $data as $v ) {
				if ( $v->parent_id > 0 ) {
					$out[$v->parent_id]['children'][$v->id] = $v->toArray();
				}
			}
		}
		Cache::forever('libraryTypes', $out);
	});
	// Return array for specified key, or value for specified attribute
	if ( $id || $key ) {
		if ( isset($data[$id]) ) {
			if ( $key ) {
				if ( isset($data[$id][$key]) ) {
					return $data[$id][$key];	
				} else {
					return false;	
				}
			}
			return $v;
		}
	}
	return $data;
}