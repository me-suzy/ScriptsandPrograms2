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



	$result=mysql_query("SELECT Title, Text, Summary, date, Author FROM news WHERE NewsId='$newsid'");
	$row=mysql_fetch_row($result);
	$name=$row[0];
	$content=$row[1];
	$summary=$row[2];
	$date=$row[3];
	$author=$row[4];

if ($content=="") {$content.="<h2>$name</h2> <h4>"; 
		if ($author!="") $content.="$author, ";
		$content.="$date</h4> <br />$summary";}


if (!isset($f)) $f="news";
if (!isset($sf)) $sf="editnews";

commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);



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






echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>News Management - edit the news page content.</h2></center><br /><br />
Edition of: <b>$name</b>. The WYSIWYG - edition works well with IE/Mozilla FF. 
<br />
	<center>
	<script language=\"JavaScript\" type=\"text/javascript\" src=\"html2xhtml.js\"></script>
	<!-- To decrease bandwidth, use richtext_compressed.js instead of richtext.js //-->
	<script language=\"JavaScript\" type=\"text/javascript\" src=\"richtextN.js\"></script>



<!-- START Demo Code -->
<form name=\"RTEDemo\" action=\"editNewsResponse.php\" method=\"post\" onsubmit=\"return submitForm();\">
<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
<input type=\"hidden\" name=\"chnlid\" value=\"$chnlid\" />

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

<p></p>
<p><input type=\"submit\" value=\"Save the changes\" /></p>
</form>";


echo "
<!-- END Demo Code -->

	

	</center>

	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();



}
?>
