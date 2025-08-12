<?PHP
##########################################################################  
#                                                                        #
# Request It : Song Request System                                       #
# Version: 1.0b                                                          #
# Copyright (c) 2005 by Jonathan Bradley (jonathan@xbaseonline.com)      #   
# http://requestit.xbaseonline.com                                       #         
#                                                                        #
# This program is free software. You can redistribute it and/or modify   #
# it under the terms of the GNU General Public License as published by   #
# the Free Software Foundation; either version 2 of the License.         #
#                                                                        #
##########################################################################
?>
<?php
function phpmkr_db_connect($HOST,$USER,$PASS,$DB,$PORT)
{
	$conn = mysql_connect($HOST . ":" . $PORT , $USER, $PASS);
	mysql_select_db($DB);
	return $conn;
}
function phpmkr_db_close($conn)
{
	mysql_close($conn);
}
function phpmkr_query($strsql,$conn)
{
	$rs = mysql_query($strsql,$conn);
	return $rs;
}
function phpmkr_num_rows($rs)
{
	return @mysql_num_rows($rs); 
}
function phpmkr_fetch_array($rs)
{
	return mysql_fetch_array($rs);
}
function phpmkr_free_result($rs)
{
	@mysql_free_result($rs);
}
function phpmkr_data_seek($rs,$cnt)
{
	@mysql_data_seek($rs, $cnt);
}
function phpmkr_error()
{
	return mysql_error();
}
?>
<?php
define("HOST", "localhost");  // PLEASE ENTER YOUR MYSQL DATABASE HOST HERE
define("PORT", 3306);         // PLEASE ENTER YOUR MYSQL DATABASE PORT NUMBER HERE
define("USER", "xbase_request");       // PLEASE ENTER THE USERNAME HERE
define("PASS", "request");           // PLEASE ENTER THE PASSWORD HERE
define("DB", "xbase_requests");      // PLEASE ENTER THE DATABASE NAME HERE
?>
