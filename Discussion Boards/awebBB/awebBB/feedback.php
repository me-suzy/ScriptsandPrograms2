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

<div class="side-headline"><b>Feedback Form:</b></div>

<br><?
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, username, emailadd, fullname, country, date FROM users WHERE username='$_SESSION[Username]'"; 
 
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
// display it all
echo "<form action='feedback.php?a=1' method='post'>
Your Name:<input type='text' name='name' size='30' value='$fullname'><br>E-mail Address:
<input type='text' name='email' size='30' value='$emailadd'><br>Your Question, Comment or Concern:<br>
<textarea name='question' rows='5' cols='32'></textarea><br>
<input type='reset' value='Reset'>&nbsp;<input type='submit' value='Submit'>"; 
} 
$email=$_GET['a'];
if ($email == 1 ) {
$query4 = "SELECT email FROM prefs"; 
$result4 = mysql_query($query4); 
while($r=mysql_fetch_array($result4)) 
{ 
$email = $r["email"]; 
$name= $_POST['name'];
$mailer= $_POST['email'];
$box= $_POST['question'];
$subject= "Forum Feedback Form";
 mail("$email", "$subject", "From: $mailer", "$name writes: $box");
echo"Feedback Submitted";
    echo "<META HTTP-EQUIV=\"refresh\" content=\"1; URL=accounts.php\">";
}
mysql_close($db);
} else { }
 

echo "<br>";
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
</div><br><br><br><br><br><br><br><br>
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