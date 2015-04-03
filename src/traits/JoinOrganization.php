<?php namespace Nrs\Librarydirectory\Traits;

trait JoinOrganization {

	public function scopeJoinOrganization($q) {
		return $q->join('organizations', 'organizations.id', '=', 'libraries.organization_id')->addSelect(organizationColumns());	
	}

	public function scopeJoinOrganizationAPI($q) {
		return $q->join('organizations', 'organizations.id', '=', 'libraries.organization_id')->addSelect(array(
			'organizations.name AS organization_name',
			'organizations.id AS organization_id',
			'organizations.alt_name AS organization_alt_name'
		));	
	}
				
}