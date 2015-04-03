<?php
/**
 *	Package specific database info
 *
 *	The Library Directory package makes use of this separate database config data
 *	so that the db can be entirely separate from the  main Laravel installation.
 */
return array(
	'fetch' => PDO::FETCH_CLASS,
	'default' => 'mysql',
	'connections' => array(
		'mysql' => array(
			'driver'    => '',
			'host'      => '',
			'database'  => '',
			'username'  => '',
			'password'  => '',
			'charset'   => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix'    => '',
		),
	),
	'migrations' => 'migrations'
);