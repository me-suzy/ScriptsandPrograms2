<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any         \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the          \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	//!database settings
	define ( '_DB_SERVER',		'localhost' );
	define ( '_DB_USER',		'root' );
	define ( '_DB_PASS',			'' );
	define ( '_DB_NAME',		'v5' );
	
	//!paths/locations
	define ( '_BASE_PATH',		'C:/www/web/' );
	define ( '_LIB_INCLUDE_PATH',	_BASE_PATH . 'lib/' );
	define ( '_TEMPLATE_PATH',		_BASE_PATH . 'templates/' );
	define ( '_BACKUP_PATH',		_BASE_PATH . 'db_backup/' );
	define ( '_LOGFILES_PATH',		_BASE_PATH . 'logs/' );
	define ( '_SITE_URL',		'http://localhost' );
	define ( '_ADMIN_URL',		_SITE_URL . '/admin' );
	



	//!update settings
	define ( '_SYSTEM_UPDATEHOST',	'' );
	define ( '_SYSTEM_UPDATEKEY',	'WST' );


	
	//! DO NOT EDIT BELOW THIS LINE !//
	
	if(!@include _LIB_INCLUDE_PATH . ".main.php")
	{
		exit("Could not load main file (" . _LIB_INCLUDE_PATH . ".main.php" . ")");
	}

?>