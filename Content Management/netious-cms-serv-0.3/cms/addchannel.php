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

if (!isset($f)) $f="rss";
if (!isset($sf)) $sf="addchnl";

if (!isset($action)) $action="0";

if (!isset($name)) $name="";
if (!isset($chnltitle)) $chnltitle="";
if (!isset($link)) $link="http://";
if (!isset($chnldescription)) $chnldescription="";
if (!isset($language)) $language="en";
if (!isset($copyright)) $copyright="";
if (!isset($managingEditor)) $managingEditor="";
if (!isset($webMaster)) $webMaster="";
if (!isset($ttl)) $ttl="60";
if (!isset($img_width)) $img_width="";
if (!isset($img_height)) $img_height="";
if (!isset($img_description)) $img_description="";


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
	<center><h2>RSS editor - add an RSS channel.</h2></center><br /><br />
	<b>Required fields are marked with *</b><br />
<br />";

if ($action=="1") echo "<b>Write the name of the file</b>";

if ($action=="2") echo "<b>Write the title</b>";

if ($action=="3") echo "<b>The description is also required</b>";

if ($action=="4") echo "<b>The link to your site is missing</b>";

if ($action=="5") echo "<b>Channel with this name already exists. Try different name. To edit the existing one select \"Edit\" from the menu</b>";


echo "
	<center>
	<div id=\"formular\">
	<form enctype=\"multipart/form-data\" action='addchannelResponse.php' method='post'>
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td align=\"left\">
	Name of the XML directory*:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"name\" value=\"$name\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Title*:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"chnltitle\" value=\"$chnltitle\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Link to your site*:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"link\" value=\"$link\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Description*:
	</td>
	<td colspan=\"2\" align=\"left\">
	<textarea name=\"chnldescription\" cols=\"40\" rows=\"5\">$chnldescription</textarea>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Language:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"language\" value=\"$language\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Copyright notice:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"copyright\" value=\"$copyright\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Managing Editor (e-mail address):
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"managingEditor\" value=\"$managingEditor\" />
	</td> 
	</tr>
	<tr>
	<td align=\"left\">
	Web Master:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"webMaster\" value=\"$webMaster\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	ttl (time to live - a number of minutes that indicates how long a channel can be cached before refreshing from the source):
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"ttl\" value=\"$ttl\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\" colspan=\"3\">
	Image:
	</td>
	</tr>
	<tr>
	<td>
	</td>
	<td align=\"left\">
	Upload the image:
	</td>
	<td align=\"left\">
	<input type=\"file\" name=\"img_file\" />
	</td>
	</tr>
	<tr>
	<td>
	</td>
	<td align=\"left\">
	Width (max 144):
	</td>
	<td align=\"left\">
	<input type=\"text\" name=\"img_width\" value=\"$img_width\" />
	</td>
	</tr>
	<tr>
	<td>
	</td>
	<td align=\"left\">
	Height (max 400):
	</td>
	<td align=\"left\">
	<input type=\"text\" name=\"img_height\" value=\"$img_height\" />
	</td>
	</tr>
	<tr>
	<td>
	</td>
	<td align=\"left\">
	Description:
	</td>
	<td align=\"left\">
	<input type=\"text\" name=\"img_description\" value=\"$img_description\" />
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Add the channel\" />
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
