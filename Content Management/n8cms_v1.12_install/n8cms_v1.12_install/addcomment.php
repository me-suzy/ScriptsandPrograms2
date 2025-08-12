<?php
$ip=$_SERVER["REMOTE_ADDR"];
require ('_.php');
require ('functions.php');
$dir=$_GET['dir'];
$page_id=$_GET['page_id'];
	if (!$page_id){$page_id=1;}
$datetime=date("Y-M-d G:i a");
$addcomment=$_POST['addcomment'];
if (!$addcomment){
	if (!$dir){
/*for building*/echo"error! Directory not set.";
echo"eRRor, How'd you get here! <script>setTimeout (document.location.replace('index.php'), 9000000);</script> ";
			}
		
include('html/comment_form.html');
}else
{
//fields needed:
$sender_name=strip_tags( addslashes($_POST['sender_name']) );
$comment_text=strip_tags( addslashes($_POST['comment_text']) );
$dir=$_POST['dir'];
$page_id=$_POST['page_id'];
$comment_ip=$_POST['comment_ip'];
$datetime=addslashes($_POST['datetime']);

if ( (!$sender_name) || (!$comment_text) ){
	if (!$sender_name){echo"<a class=content_bottom> insert a name, just something!</a><br>";}
	if (!$comment_text){echo"<a class=content_bottom>Well? speak up!</a><br>";}
include('html/comment_form.html');
exit();
	}
$db_fld="sender_name, comment_text, dir, page_id, comment_ip, datetime";
$db_val=" '$sender_name', '".nl2br($comment_text)."', '$dir', '$page_id', '$comment_ip','$datetime'"; 
include ('_.php');
$ins_query = "INSERT INTO comment (".$db_fld.") VALUES (".($db_val).")";
mysql_query($ins_query)  or die (mysql_error());
echo"Comment Added!<script>window.opener.location.replace('index.php?dir=".$dir."&page_id=".$page_id."&com=1#comment')</script>";
echo"<script>setTimeout (window.close('popUp'),90000);</script>";
mysql_close();

}
/*for upgrading future versions
if (!DB_TABLE){
$query="CREATE TABLE `comment` (
`com_id` INT( 6 ) NOT NULL AUTO_INCREMENT ,
`dir` VARCHAR( 20 ) NOT NULL ,
`page_id` INT( 6 ) NOT NULL ,
`sender_name` VARCHAR( 16 ) NOT NULL ,
`comment_text` LONGTEXT NOT NULL ,
`datetime` VARCHAR( 20 ) NOT NULL ,
INDEX ( `com_id` ) 
);
";
mysql_query($query) or die(mysql_error());
*/
?>
