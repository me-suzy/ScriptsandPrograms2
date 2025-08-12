<?php
/*
--------------------------------------------------------------------------------
PhpDig 1.6.x
This program is provided under the GNU/GPL license.
See LICENSE file for more informations
All contributors are listed in the CREDITS file provided with this package

PhpDig Website : http://phpdig.toiletoine.net/
Contact email : phpdig@toiletoine.net
Author and main maintainer : Antoine Bajolet (fr) bajolet@toiletoine.net
--------------------------------------------------------------------------------
*/

// Connection configuration
define('PHPDIG_DB_PREFIX',$table_prefix);
define('PHPDIG_DB_HOST',$hostname);
define('PHPDIG_DB_USER',$username);
define('PHPDIG_DB_PASS',$password);
define('PHPDIG_DB_NAME',$database);

//connection to the MySql server
$id_connect=$connect;
//In the current setup a connection is already made.
//If you want to connect to a other database uncomment these lines.
/*
$id_connect = @mysql_connect (PHPDIG_DB_HOST,PHPDIG_DB_USER,PHPDIG_DB_PASS);
if (!$id_connect) {
die("Unable to connect to database : Check the connection script.\n");
}
//Select DataBase
@mysql_select_db(PHPDIG_DB_NAME,$id_connect);
*/
?>
