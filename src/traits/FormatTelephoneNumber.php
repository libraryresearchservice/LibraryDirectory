<?php namespace Nrs\Librarydirectory\Traits;

trait FormatTelephoneNumber {

	public function formatTelephoneNumber($val, $display = true) {
		if ( $val ) {
			$val = preg_replace('/[^0-9]/', '', $val);
			if ( $display ) {
				return sprintf('(%s) %s-%s', substr($val, 0, 3), substr($val, 3, 3), substr($val, 6,4));
			}
		}
		return $val;
	}
			
}