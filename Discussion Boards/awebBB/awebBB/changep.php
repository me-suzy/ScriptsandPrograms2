<?php
session_start();
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
// connect to database and pull up info
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
mysql_close($db); 
// If they are NOT logged in then show the form to login...
if ($_SESSION['Logged_In'] != "True") {

    echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=login.php?page=" . $_SERVER['PHP_SELF'] . "\">";

}
else
{
include "header.php";
$a=$_GET['a'];
?>
<div class="main2-box">

<div class="side-headline"><b>Change Your Password:</b></div>
<div align="center">
<br>
<? 
if ($a == "change") {
$password1=$_POST['password1'];
$password2=$_POST['password2'];
$oldpass =$_POST['oldpass'];

if ($oldpass == "") {
$oldpassbox="<div class=\"error-box\">";
$oldpassbox1="</div>";
} else { }
if ($password1 == "") {
$pword1box="<div class=\"error-box\">";
$pword1box1="</div>";
} else { }
if ($password2 == "") {
$pword2box="<div class=\"error-box\">";
$pword2box1="</div>";
} else { }
if ($password1 == $password2 & $oldpass != "" & $password1 != "" & $password2 != "") {
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, password FROM users WHERE username='$_SESSION[Username]'"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$password=$r["password"]; 

if ($password == $oldpass) {

// update password
$query12 = "UPDATE users SET password='$_POST[password1]' WHERE username='$_SESSION[Username]'"; 
$result12 = mysql_query($query12); 
echo 'Password Changed';
echo '<meta http-equiv="refresh" content="1;url=accounts.php">';

} else {
echo "Your current password was entered incorrectly.<br>"; 
}
} 
mysql_close($db);
} else {
echo "<font color=\"red\">Please Fill in all Fields</font>";
}
} else { } 
echo "<br>";

?>
<form method="post" action="changep.php?a=change">
<?=$oldpassbox;?>Current Password:<br><input type="password" name="oldpass"><br>&nbsp;<?=$oldpassbox1;?>
<?=$pword1box;?>New Password:<br><input type="password" name="password1"><br>&nbsp;<?=$pword1box1;?>
<?=$pword2box;?>Retype New Password:<br><input type="password" name="password2"><br>&nbsp;<?=$pword2box1;?>
<input type="reset" value="Clear Form"> <input type="submit" value="Change Password">
</form></div>
</div>
<div class="right1">
<div class="side-box">
<div class="side-headline">Options:</div>
<br>
&nbsp;&nbsp;&nbsp;-<a href="changep.php">Change Password</a><br>
&nbsp;&nbsp;&nbsp;-<a href="editac.php">Edit Information</a><br>
&nbsp;&nbsp;&nbsp;-<a href="feedback.php">Feedback</a><br>
&nbsp;&nbsp;&nbsp;-<a href="<?=$_SERVER['PHP_SELF'];?>?mode=logout">Logout</a><br>
&nbsp;
</div><br><br><br><br><br><br><br><br><br><br>
</div>
<?
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
    echo "<META HTTP-EQUIV=\"refresh\" content=\"1; URL=" . $_SERVER['PHP_SELF'] . "\">";
}
}

?> 