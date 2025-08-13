<?php

/*
+--------------------------------------------------------------------------
|   IBFORUMS v1 UPGRADE FROM BETA3 TO RC1
|   ========================================
|   by Matthew Mecham and David Baxter
|   (c) 2001,2002 IBForums
|   http://www.ibforums.com
|   ========================================
|   Web: http://www.ibforums.com
|   Email: phpboards@ibforums.com
|   Licence Info: phpib-licence@ibforums.com
+---------------------------------------------------------------------------
|
|   > Wrapper script
|   > Script written by Matt Mecham
|   > Date started: 14th February 2002
|
+--------------------------------------------------------------------------
*/

//-----------------------------------------------
// USER CONFIGURABLE ELEMENTS
//-----------------------------------------------
 
// Root path

$root_path = "./";

//-----------------------------------------------
// NO USER EDITABLE SECTIONS BELOW
//-----------------------------------------------
 
error_reporting  (E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);




//--------------------------------
// Import $INFO, now!
//--------------------------------

require $root_path."conf_global.php";

//--------------------------------
// Load the DB driver and such
//--------------------------------

$INFO['sql_driver'] = !$INFO['sql_driver'] ? 'mySQL' : $INFO['sql_driver'];

$to_require = $root_path."sources/Drivers/".$INFO['sql_driver'].".php";
require ($to_require);

$DB = new db_driver;

$DB->obj['sql_database']     = $INFO['sql_database'];
$DB->obj['sql_user']         = $INFO['sql_user'];
$DB->obj['sql_pass']         = $INFO['sql_pass'];
$DB->obj['sql_host']         = $INFO['sql_host'];
$DB->obj['sql_tbl_prefix']   = $INFO['sql_tbl_prefix'];

// Get a DB connection

$DB->connect();

$DB->query("ALTER TABLE ibf_members CHANGE time_offset time_offset VARCHAR(10) DEFAULT NULL");
$DB->query("ALTER TABLE ibf_members CHANGE skin skin SMALLINT(5) DEFAULT NULL");
$DB->query("ALTER TABLE ibf_topics CHANGE forum_id forum_id SMALLINT (5) DEFAULT '0' NOT NULL");
$DB->query("ALTER TABLE ibf_members ADD dst_in_use TINYINT (1) DEFAULT '0'");

echo("<html><body><b>Running Queries:</b><br>ALTER TABLE ibf_members ADD dst_in_use TINYINT (1) DEFAULT '0'<br>ALTER TABLE ibf_topics CHANGE forum_id forum_id SMALLINT (5) DEFAULT '0' NOT NULL<br>ALTER TABLE ibf_members CHANGE time_offset time_offset VARCHAR(10) DEFAULT NULL<br>ALTER TABLE ibf_members CHANGE skin skin SMALLINT(5) DEFAULT NULL<br><br>Upgrade complete, you may now remove this file</body></html>");

exit();


//+-------------------------------------------------
// GLOBAL ROUTINES
//+-------------------------------------------------

function fatal_error($message="", $help="") {
	echo("$message<br><br>$help");
	exit;
}
?>
