<?php namespace Nrs\Librarydirectory\Traits;

/**
 *	Methods matching the naming convention set[variable name]Attribute 
 *	will be automatically applied to the variable on set.
 */


trait DirectoryEntrySetters {

	public function setFaxAttribute($val) {
		$this->attributes['fax'] = $this->formatTelephoneNumber($val, false);
	}
	
	public function setPhoneAttribute($val) {
		$this->attributes['phone'] = $this->formatTelephoneNumber($val, false);
	}

	public function setMailZipAttribute($val) {
		$this->attributes['mail_zip'] = $val <= 0 ? NULL : $val;	
	}

	public function setMailZipExtensionAttribute($val) {
		$this->attributes['mail_zip_extension'] = $val <= 0 ? NULL : $val;	
	}
		
	public function setZipAttribute($val) {
		$this->attributes['zip'] = $val <= 0 ? NULL : $val;	
	}
	
	public function setZipExtensionAttribute($val) {
		$this->attributes['zip_extension'] = $val <= 0 ? NULL : $val;	
	}
	
}