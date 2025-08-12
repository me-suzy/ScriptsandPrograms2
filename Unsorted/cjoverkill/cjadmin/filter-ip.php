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

if ($_POST["edit"]!="" && $_POST["fid"]!=""){
    $sql=@mysql_query("SELECT ip_from, ip_to, reason, hour, auto 
			FROM cjoverkill_filter_ip WHERE
			fid='$fid'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $sql=@mysql_query("SELECT INET_NTOA('$ip_from') AS ip_froma, INET_NTOA('$ip_to') AS ip_toa") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="IP filter edit for $ip_froma - $ip_toa";
}
elseif ($_POST["edit"]!="" && $_POST["fid"]==""){
    print_error("Please slect an IP range to edit first");
}
elseif ($_POST["update"]!="" && $_POST["ip_froma"]!="" && $_POST["ip_toa"]!="" && $_POST["fid"]!=""){
    $fid=$_POST["fid"];
    $ip_froma=$_POST["ip_froma"];
    $ip_toa=$_POST["ip_toa"];
    $reason=$_POST["reason"];
    @mysql_query("UPDATE cjoverkill_filter_ip SET
		   ip_from=INET_ATON('$ip_froma'), 
		   ip_to=INET_ATON('$ip_toa'),
		   reason='$reason',
		   auto='0',
		   hour='0'
		   WHERE fid='$fid'") OR
      print_error(mysql_error());
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_ip WHERE fid='$fid'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $sql=@mysql_query("SELECT INET_NTOA('$ip_from') AS ip_froma, INET_NTOA('$ip_to') AS ip_toa") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="IP filter updated for $ip_froma - $ip_toa";
}
elseif ($_POST["add"]!=""){
    $tms="Add new IP range to filter";
}
elseif ($_POST["addnew"]!=""){
    if ($_POST["ip_froma"]=="" || $_POST["ip_toa"]==""){
	print_error("Please provide a valid IP range");
    }
    $ip_froma=$_POST["ip_froma"];
    $ip_toa=$_POST["ip_toa"];
    $reason=$_POST["reason"];
    $auto="0";
    $ahour="0";
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_ip WHERE ip_from=INET_ATON('$ip_froma') OR ip_to=INET_ATON('$ip_toa')") OR
      print_error(mysql_error());
    $tmp=@mysql_num_rows($sql);
    if ($tmp > 0) {
	print_error("One of these IPs is already in the filter");
    }
    @mysql_query("INSERT INTO cjoverkill_filter_ip (ip_from,ip_to,reason,auto,hour) VALUES 
		   (INET_ATON('$ip_froma'),INET_ATON('$ip_toa'),'$reason','$auto','$ahour')") OR
      print_error(mysql_error());
    $tms="$ip_froma - $ip_toa IP range added to the Filter";
}
elseif ($_POST["idelete"]!="" && $_POST["fid"]!=""){
    $fid=$_POST["fid"];
    @mysql_query("DELETE FROM cjoverkill_filter_ip WHERE fid='$fid'") OR 
      print_error(mysql_error());
    $tms="IP Filter Updated";
}
else {
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_ip ORDER BY ip_from ASC, ip_to ASC, hour ASC, auto ASC") OR
      print_error(mysql_error());
    $tms="IP filter setup";
}

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$tms</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\">
	");
if ($_POST["edit"]!="" && $_POST["fid"]!="" || $_POST["update"]!=""){
    $fid=$_POST["fid"];
    echo ("<form action=\"filter-ip.php\" method=\"POST\">
	    <div align=\"center\"><font size=\"4\"><b>$tms</b></font><br>
	    <br>
	    </div>
	    <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr>
	    <td colspan=\"2\" class=\"toprows\">Edit $country</td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>IP From:  </b></td>
	    <td align=\"left\"><input name=\"ip_froma\" type=\"text\" id=\"ip_froma\" size=\"16\" maxlength=\"16\" value=\"$ip_froma\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>IP To:  </b></td>
	    <td align=\"left\"><input name=\"ip_toa\" type=\"text\" id=\"ip_toa\" size=\"16\" maxlength=\"16\" value=\"$ip_toa\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Reason:  </b></td>
	    <td align=\"left\"><textarea name=\"reason\" cols=\"30\" rows=\"5\" id=\"reason\">$reason</textarea></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\"><input name=\"fid\" type=\"hidden\" id=\"fid\" value=\"$fid\"></td>
	    <td align=\"left\"><input name=\"update\" type=\"submit\" class=\"buttons\" id=\"update\" value=\"Update\"></td>
	    </tr>
	    </table></td>
	    </tr>
	    </table>
	    </form>
	    ");
}
elseif ($_POST["delete"]!="" && $_POST["fid"]!=""){
    $fid=$_POST["fid"];
    $sql5=@mysql_query("SELECT * FROM cjoverkill_filter_ip WHERE fid='$fid'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql5);
    extract($tmp);
    $sql5=@mysql_query("SELECT INET_NTOA('$ip_from') AS ip_froma, INET_NTOA('$ip_to') AS ip_toa") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql5);
    extract($tmp);
    echo("<form action=\"filter-ip.php\" method=\"POST\">
	   <input type=\"hidden\" name=\"fid\" value=\"$fid\">
	   <div align=\"center\"><b><font size=\"4\">Delete $ip_froma - $ip_toa From Filter?<br>
	   <input name=\"idelete\" type=\"submit\" value=\"Yes, Delete It\" class=\"buttons\">&nbsp;&nbsp;
	 <input name=\"goback\" type=\"submit\" value=\"No, Let It Be\" class=\"buttons\">
	   </font></b> </div>
	   </form>
	   ");   
}
elseif ($_POST["idelete"]!="" && $_POST["fid"]!=""){
    echo ("<div align=\"center\"><b><font size=\"4\">Filter was updated<br></font></b>
	    <br><br><br><br><br><br><br><br>
	    </div>
	    ");
}
elseif ($_POST["add"]!="" || $_POST["addnew"]!=""){
    echo ("<form action=\"filter-ip.php\" method=\"POST\">
	    <div align=\"center\"><font size=\"4\"><b>$tms</b></font><br>
	    <br>
	    </div>
	    <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td colspan=\"2\" class=\"toprows\">Add IP Range To Filter</td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>IP From: </b></td>
	    <td align=\"left\"><input name=\"ip_froma\" type=\"text\" size=\"16\" maxlength=\"16\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>IP To: </b></td>
	    <td align=\"left\"><input name=\"ip_toa\" type=\"text\" size=\"16\" maxlength=\"16\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\"><b>Reason:     </b></td>
	    <td align=\"left\"><textarea name=\"reason\" cols=\"30\" rows=\"5\" id=\"reason\"></textarea></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\">&nbsp;</td>
	    <td align=\"left\"><input name=\"addnew\" type=\"submit\" class=\"buttons\" value=\" Add \"></td>
	    </tr>
	    </table></td>
	    </tr>
	    </table>
	    </form>
	    ");
}
else {
    echo ("<form action=\"filter-ip.php\" method=\"POST\">
	    <div align=\"center\"><b><font size=\"4\">$tms</font></b><br>
	    <br>
	    </div>
	    <table width=\"550\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr class=\"toprows\">
	    <td width=\"25%\">IP From</td>
	    <td width=\"25%\">IP To</td>
	    <td width=\"25%\">AUTO</td>
	    <td width=\"25%\">Reason</td>
	    <td>&nbsp;</td>
	    </tr>
	    ");
    while ($tmp=@mysql_fetch_array($sql)){
	extract($tmp);
	if ($auto=="1"){
	    $filt="YES";
	}
	else {
	    $filt="NO";
	}
	$sql2=@mysql_query("SELECT INET_NTOA('$ip_from') AS ip_froma, INET_NTOA('$ip_to') AS ip_toa") OR
	  print_error(mysql_error());
	$tmp2=@mysql_fetch_array($sql2);
	extract($tmp2);
	echo ("<tr class=\"normalrow\">
		<td align=\"left\">$ip_froma</td>
		<td align=\"left\">$ip_toa</td>
		<td align=\"left\">$filt</td>
		<td align=\"left\">$reason &nbsp;</td>
		<td><input type=\"radio\" name=\"fid\" value=\"$fid\"></td>
		</tr>
		");
    }
    echo ("</table></td>
	    </tr>
	    </table>
	    <div align=\"center\"><br>
	    <input name=\"add\" type=\"submit\" class=\"buttons\" id=\"add\" value=\" Add \">
	    &nbsp;
	    <input name=\"edit\" type=\"submit\" class=\"buttons\" id=\"edit\" value=\" Edit \">
	    &nbsp;
	    <input name=\"delete\" type=\"submit\" class=\"buttons\" id=\"delete\" value=\" Delete \">
	    </div>
	    ");
}

echo ("<p align=\"center\"><a href=\"filter-ip.php\">Back To IP Filter</a><br><br>
	<a href=\"javascript:window.close()\">Close Window</a></p>
	</form>
	</body>
	</html>
	");

?>
