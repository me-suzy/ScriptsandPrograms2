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

if ($_POST["edit"]!="" && $_POST["mid"]!=""){
    $mid=$_POST["mid"];
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_method WHERE mid='$mid'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="Request Method filter edit for $method";
}
elseif ($_POST["edit"]!="" && $_POST["mid"]==""){
    print_error("Please select a request method to edit first");
}
elseif ($_POST["update"]!="" && $_POST["method"]!="" && $_POST["mid"]!=""){
    $mid=$_POST["mid"];
    $method=$_POST["method"];
    $reason=$_POST["reason"];
    $allow=$_POST["allow"];
    @mysql_query("UPDATE cjoverkill_filter_method SET method='$method', reason='$reason', allow='$allow' WHERE mid='$mid'") OR
      print_error(mysql_error());
    $sql1=@mysql_query("SELECT method FROM cjoverkill_filter_method WHERE mid='$mid'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql1);
    extract($tmp);
    $tms="$method updated for filtering";    
}
elseif ($_POST["add"]!=""){
    $tms="Add request method to filter";
}
elseif ($_POST["addnew"]!=""){
    if ($_POST["method"]==""){
	print_error("Please provide a request method for filtering");
    }
    $method=$_POST["method"];
    $reason=$_POST["reason"];
    $allow=$_POST["allow"];
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_method WHERE method='$method'") OR
      print_error(mysql_error());
    $tmp=@mysql_num_rows($sql);
    if ($tmp > 0){
	print_error("This request method is already in the filter");
    }
    @mysql_query("INSERT INTO cjoverkill_filter_method (method, reason, allow) VALUES ('$method', '$reason', '$allow')") OR
      print_error(mysql_error());
    $tms="$method request method was added to the Filter";
}
elseif ($_POST["idelete"]!="" && $_POST["mid"]!=""){
    $mid=$_POST["mid"];
    @mysql_query("DELETE FROM cjoverkill_filter_method WHERE mid='$mid'") OR
      print_error(mysql_error());
    $tms="Request Method Filter Updated";
}
else {
    $sql=@mysql_query("SELECT * FROM cjoverkill_filter_method ORDER BY allow DESC, method ASC") OR
      print_error(mysql_error());
    $tms="Request Method Filter Setup";
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
if ($_POST["edit"]!="" && $_POST["mid"]!="" || $_POST["update"]!=""){
    $mid=$_POST["mid"];
    echo ("<form action=\"filter-method.php\" method=\"POST\">
	    <div align=\"center\"><font size=\"4\"><b>$tms</b></font><br>
	    <br>
	    </div>
	    <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr>
	    <td colspan=\"2\" class=\"toprows\">Edit $method Requests</td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Request Method:  </b></td>
	    <td align=\"left\"><input name=\"method\" type=\"text\" id=\"method\" size=\"30\" maxlength=\"250\" value=\"$method\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Allow Method:  </b></td>
	    <td><select name=\"allow\">
	    <option value=\"1\" ");
    if ($allow=="1"){
	echo ("selected");
    }
    echo (">YES</option>
	    <option value=\"0\" ");
    if ($allow=="0"){
	echo ("selected");
    }
    echo (">NO</option>
	    </td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Reason:  </b></td>
	    <td align=\"left\"><textarea name=\"reason\" cols=\"30\" rows=\"5\" id=\"reason\">$reason</textarea></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td align=\"left\"><input name=\"mid\" type=\"hidden\" id=\"mid\" value=\"$mid\"></td>
	    <td align=\"left\"><input name=\"update\" type=\"submit\" class=\"buttons\" id=\"update\" value=\"Update\"></td>
	    </tr>
	    </table></td>
	    </tr>
	    </table>
	    </form>
	    ");
}
elseif ($_POST["delete"]!="" && $_POST["mid"]!=""){
    $mid=$_POST["mid"];
    $sql5=@mysql_query("SELECT * FROM cjoverkill_filter_method WHERE mid='$mid'") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql5);
    extract($tmp);
    echo("<form action=\"filter-method.php\" method=\"POST\">
	   <input type=\"hidden\" name=\"mid\" value=\"$mid\">
	   <div align=\"center\"><b><font size=\"4\">Delete $method Method From Filter?<br>
	   <input name=\"idelete\" type=\"submit\" value=\"Yes, Delete It\" class=\"buttons\">&nbsp;&nbsp;
	 <input name=\"goback\" type=\"submit\" value=\"No, Let It Be\" class=\"buttons\">
	   </font></b> </div>
	   </form>
	   ");
}
elseif ($_POST["idelete"]!="" && $_POST["mid"]!=""){
    echo ("<div align=\"center\"><b><font size=\"4\">Filter was updated<br></font></b>
	    <br><br><br><br><br><br><br><br>
	    </div>
	    ");
}
elseif ($_POST["add"]!="" || $_POST["addnew"]!=""){ 
    echo ("<form action=\"filter-method.php\" method=\"POST\">
	    <div align=\"center\"><font size=\"4\"><b>$tms</b></font><br>
	    <br>
	    </div>
	    <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td colspan=\"2\" class=\"toprows\">Add Request Method To Filter</td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Request Method: </b></td>
	    <td align=\"left\"><input name=\"method\" type=\"text\" size=\"30\" maxlength=\"250\"></td>
	    </tr>
	    <tr class=\"normalrow\">
	    <td width=\"100\" align=\"left\"><b>Allow: </b></td>
	    <td><select name=\"allow\">
	    <option value=\"1\">YES</option>
	    <option value=\"0\">NO</option>
	    </td>
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
    echo ("<form action=\"filter-method.php\" method=\"POST\">
	    <div align=\"center\"><b><font size=\"4\">$tms</font></b><br>
	    <br>
	    </div>
	    <table width=\"550\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr>
	    <td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	    <tr class=\"toprows\">
	    <td width=\"33%\">Request Method</td>
	    <td width=\"33%\">Allow</td>
	    <td width=\"33%\">Reason</td>
	    <td>&nbsp;</td>
	    </tr>
	    ");
    while ($tmp=@mysql_fetch_array($sql)){
	extract($tmp);
	if ($allow=="1"){
	    $filt="YES";
	}
	else {
	    $filt="NO";
	}
	echo ("<tr class=\"normalrow\">
		<td align=\"left\">$method</td>
		<td align=\"left\">$filt</td>
		<td align=\"left\">$reason &nbsp;</td>
		<td><input type=\"radio\" name=\"mid\" value=\"$mid\"></td>
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
echo ("<p align=\"center\"><a href=\"filter-method.php\">Back To Request Method Filter</a><br><br>
	<a href=\"javascript:window.close()\">Close Window</a></p>
	</form>
	</body>
	</html>
	");
?>
