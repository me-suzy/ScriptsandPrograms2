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

	$tmpString = str_replace("\"../sections/", "\"./sections/", $tmpString);

	return $tmpString;
}


$content = rteSafe($_POST["rte1"],$SUID);


mysql_query("UPDATE pages SET Content='$content', Active='1' WHERE PageId='$pageid'") 
or die(mysql_error());

Header("Location: admin.php");

}
?>
