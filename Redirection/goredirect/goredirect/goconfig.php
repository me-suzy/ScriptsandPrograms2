<?php

/*********************************************

Go Redirector PHP Configuration Script
Version 0.4
Copyright (c) 2003-2004, StudentPlatinum.com and
the Edvisors Network

Provided under BSD license located at
http://www.studentplatinum.com/scripts/license.php

It is a violation of the license to distribute
this file without the accompanying license and
copyright information.

You may obtain the latest version of this software
at http://www.studentplatinum.com/scripts/

Please visit our corporate page at:
http://www.edvisorsnetwork.com/

*********************************************/

/*********************************************
database connection - edit as needed
*********************************************/

function dbinit()
{
    $server="";
    $user="";
    $pass="";
    $db="goredirect";
    $connection=mysql_connect($server,$user,$pass) or die(mysql_error());
    $database=mysql_select_db($db) or die(mysql_error());
    return NULL;
}
?>

