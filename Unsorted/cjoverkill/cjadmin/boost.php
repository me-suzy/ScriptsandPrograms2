<?php

/******************************************************
 * CjOverkill version 2.0.1
 * © Kaloyan Olegov Georgiev
 * http://www.icefire.org/
 * spam@icefire.org
 * 
 * Please read the lisence before you start editing this script.
 * 
********************************************************/

include ("../cj-conf.inc.php");
include ("../cj-functions.inc.php");   
cjoverkill_connect();
 
include ("security.inc.php");

if ((!isset($_GET["id"]) || $_GET["id"]=="") && $_POST["id"]=="") { 
    print_error("Please select a trade first"); 
}
else {
    $tid=$_GET["id"];
    if ($tid==""){
	$tid=$_POST["id"];
    }
    $sql=@mysql_query("SELECT domain,boost FROM cjoverkill_trades WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    if ($boost==1){
	$tms="Remove $domain from boost list?";
    }
    else {
	$tms="Add $domain to the boost list?";
    }
}

if ($_POST["boost"]!="") {
    $tid=$_POST["id"];
    $sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    @mysql_query("UPDATE cjoverkill_trades SET boost=1 WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tms="$domain was added to the boost list";
}
elseif ($_POST["deboost"]!="") {
    $tid=$_POST["id"];
    $sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    @mysql_query("UPDATE cjoverkill_trades SET boost=0 WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tms="$domain was deleted from the boost list";
}
else {
    if ($boost==1){
	$tms="Remove $domain from boost list?";
    }
    else {
	$tms="Add $domain to the boost list?";
    }
    
}

cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$tms</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	<form action=\"boost.php\" method=\"POST\">
	<div align=\"center\"><b><font size=\"4\">$tms<br>
	<input type=\"hidden\" name=\"id\" value=\"$tid\">
	<input name=\"boost\" type=\"submit\" value=\"Add To Boost List\" class=\"buttons\"><br>
	<input name=\"deboost\" type=\"submit\" value=\"Remove From Boost List\" class=\"buttons\"><br>
	</font></strong>
	<br><br><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font>
	</div>
	</form>
	</body>
	</html>
	");
    

?>
