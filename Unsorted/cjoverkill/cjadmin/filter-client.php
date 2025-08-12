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

if ($_POST["edit"]!="" && $_POST["cid"]!=""){
    $cid=$_POST["cid"];
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_client WHERE cid='$cid'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="Client filter edit for $client";
}
elseif ($_POST["edit"]!="" && $_POST["cid"]==""){
    print_error("Please select a client to edit first");
}
elseif ($_POST["update"]!="" && $_POST["client"]!="" && $_POST["cid"]!=""){
    $cid=$_POST["cid"];
    $client=$_POST["client"];
    $reason=$_POST["reason"];
    @mysql_query("UPDATE cjoverkill_filter_client SET client='$client', reason='$reason' WHERE cid='$cid'") OR
      print_error(mysql_error());
    $sql1=@mysql_query("SELECT client FROM cjoverkill_filter_client WHERE cid='$cid'") OR
      print_Error(mysql_error());
    $tmp=@mysql_fetch_array($sql1);
    extract($tmp);
    $tms="$client updated for filtering";
}
elseif ($_POST["add"]!=""){
    $tms="Add client / bot to filter";
}
elseif ($_POST["addnew"]!=""){
    if ($_POST["client"]==""){
	print_error("Please provide a client / bot for filtering");
    }
    $client=$_POST["client"];
    $reason=$_POST["reason"];
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_client WHERE client='$client'") OR
      print_error(mysql_error());
    $tmp=@mysql_num_rows($sql);
    if ($tmp > 0){
	print_error("This client / bot is already in the filter");
    }
    @mysql_query("INSERT INTO cjoverkill_filter_client (client, reason) VALUES ('$client', '$reason')") OR
      print_error(mysql_error());
    $tms="$client client / bot was added to the Filter";
}
elseif ($_POST["idelete"]!="" && $_POST["cid"]!=""){
    $cid=$_POST["cid"];
    @mysql_query("DELETE FROM cjoverkill_filter_client WHERE cid='$cid'") OR
      print_error(mysql_error());
    $tms="Client Filter Updated";
}
else {
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_client ORDER BY client ASC") OR
      print_error(mysql_error());
    $tms="Client / Bot Filter Setup";
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
if ($_POST["edit"]!="" && $_POST["cid"]!="" || $_POST["update"]!=""){
    $cid=$_POST["cid"];
    echo ("<form action=\"filter-client.php\" method=\"POST\">
	    <div align=\"center\"><font size=\"4\"><b>$tms</b></font><br>
	    <br>
	    </div>
	    <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr>
	    <td colspan=\"2\" class=\"toprows\">Edit $client</td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Client / Bot:  </b></td>
	    <td align=\"left\"><input name=\"client\" type=\"text\" id=\"client\" size=\"30\" maxlength=\"250\" value=\"$client\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Reason:  </b></td>
	    <td align=\"left\"><textarea name=\"reason\" cols=\"30\" rows=\"5\" id=\"reason\">$reason</textarea></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\"><input name=\"cid\" type=\"hidden\" id=\"cid\" value=\"$cid\"></td>
	    <td align=\"left\"><input name=\"update\" type=\"submit\" class=\"buttons\" id=\"update\" value=\"Update\"></td>
	    </tr>
	    </table></td>
	    </tr>
	    </table>
	    </form>
	    ");
}
elseif ($_POST["delete"]!="" && $_POST["cid"]!=""){
    $cid=$_POST["cid"];
    $sql5=@mysql_query("SELECT * FROM cjoverkill_filter_client WHERE cid='$cid'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql5); 
    extract($tmp);
    echo("<form action=\"filter-client.php\" method=\"POST\">
	   <input type=\"hidden\" name=\"cid\" value=\"$cid\">
	   <div align=\"center\"><b><font size=\"4\">Delete $client From Filter?<br>
	   <input name=\"idelete\" type=\"submit\" value=\"Yes, Delete It\" class=\"buttons\">&nbsp;&nbsp;
	 <input name=\"goback\" type=\"submit\" value=\"No, Let It Be\" class=\"buttons\">
	   </font></b> </div> 
	   </form>
	   ");
}
elseif ($_POST["idelete"]!="" && $_POST["cid"]!=""){
    echo ("<div align=\"center\"><b><font size=\"4\">Filter was updated<br></font></b>
	    <br><br><br><br><br><br><br><br>
	    </div>
	    ");
}
elseif ($_POST["add"]!="" || $_POST["addnew"]!=""){
    echo ("<form action=\"filter-client.php\" method=\"POST\">
	    <div align=\"center\"><font size=\"4\"><b>$tms</b></font><br>
	    <br>
	    </div>
	    <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td colspan=\"2\" class=\"toprows\">Add Client / Bot To Filter</td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Client / Bot: </b></td>
	    <td align=\"left\"><input name=\"client\" type=\"text\" size=\"30\" maxlength=\"250\"></td>
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
    echo ("<form action=\"filter-client.php\" method=\"POST\">
	    <div align=\"center\"><b><font size=\"4\">$tms</font></b><br>
	    <br>
	    </div>
	    <table width=\"550\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr class=\"toprows\">
	    <td width=\"33%\">Client / Bot</td>
	    <td width=\"67%\">Reason</td>
	    <td>&nbsp;</td>
	    </tr>
	    ");
    while ($tmp=@mysql_fetch_array($sql)){
	extract($tmp);
	echo ("<tr class=\"normalrow\">
		<td align=\"left\">$client</td>
		<td align=\"left\">$reason &nbsp;</td>
		<td><input type=\"radio\" name=\"cid\" value=\"$cid\"></td>
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
echo ("<p align=\"center\"><a href=\"filter-client.php\">Back To Client / Bot Filter</a><br><br>
	<a href=\"javascript:window.close()\">Close Window</a></p>
	</form>
	</body>
	</html>
	");
?>
