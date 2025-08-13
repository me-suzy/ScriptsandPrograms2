<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | MySQL Configuration
// +-------------------------------------------------------------+
$config = array(
	'server' => 'localhost',	// Hostname or IP address of the MySQL server
	'database' => 'hivemail',	// Name of your MySQL database
	'username' => '',			// The username and password that
	'password' => '',			// are used to log on to the MySQL server
	'persistent' => true,		// Whether to use persistent connections or not
);
// +-------------------------------------------------------------+
// | Special Administrative Permissions
// +-------------------------------------------------------------+
// All values in this section are comma-separated lists of user ID's. For example,
// for users 1, 3 and 6, you would enter this: 1,3,6
// To find a user's ID, visit the control panel and edit his account. The original
// admin ID is usually 1.
$admin_perms = array(
	'backup' => '1',			// Backing up the database from the control panel.
	'viewlog' => '1',			// Viewing the complete admin log in the control panel.
	'prunelog' => '1',			// Pruning parts of the admin log from the control panel.
);
// +-------------------------------------------------------------+
// | End of Configuration
// +-------------------------------------------------------------+

?>