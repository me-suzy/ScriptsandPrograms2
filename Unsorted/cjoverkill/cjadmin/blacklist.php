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

if ($_POST["idelete"]!="" && $_POST["domain"]!="") {
    @mysql_query("DELETE FROM cjoverkill_blacklist WHERE domain='$_POST[domain]'") OR 
      print_error(mysql_error());
}
elseif ($_POST["edit"]!="" && $_POST["domain"]!="") {
    $sql=@mysql_query("SELECT * FROM cjoverkill_blacklist WHERE domain='$_POST[domain]'") OR 
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="Local blacklist edit $_POST[domain]";
}
elseif ($_POST["update"]!="" && $_POST["domain"]!="") {
    $email=$_POST["email"];
    $icq=$_POST["icq"];
    $reason=$_POST["reason"];
    @mysql_query("UPDATE cjoverkill_blacklist SET email='$email',icq='$icq',reason='$reason' WHERE domain='$_POST[domain]'") OR
      print_error(mysql_error());
    $tms="$_POST[domain] Updated on the local blacklist";
}
elseif ($_POST["add"]!="") { 
    $tms="Add new site to local blacklist"; 
}
elseif ($_POST["addnew"]!="") {
    if ($_POST["domain"]=="") { 
	print_error("Please insert a valid domain"); 
    }
    $domain=$_POST["domain"];
    $icq=$_POST["icq"];
    $email=$_POST["email"];
    $reason=$_POST["reason"];
    $sql=@mysql_query("SELECT domain FROM cjoverkill_blacklist WHERE domain='$domain'") OR
      print_error(mysql_error());
    $tmp=@mysql_num_rows($sql);
    if ($tmp > 0) { 
	print_error("This domain already in local blacklist"); 
    }
    @mysql_query("INSERT INTO cjoverkill_blacklist (domain,email,icq,reason) VALUES ('$domain','$email','$icq','$reason')") OR
      print_error(mysql_error());
    $tms="$domain added to local blacklist";
}
else {
    $sql=@mysql_query("SELECT * FROM cjoverkill_blacklist ORDER BY domain") OR 
      print_error(mysql_error());
    $tms="Local blacklist";
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
if ($_POST["delete"]!="" && $_POST["domain"]!="") {
    echo ("<form action=\"blacklist.php\" method=\"POST\">
	    <input type=\"hidden\" name=\"domain\" value=\"$_POST[domain]\">
	    <div align=\"center\"><b><font size=\"4\">Delete $_POST[domain] From Blacklist?<br>
	    <input name=\"idelete\" type=\"submit\" value=\"Yes, Delete It\" class=\"buttons\">&nbsp;&nbsp;
	  <input name=\"goback\" type=\"submit\" value=\"No, Let It Be\" class=\"buttons\">
	    </font></b> </div>
	    </form>
	    ");
}
elseif ($_POST["idelete"]!="") {
    echo ("<div align=\"center\"><b><font size=\"4\">$_POST[domain] Deleted From Blacklist<br></font></b>
	    <br><br><br><br><br><br><br><br>
	    </div>
	    ");
}
elseif (($_POST["edit"]!="" && $_POST["domain"]!="") || $_POST["update"]!="") { 
    echo ("<form action=\"blacklist.php\" method=\"POST\">
	    <div align=\"center\"><font size=\"4\"><b>$tms</b></font><br>
	    <br>
	    </div>
	    <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr>
	    <td colspan=\"2\" class=\"toprows\">Edit Blacklist</td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Email:	</b></td>
	    <td align=\"left\"><input name=\"email\" type=\"text\" id=\"email\" size=\"30\" maxlength=\"150\" value=\"$email\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\"><b>ICQ:	</b></td>
	    <td align=\"left\"><input name=\"icq\" type=\"text\" id=\"icq\" size=\"30\" maxlength=\"20\" value=\"$icq\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\"><b>Reason:	</b></td>
	    <td align=\"left\"><textarea name=\"reason\" cols=\"30\" rows=\"5\" id=\"reason\">$reason</textarea></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\">&nbsp;<input type=\"hidden\" name=\"domain\" value=\"$_POST[domain]\"></td>
	    <td align=\"left\"><input name=\"update\" type=\"submit\" class=\"buttons\" id=\"update\" value=\"Update\"></td>
	    </tr>
	    </table></td>
	    </tr>
	    </table>
	    </form>
	    ");
}
elseif ($_POST["add"]!="" || $_POST["addnew"]!="") { 
    echo ("<form action=\"blacklist.php\" method=\"POST\">
	    <div align=\"center\"><font size=\"4\"><b>$tms</b></font><br>
	    <br>
	    </div>
	    <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr>
	    <td colspan=\"2\" class=\"toprows\">Add New Site To Blacklist</td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Domain:	</b></td>
	    <td align=\"left\"><input name=\"domain\" type=\"text\" size=\"30\" maxlength=\"150\"></td> 
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Email:	</b></td>
	    <td align=\"left\"><input name=\"email\" type=\"text\" id=\"email\" size=\"30\" maxlength=\"150\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\"><b>ICQ:	</b></td>
	    <td align=\"left\"><input name=\"icq\" type=\"text\" id=\"icq\" size=\"30\" maxlength=\"20\"></td>
	    </tr>
	    <tr class=\"normalrow\"> 
	    <td align=\"left\"><b>Reason:	</b></td>
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
    echo ("<form action=\"blacklist.php\" method=\"POST\">
	    <div align=\"center\"><b><font size=\"4\">Blacklist</font></b><br> 
	    <br>
	    </div>
	    <table width=\"550\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr class=\"toprows\">
	    <td width=\"25%\">Domain</td>
	    <td width=\"25%\">Email</td>
	    <td width=\"15%\">ICQ</td>
	    <td width=\"35%\">Reason</td>
	    <td>&nbsp;</td>
	    </tr>
	    ");
    if (mysql_num_rows($sql)==0) {
	echo ("<tr class=\"normalrow\">
		<td colspan=\"5\"><font size=\"3\"><b>LOCAL BLACKLIST IS EMPTY</b></font></td>
		</tr>
		");
    }
    while ($tmp=@mysql_fetch_array($sql)) {
	extract($tmp);
	if (strlen($reason)>250) { 
	    $reason=substr($reason,0,250)."...";
	}
	echo ("<tr class=\"normalrow\">\n
		<td align=\"left\">$domain</td>\n
		<td align=\"left\">$email</td>\n
		<td align=\"left\">$icq</td>\n
		<td align=\"left\">$reason</td>\n
		<td><input type=\"radio\" name=\"domain\" value=\"$domain\"></td>\n
		</tr>\n
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
	  <input name=\"delete\" type=\"submit\" class=\"buttons\" id=\"delete\" value=\"Delete\">
	    </div>
	    ");
}
echo ("<p align=\"center\"><a href=\"blacklist.php\">Back To Blacklist</a><br><br>
	<a href=\"javascript:window.close()\">Close Window</a></p>
	</form>
	</body> 
	</html>
	");
	 
?>
