# LibraryDirectory

Laravel package that powers the Colorado Library Directory at http://find.coloradolibraries.org.

Installation
============

1. Install Laravel
2. Add the repository to Laravel's composer.json:

    "repositories": [
        {
            "type": "vcs",
            "url":  "https://github.com/libraryresearchservice/LibraryDirectory.git"
        }
    ],
3. Add the required packages to composer.json:

	"require": {
		"laravel/framework": "4.2.*",
		"nrs/librarydirectory": "dev-master",
		"heroicpixels/filterable": "dev-master"
	},
	
4. In the package files, edit /src/config/database.php and add your database info.
5. Add the service provider to /app/app.php

  'Nrs\Librarydirectory\LibrarydirectoryServiceProvider'
  
6. (COMING SOON; contact us directly until then) Create the MySQL database using librarydirectory.sql.
