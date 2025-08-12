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
if (!isset($sf)) $sf="add";

if (!isset($action)) $action="0";
if (!isset($alias)) $alias="";
if (!isset($name)) $name="";
if (!isset($refid)) $refid="0";
if (!isset($thisdescription)) $thisdescription="";
if (!isset($thiskeywords)) $thiskeywords="";
if (!isset($pagetype)) $pagetype="";


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
	<center><h2>Control panel - add an item to the navigation menu.</h2></center><br /><br />
	<b>Specify at least the name of the new section (it will appear in the menu). If it is a sub-section of an existing one, select the super-ordered section. Alias is an alternative name of the page (optional). If you write any, the page will be accessible using \"http://[yourdomain]/alias\".</b><br />
<br /><br />
<br />";

if ($action=="1") echo "<b style=\"color:red\">The selected alias already exists. Please select a different one.</b>";

echo "
	<center>
	<div id=\"formular\">
	<form action='addtomenuResponse.php' method='post'>
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td align=\"left\">
	Name of the section:
	</td>
	<td align=\"left\">
	<input type=\"text\" name=\"name\" value=\"$name\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Alias (optional. Do not use the name of the directory with your CMS!):
	</td>
	<td align=\"left\">
	<input type=\"text\" name=\"alias\" value=\"$alias\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Description (optional; if left blank, the common description will appear in the meta-tag):
	</td>
	<td align=\"left\">
	<input type=\"text\" size=\"40\" name=\"thisdescription\" value=\"$thisdescription\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Keywords (optional; if left blank, the common keywords will appear in the meta-tag):
	</td>
	<td align=\"left\">
	<input type=\"text\" size=\"40\" name=\"thiskeywords\" value=\"$thiskeywords\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	It is a sub-section of:
	</td>
	<td align=\"left\">
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
	<td align=\"left\">
	The type of the page
	</td>
	<td align=\"left\">
	<select name=\"pagetype\">";
	if ($pagetype=="") echo "<option selected=\"selected\" value=\"\">Normal presentation</option>"; else echo "<option value=\"\">Normal presentation</option>";
	if ($pagetype=="news") echo "<option selected=\"selected\" value=\"news\">News (headers)</option>"; else echo "<option value=\"news\">News (headers)</option>";
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td colspan=\"2\" align=\"center\">
	<input type=\"submit\" value=\"Add the section\" />
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
