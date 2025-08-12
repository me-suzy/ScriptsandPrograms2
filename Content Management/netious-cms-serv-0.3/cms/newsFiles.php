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


$result=mysql_query("SELECT Title FROM news WHERE NewsId='$newsid'");
$row=mysql_fetch_row($result);
$name=$row[0];

if (!isset($action)) $action="0";
if (!isset($f)) $f="news";
if (!isset($sf)) $sf="addnews";

$rootdir="../news/$newsid";
$imagedir="../news/$newsid/images";
$filedir="../news/$newsid/files";



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
	<center><h2>Control panel - file management</h2></center><br /><br />
	<b>In the table below you you can see the list of currently stored files.</b><br />
<br /><br />
<br />

<!-- Dynamically switch the action of the form between delete/rename -->

	 <script language=\"JavaScript\" type=\"text/javascript\">
	function OnSubmitForm()
	{
	  if(document.pressed == 'Delete')
	  {
	   document.fileform.action =\"delfileNews.php\";
	  }
	  else
	  if(document.pressed == 'Rename')
	  {
	    document.fileform.action =\"renamefileNews.php\";
	  }
	  return true;
	}
	</SCRIPT>

	<center>
	<div id=\"formular\">

	<table width=\"90%\" border=\"1\" style=\"border-collapse:collapse\" cellpadding=\"5\" cellspacing=\"5\">
	<form name=\"fileform\" onsubmit=\"return OnSubmitForm();\">
	<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
	<input type=\"hidden\" name=\"chnlid\" value=\"$chnlid\" />
	<input type=\"hidden\" name=\"from\" value=\"$from\" />
	<tr>
	<td align=\"center\" width=\"50%\">
	<b>Images in \"$name\"</b>	
	</td>
	<td align=\"center\" width=\"50%\">
	<b>Other files in \"$name\"</b>
	</tr>
	<tr>
	<td align=\"left\">";
	$dh=opendir($imagedir);
	while ($file=readdir($dh))
	{if ($file!="." && $file!=".." && $file!="index.php") echo "<input type=\"radio\" name=\"thefile\" value=\"$imagedir/$file\" /> $file<br />";
	}
	echo "
	</td>
	<td align=\"left\">";
	$dh=opendir($filedir);
	while ($file=readdir($dh))
	{if ($file!="." && $file!=".." && $file!="index.php") echo "<input type=\"radio\" name=\"thefile\" value=\"$filedir/$file\" /> $file <br />";
	}
	echo "
	</td>
	</tr>
	<tr>
	<td colspan=\"2\" align=\"center\">
	<input type=\"submit\" value=\"Delete\" name=\"operation\" onclick=\"document.pressed=this.value\" /> <br/>
	<input type=\"submit\" value=\"Rename\" name=\"operation\" onclick=\"document.pressed=this.value\" />
	
	</td>
	</tr>
	</form>
	<tr>
	<td align=\"left\">
	<b>Upload image:</b><br />
	<form enctype=\"multipart/form-data\" action='uploadfileResponseNews.php' method='POST'>
	<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
	<input type=\"hidden\" name=\"filetype\" value=\"image\" />
	<input type=\"hidden\" name=\"chnlid\" value=\"$chnlid\" />
	<input type=\"hidden\" name=\"from\" value=\"$from\" />
	<input type=\"file\" name=\"thefile\" />
	<input type=\"submit\" value=\"Upload\" />
	</form>
	</td>
	<td align=\"left\">
	<b>Upload file:</b><br />
	<form enctype=\"multipart/form-data\" action='uploadfileResponseNews.php' method='POST'>
	<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
	<input type=\"hidden\" name=\"filetype\" value=\"file\" />
	<input type=\"hidden\" name=\"chnlid\" value=\"$chnlid\" />
	<input type=\"hidden\" name=\"from\" value=\"$from\" />
	<input type=\"file\" name=\"thefile\" />
	<input type=\"submit\" value=\"Upload\" />
	</form>
	</td>
	</tr>
	</table>";
	if ($action=="1") echo "<b>You cannot upload scripts using this forms!</b>";
	

	echo "
	<br />
	</div>";

	if (!isset($from) || $from=="") {echo "<form action='editNews.php' method='post'>";} elseif ($from=="edit") {echo "<form action='editnewsEdition.php' method='post'>";}
echo "

	<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
	<input type=\"hidden\" name=\"chnlid\" value=\"$chnlid\" />
	<input type=\"submit\" value=\"Go to the content edition\" />
	</form>
	<br />";

$result=mysql_query("SELECT active FROM news WHERE NewsId='$newsid'");
$row=mysql_fetch_row($result);
$active=$row[0];

if (isset($chnlid) && $chnlid!="" && $active!=0)
	{echo "
	<form action='news2RSS.php' method='post'>
	<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
	<input type=\"hidden\" name=\"chnlid\" value=\"$chnlid\" />
	<input type=\"submit\" value=\"Create the RSS channel\" />
	</form>
	<br />

	";}

echo "

	</center>

	<br /><br /><br />


	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();



}
?>
