<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");

}else{


if (!isset($f)) $f="structure";
if (!isset($sf)) $sf="edit";

if (!isset($action)) $action="0";

if (!isset($name)) 
{
$result=mysql_query("SELECT RefId, Name, Alias, Active, Description, Keywords FROM pages WHERE PageId='$pageid'");
$row=mysql_fetch_row($result);
$refid=$row[0];
$name=$row[1];
$alias=$row[2];
$active=$row[3];
$thisdescription=$row[4];
$thiskeywords=$row[5];

$alias_old=$alias;

if ($result=mysql_query("SELECT Type FROM pages WHERE PageId='$pageid'"))
	{$row=mysql_fetch_row($result);
	$pagetype=$row[0];}
else $pagetype="";

}


commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>Control panel - edit an item from the navigation menu.</h2></center>
<br /><br />
<br />";

if ($action=="1") echo "<b style=\"color:red\">The selected alias already exists. Please select a different one.</b>";

echo "
	<center>
	<div id=\"formular\">
	<form action='editmenuResponse.php' method='post'>
	<input type=\"hidden\" name=\"pageid\" value=\"$pageid\" />
	<input type=\"hidden\" name=\"alias_old\" value=\"$alias_old\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td>
	Name of the section:
	</td>
	<td>
	<input type=\"text\" name=\"name\" value=\"$name\" />
	</td>
	</tr>
	<tr>
	<td>
	Alias:
	</td>
	<td>
	<input type=\"text\" name=\"alias\" value=\"$alias\" />
	</td>
	</tr>
	<tr>
	<td>
	Description:
	</td>
	<td>
	<input type=\"text\" name=\"thisdescription\" value=\"$thisdescription\" />
	</td>
	</tr>
	<tr>
	<td>
	Keywords:
	</td>
	<td>
	<input type=\"text\" name=\"thiskeywords\" value=\"$thiskeywords\" />
	</td>
	</tr>
	<tr>
	<td>
	It is a sub-section of:
	</td>
	<td>
	<select name=\"refid\">
	<option value=\"0\">None. It is a top-level one </option>
";
$result=mysql_query("SELECT PageId, Name FROM pages WHERE RefId='0' order by PageId");
while ($row=mysql_fetch_row($result))
	{$thisrefid=$row[0];
	$thisname=$row[1];
	if ($thisrefid==$refid) $selected="selected=\"selected\""; else $selected="";
	echo "<option $selected value=\"$thisrefid\">$thisname</option>";
	}
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td>
	The item is now ";
	
	$select0="";
	$select1="";
	if ($active=="0") {$vis="invisible"; $select0="selected=\"selected\"";}
	if ($active=="1") {$vis="visible"; $select1="selected=\"selected\"";}

	echo "$vis. It should be
	</td>
	<td>
	<select name=\"active\">
	<option $select0 value=\"0\">invisible</option>
	<option $select1 value=\"1\">visible</option>
	</select>
	</td>
	</tr>
	<tr>
	<td>
	The type of the page
	</td>
	<td>
	<select name=\"pagetype\">";
	if ($pagetype=="") echo "<option selected=\"selected\" value=\"\">Normal presentation</option>"; else echo "<option value=\"\">Normal presentation</option>";
	if ($pagetype=="news") echo "<option selected=\"selected\" value=\"news\">News (headers)</option>"; else echo "<option value=\"news\">News (headers)</option>";
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td colspan=\"2\" align=\"center\">
	<input type=\"submit\" value=\"Save the changes\" />
	</td>
	</tr>	
	</table>
	</form>
	</div>
	</center>

	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();



}
?>
