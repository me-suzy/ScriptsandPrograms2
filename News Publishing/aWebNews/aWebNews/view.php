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
var agree=confirm("Are you sure you want to delete this story?");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>
<div align="center"><div class="bluein-box"><b>Viewing All News Articles,</b> <a href="visview.php">Visitor View</a></div>
<?
if ($_GET['a'] == "") {
$query = "SELECT id, cid, category, title, author, shorta, longa, datetime FROM news"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$id = $r["id"];  
$cid = $r["cid"];  
$category = $r["category"];  
$title = $r["title"];  
$author = $r["author"];  
$shorta = $r["shorta"];  
$longa = $r["longa"];  
$datetime = $r["datetime"];  
$query7="SELECT * FROM comments WHERE cid = '$cid'";
$result7 = mysql_query($query7);
$commentsnum = mysql_num_rows($result7);
?>
<table cellpadding="0" cellspacing="0" width="400" style="margin-top: 2px; border: 1px solid gray; padding: 1px;" align="center"><tr><td style="border-bottom: 1px solid #DDDDDD"><b><?=$category;?>: <?=$title;?></b> <i>by: <?=$author;?></i>&nbsp;&nbsp;<a href="<?=$_SERVER['PHP_SELF'];?>?a=delete&id=<?=$id;?>&cid=<?=$_GET['cid'];?>" onClick="return confirmSubmit()"><img src="images/b_drop.png" border="0"></a>&nbsp;<a href="<?=$_SERVER['PHP_SELF'];?>?a=edit&id=<?=$id;?>"><img src="images/b_edit.png" border="0"></a></td></tr><tr><td><u>Short Version:</u><br><?=$shorta;?></td></tr><tr><td><u>Full Version:</u><br><?=$longa;?></td></tr><tr><td align="right" style="border-top: 1px dashed #DDDDDD;"><a href="viewn.php?cid=<?=$cid;?>"><?=$commentsnum;?> Comments</a> | <?=$datetime;?></td></tr></table>
<? }
} else { }
if ($_GET['a'] == "delete") {
$query4 = "DELETE FROM news WHERE id = '$_GET[id]'"; 
mysql_query($query4); 
$query9 = "DELETE FROM comments WHERE cid = '$_GET[cid]'"; 
mysql_query($query9); 
echo "<div align=\"center\">Deleted</div>";
echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=view.php\">";
} else { }
if ($_GET['a'] == "edit") {
$query2 = "SELECT id, category, title, shorta, longa, datetime FROM news WHERE id = '$_GET[id]'"; 
$result2 = mysql_query($query2); 
while($r=mysql_fetch_array($result2)) 
{ 
$id = $r["id"];  
$category = $r["category"];  
$title = $r["title"];  
$shorta = $r["shorta"];  
$longa = $r["longa"];  
$datetime = $r["datetime"];  
?><div align="center">
<form method="post" name="news" action="<?=$_SERVER['PHP_SELF'];?>?a=edit&b=post&id=<?=$_GET['id'];?>">
<div class="grey-box">Story Title: <input type="text" name="title" size="40" value="<?=$title;?>"></div>
<div class="grey-box"><input type="hidden" name="datetime" value="<?=$datetime;?>">
Category: <select name="category" id="category">

<?php 
echo "<option value=\"$category\">$category</option>"; 
$query1 = "SELECT category FROM categories WHERE category != '$category'"; 
$result1 = mysql_query($query1); 
while($r=mysql_fetch_array($result1)) 
{ 
$cname=$r["category"]; 
echo "<option value=\"$cname\">$cname</option>"; 
} 
?>
 </select></div>
<div class="grey-box">
New Article (short version): <br><textarea rows="6" cols="45" name="shorta"><?=$shorta;?></textarea></div><div class="grey-box">
New Article (full version): <br><textarea rows="12" cols="45" name="longa"><?=$longa;?></textarea></div><div class="grey-box"><div align="center">
<input type="submit" value="Save Revision"></div></div></form>
<br>
<?
}
if ($_GET['b'] == "post") {
$query5 = "UPDATE news SET category='$_POST[category]', title='$_POST[title]', shorta='$_POST[shorta]', longa='$_POST[longa]', datetime='$_POST[datetime]' WHERE id = '$_GET[id]'"; 
mysql_query($query5); 
echo "<div align=\"center\">News Article Updated</div>";
echo '<meta http-equiv="refresh" content="1;url=view.php">'; 
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