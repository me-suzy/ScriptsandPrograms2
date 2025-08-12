<?
session_start();  // Start Session
require ('_.php');
require ('functions.php');
//moves comment from "comment" table to banned_ip table
//archives comment and adds users IP to the banned list.
$com_id=$_GET['com_id'];
$com_query="SELECT * FROM comment WHERE com_id=".$com_id."";
$com= mysql_query($com_query) or exit(mysql_error());
$del=$_GET[del];
if (!$del){

	echo "Command:Ban IP# ".$com['comment_ip']." and Delete comment?";	
	echo "<br><a href='?del=1&com_id=".$com_id."&dir=".$dir."&page_id=".$page_id."' class=int_link><b>heck ya!</b></a> <br>";
	echo"<br>On second thought, <a href='javascript:window.close();' class=int_link>No</a><br><b>THERE IS NO UNDO!</b><br>" ;
}else{
	$del_query= "DELETE FROM comment WHERE dir='".$dir."' AND page_id='".$page_id."' AND com_id='".$com_id."' LIMIT 1";
	echo$del_query;
	mysql_query($del_query) or die (mysql_error());
	echo"<a href=\"admin_funtions.php?dir=comment\">Done</a>";
echo"<script>window.opener.location.replace('admin_funtions.php?dir=comment')</script>";

echo "DONE <script>setTimeout (window.close(),900000)</SCRIPT>";
}
?>