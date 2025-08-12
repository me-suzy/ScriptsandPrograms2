<?php
session_start();
// This script was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this news script
include "config.php";
$user123=$_POST['Username'];
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, username, password FROM users WHERE username='$user123'"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$_Username=$r["username"]; 
$_Password=$r["password"]; 

// If the form was submitted
if ($_POST['Submitted'] == "True") {

    // If the username and password match up, then continue...
    if ($_POST['Username'] == $_Username && $_POST['Password'] == $_Password) {

        // Username and password matched, set them as logged in and set the
        // Username to a session variable.
        $_SESSION['Logged_In'] = "True";
        $_SESSION['Username'] = $_Username;
    }
}
} 
// If they are NOT logged in then show the form to login...
if ($_SESSION['Logged_In'] != "True") {

    echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=login.php?page=" . $_SERVER['PHP_SELF'] . "\">";

}
else
{
include "style.php";
include "header.php";
?>
<script LANGUAGE="JavaScript">
<!--
function confirmSubmit()
{
var agree=confirm("Are you sure you want to delete this comment?");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>
<div align="center"><div class="bluein-box"><b>Viewing News Article and Comments</b></div>
<?
if ($_GET['cid'] == "") {

$query = "SELECT id, cid, title FROM news"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$id = $r["id"];  
$cid = $r["cid"];  
$title = $r["title"];  
 
$query7="SELECT * FROM comments WHERE cid = '$cid'";
$result7 = mysql_query($query7);
$commentsnum = mysql_num_rows($result7);
?>
<div class="green-box">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="viewn.php?cid=<?=$cid;?>"><?=$title;?> - <?=$commentsnum;?> Comments</a></div>
<? }


} else { }
$query = "SELECT id, cid, category, title, shorta, longa, datetime FROM news WHERE cid = '$_GET[cid]' LIMIT 0,1"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$id = $r["id"];  
$cid = $r["cid"];  
$category = $r["category"];  
$title = $r["title"];  
$shorta = $r["shorta"];  
$longa = $r["longa"];  
$datetime = $r["datetime"];  
$query7="SELECT * FROM comments WHERE cid = '$cid'";
$result7 = mysql_query($query7);
$commentsnum = mysql_num_rows($result7);
?>
<table cellpadding="0" cellspacing="0" width="400" style="margin-top: 2px; border: 1px solid gray; padding: 1px;" align="center"><tr><td style="border-bottom: 1px solid #DDDDDD"><b><?=$category;?>: <?=$title;?></b></td></tr><tr><td><?
if ($longa == "") {
echo $shorta;
} else {
echo $longa;
}
?></td></tr><tr><td align="right" style="border-top: 1px dashed #DDDDDD;"><?=$commentsnum;?> Comments | <?=$datetime;?></td></tr></table>
<?
if ($_GET['a'] == "") {
?><div class="green-box"><table cellpadding="0" cellspacing="0" border="0" align="center"><tr><td>
<form method="get" action="<?=$_SERVER['PHP_SELF'];?>"><input type="hidden" name="cid" value="<?=$_GET['cid'];?>"><input type="hidden" name="a" value="<?=$_GET['a'];?>">Sort By: <select name="c" id="c"><option value="id DESC">Newest First</option><option value="id">Oldest First</option></select><input type="submit" value="Change"></form></td><td><form method="post" action="<?=$_SERVER['PHP_SELF'];?>?b=newc&cid=<?=$_GET['cid'];?>"><input type="submit" value="Reply"></form></td></tr></table>
</div>
<?
if ($_GET['c'] == "") {
$sorty = "id DESC";
} else { 
$sorty = $_GET['c'];
}
$query3 = "SELECT id, yname, emailadd, subject, comment, datetime FROM comments WHERE cid = '$_GET[cid]' ORDER BY $sorty"; 
$result3 = mysql_query($query3); 
while($r=mysql_fetch_array($result3)) 
{ 
$id = $r["id"];  
$yname = $r["yname"];  
$emailadd = $r["emailadd"];  
$subject = $r["subject"];  
$comment = $r["comment"];  
$datetime = $r["datetime"];  
?>
<table cellpadding="0" cellspacing="0" width="400" style="margin-top: 2px; border: 1px solid gray; padding: 1px;" align="center"><tr><td style="border-bottom: 1px solid #DDDDDD"><b><?=$subject;?></b> <a href="<?=$_SERVER['PHP_SELF'];?>?a=delete&id=<?=$id;?>&cid=<?=$cid;?>" onClick="return confirmSubmit()"><img src="images/b_drop.png" border="0"></a>&nbsp;<a href="<?=$_SERVER['PHP_SELF'];?>?a=edit&id=<?=$id;?>&cid=<?=$cid;?>"><img src="images/b_edit.png" border="0"></a></td></tr><tr><td><?=$comment;?></td></tr><tr><td align="right" style="border-top: 1px dashed #DDDDDD;"><a href="<?=$emailadd;?>" target="_Blank"><?=$yname;?></a> | <?=$datetime;?></td></tr></table>
<?
}
} else { }
}
if ($_GET['a'] == "delete") {
$query4 = "DELETE FROM comments WHERE id = '$_GET[id]' AND cid = '$_GET[cid]'"; 
mysql_query($query4); 
echo "<div align=\"center\">Deleted</div>";
echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=viewn.php?cid=" . $_GET[cid] . "\">";
} else { }
if ($_GET['a'] == "edit") {
$query2 = "SELECT id, yname, emailadd, subject, comment, datetime FROM comments WHERE id = '$_GET[id]' AND cid = '$_GET[cid]'"; 
$result2 = mysql_query($query2); 
while($r=mysql_fetch_array($result2)) 
{ 
$id = $r["id"];  
$yname = $r["yname"];  
$emailadd = $r["emailadd"];  
$subject = $r["subject"];  
$comment = $r["comment"];  
$datetime = $r["datetime"];  
?><div align="center">
<form method="post" name="news" action="<?=$_SERVER['PHP_SELF'];?>?a=edit&b=post&id=<?=$_GET['id'];?>&cid=<?=$_GET['cid'];?>">
<div class="grey-box"><table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">Your Name:</td><td><input type="text" size="30" name="yname" value="<?=$yname;?>"></td></tr></table></div>
<div class="grey-box"><input type="hidden" name="datetime" value="<?=$datetime;?>"><table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">
Email / Website:</td><td><input type="text" size="30" name="emailadd" value="<?=$emailadd;?>"></td></tr></table></div>
<div class="grey-box"><table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">
Comment Subject:</td><td><input type="text" size="30" name="subject" value="<?=$subject;?>"></td></tr></table></div><div class="grey-box">
Comment Text:<br>
<textarea rows="5" cols="30" name="comment"><?=$comment;?></textarea></div><div class="grey-box"><div align="center">
<input type="submit" value="Save Revision"></div></div></form>
<br>
<?
}
if ($_GET['b'] == "post") {
$query5 = "UPDATE comments SET yname='$_POST[yname]', emailadd='$_POST[emailadd]', subject='$_POST[subject]', comment='$_POST[comment]' WHERE id = '$_GET[id]'"; 
mysql_query($query5); 
echo "<div align=\"center\">Comment Updated</div>";
?><meta http-equiv="refresh" content="1;url=viewn.php?cid=<?=$_GET['cid'];?>">
<? 
} else { }
} else { }
if ($_GET['a'] == "newc") {
$datetime = date("l dS of F Y h:i:s A"); 
?>
<div align="center">
<form method="post" name="news" action="<?=$_SERVER['PHP_SELF'];?>?a=newc&b=post&cid=<?=$_GET['cid'];?>">
<div class="grey-box"><table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">Your Name:</td><td><input type="text" size="30" name="yname"></td></tr></table></div>
<div class="grey-box"><input type="hidden" name="datetime" value="<?=$datetime;?>"><input type="hidden" name="cid" value="<?=$_GET['cid'];?>">
<table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">Email / Website:</td><td><input type="text" size="30" name="emailadd" value="mailto:"></td></tr></table></div>
<div class="grey-box">
<table cellpadding="0" cellspacing="0" border="0"><tr><td width="130">Comment Subject:</td><td><input type="text" size="30" name="subject"></td></tr></table></div><div class="grey-box">
Comment Text:<br>
<textarea rows="5" cols="40" name="comment"></textarea></div><div class="grey-box"><div align="center">
<input type="submit" value="Post Comment"></div></div></form>
<br>
<?
if ($_GET['b'] == "post") {
$query8 = "INSERT INTO comments(cid, yname, emailadd, subject, comment, datetime) 
VALUES('$_GET[cid]','$_POST[yname]','$_POST[emailadd]','$_POST[subject]','$_POST[comment]','$_POST[datetime]')"; 
mysql_query($query8); 
echo "<div align=\"center\">Comment Saved</div>";
$cider = $_POST['cid'];
?>
<meta http-equiv="refresh" content="1;url=viewn.php?cid=<?=$cider;?>"> 
<?
} else { }
} else { }
} 
include "footer.php";
// If they want to logout then
if ($_GET['mode'] == "logout") {
    // Start the session
    session_start();

    // Put all the session variables into an array
    $_SESSION = array();

    // and finally remove all the session variables
    session_destroy();

    // Redirect to show results..
    echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=" . $_SERVER['PHP_SELF'] . "\">";
}
mysql_close($db); 

?>