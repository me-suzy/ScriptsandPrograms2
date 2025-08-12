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
if ($_GET['d'] == "") {
?>
<style type='text/css'>
<!--
.bordercontrol {padding: 0px; border-right: 1px solid lightblue; border-top: 1px solid lightblue; border-left: 1px solid lightblue; border-bottom: 1px solid lightblue;}
A.post:hover, A.post:active {background-color: #DDDDDD}
A.post:hover .bordercontrol, A.post:active .bordercontrol {background-color: silver; padding: 0px; border:1px; border-thickness: 1px; border-color: blue; border-style: solid}
-->
</style>
<table cellpadding="0" cellspacing="2" border="0" align="center"><tr><td><a href="accounts.php?d=newa" class="post">
<img class="bordercontrol" src="images/newa.png" border="0"></a></td><td><a href="accounts.php?d=youra" class="post">
<img class="bordercontrol" src="images/youra.png" border="0"></a></td></tr></table>
<? } else { } 
if ($_GET['d'] == "newa") { ?>
<div align="center"><div class="bluein-box"><b>Create new account</b></div><?
$a=$_GET['b'];
if ($a == "skyreg" ) {
$fullname=$_POST['fullname'];
$username=strtolower($_POST['username']);
$password1=$_POST['password1'];
$password2=$_POST['password2'];
$emailadd=$_POST['emailadd'];
if ($fullname == "") {
$fullnamebox="<div class=\"error-box\">";
$fullnamebox1="</div>";
} else { }
if ($username == "") {
$usernamebox="<div class=\"error-box\">";
$usernamebox1="</div>";
} else { }
if ($password1 == "") {
$pword1box="<div class=\"error-box\">";
$pword1box1="</div>";
} else { }
if ($password2 == "") {
$pword2box="<div class=\"error-box\">";
$pword2box1="</div>";
} else { }
if ($emailadd == "") {
$emailaddbox="<div class=\"error-box\">";
$emailaddbox1="</div>";
} else { }
if ($fullname == "" OR $username == "" OR $password1 == "" OR $password2 == "" OR $emailadd == "") {

$errormessage="<font color=\"red\">Please Fill in all Feilds.</font><br>";
} else { 

include "config.php";
$db12 = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query12 = "SELECT username FROM users WHERE username = '$_POST[username]'"; 
$result12 = mysql_query($query12); 
while($r=mysql_fetch_array($result12)) 
{ 
$username12=$r["username"]; 
}
if ($username12 == $username) {
$errormessage3="<font color=\"red\">The username chosen is in use, choose another.</font>";
} else { 


if ($password1 == $password2) {
$fullname=$_POST['fullname'];
$username=strtolower($_POST['username']);
$password=$_POST['password1'];
$emailadd=$_POST['emailadd'];



include "config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO users(username, password, emailadd, fullname) 
VALUES('$username','$password','$emailadd','$fullname')"; 
mysql_query($query); 
echo "Account Created";
echo '<meta http-equiv="refresh" content="1;url=index.php">'; 
mysql_close($db); 

} else {
$errormessage2="<font color=\"red\">Passwords Entered do not Match.</font>";
}

}
mysql_close($db12);
}

} else { }

if ($a != "skyreg" OR $a != "reg") { 
?>
<div align="center"><?=$errormessage;?><?=$errormessage2;?><?=$errormessage3;?><br>
<form method="post" action="accounts.php?d=newa&b=skyreg">
<?=$fullnamebox;?>Full Name:<br><input type="text" name="fullname"><br>&nbsp;<?=$fullnamebox1;?>
<?=$usernamebox;?>Username:<br><input type="text" name="username"><br>&nbsp;<?=$usernamebox1;?>
<?=$pword1box;?>Password:<br><input type="password" name="password1"><br>&nbsp;<?=$pword1box1;?>
<?=$pword2box;?>Retype Password:<br><input type="password" name="password2"><br>&nbsp;<?=$pword2box1;?>
<?=$emailaddbox;?>E-mail Address:<br><input type="text" name="emailadd"><br>&nbsp;<?=$emailaddbox1;?>
<input type="reset" value="Clear Form"> <input type="submit" value="Register">
</form>
</div>
<? 
} else { }
} else { }
if ($_GET['d'] == "youra") {
?>
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
<div class="main2-box">

<div class="side-headline"><b>Account Settings:</b></div>

<br><?
$query = "SELECT id, username, emailadd, fullname FROM users WHERE username='$_SESSION[Username]'"; 
 
$result = mysql_query($query); 
/* Here we fetch the result as an array */ 
while($r=mysql_fetch_array($result)) 
{ 
/* This bit sets our data from each row as variables, to make it easier to display */ 
$id=$r["id"]; 
$username=$r["username"]; 
$emailadd=$r["emailadd"]; 
$fullname=$r["fullname"]; 
// display it all
echo "<b>General</b><br>";
echo "&nbsp;&nbsp;&nbsp;Username: <b>$username</b><br>&nbsp;&nbsp;&nbsp;Email Address: <b>$emailadd</b><br>&nbsp;&nbsp;&nbsp;Fullname: <b>$fullname</b><br>"; 
?>
</div>
<br><br><br></div>
<?
} 
} else { }
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
}
mysql_close($db); 

?> 