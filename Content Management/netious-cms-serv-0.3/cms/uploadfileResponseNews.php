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


if ($filetype=="image") {$direct="images";} elseif ($filetype=="file") {$direct="files";}
$thefile_tmp = $_FILES['thefile']['tmp_name'];
$thefile = $_FILES['thefile']['name'];

$parts=explode(".",$thefile);
$num_part=count($parts);
$ext=$parts[$num_part - 1];

if ($ext=="php" || $ext=="html" || $ext=="cgi" || $ext=="aps" || $ext=="js") {Header("Location: newsFiles.php?action=1&newsid=$newsid&chnlid=$chnlid");}

else {

$newfile="../news/"."$newsid"."/"."$direct"."/"."$thefile";
move_uploaded_file($thefile_tmp,$newfile);


Header("Location: newsFiles.php?newsid=$newsid&chnlid=$chnlid&from=$from"); 
}


}
?>
