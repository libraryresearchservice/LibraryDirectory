<?php

/**
 *	This file contains functions that return columns 
 *	and/or column aliases.  We do this because so many
 *	models (e.g. library, contact) require joins, and 
 *	because the respective database tables share 
 *	column names (e.g. id, name).
 */

function contactColumns() {
	return array(
		'contacts.id as contact_id',
		'contacts.name as contact_name',
		'contacts.title as contact_title',
		'contacts.email as contact_email',
		'contacts.library_id as contact_library_id',
		'contacts.organization_id as contact_organization_id',
		'contacts.address_1 as contact_address_1',
		'contacts.address_2 as contact_address_1',
		'contacts.city as contact_city',
		'contacts.state as contact_state',
		'contacts.zip as contact_zip',
		'contacts.phone as contact_phone',
		'contacts.phone_extension as contact_phone_extension',
		'contacts.fax as contac_fax',
		'contacts.notes as contact_notes'
	);
}

function contactUnaliasedColumns() {
	return array(
		'contacts.id',
		'contacts.name',
		'contacts.title',
		'contacts.email',
		'contacts.library_id',
		'contacts.organization_id',
		'contacts.address_1',
		'contacts.address_2',
		'contacts.city',
		'contacts.state',
		'contacts.zip',
		'contacts.phone',
		'contacts.phone_extension',
		'contacts.fax',
		'contacts.notes'
	);	
}

function countyColumns() {
	return array(
		'counties.name as county_name',
		'counties.description as county_description'
	);
}

function defaultLibraryColumns() {
	return array(
		'libraries.id',
		'libraries.organization_id',
		'libraries.name',
		'libraries.address_1',
		'libraries.county',
		'libraries.state',
		'libraries.zip'
	);
}

function geoColumns() {
	return array(
		'libraries.latitude', 
		'libraries.longitude'
	);
}

function libraryColumns() {
	return array(
		'libraries.id',
		'libraries.old_id',
		'libraries.external_id',
		'libraries.external_id_source',
		'libraries.name',
		'libraries.alternate_name',
		'libraries.organization_id',
		'libraries.organization_name_DEPR',
		'libraries.address_1',
		'libraries.address_2',
		'libraries.city',
		'libraries.county',
		'libraries.state',
		'libraries.zip',
		'libraries.zip_extension',
		'libraries.mail_address_1',
		'libraries.mail_address_2',
		'libraries.mail_city',
		'libraries.mail_state',
		'libraries.mail_zip',
		'libraries.mail_zip_extension',
		'libraries.phone',
		'libraries.phone_extension',
		'libraries.fax',
		'libraries.email',
		'libraries.library_type',
		'libraries.library_sup_type',
		'libraries.school_type',
		'libraries.congressional_district',
		'libraries.director_name',
		'libraries.director_title',
		'libraries.director_email',
		'libraries.director_phone',
		'libraries.director_fax',
		'libraries.notes',
		'libraries.url',
		'libraries.courier_code',
		'libraries.is_clc',
		'libraries.is_federal_repository',
		'libraries.is_outlet',
		'libraries.is_state_pubs',
		'libraries.is_publicly_visible',
		'libraries.latitude',
		'libraries.longitude',
		'libraries.marc_code',
		'libraries.ils_vendor',
		'libraries.consortia',
		'libraries.ill_policies',
		'libraries.marmot_code',
		'libraries.oclc_code',
		'libraries.created_at',
		'libraries.created_by',
		'libraries.deleted_at',
		'libraries.deleted_by',
		'libraries.updated_at',
		'libraries.updated_by'
	);	
}

function libraryColumnsAliased() {
	return array(
		'libraries.library_id',
		'libraries.library_old_id',
		'libraries.library_external_id',
		'libraries.library_external_id_source',
		'libraries.library_name',
		'libraries.library_alternate_name',
		'libraries.library_organization_id',
		'libraries.library_organization_name_DEPR',
		'libraries.library_address_1',
		'libraries.library_address_2',
		'libraries.library_city',
		'libraries.library_county',
		'libraries.library_state',
		'libraries.library_zip',
		'libraries.library_zip_extension',
		'libraries.library_mail_address_1',
		'libraries.library_mail_address_2',
		'libraries.library_mail_city',
		'libraries.library_mail_state',
		'libraries.library_mail_zip',
		'libraries.library_mail_zip_extension',
		'libraries.library_phone',
		'libraries.library_phone_extension',
		'libraries.library_fax',
		'libraries.library_email',
		'libraries.library_library_type',
		'libraries.library_library_sup_type',
		'libraries.library_school_type',
		'libraries.library_congressional_district',
		'libraries.library_director_name',
		'libraries.library_director_title',
		'libraries.library_director_email',
		'libraries.library_director_phone',
		'libraries.library_director_fax',
		'libraries.library_notes',
		'libraries.library_url',
		'libraries.library_courier_code',
		'libraries.library_is_clc',
		'libraries.library_is_federal_repository',
		'libraries.library_is_outlet',
		'libraries.library_is_state_pubs',
		'libraries.library_is_publicly_visible',
		'libraries.library_latitude',
		'libraries.library_longitude',
		'libraries.library_marmot_code',
		'libraries.library_oclc_code',
	);	
}

function libraryTypeColumns() {
	return array(
		'library_types.id as library_type_id',
		'library_types.name as library_type_name',
		'library_types.parent_id as library_type_parent_id',
		'library_types.type_order as library_type_order'
	);	
}

function lrsiColumnTranslations($type = 'public') {
	if ( $type == 'public' ) {
		return array(
			'address1'	=> 'address',
			'city'		=> 'city',
			'zip'		=> 'zip',
			'county'	=> 'county',
			'namea'		=> 'director'
		);
	} else {
		return array(
			'address1'	=> 'mladdress',
			'city'		=> 'mlcity',
			'zip'		=> 'mlzip',
			'county'	=> 'county'
		);
	
	}
}

function organizationColumns() {
	return array(		
		'organizations.id as organization_id',
		'organizations.external_id as organization_external_id',
		'organizations.external_id_source as organization_external_id_source',
		'organizations.name as organization_name',
		'organizations.alt_name as organization_alt_name',
		'organizations.description as organization_description'
	);
}