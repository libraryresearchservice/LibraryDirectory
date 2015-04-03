<?php namespace Nrs\Librarydirectory\Traits;

/**
 *	Methods matching the naming convention get[variable name]Attribute 
 *	will be automatically applied to the variable on get.
 */

trait DirectoryEntryGetters {

	public function getFaxAttribute($val) {
		return $this->formatTelephoneNumber($val);
	}
	
	public function getPhoneAttribute($val) {
		return $this->formatTelephoneNumber($val);
	}
	
	public function getMailZipAttribute($val) {
		return $val <= 0 ? NULL : $val;	
	}

	public function getMailZipExtensionAttribute($val) {
		return $val <= 0 ? NULL : $val;	
	}
		
	public function getZipAttribute($val) {
		return $val <= 0 ? NULL : $val;	
	}
	
	public function getZipExtensionAttribute($val) {
		return $val <= 0 ? NULL : $val;	
	}
	
}