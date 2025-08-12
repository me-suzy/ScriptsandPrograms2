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

<div align="center"><div class="bluein-box"><b>Post News Article</b></div>
<?
if ($_GET['a'] == "") {
$datetime = date("l dS of F Y h:i:s A"); 
$cid=rand(1000000, 9999999);
?>

<form method="post" name="news" action="<?=$_SERVER['PHP_SELF'];?>?a=post">
<div class="grey-box">Story Title: <input type="text" size="40" name="title"></div>
<div class="grey-box"><input type="hidden" name="datetime" value="<?=$datetime;?>"><input type="hidden" name="cid" value="<?=$cid;?>">
<?
$query11 = "SELECT emailadd FROM users WHERE username = '$_SESSION[Username]' LIMIT 0,1"; 
$result11 = mysql_query($query11); 
while($r=mysql_fetch_array($result11)) 
{ 
$emailadd=$r["emailadd"]; 
?><input type="hidden" name="eauthor" value="<?=$emailadd;?>"><?
} 
?>

Category: <select name="category" id="category">
<?php 
$query1 = "SELECT category FROM categories"; 
$result1 = mysql_query($query1); 
while($r=mysql_fetch_array($result1)) 
{ 
$cname=$r["category"]; 
echo "<option value=\"$cname\">$cname</option>"; 
} 
?>
 </select></div>
<div class="grey-box">
New Article (short version): <br><textarea rows="6" cols="45" name="shorta"></textarea></div><div class="grey-box">
New Article (full version): <br><textarea rows="12" cols="45" name="longa"></textarea></div><div class="grey-box"><div align="center">
<input type="submit" value="Post News"></div></div></form>
<br></div>
<?
} else { }
if ($_GET['a'] == "post") {
$query = "INSERT INTO news(cid, category, title, author, shorta, longa, datetime, eauthor) 
VALUES('$_POST[cid]','$_POST[category]','$_POST[title]','$_SESSION[Username]','$_POST[shorta]','$_POST[longa]','$_POST[datetime]','$_POST[eauthor]')"; 
mysql_query($query); 
echo "<div align=\"center\">News Article Saved</div>";
echo '<meta http-equiv="refresh" content="1;url=index.php">'; 
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