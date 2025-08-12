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

<div align="center"><div class="bluein-box"><b>Modify Categories</b>, <a href="<?=$_SERVER['PHP_SELF'];?>?a=new">New</a></div>
<?
if ($_GET['a'] == "") { 
$query1 = "SELECT * FROM categories"; 
$result1 = mysql_query($query1); 
while($r=mysql_fetch_array($result1)) 
{ 
$id=$r["id"]; 
$category=$r["category"]; 
$descript=$r["descript"]; 
?>
<table cellpadding="0" cellspacing="0" width="400" style="margin-top: 2px; border: 1px solid gray; padding: 1px;" align="center"><tr><td style="border-bottom: 1px solid #DDDDDD"><b><?=$category;?></b> <a href="<?=$_SERVER['PHP_SELF'];?>?a=delete&id=<?=$id;?>"><img src="images/b_drop.png" border="0"></a>&nbsp;<a href="<?=$_SERVER['PHP_SELF'];?>?a=edit&id=<?=$id;?>"><img src="images/b_edit.png" border="0"></a></td></tr><tr><td><u>Description:</u><br><?=$descript;?></td></tr><tr><td align="right" style="border-top: 1px dashed #DDDDDD;">
<?
$query2="SELECT * FROM news WHERE category = '$category'";
$result2 = mysql_query($query2);
$num_rows2 = mysql_num_rows($result2);
echo "" . $num_rows2 . " News Articles";
?>
</td></tr></table>
<?
} 
} else { }
if ($_GET['a'] == "edit") {
$query2 = "SELECT id, category, descript FROM categories WHERE id = '$_GET[id]'"; 
$result2 = mysql_query($query2); 
while($r=mysql_fetch_array($result2)) 
{ 
$id = $r["id"];  
$category = $r["category"];  
$descript = $r["descript"];  
?><div align="center">
<form method="post" name="news" action="<?=$_SERVER['PHP_SELF'];?>?a=edit&b=post&id=<?=$_GET['id'];?>"><input type="hidden" name="oldcat" size="20" value="<?=$category;?>">
<div class="grey-box">Category: <input type="text" name="category" size="40" value="<?=$category;?>"></div>
<div class="grey-box">
Description: <br><textarea rows="6" cols="45" name="descript"><?=$descript;?></textarea></div><div class="grey-box"><div align="center">
<input type="submit" value="Save Revision"></div></div></form>
<br>
<?
}
if ($_GET['b'] == "post") {
$query5 = "UPDATE categories SET category='$_POST[category]', descript='$_POST[descript]' WHERE id = '$_GET[id]'"; 
mysql_query($query5); 
$query6 = "UPDATE news SET category='$_POST[category]' WHERE category = '$_POST[oldcat]'"; 
mysql_query($query6); 
echo "<div align=\"center\">Category Updated</div>";
echo '<meta http-equiv="refresh" content="1;url=category.php">'; 
} else { }
} else { }
if ($_GET['a'] == "delete") {
$query4 = "DELETE FROM categories WHERE id = '$_GET[id]'"; 
mysql_query($query4); 
echo "<div align=\"center\">Deleted</div>";
echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=category.php\">";
} else { }
if ($_GET['a'] == "new") {
?>
<form method="post" name="category" action="<?=$_SERVER['PHP_SELF'];?>?a=new&b=save">
<div class="grey-box">Category Name: <input type="text" name="category" size="40"></div>
<div class="grey-box">Description:<br><textarea rows="6" cols="45" name="descript"></textarea></div>
<div class="grey-box"><div align="center">
<input type="submit" value="Create Category"></div></div></form>
<?
if ($_GET['b'] == "save") {
$query5 = "INSERT INTO categories (category,descript) VALUES ('$_POST[category]','$_POST[descript]')"; 
mysql_query($query5) or die(mysql_error());
echo "<div align=\"center\">Category Saved</div>";
echo '<meta http-equiv="refresh" content="1;url=category.php">'; 
} else { }
} else { }
echo "</div>";
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