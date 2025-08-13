<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
include ('config.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN"> 
<html>
<head>
       <title>PHP Version Checking Utility</title>
</head>
<body>
    <?  echo "PHP version: ".phpversion(); ?>
    <?
mysql_connect($host,$user,$pass);
@mysql_select_db($database) or print( "<br>Unable to select database, check mysql setup in config.php");

$mysqlversion = @mysql_query("SELECT Version() as version");
$version = @mysql_fetch_array($mysqlversion);

echo "<br>MySQL Version: $version[version] <br>";
echo phpinfo();
?>
</body>
</html>
