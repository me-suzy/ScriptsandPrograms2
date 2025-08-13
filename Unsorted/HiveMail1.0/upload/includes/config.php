<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | General instructions
// +-------------------------------------------------------------+
// To change the value of a setting, edit the value that is between the
// set of single quotes (i.e: 'value'). Do not remove the single quotes
// that surround the value or the file will not be functional.
// If you are not sure what the values should be please contact your host
//
// For a complete and detailed information about these settings, please
// read the INSTALL file that is included in the HiveMail ZIP archive, or
// +-------------------------------------------------------------+
// | MySQL Configuration
// +-------------------------------------------------------------+
$config = array(
	'server' => 'localhost',	// Hostname or IP address of the MySQL server
	'database' => 'mail',			// Name of your MySQL database
	'username' => 'root',		// The username and password that
	'password' => '',			// are used to log on to the MySQL server
	'persistent' => true,		// Whether to use persistent connections
);
// +-------------------------------------------------------------+
// | SMTP Configuration
// +-------------------------------------------------------------+
$smtp_config = array(
	'host' => '',				// The SMTP server host/ip
	'port' => 25,				// The SMTP server port (usually 25)
	'helo' => '',				// What to use when sending the HELO command.
								// Typically, your domain/hostname. Only include
								// the name, WITHOUT the HELO command.
	'auth' => false,			// Whether to use basic authentication or not
	'user' => '',				// Username for authentication
	'pass' => '',				// Password for authentication
);
// +-------------------------------------------------------------+
// | End of Configuration
// +-------------------------------------------------------------+

?>