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

$path_array=explode("/",$thefile);
$path="$path_array[0]/$path_array[1]/$path_array[2]/$path_array[3]";

$newfile="$path/$newname";

rename($thefile,$newfile);

Header("Location: newsFiles.php?newsid=$newsid&chnlid=$chnlid&from=$from");


}
?>
