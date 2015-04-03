<?php
/**
 *	Determine if key exists in mulit-dimensional array
 */
function arrayKeyExistsMulti($needle,$haystack) {
	if ( is_array($haystack) ) {
		foreach ( $haystack as $k => $v ) {
			if ( arrayKeyExistsMulti($needle, $k) ) {
				return true;
			}
		}
	} else {
		if ( $needle == $haystack ) {
			return true;
		}
	}
	return false;
}

/**
 *	Merge multi-dimensional array while removing duplicates and preserving string values.
 *	Borrowed from http://www.php.net/manual/en/function.array-merge-recursive.php#92195
 */
function arrayMergeRecursiveDistinct(array &$array1, array &$array2) {
	$merged = $array1;
	foreach ( $array2 as $key => &$value ) {
		if ( is_array($value) && isset($merged[$key]) && is_array($merged[$key]) ) {
			$merged[$key] = arrayMergeRecursiveDistinct($merged[$key], $value);
		} else {
			$merged[$key] = $value;
		}
	}
	return $merged;
}

/**
 *	Implode and wrap array keys
 */
function implodeKeysWrapped($before, $after, $glue, $array) {
	$out = '';
    foreach ( $array as $k => $v ) {
        $out .= $before.$k.$after.$glue;
    }
    return substr($out, 0, -strlen($glue));
}

/**
 *	Implode and wrap array values
 */
function implodeWrapped($before, $after, $glue, $array) {
    $out = '';
    foreach ( $array as $item ){
        $out .= $before.$item.$after.$glue;
    }

    return substr($out, 0, -strlen($glue));
}

/**
 *	Determine if value exists in multi-dimensional array
 */
function inArrayMulti($needle,$haystack) {
	if ( is_array($haystack) ) {
		foreach ( $haystack as $k => $v ) {
			if ( inArrayMulti($needle,$v) ) {
				return true;
			}
		}
	} else {
		if ( $haystack == $needle ) {
			return true;
		}
	}
	return false;
}