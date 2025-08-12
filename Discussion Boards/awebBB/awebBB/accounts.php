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
?>
<div class="main2-box">

<div class="side-headline"><b>Account Settings:</b></div>

<br><?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, username, emailadd, fullname, country, date, sig, avatar FROM users WHERE username='$_SESSION[Username]'"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$username=$r["username"]; 
$emailadd=$r["emailadd"]; 
$fullname=$r["fullname"]; 
$country=$r["country"]; 
$date=$r["date"]; 
$sig=$r["sig"]; 
$avatar=$r["avatar"]; 
// display it all
echo "<b>General</b><br>";
echo "&nbsp;&nbsp;&nbsp;Username: <b>$username</b><br>&nbsp;&nbsp;&nbsp;Email Address: <b>$emailadd</b><br>&nbsp;&nbsp;&nbsp;Fullname: <b>$fullname</b><br>&nbsp;&nbsp;&nbsp;Country: <b>$country</b><br>&nbsp;&nbsp;&nbsp;Date you signed up: <b>$date</b><br>"; 
echo "<br><b>Forum</b><br>";
echo "&nbsp;&nbsp;&nbsp;Forum Signature: <b>$sig</b><br>&nbsp;&nbsp;&nbsp;Avatar URL: <b>$avatar</b><br>";
$query2="SELECT * FROM forum WHERE poster = '$_SESSION[Username]'";
$result2 = mysql_query($query2);
 $num_rows2 = mysql_num_rows($result2);
$presum=$num_rows2;
echo "&nbsp;&nbsp;&nbsp;Number of Posts: <b><a href=\"dpost.php?p=" . $_SESSION['Username'] . "\">$presum</a></b><br>"; 
?>
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
</div>
<div class="side-box">
<div class="side-headline">Avatar:</div>
Scaled to 80 x 80<br>
<img src="<?=$avatar;?>" width="80" height="80" align="center"><br>&nbsp;
</div>
</div>
<?
} 
mysql_close($db); 
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