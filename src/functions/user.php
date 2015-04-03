<?php

/**
 *	Check if user is "admin" or "update"
 */
function userCanEdit() {
	return userIsLoggedIn() && ( Auth::user()->level == 'admin' || Auth::user()->level == 'update' );
}

/**
 *	Check if user has role "admin"
 */
function userIsAdmin() {
	return userIsLoggedIn() && Auth::user()->level == 'admin';	
}

/**
 *	Check if user has role "guest"
 */
function userIsGuest() {
	return userIsLoggedIn() && Auth::user()->level == 'guest';	
}

/**
 *	Check if user can edit
 */
function userIsEditor() {
	return userIsLoggedIn() && Auth::user()->level == 'update';	
}

/**
 *	Check if user is logged in
 */
function userIsLoggedIn() {
	return Auth::check();	
}