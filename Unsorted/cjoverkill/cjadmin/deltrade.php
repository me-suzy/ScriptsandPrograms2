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

if (($_GET["id"]=="" || !isset($_GET["id"])) && !isset($_POST["id"])) { 
    print_error("Please select a trade first"); 
}
if (($_POST["delete"]!="" || $_POST["blacklist"]!="") && $_POST["id"]>4) {
    $tid=$_POST["id"];
    $sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    if ($_POST["blacklist"]!=""){
	$sql=@mysql_query("SELECT domain,email,icq FROM cjoverkill_trades WHERE trade_id='$tid'") OR
	  print_error(mysql_error());
	$tmp=@mysql_fetch_array($sql);
	extract($tmp);
	@mysql_query("INSERT INTO cjoverkill_blacklist (domain,email,icq,reason) VALUES ('$domain','$email','$icq','Blacklisted by admin')") OR
	  print_error(mysql_error());
    }
    @mysql_query("DELETE FROM cjoverkill_trades WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_stats WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_forces WHERE trade_id='$tid'") OR
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_iplog_in WHERE trade_id='$tid'") OR
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_iplog_out WHERE trade_id='$tid'") OR
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_ref WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tms="$domain was deleted form the trade list";
    if ($_POST["blacklist"]!=""){
	$tms="$domain was deleted and blacklisted";
    }
}
else {
    $sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE trade_id=$_GET[id]") OR 
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="Delete $domain?"; 
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
	");
if ($_POST["delete"]!="" || $_POST["blacklist"]!="") {
    echo ("<div align=\"center\"><strong><font size=\"4\">$tms<br></font></strong>
	    <br><br><br><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font>
	    </div>
	    ");
}
else {
    echo ("<form action=\"deltrade.php\" method=\"POST\">
	    <input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">
	    <div align=\"center\"><strong><font size=\"4\">Are you sure you want to delete $domain ?<br>	
	    <input name=\"delete\" type=\"submit\" value=\"Yes, Delete\" class=\"buttons\">&nbsp;&nbsp;
	  <input name=\"goback\" type=\"button\" value=\"No, Let It Be\" onclick=\"window.close()\" class=\"buttons\"><br><br>
	    <input name=\"blacklist\" type=\"submit\" value=\"Yes, Delete And Blacklist\" class=\"buttons\">
	    </font></strong> </div>
	    </form>
	    ");
}
echo ("</body>
	</html>
	");
?>

