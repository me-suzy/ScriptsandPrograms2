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


if (!isset($f)) $f="news";
if (!isset($sf)) $sf="editnews";

if (!isset($action)) $action="0";

if (!isset($ntitle))
{$result=mysql_query("SELECT RefId, Author, Title, Summary, Text FROM news WHERE NewsId='$newsid'");
$row=mysql_fetch_row($result);
$refid=$row[0];
$author=$row[1];
$ntitle=$row[2];
$nsummary=$row[3];
$content=$row[4];

}


function rteSafe($strText,$SUID) {
	//returns safe code for preloading in the RTE
	$tmpString = $strText;
	
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
//	$tmpString = str_replace("\"", "\"", $tmpString);
	
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);

	//remove all calls to the PHPSESSID

	$tmpString = str_replace("?PHPSESSID=$SUID", "", $tmpString);
	$tmpString = str_replace("&amp;PHPSESSID=$SUID", "", $tmpString);
	$tmpString = str_replace("$SUID", "", $tmpString);

	//relative path

	$tmpString = str_replace("\"./news/", "\"../news/", $tmpString);
	
	return $tmpString;
}





commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);

if ($result=mysql_query("SELECT * FROM pages WHERE type='news'")) {$num_news=mysql_num_rows($result);} else {$num_news="0";}


if ($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC")) {$num_channels=mysql_num_rows($result);} else {$num_channels="0";}



echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>News Management - edit a news item.</h2></center>";

if ($action=="1") echo "<b>Write at least the title!</b>";

echo "
<br />
<script language=\"JavaScript\" type=\"text/javascript\" src=\"html2xhtml.js\"></script>
	<!-- To decrease bandwidth, use richtext_compressed.js instead of richtext.js //-->
	<script language=\"JavaScript\" type=\"text/javascript\" src=\"richtextN.js\"></script>
	<center>
	<div id=\"formular\">
	<form action='newsFiles.php' methos=post>
	<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
	<input type=\"hidden\" name=\"from\" value=\"edit\" />
	<input type=\"submit\" value=\"Manage the files first\" />
	</form>
	<form name=\"RTEDemo\" action=\"editNewsResponse.php\" method=\"post\" onsubmit=\"return submitForm();\">
	<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";

if ($num_news!=0)
{echo "
	<td align=\"left\">
	Title:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"ntitle\" value=\"$ntitle\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Summary:
	</td>
	<td colspan=\"2\" align=\"left\">
	<textarea name=\"nsummary\" rows=\"5\" cols=\"30\">$nsummary</textarea>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Author:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"author\" value=\"$author\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	The \"News\" - type section:
	</td>
	<td>
	<select name=\"refid\">";

	if ($result=mysql_query("SELECT PageId, Name FROM pages WHERE type='news'")) {while ($row=mysql_fetch_row($result))
		{$thisrefid=$row[0];
		$thisname=$row[1];
		if ($thisrefid==$refid) {echo "<option selected=\"selected\" value=\"$thisrefid\">$thisname</option>";} else {echo "<option value=\"$thisrefid\">$thisname</option>";}

		}
	}


	echo "</select></td></tr>

";

echo "	<tr>
	<td colspan=\"3\" align=\"center\">
	
<script language=\"JavaScript\" type=\"text/javascript\">
<!--
function submitForm() {
	//make sure hidden and iframe values are in sync before submitting form
	//to sync only 1 rte, use updateRTE(rte)
	//to sync all rtes, use updateRTEs
	updateRTE('rte1');
	//updateRTEs();
	
	//change the following line to true to submit form
	return true;
}

//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML)
initRTE(\"editimages/\", \"\", \"styleedit.css\", true, $newsid);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>

<script language=\"JavaScript\" type=\"text/javascript\">
<!--
";

//format content for preloading
if (!(isset($_POST["rte1"]))) {	
	$content=rteSafe($content,$SUID);
} 


echo "
//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
writeRichText('rte1', '$content', 640, 300, true, false);
//-->
</script>
	
	
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Save\" />
	</td>";} else {echo "<td>Warning: there is no section with the \"News (headers)\" type!</td>";}

echo "
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
