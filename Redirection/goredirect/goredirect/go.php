<?php

/*********************************************

Go Redirector PHP Redirector Script
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

require("goconfig.php");

/*********************************************
database connection section
*********************************************/
dbinit();

/********************************************
Site Code Section

The site code allows you to develop multiple codes
in the database for different web sites, so you
can track not only what referrer URL a visitor
is coming from, but the absolute site, too. This
also works around some popup blockers which tend
to overzealously nuke all kinds of variables.

For example, StudentPlatinum.com would use code PLAT
while ParentPLUSLoan.com would use code PPLU

*********************************************/
$sitecode="XXXX";

/********************************************
data capture section - server variables
*********************************************/
$linkid=$_GET['id'];
$visitorip= $_SERVER['REMOTE_ADDR'];
$visitorurl= $_SERVER['HTTP_REFERER'];
$visitorbrowser= $_SERVER['HTTP_USER_AGENT'];
$visitorpage=$_SERVER['REQUEST_URI'];

/********************************************
upload the data into the database
*********************************************/
$insertquery="INSERT INTO stats (ip,url,browser,date,page,linkid,sitecode) VALUES ('$visitorip','$visitorurl','$visitorbrowser',NOW(),'$visitorpage',$linkid,'$sitecode')";
// echo $insertquery;
$result=mysql_query($insertquery) or die(mysql_error());

/********************************************
redirect the user
*********************************************/
$sqlredir="SELECT * FROM redirs WHERE id=$linkid";
$rdresult=mysql_query($sqlredir) or die(mysql_error());
$row=mysql_fetch_assoc($rdresult) or die(mysql_error());
$rdurl="Location: ".$row['redirect'];
header($rdurl);

/********************************************
shut down the SQL connection and flush
*********************************************/
mysql_free_result($rdresult);
mysql_free_result($result);
$goodbye=mysql_close($conn);

?>