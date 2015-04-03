<?php namespace Nrs\Librarydirectory\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Nrs\Librarydirectory\Traits\FormatTelephoneNumber;
use Nrs\Librarydirectory\Traits\JoinOrganization;

class PublicEdit extends BaseModel {
	
	use FormatTelephoneNumber, JoinOrganization, SoftDeletingTrait;
	
	protected $primaryKey = 'edit_id';
	protected $table = 'public_edits';

	public function getFaxAttribute($val) {
		return $this->formatTelephoneNumber($val);
	}
	
	public function getPhoneAttribute($val) {
		return $this->formatTelephoneNumber($val);
	}

	public function getMailZipAttribute($val) {
		return $val == 0 ? NULL : $val;	
	}

	public function getMailZipExtensionAttribute($val) {
		return $val == 0 ? NULL : $val;	
	}
		
	public function getZipAttribute($val) {
		return $val == 0 ? NULL : $val;	
	}
	
	public function getZipExtensionAttribute($val) {
		return $val == 0 ? NULL : $val;	
	}
	
	public function library() {
		return $this->hasOne('Library', 'id', 'id');	
	}
	
	public function setFaxAttribute($val) {
		$this->attributes['fax'] = $this->formatTelephoneNumber($val, false);
	}
	
	public function setPhoneAttribute($val) {
		$this->attributes['phone'] = $this->formatTelephoneNumber($val, false);
	}

	public function setMailZipAttribute($val) {
		$this->attributes['mail_zip'] = $val == 0 || $val == false || $val == '' ? NULL : $val;	
	}

	public function setMailZipExtensionAttribute($val) {
		$this->attributes['mail_zip_extension'] = $val == 0 || $val == false || $val == '' ? NULL : $val;	
	}
		
	public function setZipAttribute($val) {
		$this->attributes['zip'] = $val == 0 || $val == false || $val == '' ? NULL : $val;	
	}
	
	public function setZipExtensionAttribute($val) {
		$this->attributes['zip_extension'] = $val == 0 || $val == false || $val == '' ? NULL : $val;	
	}
		
}