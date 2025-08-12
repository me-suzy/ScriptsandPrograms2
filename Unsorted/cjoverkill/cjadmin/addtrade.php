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

if ($_POST["url"] != "") {
    $siteurl=$_POST["url"];
    $password=$_POST["passwd"];
    if ($password==""){
	$password="NNN";
    }
    $sql=@mysql_query("SELECT return AS ret,max_p,min_p,max_px,max_clicks,max_ip,max_ret,signup FROM cjoverkill_settings") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tmp=parse_url($siteurl);
    $domain=eregi_replace("www\.", "", $tmp["host"]);
    $tmpurl="http://".$tmp["host"].$tmp["path"];
    if ($tmp["query"] != "") { 
	$tmpurl=$tmpurl."?".$tmp["query"]; 
    }
    $sql=@mysql_query("SELECT * FROM cjoverkill_trades WHERE domain='$domain'") OR
      print_error(mysql_error());
    $tmp_sql=@mysql_num_rows($sql);
    if ($tmp_sql > 0){
	$err="This domain is already trading with us";
    }
    $sql=@mysql_query("SELECT * FROM cjoverkill_blacklist WHERE domain='$domain'") OR
      print_error(mysql_error());
    $tmp_sql=@mysql_num_rows($sql);
    if ($tmp_sql > 0){
	$err="This domain is blacklisted on the local blacklist";
    }
    if (!isset($err)) {
	if ($signup!=1){
	    $signup=1;
	}
	@mysql_query ("INSERT INTO cjoverkill_trades (domain,url,site_name,site_desc,email,icq,status,return,max_p,
						      min_p,max_px,max_clicks,max_ip,passwd,max_ret) VALUES
			('$domain','$tmpurl','$site_name','$site_desc','$email','$icq','$signup','$ret','$max_p',
			 '$min_p','$max_px','$max_clicks','$max_ip','$password', '$max_ret')") OR
	  print_error(mysql_error());
	@mysql_query ("INSERT INTO cjoverkill_stats (trade_id) VALUES (LAST_INSERT_ID())") OR
	  print_error(mysql_error());
	@mysql_query ("INSERT INTO cjoverkill_forces (trade_id) VALUES (LAST_INSERT_ID())") OR
	  print_error(mysql_error());
	$tms="$domain added to trade list";
    }
}
else {
    $tms="Add site to trade list";
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
if (isset($err)) 
{
    echo ("<div align=\"center\"><font size=\"4\"><b>Error:<br>\n
	    $err<br>\n
	    </b></font></div>
	    ");
}
else {
    echo ("<form action=\"addtrade.php\" method=\"POST\">\n
	    <div align=\"center\"><strong><font size=\"4\">$tms<br>\n
	    <br>\n
	    </font></strong> </div> 
	    <table width=\"300\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\" class=\"normalrow\">
	    <tr class=\"toprows\">
	    <td colspan=\"2\">Trade Info</td>
	    </tr> 
	    <tr> 
	    <td align=\"left\">Trade URL:</td>
	    <td align=\"left\"><input name=\"url\" type=\"text\" id=\"url\" size=\"25\" maxlength=\"250\"></td>
	    </tr>
	    <tr> 
	    <td align=\"left\">Trade Name:</td>
	    <td align=\"left\"><input name=\"site_name\" type=\"text\" id=\"site_name\" size=\"25\" maxlength=\"250\"></td>
	    </tr>
	    <tr>
	    <td align=\"left\">Site Description:</td>
	    <td align=\"left\"><input name=\"site_desc\" type=\"text\" id=\"site_desc\" size=\"25\" maxlength=\"250\"></td>
	    </tr>
	    <tr>
	    <td align=\"left\">Site Password:</td>
	    <td align=\"left\"><input name=\"passwd\" type=\"text\" id=\"passwd\" size=\"25\" maxlength=\"250\"></td>
	    </tr>
	    <tr class=\"toprows\">
	    <td colspan=\"2\">Webmaster Info</td>
	    </tr>
	    <tr>
	    <td align=\"left\">Email:</td>
	    <td align=\"left\"><input name=\"email\" type=\"text\" id=\"email\" size=\"25\" maxlength=\"250\"></td>
	    </tr>
	    <tr>
	    <td align=\"left\">ICQ:</td>
	    <td align=\"left\"><input name=\"icq\" type=\"text\" id=\"icq\" size=\"25\" maxlength=\"50\"></td>
	    </tr>
	    <tr>
	    <td align=\"left\">&nbsp;</td>
	    <td align=\"left\"><input name=\"Submit\" type=\"submit\" class=\"buttons\" value=\"Add Trade\"></td>
	    </tr>
	    </table></td>
	    </tr>
	    </table>
	    <div align=\"center\">
	    <br><br><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font>
	    </form>
	    </div>
	    ");
}
echo ("</body>
	</html>
	");
?>
