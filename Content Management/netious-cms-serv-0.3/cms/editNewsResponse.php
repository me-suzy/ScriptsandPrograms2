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

	$tmpString = str_replace("\"../news/", "\"./news/", $tmpString);

	return $tmpString;
}


$content = rteSafe($_POST["rte1"],$SUID);


mysql_query("UPDATE news SET Text='$content', active='1' WHERE NewsId='$newsid'") or die(mysql_error());

if (isset($ntitle))
{mysql_query("UPDATE news SET Title='$ntitle' WHERE NewsId='$newsid'");
}

if (isset($nsummary))
{mysql_query("UPDATE news SET Summary='$nsummary' WHERE NewsId='$newsid'");
}

if (isset($author))
{mysql_query("UPDATE news SET Author='$author' WHERE NewsId='$newsid'");
}

if (isset($refid))
{mysql_query("UPDATE news SET RefId='$refid' WHERE NewsId='$newsid'");
}




$result=mysql_query("SELECT RefId FROM news WHERE NewsId='$newsid'");
$row=mysql_fetch_row($result);
$refid=$row[0];

mysql_query("UPDATE pages SET Active='1' WHERE PageId='$refid'");


if (isset($chnlid) && $chnlid!="")
{Header("Location: editNews2RSS.php?chnlid=$chnlid&newsid=$newsid");


} else {Header("Location: admin.php?f=news");}

}
?>
