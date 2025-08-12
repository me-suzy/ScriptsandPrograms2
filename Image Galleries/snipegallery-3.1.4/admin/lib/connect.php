<?php
/**
* connect.php
*
* This file simply contains the database connection code.
* It pulls from the config file in order to obtain the correct
* variables to feed mySQL
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/

if (!@mysql_pconnect($cfg_database_host,$cfg_database_user,$cfg_database_pass))
	die('<span class="errortxt">FATAL ERROR: cannot connect to mySQL server <br>host: '.$cfg_database_host.' <br>user: '.$cfg_database_user.' </span>');
if (!@mysql_select_db($cfg_database_name)) 
	die('<span class="errortxt">FATAL ERROR: I cant make up my mind!! Cannot select MySQL database "'.$cfg_database_name.'"</span>');
?>