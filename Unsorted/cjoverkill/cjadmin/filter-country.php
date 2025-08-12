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

if ($_POST["edit"]!="" && $_POST["c2code"]!=""){
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_country WHERE c2code='$_POST[c2code]'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="Country filter edit for $country";
}
elseif ($_POST["edit"]!="" && $_POST["c2code"]==""){
    print_error("Please slect a country to edit first");
}
elseif ($_POST["update"]!="" && $_POST["c2code"]!=""){
    $c2code=$_POST["c2code"];
    $url=$_POST["url"];
    $reason=$_POST["reason"];
    $filter=$_POST["filter"];
    if ($filter=="1" && $url==""){
	$sql5=@mysql_query("SELECT filter_url_default FROM cjoverkill_settings") OR
	  print_error(mysql_error());
	$tmp5=@mysql_fetch_array($sql5);
	extract($tmp5);
	$url=$filter_url_default;
    }
    @mysql_query("UPDATE cjoverkill_filter_country SET filter='$filter', url='$url', reason='$reason' WHERE c2code='$c2code'") OR
      print_error(mysql_error());
    $sql1=@mysql_query("SELECT country FROM cjoverkill_filter_country where c2code='$c2code'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql1);
    extract($tmp);
    $tms="$country updated for geofiltering";
}
else {
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_country ORDER BY country ASC") OR
      print_error(mysql_error());
    $tms="Country filter setup";
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
if ($_POST["edit"]!="" && $_POST["c2code"]!="" || $_POST["update"]!=""){
    echo ("<form action=\"filter-country.php\" method=\"POST\">
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
	    <td width=\"100\" align=\"left\"><b>Filter URL:  </b></td>
	    <td align=\"left\"><input name=\"url\" type=\"text\" id=\"url\" size=\"30\" maxlength=\"250\" value=\"$url\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Enable Filter:  </b></td>
	    <td><select name=\"filter\">
	    <option value=\"1\" ");
    if ($filter=="1"){
	echo ("selected");
    }
    echo (">Enabled</option>
	    <option value=\"0\" ");
    if ($filter=="0"){
	echo ("selected");
    }
    echo (">Disabled</option>
	    </td>\n
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Reason:  </b></td>
	    <td align=\"left\"><textarea name=\"reason\" cols=\"30\" rows=\"5\" id=\"reason\">$reason</textarea></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\">&nbsp;<input type=\"hidden\" name=\"c2code\" value=\"$_POST[c2code]\"></td>
	    <td align=\"left\"><input name=\"update\" type=\"submit\" class=\"buttons\" id=\"update\" value=\"Update\"></td>
	    </tr>
	    </table></td>
	    </tr>
	    </table>
	    </form>
	    ");
}
else {
    echo ("<form action=\"filter-country.php\" method=\"POST\">
	    <div align=\"center\"><b><font size=\"4\">$tms</font></b><br>
	    <br>
	    </div> 
	    <table width=\"550\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr class=\"toprows\">
	    <td width=\"25%\">Country</td>
	    <td width=\"25%\">Filter</td>
	    <td width=\"25%\">Filter URL</td>
	    <td width=\"25%\">Reason</td>
	    <td>&nbsp;</td>
	    </tr>
	    ");
    while ($tmp=@mysql_fetch_array($sql)){
	extract($tmp);
	if ($filter=="1"){
	    $filt="YES";
	}
	else {
	    $filt="NO";
	}
	echo ("<tr class=\"normalrow\">
		<td align=\"left\">$country</td>
		<td align=\"left\">$filt</td>
		<td align=\"left\"><a href=\"$url\" target=\"_blank\">$url</a>&nbsp;</td>
		<td align=\"left\">$reason &nbsp;</td>
		<td><input type=\"radio\" name=\"c2code\" value=\"$c2code\"></td>
		</tr>
		");
    }
    echo ("</table></td>
	    </tr>
	    </table>
	    <div align=\"center\"><br>
	    <input name=\"edit\" type=\"submit\" class=\"buttons\" id=\"edit\" value=\" Edit \">
	    </div>
	    ");
}

echo ("<p align=\"center\"><a href=\"filter-country.php\">Back To Country Filter</a><br><br>
	<a href=\"javascript:window.close()\">Close Window</a></p>
	</form>
	</body>
	</html>
	");

?>
