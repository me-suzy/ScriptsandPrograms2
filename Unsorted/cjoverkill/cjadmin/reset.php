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

if ((!isset($_GET["id"]) || $_GET["id"]=="") && $_POST["reset"]=="") { 
    print_error("Please select a trade first"); 
}

$tid=$_GET["id"];

if ($_POST["reset"]!="") {
    $tid=$_POST["id"];
    $sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tmp_sql=@mysql_fetch_array($sql);
    extract($tmp_sql);
    @mysql_query("DELETE FROM cjoverkill_stats WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    @mysql_query("INSERT INTO cjoverkill_stats (trade_id) VALUES ('$tid')") OR 
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_ref WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_iplog_in WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    @mysql_query("DELETE FROM cjoverkill_iplog_out WHERE trade_id='$tid'") OR
      print_error(mysql_error());
    $tms="$domain was reseted";
}
else {
    $sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE trade_id='$tid'") OR 
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="Are you sure you want to reset $domain?";
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
if ($_POST["reset"]!=""){
    echo ("<div align=\"center\"><strong><font size=\"4\">$tms<br></font></strong>
	    <br><br><br><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font>
	    </div>
	    ");
}
else {
    echo ("<form action=\"reset.php\" method=\"POST\">
	    <input type=\"hidden\" name=\"id\" value=\"$tid\">
	    <div align=\"center\"><strong><font size=\"4\">$tms<br>
	    <input name=\"reset\" type=\"submit\" value=\"Yes, Reset It\" class=\"buttons\">&nbsp;&nbsp;
	    <input name=\"goback\" type=\"button\" value=\"No, Let It Be\" onclick=\"window.close()\" class=\"buttons\">
	    </font></strong> </div>
	    </form>
	    ");
}
echo ("</body>
	</html>
	");

?>
